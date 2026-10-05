<?php
require_once __DIR__ . "/../core/core.php";
require_once __DIR__ . "/../controllers/ProductController.php";

require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id   = (int)($_POST['brand_id'] ?? 0);
    $name = trim(strip_tags($_POST['brand_name'] ?? ''));

    if ($id > 0 && $name !== '' && (new ProductController())->updateBrand($id, $name)) {
        $_SESSION['success'] = 'Brand updated.';
    } else {
        $_SESSION['error'] = 'Could not update brand.';
    }
}

header("Location: " . BASE_URL . "views/admin/brand.php");
exit;