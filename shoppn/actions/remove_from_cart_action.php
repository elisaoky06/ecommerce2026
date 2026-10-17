<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cart = new CartController();
    $ip   = get_ip();
    foreach (($_POST['remove'] ?? []) as $pid) {
        $cart->removeFromCart((int)$pid, $ip);
    }
}
header("Location: ../views/cart.php");
exit;