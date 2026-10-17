
<?php
require_once __DIR__ . '/../core/core.php';

$order = $_SESSION['order_success'] ?? null;

if (!$order) {
    header('Location: cart.php');
    exit;
}

unset($_SESSION['order_success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/style.css">
    <title>Order Confirmation | SHOPPN</title>
</head>
<body>
    <h1>Order placed successfully!</h1>

    <p>Thank you for shopping with SHOPPN.</p>

    <p>
        Order number:
        <?= htmlspecialchars((string)$order['order_id']); ?>
    </p>

    <p>
        Invoice number:
        <?= htmlspecialchars((string)$order['invoice_no']); ?>
    </p>

    <p>Your order is pending payment confirmation.</p>

    <a href="../index.php">Continue shopping</a>
</body>
</html>
