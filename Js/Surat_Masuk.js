     // Toggle sidebar for mobile
        document.getElementById('menuToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });

        // Toggle dropdown menu
        document.querySelector('.user-profile').addEventListener('click', function() {
            this.classList.toggle('active');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.querySelector('.dropdown');
            if (!dropdown.contains(event.target)) {
                dropdown.classList.remove('active');
            }
        });

        // Tab functionality
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active class from all tabs and tab contents
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                
                // Add active class to clicked tab
                this.classList.add('active');
                
                // Show corresponding tab content
                const tabId = this.getAttribute('data-tab');
                document.getElementById(tabId).classList.add('active');
            });
        });

        // Modal functionality
        function openModal(modalId) {
            document.getElementById(modalId).classList.add('active');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
        }

        // Close modal when clicking outside
        window.addEventListener('click', function(event) {
            document.querySelectorAll('.modal').forEach(modal => {
                if (event.target === modal) {
                    modal.classList.remove('active');
                }
            });
        });

        // Scan folder functionality
        function startFolderScan() {
            document.getElementById('scanContainer').style.display = 'block';
        }

        function closeScanContainer() {
            document.getElementById('scanContainer').style.display = 'none';
            document.getElementById('scanProgress').style.display = 'none';
            document.getElementById('scanResults').style.display = 'none';
        }

        // Simulate scanning process
        document.getElementById('scanFolderDropzone').addEventListener('click', function() {
            document.getElementById('scanProgress').style.display = 'block';
            document.getElementById('scanResults').style.display = 'block';
            
            // Simulate progress
            let progress = 0;
            const progressFill = document.getElementById('progressFill');
            const progressText = document.getElementById('progressText');
            
            const interval = setInterval(() => {
                progress += Math.random() * 10;
                if (progress >= 100) {
                    progress = 100;
                    clearInterval(interval);
                    
                    // Show results after completion
                    setTimeout(() => {
                        document.getElementById('scanResultsList').innerHTML = `
                            <div class="scan-result-item">
                                <div class="scan-result-info">
                                    <i class="fas fa-file-pdf scan-result-icon"></i>
                                    <div class="scan-result-name">Surat_Undangan_Rapat.pdf</div>
                                </div>
                                <div class="scan-result-status">Berhasil di-scan</div>
                            </div>
                            <div class="scan-result-item">
                                <div class="scan-result-info">
                                    <i class="fas fa-file-word scan-result-icon"></i>
                                    <div class="scan-result-name">Draft_Surat_Edaran.docx</div>
                                </div>
                                <div class="scan-result-status">Berhasil di-scan</div>
                            </div>
                            <div class="scan-result-item">
                                <div class="scan-result-info">
                                    <i class="fas fa-file-image scan-result-icon"></i>
                                    <div class="scan-result-name">Lampiran_1.jpg</div>
                                </div>
                                <div class="scan-result-status">Berhasil di-scan</div>
                            </div>
                        `;
                    }, 500);
                }
                
                progressFill.style.width = `${progress}%`;
                progressText.textContent = `Memindai dokumen... ${Math.round(progress)}%`;
            }, 300);
        });

        // Form submission
        document.getElementById('addSuratForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Surat keluar berhasil ditambahkan!');
            closeModal('addSuratModal');
            // In a real application, you would send the form data to a server here
        });
