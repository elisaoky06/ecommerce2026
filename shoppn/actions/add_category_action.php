<?php

require_once __DIR__ . "/../core/core.php";
require_once __DIR__ . "/../controllers/ProductController.php";

require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . BASE_URL . "views/admin/category.php");
    exit;
}

$name = trim(strip_tags($_POST['cat_name'] ?? ''));

if ($name === '') {
    $_SESSION['error'] = 'Category name is required.';
} elseif ((new ProductController())->addCategory($name)) {
    $_SESSION['success'] = 'Category added.';
} else {
    $_SESSION['error'] = 'Could not add category (it may already exist).';
}

header("Location: " . BASE_URL . "views/admin/category.php");
exit;