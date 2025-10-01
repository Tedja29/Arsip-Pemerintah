
        let currentView = 'table';
        let currentPage = 1;
        let isScanning = false;

        // Toggle sidebar on mobile
        document.getElementById('menuToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });

        // Toggle view between table and grid
        function toggleView(view) {
            currentView = view;
            const tableView = document.getElementById('tableView');
            const gridView = document.getElementById('gridView');
            const buttons = document.querySelectorAll('.view-toggle button');
            
            buttons.forEach(btn => btn.classList.remove('active'));
            
            if (view === 'table') {
                tableView.style.display = 'block';
                gridView.classList.remove('active');
                buttons[0].classList.add('active');
            } else {
                tableView.style.display = 'none';
                gridView.classList.add('active');
                buttons[1].classList.add('active');
            }
        }

        // Folder scan functionality
        const scanFolderDropzone = document.getElementById('scanFolderDropzone');
        const folderInput = document.getElementById('folderInput');
        const scanProgress = document.getElementById('scanProgress');
        const progressFill = document.getElementById('progressFill');
        const progressText = document.getElementById('progressText');
        const scanResults = document.getElementById('scanResults');
        const scanResultsList = document.getElementById('scanResultsList');

        // Click to select folder
        scanFolderDropzone.addEventListener('click', function() {
            folderInput.click();
        });

        // Drag and drop functionality
        scanFolderDropzone.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('active');
        });

        scanFolderDropzone.addEventListener('dragleave', function() {
            this.classList.remove('active');
        });

        scanFolderDropzone.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('active');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                startScanProcess(files);
            }
        });

        // Folder input change
        folderInput.addEventListener('change', function(e) {
            if (this.files.length > 0) {
                startScanProcess(this.files);
            }
        });

        // Start folder scan
        function startFolderScan() {
            folderInput.click();
        }

        // Simulate AI image-to-text scanning process
        function startScanProcess(files) {
            if (isScanning) return;
            
            isScanning = true;
            scanProgress.style.display = 'block';
            scanResults.style.display = 'none';
            scanResultsList.innerHTML = '';
            
            let progress = 0;
            const totalFiles = Math.min(files.length, 10); // Limit for demo
            
            // Simulate scanning progress
            const interval = setInterval(() => {
                progress += 10;
                progressFill.style.width = `${progress}%`;
                
                if (progress <= 30) {
                    progressText.textContent = 'Mengidentifikasi dokumen...';
                } else if (progress <= 60) {
                    progressText.textContent = 'Memproses teks dengan AI...';
                } else if (progress <= 90) {
                    progressText.textContent = 'Menganalisis konten...';
                } else {
                    progressText.textContent = 'Menyelesaikan...';
                }
                
                if (progress >= 100) {
                    clearInterval(interval);
                    simulateScanResults(totalFiles);
                    isScanning = false;
                }
            }, 300);
        }

        // Simulate scan results with AI-extracted data
        function simulateScanResults(fileCount) {
            scanProgress.style.display = 'none';
            scanResults.style.display = 'block';
            
            // Sample data that AI would extract
            const sampleData = [
                {
                    name: 'Surat Edaran No. 001',
                    suratDari: 'Kementerian Dalam Negeri',
                    tanggalSurat: '2024-01-15',
                    nomorSurat: '001/KD/IX/2024',
                    perihal: 'Petunjuk Teknis Pelaksanaan Program',
                    diterimaTanggal: '2024-01-18',
                    kepada: 'sekretaris',
                    nomorAgenda: 'AG-2024-001',
                    disposisi: 'tindak-lanjut'
                },
                {
                    name: 'Laporan Keuangan Triwulan I',
                    suratDari: 'Badan Pengelola Keuangan',
                    tanggalSurat: '2024-03-31',
                    nomorSurat: 'BPK/045/III/2024',
                    perihal: 'Laporan Realisasi Anggaran Triwulan I',
                    diterimaTanggal: '2024-04-05',
                    kepada: 'kabag',
                    nomorAgenda: 'AG-2024-045',
                    disposisi: 'arsip'
                },
                {
                    name: 'Undangan Rapat Koordinasi',
                    suratDari: 'Sekretariat Daerah',
                    tanggalSurat: '2024-02-10',
                    nomorSurat: '005/SEKDA/II/2024',
                    perihal: 'Undangan Rapat Koordinasi Bulanan',
                    diterimaTanggal: '2024-02-12',
                    kepada: 'kasubag',
                    nomorAgenda: 'AG-2024-028',
                    disposisi: 'koodinasi'
                }
            ];
            
            // Display results
            for (let i = 0; i < Math.min(fileCount, sampleData.length); i++) {
                const data = sampleData[i];
                const resultItem = document.createElement('div');
                resultItem.className = 'scan-result-item';
                resultItem.innerHTML = `
                    <div class="scan-result-info">
                        <div class="scan-result-icon">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <div>
                            <div class="scan-result-name">${data.name}</div>
                            <div style="font-size: 0.8rem; color: var(--neutral);">${data.perihal}</div>
                        </div>
                    </div>
                    <div class="scan-result-status">Berhasil dipindai</div>
                `;
                
                // Add click to auto-fill form
                resultItem.addEventListener('click', function() {
                    autoFillForm(data);
                });
                
                scanResultsList.appendChild(resultItem);
            }
            
            // Show notification
            showNotification(`${Math.min(fileCount, sampleData.length)} dokumen berhasil dipindai`, 'success');
        }

        // Auto-fill form with scanned data
        function autoFillForm(data) {
            document.getElementById('suratDari').value = data.suratDari;
            document.getElementById('tanggalSurat').value = data.tanggalSurat;
            document.getElementById('nomorSurat').value = data.nomorSurat;
            document.getElementById('perihal').value = data.perihal;
            document.getElementById('diterimaTanggal').value = data.diterimaTanggal;
            document.getElementById('kepada').value = data.kepada;
            document.getElementById('nomorAgenda').value = data.nomorAgenda;
            document.getElementById('disposisi').value = data.disposisi;
            
            // Open the modal
            openModal('addArchiveModal');
            
            // Show notification
            showNotification('Form berhasil diisi otomatis dengan data hasil scan', 'success');
        }

        // Apply filters
        function applyFilters() {
            const category = document.getElementById('categoryFilter').value;
            const status = document.getElementById('statusFilter').value;
            const dateFrom = document.getElementById('dateFromFilter').value;
            const dateTo = document.getElementById('dateToFilter').value;
            
            const tableRows = document.querySelectorAll('#archiveTableBody tr');
            const gridCards = document.querySelectorAll('.archive-grid .archive-card');
            
            // Filter table rows
            tableRows.forEach(row => {
                const rowCategory = row.getAttribute('data-category');
                const rowStatus = row.getAttribute('data-status');
                const rowDate = row.getAttribute('data-date');
                
                let show = true;
                
                if (category && rowCategory !== category) show = false;
                if (status && rowStatus !== status) show = false;
                if (dateFrom && rowDate < dateFrom) show = false;
                if (dateTo && rowDate > dateTo) show = false;
                
                row.style.display = show ? '' : 'none';
            });
            
            // Filter grid cards
            gridCards.forEach(card => {
                const cardCategory = card.getAttribute('data-category');
                const cardStatus = card.getAttribute('data-status');
                const cardDate = card.getAttribute('data-date');
                
                let show = true;
                
                if (category && cardCategory !== category) show = false;
                if (status && cardStatus !== status) show = false;
                if (dateFrom && cardDate < dateFrom) show = false;
                if (dateTo && cardDate > dateTo) show = false;
                
                card.style.display = show ? '' : 'none';
            });
        }

        // Clear all filters
        function clearFilters() {
            document.getElementById('categoryFilter').value = '';
            document.getElementById('statusFilter').value = '';
            document.getElementById('dateFromFilter').value = '';
            document.getElementById('dateToFilter').value = '';
            document.getElementById('globalSearch').value = '';
            
            // Show all items
            const tableRows = document.querySelectorAll('#archiveTableBody tr');
            const gridCards = document.querySelectorAll('.archive-grid .archive-card');
            
            tableRows.forEach(row => row.style.display = '');
            gridCards.forEach(card => card.style.display = '');
        }

        // Global search functionality
        document.getElementById('globalSearch').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const tableRows = document.querySelectorAll('#archiveTableBody tr');
            const gridCards = document.querySelectorAll('.archive-grid .archive-card');
            
            // Search in table
            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchTerm) ? '' : 'none';
            });
            
            // Search in grid
            gridCards.forEach(card => {
                const text = card.textContent.toLowerCase();
                card.style.display = text.includes(searchTerm) ? '' : 'none';
            });
        });

        // Select all functionality
        function toggleSelectAll() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.archive-checkbox');
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
        }

        // Archive actions
        function viewArchive(id) {
            const archiveData = {
                1: {
                    name: 'Laporan Keuangan Q4 2023',
                    category: 'Keuangan',
                    date: '15 Jan 2024',
                    size: '2.4 MB',
                    status: 'Aktif',
                    description: 'Laporan keuangan komprehensif untuk kuartal keempat tahun 2023, mencakup analisis pendapatan, pengeluaran, dan proyeksi keuangan.'
                },
                2: {
                    name: 'Notulensi Rapat Koordinasi',
                    category: 'Administrasi',
                    date: '12 Jan 2024',
                    size: '856 KB',
                    status: 'Draft',
                    description: 'Catatan lengkap dari rapat koordinasi bulanan yang membahas program kerja dan evaluasi kinerja.'
                },
                // Add more data as needed
            };
            
            const archive = archiveData[id];
            if (archive) {
                document.getElementById('archiveDetails').innerHTML = `
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px; padding: 20px; background: var(--section-bg); border-radius: 12px;">
                        <div class="file-icon" style="width: 60px; height: 60px; font-size: 1.8rem;">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <div>
                            <h4 style="margin-bottom: 5px; color: var(--primary);">${archive.name}</h4>
                            <p style="margin: 0; color: var(--neutral); font-size: 0.9rem;">${archive.description}</p>
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 25px;">
                        <div>
                            <label style="font-weight: 500; color: var(--dark); display: block; margin-bottom: 5px;">Kategori:</label>
                            <span class="status-badge" style="background: rgba(72, 187, 120, 0.1); color: #48BB78;">${archive.category}</span>
                        </div>
                        <div>
                            <label style="font-weight: 500; color: var(--dark); display: block; margin-bottom: 5px;">Status:</label>
                            <span class="status-badge status-${archive.status.toLowerCase()}">${archive.status}</span>
                        </div>
                        <div>
                            <label style="font-weight: 500; color: var(--dark); display: block; margin-bottom: 5px;">Tanggal Upload:</label>
                            <span>${archive.date}</span>
                        </div>
                        <div>
                            <label style="font-weight: 500; color: var(--dark); display: block; margin-bottom: 5px;">Ukuran File:</label>
                            <span>${archive.size}</span>
                        </div>
                    </div>
                    <div style="display: flex; gap: 10px; justify-content: flex-end;">
                        <button class="btn btn-secondary" onclick="downloadArchive(${id})">
                            <i class="fas fa-download"></i> Download
                        </button>
                        <button class="btn btn-primary" onclick="editArchive(${id})">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                    </div>
                `;
                openModal('viewArchiveModal');
            }
        }

        function downloadArchive(id) {
            // Simulate download
            const link = document.createElement('a');
            link.href = '#';
            link.download = `archive_${id}.pdf`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            // Show success message
            showNotification('File berhasil didownload!', 'success');
        }

        function editArchive(id) {
            // Close view modal if open
            closeModal('viewArchiveModal');
            // Open edit form (you can populate with existing data)
            openModal('addArchiveModal');
            // Change modal title for editing
            document.querySelector('#addArchiveModal .modal-title').textContent = 'Edit Arsip';
        }

        function deleteArchive(id) {
            if (confirm('Apakah Anda yakin ingin menghapus arsip ini?')) {
                // Simulate deletion
                showNotification('Arsip berhasil dihapus!', 'success');
                // Remove from DOM or refresh data
            }
        }

        function exportArchive() {
            showNotification('Export arsip sedang diproses...', 'info');
        }

        // Modal functions
        function openModal(modalId) {
            document.getElementById(modalId).classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
            document.body.style.overflow = 'auto';
            
            // Reset form if it's add archive modal
            if (modalId === 'addArchiveModal') {
                document.getElementById('addArchiveForm').reset();
                document.querySelector('#addArchiveModal .modal-title').textContent = 'Tambah Arsip Baru';
            }
        }

        // Form submission
        document.getElementById('addArchiveForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = {
                suratDari: document.getElementById('suratDari').value,
                tanggalSurat: document.getElementById('tanggalSurat').value,
                nomorSurat: document.getElementById('nomorSurat').value,
                perihal: document.getElementById('perihal').value,
                diterimaTanggal: document.getElementById('diterimaTanggal').value,
                kepada: document.getElementById('kepada').value,
                nomorAgenda: document.getElementById('nomorAgenda').value,
                disposisi: document.getElementById('disposisi').value,
                fileSurat: document.getElementById('fileSurat').files[0]?.name || 'Tidak ada file',
                fileDisposisi: document.getElementById('fileDisposisi').files[0]?.name || 'Tidak ada file'
            };
            
            // Validasi form
            if (!formData.suratDari || !formData.tanggalSurat || !formData.nomorSurat || 
                !formData.perihal || !formData.diterimaTanggal || !formData.kepada || 
                !formData.nomorAgenda || !formData.disposisi) {
                showNotification('Harap lengkapi semua field yang wajib diisi!', 'error');
                return;
            }
            
            // Simulate form submission
            console.log('Data yang disimpan:', formData);
            showNotification(`Arsip "${formData.perihal}" berhasil disimpan!`, 'success');
            closeModal('addArchiveModal');
            
            // Reset form
            this.reset();
        });

        // Enhanced file upload functionality
        document.querySelectorAll('.file-upload-container input[type="file"]').forEach(input => {
            input.addEventListener('change', function(e) {
                const container = this.closest('.file-upload-container');
                const label = container.querySelector('.file-upload-label');
                const files = this.files;
                
                if (files.length > 0) {
                    const fileName = files[0].name;
                    const fileSize = (files[0].size / 1024 / 1024).toFixed(2); // Convert to MB
                    
                    label.innerHTML = `
                        <i class="fas fa-file" style="color: var(--success);"></i>
                        <div>
                            <div style="font-weight: 500; color: var(--dark);">${fileName}</div>
                            <div style="font-size: 0.8rem; color: var(--neutral);">${fileSize} MB</div>
                        </div>
                    `;
                    container.style.borderColor = 'var(--success)';
                    container.style.backgroundColor = 'rgba(72, 187, 120, 0.05)';
                } else {
                    label.innerHTML = `
                        <i class="fas fa-cloud-upload-alt"></i>
                        <span>Choose files or drag and drop files to upload</span>
                    `;
                    container.style.borderColor = 'var(--border-color)';
                    container.style.backgroundColor = 'var(--section-bg)';
                }
            });
        });

        // Drag and drop functionality for file upload
        document.querySelectorAll('.file-upload-container').forEach(container => {
            container.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.style.borderColor = 'var(--primary)';
                this.style.backgroundColor = 'rgba(48, 144, 254, 0.1)';
            });
            
            container.addEventListener('dragleave', function(e) {
                e.preventDefault();
                this.style.borderColor = 'var(--border-color)';
                this.style.backgroundColor = 'var(--section-bg)';
            });
            
            container.addEventListener('drop', function(e) {
                e.preventDefault();
                const input = this.querySelector('input[type="file"]');
                input.files = e.dataTransfer.files;
                
                // Trigger change event
                const event = new Event('change', { bubbles: true });
                input.dispatchEvent(event);
            });
        });

        // Pagination
        function changePage(page) {
            const buttons = document.querySelectorAll('.pagination button');
            
            if (page === 'prev' && currentPage > 1) {
                currentPage--;
            } else if (page === 'next' && currentPage < 10) {
                currentPage++;
            } else if (typeof page === 'number') {
                currentPage = page;
            }
            
            // Update active button
            buttons.forEach(btn => btn.classList.remove('active'));
            if (typeof page === 'number') {
                buttons[page].classList.add('active');
            }
            
            // Update button states
            document.getElementById('prevBtn').disabled = currentPage === 1;
            document.getElementById('nextBtn').disabled = currentPage === 10;
            
            // Simulate loading new data
            showNotification(`Memuat halaman ${currentPage}...`, 'info');
        }

        // Notification system
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                background: ${type === 'success' ? 'var(--success)' : type === 'error' ? 'var(--danger)' : 'var(--info)'};
                color: white;
                padding: 15px 20px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                z-index: 10000;
                animation: slideIn 0.3s ease;
                max-width: 300px;
                font-size: 0.9rem;
            `;
            
            notification.innerHTML = `
                <div style="display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }

        // Close modals when clicking outside
        window.addEventListener('click', function(e) {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                if (e.target === modal) {
                    closeModal(modal.id);
                }
            });
        });

        // Animation styles
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideIn {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            
            @keyframes slideOut {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
        `;
        document.head.appendChild(style);

        // Initialize page with sample data
        document.addEventListener('DOMContentLoaded', function() {
            // Populate archive table with sample data
            const sampleArchives = [
                {
                    id: 1,
                    name: 'Laporan Keuangan Q4 2023',
                    filename: 'laporan_keuangan_q4.pdf',
                    category: 'keuangan',
                    date: '2024-01-15',
                    size: '2.4 MB',
                    status: 'aktif',
                    icon: 'file-pdf',
                    iconColor: '#F56565'
                },
                {
                    id: 2,
                    name: 'Notulensi Rapat Koordinasi',
                    filename: 'notulensi_rapat.docx',
                    category: 'administrasi',
                    date: '2024-01-12',
                    size: '856 KB',
                    status: 'draft',
                    icon: 'file-word',
                    iconColor: '#4299E1'
                },
                {
                    id: 3,
                    name: 'Proposal Kegiatan Pembangunan',
                    filename: 'proposal_pembangunan.pptx',
                    category: 'kegiatan',
                    date: '2024-01-10',
                    size: '4.7 MB',
                    status: 'aktif',
                    icon: 'file-powerpoint',
                    iconColor: '#ECC94B'
                },
                {
                    id: 4,
                    name: 'Data Kepegawaian 2024',
                    filename: 'data_pegawai.xlsx',
                    category: 'kepegawaian',
                    date: '2024-01-08',
                    size: '1.2 MB',
                    status: 'aktif',
                    icon: 'file-excel',
                    iconColor: '#48BB78'
                },
                {
                    id: 5,
                    name: 'Surat Edaran Mendagri',
                    filename: 'surat_edaran_001.pdf',
                    category: 'surat-masuk',
                    date: '2024-01-05',
                    size: '673 KB',
                    status: 'arsip',
                    icon: 'file-pdf',
                    iconColor: '#F56565'
                }
            ];

            const tableBody = document.getElementById('archiveTableBody');
            const gridView = document.getElementById('gridView');

            sampleArchives.forEach(archive => {
                // Add to table view
                const tableRow = document.createElement('tr');
                tableRow.setAttribute('data-category', archive.category);
                tableRow.setAttribute('data-status', archive.status);
                tableRow.setAttribute('data-date', archive.date);
                
                tableRow.innerHTML = `
                    <td><input type="checkbox" class="archive-checkbox"></td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <i class="fas fa-${archive.icon}" style="color: ${archive.iconColor}; font-size: 1.2rem;"></i>
                            <div>
                                <div style="font-weight: 500;">${archive.name}</div>
                                <div style="font-size: 0.8rem; opacity: 0.7;">${archive.filename}</div>
                            </div>
                        </div>
                    </td>
                    <td><span class="status-badge" style="background: rgba(72, 187, 120, 0.1); color: #48BB78;">${getCategoryName(archive.category)}</span></td>
                    <td>${formatDate(archive.date)}</td>
                    <td>${archive.size}</td>
                    <td><span class="status-badge status-${archive.status}">${getStatusName(archive.status)}</span></td>
                    <td>
                        <div style="display: flex; gap: 8px;">
                            <button class="action-btn" onclick="viewArchive(${archive.id})" title="Lihat">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="action-btn" onclick="downloadArchive(${archive.id})" title="Download">
                                <i class="fas fa-download"></i>
                            </button>
                            <button class="action-btn" onclick="editArchive(${archive.id})" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn" onclick="deleteArchive(${archive.id})" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                `;
                
                tableBody.appendChild(tableRow);
                
                // Add to grid view
                const gridCard = document.createElement('div');
                gridCard.className = 'archive-card';
                gridCard.setAttribute('data-category', archive.category);
                gridCard.setAttribute('data-status', archive.status);
                gridCard.setAttribute('data-date', archive.date);
                
                gridCard.innerHTML = `
                    <div class="archive-card-header">
                        <div class="file-icon">
                            <i class="fas fa-${archive.icon}" style="color: ${archive.iconColor};"></i>
                        </div>
                        <input type="checkbox" class="archive-checkbox">
                    </div>
                    <h4 class="archive-title">${archive.name}</h4>
                    <div class="archive-meta">
                        <div class="meta-item">
                            <i class="fas fa-folder"></i>
                            <span>${getCategoryName(archive.category)}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-calendar"></i>
                            <span>${formatDate(archive.date)}</span>
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-file"></i>
                            <span>${archive.size}</span>
                        </div>
                        <div class="meta-item">
                            <span class="status-badge status-${archive.status}">${getStatusName(archive.status)}</span>
                        </div>
                    </div>
                    <div class="action-buttons">
                        <button class="action-btn" onclick="viewArchive(${archive.id})">
                            <i class="fas fa-eye"></i> Lihat
                        </button>
                        <button class="action-btn" onclick="downloadArchive(${archive.id})">
                            <i class="fas fa-download"></i> Download
                        </button>
                        <button class="action-btn" onclick="editArchive(${archive.id})">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                    </div>
                `;
                
                gridView.appendChild(gridCard);
            });

            // Update notification badge randomly
            setInterval(() => {
                const badge = document.querySelector('.notification-badge');
                const randomCount = Math.floor(Math.random() * 5) + 1;
                badge.textContent = randomCount;
            }, 10000);
            
            // Simulate real-time stats updates
            setInterval(() => {
                const statValues = document.querySelectorAll('.stat-value');
                statValues.forEach(stat => {
                    const currentValue = parseInt(stat.textContent.replace(/,/g, ''));
                    const change = Math.floor(Math.random() * 3) - 1; // -1, 0, or 1
                    const newValue = Math.max(0, currentValue + change);
                    stat.textContent = newValue.toLocaleString();
                });
            }, 15000);
        });

        // Helper functions
        function getCategoryName(category) {
            const categories = {
                'keuangan': 'Keuangan',
                'administrasi': 'Administrasi',
                'kegiatan': 'Kegiatan',
                'kepegawaian': 'Kepegawaian',
                'surat-masuk': 'Surat Masuk',
                'surat-keluar': 'Surat Keluar'
            };
            return categories[category] || category;
        }

        function getStatusName(status) {
            const statuses = {
                'aktif': 'Aktif',
                'draft': 'Draft',
                'arsip': 'Diarsipkan'
            };
            return statuses[status] || status;
        }

        function formatDate(dateString) {
            const options = { day: 'numeric', month: 'short', year: 'numeric' };
            return new Date(dateString).toLocaleDateString('id-ID', options);
        }
