<?php
/*
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
<link rel="stylesheet" href="assets/css/navbar.css">

<header>

    <!-- Top Bar - div before navbar -->
    <div class="top-bar">
        <div class="container flex-row">
            <div> <i class="fas fa-truck"> </i> Free Delivery Over ৳5000 </div>
            <div style="display:flex; gap:1rem;">
                <a href="track-order.php" style="color:white; text-decoration:none;"> Track Order </a>
                <a href="support.php" style="color:white; text-decoration:none;"> Support </a>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <div class="main-nav">
        <div class="container flex-row">
            <a href="index.php" class="logo">
                <div class="logo-box"> R </div>
                <div>
                    <b style="font-size:1.5rem; display:block"> Relyve </b>
                    <small style="color:#999; display:block; margin-top:-0.25rem"> .com </small>
                </div>
            </a>

            <nav class="hidden-mobile">
                <a href="index.php" style="color:var(--primary)"> Home </a>
                <a href="smartphones.php"> Smartphones </a>
                <a href="laptops.php"> Laptops </a>
                <a href="tablets.php"> Tablets </a>
                <a href="hot-deals.php" class="hot-deal"> Hot Deals 🔥 </a>
            </nav>

            <div class="nav-icons">
                <a href="wishlist.php" class="icon-btn">
                    <i class="fas fa-heart"></i>
                    <span class="badge"> 3 </span>
                    <span class="hide-sm"> Wishlist </span>
                </a>
                <a href="cart.php" class="icon-btn">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="badge"> 2 </span>
                    <span class="hide-sm"> Cart </span>
                </a>
                <a href="account.php" class="icon-btn">
                    <i class="fas fa-user"> </i>
                    <span class="hide-sm"> Account </span>
                </a>
            </div>
        </div>
    </div>
</header>
*/
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
<link rel="stylesheet" href="assets/css/navbar.css">

<header>

    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container flex-row">
            <div> <i class="fas fa-truck"></i> Free Delivery Over ৳5000 </div>
            <div style="display:flex; gap:1rem;">
                <a href="track-order.php" style="color:white; text-decoration:none;"> Track Order </a>
                <a href="support.php" style="color:white; text-decoration:none;"> Support </a>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar -->
    <div class="main-nav">
        <div class="container flex-row">
            <a href="index.php" class="logo">
                <div class="logo-box"> R </div>
                <div>
                    <b style="font-size:1.5rem; display:block"> Relyve </b>
                    <small style="color:#999; display:block; margin-top:-0.25rem"> .com </small>
                </div>
            </a>

            <nav class="hidden-mobile">
                <a href="index.php"> Home </a>
                <a href="category.php?cat=smartphones"> Smartphones </a>
                <a href="category.php?cat=laptops"> Laptops </a>
                <a href="category.php?cat=tablets"> Tablets </a>
                <a href="hot-deals.php" class="hot-deal"> Hot Deals 🔥 </a>
            </nav>

            <div class="nav-icons">
                <a href="wishlist.php" class="icon-btn">
                    <i class="fas fa-heart"></i>
                    <span class="badge">0</span>
                    <span class="hide-sm"> Wishlist </span>
                </a>
                <a href="cart.php" class="icon-btn">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="badge">0</span>
                    <span class="hide-sm"> Cart </span>
                </a>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="account.php" class="icon-btn">
                        <i class="fas fa-user"></i>
                        <span class="hide-sm"> Account </span>
                    </a>
                    <a href="logout.php" class="icon-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="hide-sm"> Logout </span>
                    </a>
                <?php else: ?>
                    <a href="login_form.php" class="icon-btn">
                        <i class="fas fa-sign-in-alt"></i>
                        <span class="hide-sm"> Login </span>
                    </a>
                    <a href="register_form.php" class="icon-btn">
                        <i class="fas fa-user-plus"></i>
                        <span class="hide-sm"> Register </span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>