<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
<link rel="stylesheet" href="assets/css/footer.css">

<footer>
    <div class="container">
        <div class="footer-links">
            <div class="footer-column">
                <h4><i class="fas fa-store"></i> Shop</h4>
                <a href="all_products.php">All Products</a>
                <a href="hot_deals.php">Hot Deals</a>
                <a href="category.php?cat=smartphones">Smartphones</a>
                <a href="category.php?cat=laptops">Laptops</a>
                <a href="category.php?cat=accessories">Accessories</a>
            </div>
            <div class="footer-column">
                <h4><i class="fas fa-headset"></i> Customer Service</h4>
                <a href="contact.php">Contact Us</a>
                <a href="faq.php">FAQ</a>
                <a href="track_order.php">Track Order</a>
                <a href="support.php">Support</a>
                <a href="about.php">About Us</a>
            </div>
            <div class="footer-column">
                <h4><i class="fas fa-user-circle"></i> My Account</h4>
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
                <h4><i class="fas fa-file-alt"></i> Policies</h4>
                <a href="terms.php">Terms & Conditions</a>
                <a href="privacy.php">Privacy Policy</a>
                <a href="returns.php">Return Policy</a>
                <a href="shipping.php">Shipping Info</a>
            </div>
        </div>
        
        <!-- Payment Methods Section -->
        <div class="payment-methods">
            <span>Secure Payments:</span>
            <i class="fab fa-cc-visa"></i>
            <i class="fab fa-cc-mastercard"></i>
            <i class="fab fa-cc-amex"></i>
            <i class="fas fa-mobile-alt"></i>
            <span class="bkash">bKash</span>
        </div>
        
        <div class="footer-bottom">
            <p>© <?php echo date("Y"); ?> Relyve.com • All rights reserved</p>
            <div class="social-icons">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-linkedin-in"></i></a>
            </div>
        </div>
    </div>
</footer>

<style>
    footer {
        background: linear-gradient(135deg, #111827 0%, #1f2937 100%);
        color: #9ca3af;
        padding: 3rem 0 2rem;
        margin-top: 60px;
    }
    
    .container {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0 20px;
    }
    
    .footer-links {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 30px;
        margin-bottom: 30px;
    }
    
    .footer-column h4 {
        color: white;
        margin-bottom: 18px;
        font-size: 1rem;
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    
    .footer-column h4 i {
        color: #f97316;
        margin-right: 8px;
        font-size: 0.9rem;
    }
    
    .footer-column a {
        display: block;
        color: #9ca3af;
        text-decoration: none;
        margin-bottom: 10px;
        font-size: 0.85rem;
        transition: all 0.2s ease;
        padding: 2px 0;
    }
    
    .footer-column a:hover {
        color: #f97316;
        transform: translateX(5px);
    }
    
    /* Payment Methods */
    .payment-methods {
        text-align: center;
        padding: 20px 0;
        border-top: 1px solid #374151;
        margin-top: 10px;
        font-size: 0.8rem;
        color: #9ca3af;
    }
    
    .payment-methods span {
        margin-right: 15px;
    }
    
    .payment-methods i, .payment-methods .bkash {
        font-size: 1.3rem;
        margin: 0 8px;
        color: #9ca3af;
        transition: color 0.2s ease;
        cursor: default;
    }
    
    .payment-methods i:hover {
        color: #f97316;
    }
    
    .payment-methods .bkash {
        font-size: 0.85rem;
        font-weight: 600;
    }
    
    .payment-methods .bkash:hover {
        color: #f97316;
    }
    
    /* Footer Bottom */
    .footer-bottom {
        text-align: center;
        padding-top: 20px;
        border-top: 1px solid #374151;
        font-size: 0.8rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }
    
    .footer-bottom p {
        margin: 0;
    }
    
    .social-icons {
        display: flex;
        gap: 20px;
    }
    
    .social-icons a {
        color: #9ca3af;
        transition: all 0.2s ease;
        font-size: 1.1rem;
    }
    
    .social-icons a:hover {
        color: #f97316;
        transform: translateY(-3px);
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .footer-links {
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
        }
        
        .footer-bottom {
            flex-direction: column;
            text-align: center;
        }
        
        .payment-methods {
            font-size: 0.7rem;
        }
        
        .payment-methods i {
            font-size: 1.1rem;
        }
    }
    
    @media (max-width: 480px) {
        .footer-links {
            grid-template-columns: 1fr;
            gap: 25px;
        }
        
        .footer-column {
            text-align: center;
        }
        
        .footer-column a:hover {
            transform: translateX(0);
        }
        
        .payment-methods span {
            display: block;
            margin-bottom: 10px;
        }
    }
</style>