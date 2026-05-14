<?php
session_start();
include_once 'includes/navbar.php';
include_once 'config/db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Relyve</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        /* ============================================
           ABOUT HERO SECTION
        ============================================ */
        .about-hero {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 50%, #c2410c 100%);
            color: var(--white);
            padding: var(--spacing-3xl) 0;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .about-hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .about-hero h1 {
            font-size: 3.5rem;
            margin-bottom: var(--spacing-md);
            animation: fadeInDown 0.8s ease;
            position: relative;
            z-index: 1;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .about-hero p {
            font-size: 1.25rem;
            opacity: 0.95;
            animation: fadeInUp 0.8s ease;
            position: relative;
            z-index: 1;
        }

        /* ============================================
           ABOUT CONTENT SECTION
        ============================================ */
        .about-content {
            padding: var(--spacing-3xl) var(--spacing-xl);
            background: linear-gradient(135deg, var(--white) 0%, var(--gray-50) 100%);
        }

        .about-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: var(--spacing-3xl);
            align-items: center;
            margin-bottom: var(--spacing-3xl);
        }

        .about-text {
            animation: fadeInLeft 0.8s ease;
        }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .about-text h2 {
            font-size: 2.5rem;
            margin-bottom: var(--spacing-lg);
            color: var(--gray-900);
            position: relative;
            padding-bottom: var(--spacing-md);
        }

        .about-text h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: var(--primary);
            border-radius: var(--radius-full);
        }

        .about-text p {
            color: var(--gray-600);
            line-height: 1.8;
            margin-bottom: var(--spacing-lg);
            font-size: 1.05rem;
        }

        .about-image {
            animation: fadeInRight 0.8s ease;
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .about-image img {
            width: 100%;
            border-radius: var(--radius-2xl);
            box-shadow: var(--shadow-xl);
            transition: var(--transition);
            border: 1px solid var(--gray-200);
        }

        .about-image img:hover {
            transform: scale(1.02);
            box-shadow: var(--shadow-2xl);
        }

        /* ============================================
           MISSION & VISION CARDS
        ============================================ */
        .mission-vision {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: var(--spacing-xl);
            margin: var(--spacing-3xl) 0;
        }

        .mission-card, .vision-card {
            background: var(--white);
            padding: var(--spacing-2xl);
            border-radius: var(--radius-2xl);
            text-align: center;
            transition: var(--transition);
            border: 1px solid var(--gray-200);
            position: relative;
            overflow: hidden;
        }

        .mission-card::before, .vision-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--primary-dark));
            transform: scaleX(0);
            transition: transform var(--transition);
        }

        .mission-card:hover::before, .vision-card:hover::before {
            transform: scaleX(1);
        }

        .mission-card:hover, .vision-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
            border-color: var(--primary-light);
        }

        .mission-card i, .vision-card i {
            font-size: 3.5rem;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-bottom: var(--spacing-lg);
            display: inline-block;
        }

        .mission-card h3, .vision-card h3 {
            font-size: 1.75rem;
            margin-bottom: var(--spacing-md);
            color: var(--gray-900);
        }

        .mission-card p, .vision-card p {
            color: var(--gray-600);
            line-height: 1.7;
        }

        /* ============================================
           STATS SECTION
        ============================================ */
        .stats {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 50%, #c2410c 100%);
            color: var(--white);
            padding: var(--spacing-3xl) 0;
            margin: var(--spacing-3xl) 0;
            position: relative;
            overflow: hidden;
        }

        .stats::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="10" cy="10" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="30" cy="40" r="3" fill="rgba(255,255,255,0.1)"/><circle cx="70" cy="20" r="2" fill="rgba(255,255,255,0.1)"/><circle cx="90" cy="70" r="3" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="85" r="2" fill="rgba(255,255,255,0.1)"/></svg>');
            background-repeat: repeat;
            opacity: 0.1;
            pointer-events: none;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: var(--spacing-xl);
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .stat-item {
            padding: var(--spacing-lg);
            border-radius: var(--radius-xl);
            transition: var(--transition);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .stat-item:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 800;
            display: block;
            margin-bottom: var(--spacing-sm);
            background: linear-gradient(135deg, #fff, #ffedd5);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .stat-label {
            font-size: 1rem;
            opacity: 0.95;
            font-weight: 500;
        }

        /* ============================================
           VALUES SECTION
        ============================================ */
        .values {
            text-align: center;
            margin-top: var(--spacing-3xl);
            padding: 0 var(--spacing-xl);
        }

        .values h2 {
            font-size: 2.5rem;
            margin-bottom: var(--spacing-3xl);
            position: relative;
            display: inline-block;
        }

        .values h2::after {
            content: '';
            position: absolute;
            bottom: -12px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: var(--primary);
            border-radius: var(--radius-full);
        }

        .values-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: var(--spacing-xl);
            margin-top: var(--spacing-xl);
        }

        .value-card {
            background: var(--white);
            padding: var(--spacing-xl);
            border-radius: var(--radius-xl);
            transition: var(--transition);
            border: 1px solid var(--gray-200);
            text-align: center;
        }

        .value-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-xl);
            border-color: var(--primary);
        }

        .value-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto var(--spacing-lg);
            background: linear-gradient(135deg, var(--primary-light), var(--white));
            border-radius: var(--radius-2xl);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .value-card:hover .value-icon {
            transform: scale(1.1);
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        }

        .value-card i {
            font-size: 2.5rem;
            color: var(--primary);
            transition: var(--transition);
        }

        .value-card:hover i {
            color: var(--white);
        }

        .value-card h4 {
            font-size: 1.3rem;
            margin-bottom: var(--spacing-md);
            color: var(--gray-900);
        }

        .value-card p {
            color: var(--gray-600);
            line-height: 1.6;
        }

        /* ============================================
           TEAM SECTION (Added Enhancement)
        ============================================ */
        .team-section {
            margin-top: var(--spacing-3xl);
            padding: var(--spacing-2xl) var(--spacing-xl);
            border-top: 2px solid var(--gray-200);
        }

        .team-section h2 {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: var(--spacing-2xl);
            position: relative;
        }

        .team-section h2::after {
            content: '';
            position: absolute;
            bottom: -12px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: var(--primary);
            border-radius: var(--radius-full);
        }

        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: var(--spacing-xl);
        }

        .team-card {
            background: var(--white);
            border-radius: var(--radius-xl);
            overflow: hidden;
            text-align: center;
            transition: var(--transition);
            border: 1px solid var(--gray-200);
        }

        .team-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-xl);
            border-color: var(--primary);
        }

        .team-img {
            width: 100%;
            height: 250px;
            overflow: hidden;
        }

        .team-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: var(--transition);
        }

        .team-card:hover .team-img img {
            transform: scale(1.1);
        }

        .team-info {
            padding: var(--spacing-lg);
        }

        .team-info h4 {
            font-size: 1.2rem;
            margin-bottom: var(--spacing-xs);
            color: var(--gray-900);
        }

        .team-info p {
            color: var(--primary);
            font-weight: 500;
            margin-bottom: var(--spacing-md);
        }

        .team-social {
            display: flex;
            justify-content: center;
            gap: var(--spacing-md);
        }

        .team-social a {
            width: 35px;
            height: 35px;
            background: var(--gray-100);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray-600);
            transition: var(--transition);
        }

        .team-social a:hover {
            background: var(--primary);
            color: var(--white);
            transform: translateY(-3px);
        }

        /* ============================================
           RESPONSIVE DESIGN
        ============================================ */
        @media (max-width: 1024px) {
            .about-hero h1 {
                font-size: 2.8rem;
            }
            .stats-grid {
                gap: var(--spacing-lg);
            }
            .stat-number {
                font-size: 2.5rem;
            }
        }

        @media (max-width: 768px) {
            .about-hero {
                padding: var(--spacing-2xl) 0;
            }
            .about-hero h1 {
                font-size: 2rem;
            }
            .about-hero p {
                font-size: 1rem;
            }
            .about-grid {
                grid-template-columns: 1fr;
                gap: var(--spacing-xl);
            }
            .mission-vision {
                grid-template-columns: 1fr;
                gap: var(--spacing-lg);
            }
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: var(--spacing-lg);
            }
            .about-text h2 {
                font-size: 2rem;
            }
            .values h2 {
                font-size: 2rem;
            }
            .team-section h2 {
                font-size: 2rem;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
                gap: var(--spacing-md);
            }
            .values-grid {
                grid-template-columns: 1fr;
            }
            .team-grid {
                grid-template-columns: 1fr;
            }
            .mission-card, .vision-card {
                padding: var(--spacing-lg);
            }
            .value-card {
                padding: var(--spacing-lg);
            }
        }
    </style>
