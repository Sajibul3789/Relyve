<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
<link rel="stylesheet" href="assets/css/footer.css">

<footer>
    <div class="container">
        <div class="footer-links">
            <div class="footer-column">
                <h4 style="color: white; margin-bottom: 15px;">Shop</h4>
                <a href="all_products.php">All Products</a>
                <a href="hot_deals.php">Hot Deals</a>
                <a href="category.php?cat=smartphones">Smartphones</a>
                <a href="category.php?cat=laptops">Laptops</a>
                <a href="category.php?cat=accessories">Accessories</a>
            </div>
            <div class="footer-column">
                <h4 style="color: white; margin-bottom: 15px;">Customer Service</h4>
                <a href="contact.php">Contact Us</a>
                <a href="faq.php">FAQ</a>
                <a href="track_order.php">Track Order</a>
                <a href="support.php">Support</a>
                <a href="about.php">About Us</a>
            </div>
            <div class="footer-column">
                <h4 style="color: white; margin-bottom: 15px;">My Account</h4>
                <a href="profile.php">My Profile</a>
                <a href="my_orders.php">My Orders</a>
                <a href="wishlist.php">Wishlist</a>
                <a href="cart.php">Shopping Cart</a>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="logout.php">Logout</a>
                <?php else: ?>
                    <a href="login_form.php">Login</a>
                    <a href="register_form.php">Register</a>
                <?php endif; ?>
            </div>
            <div class="footer-column">
                <h4 style="color: white; margin-bottom: 15px;">Policies</h4>
                <a href="terms.php">Terms & Conditions</a>
                <a href="privacy.php">Privacy Policy</a>
                <a href="returns.php">Return Policy</a>
                <a href="shipping.php">Shipping Info</a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© <?php echo date("Y"); ?> Relyve.com • All rights reserved</p>
            <p style="margin-top: 10px;">
                <i class="fab fa-facebook"></i> &nbsp;&nbsp;
                <i class="fab fa-twitter"></i> &nbsp;&nbsp;
                <i class="fab fa-instagram"></i> &nbsp;&nbsp;
                <i class="fab fa-linkedin"></i>
            </p>
        </div>
    </div>
</footer>

<style>
    footer {
        background: #111827;
        color: #6b7280;
        padding: 3rem 0 2rem;
        margin-top: 60px;
    }
    .footer-links {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 30px;
        margin-bottom: 30px;
    }
    .footer-column h4 {
        color: white;
        margin-bottom: 15px;
        font-size: 1rem;
    }
    .footer-column a {
        display: block;
        color: #9ca3af;
        text-decoration: none;
        margin-bottom: 8px;
        font-size: 0.85rem;
        transition: color 0.3s;
    }
    .footer-column a:hover {
        color: #f97316;
    }
    .footer-bottom {
        text-align: center;
        padding-top: 20px;
        border-top: 1px solid #374151;
        font-size: 0.8rem;
    }
    .footer-bottom i {
        cursor: pointer;
        transition: color 0.3s;
    }
    .footer-bottom i:hover {
        color: #f97316;
    }
    @media (max-width: 768px) {
        .footer-links {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (max-width: 480px) {
        .footer-links {
            grid-template-columns: 1fr;
        }
    }
</style>