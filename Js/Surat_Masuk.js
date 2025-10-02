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

function showLogoutConfirmation() {
    // Buat modal konfirmasi
    const modal = document.createElement('div');
    modal.id = 'logoutConfirmationModal';
    modal.innerHTML = `
        <div class="modal-overlay" style="
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        ">
            <div class="modal-content" style="
                background: white;
                padding: 30px;
                border-radius: 10px;
                max-width: 400px;
                width: 90%;
                text-align: center;
                box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            ">
                <div class="modal-icon" style="font-size: 50px; color: #e74c3c; margin-bottom: 15px;">
                    <i class="fas fa-sign-out-alt"></i>
                </div>
                <h3 style="margin-bottom: 10px;">Konfirmasi Logout</h3>
                <p style="margin-bottom: 25px; color: #666;">Apakah Anda yakin ingin keluar dari sistem?</p>
                <div style="display: flex; gap: 10px; justify-content: center;">
                    <button id="cancelLogout" style="
                        padding: 10px 20px;
                        border: 1px solid #ddd;
                        background: white;
                        border-radius: 5px;
                        cursor: pointer;
                    ">Batal</button>
                    <button id="confirmLogout" style="
                        padding: 10px 20px;
                        border: none;
                        background: #e74c3c;
                        color: white;
                        border-radius: 5px;
                        cursor: pointer;
                    ">Ya, Logout</button>
                </div>
            </div>
        </div>
    `;

    document.body.appendChild(modal);

    // Tombol batal
    document.getElementById('cancelLogout').addEventListener('click', function() {
        document.body.removeChild(modal);
    });

    // Tombol konfirmasi
    document.getElementById('confirmLogout').addEventListener('click', function() {
        document.body.removeChild(modal);
        doLogout();
    });
}

// Fungsi utama logout
function doLogout() {
    console.log("Proses logout dimulai...");

    // Bersihkan data login dari localStorage
    localStorage.removeItem('arsiparis_currentUser');
    localStorage.removeItem('arsiparis_rememberMe');

    // Redirect ke halaman login
    window.location.href = "../Html/Login_Register.html";
}

// Pasang event listener untuk tombol logout di menu
document.addEventListener('DOMContentLoaded', function() {
    const logoutLink = document.getElementById('logoutLink');

    if (logoutLink) {
        logoutLink.addEventListener('click', function(e) {
            e.preventDefault();
            showLogoutConfirmation();
        });
    }
    // Cek apakah user sudah login
    const currentUser = JSON.parse(localStorage.getItem('arsiparis_currentUser'));
    if (!currentUser) {
        window.location.href = "../Html/Login_Register.html";
    }
});
