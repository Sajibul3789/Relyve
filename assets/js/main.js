function renderProducts() {
    const grid = document.getElementById('flashGrid');
    if (!grid) return;

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

// Initialize when page loads
document.addEventListener('DOMContentLoaded', renderProducts);

function addToCart(productId, quantity) {
    fetch('process/add_to_cart.php?product_id=' + productId + '&quantity=' + quantity, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            // Show success message
            showNotification('Product added to cart!', 'success');
            // Update cart count
            updateCartCount();
        } else {
            showNotification(data.message || 'Error adding to cart', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error adding to cart', 'error');
    });
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i> ${message}`;
    notification.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        padding: 15px 20px;
        border-radius: 10px;
        color: white;
        font-weight: 500;
        z-index: 1000;
        animation: slideIn 0.3s ease;
        background: ${type === 'success' ? '#22c55e' : '#ef4444'};
    `;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

function updateCartCount() {
    fetch('process/get_cart_count.php')
    .then(response => response.json())
    .then(data => {
        const badges = document.querySelectorAll('.badge');
        badges.forEach(badge => {
            badge.textContent = data.count;
        });
    });
}

// Add CSS animation
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
`;
document.head.appendChild(style);

document.getElementById('sInput').addEventListener('keypress', function(e) {
    if(e.key === 'Enter') {
        location.href='search.php?q=' + this.value;
    }
});

document.getElementById('sInput').addEventListener('keypress', function(e) {
    if(e.key === 'Enter') {
        location.href='search.php?q=' + this.value;
    }
});