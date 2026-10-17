<?php
require_once __DIR__ . '/../classes/CartClass.php';

class CartController {
    private $cart;
    public function __construct() { $this->cart = new CartClass(); }

    public function isInCart($pid, $ip)                  { return $this->cart->isInCart($pid, $ip); }
    public function addToCart($pid, $qty, $ip, $cid = null, $size = null) {
    return $this->cart->addToCart($pid, $qty, $ip, $cid, $size);
}
    public function getCartItems($ip)                    { return $this->cart->getCartItems($ip); }
    public function getCartCount($ip)                    { return $this->cart->getCartCount($ip); }
    public function getCartTotal($ip)                    { return $this->cart->getCartTotal($ip); }
    public function removeFromCart($pid, $ip)            { return $this->cart->removeFromCart($pid, $ip); }
    public function updateQty($pid, $qty, $ip)           { return $this->cart->updateQty($pid, $qty, $ip); }
}