<?php
/**
 * Product Detail View — Dynamic Render Architecture
 * 
 * PHP: Fetches product from DB → serializes to window.__PRODUCT_DATA__
 * JS:  Reads that data → renders all UI components reactively
 *      Related products fetched async via fetch() → rendered on demand
 */

// --- PHP Data Layer ---
if (!isset($_GET['id'])) {
    header('location: index.php?act=shop');
    exit;
}

$product_id = intval($_GET['id']);
$conn = connectdb();

// Fetch product + category
$stmt = $conn->prepare("
    SELECT p.*, c.name as category_name 
    FROM products p 
    LEFT JOIN categories c ON p.category_id = c.id 
    WHERE p.id = ? AND p.status = 1
");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header('location: index.php?act=shop');
    exit;
}

// Increment view count
$conn->prepare("UPDATE products SET view = view + 1 WHERE id = ?")->execute([$product_id]);

// Inject product data safely as JSON for JS renderer
$product_json = json_encode([
    'id'            => intval($product['id']),
    'name'          => $product['name'],
    'img'           => $product['img'],
    'price'         => floatval($product['price']),
    'old_price'     => $product['old_price'] ? floatval($product['old_price']) : null,
    'quantity'      => intval($product['quantity']),
    'view'          => intval($product['view']) + 1,
    'category_id'   => intval($product['category_id']),
    'category_name' => $product['category_name'],
    'status'        => intval($product['status']),
], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);

// Session data for add-to-cart
$is_logged_in = isset($_SESSION['user']) ? 'true' : 'false';
$user_id = isset($_SESSION['user']) ? intval($_SESSION['user']['id']) : 0;

// Wishlist status (server-side check)
$in_wishlist = ($user_id > 0) ? is_in_wishlist($user_id, $product_id) : false;
$wishlist_init = $in_wishlist ? 'true' : 'false';
?>

<!-- Page-level meta update via JS -->
<script>
window.__PRODUCT_DATA__    = <?= $product_json ?>;
window.__IS_LOGGED_IN__    = <?= $is_logged_in ?>;
window.__USER_ID__         = <?= $user_id ?>;
window.__IN_WISHLIST__     = <?= $wishlist_init ?>;
</script>

<!-- =====================================================
     PRODUCT DETAIL — SHELL (Static Skeleton)
     JS fills in all dynamic content after DOM ready
     ===================================================== -->
<div class="product-detail-page">

    <!-- Breadcrumb — rendered by JS -->
    <nav id="pd-breadcrumb" class="container pd-breadcrumb" aria-label="breadcrumb"></nav>

    <!-- Main Product Section — rendered by JS -->
    <section class="container pd-main-section" id="pd-main-section"></section>

    <!-- Tabs (Description / Specs / Reviews) — rendered by JS -->
    <div class="container mt-5" id="pd-tabs-section"></div>

    <!-- Related Products — lazy loaded by JS -->
    <div class="container mt-5 mb-5" id="pd-related-section"></div>

</div>

<!-- ================================================================
     PRODUCT DETAIL STYLES
     ================================================================ -->
<style>
/* ── Layout ── */
.product-detail-page { padding-bottom: 60px; }

.pd-breadcrumb {
    padding: 18px 0 10px;
    font-size: 13px;
    color: var(--bs-secondary);
}
.pd-breadcrumb a { color: var(--bs-secondary); text-decoration: none; }
.pd-breadcrumb a:hover { color: var(--bs-primary); }
.pd-breadcrumb .sep { margin: 0 6px; opacity: .4; }

/* ── Gallery ── */
.pd-gallery {
    position: sticky;
    top: 80px;
}
.pd-main-img-wrap {
    border: 1px solid #e8e8e8;
    border-radius: 12px;
    overflow: hidden;
    background: var(--bs-body-bg);
    aspect-ratio: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: zoom-in;
    position: relative;
}
.pd-main-img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    transition: transform .4s ease;
}
.pd-main-img-wrap:hover .pd-main-img { transform: scale(1.06); }

