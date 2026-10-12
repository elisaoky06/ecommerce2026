<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../core/core.php";
require_admin();
require_once __DIR__ . "/../controllers/ProductController.php";

$back = BASE_URL . "views/admin/product.php";

function fail($msg, $back) {
    $_SESSION['error'] = $msg;
    header("Location: $back");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') fail('Invalid request.', $back);

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

$file = $_FILES['product_image'] ?? null;
if (!$file || $file['error'] !== UPLOAD_ERR_OK) fail('Choose a product image.', $back);
if ($file['size'] > 2 * 1024 * 1024) fail('Image must be 2MB or less.', $back);

$info  = getimagesize($file['tmp_name']);
$types = [IMAGETYPE_JPEG => 'jpg', IMAGETYPE_PNG => 'png', IMAGETYPE_GIF => 'gif', IMAGETYPE_WEBP => 'webp'];
if (!$info || !isset($types[$info[2]])) fail('Only JPG, PNG, GIF or WEBP allowed.', $back);

$dir = __DIR__ . "/../images/products/";
if (!is_dir($dir) && !mkdir($dir, 0755, true)) fail('Could not create images/products folder.', $back);

$filename = uniqid('prod_', true) . '.' . $types[$info[2]];
if (!move_uploaded_file($file['tmp_name'], $dir . $filename)) fail('Image upload failed.', $back);

$controller = new ProductController();
if ($controller->addProduct($cat, $brand, $title, (float)$price, $desc, $filename, $keywords)) {
    $_SESSION['success'] = 'Product added.';
} else {
    unlink($dir . $filename);
    $_SESSION['error'] = 'Could not add product.';
}
header("Location: $back");
exit;