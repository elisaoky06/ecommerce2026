<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../core/core.php";
require_once __DIR__ . "/../controllers/ProductController.php";
require_once __DIR__ . "/../controllers/CartController.php";

$controller = new ProductController();
$cartCtl    = new CartController();
$cartIp     = get_ip();
$cartCount  = $cartCtl->getCartCount($cartIp);
$cartTotal  = $cartCtl->getCartTotal($cartIp);

$proId   = (int)($_GET['pro_id'] ?? 0);
$catId   = (int)($_GET['cat'] ?? 0);
$brandId = (int)($_GET['brand'] ?? 0);
$q       = mb_substr(trim(strip_tags($_GET['q'] ?? '')), 0, 100);

$product = null;
if ($proId > 0) {
    $product = $controller->getProductById($proId);
    if (!$product) { header("Location: products.php"); exit; }
}

// Size options by category name
$sizes = [];
if ($product) {
    $cat = strtolower($product['cat_name']);
    if (preg_match('/shoe|sneaker|sandal|heel|boot|slipper|footwear/', $cat)) {
        $sizes = ['36','37','38','39','40','41','42','43','44','45'];
    } elseif (preg_match('/cloth|dress|shirt|top|trouser|pant|jacket|skirt|wear|apparel/', $cat)) {
        $sizes = ['XS','S','M','L','XL','XXL'];
    }
}

