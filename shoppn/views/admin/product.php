<?php
require_once __DIR__ . "/../../core/core.php";
require_admin();
require_once __DIR__ . "/../../controllers/ProductController.php";

$controller = new ProductController();
$categories = $controller->getAllCategories();
$brands     = $controller->getAllBrands();

$editProduct = null;
if (isset($_GET['edit_id'])) {
    $editProduct = $controller->getProductById((int)$_GET['edit_id']);
}
$editing = (bool)$editProduct;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../../css/style.css">
    <title><?= $editing ? 'Edit Product' : 'Add Product'; ?></title>
</head>
<body>
<?php include __DIR__ . '/../layout/sidebar.php'; ?>

    <h2><?= $editing ? 'Edit Product' : 'Add Product'; ?></h2>

    <?php if (!empty($_SESSION['success'])): ?>
        <p style="color:green;"><?= htmlspecialchars($_SESSION['success']); ?></p>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
        <p style="color:red;"><?= htmlspecialchars($_SESSION['error']); ?></p>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form id="productForm" class="product-form" method="POST"
          action="../../actions/<?= $editing ? 'update_product_action.php' : 'add_product_action.php'; ?>"
          enctype="multipart/form-data">

        <?php if ($editing): ?>
            <input type="hidden" name="product_id" value="<?= (int)$editProduct['product_id']; ?>">
        <?php endif; ?>

        <div>
            <label>Category</label>
            <select name="product_cat" required>
                <option value="">Select category</option>
                <?php foreach ($categories as $c): ?>
                    <option value="<?= (int)$c['cat_id']; ?>" <?= ($editing && (int)$editProduct['product_cat'] === (int)$c['cat_id']) ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($c['cat_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label>Brand</label>
            <select name="product_brand" required>
                <option value="">Select brand</option>
                <?php foreach ($brands as $b): ?>
                    <option value="<?= (int)$b['brand_id']; ?>" <?= ($editing && (int)$editProduct['product_brand'] === (int)$b['brand_id']) ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($b['brand_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label>Title</label>
            <input type="text" name="product_title" maxlength="200" required
                   value="<?= $editing ? htmlspecialchars($editProduct['product_title']) : ''; ?>">
        </div>
        <div>
            <label>Price (GH₵)</label>
            <input type="number" name="product_price" step="0.01" min="0.01" required
                   value="<?= $editing ? htmlspecialchars($editProduct['product_price']) : ''; ?>">
        </div>
        <div>
            <label>Description</label>
            <textarea name="product_desc" rows="4" required><?= $editing ? htmlspecialchars($editProduct['product_desc']) : ''; ?></textarea>
        </div>
        <div>
            <label>Image<?= $editing ? ' (optional)' : ''; ?></label>
            <?php if ($editing && !empty($editProduct['product_image'])): ?>
                <img src="../../images/products/<?= htmlspecialchars($editProduct['product_image']); ?>" alt=""
                     style="width:96px;height:96px;object-fit:cover;display:block;margin-bottom:12px;">
            <?php endif; ?>
            <input type="file" name="product_image" accept="image/jpeg,image/png,image/gif,image/webp" <?= $editing ? '' : 'required'; ?>>
        </div>
        <div>
            <label>Keywords</label>
            <input type="text" name="product_keywords"
                   value="<?= $editing ? htmlspecialchars($editProduct['product_keywords'] ?? '') : ''; ?>">
        </div>

        <button type="submit"><?= $editing ? 'Save Changes' : 'Add Product'; ?></button>
        <?php if ($editing): ?>
            <a href="../all_products.php"><button type="button">Cancel</button></a>
        <?php endif; ?>
    </form>

<script>
const editing = <?= $editing ? 'true' : 'false'; ?>;
document.getElementById('productForm').addEventListener('submit', function (e) {
    const f = this, errors = [];
    if (!f.product_cat.value) errors.push('Choose a category.');
    if (!f.product_brand.value) errors.push('Choose a brand.');
    if (f.product_title.value.trim().length < 2) errors.push('Enter a title.');
    if (!(parseFloat(f.product_price.value) > 0)) errors.push('Enter a valid price.');
    if (!f.product_desc.value.trim()) errors.push('Enter a description.');
    const img = f.product_image.files[0];
    if (!img) { if (!editing) errors.push('Choose an image.'); }
    else if (!/^image\/(jpeg|png|gif|webp)$/.test(img.type)) errors.push('Image must be JPG, PNG, GIF or WEBP.');
    else if (img.size > 2 * 1024 * 1024) errors.push('Image must be 2MB or less.');
    if (errors.length) { e.preventDefault(); alert(errors.join('\n')); }
});
</script>
</body>
</html>