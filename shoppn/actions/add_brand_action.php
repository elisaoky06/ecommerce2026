<?php
require_once __DIR__ . "/../core/core.php";
require_once __DIR__ . "/../controllers/ProductController.php";

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . BASE_URL . "views/admin/brand.php");
    exit;
}

$name = trim(strip_tags($_POST['brand_name'] ?? ''));

if ($name === '') {
    $_SESSION['error'] = 'Brand name is required.';
} elseif ((new ProductController())->addBrand($name)) {
    $_SESSION['success'] = 'Brand added.';
} else {
    $_SESSION['error'] = 'Could not add brand.';
}

header("Location: " . BASE_URL . "views/admin/brand.php");
exit;