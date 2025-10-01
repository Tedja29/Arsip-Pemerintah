// Toggle sidebar on mobile
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

        // Modal functions
        function openModal(modalId) {
            document.getElementById(modalId).classList.add('active');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
        }

        // Open modal when clicking "Arsip Baru" button
        document.getElementById('addArchiveBtn').addEventListener('click', function() {
            openModal('addArchiveModal');
        });

        // Close modal when clicking outside modal content
        document.addEventListener('click', function(event) {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                if (event.target === modal) {
                    modal.classList.remove('active');
                }
            });
        });

        // Form submission
        document.getElementById('addArchiveForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Arsip berhasil ditambahkan!');
            closeModal('addArchiveModal');
        });

        // Animate progress bars on page load
        window.addEventListener('load', function() {
            const progressBars = document.querySelectorAll('.progress-fill');
            progressBars.forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0';
                setTimeout(() => {
                    bar.style.width = width;
                }, 500);
            });
        });
        // Fungsi Logout
        function logout() {
            // Tampilkan konfirmasi logout
            const confirmLogout = confirm("Apakah Anda yakin ingin keluar dari sistem?");
            
            if (confirmLogout) {
                // Simulasi proses logout
                console.log("Proses logout dimulai...");
                
                // Tampilkan loading indicator (opsional)
                showLoadingIndicator();
                
                // Simulasi delay untuk proses server
                setTimeout(() => {
                    // Hapus data sesi/token dari localStorage (jika ada)
                    localStorage.removeItem('userToken');
                    localStorage.removeItem('userData');
                    
                    // Redirect ke halaman login
                    window.location.href = "Login.html";
                }, 1000);
            }
        }

        // Fungsi untuk menampilkan loading indicator
        function showLoadingIndicator() {
            // Buat elemen loading
            const loadingDiv = document.createElement('div');
            loadingDiv.id = 'logoutLoading';
            loadingDiv.innerHTML = `
                <div style="
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0,0,0,0.7);
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    z-index: 9999;
                    color: white;
                    font-size: 18px;
                ">
                    <div style="text-align: center;">
                        <i class="fas fa-spinner fa-spin" style="font-size: 40px; margin-bottom: 10px;"></i>
                        <p>Sedang logout...</p>
                    </div>
                </div>
            `;
            
            document.body.appendChild(loadingDiv);
        }

        // Event listener untuk link logout
        document.addEventListener('DOMContentLoaded', function() {
            const logoutLink = document.getElementById('logoutLink');
            
            if (logoutLink) {
                logoutLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    logout();
                });
            }
            
            // Tambahkan juga event listener untuk dropdown user info
            const userInfoToggle = document.querySelector('.user-info-toggle');
            const dropdownMenu = document.querySelector('.dropdown-menu');
            
            if (userInfoToggle && dropdownMenu) {
                userInfoToggle.addEventListener('click', function() {
                    dropdownMenu.classList.toggle('show');
                });
                
                // Tutup dropdown ketika klik di luar
                document.addEventListener('click', function(e) {
                    if (!userInfoToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
                        dropdownMenu.classList.remove('show');
                    }
                });
            }
        });
        // Close mobile menu when clicking a link (if applicable)
        // Fungsi untuk menampilkan modal konfirmasi logout
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
    
    // Event listener untuk tombol
    document.getElementById('cancelLogout').addEventListener('click', function() {
        document.body.removeChild(modal);
    });
    
    document.getElementById('confirmLogout').addEventListener('click', function() {
        document.body.removeChild(modal);
        logout();
    });
}

// Ganti fungsi logout utama
function logout() {
    console.log("Proses logout dimulai...");
    showLoadingIndicator();
    
    setTimeout(() => {
        // Hapus data sesi
        localStorage.removeItem('userToken');
        localStorage.removeItem('userData');
        sessionStorage.clear();
        
        // Redirect ke halaman login
        window.location.href = "../index.html";
    }, 1000);
}

// Update event listener untuk menggunakan modal konfirmasi
document.addEventListener('DOMContentLoaded', function() {
    const logoutLink = document.getElementById('logoutLink');
    
    if (logoutLink) {
        logoutLink.addEventListener('click', function(e) {
            e.preventDefault();
            showLogoutConfirmation();
        });
    }
});