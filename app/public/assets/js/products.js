'use strict';

const searchInput = document.getElementById('search');
const categoryFilter = document.getElementById('categoryFilter');
const lowStockOnly = document.getElementById('lowStockOnly');
const statusEl = document.getElementById('status');

function getCards() {
    return document.querySelectorAll('.product-card');
}

function setStatus(message, className) {
    if (!statusEl) {
        return;
    }

    statusEl.className = className ? className : '';
    statusEl.textContent = message;
}

function buildCategoryOptions() {
    if (!categoryFilter) {
        return;
    }

    const cards = getCards();
    const categories = [];

    cards.forEach(function (card) {
        const category = (card.dataset.cat || '').trim();

        if (category !== '' && !categories.includes(category)) {
            categories.push(category);
        }
    });

    categories.sort();

    let options = '<option value="">All categories</option>';

    categories.forEach(function (category) {
        options += '<option value="' + category + '">' + category + '</option>';
    });

    categoryFilter.innerHTML = options;
}

function applyFilters() {
    const cards = getCards();
    const searchText = searchInput ? searchInput.value.trim().toLowerCase() : '';
    const selectedCategory = categoryFilter ? categoryFilter.value.trim().toLowerCase() : '';
    const showLowStockOnly = lowStockOnly ? lowStockOnly.checked : false;

    let shown = 0;

    cards.forEach(function (card) {
        const name = (card.dataset.name || '').toLowerCase();
        const sku = (card.dataset.sku || '').toLowerCase();
        const category = (card.dataset.cat || '').toLowerCase();

        const qtyInput = card.querySelector('.qty-input');
        let quantity = 999999;

        if (qtyInput) {
            quantity = parseInt(qtyInput.value, 10);
        }

        const matchesSearch =
            name.includes(searchText) ||
            sku.includes(searchText) ||
            category.includes(searchText);

        const matchesCategory = selectedCategory === '' || category === selectedCategory;
        const matchesLowStock = !showLowStockOnly || (!isNaN(quantity) && quantity <= 5);

        if (matchesSearch && matchesCategory && matchesLowStock) {
            card.style.display = '';
            shown++;
        } else {
            card.style.display = 'none';
        }
    });

    setStatus(shown + ' product(s) shown', 'text-muted');
}

function saveQuantity(input) {
    const id = input.dataset.id;
    const quantity = parseInt(input.value, 10);

    if (isNaN(quantity) || quantity < 0) {
        setStatus('Quantity must be 0 or more.', 'text-danger');
        return;
    }

    input.disabled = true;
    setStatus('Saving...', 'text-warning');

    fetch('/api/products/' + id + '/quantity', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            quantity: quantity
        })
    })
    .then(function (response) {
        return response.json();
    })
    .then(function (data) {
        if (!data.ok) {
            let message = 'Update failed.';

            if (data.errors && Array.isArray(data.errors)) {
                message = data.errors.join(' ');
            }

            setStatus(message, 'text-danger');
        } else {
            applyFilters();
            setStatus('Quantity saved.', 'text-success');
        }
    })
    .catch(function () {
        setStatus('Network error while saving.', 'text-danger');
    })
    .finally(function () {
        input.disabled = false;
    });
}

buildCategoryOptions();
applyFilters();

if (searchInput) {
    searchInput.addEventListener('input', applyFilters);
}

if (categoryFilter) {
    categoryFilter.addEventListener('change', applyFilters);
}

if (lowStockOnly) {
    lowStockOnly.addEventListener('change', applyFilters);
}

document.querySelectorAll('.qty-input').forEach(function (input) {
    input.addEventListener('change', function () {
        saveQuantity(input);
    });
});