$categories = $controller->getAllCategories();
$brands     = $controller->getAllBrands();
$products   = $product ? [] : $controller->getFilteredProducts($catId, $brandId, $q);
$filtered   = ($catId > 0 || $brandId > 0 || $q !== '');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/style.css">
    <title><?= $product ? htmlspecialchars($product['product_title']) . ' | SHOPPN' : 'Shop | SHOPPN'; ?></title>
    <style>
    .shop-top { display: grid; grid-template-columns: 1fr auto 1fr; align-items: center; margin-bottom: 56px; padding-bottom: 20px; border-bottom: 1px solid #d8d3c8; font-size: 12px; letter-spacing: 0.18em; text-transform: uppercase; }
    .shop-top a { color: #0a0a0a; text-decoration: none; }
    .shop-top a:hover { color: #b89b6a; }
    .shop-top .top-logo { font-family: 'Cormorant Garamond', serif; font-size: 28px; letter-spacing: -0.02em; text-transform: none; }
    .shop-top .shop-right { justify-self: end; display: flex; gap: 28px; }
    .cart-link { display: inline-flex; align-items: center; gap: 8px; }
    .cart-link svg { width: 22px; height: 22px; }

    .cart-msg { margin: -32px 0 32px; padding: 14px 18px; background: #0a0a0a; color: #fff; font-size: 13px; letter-spacing: 0.04em; }
    .cart-msg a { color: #b89b6a; }

    .filter-bar { display: flex; flex-wrap: wrap; align-items: flex-end; gap: 24px 40px; margin: 0 0 24px; }
    .filter-bar > div { min-width: 220px; }
    .filter-bar .search-field { flex: 1; min-width: 260px; }
    .filter-bar select, .filter-bar input[type="text"] { width: 100%; }
    .filter-clear { font-size: 12px; letter-spacing: 0.18em; text-transform: uppercase; color: #0a0a0a; padding-bottom: 14px; }
    .result-note { margin: 0 0 40px; font-size: 13px; letter-spacing: 0.04em; color: #6f6a60; }

    .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 56px 32px; }
    .product-card { display: block; color: inherit; text-decoration: none; }
    .product-card img { display: block; width: 100%; aspect-ratio: 3 / 4; object-fit: cover; background: #fff; margin-bottom: 16px; transition: opacity 0.2s; }
    .product-card:hover img { opacity: 0.88; }
    .product-card p { margin: 0; }
    .pc-brand { font-size: 11px; letter-spacing: 0.18em; text-transform: uppercase; color: #6f6a60; }
    .pc-title { font-family: 'Cormorant Garamond', serif; font-size: 26px; line-height: 1.2; margin: 4px 0 !important; }
    .pc-price { font-size: 14px; }

    .sp { display: grid; grid-template-columns: 1fr 1fr; gap: 6vw; align-items: start; }
    .sp-image img { display: block; width: 100%; background: #fff; }
    .sp-info { padding-top: 40px; }
    .sp-crumb { margin: 0 0 24px; font-size: 12px; letter-spacing: 0.18em; text-transform: uppercase; color: #6f6a60; }
    .sp-crumb a { color: inherit; text-decoration: none; border-bottom: 1px solid currentColor; }
    .sp-title { margin: 0 0 8px; font-size: clamp(36px, 4vw, 56px); }
    .sp-bar { margin: 32px 0; padding: 22px 24px; background: #0a0a0a; color: #fff; font-size: 18px; letter-spacing: 0.05em; }
    .sp-desc h4 { margin: 0 0 16px; padding-top: 20px; border-top: 1px solid #d8d3c8; font-size: 12px; font-weight: 500; letter-spacing: 0.2em; text-transform: uppercase; }
    .sp-desc p { margin: 0; line-height: 1.8; white-space: pre-line; }

    .sp-buy { margin: 0 0 40px; }
    .sp-buy label { display: block; margin-bottom: 10px; font-size: 12px; letter-spacing: 0.2em; text-transform: uppercase; }
    .size-options { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 24px; }
    .size-options input { position: absolute; opacity: 0; }
    .size-options label { display: inline-block; margin: 0; padding: 10px 16px; border: 1px solid #0a0a0a; font-size: 13px; letter-spacing: 0.08em; cursor: pointer; }
    .size-options input:checked + label { background: #0a0a0a; color: #fff; }
    .sp-buy button, .filter-bar button { padding: 16px 32px; background: #0a0a0a; color: #fff; border: 0; font-size: 12px; letter-spacing: 0.2em; text-transform: uppercase; cursor: pointer; }
    .sp-buy .qty-box { display: inline-flex; align-items: center; border: 1px solid #0a0a0a; margin-bottom: 24px; }
    .sp-buy .qty-btn { width: 40px; height: 40px; padding: 0; background: transparent; color: #0a0a0a; font-size: 18px; letter-spacing: 0; }
    .sp-buy .qty-input { width: 48px; height: 40px; border: 0; text-align: center; background: transparent; padding: 0; }
    @media (max-width: 800px) { .sp { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
<div class="container">

    <div class="shop-top">
        <a href="products.php">Shop</a>
        <a class="top-logo" href="products.php">SHOPPN</a>
        <div class="shop-right">
            <a href="cart.php" class="cart-link" aria-label="Cart">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 7h12l-1 13H7L6 7z"/><path d="M9 7V6a3 3 0 0 1 6 0v1"/></svg>
                (<?= (int)$cartCount; ?>) · GH₵ <?= number_format($cartTotal, 2); ?>
            </a>
        </div>
    </div>

<?php if ($product): ?>

    <div class="sp">
        <div class="sp-image">
           <img src="../images/products/<?= htmlspecialchars(basename($product['product_image'])); ?>" alt="<?= htmlspecialchars($product['product_title']); ?>">
        </div>
        <div class="sp-info">
            <p class="sp-crumb">
                <a href="products.php">Shop</a> /
                <a href="products.php?cat=<?= (int)$product['product_cat']; ?>"><?= htmlspecialchars($product['cat_name']); ?></a> /
                <a href="products.php?brand=<?= (int)$product['product_brand']; ?>"><?= htmlspecialchars($product['brand_name']); ?></a>
            </p>
            <h1 class="sp-title"><?= htmlspecialchars($product['product_title']); ?></h1>
            <div class="sp-bar">GH₵ <?= number_format($product['product_price'], 2); ?></div>

            <form class="sp-buy" method="post" action="../actions/add_to_cart_action.php">
                <input type="hidden" name="pro_id" value="<?= (int)$product['product_id']; ?>">
                <?php if ($sizes): ?>
                    <label>Size</label>
                    <div class="size-options">
                        <?php foreach ($sizes as $i => $s): ?>
                            <input type="radio" name="size" id="size-<?= $i; ?>" value="<?= htmlspecialchars($s); ?>" required>
                            <label for="size-<?= $i; ?>"><?= htmlspecialchars($s); ?></label>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <label>Quantity</label>
                <div class="qty-box">
                    <button type="button" class="qty-btn" data-step="-1">&minus;</button>
                    <input type="text" class="qty-input" name="qty" value="1" inputmode="numeric">
                    <button type="button" class="qty-btn" data-step="1">+</button>
                </div>
                <br>
                <button type="submit">Add to bag</button>
            </form>

            <div class="sp-desc">
                <h4>Description</h4>
                <p><?= htmlspecialchars($product['product_desc']); ?></p>
            </div>
        </div>
    </div>

<?php else: ?>

    <form class="filter-bar" method="get" action="products.php">
        <div>
            <select name="cat">
                <option value="0">All categories</option>
                <?php foreach ($categories as $c): ?>
                    <option value="<?= (int)$c['cat_id']; ?>" <?= $catId == $c['cat_id'] ? 'selected' : ''; ?>><?= htmlspecialchars($c['cat_name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <select name="brand">
                <option value="0">All brands</option>
                <?php foreach ($brands as $b): ?>
                    <option value="<?= (int)$b['brand_id']; ?>" <?= $brandId == $b['brand_id'] ? 'selected' : ''; ?>><?= htmlspecialchars($b['brand_name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="search-field">
            <input type="text" name="q" placeholder="Search products" value="<?= htmlspecialchars($q); ?>">
        </div>
        <button type="submit">Filter</button>
        <?php if ($filtered): ?><a class="filter-clear" href="products.php">Clear</a><?php endif; ?>
    </form>

    <?php if ($filtered): ?>
        <p class="result-note"><?= count($products); ?> result(s) found</p>
    <?php endif; ?>

    <div class="product-grid">
        <?php foreach ($products as $p): ?>
            <a class="product-card" href="products.php?pro_id=<?= (int)$p['product_id']; ?>">
                <img
    src="../images/products/<?= htmlspecialchars(basename($p['product_image'])); ?>"
    alt="<?= htmlspecialchars($p['product_title']); ?>"
>
                <p class="pc-brand"><?= htmlspecialchars($p['brand_name']); ?></p>
                <p class="pc-title"><?= htmlspecialchars($p['product_title']); ?></p>
                <p class="pc-price">GH₵ <?= number_format($p['product_price'], 2); ?></p>
            </a>
        <?php endforeach; ?>
    </div>

<?php endif; ?>

</div>

<script>
document.querySelectorAll('.qty-box').forEach(function (box) {
    var input = box.querySelector('.qty-input');
    box.querySelectorAll('.qty-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var v = (parseInt(input.value, 10) || 1) + parseInt(btn.dataset.step, 10);
            input.value = Math.min(99, Math.max(1, v));
        });
    });
});
</script>

</body>
</html>