<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cao Hùng Tech - Dịch Vụ Sửa Chữa Máy Tính Uy Tín</title>
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>?v=<?= time() ?>">
    <link rel="stylesheet" href="<?= asset('css/landing.css') ?>?v=<?= time() ?>">
</head>
<body>
    <!-- ===== HEADER ===== -->
    <header class="landing-header" id="header">
        <div class="landing-container">
            <a href="<?= url('') ?>" class="landing-logo">
                <img class="landing-logo-full" src="<?= asset('images/D11_CAOHUNG-1.png') ?>" alt="Cao Hung logo">
                <span class="logo-name">CAO HÙNG TECH</span>
            </a>
            <nav class="landing-nav" id="mainNav">
                <a href="#home" class="nav-link active">Trang chủ</a>
                <a href="#about" class="nav-link">Giới thiệu</a>
                <a href="#services" class="nav-link">Dịch vụ</a>
                <a href="#contact" class="nav-link">Liên hệ</a>
            </nav>
            <div class="landing-actions">
                <?php if (!empty($_SESSION['user'])):
                    $role = $_SESSION['user']['LoaiTK'] ?? '';
                    if ($role === 'admin')           $dashUrl = url('admin');
                    elseif ($role === 'ktv')         $dashUrl = url('ktv');
                    elseif ($role === 'nhanvien')    $dashUrl = url('nhanvien');
                    elseif ($role === 'khachhang')   $dashUrl = url('khach');
                    else                             $dashUrl = url('auth/login');
                ?>
                    <a href="<?= $dashUrl ?>" class="btn-login">Vào hệ thống</a>
                <?php else: ?>
                    <a href="<?= url('auth/login') ?>" class="btn-login">Đăng nhập</a>
                <?php endif; ?>
            </div>
            <button class="hamburger" id="hamburger" aria-label="Menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </header>

    <!-- ===== HERO ===== -->
    <section class="hero-section" id="home">
        <div class="hero-overlay"></div>
        <div class="landing-container hero-content">
            <h1>Dịch Vụ Sửa Chữa<br><span>Máy Tính & Laptop</span></h1>
            <p class="hero-desc">Chuyên sửa chữa, bảo trì, nâng cấp máy tính - laptop với đội ngũ kỹ thuật viên giàu kinh nghiệm. Bảo hành dài hạn, giá cả hợp lý.</p>
            <div class="hero-buttons">
                <a href="#services" class="btn-hero-primary">Xem dịch vụ</a>
                <a href="#contact" class="btn-hero-outline">Liên hệ ngay</a>
            </div>
        </div>
    </section>

    <!-- ===== GIỚI THIỆU ===== -->
    <section class="landing-section" id="about">
        <div class="landing-container">
            <div class="section-header">
                <h2>Về Chúng Tôi</h2>
                <p>CÔNG TY TNHH CÔNG NGHỆ CAO HÙNG</p>
            </div>
            <div class="about-grid">
                <div class="about-text">
                    <h3>Đơn vị sửa chữa máy tính uy tín tại Vĩnh Long</h3>
                    <p>Cao Hùng Tech là đơn vị chuyên cung cấp dịch vụ sửa chữa, bảo trì và nâng cấp máy tính, laptop tại Vĩnh Long. Với đội ngũ kỹ thuật viên được đào tạo bài bản, chúng tôi cam kết mang đến dịch vụ chất lượng cao với giá thành hợp lý nhất.</p>
                    <div class="about-features">
                        <div class="feature-item">
                            <span class="feature-icon">🎯</span>
                            <div>
                                <strong>Chẩn đoán chính xác</strong>
                                <p>Xác định lỗi nhanh chóng, báo giá minh bạch trước khi sửa chữa</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <span class="feature-icon">⚡</span>
                            <div>
                                <strong>Sửa chữa nhanh chóng</strong>
                                <p>Hầu hết các lỗi được xử lý trong ngày, tiết kiệm thời gian cho bạn</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <span class="feature-icon">🛡️</span>
                            <div>
                                <strong>Bảo hành dài hạn</strong>
                                <p>Cam kết bảo hành từ 3-12 tháng tùy loại dịch vụ</p>
                            </div>
                        </div>
                        <div class="feature-item">
                            <span class="feature-icon">💰</span>
                            <div>
                                <strong>Giá cả hợp lý</strong>
                                <p>Báo giá trước khi sửa, không phát sinh chi phí ẩn</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="about-image">
                    <div class="about-card">
                        <div class="card-icon"><img class="about-logo-mark" src="<?= asset('images/D11_CAOHUNG-1.png') ?>" alt="Cao Hung logo"></div>
                        <h4>CÔNG TY TNHH CÔNG NGHỆ CAO HÙNG</h4>
                        <p>📍 189A, Nguyễn Đáng, Khóm 6, Phường Nguyệt Hóa, Vĩnh Long</p>
                        <p>📞 094.179.1313</p>
                        <p>🕐 Thứ 2 - Thứ 7: 7:30 - 17:30</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== DỊCH VỤ ===== -->
    <section class="landing-section section-alt" id="services">
        <div class="landing-container">
            <div class="section-header">
                <h2>Dịch Vụ Sửa Chữa</h2>
                <p>Đa dạng dịch vụ, đáp ứng mọi nhu cầu</p>
            </div>
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">💻</div>
                    <h3>Sửa chữa Laptop</h3>
                    <p>Sửa mainboard, thay màn hình, bàn phím, loa, pin và các linh kiện laptop</p>
                    <ul>
                        <li>Sửa laptop không lên nguồn</li>
                        <li>Thay màn hình laptop</li>
                        <li>Sửa lỗi phần mềm, virus</li>
                    </ul>
                </div>
                <div class="service-card">
                    <div class="service-icon">🖥️</div>
                    <h3>Sửa chữa PC</h3>
                    <p>Sửa chữa, lắp ráp, nâng cấp máy tính bàn theo yêu cầu</p>
                    <ul>
                        <li>Lắp ráp PC theo cấu hình</li>
                        <li>Nâng cấp RAM, SSD, VGA</li>
                        <li>Sửa mainboard desktop</li>
                    </ul>
                </div>
                <div class="service-card">
                    <div class="service-icon">🔧</div>
                    <h3>Bảo trì định kỳ</h3>
                    <p>Vệ sinh, bảo dưỡng máy tính để đảm bảo hiệu suất tốt nhất</p>
                    <ul>
                        <li>Vệ sinh phần cứng</li>
                        <li>Thay keo tản nhiệt</li>
                        <li>Kiểm tra tổng thể</li>
                    </ul>
                </div>
                <div class="service-card">
                    <div class="service-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#f5a623" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/>
                            <circle cx="12" cy="13" r="4"/>
                            <line x1="18" y1="5" x2="20" y2="3"/>
                            <line x1="20" y1="3" x2="22" y2="5"/>
                            <line x1="20" y1="1" x2="20" y2="3"/>
                        </svg>
                    </div>
                    <h3>Lắp đặt Camera & Đèn Năng Lượng Mặt Trời</h3>
                    <p>Chuyên lắp đặt camera an ninh và đèn năng lượng mặt trời</p>
                    <ul>
                        <li>Camera quan sát trong/ngoài nhà</li>
                        <li>Đèn năng lượng mặt trời</li>
                        <li>Tư vấn & bảo trì hệ thống</li>
                    </ul>
                </div>
                <div class="service-card">
                    <div class="service-icon">🌐</div>
                    <h3>Cài đặt phần mềm</h3>
                    <p>Cài Win, driver, phần mềm bản quyền, diệt virus chuyên nghiệp</p>
                    <ul>
                        <li>Cài đặt Windows, macOS</li>
                        <li>Cài phần mềm văn phòng</li>
                        <li>Diệt virus, malware</li>
                    </ul>
                </div>
                <div class="service-card">
                    <div class="service-icon">🖨️</div>
                    <h3>Sửa máy in & mạng</h3>
                    <p>Sửa chữa máy in, cài đặt mạng LAN, WiFi cho văn phòng</p>
                    <ul>
                        <li>Sửa & đổ mực máy in</li>
                        <li>Cài đặt mạng văn phòng</li>
                        <li>Cấu hình WiFi, Router</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== QUY TRÌNH ===== -->
    <section class="landing-section" id="process">
        <div class="landing-container">
            <div class="section-header">
                <h2>Quy Trình Sửa Chữa</h2>
                <p>Minh bạch - Chuyên nghiệp - Nhanh chóng</p>
            </div>
            <div class="process-steps">
                <div class="process-step">
                    <div class="step-number">1</div>
                    <h3>Tiếp nhận</h3>
                    <p>Nhân viên tiếp nhận thiết bị, ghi nhận tình trạng ban đầu và tạo phiếu sửa chữa</p>
                </div>
                <div class="process-line"></div>
                <div class="process-step">
                    <div class="step-number">2</div>
                    <h3>Kiểm tra & Báo giá</h3>
                    <p>Kỹ thuật viên kiểm tra chi tiết, xác định lỗi và báo giá trước khi sửa</p>
                </div>
                <div class="process-line"></div>
                <div class="process-step">
                    <div class="step-number">3</div>
                    <h3>Sửa chữa</h3>
                    <p>Thực hiện sửa chữa theo đúng cam kết, cập nhật tiến độ cho khách hàng</p>
                </div>
                <div class="process-line"></div>
                <div class="process-step">
                    <div class="step-number">4</div>
                    <h3>Trả máy & Bảo hành</h3>
                    <p>Bàn giao thiết bị, hướng dẫn sử dụng và cấp phiếu bảo hành</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== LIÊN HỆ ===== -->
    <section class="landing-section" id="contact">
        <div class="landing-container">
            <div class="section-header">
                <h2>Liên Hệ Với Chúng Tôi</h2>
                <p>Hãy liên hệ khi bạn cần hỗ trợ!</p>
            </div>
            <div class="contact-grid">
                <div class="contact-info">
                    <div class="contact-card">
                        <div class="contact-icon">📍</div>
                        <h3>Địa chỉ</h3>
                        <p>189A, Nguyễn Đáng,<br>Khóm 6, Phường Nguyệt Hóa, Vĩnh Long</p>
                    </div>
                    <div class="contact-card">
                        <div class="contact-icon">📞</div>
                        <h3>Điện thoại</h3>
                        <p><a href="tel:0941791313">094.179.1313</a></p>
                    </div>
                    <div class="contact-card">
                        <div class="contact-icon">🕐</div>
                        <h3>Giờ làm việc</h3>
                        <p>Thứ 2 - Thứ 7: 7:30 - 17:30</p>
                    </div>
                    <div class="contact-card">
                        <div class="contact-icon">📧</div>
                        <h3>Email</h3>
                        <p>caohungtech@gmail.com</p>
                    </div>
                </div>
                <div class="contact-map">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1963.5!2d106.3326976!3d9.9271011!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31a0170333690be9%3A0x97f0affe9418960e!2sLaptop%20C%C5%A9%20-%20Camera%20-%20M%C3%A1y%20in%20Tr%C3%A0%20Vinh%20-%20CTY%20Cao%20H%C3%B9ng!5e0!3m2!1svi!2s!4v1"
                        width="100%" height="100%" style="border:0;border-radius:12px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== FOOTER ===== -->
    <footer class="landing-footer">
        <div class="landing-container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <div class="footer-logo">
                        <div class="logo-circle-sm">
                            <img src="<?= asset('images/D11_CAOHUNG-1.png') ?>" alt="Cao Hung logo">
                        </div>
                        <span>CAO HÙNG TECH</span>
                    </div>
                    <p>Trao giá trị - Nhận niềm tin</p>
                    <p class="footer-desc">Chuyên sửa chữa, bảo trì, nâng cấp máy tính và laptop. Uy tín - Chất lượng - Giá hợp lý.</p>
                </div>
                <div class="footer-links">
                    <h4>Dịch vụ</h4>
                    <ul>
                        <li><a href="#services">Sửa chữa Laptop</a></li>
                        <li><a href="#services">Sửa chữa PC</a></li>
                        <li><a href="#services">Bảo trì định kỳ</a></li>
                        <li><a href="#services">Lắp đặt Camera & Đèn Năng Lượng Mặt Trời</a></li>
                    </ul>
                </div>
                <div class="footer-links">
                    <h4>Liên kết</h4>
                    <ul>
                        <li><a href="#home">Trang chủ</a></li>
                        <li><a href="#about">Giới thiệu</a></li>
                        <li><a href="#contact">Liên hệ</a></li>
                        <li><a href="<?= url('auth/login') ?>">Đăng nhập</a></li>
                    </ul>
                </div>
                <div class="footer-contact">
                    <h4>Liên hệ</h4>
                    <p>📍 189A, Nguyễn Đáng, Khóm 6, Phường Nguyệt Hóa, Vĩnh Long</p>
                    <p>📞 094.179.1313</p>
                    <p>📧 caohungtech@gmail.com</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> Cao Hùng Tech. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- ===== SCRIPTS ===== -->
    <script>
    // Hamburger menu toggle
    document.getElementById('hamburger').addEventListener('click', function() {
        document.getElementById('mainNav').classList.toggle('open');
        this.classList.toggle('active');
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            var target = document.querySelector(this.getAttribute('href'));
            if (target) {
                var headerH = document.getElementById('header').offsetHeight;
                var top = target.getBoundingClientRect().top + window.pageYOffset - headerH;
                window.scrollTo({ top: top, behavior: 'smooth' });
            }
            // Close mobile menu
            document.getElementById('mainNav').classList.remove('open');
            document.getElementById('hamburger').classList.remove('active');
        });
    });

    // Header scroll effect
    window.addEventListener('scroll', function() {
        var header = document.getElementById('header');
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    });

    // Active nav link on scroll
    var sections = document.querySelectorAll('section[id]');
    window.addEventListener('scroll', function() {
        var scrollY = window.pageYOffset;
        sections.forEach(function(section) {
            var top = section.offsetTop - 100;
            var height = section.offsetHeight;
            var id = section.getAttribute('id');
            var link = document.querySelector('.landing-nav a[href="#' + id + '"]');
            if (link) {
                if (scrollY >= top && scrollY < top + height) {
                    document.querySelectorAll('.landing-nav a').forEach(function(a) { a.classList.remove('active'); });
                    link.classList.add('active');
                }
            }
        });
    });

    </script>
</body>
</html>
