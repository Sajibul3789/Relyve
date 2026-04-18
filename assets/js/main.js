// assets/js/main.js

const products = [
    { id: 1, name: "Samsung Galaxy S25 Ultra", price: "189999", old: "199999", disc: "5%" },
    { id: 2, name: "Xiaomi Redmi Note 14 Pro", price: "32999", old: "37999", disc: "13%" },
    { id: 3, name: "Apple AirPods Pro 2", price: "24999", old: "27999", disc: "11%" },
    { id: 4, name: "Sony WH-1000XM5", price: "44999", old: "49999", disc: "10%" },
    { id: 5, name: "OnePlus 13R", price: "59999", old: "64999", disc: "8%" }
];

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