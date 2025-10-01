// Toggle Sidebar
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('active');
        });

        // Navigasi menu pengaturan
        const navItems = document.querySelectorAll('.settings-nav a');
        const settingsDetails = document.querySelectorAll('.settings-details');
        
        navItems.forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                
                // Hapus kelas active dari semua item
                navItems.forEach(i => i.classList.remove('active'));
                settingsDetails.forEach(detail => detail.classList.remove('active'));
                
                // Tambah kelas active ke item yang diklik
                item.classList.add('active');
                
                // Tampilkan detail yang sesuai
                const target = item.getAttribute('data-target');
                document.getElementById(target).classList.add('active');
            });
        });

        // Pilihan tema
        const themeOptions = document.querySelectorAll('.theme-option');
        themeOptions.forEach(option => {
            option.addEventListener('click', () => {
                themeOptions.forEach(opt => opt.classList.remove('active'));
                option.classList.add('active');
            });
        });

        // Simpan pengaturan
        document.getElementById('saveSettings').addEventListener('click', () => {
            alert('Pengaturan berhasil disimpan!');
        });

        // Reset pengaturan
        document.getElementById('resetSettings').addEventListener('click', () => {
            if (confirm('Apakah Anda yakin ingin mengembalikan pengaturan ke default?')) {
                alert('Pengaturan telah direset ke default');
            }
        });