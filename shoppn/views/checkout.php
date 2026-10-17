
<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

$cart = new CartController();
$ip = get_ip();

$items = $cart->getCartItems($ip);
$subtotal = $cart->getCartTotal($ip);
$shipping = 0.00; //default shipping cost
$total = $subtotal + $shipping;

if (empty($items)) {
    header('Location: cart.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/style.css">
    <title>Checkout | SHOPPN</title>
</head>
<body>
<main class="co-page">
    <h1>Checkout</h1>
    <a href="cart.php" class="co-back">← Back to Cart</a>

    <div class="co-layout">

        <section class="co-summary">
            <h2>Order Summary</h2>

            <table class="co-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($items as $item): ?>
                    <?php $unit = (float)$item['product_price']; $qty = (int)$item['qty']; ?>
                    <tr>
                        <td>
                            <?= htmlspecialchars($item['product_title']); ?>
                            <?php if (!empty($item['size'])): ?>
                                <span class="co-size">Size: <?= htmlspecialchars($item['size']); ?></span>
                            <?php endif; ?>
                        </td>
                        <td><?= $qty; ?></td>
                        <td>GH₵ <?= number_format($unit, 2); ?></td>
                        <td>GH₵ <?= number_format($unit * $qty, 2); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <div class="co-totals">
                <div><span>Sub Total</span><span>GH₵ <?= number_format($subtotal, 2); ?></span></div>
                <div><span>Shipping</span><span>GH₵ <?= number_format($shipping, 2); ?></span></div>
                <div class="co-total"><span>Estimated Total</span><span>GH₵ <?= number_format($total, 2); ?></span></div>
            </div>
        </section>

        <section class="co-delivery">
            <h2>Delivery Details</h2>
            <form method="POST" action="../actions/place_order_action.php">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>

                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" required>

                <label for="address">Delivery Address</label>
                <textarea id="address" name="address" rows="3" required></textarea>

                <button type="submit">Place Order</button>
            </form>
        </section>

    </div>
</main>
</body>
</html>
