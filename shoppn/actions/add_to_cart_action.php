
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../views/products.php");
    exit;
}

$pid = filter_input(INPUT_POST, 'pro_id', FILTER_VALIDATE_INT);

if (!$pid || $pid < 1) {
    header("Location: ../views/products.php");
    exit;
}

$ip  = get_ip();
$cart = new CartController();
$qty = filter_input(INPUT_POST, 'qty', FILTER_VALIDATE_INT) ?: 1;
$qty = max(1, min(99, $qty));

if ($cart->isInCart($pid, $ip)) {
    $_SESSION['cart_msg'] = 'This item is already in your cart. Go to cart to update quantity.';
} else {
    $cid  = $_SESSION['customer_id'] ?? null;
    $size = trim($_POST['size'] ?? '') ?: null;
    $cart->addToCart($pid, $qty, $ip, $cid, $size);
}

header("Location: ../views/cart.php");
exit;