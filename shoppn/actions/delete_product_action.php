
<?php

require_once __DIR__ . "/../core/core.php";
require_once __DIR__ . "/../controllers/ProductController.php";

require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['product_id'] ?? 0);

    if ($id > 0 && (new ProductController())->deleteProduct($id)) {
        $_SESSION['success'] = 'Product deleted.';
    } else {
        $_SESSION['error'] = 'Could not delete product.';
    }
}

header("Location: " . BASE_URL . "views/all_products.php");
exit;
