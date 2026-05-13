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
        .about-hero {
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: white;
            padding: 80px 0;
            text-align: center;
        }
        .about-hero h1 {
            font-size: 3rem;
            margin-bottom: 20px;
        }
        .about-content {
            padding: 60px 0;
        }
        .about-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            align-items: center;
        }
        .about-text h2 {
            font-size: 2rem;
            margin-bottom: 20px;
            color: var(--text);
        }
        .about-text p {
            color: var(--text-light);
            line-height: 1.8;
            margin-bottom: 20px;
        }
        .about-image img {
            width: 100%;
            border-radius: 20px;
            box-shadow: var(--shadow-lg);
        }
        .mission-vision {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin: 60px 0;
        }
        .mission-card, .vision-card {
            background: white;
            padding: 30px;
            border-radius: 20px;
            text-align: center;
            box-shadow: var(--shadow-sm);
        }
        .mission-card i, .vision-card i {
            font-size: 3rem;
            color: var(--primary);
            margin-bottom: 20px;
        }
        .mission-card h3, .vision-card h3 {
            font-size: 1.5rem;
            margin-bottom: 15px;
        }
        .values {
            text-align: center;
            margin-top: 60px;
        }
        .values h2 {
            font-size: 2rem;
            margin-bottom: 40px;
        }
        .values-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }
        .value-card {
            background: white;
            padding: 30px;
            border-radius: 20px;
            transition: var(--transition);
        }
        .value-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow-lg);
        }
        .value-card i {
            font-size: 2.5rem;
            color: var(--primary);
            margin-bottom: 15px;
        }
        .value-card h4 {
            font-size: 1.2rem;
            margin-bottom: 10px;
        }
        .stats {
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: white;
            padding: 60px 0;
            margin: 60px 0;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
            text-align: center;
        }
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            display: block;
        }
        .stat-label {
            font-size: 1rem;
            opacity: 0.9;
        }
        @media (max-width: 768px) {
            .about-grid, .mission-vision, .stats-grid {
                grid-template-columns: 1fr;
            }
            .about-hero h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>

<main>
    <div class="about-hero">
        <div class="container">
            <h1>About Relyve</h1>
            <p>Bangladesh's Trusted Online Shopping Destination</p>
        </div>
    </div>

    <div class="container about-content">
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

        <div class="stats">
            <div class="container">
                <div class="stats-grid">
                    <div>
                        <span class="stat-number">50,000+</span>
                        <span class="stat-label">Happy Customers</span>
                    </div>
                    <div>
                        <span class="stat-number">10,000+</span>
                        <span class="stat-label">Products Sold</span>
                    </div>
                    <div>
                        <span class="stat-number">500+</span>
                        <span class="stat-label">Products</span>
                    </div>
                    <div>
                        <span class="stat-number">64</span>
                        <span class="stat-label">Cities Covered</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="values">
            <h2>Our Core Values</h2>
            <div class="values-grid">
                <div class="value-card">
                    <i class="fas fa-heart"></i>
                    <h4>Customer First</h4>
                    <p>Our customers are at the heart of everything we do.</p>
                </div>
                <div class="value-card">
                    <i class="fas fa-shield-alt"></i>
                    <h4>Trust & Transparency</h4>
                    <p>We believe in honest business practices.</p>
                </div>
                <div class="value-card">
                    <i class="fas fa-bolt"></i>
                    <h4>Fast & Reliable</h4>
                    <p>Quick delivery and responsive support.</p>
                </div>
                <div class="value-card">
                    <i class="fas fa-star"></i>
                    <h4>Quality Assurance</h4>
                    <p>Only the best products make it to our store.</p>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include_once 'includes/footer.php'; ?>
</body>
</html>