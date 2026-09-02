'use strict';

function loadLowStockWidget() {
    const container = document.getElementById('low-stock-widget');

    if (!container) {
        return;
    }

    const tbody = container.querySelector('tbody');
    const spinner = container.querySelector('.widget-spinner');
    const empty = container.querySelector('.widget-empty');

    fetch('/api/products/low-stock', {
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(function (response) {
        if (!response.ok) {
            throw new Error('Could not load products');
        }

        return response.json();
    })
    .then(function (products) {
        if (spinner) {
            spinner.style.display = 'none';
        }

        let rows = '';

        products.forEach(function (product) {
            const qty = parseInt(product.quantity, 10);
            let status = 'Low stock';

            if (qty === 0) {
                status = 'Out of stock';
            }

            rows += `
                <tr>
                    <td>${product.name}</td>
                    <td>${product.category_name ? product.category_name : '-'}</td>
                    <td>${product.sku ? product.sku : '-'}</td>
                    <td>${qty}</td>
                    <td>${status}</td>
                </tr>
            `;
        });

        if (products.length === 0) {
            if (empty) {
                empty.style.display = '';
            }

            return;
        }

        tbody.innerHTML = rows;
    })
    .catch(function () {
        if (spinner) {
            spinner.style.display = 'none';
        }

        tbody.innerHTML = `
            <tr>
                <td colspan="5" class="text-center text-danger">
                    Could not load stock data.
                </td>
            </tr>
        `;
    });
}

document.addEventListener('DOMContentLoaded', function () {
    loadLowStockWidget();
});