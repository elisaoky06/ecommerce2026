
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';
require_once __DIR__ . '/../core/db_class.php';

// Only accept form submissions
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/cart.php');
    exit;
}

// Require a logged-in customer
$customerId = $_SESSION['customer_id'] ?? null;

if (!$customerId) {
    $_SESSION['checkout_error'] = 'Please log in before placing your order.';
    header('Location: ../views/cart.php');
    exit;
}

// Validate checkout fields
$name    = trim($_POST['name'] ?? '');
$email   = trim($_POST['email'] ?? '');
$phone   = trim($_POST['phone'] ?? '');
$address = trim($_POST['address'] ?? '');

if (
    $name === '' ||
    !filter_var($email, FILTER_VALIDATE_EMAIL) ||
    $phone === '' ||
    $address === ''
) {
    $_SESSION['checkout_error'] = 'Please enter valid delivery details.';
    header('Location: ../views/checkout.php');
    exit;
}

// Retrieve the cart
$ip = get_ip();
$cart = new CartController();
$items = $cart->getCartItems($ip);

if (empty($items)) {
    $_SESSION['checkout_error'] = 'Your cart is empty.';
    header('Location: ../views/cart.php');
    exit;
}

$db = new Database();
$conn = $db->getConnection();

// Generate an invoice number
$invoiceNo = random_int(100000, 999999999);

try {
    $conn->beginTransaction();

    // Create the order
    $orderSql = "INSERT INTO orders
                 (customer_id, invoice_no, order_date, order_status)
                 VALUES (?, ?, CURDATE(), ?)";

    $stmt = $conn->prepare($orderSql);
    $stmt->execute([
        $customerId,
        $invoiceNo,
        'pending'
    ]);

    // Get the ID of the order just created
    $orderId = $conn->lastInsertId();

    // Add each cart item to orderdetails
    $detailSql = "INSERT INTO orderdetails
                  (order_id, product_id, qty)
                  VALUES (?, ?, ?)";

    $detailStmt = $conn->prepare($detailSql);

    foreach ($items as $item) {
        $detailStmt->execute([
            $orderId,
            $item['p_id'],
            $item['qty']
        ]);
    }

    // Clear the cart only after all order records are saved
    $deleteStmt = $conn->prepare(
        "DELETE FROM cart WHERE ip_add = ?"
    );
    $deleteStmt->execute([$ip]);

    // Commit the order and its details
    $conn->commit();

    $_SESSION['order_success'] = [
        'invoice_no' => $invoiceNo,
        'order_id' => $orderId
    ];

    header('Location: ../views/order_confirmation.php');
    exit;

} catch (Throwable $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }

    error_log('Place order failed: ' . $e->getMessage());

    $_SESSION['checkout_error'] =
        'We could not place your order. Please try again.';

    header('Location: ../views/checkout.php');
    exit;
}
