// Toggle sidebar on mobile
        document.getElementById('menuToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });

        // Update notification badge randomly
        setInterval(() => {
            const badge = document.querySelector('.notification-badge');
            const randomCount = Math.floor(Math.random() * 5) + 1;
            badge.textContent = randomCount;
        }, 10000);
        
        // Add interactivity to report cards
        document.querySelectorAll('.report-card').forEach(card => {
            card.addEventListener('click', function() {
                const title = this.querySelector('.report-title').textContent;
                alert(`Anda mengklik laporan: ${title}`);
            });
        });