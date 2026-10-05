<?php

ob_start();
session_start();

define('BASE_URL', '/~elisa.okyere/ECommerce2026/shoppn/');

function is_logged_in()
{
    return isset($_SESSION['customer_id']);
}

// True if the logged-in customer's role is 1 (admin).
function is_admin()
{
    return isset($_SESSION['user_role']) && (int)$_SESSION['user_role'] === 1;
}

function require_login()
{
    if (!is_logged_in()) {
        header('Location: ' . BASE_URL . 'views/login.php');
        exit;
    }
}

function require_admin()
{
    if (!is_admin()) {
        header('Location: ' . BASE_URL . 'index.php');
        exit;
    }
}