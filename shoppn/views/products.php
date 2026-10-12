<?php
require_once __DIR__ . "/../core/core.php";
require_once __DIR__ . "/../controllers/ProductController.php";

$controller = new ProductController();

$proId   = (int)($_GET['pro_id'] ?? 0);
$catId   = (int)($_GET['cat'] ?? 0);
$brandId = (int)($_GET['brand'] ?? 0);
$q       = mb_substr(trim(strip_tags($_GET['q'] ?? '')), 0, 100);

$product = null;
if ($proId > 0) {
    $product = $controller->getProductById($proId);
    if (!$product) { header("Location: products.php"); exit; }
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
    @media (max-width: 800px) { .sp { grid-template-columns: 1fr; } .sp-info { padding-top: 0; } }
    </style>
</head>
<body>

    <header class="shop-top">
        <?php if ($product): ?>
            <a href="products.php">&larr; Shop</a>
        <?php else: ?>
            <a href="<?= BASE_URL ?>index.php">Home</a>
        <?php endif; ?>
        <a href="<?= BASE_URL ?>index.php" class="top-logo">SHOPPN.</a>
        <span class="shop-right">
            <?php if (is_logged_in()): ?>
                <a href="<?= BASE_URL ?>logout.php">Logout</a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>views/login.php">Login</a>
            <?php endif; ?>
        </span>
    </header>

<?php if ($product): ?>

    <div class="sp">
        <div class="sp-image">
            <img src="../images/products/<?= htmlspecialchars($product['product_image']); ?>" alt="<?= htmlspecialchars($product['product_title']); ?>">
        </div>
        <div class="sp-info">
            <p class="sp-crumb">
                <a href="products.php">Shop</a> &gt;
                <a href="products.php?brand=<?= (int)$product['product_brand']; ?>"><?= htmlspecialchars($product['brand_name']); ?></a> &gt;
                <a href="products.php?cat=<?= (int)$product['product_cat']; ?>"><?= htmlspecialchars($product['cat_name']); ?></a>
            </p>
            <h1 class="sp-title"><?= htmlspecialchars($product['product_title']); ?></h1>
            <div class="sp-bar">GH₵ <?= number_format($product['product_price'], 2); ?></div>
            <div class="sp-desc">
                <h4>Description</h4>
                <p><?= htmlspecialchars($product['product_desc']); ?></p>
            </div>
        </div>
    </div>

<?php else: ?>

    <h2>Shop</h2>

    <form method="GET" action="products.php" class="filter-bar">
        <div class="search-field">
            <label>Search</label>
            <input type="text" name="q" value="<?= htmlspecialchars($q); ?>" placeholder="Search products, brands, categories" maxlength="100">
        </div>
        <div>
            <label>Category</label>
            <select name="cat" onchange="this.form.submit()">
                <option value="0">All categories</option>
                <?php foreach ($categories as $c): ?>
                    <option value="<?= (int)$c['cat_id']; ?>" <?= $catId === (int)$c['cat_id'] ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($c['cat_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label>Brand</label>
            <select name="brand" onchange="this.form.submit()">
                <option value="0">All brands</option>
                <?php foreach ($brands as $b): ?>
                    <option value="<?= (int)$b['brand_id']; ?>" <?= $brandId === (int)$b['brand_id'] ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($b['brand_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit">Search</button>
        <?php if ($filtered): ?>
            <a href="products.php" class="filter-clear">Clear</a>
        <?php endif; ?>
    </form>

    <?php if ($q !== ''): ?>
        <p class="result-note"><?= count($products); ?> result<?= count($products) === 1 ? '' : 's'; ?> for "<?= htmlspecialchars($q); ?>"</p>
    <?php else: ?>
        <p class="result-note"></p>
    <?php endif; ?>

    <?php if (empty($products)): ?>
        <p><?= $filtered ? 'No products match your search.' : 'No products yet.'; ?></p>
    <?php else: ?>
        <div class="product-grid">
            <?php foreach ($products as $p): ?>
                <a class="product-card" href="products.php?pro_id=<?= (int)$p['product_id']; ?>">
                    <img src="../images/products/<?= htmlspecialchars($p['product_image']); ?>" alt="<?= htmlspecialchars($p['product_title']); ?>">
                    <p class="pc-brand"><?= htmlspecialchars($p['brand_name']); ?> &middot; <?= htmlspecialchars($p['cat_name']); ?></p>
                    <p class="pc-title"><?= htmlspecialchars($p['product_title']); ?></p>
                    <p class="pc-price">GH₵ <?= number_format($p['product_price'], 2); ?></p>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<?php endif; ?>

</body>
</html>