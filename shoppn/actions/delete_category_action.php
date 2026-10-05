<?php

require_once __DIR__ . "/../core/core.php";
require_once __DIR__ . "/../controllers/ProductController.php";

require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['cat_id'] ?? 0);

    if ($id > 0 && (new ProductController())->deleteCategory($id)) {
        $_SESSION['success'] = 'Category deleted.';
    } else {
        $_SESSION['error'] = 'Could not delete category.';
    }
}

header("Location: " . BASE_URL . "views/admin/category.php");
exit;