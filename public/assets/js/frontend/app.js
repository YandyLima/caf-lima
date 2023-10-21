let shopping;

function updateCartCount() {
    const products = JSON.parse(localStorage.getItem("products")) || {};
    const totalCount = Object.values(products).reduce((acc, curr) => acc + curr.amount, 0);
    const cartCount = document.getElementById("cart-count");
    if (cartCount) {
        cartCount.textContent = totalCount > 0 ? totalCount.toString() + '+' : "0";
    }
}

async function productCalculation() {
    try {
        const response = await axios.post('/api-product-calculation', {
            'products': JSON.parse(localStorage.getItem("products"))
        });
        const total = response.data.total
        const cartTotalPrice = document.getElementById("cart-total-price");
        if (cartTotalPrice) {
            cartTotalPrice.textContent = total > 0 ? total.toFixed(2) : "0.00";
        }
        window.shopping = response.data;
        setTimeout(function () {
            const loaderElement = document.getElementById("loader");
            loaderElement.classList.add("d-none");
        }, 600);

    } catch (error) {
        console.error(error);
        location.reload()
    }
}

document.addEventListener("DOMContentLoaded", function () {
    updateCartCount()
    productCalculation();
});


function showToast(title, message, color, iconClass) {
    const toast = document.getElementById('liveToast');
    toast.querySelector('.toast-header span').textContent = title;
    toast.querySelector('.toast-body').textContent = message;
    toast.querySelector('.toast-header small').textContent = new Date().toLocaleString();
    const icon = toast.querySelector('.toast-header i');
    icon.className = iconClass;
    toast.classList.remove('bg-success-subtle', 'bg-info-subtle', 'bg-warning-subtle', 'bg-danger-subtle');
    toast.classList.add(`bg-${color}-subtle`);
    new bootstrap.Toast(toast).show();
}
