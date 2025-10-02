// Toggle sidebar on mobile
        document.getElementById('menuToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });

        // Tab functionality
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active class from all tabs and contents
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                
                // Add active class to clicked tab and corresponding content
                this.classList.add('active');
                document.getElementById(this.getAttribute('data-tab')).classList.add('active');
            });
        });

        // Simulate notification updates
        setInterval(() => {
            const badge = document.querySelector('.notification-badge');
            const randomCount = Math.floor(Math.random() * 5) + 1;
            badge.textContent = randomCount;
        }, 10000);
// Fungsi untuk membuka modal
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    const body = document.body;
    
    modal.classList.add('active');
    body.classList.add('body-modal-open');
}

// Fungsi untuk menutup modal
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    const body = document.body;
    
    modal.classList.remove('active');
    body.classList.remove('body-modal-open');
}

// Event listener untuk tombol close
document.addEventListener('DOMContentLoaded', function() {
    // Tutup modal ketika klik tombol close
    document.querySelectorAll('.close-btn').forEach(button => {
        button.addEventListener('click', function() {
            const modal = this.closest('.modal');
            if (modal) {
                closeModal(modal.id);
            }
        });
    });
    
    // Tutup modal ketika klik di luar konten modal
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal(this.id);
            }
        });
    });
});