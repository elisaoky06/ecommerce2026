<?php
require_once __DIR__ . '/../../controllers/CartController.php';

$cartCtl   = new CartController();
$ip        = get_ip();
$cartCount = $cartCtl->getCartCount($ip);
$cartTotal = $cartCtl->getCartTotal($ip);
?>

<?php if (is_admin()): ?>
    <li><a href="<?= BASE_URL ?>views/admin/brand.php">Manage Brands</a></li>
    <li><a href="<?= BASE_URL ?>views/admin/category.php">Manage Categories</a></li>
<?php endif; ?>

<li class="cart-summary">
    Shopping Cart: Total Items: <?= $cartCount ?> | Total Price: GHS <?= number_format($cartTotal, 2) ?> |
    <a href="<?= BASE_URL ?>views/cart.php">Go to Cart</a>
</li>

<?php if (isset($_SESSION['customer_id'])): ?>
    <li><a href="<?= BASE_URL ?>actions/logout_action.php">Logout</a></li>
<?php else: ?>
    <li><a href="<?= BASE_URL ?>views/login.php">Login</a></li>
<?php endif; ?>

<?php if (!empty($_SESSION['cart_msg'])): ?>
    <div class="cart-msg"><?= htmlspecialchars($_SESSION['cart_msg']) ?></div>
    <?php unset($_SESSION['cart_msg']); ?>
<?php endif; ?>