<?php
require_once __DIR__ . "/../core/core.php";
require_admin();
require_once __DIR__ . "/../controllers/ProductController.php";

$list = BASE_URL . "views/all_products.php";
$id   = (int)($_POST['product_id'] ?? 0);
$back = BASE_URL . "views/admin/product.php?edit_id=" . $id;

function fail($msg, $to) {
    $_SESSION['error'] = $msg;
    header("Location: $to");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $id < 1) fail('Invalid request.', $list);

$controller = new ProductController();
$existing = $controller->getProductById($id);
if (!$existing) fail('Product not found.', $list);

$cat      = (int)($_POST['product_cat'] ?? 0);
$brand    = (int)($_POST['product_brand'] ?? 0);
$title    = trim(strip_tags($_POST['product_title'] ?? ''));
$price    = $_POST['product_price'] ?? '';
$desc     = trim(strip_tags($_POST['product_desc'] ?? ''));
$keywords = trim(strip_tags($_POST['product_keywords'] ?? ''));

if ($cat < 1 || $brand < 1) fail('Choose a category and a brand.', $back);
if ($title === '' || strlen($title) > 200) fail('Title is required (max 200 characters).', $back);
if (!is_numeric($price) || $price <= 0) fail('Enter a valid price.', $back);
if ($desc === '') fail('Description is required.', $back);

$dir      = __DIR__ . "/../images/products/";
$filename = $existing['product_image'];
$newPath  = null;

$file = $_FILES['product_image'] ?? null;
if ($file && $file['error'] !== UPLOAD_ERR_NO_FILE) {
    if ($file['error'] !== UPLOAD_ERR_OK) fail('Image upload failed.', $back);
    if ($file['size'] > 2 * 1024 * 1024) fail('Image must be 2MB or less.', $back);

    $info  = getimagesize($file['tmp_name']);
    $types = [IMAGETYPE_JPEG => 'jpg', IMAGETYPE_PNG => 'png', IMAGETYPE_GIF => 'gif', IMAGETYPE_WEBP => 'webp'];
    if (!$info || !isset($types[$info[2]])) fail('Only JPG, PNG, GIF or WEBP allowed.', $back);

    if (!is_dir($dir) && !mkdir($dir, 0755, true)) fail('Could not create images/products folder.', $back);

    $filename = uniqid('prod_', true) . '.' . $types[$info[2]];
    $newPath  = $dir . $filename;
    if (!move_uploaded_file($file['tmp_name'], $newPath)) fail('Image upload failed.', $back);
}

if ($controller->updateProduct($id, $cat, $brand, $title, (float)$price, $desc, $filename, $keywords) !== false) {
    if ($newPath && $existing['product_image']) {
        $old = $dir . basename($existing['product_image']);
        if (is_file($old)) unlink($old);
    }
    $_SESSION['success'] = 'Product updated.';
    header("Location: $list");
    exit;
}

if ($newPath) unlink($newPath);
fail('Could not update product.', $back);