</head>
<body>

<main>
    <!-- HERO SECTION -->
    <div class="about-hero">
        <div class="container">
            <h1>About Relyve</h1>
            <p>Bangladesh's Trusted Online Shopping Destination</p>
        </div>
    </div>

    <div class="container about-content">
        <!-- OUR STORY SECTION -->
        <div class="about-grid">
            <div class="about-text">
                <h2>Our Story</h2>
                <p>Founded in 2024, Relyve started with a simple mission: to make quality products accessible to everyone in Bangladesh. What began as a small startup has grown into one of the country's most trusted e-commerce platforms.</p>
                <p>We believe that online shopping should be simple, secure, and enjoyable. That's why we've built a platform that puts customers first, offering a wide selection of products at competitive prices with reliable delivery.</p>
                <p>Today, Relyve serves thousands of satisfied customers across Bangladesh, and we're just getting started.</p>
            </div>
            <div class="about-image">
                <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=600" alt="Our Team">
            </div>
        </div>

        <!-- MISSION & VISION CARDS -->
        <div class="mission-vision">
            <div class="mission-card">
                <i class="fas fa-bullseye"></i>
                <h3>Our Mission</h3>
                <p>To provide high-quality products at affordable prices with exceptional customer service and fast, reliable delivery across Bangladesh.</p>
            </div>
            <div class="vision-card">
                <i class="fas fa-eye"></i>
                <h3>Our Vision</h3>
                <p>To become Bangladesh's most loved online shopping platform, empowering local businesses and connecting them with customers nationwide.</p>
            </div>
        </div>

        <!-- STATS SECTION -->
        <div class="stats">
            <div class="container">
                <div class="stats-grid">
                    <div class="stat-item">
                        <span class="stat-number">50,000+</span>
                        <span class="stat-label">Happy Customers</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">10,000+</span>
                        <span class="stat-label">Products Sold</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">500+</span>
                        <span class="stat-label">Products</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">64</span>
                        <span class="stat-label">Cities Covered</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- CORE VALUES SECTION -->
        <div class="values">
            <h2>Our Core Values</h2>
            <div class="values-grid">
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h4>Customer First</h4>
                    <p>Our customers are at the heart of everything we do.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h4>Trust & Transparency</h4>
                    <p>We believe in honest business practices.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h4>Fast & Reliable</h4>
                    <p>Quick delivery and responsive support.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <h4>Quality Assurance</h4>
                    <p>Only the best products make it to our store.</p>
                </div>
            </div>
        </div>

        <!-- TEAM SECTION (Enhanced Addition) -->
        <div class="team-section">
            <h2>Meet Our Leadership</h2>
            <div class="team-grid">
                <div class="team-card">
                    <div class="team-img">
                        <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?w=300" alt="CEO">
                    </div>
                    <div class="team-info">
                        <h4>Md. Rahman</h4>
                        <p>Founder & CEO</p>
                        <div class="team-social">
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                </div>
                <div class="team-card">
                    <div class="team-img">
                        <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=300" alt="COO">
                    </div>
                    <div class="team-info">
                        <h4>Sultana Akhter</h4>
                        <p>Chief Operating Officer</p>
                        <div class="team-social">
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                </div>
                <div class="team-card">
                    <div class="team-img">
                        <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=300" alt="CTO">
                    </div>
                    <div class="team-info">
                        <h4>Rafiqul Islam</h4>
                        <p>Chief Technology Officer</p>
                        <div class="team-social">
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include_once 'includes/footer.php'; ?>
</body>
</html>