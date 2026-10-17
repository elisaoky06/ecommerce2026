<?php
require_once __DIR__ . '/../core/db_class.php';

class CartClass extends Database {

    public function isInCart($product_id, $ip) {
        $row = $this->fetchOne("SELECT 1 AS found FROM cart WHERE p_id = ? AND ip_add = ? LIMIT 1", [$product_id, $ip]);
        return !empty($row);
    }

    public function addToCart($product_id, $qty, $ip, $customer_id = null, $size = null) {
        return $this->execute(
            "INSERT INTO cart (p_id, ip_add, c_id, qty, size) VALUES (?, ?, ?, ?, ?)",
            [$product_id, $ip, $customer_id, $qty, $size]
        );
    }

    public function getCartItems($ip) {
        return $this->fetchAll(
            "SELECT cart.*, products.product_title, products.product_price, products.product_image
             FROM cart JOIN products ON cart.p_id = products.product_id
             WHERE cart.ip_add = ?",
            [$ip]
        );
    }

    public function getCartCount($ip) {
        $row = $this->fetchOne("SELECT COUNT(*) AS cnt FROM cart WHERE ip_add = ?", [$ip]);
        return (int)($row['cnt'] ?? 0);
    }

    public function getCartTotal($ip) {
        $total = 0.0;
        foreach ($this->getCartItems($ip) as $item) {
            $total += $item['qty'] * $item['product_price'];
        }
        return (float)$total;
    }

    public function removeFromCart($product_id, $ip) {
        return $this->execute("DELETE FROM cart WHERE p_id = ? AND ip_add = ?", [$product_id, $ip]);
    }

    public function updateQty($product_id, $new_qty, $ip) {
        if ($new_qty < 1) return false;
        return $this->execute("UPDATE cart SET qty = ? WHERE p_id = ? AND ip_add = ?", [$new_qty, $product_id, $ip]);
    }
}