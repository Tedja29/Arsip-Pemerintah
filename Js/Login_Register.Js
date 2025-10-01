        // Backend Simulasi dengan JavaScript
        class AuthBackend {
            constructor() {
                // Inisialisasi data pengguna dari localStorage jika ada
                this.users = JSON.parse(localStorage.getItem('arsiparis_users')) || [];
                this.currentUser = JSON.parse(localStorage.getItem('arsiparis_currentUser')) || null;
            }

            // Simpan data pengguna ke localStorage
            saveUsers() {
                localStorage.setItem('arsiparis_users', JSON.stringify(this.users));
            }

            // Daftar pengguna baru
            register(userData) {
                return new Promise((resolve, reject) => {
                    // Simulasi delay server
                    setTimeout(() => {
                        // Cek apakah email sudah terdaftar
                        const existingUser = this.users.find(user => user.email === userData.email);
                        if (existingUser) {
                            reject('Email sudah terdaftar');
                            return;
                        }

                        // Cek apakah username sudah digunakan
                        const existingUsername = this.users.find(user => user.username === userData.username);
                        if (existingUsername) {
                            reject('Username sudah digunakan');
                            return;
                        }

                        // Tambah pengguna baru
                        const newUser = {
                            id: Date.now(),
                            ...userData,
                            createdAt: new Date().toISOString()
                        };

                        this.users.push(newUser);
                        this.saveUsers();

                        resolve(newUser);
                    }, 1000);
                });
            }

            // Login pengguna
            login(identifier, password) {
                return new Promise((resolve, reject) => {
                    // Simulasi delay server
                    setTimeout(() => {
                        // Cari pengguna berdasarkan email atau username
                        const user = this.users.find(u => 
                            (u.email === identifier || u.username === identifier) && 
                            u.password === password
                        );

                        if (user) {
                            this.currentUser = user;
                            localStorage.setItem('arsiparis_currentUser', JSON.stringify(user));
                            resolve(user);
                        } else {
                            reject('Username/email atau kata sandi salah');
                        }
                    }, 1000);
                });
            }

            // Cek apakah pengguna sudah login
            isLoggedIn() {
                return this.currentUser !== null;
            }

            // Logout pengguna
            logout() {
                this.currentUser = null;
                localStorage.removeItem('arsiparis_currentUser');
            }
        }

        // Inisialisasi backend
        const authBackend = new AuthBackend();

        // DOM Elements
        const loginTab = document.getElementById('loginTab');
        const registerTab = document.getElementById('registerTab');
        const loginForm = document.getElementById('loginForm');
        const registerForm = document.getElementById('registerForm');
        const switchToRegister = document.getElementById('switchToRegister');
        const switchToLogin = document.getElementById('switchToLogin');
        const loginFormElement = document.getElementById('loginFormElement');
        const registerFormElement = document.getElementById('registerFormElement');
        const loginButton = document.getElementById('loginButton');
        const registerButton = document.getElementById('registerButton');
        const loginSpinner = document.getElementById('loginSpinner');
        const registerSpinner = document.getElementById('registerSpinner');
        const loginPasswordToggle = document.getElementById('loginPasswordToggle');
        const registerPasswordToggle = document.getElementById('registerPasswordToggle');
        const loginError = document.getElementById('loginError');
        const loginSuccess = document.getElementById('loginSuccess');
        const registerError = document.getElementById('registerError');
        const registerSuccess = document.getElementById('registerSuccess');

        // Toggle antara form login dan register
        function showLoginForm() {
            loginTab.classList.add('active');
            registerTab.classList.remove('active');
            loginForm.classList.add('active');
            registerForm.classList.remove('active');
            clearAlerts();
        }

        function showRegisterForm() {
            registerTab.classList.add('active');
            loginTab.classList.remove('active');
            registerForm.classList.add('active');
            loginForm.classList.remove('active');
            clearAlerts();
        }

        // Event listeners untuk tab
        loginTab.addEventListener('click', showLoginForm);
        registerTab.addEventListener('click', showRegisterForm);
        switchToRegister.addEventListener('click', (e) => {
            e.preventDefault();
            showRegisterForm();
        });
        switchToLogin.addEventListener('click', (e) => {
            e.preventDefault();
            showLoginForm();
        });

        // Toggle visibility password
        function togglePasswordVisibility(inputId, toggleElement) {
            const passwordInput = document.getElementById(inputId);
            const icon = toggleElement.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        loginPasswordToggle.addEventListener('click', () => {
            togglePasswordVisibility('loginPassword', loginPasswordToggle);
        });

        registerPasswordToggle.addEventListener('click', () => {
            togglePasswordVisibility('registerPassword', registerPasswordToggle);
        });

        // Fungsi untuk menampilkan alert
        function showAlert(alertElement, message, type) {
            alertElement.textContent = message;
            alertElement.style.display = 'block';
            
            if (type === 'error') {
                alertElement.classList.remove('alert-success');
                alertElement.classList.add('alert-error');
            } else {
                alertElement.classList.remove('alert-error');
                alertElement.classList.add('alert-success');
            }
            
            // Sembunyikan alert setelah 5 detik
            setTimeout(() => {
                alertElement.style.display = 'none';
            }, 5000);
        }

        // Fungsi untuk menghapus semua alert
        function clearAlerts() {
            loginError.style.display = 'none';
            loginSuccess.style.display = 'none';
            registerError.style.display = 'none';
            registerSuccess.style.display = 'none';
        }

        // Fungsi untuk menampilkan/menyembunyikan spinner
        function setLoading(button, spinner, isLoading) {
            if (isLoading) {
                button.disabled = true;
                spinner.style.display = 'inline-block';
                button.querySelector('span').style.opacity = '0.5';
            } else {
                button.disabled = false;
                spinner.style.display = 'none';
                button.querySelector('span').style.opacity = '1';
            }
        }

        // Handle login form submission
        loginFormElement.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const identifier = document.getElementById('loginUsername').value;
            const password = document.getElementById('loginPassword').value;
            const rememberMe = document.getElementById('rememberMe').checked;
            
            // Validasi input
            if (!identifier || !password) {
                showAlert(loginError, 'Harap isi semua field', 'error');
                return;
            }
            
            // Tampilkan loading state
            setLoading(loginButton, loginSpinner, true);
            clearAlerts();
            
            try {
                // Panggil backend untuk login
                const user = await authBackend.login(identifier, password);
                
                // Simpan preferensi "Ingat Saya" jika dicentang
                if (rememberMe) {
                    localStorage.setItem('arsiparis_rememberMe', 'true');
                }
                
                // Tampilkan pesan sukses
                showAlert(loginSuccess, `Selamat datang, ${user.fullName || user.username}!`, 'success');
                
                // Redirect ke dashboard setelah 1.5 detik
                setTimeout(() => {
                    window.location.href = 'Dashboard.html';
                }, 1500);
                
            } catch (error) {
                showAlert(loginError, error, 'error');
            } finally {
                setLoading(loginButton, loginSpinner, false);
            }
        });

        // Handle register form submission
        registerFormElement.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const fullName = document.getElementById('registerFullName').value;
            const email = document.getElementById('registerEmail').value;
            const username = document.getElementById('registerUsername').value;
            const password = document.getElementById('registerPassword').value;
            const confirmPassword = document.getElementById('registerConfirmPassword').value;
            const agreeTerms = document.getElementById('agreeTerms').checked;
            
            // Validasi input
            if (!fullName || !email || !username || !password || !confirmPassword) {
                showAlert(registerError, 'Harap isi semua field', 'error');
                return;
            }
            
            if (!agreeTerms) {
                showAlert(registerError, 'Anda harus menyetujui syarat dan ketentuan', 'error');
                return;
            }
            
            if (password !== confirmPassword) {
                showAlert(registerError, 'Kata sandi tidak cocok', 'error');
                return;
            }
            
            if (password.length < 6) {
                showAlert(registerError, 'Kata sandi harus minimal 6 karakter', 'error');
                return;
            }
            
            // Tampilkan loading state
            setLoading(registerButton, registerSpinner, true);
            clearAlerts();
            
            try {
                // Panggil backend untuk pendaftaran
                const userData = {
                    fullName,
                    email,
                    username,
                    password
                };
                
                const newUser = await authBackend.register(userData);
                
                // Tampilkan pesan sukses
                showAlert(registerSuccess, `Pendaftaran berhasil! Silakan masuk dengan akun Anda.`, 'success');
                
                // Reset form
                registerFormElement.reset();
                
                // Beralih ke form login setelah 2 detik
                setTimeout(() => {
                    showLoginForm();
                }, 2000);
                
            } catch (error) {
                showAlert(registerError, error, 'error');
            } finally {
                setLoading(registerButton, registerSpinner, false);
            }
        });

        // Cek apakah pengguna sudah login
        if (authBackend.isLoggedIn()) {
            // Redirect ke dashboard jika sudah login
            window.location.href = 'Dashboard.html';
        }

        // Animasi tambahan untuk meningkatkan estetika
        document.addEventListener('DOMContentLoaded', () => {
            // Animasi saat halaman dimuat
            const authContainer = document.getElementById('authContainer');
            authContainer.style.opacity = '0';
            authContainer.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                authContainer.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                authContainer.style.opacity = '1';
                authContainer.style.transform = 'translateY(0)';
            }, 100);
            
            // Animasi untuk input fields saat focus
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('focus', () => {
                    input.parentElement.style.transform = 'translateY(-2px)';
                });
                
                input.addEventListener('blur', () => {
                    input.parentElement.style.transform = 'translateY(0)';
                });
            });
        });
   