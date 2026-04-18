<?php include 'includes/navbar.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relyve - Online Shopping</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <div class="search-area">
        <div class="container">
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="sInput" class="search-input" placeholder="Search smartphones, laptops, accessories...">
                <button class="search-btn"> Search </button>
            </div>
        </div>
    </div>

    <main>
        <section class="hero">
            <div class="container">
                <span class="hero-tag">NEW ARRIVALS 2026</span>
                <h1>Samsung Galaxy<br>S25 Ultra</h1>
                <p>Titanium design • AI Camera • 5000mAh Battery</p>
                <button class="p-btn" style="width:auto; padding:18px 40px; border-radius:18px; font-size:1.1rem">
                    Buy Now - ৳1,89,999
                </button>
            </div>
        </section>

        <section class="section container">
            <h2 style="text-align:center; margin-bottom:40px; font-size:2rem">Shop by Category</h2>
            <div class="cat-grid">
                <div class="cat-card">
                    <div class="cat-img cat-blue"><i class="fas fa-mobile-alt"></i></div>
                    <h3>Smartphones</h3>
                </div>
                <div class="cat-card">
                    <div class="cat-img cat-slate"><i class="fas fa-laptop"></i></div>
                    <h3>Laptops</h3>
                </div>
                <div class="cat-card">
                    <div class="cat-img" style="background:#fff7ed; color:#f97316"><i class="fas fa-tablet-alt"></i></div>
                    <h3>Tablets</h3>
                </div>
                <div class="cat-card">
                    <div class="cat-img" style="background:#f0fdf4; color:#22c55e"><i class="fas fa-headphones"></i></div>
                    <h3>Accessories</h3>
                </div>
                <div class="cat-card">
                    <div class="cat-img" style="background:#faf5ff; color:#a855f7"><i class="fas fa-tv"></i></div>
                    <h3>TV & Audio</h3>
                </div>
                <div class="cat-card">
                    <div class="cat-img" style="background:#fef2f2; color:#ef4444"><i class="fas fa-clock"></i></div>
                    <h3>Watches</h3>
                </div>
            </div>
        </section>

        <section class="section" style="background:#fef2f2">
            <div class="container">
                <div class="section-header">
                    <h2>Flash Deals</h2>
                    <a href="hot-deals.php" style="color:var(--primary); text-decoration:none; font-weight:600">View All →</a>
                </div>
                <div class="product-grid" id="flashGrid"></div>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script>
        const products = [{
                id: 1,
                name: "Samsung Galaxy S25 Ultra",
                price: "189999",
                old: "199999",
                disc: "5%"
            },
            {
                id: 2,
                name: "Xiaomi Redmi Note 14 Pro",
                price: "32999",
                old: "37999",
                disc: "13%"
            },
            {
                id: 3,
                name: "Apple AirPods Pro 2",
                price: "24999",
                old: "27999",
                disc: "11%"
            },
            {
                id: 4,
                name: "Sony WH-1000XM5",
                price: "44999",
                old: "49999",
                disc: "10%"
            },
            {
                id: 5,
                name: "OnePlus 13R",
                price: "59999",
                old: "64999",
                disc: "8%"
            }
        ];

        function renderProducts() {
            const grid = document.getElementById('flashGrid');
            grid.innerHTML = '';
            products.forEach(p => {
                const el = document.createElement('div');
                el.className = 'product-card';
                el.innerHTML = `
                    <div class="p-img">
                        📱 
                        <div class="p-discount">-${p.disc}</div>
                    </div>
                    <div class="p-info">
                        <div class="p-title">${p.name}</div>
                        <div class="p-price">৳${Number(p.price).toLocaleString()} 
                            <span class="p-old">৳${Number(p.old).toLocaleString()}</span>
                        </div>
                        <button class="p-btn">Add to Cart</button>
                    </div>
                `;
                grid.appendChild(el);
            });
        }

        window.onload = renderProducts;
    </script>
</body>

</html>