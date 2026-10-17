<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart = new CartController();
    $ip   = get_ip();
    $ids  = $_POST['update'] ?? [];
    $qtys = $_POST['qty'] ?? [];
    foreach ($ids as $i => $pid) {
        $q = max(1, min(99, (int)($qtys[$i] ?? 1)));
        $cart->updateQty((int)$pid, $q, $ip);
    }
}
header("Location: ../views/cart.php");
exit;