.pd-badge-wrap {
    position: absolute;
    top: 12px;
    left: 12px;
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.pd-badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: .5px;
}
.pd-badge-sale { background: #ef4444; color: #fff; }
.pd-badge-hot  { background: #f97316; color: #fff; }
.pd-badge-out  { background: #6b7280; color: #fff; }

/* ── Info Panel ── */
.pd-info {}
.pd-cat-link {
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: var(--bs-primary);
    text-decoration: none;
}
.pd-title {
    font-size: clamp(1.3rem, 2vw, 1.75rem);
    font-weight: 700;
    line-height: 1.3;
    margin: 8px 0 14px;
}
.pd-meta {
    display: flex;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
    margin-bottom: 18px;
    font-size: 13px;
    color: var(--bs-secondary);
}
.pd-stars { color: #f59e0b; font-size: 15px; letter-spacing: 1px; }
.pd-views { display: flex; align-items: center; gap: 4px; }

.pd-price-wrap { margin-bottom: 20px; }
.pd-price {
    font-size: 2rem;
    font-weight: 800;
    color: var(--bs-primary);
    line-height: 1;
}
.pd-old-price {
    font-size: 1.05rem;
    text-decoration: line-through;
    color: var(--bs-secondary);
    margin-left: 10px;
}
.pd-discount-pct {
    display: inline-block;
    background: #fee2e2;
    color: #dc2626;
    font-size: 12px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 20px;
    margin-left: 8px;
    vertical-align: middle;
}

.pd-stock-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 5px 14px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 22px;
}
.pd-stock-badge.in-stock { background: #dcfce7; color: #16a34a; }
.pd-stock-badge.low-stock { background: #fef3c7; color: #d97706; }
.pd-stock-badge.out-of-stock { background: #fee2e2; color: #dc2626; }
.pd-stock-badge .dot {
    width: 7px; height: 7px; border-radius: 50%; background: currentColor;
    animation: pulse-dot 1.5s infinite;
}
@keyframes pulse-dot {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: .5; transform: scale(.8); }
}

/* ── Quantity Selector ── */
.pd-qty-wrap {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 22px;
    flex-wrap: wrap;
}
.pd-qty-label { font-weight: 600; font-size: 14px; min-width: 60px; }
.qty-control {
    display: flex;
    align-items: center;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    overflow: hidden;
}
.qty-btn {
    width: 40px; height: 40px;
    border: none;
    background: transparent;
    font-size: 18px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background .2s;
    color: var(--bs-body-color);
}
.qty-btn:hover:not(:disabled) { background: var(--bs-primary); color: #fff; }
.qty-btn:disabled { opacity: .35; cursor: not-allowed; }
.qty-input {
    width: 52px;
    border: none;
    text-align: center;
    font-weight: 700;
    font-size: 16px;
    background: transparent;
    color: var(--bs-body-color);
    outline: none;
}

/* ── Action Buttons ── */
.pd-actions { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 28px; }
.pd-btn-cart {
    flex: 1;
    min-width: 180px;
    padding: 13px 28px;
    border-radius: 10px;
    font-weight: 700;
    font-size: 15px;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all .25s;
    background: var(--bs-primary);
    color: #fff;
}
.pd-btn-cart:hover:not(:disabled) { filter: brightness(1.1); transform: translateY(-1px); box-shadow: 0 6px 20px rgba(0,0,0,.15); }
.pd-btn-cart:disabled { background: #9ca3af; cursor: not-allowed; transform: none; }
.pd-btn-wish {
    width: 48px; height: 48px;
    border-radius: 10px;
    border: 1.5px solid #e2e8f0;
    background: transparent;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    transition: all .25s;
    color: var(--bs-body-color);
}
.pd-btn-wish:hover { border-color: #ef4444; color: #ef4444; background: #fee2e2; }
.pd-btn-wish.active { border-color: #ef4444; color: #fff; background: #ef4444; }

/* ── Info List ── */
.pd-info-list { list-style: none; padding: 0; margin: 0 0 22px; }
.pd-info-list li {
    display: flex;
    align-items: flex-start;
    gap: 8px;
    padding: 7px 0;
    border-bottom: 1px solid var(--bs-border-color);
    font-size: 14px;
}
.pd-info-list li:last-child { border-bottom: none; }
.pd-info-list .key { font-weight: 600; min-width: 120px; color: var(--bs-secondary); }
.pd-info-list .val { flex: 1; }

/* ── Guarantee row ── */
.pd-guarantee {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    padding: 16px;
    background: var(--bs-tertiary-bg, #f8f9fa);
    border-radius: 10px;
    margin-top: 4px;
}
.pd-guarantee-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12.5px;
    color: var(--bs-secondary);
}
.pd-guarantee-item i { color: var(--bs-primary); font-size: 16px; }

/* ── Tabs ── */
.pd-tab-nav {
    display: flex;
    gap: 0;
    border-bottom: 2px solid var(--bs-border-color);
    margin-bottom: 24px;
}
.pd-tab-btn {
    padding: 12px 24px;
    border: none;
    background: none;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    color: var(--bs-secondary);
    border-bottom: 2px solid transparent;
    margin-bottom: -2px;
    transition: all .2s;
}
.pd-tab-btn.active { color: var(--bs-primary); border-bottom-color: var(--bs-primary); }
.pd-tab-btn:hover:not(.active) { color: var(--bs-body-color); }
.pd-tab-content { display: none; animation: fadeIn .3s ease; }
.pd-tab-content.active { display: block; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: none; } }

/* ── Related Products ── */
.pd-related-title {
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 24px;
    padding-bottom: 10px;
    border-bottom: 2px solid var(--bs-border-color);
    display: flex;
    align-items: center;
    gap: 8px;
}
.pd-related-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 18px;
}
@media (max-width: 576px) {
    .pd-related-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
}

/* Related card */
.pd-rel-card {
    border-radius: 10px;
    overflow: hidden;
    background: var(--bs-body-bg);
    border: 1px solid var(--bs-border-color);
    transition: all .25s;
    cursor: pointer;
    text-decoration: none;
    display: block;
    color: inherit;
}
.pd-rel-card:hover {
    border-color: var(--bs-primary);
    box-shadow: 0 8px 24px rgba(0,0,0,.1);
    transform: translateY(-3px);
    color: inherit;
    text-decoration: none;
}
.pd-rel-img-wrap {
    aspect-ratio: 1;
    overflow: hidden;
    background: var(--bs-tertiary-bg, #f8f9fa);
}
.pd-rel-img { width: 100%; height: 100%; object-fit: contain; transition: transform .3s; padding: 8px; }
.pd-rel-card:hover .pd-rel-img { transform: scale(1.05); }
.pd-rel-body { padding: 10px 12px 12px; }
.pd-rel-name {
    font-size: 12.5px;
    font-weight: 600;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    margin-bottom: 6px;
    line-height: 1.4;
}
.pd-rel-price { font-size: 13.5px; font-weight: 800; color: var(--bs-primary); }
.pd-rel-old { font-size: 11px; color: var(--bs-secondary); text-decoration: line-through; margin-left: 4px; }

/* Skeleton loader */
.pd-skeleton {
    background: linear-gradient(90deg, var(--bs-tertiary-bg,#f0f0f0) 25%, #e0e0e0 50%, var(--bs-tertiary-bg,#f0f0f0) 75%);
    background-size: 200% 100%;
    animation: shimmer 1.4s infinite;
    border-radius: 6px;
}
@keyframes shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }

/* Toast */
.pd-toast {
    position: fixed;
    bottom: 28px;
    right: 28px;
    z-index: 9999;
    background: #1e293b;
    color: #fff;
    padding: 12px 20px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 10px;
    box-shadow: 0 8px 30px rgba(0,0,0,.3);
    transform: translateY(20px);
    opacity: 0;
    transition: all .3s ease;
    pointer-events: none;
}
.pd-toast.show { transform: none; opacity: 1; }
.pd-toast.success .toast-icon { color: #4ade80; }
.pd-toast.error   .toast-icon { color: #f87171; }

/* Dark mode compatibility */
[data-theme="dark"] .pd-main-img-wrap { border-color: #374151; }
[data-theme="dark"] .qty-control { border-color: #374151; }
[data-theme="dark"] .pd-btn-wish { border-color: #374151; }
[data-theme="dark"] .pd-skeleton {
    background: linear-gradient(90deg, #1f2937 25%, #374151 50%, #1f2937 75%);
    background-size: 200% 100%;
}

/* Specs table */
.pd-specs-table { width: 100%; border-collapse: collapse; font-size: 14px; }
.pd-specs-table tr:nth-child(even) td { background: var(--bs-tertiary-bg,#f8f9fa); }
.pd-specs-table td { padding: 10px 14px; border-bottom: 1px solid var(--bs-border-color); }
.pd-specs-table td:first-child { font-weight: 600; width: 35%; color: var(--bs-secondary); }
</style>

<!-- ================================================================
     DYNAMIC RENDERER — Main JS Engine
     ================================================================ -->
<script>
(function() {
    'use strict';

    /* ══════════════════════════════════════════════════════
       UTILITY HELPERS
       ══════════════════════════════════════════════════════ */
    const fmt = (n) => new Intl.NumberFormat('vi-VN').format(Math.round(n)) + ' đ';

    const el = (tag, attrs = {}, ...children) => {
        const e = document.createElement(tag);
        Object.entries(attrs).forEach(([k, v]) => {
            if (v === '' && (k === 'disabled' || k === 'readonly')) return;
            if (k === 'class') e.className = v;
            else if (k === 'html') e.innerHTML = v;
            else if (k.startsWith('on')) e.addEventListener(k.slice(2), v);
            else e.setAttribute(k, v);
        });
        children.forEach(c => c && e.appendChild(typeof c === 'string' ? document.createTextNode(c) : c));
        return e;
    };

    const mount = (id, node) => {
        const container = document.getElementById(id);
        if (container) { container.innerHTML = ''; container.appendChild(node); }
    };

    // Toast notification system
    let toastTimer = null;
    function showToast(msg, type = 'success') {
        let toast = document.getElementById('pd-global-toast');
        if (!toast) {
            toast = el('div', { class: 'pd-toast', id: 'pd-global-toast' });
            document.body.appendChild(toast);
        }
        toast.className = `pd-toast ${type}`;
        toast.innerHTML = `<span class="toast-icon"><i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i></span><span>${msg}</span>`;
        requestAnimationFrame(() => toast.classList.add('show'));
        clearTimeout(toastTimer);
        toastTimer = setTimeout(() => toast.classList.remove('show'), 3200);
    }

    /* ══════════════════════════════════════════════════════
       COMPONENT: BREADCRUMB
       ══════════════════════════════════════════════════════ */
    function renderBreadcrumb(product) {
        const nav = el('div', { class: 'd-flex align-items-center flex-wrap gap-1' });
        const items = [
            { label: '<i class="fas fa-home"></i>', href: 'index.php' },
            { label: 'Cửa hàng', href: 'index.php?act=shop' },
            { label: product.category_name, href: `index.php?act=shop&idcat=${product.category_id}` },
            { label: product.name, href: null },
        ];
        items.forEach((item, i) => {
            if (i > 0) nav.appendChild(el('span', { class: 'sep', html: '<i class="fas fa-chevron-right" style="font-size:9px;"></i>' }));
            if (item.href) {
                nav.appendChild(el('a', { href: item.href, html: item.label }));
            } else {
                const cur = el('span', { style: 'font-weight:600;color:var(--bs-body-color);max-width:280px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;display:inline-block;vertical-align:middle;' });
                cur.textContent = item.label;
                nav.appendChild(cur);
            }
        });
        mount('pd-breadcrumb', nav);
    }

    /* ══════════════════════════════════════════════════════
       COMPONENT: GALLERY (Image Viewer)
       ══════════════════════════════════════════════════════ */
    function renderGallery(product) {
        
        const isOutOfStock = product.quantity <= 0;
        const hasDiscount  = product.old_price && product.old_price > product.price;
        const discPct      = hasDiscount ? Math.round((product.old_price - product.price) / product.old_price * 100) : 0;
        console.log(isOutOfStock);
        const wrap = el('div', { class: 'pd-main-img-wrap' });

        // Badges
        const badges = el('div', { class: 'pd-badge-wrap' });
        if (isOutOfStock)  badges.appendChild(el('span', { class: 'pd-badge pd-badge-out', html: 'Hết hàng' }));
        if (hasDiscount)   badges.appendChild(el('span', { class: 'pd-badge pd-badge-sale', html: `-${discPct}%` }));
        if (product.view > 50) badges.appendChild(el('span', { class: 'pd-badge pd-badge-hot', html: '🔥 Hot' }));
        wrap.appendChild(badges);

        // Main image
        const img = el('img', {
            class: 'pd-main-img',
            src: product.img || 'assets/images/placeholder.png',
            alt: product.name,
            id: 'pd-main-img'
        });
        img.onerror = () => { img.src = 'data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 200 200"><rect fill="%23f3f4f6" width="200" height="200"/><text fill="%239ca3af" font-family="sans-serif" font-size="14" x="50%" y="50%" text-anchor="middle" dy=".3em">No Image</text></svg>'; };

        // Zoom on click
        wrap.addEventListener('click', () => openLightbox(img.src, product.name));
        wrap.appendChild(img);

        return el('div', { class: 'pd-gallery' }, wrap);
    }

    /* ── Lightbox ── */
    function openLightbox(src, alt) {
        const overlay = el('div', {
            style: 'position:fixed;inset:0;z-index:99999;background:rgba(0,0,0,.9);display:flex;align-items:center;justify-content:center;cursor:zoom-out;',
            onclick: function() { document.body.removeChild(overlay); }
        });
        const img = el('img', {
            src, alt,
            style: 'max-width:90vw;max-height:90vh;object-fit:contain;border-radius:8px;box-shadow:0 20px 60px rgba(0,0,0,.5);'
        });
        overlay.appendChild(img);
        document.body.appendChild(overlay);
    }

    /* ══════════════════════════════════════════════════════
       COMPONENT: PRODUCT INFO (Right Panel)
       ══════════════════════════════════════════════════════ */
    function renderInfo(product) {
        const isOutOfStock = product.quantity <= 0;
        const hasDiscount  = product.old_price && product.old_price > product.price;
        const discPct      = hasDiscount ? Math.round((product.old_price - product.price) / product.old_price * 100) : 0;

        const panel = el('div', { class: 'pd-info' });

        // Category link
        panel.appendChild(el('a', {
            class: 'pd-cat-link',
            href: `index.php?act=shop&idcat=${product.category_id}`
        }, product.category_name || 'Sản phẩm'));

        // Title
        const title = el('h1', { class: 'pd-title' });
        title.textContent = product.name;
        panel.appendChild(title);

        // Stars + views
        const meta = el('div', { class: 'pd-meta' });
        meta.innerHTML = `
            <span id="pd-rating-stars-wrap">
                <span class="pd-stars">★★★★★</span>
                <span style="font-weight:600; margin-left: 5px;">5.0</span>
                <span style="margin-left: 5px;">(0 đánh giá)</span>
            </span>
            <span class="pd-views"><i class="fas fa-eye"></i> ${product.view.toLocaleString('vi-VN')} lượt xem</span>
        `;
        panel.appendChild(meta);

        // Price
        const priceWrap = el('div', { class: 'pd-price-wrap' });
        const priceMain = el('span', { class: 'pd-price' }, fmt(product.price));
        priceWrap.appendChild(priceMain);
        if (hasDiscount) {
            priceWrap.appendChild(el('span', { class: 'pd-old-price' }, fmt(product.old_price)));
            priceWrap.appendChild(el('span', { class: 'pd-discount-pct' }, `-${discPct}%`));
            // Savings
            const saved = el('div', { style: 'margin-top:6px;font-size:13px;color:#16a34a;font-weight:600;' });
            saved.textContent = `Tiết kiệm: ${fmt(product.old_price - product.price)}`;
            priceWrap.appendChild(saved);
        }
        panel.appendChild(priceWrap);

        // Stock badge
        let stockClass, stockIcon, stockText;
        if (isOutOfStock) {
            stockClass = 'out-of-stock'; stockIcon = 'times-circle'; stockText = 'Hết hàng';
        } else if (product.quantity <= 10) {
            stockClass = 'low-stock'; stockIcon = 'exclamation-circle'; stockText = `Sắp hết — Còn ${product.quantity} sản phẩm`;
        } else {
            stockClass = 'in-stock'; stockIcon = 'check-circle'; stockText = `Còn hàng (${product.quantity} sản phẩm)`;
        }
        const stockBadge = el('div', { class: `pd-stock-badge ${stockClass}` });
        stockBadge.innerHTML = `<span class="dot"></span><i class="fas fa-${stockIcon}"></i> ${stockText}`;
        panel.appendChild(stockBadge);

        // ── Quantity selector ──
        const qtyWrap = el('div', { class: 'pd-qty-wrap' });
        const qtyLabel = el('span', { class: 'pd-qty-label' }, 'Số lượng:');
        const qtyCtrl = el('div', { class: 'qty-control' });

        let qty = 1;
        const qtyInput = el('input', {
            type: 'number',
            class: 'qty-input',
            value: '1',
            min: '1',
            max: String(product.quantity),
            id: 'pd-qty-input',
            readonly: isOutOfStock ? 'readonly' : ''
        });

        const btnDec = el('button', { class: 'qty-btn', id: 'qty-dec', disabled: isOutOfStock ? 'disabled' : '' }, '−');
        const btnInc = el('button', { class: 'qty-btn', id: 'qty-inc', disabled: isOutOfStock ? 'disabled' : '' }, '+');

        btnDec.addEventListener('click', () => {
            qty = Math.max(1, qty - 1);
            qtyInput.value = qty;
            btnDec.disabled = qty <= 1;
            btnInc.disabled = qty >= product.quantity;
        });
        btnInc.addEventListener('click', () => {
            qty = Math.min(product.quantity, qty + 1);
            qtyInput.value = qty;
            btnDec.disabled = qty <= 1;
            btnInc.disabled = qty >= product.quantity;
        });
        qtyInput.addEventListener('change', () => {
            qty = Math.min(product.quantity, Math.max(1, parseInt(qtyInput.value) || 1));
            qtyInput.value = qty;
            btnDec.disabled = qty <= 1;
            btnInc.disabled = qty >= product.quantity;
        });

        qtyCtrl.append(btnDec, qtyInput, btnInc);
        if (!isOutOfStock) qtyWrap.append(qtyLabel, qtyCtrl);
        panel.appendChild(qtyWrap);

        // ── Action buttons ──
        const actionsRow = el('div', { class: 'pd-actions' });

        const btnCart = el('button', {
            class: 'pd-btn-cart',
            id: 'pd-btn-add-to-cart',
            disabled: isOutOfStock ? 'disabled' : ''
        });
        btnCart.innerHTML = isOutOfStock
            ? '<i class="fas fa-ban"></i> Hết hàng'
            : '<i class="fas fa-shopping-cart"></i> Thêm vào giỏ hàng';

        btnCart.addEventListener('click', () => addToCart(product, parseInt(qtyInput.value)));

        const btnWish = el('button', { class: `pd-btn-wish${window.__IN_WISHLIST__ ? ' active' : ''}`, title: 'Thêm vào yêu thích', id: 'pd-btn-wish' });
        btnWish.innerHTML = window.__IN_WISHLIST__ ? '<i class="fas fa-heart"></i>' : '<i class="far fa-heart"></i>';
        btnWish.addEventListener('click', () => toggleWishlist(product.id, btnWish));

        actionsRow.append(btnCart, btnWish);
        panel.appendChild(actionsRow);

        // ── Info list ──
        const infoList = el('ul', { class: 'pd-info-list' });
        const infoItems = [
            ['Danh mục', `<a href="index.php?act=shop&idcat=${product.category_id}" style="color:var(--bs-primary);">${product.category_name}</a>`],
            ['Mã sản phẩm', `#${String(product.id).padStart(6, '0')}`],
            ['Tình trạng', isOutOfStock ? '<span style="color:#dc2626;font-weight:600;">Hết hàng</span>' : '<span style="color:#16a34a;font-weight:600;">Còn hàng</span>'],
            ['Lượt xem', product.view.toLocaleString('vi-VN')],
        ];
        infoItems.forEach(([key, val]) => {
            const li = el('li');
            li.innerHTML = `<span class="key">${key}</span><span class="val">${val}</span>`;
            infoList.appendChild(li);
        });
        panel.appendChild(infoList);

        // ── Guarantee badges ──
        const guarantee = el('div', { class: 'pd-guarantee' });
        const guaranteeItems = [
            ['fa-shield-alt', 'Bảo hành chính hãng'],
            ['fa-undo', 'Đổi trả 7 ngày'],
            ['fa-truck', 'Miễn phí vận chuyển'],
            ['fa-headset', 'Hỗ trợ 24/7'],
        ];
        guaranteeItems.forEach(([icon, text]) => {
            const item = el('div', { class: 'pd-guarantee-item' });
            item.innerHTML = `<i class="fas ${icon}"></i><span>${text}</span>`;
            guarantee.appendChild(item);
        });
        panel.appendChild(guarantee);

        return panel;
    }

    /* ══════════════════════════════════════════════════════
       WISHLIST HANDLER
       ══════════════════════════════════════════════════════ */
    function toggleWishlist(productId, btnNode) {
        if (!window.__IS_LOGGED_IN__) {
            showToast('Vui lòng đăng nhập để lưu vào yêu thích!', 'error');
            setTimeout(() => { window.location.href = 'index.php?act=login'; }, 1500);
            return;
        }

        // Optimistic UI update
        const wasActive = btnNode.classList.contains('active');
        btnNode.classList.toggle('active');
        btnNode.innerHTML = wasActive ? '<i class="far fa-heart"></i>' : '<i class="fas fa-heart"></i>';

        fetch('api/wishlist.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ product_id: productId, action: 'toggle' })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                window.__IN_WISHLIST__ = data.in_wishlist;
                // Sync button state with actual server state in case it differs
                btnNode.classList.toggle('active', data.in_wishlist);
                btnNode.innerHTML = data.in_wishlist ? '<i class="fas fa-heart"></i>' : '<i class="far fa-heart"></i>';
                showToast(data.in_wishlist ? 'Đã thêm vào yêu thích ❤️' : 'Đã xóa khỏi yêu thích', data.in_wishlist ? 'success' : 'error');
                
                // Update global wishlist counter if it exists in header
                const wishBadge = document.querySelector('.wishlist-count-badge');
                if (wishBadge && data.count !== undefined) wishBadge.textContent = data.count;
            } else {
                // Revert UI on failure
                btnNode.classList.toggle('active', wasActive);
                btnNode.innerHTML = wasActive ? '<i class="fas fa-heart"></i>' : '<i class="far fa-heart"></i>';
                showToast(data.message || 'Lỗi hệ thống', 'error');
            }
        })
        .catch(err => {
            // Revert UI on failure
            btnNode.classList.toggle('active', wasActive);
            btnNode.innerHTML = wasActive ? '<i class="fas fa-heart"></i>' : '<i class="far fa-heart"></i>';
            showToast('Không thể kết nối đến máy chủ', 'error');
        });
    }

    /* ══════════════════════════════════════════════════════
       ADD TO CART HANDLER
       ══════════════════════════════════════════════════════ */
    function addToCart(product, qty) {
        if (!window.__IS_LOGGED_IN__) {
            showToast('Vui lòng đăng nhập để thêm vào giỏ hàng!', 'error');
            setTimeout(() => { window.location.href = 'index.php?act=login'; }, 1500);
            return;
        }

        const btn = document.getElementById('pd-btn-add-to-cart');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang thêm...';

        // Submit each qty unit (simplest approach that works with current backend)
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'index.php?act=addtocart';
        form.style.display = 'none';
        
        const fields = {
            addtocart: 'add',
            id: product.id,
            name: product.name,
            img: product.img,
            price: product.price
        };
        Object.entries(fields).forEach(([k, v]) => {
            const inp = document.createElement('input');
            inp.type = 'hidden';
            inp.name = k;
            inp.value = v;
            form.appendChild(inp);
        });

        // Use fetch to call the API to simulate add (avoids full page reload)
        fetch('api/cart_update.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ product_id: product.id, qty: qty, price: product.price })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showToast(`✅ Đã thêm ${qty} "${product.name.substring(0,30)}..." vào giỏ hàng!`);
                // Update cart badge in header
                const badge = document.querySelector('.badge-count');
                if (badge) badge.textContent = data.cart_count || (parseInt(badge.textContent)||0) + qty;
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-shopping-cart"></i> Thêm vào giỏ hàng';
            } else {
                showToast(data.message || 'Lỗi: Không thể thêm vào giỏ hàng', 'error');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-shopping-cart"></i> Thêm vào giỏ hàng';
            }
        })
        .catch(err => {
            console.error('Fetch error:', err);
            showToast('Lỗi kết nối hoặc xử lý dữ liệu!', 'error');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-shopping-cart"></i> Thêm vào giỏ hàng';
        });
    }

    /* ══════════════════════════════════════════════════════
       COMPONENT: TABS (Description / Specs / Reviews)
       ══════════════════════════════════════════════════════ */
    function renderTabs(product) {
        const section = el('div', {});

        // Tab nav
        const nav = el('div', { class: 'pd-tab-nav' });
        const tabs = [
            { id: 'desc',    label: 'Mô tả sản phẩm' },
            { id: 'specs',   label: 'Thông số kỹ thuật' },
            { id: 'reviews', label: 'Đánh giá (0)' },
        ];

        const contents = {};

        tabs.forEach((tab, i) => {
            const btn = el('button', { class: `pd-tab-btn${i === 0 ? ' active' : ''}` }, tab.label);
            btn.dataset.tab = tab.id;
            btn.addEventListener('click', () => switchTab(tab.id));
            nav.appendChild(btn);

            const content = el('div', { class: `pd-tab-content${i === 0 ? ' active' : ''}`, id: `pd-tab-${tab.id}` });
            contents[tab.id] = content;
        });

        section.appendChild(nav);

        // ── Description content ──
        contents.desc.innerHTML = `
            <div class="row g-4">
                <div class="col-md-8">
                    <h5 class="fw-bold mb-3">${product.name}</h5>
                    <p class="text-secondary">Sản phẩm thuộc danh mục <strong>${product.category_name}</strong>, 
                    được cung cấp bởi WindyStore với chất lượng được kiểm định chặt chẽ.</p>
                    <ul class="list-unstyled" style="margin-top:16px;">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Sản phẩm chính hãng 100%</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Đã qua kiểm tra chất lượng</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Bảo hành theo chính sách nhà sản xuất</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Hỗ trợ kỹ thuật 24/7</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Đóng gói cẩn thận, giao hàng toàn quốc</li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <div style="background:var(--bs-tertiary-bg,#f8f9fa);border-radius:12px;padding:20px;">
                        <h6 class="fw-bold mb-3"><i class="fas fa-info-circle text-primary me-2"></i>Thông tin nhanh</h6>
                        <div style="font-size:14px;line-height:2;">
                            <div><span style="color:var(--bs-secondary);min-width:100px;display:inline-block;">Giá bán:</span> <strong>${fmt(product.price)}</strong></div>
                            ${product.old_price ? `<div><span style="color:var(--bs-secondary);min-width:100px;display:inline-block;">Giá gốc:</span> <s style="color:var(--bs-secondary);">${fmt(product.old_price)}</s></div>` : ''}
                            <div><span style="color:var(--bs-secondary);min-width:100px;display:inline-block;">Tồn kho:</span> <strong>${product.quantity} sản phẩm</strong></div>
                            <div><span style="color:var(--bs-secondary);min-width:100px;display:inline-block;">Danh mục:</span> <a href="index.php?act=shop&idcat=${product.category_id}" style="color:var(--bs-primary);">${product.category_name}</a></div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // ── Specs content ──
        const specsData = generateSpecs(product);
        let specsHTML = `<table class="pd-specs-table">`;
        specsData.forEach(([key, val]) => {
            specsHTML += `<tr><td>${key}</td><td>${val}</td></tr>`;
        });
        specsHTML += `</table>`;
        contents.specs.innerHTML = specsHTML;

        // ── Reviews content ──
        contents.reviews.innerHTML = `
            <div class="text-center py-5">
                <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
                <p class="text-secondary mt-2">Đang tải đánh giá...</p>
            </div>
        `;

        Object.values(contents).forEach(c => section.appendChild(c));
        return section;
    }

    function switchTab(tabId) {
        document.querySelectorAll('.pd-tab-btn').forEach(b => b.classList.toggle('active', b.dataset.tab === tabId));
        document.querySelectorAll('.pd-tab-content').forEach(c => c.classList.toggle('active', c.id === `pd-tab-${tabId}`));
    }

    function generateSpecs(product) {
        return [
            ['Tên sản phẩm', product.name],
            ['Mã sản phẩm', `#${String(product.id).padStart(6, '0')}`],
            ['Danh mục', product.category_name],
            ['Giá bán', fmt(product.price)],
            ['Giá gốc', product.old_price ? fmt(product.old_price) : 'N/A'],
            ['Tình trạng kho', product.quantity > 0 ? `Còn ${product.quantity} sản phẩm` : 'Hết hàng'],
            ['Lượt xem', product.view.toLocaleString('vi-VN')],
            ['Bảo hành', '12 tháng'],
            ['Xuất xứ', 'Chính hãng'],
        ];
    }

    /* ══════════════════════════════════════════════════════
       COMPONENT: RELATED PRODUCTS (Async Loaded)
       ══════════════════════════════════════════════════════ */
    function renderRelatedSkeleton() {
        const wrap = el('div', {});
        const title = el('div', { class: 'pd-related-title' });
        title.innerHTML = '<i class="fas fa-fire text-danger"></i> Sản phẩm liên quan';
        wrap.appendChild(title);

        const grid = el('div', { class: 'pd-related-grid' });
        for (let i = 0; i < 6; i++) {
            const card = el('div', { style: 'border-radius:10px;overflow:hidden;border:1px solid var(--bs-border-color);' });
            card.innerHTML = `
                <div style="aspect-ratio:1;" class="pd-skeleton"></div>
                <div style="padding:10px 12px 12px;">
                    <div class="pd-skeleton" style="height:14px;margin-bottom:6px;border-radius:4px;"></div>
                    <div class="pd-skeleton" style="height:14px;width:60%;border-radius:4px;"></div>
                    <div class="pd-skeleton" style="height:16px;width:50%;margin-top:8px;border-radius:4px;"></div>
                </div>`;
            grid.appendChild(card);
        }
        wrap.appendChild(grid);
        mount('pd-related-section', wrap);
    }

    async function loadRelatedProducts(product) {
        renderRelatedSkeleton();
        try {
            const res = await fetch(`api/product.php?id=${product.id}`);
            const data = await res.json();
            if (!data.success || !data.related.length) {
                document.getElementById('pd-related-section').innerHTML = '';
                return;
            }
            renderRelated(data.related);
        } catch (e) {
            document.getElementById('pd-related-section').innerHTML = '';
        }
    }

    function renderRelated(products) {
        const wrap = el('div', {});
        const title = el('div', { class: 'pd-related-title' });
        title.innerHTML = '<i class="fas fa-fire text-danger"></i> Sản phẩm liên quan';
        wrap.appendChild(title);

        const grid = el('div', { class: 'pd-related-grid' });
        products.forEach(p => {
            const hasDiscount = p.old_price && p.old_price > p.price;
            const discPct = hasDiscount ? Math.round((p.old_price - p.price) / p.old_price * 100) : 0;

            const card = el('a', {
                class: 'pd-rel-card',
                href: `index.php?act=shop-single&id=${p.id}`
            });
            card.innerHTML = `
                <div class="pd-rel-img-wrap">
                    <img class="pd-rel-img" src="${p.img}" alt="${p.name}" 
                         onerror="this.src='data:image/svg+xml,<svg xmlns=\\'http://www.w3.org/2000/svg\\' width=\\'100\\' height=\\'100\\'><rect fill=\\'%23f3f4f6\\' width=\\'100\\' height=\\'100\\'/></svg>'">
                </div>
                <div class="pd-rel-body">
                    <div class="pd-rel-name">${p.name}</div>
                    <div>
                        <span class="pd-rel-price">${fmt(p.price)}</span>
                        ${hasDiscount ? `<span class="pd-rel-old">${fmt(p.old_price)}</span>` : ''}
                        ${hasDiscount ? `<span style="display:inline-block;background:#fee2e2;color:#dc2626;font-size:10px;font-weight:700;padding:1px 6px;border-radius:10px;margin-left:4px;">-${discPct}%</span>` : ''}
                    </div>
                    ${p.quantity <= 0 ? '<div style="font-size:11px;color:#dc2626;margin-top:4px;font-weight:600;">Hết hàng</div>' : ''}
                </div>`;
            grid.appendChild(card);
        });
        wrap.appendChild(grid);
        mount('pd-related-section', wrap);
    }

    /* ══════════════════════════════════════════════════════
       REVIEWS HANDLER (Dynamic load & submit)
       ══════════════════════════════════════════════════════ */
    function fetchAndRenderReviews(product) {
        fetch(`api/reviews.php?product_id=${product.id}`)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Update rating metadata in top info
                    const starsWrap = document.getElementById('pd-rating-stars-wrap');
                    if (starsWrap) {
                        const score = data.rating;
                        const count = data.count;
                        let starsHTML = '';
                        for (let i = 1; i <= 5; i++) {
                            starsHTML += i <= Math.round(score) ? '★' : '☆';
                        }
                        starsWrap.innerHTML = `
                            <span class="pd-stars" style="color: #f59e0b; font-size: 15px; letter-spacing: 1px;">${starsHTML}</span>
                            <span style="font-weight:600; margin-left: 5px;">${score}</span>
                            <span style="margin-left: 5px;">(${count} đánh giá)</span>
                        `;
                    }

                    // Update Tab Button label
                    const reviewsTabBtn = document.querySelector('.pd-tab-btn[data-tab="reviews"]');
                    if (reviewsTabBtn) {
                        reviewsTabBtn.textContent = `Đánh giá (${data.count})`;
                    }

                    // Populate Tab Content
                    const reviewsContainer = document.getElementById('pd-tab-reviews');
                    if (reviewsContainer) {
                        renderReviewsListAndForm(product, reviewsContainer, data.reviews, data.rating, data.count);
                    }
                }
            })
            .catch(err => console.error(err));
    }

    function renderReviewsListAndForm(product, container, reviews, avgRating, count) {
        container.innerHTML = '';
        
        const row = el('div', { class: 'row g-4' });
        const leftCol = el('div', { class: 'col-md-5' });
        const rightCol = el('div', { class: 'col-md-7' });
        
        // Left Column: Rating breakdown
        const ratingBox = el('div', { 
            style: 'background: var(--surface-2); border-radius: 12px; padding: 24px; text-align: center; border: 1px solid var(--border-c);'
        });
        
        const roundedRating = avgRating ? avgRating : 5.0;
        const fullStars = Math.round(roundedRating);
        const starsHTML = '★'.repeat(fullStars) + '☆'.repeat(5 - fullStars);
        
        ratingBox.innerHTML = `
            <h5 class="fw-bold mb-3">Đánh giá trung bình</h5>
            <div style="font-size: 48px; font-weight: 800; color: var(--theme-primary); line-height: 1;">${roundedRating}</div>
            <div style="color: #f59e0b; font-size: 20px; margin: 10px 0;">${starsHTML}</div>
            <div class="text-secondary small">Có ${count} đánh giá cho sản phẩm này</div>
        `;
        
        leftCol.appendChild(ratingBox);
        
        // Add form
        if (window.__IS_LOGGED_IN__) {
            const formBox = el('div', { 
                class: 'mt-4',
                style: 'background: var(--surface); border-radius: 12px; padding: 24px; border: 1px solid var(--border-c);'
            });
            formBox.innerHTML = `
                <h5 class="fw-bold mb-3">Viết đánh giá của bạn</h5>
                <form id="pd-review-form">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Chọn số sao:</label>
                        <div class="rating-stars-input" style="font-size: 28px; color: #d1d5db;">
                            <i class="far fa-star star-input-item text-warning" style="cursor:pointer;" data-value="1"></i>
                            <i class="far fa-star star-input-item text-warning" style="cursor:pointer;" data-value="2"></i>
                            <i class="far fa-star star-input-item text-warning" style="cursor:pointer;" data-value="3"></i>
                            <i class="far fa-star star-input-item text-warning" style="cursor:pointer;" data-value="4"></i>
                            <i class="far fa-star star-input-item text-warning" style="cursor:pointer;" data-value="5"></i>
                        </div>
                        <input type="hidden" id="pd-review-rating-value" value="0">
                    </div>
                    <div class="mb-3">
                        <label for="pd-review-content" class="form-label fw-semibold">Nội dung đánh giá:</label>
                        <textarea class="form-control" id="pd-review-content" rows="4" placeholder="Nhập cảm nhận của bạn về sản phẩm..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold">Gửi đánh giá</button>
                </form>
            `;
            
            // Add stars interaction
            const stars = formBox.querySelectorAll('.star-input-item');
            const ratingInput = formBox.querySelector('#pd-review-rating-value');
            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const val = parseInt(this.getAttribute('data-value'));
                    ratingInput.value = val;
                    stars.forEach((s, idx) => {
                        if (idx < val) {
                            s.classList.remove('far');
                            s.classList.add('fas');
                        } else {
                            s.classList.remove('fas');
                            s.classList.add('far');
                        }
                    });
                });
            });
            
            // Handle form submission
            const form = formBox.querySelector('#pd-review-form');
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const rating = parseInt(ratingInput.value);
                const content = formBox.querySelector('#pd-review-content').value.trim();
                
                if (rating === 0) {
                    showToast('Vui lòng chọn số sao đánh giá!', 'error');
                    return;
                }
                if (content === '') {
                    showToast('Vui lòng nhập nội dung đánh giá!', 'error');
                    return;
                }
                
                fetch('api/reviews.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ product_id: product.id, rating, content })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message);
                        fetchAndRenderReviews(product);
                    } else {
                        showToast(data.message, 'error');
                    }
                })
                .catch(err => {
                    showToast('Không thể gửi đánh giá!', 'error');
                });
            });
            
            leftCol.appendChild(formBox);
        } else {
            const loginPrompt = el('div', { 
                class: 'mt-4 text-center py-4 border rounded-3',
                style: 'background: var(--surface-2); border-color: var(--border-c);'
            });
            loginPrompt.innerHTML = `
                <p class="text-secondary mb-2">Vui lòng đăng nhập để viết đánh giá</p>
                <a href="index.php?act=login" class="btn btn-sm btn-primary fw-bold">Đăng nhập</a>
            `;
            leftCol.appendChild(loginPrompt);
        }
        
        // Right Column: Review List
        const reviewsList = el('div', { class: 'd-flex flex-column gap-3 w-100' });
        if (reviews.length === 0) {
            reviewsList.innerHTML = `
                <div class="text-center py-5 border rounded-3 bg-light text-secondary">
                    <div style="font-size:40px; margin-bottom:10px;">💬</div>
                    <p class="mb-0">Chưa có bình luận nào cho sản phẩm này.</p>
                </div>
            `;
        } else {
            reviews.forEach(r => {
                const item = el('div', { 
                    class: 'p-3 border rounded-3',
                    style: 'background: var(--surface); border-color: var(--border-c);'
                });
                const starsHTML = '★'.repeat(r.rating) + '☆'.repeat(5 - r.rating);
                const dateStr = new Date(r.created_at).toLocaleDateString('vi-VN', {
                    year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit'
                });
                
                item.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <strong class="text-dark-theme-compat">${r.fullname || r.username}</strong>
                            <span class="text-warning ms-2" style="letter-spacing:1px;">${starsHTML}</span>
                        </div>
                        <span class="text-secondary small">${dateStr}</span>
                    </div>
                    <p class="mb-0 text-secondary" style="font-size:14px; line-height: 1.5; white-space: pre-line;">${r.content}</p>
                `;
                reviewsList.appendChild(item);
            });
        }
        
        rightCol.appendChild(reviewsList);
        row.append(leftCol, rightCol);
        container.appendChild(row);
    }

    /* ══════════════════════════════════════════════════════
       MAIN RENDER ORCHESTRATOR
       ══════════════════════════════════════════════════════ */
    function renderProductPage(product) {
        // Update page title
        document.title = `${product.name} — WindyStore`;

        // 1. Breadcrumb
        renderBreadcrumb(product);

        // 2. Main section (gallery + info)
        const mainSection = el('div', { class: 'row g-4 g-lg-5 py-4' });
        const galleryCol  = el('div', { class: 'col-lg-6 col-md-12' });
        const infoCol     = el('div', { class: 'col-lg-6 col-md-12' });

        galleryCol.appendChild(renderGallery(product));
        infoCol.appendChild(renderInfo(product));
        mainSection.append(galleryCol, infoCol);
        mount('pd-main-section', mainSection);

        // 3. Tabs
        mount('pd-tabs-section', renderTabs(product));

        // 4. Fetch dynamic reviews
        fetchAndRenderReviews(product);

        // 5. Related products (async)
        loadRelatedProducts(product);
    }

    /* ══════════════════════════════════════════════════════
       BOOT — Execute when DOM is ready
       ══════════════════════════════════════════════════════ */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => renderProductPage(window.__PRODUCT_DATA__));
    } else {
        renderProductPage(window.__PRODUCT_DATA__);
    }

})();
</script>
