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
