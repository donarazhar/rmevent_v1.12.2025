<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\DocumentFolder;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DocumentSeeder extends Seeder
{
    public function run(): void
    {
        // Create sample folders
        $folders = [
            [
                'name' => 'Proposal',
                'description' => 'Folder untuk menyimpan proposal kegiatan',
                'path' => '/Proposal',
                'color' => '#8B5CF6',
                'icon' => 'document-text',
            ],
            [
                'name' => 'Laporan',
                'description' => 'Folder untuk menyimpan laporan kegiatan',
                'path' => '/Laporan',
                'color' => '#3B82F6',
                'icon' => 'clipboard-list',
            ],
            [
                'name' => 'Notulen',
                'description' => 'Folder untuk menyimpan notulen rapat',
                'path' => '/Notulen',
                'color' => '#10B981',
                'icon' => 'pencil-alt',
            ],
            [
                'name' => 'Sertifikat',
                'description' => 'Folder untuk menyimpan sertifikat',
                'path' => '/Sertifikat',
                'color' => '#F59E0B',
                'icon' => 'badge-check',
            ],
        ];

        $createdFolders = [];
        $user = User::first();

        foreach ($folders as $folderData) {
            $createdFolders[] = DocumentFolder::create([
                ...$folderData,
                'level' => 1,
                'visibility' => 'public',
                'created_by' => $user->id,
            ]);
        }

        // Create sample documents
        $documents = [
            [
                'title' => 'Proposal Kegiatan Ramadhan 1447H',
                'description' => 'Proposal lengkap untuk kegiatan Ramadhan tahun ini',
                'category' => 'proposal',
                'folder_id' => $createdFolders[0]->id,
                'file_type' => 'pdf',
                'status' => 'final',
            ],
            [
                'title' => 'Laporan Pertanggungjawaban',
                'description' => 'LPJ kegiatan bulan lalu',
                'category' => 'report',
                'folder_id' => $createdFolders[1]->id,
                'file_type' => 'docx',
                'status' => 'final',
            ],
            [
                'title' => 'Notulen Rapat Koordinasi',
                'description' => 'Hasil rapat koordinasi panitia',
                'category' => 'meeting_notes',
                'folder_id' => $createdFolders[2]->id,
                'file_type' => 'pdf',
                'status' => 'draft',
            ],
            [
                'title' => 'Sertifikat Peserta',
                'description' => 'Template sertifikat untuk peserta',
                'category' => 'certificate',
                'folder_id' => $createdFolders[3]->id,
                'file_type' => 'pdf',
                'status' => 'final',
            ],
            [
                'title' => 'Kontrak Kerjasama Vendor',
                'description' => 'Kontrak kerjasama dengan vendor catering',
                'category' => 'contract',
                'folder_id' => null,
                'file_type' => 'pdf',
                'status' => 'final',
            ],
            [
                'title' => 'Foto Dokumentasi Kegiatan',
                'description' => 'Dokumentasi kegiatan minggu lalu',
                'category' => 'photo',
                'folder_id' => null,
                'file_type' => 'jpg',
                'status' => 'final',
            ],
        ];

        foreach ($documents as $index => $docData) {
            // Generate document code
            $docCode = 'DOC-' . str_pad($index + 1, 6, '0', STR_PAD_LEFT);

            // Create dummy file path
            $fileName = $docData['title'] . '.' . $docData['file_type'];
            $filePath = 'documents/' . time() . '_' . $fileName;

            Document::create([
                'document_code' => $docCode, // Tambahkan ini
                'title' => $docData['title'],
                'description' => $docData['description'],
                'category' => $docData['category'],
                'folder_id' => $docData['folder_id'],
                'file_name' => $fileName,
                'file_path' => $filePath,
                'file_type' => $docData['file_type'],
                'file_size' => rand(100000, 5000000), // Random size 100KB - 5MB
                'mime_type' => $this->getMimeType($docData['file_type']),
                'version' => '1.0',
                'visibility' => 'public',
                'status' => $docData['status'],
                'allow_download' => true,
                'allow_print' => true,
                'uploaded_by' => $user->id,
                'tags' => ['ramadhan', '1447h', 'kegiatan'],
                'document_date' => now()->subDays(rand(1, 30)),
                'view_count' => rand(5, 100),
                'download_count' => rand(0, 50),
            ]);
        }

        $this->command->info('✅ Document seeder completed successfully!');
        $this->command->info('📁 Created ' . count($createdFolders) . ' folders');
        $this->command->info('📄 Created ' . count($documents) . ' documents');
    }

    /**
     * Get MIME type based on file extension
     */
    private function getMimeType(string $extension): string
    {
        $mimeTypes = [
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'ppt' => 'application/vnd.ms-powerpoint',
            'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'mp4' => 'video/mp4',
            'avi' => 'video/x-msvideo',
            'txt' => 'text/plain',
            'csv' => 'text/csv',
        ];

        return $mimeTypes[$extension] ?? 'application/octet-stream';
    }
}
