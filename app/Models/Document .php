<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'folder_id',
        'event_id',
        'document_code',
        'title',
        'description',
        'category',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'mime_type',
        'version',
        'parent_document_id',
        'version_notes',
        'visibility',
        'shared_with_users',
        'shared_with_structures',
        'allow_download',
        'allow_print',
        'tags',
        'custom_fields',
        'status',
        'document_date',
        'expiry_date',
        'uploaded_by',
        'view_count',
        'download_count',
        'last_viewed_at',
        'notes',
    ];

    protected $casts = [
        'shared_with_users' => 'array',
        'shared_with_structures' => 'array',
        'tags' => 'array',
        'custom_fields' => 'array',
        'allow_download' => 'boolean',
        'allow_print' => 'boolean',
        'document_date' => 'date',
        'expiry_date' => 'date',
        'last_viewed_at' => 'datetime',
        'view_count' => 'integer',
        'download_count' => 'integer',
        'file_size' => 'integer',
    ];

    protected $appends = [
        'file_url',
        'file_size_human',
        'is_expired',
    ];

    // Constants for enums
    public const CATEGORY_PROPOSAL = 'proposal';
    public const CATEGORY_REPORT = 'report';
    public const CATEGORY_MEETING_NOTES = 'meeting_notes';
    public const CATEGORY_CONTRACT = 'contract';
    public const CATEGORY_LETTER = 'letter';
    public const CATEGORY_CERTIFICATE = 'certificate';
    public const CATEGORY_PRESENTATION = 'presentation';
    public const CATEGORY_PHOTO = 'photo';
    public const CATEGORY_VIDEO = 'video';
    public const CATEGORY_OTHER = 'other';

    public const VISIBILITY_PUBLIC = 'public';
    public const VISIBILITY_PRIVATE = 'private';
    public const VISIBILITY_RESTRICTED = 'restricted';

    public const STATUS_DRAFT = 'draft';
    public const STATUS_FINAL = 'final';
    public const STATUS_ARCHIVED = 'archived';

    /**
     * Relationships
     */
    public function folder(): BelongsTo
    {
        return $this->belongsTo(DocumentFolder::class, 'folder_id');
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function parentDocument(): BelongsTo
    {
        return $this->belongsTo(Document::class, 'parent_document_id');
    }

    public function versions(): HasMany
    {
        return $this->hasMany(Document::class, 'parent_document_id');
    }

    /**
     * Accessors
     */
    public function getFileUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }

    public function getFileSizeHumanAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    /**
     * Scopes
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopePublic($query)
    {
        return $query->where('visibility', self::VISIBILITY_PUBLIC);
    }

    public function scopeVisibleTo($query, int $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('visibility', self::VISIBILITY_PUBLIC)
              ->orWhere('uploaded_by', $userId)
              ->orWhereJsonContains('shared_with_users', $userId);
        });
    }

    public function scopeNotExpired($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expiry_date')
              ->orWhere('expiry_date', '>=', now());
        });
    }

    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('document_code', 'like', "%{$search}%");
        });
    }

    public function scopeByFolder($query, int $folderId)
    {
        return $query->where('folder_id', $folderId);
    }

    public function scopeByEvent($query, int $eventId)
    {
        return $query->where('event_id', $eventId);
    }

    /**
     * Helper Methods
     */
    public function incrementViewCount(): void
    {
        $this->increment('view_count');
        $this->update(['last_viewed_at' => now()]);
    }

    public function incrementDownloadCount(): void
    {
        $this->increment('download_count');
    }

    public function canBeViewedBy(User $user): bool
    {
        if ($this->visibility === self::VISIBILITY_PUBLIC) {
            return true;
        }

        if ($this->uploaded_by === $user->id) {
            return true;
        }

        if ($this->shared_with_users && in_array($user->id, $this->shared_with_users)) {
            return true;
        }

        // Check structure permissions
        if ($this->shared_with_structures && $user->structure_id) {
            if (in_array($user->structure_id, $this->shared_with_structures)) {
                return true;
            }
        }

        return false;
    }

    public function canBeDownloadedBy(User $user): bool
    {
        return $this->allow_download && $this->canBeViewedBy($user);
    }

    public function canBePrintedBy(User $user): bool
    {
        return $this->allow_print && $this->canBeViewedBy($user);
    }

    public function shareWithUser(int $userId): void
    {
        $sharedUsers = $this->shared_with_users ?? [];
        
        if (!in_array($userId, $sharedUsers)) {
            $sharedUsers[] = $userId;
            $this->update(['shared_with_users' => $sharedUsers]);
        }
    }

    public function unshareWithUser(int $userId): void
    {
        $sharedUsers = $this->shared_with_users ?? [];
        
        $sharedUsers = array_filter($sharedUsers, fn($id) => $id !== $userId);
        
        $this->update(['shared_with_users' => array_values($sharedUsers)]);
    }

    public function createNewVersion(array $data): Document
    {
        $newVersion = $this->replicate();
        $newVersion->parent_document_id = $this->id;
        $newVersion->version = $this->incrementVersion();
        $newVersion->document_code = $data['document_code'] ?? $this->generateDocumentCode();
        $newVersion->file_name = $data['file_name'];
        $newVersion->file_path = $data['file_path'];
        $newVersion->file_type = $data['file_type'];
        $newVersion->file_size = $data['file_size'];
        $newVersion->mime_type = $data['mime_type'];
        $newVersion->version_notes = $data['version_notes'] ?? null;
        $newVersion->uploaded_by = $data['uploaded_by'];
        $newVersion->save();

        return $newVersion;
    }

    protected function incrementVersion(): string
    {
        $parts = explode('.', $this->version);
        $parts[1] = (int)$parts[1] + 1;
        
        return implode('.', $parts);
    }

    public static function generateDocumentCode(): string
    {
        $latestDocument = static::latest('id')->first();
        $number = $latestDocument ? ($latestDocument->id + 1) : 1;
        
        return 'DOC-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }

    public function addTag(string $tag): void
    {
        $tags = $this->tags ?? [];
        
        if (!in_array($tag, $tags)) {
            $tags[] = $tag;
            $this->update(['tags' => $tags]);
        }
    }

    public function removeTag(string $tag): void
    {
        $tags = $this->tags ?? [];
        
        $tags = array_filter($tags, fn($t) => $t !== $tag);
        
        $this->update(['tags' => array_values($tags)]);
    }

    public function isImage(): bool
    {
        return in_array($this->file_type, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
    }

    public function isVideo(): bool
    {
        return in_array($this->file_type, ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm']);
    }

    public function isPdf(): bool
    {
        return $this->file_type === 'pdf';
    }

    public function isDocument(): bool
    {
        return in_array($this->file_type, ['doc', 'docx', 'odt', 'txt']);
    }

    public function isSpreadsheet(): bool
    {
        return in_array($this->file_type, ['xls', 'xlsx', 'ods', 'csv']);
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($document) {
            if (empty($document->document_code)) {
                $document->document_code = static::generateDocumentCode();
            }
        });

        static::deleting(function ($document) {
            // Delete file from storage when document is deleted
            if (Storage::exists($document->file_path)) {
                Storage::delete($document->file_path);
            }
        });
    }
}