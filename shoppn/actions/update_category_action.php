<?php

require_once __DIR__ . "/../core/core.php";
require_once __DIR__ . "/../controllers/ProductController.php";

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . BASE_URL . "views/admin/category.php");
    exit;
}

$id   = (int)($_POST['cat_id'] ?? 0);
$name = trim(strip_tags($_POST['cat_name'] ?? ''));

if ($id <= 0 || $name === '') {
    $_SESSION['error'] = 'Invalid category or name.';
} elseif ((new ProductController())->updateCategory($id, $name)) {
    $_SESSION['success'] = 'Category updated.';
} else {
    $_SESSION['error'] = 'Could not update category.';
}

header("Location: " . BASE_URL . "views/admin/category.php");
exit;