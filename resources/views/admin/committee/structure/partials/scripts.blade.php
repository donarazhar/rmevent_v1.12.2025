
<script>
    function committeeStructureManager() {
        return {
            // State
            showModal: false,
            modalMode: 'add', // 'add' or 'edit'
            loading: false,
            editingId: null,
            
            // Filters
            filters: {
                eventId: '{{ $eventId ?? '' }}',
                search: ''
            },
    
            // Form Data
            formData: {
                event_id: '',
                parent_id: '',
                name: '',
                code: '',
                description: '',
                level: 1,
                order: '',
                leader_id: '',
                vice_leader_id: '',
                email: '',
                phone: '',
                status: 'active',
                responsibilities: [],
                authorities: []
            },
    
            availableParents: [],
    
            // Initialize
            init() {
                this.loadAvailableParents();
                this.setupEventListeners();
            },
    
            // Setup event listeners
            setupEventListeners() {
                // Listen for add child structure event
                this.$el.addEventListener('add-child-structure', (e) => {
                    this.openModal('add', e.detail.parent);
                });
    
                // Listen for delete structure event
                this.$el.addEventListener('delete-structure', (e) => {
                    this.deleteStructure(e.detail.id, e.detail.name);
                });
            },
    
            // Load available parent structures
            async loadAvailableParents() {
                try {
                    // Untuk sekarang, gunakan data yang sudah ada di page
                    // Atau bisa fetch via AJAX jika diperlukan
                    this.availableParents = @json($structures ?? []);
                } catch (error) {
                    console.error('Error loading parents:', error);
                }
            },
    
            // Open modal
            openModal(mode = 'add', parentId = null) {
                this.modalMode = mode;
                this.showModal = true;
                
                if (mode === 'add') {
                    this.resetForm();
                    if (parentId) {
                        this.formData.parent_id = parentId;
                    }
                    if (this.filters.eventId) {
                        this.formData.event_id = this.filters.eventId;
                    }
                }
            },
    
            // Close modal
            closeModal() {
                this.showModal = false;
                this.resetForm();
            },
    
            // Reset form
            resetForm() {
                this.formData = {
                    event_id: this.filters.eventId || '',
                    parent_id: '',
                    name: '',
                    code: '',
                    description: '',
                    level: 1,
                    order: '',
                    leader_id: '',
                    vice_leader_id: '',
                    email: '',
                    phone: '',
                    status: 'active',
                    responsibilities: [],
                    authorities: []
                };
                this.editingId = null;
            },
    
            // Submit form
            async submitForm() {
                if (this.loading) return;
                
                this.loading = true;
                
                // Clean empty items from arrays
                this.formData.responsibilities = this.formData.responsibilities.filter(r => r.trim());
                this.formData.authorities = this.formData.authorities.filter(a => a.trim());
    
                try {
                    const url = this.modalMode === 'add' 
                        ? '{{ route('admin.committee.structure.store') }}'
                        : `{{ route('admin.committee.structure.index') }}/${this.editingId}`;
                    
                    const method = this.modalMode === 'add' ? 'POST' : 'PUT';
    
                    const response = await fetch(url, {
                        method: method,
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(this.formData)
                    });
    
                    const data = await response.json();
    
                    if (data.success) {
                        this.showNotification('success', data.message);
                        this.closeModal();
                        setTimeout(() => window.location.reload(), 1000);
                    } else {
                        this.showNotification('error', data.message || 'Terjadi kesalahan');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    this.showNotification('error', 'Gagal menyimpan data');
                } finally {
                    this.loading = false;
                }
            },
    
            // Delete structure
            async deleteStructure(id, name) {
                if (!confirm(`Apakah Anda yakin ingin menghapus struktur "${name}"?\n\nPeringatan: Semua data terkait akan ikut terhapus.`)) {
                    return;
                }
    
                try {
                    const response = await fetch(`{{ route('admin.committee.structure.index') }}/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });
    
                    const data = await response.json();
    
                    if (data.success) {
                        this.showNotification('success', data.message);
                        setTimeout(() => window.location.reload(), 1000);
                    } else {
                        this.showNotification('error', data.message || 'Gagal menghapus struktur');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    this.showNotification('error', 'Terjadi kesalahan saat menghapus');
                }
            },
    
            // Filter structures
            filterStructures() {
                const params = new URLSearchParams();
                if (this.filters.eventId) params.append('event_id', this.filters.eventId);
                if (this.filters.search) params.append('search', this.filters.search);
                
                window.location.href = `{{ route('admin.committee.structure.index') }}?${params.toString()}`;
            },
    
            // Export structure
            async exportStructure() {
                try {
                    const params = new URLSearchParams();
                    if (this.filters.eventId) params.append('event_id', this.filters.eventId);
                    
                    window.location.href = `{{ route('admin.committee.structure.export') }}?${params.toString()}`;
                } catch (error) {
                    console.error('Error:', error);
                    this.showNotification('error', 'Gagal mengekspor data');
                }
            },
    
            // Show notification
            showNotification(type, message) {
                // Simple alert for now, you can integrate with a toast library
                if (type === 'success') {
                    alert('✓ ' + message);
                } else {
                    alert('✗ ' + message);
                }
            }
        }
    }
    </script>