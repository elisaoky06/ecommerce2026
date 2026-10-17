<?php
require_once __DIR__ . "/../core/core.php";
require_once __DIR__ . "/../controllers/CartController.php";

if (session_status() === PHP_SESSION_NONE) { session_start(); }

$cart      = new CartController();
$ip        = get_ip();
$items     = $cart->getCartItems($ip);
$sub       = $cart->getCartTotal($ip);
$cartCount = $cart->getCartCount($ip);
$shipping  = 0.00;
$total     = $sub + $shipping;

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/style.css">
    <title>Your Shopping Bag | SHOPPN</title>
    <style>
    .shop-top { display: grid; grid-template-columns: 1fr auto 1fr; align-items: center; margin-bottom: 56px; padding-bottom: 20px; border-bottom: 1px solid #d8d3c8; font-size: 12px; letter-spacing: 0.18em; text-transform: uppercase; }
    .shop-top a { color: #0a0a0a; text-decoration: none; }
    .shop-top a:hover { color: #b89b6a; }
    .shop-top .top-logo { font-family: 'Cormorant Garamond', serif; font-size: 28px; letter-spacing: -0.02em; text-transform: none; }
    .shop-top .shop-right { justify-self: end; display: flex; gap: 28px; }
    .cart-link { display: inline-flex; align-items: center; gap: 8px; }
    .cart-link svg { width: 22px; height: 22px; }

    .cart-table { width: 100%; border-collapse: collapse; margin-bottom: 32px; }
    .cart-table th { text-align: left; padding: 12px 0; border-bottom: 1px solid #0a0a0a; font-size: 12px; font-weight: 500; letter-spacing: 0.2em; text-transform: uppercase; }
    .cart-table td { padding: 20px 0; border-bottom: 1px solid #d8d3c8; vertical-align: middle; }
    .cart-prod { display: flex; align-items: center; gap: 20px; }
    .cart-prod img { width: 80px; aspect-ratio: 3 / 4; object-fit: cover; background: #fff; }
    .cart-prod .ct { font-family: 'Cormorant Garamond', serif; font-size: 22px; }
    .cart-prod small { display: block; margin-top: 4px; font-size: 12px; letter-spacing: 0.12em; text-transform: uppercase; color: #6f6a60; }
        .cart-table th:nth-child(n+4), .cart-table td:nth-child(n+4) { text-align: right; }
    .cart-totals { max-width: 420px; margin: 0 0 32px auto; }
    .cart-totals div { display: flex; justify-content: space-between; padding: 10px 0; font-size: 14px; letter-spacing: 0.1em; text-transform: uppercase; }
    .cart-totals .cart-total { border-top: 1px solid #0a0a0a; margin-top: 8px; font-weight: 600; font-size: 16px; }
    .cart-actions { display: flex; flex-wrap: wrap; gap: 12px; }
    .cart-actions a { text-decoration: none; }
    .qty-box { display: inline-flex; align-items: center; border: 1px solid #0a0a0a; }
    .qty-box .qty-btn { width: 36px; height: 36px; padding: 0; border: 0; background: transparent; color: #0a0a0a; font-size: 18px; cursor: pointer; letter-spacing: 0; }
    .cart-table .qty-box .qty-input { width: 44px; height: 36px; border: 0; text-align: center; background: transparent; padding: 0; }
    </style>
</head>
<body>

    <header class="shop-top">
        <a href="products.php">&larr; Shop</a>
        <a href="<?= BASE_URL ?>index.php" class="top-logo">SHOPPN.</a>
        <span class="shop-right">
            <a href="cart.php" class="cart-link" aria-label="Cart">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 7h12l-1 13H7L6 7z"/><path d="M9 7V6a3 3 0 0 1 6 0v1"/></svg>
                (<?= $cartCount; ?>)
            </a>
            <?php if (is_logged_in()): ?>
                <a href="<?= BASE_URL ?>logout.php">Logout</a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>views/login.php">Login</a>
            <?php endif; ?>
        </span>
    </header>

    <h2>Your Shopping Bag</h2>

<?php if (empty($items)): ?>

    <p>Your bag is empty.</p>
    <a href="products.php"><button type="button">Continue Shopping</button></a>

<?php else: ?>

    <form id="removeForm" method="POST" action="<?= BASE_URL ?>actions/remove_from_cart_action.php"></form>
    <form id="updateForm" method="POST" action="<?= BASE_URL ?>actions/update_qty_action.php"></form>

        <table class="cart-table">
        <tr><th>Remove</th><th>Product</th><th>Qty</th><th>Unit Price</th><th>Subtotal</th></tr>
        <?php foreach ($items as $it): ?>
        <tr>
            <td><input type="checkbox" form="removeForm" name="remove[]" value="<?= (int)$it['p_id']; ?>"></td>
            <td>
                <div class="cart-prod">
                    <img src="../images/products/<?= htmlspecialchars($it['product_image']); ?>" alt="">
                    <div>
                        <span class="ct"><?= htmlspecialchars($it['product_title']); ?></span>
                        <?php if (!empty($it['size'])): ?>
                            <small>Size: <?= htmlspecialchars($it['size']); ?></small>
                        <?php endif; ?>
                    </div>
                </div>
            </td>
            <td>
                <div class="qty-box">
                    <button type="button" class="qty-btn" data-step="-1">&minus;</button>
                    <input type="text" class="qty-input" form="updateForm" name="qty[]" value="<?= (int)$it['qty']; ?>" inputmode="numeric">
                    <button type="button" class="qty-btn" data-step="1">+</button>
                </div>
                <input type="hidden" form="updateForm" name="update[]" value="<?= (int)$it['p_id']; ?>">
            </td>
            <td>GH₵ <?= number_format($it['product_price'], 2); ?></td>
            <td>GH₵ <?= number_format($it['product_price'] * $it['qty'], 2); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <div class="cart-totals">
        <div><span>Sub Total</span><span>GH₵ <?= number_format($sub, 2); ?></span></div>
        <div><span>Shipping</span><span>GH₵ <?= number_format($shipping, 2); ?></span></div>
        <div class="cart-total"><span>Estimated Total</span><span>GH₵ <?= number_format($total, 2); ?></span></div>
    </div>


    <div class="cart-actions">
        <button type="submit" form="removeForm">Remove from Bag</button>
        <a href="products.php"><button type="button">Continue Shopping</button></a>
        <a href="checkout.php"><button type="button">Checkout</button></a>
    </div>

<?php endif; ?>

<script>
document.querySelectorAll('.qty-box').forEach(function (box) {
    var input = box.querySelector('.qty-input');
    box.querySelectorAll('.qty-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var v = (parseInt(input.value, 10) || 1) + parseInt(btn.dataset.step, 10);
            input.value = Math.min(99, Math.max(1, v));
            document.getElementById('updateForm').submit();
        });
    });
});
</script>

</body>
</html>