<?php

require_once __DIR__ . "/../core/core.php";
require_once __DIR__ . "/../controllers/ProductController.php";

require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['brand_id'] ?? 0);

    if ($id > 0 && (new ProductController())->deleteBrand($id)) {
        $_SESSION['success'] = 'Brand deleted.';
    } else {
        $_SESSION['error'] = 'Could not delete brand.';
    }
}

header("Location: " . BASE_URL . "views/admin/brand.php");
exit;