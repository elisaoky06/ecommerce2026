<?php
require_once __DIR__ . "/../core/core.php";
require_admin();
require_once __DIR__ . "/../controllers/ProductController.php";

$controller = new ProductController();
$products   = $controller->getAllProducts();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/style.css">
    <title>All Products</title>
    <style>
    .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 56px 32px; }
    .product-card { display: block; color: inherit; }
    .product-card img { display: block; width: 100%; aspect-ratio: 3 / 4; object-fit: cover; background: #fff; margin-bottom: 16px; }
    .product-card p { margin: 0; }
    .pc-brand { font-size: 11px; letter-spacing: 0.18em; text-transform: uppercase; color: #6f6a60; }
    .pc-title { font-family: 'Cormorant Garamond', serif; font-size: 26px; line-height: 1.2; margin: 4px 0 !important; }
    .pc-price { font-size: 14px; }
    .pc-actions { display: flex; gap: 8px; margin-top: 14px; }
    .pc-actions a { text-decoration: none; }
    .pc-actions button { padding: 8px 18px; margin: 0; }
    </style>
</head>
<body>
<?php include __DIR__ . '/layout/sidebar.php'; ?>

    <h2>All Products</h2>

    <?php if (!empty($_SESSION['success'])): ?>
        <p style="color:green;"><?= htmlspecialchars($_SESSION['success']); ?></p>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
        <p style="color:red;"><?= htmlspecialchars($_SESSION['error']); ?></p>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (empty($products)): ?>
        <p>No products yet.</p>
    <?php else: ?>
        <div class="product-grid">
            <?php foreach ($products as $p): ?>
                <div class="product-card">
                    <img src="../images/products/<?= htmlspecialchars($p['product_image']); ?>" alt="<?= htmlspecialchars($p['product_title']); ?>">
                    <p class="pc-brand"><?= htmlspecialchars($p['brand_name']); ?> &middot; <?= htmlspecialchars($p['cat_name']); ?></p>
                    <p class="pc-title"><?= htmlspecialchars($p['product_title']); ?></p>
                    <p class="pc-price">GH₵ <?= number_format($p['product_price'], 2); ?></p>
                    <div class="pc-actions">
                        <a href="admin/product.php?edit_id=<?= (int)$p['product_id']; ?>"><button type="button">Edit</button></a>
                        <button type="button"
                            onclick="askDelete(<?= (int)$p['product_id']; ?>, <?= htmlspecialchars(json_encode($p['product_title']), ENT_QUOTES); ?>)">Delete</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <dialog id="deleteBox">
        <form method="POST" action="../actions/delete_product_action.php">
            <p id="deleteMsg"></p>
            <input type="hidden" name="product_id" id="deleteId">
            <button type="submit">Yes, delete</button>
            <button type="button" onclick="document.getElementById('deleteBox').close()">No</button>
        </form>
    </dialog>

    <script>
    function askDelete(id, name) {
        document.getElementById('deleteId').value = id;
        document.getElementById('deleteMsg').textContent = 'Delete "' + name + '"?';
        document.getElementById('deleteBox').showModal();
    }
    </script>

</body>
</html>