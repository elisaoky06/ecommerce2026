<?php

// This is an "action" file - the endpoint the browser's JavaScript sends
// the login form to (see js/customer.js -> fetch("../actions/customer_login_action.php")).
// Its job is: read the incoming request, hand the data to the controller,
// and send a response back. It should not contain any SQL itself - that
// belongs in the model (classes/CustomerClass.php).
require_once "../controllers/CustomerController.php";

// Sessions store login state across page loads, so we need one started
// before we can write anything into $_SESSION below.
session_start();

// Tell the browser the response body will be JSON, not HTML
header("Content-Type: application/json");

// Read each form field from $_POST.
$email = $_POST['customer_email'] ?? '';
$pass  = $_POST['customer_pass'] ?? '';

// Server-side validation - same reasoning as registration: JS can be
// bypassed, so this check has to exist here too.
if ($email === '' || $pass === '') {
    echo json_encode(["success" => false, "message" => "Email and password are required."]);
    exit;
}

// Create the controller (this also connects to the database, since
// CustomerController creates a Customer, which extends Database).
$controller = new CustomerController();

// Hand the credentials to the controller, which asks the model to look
// up the row and verify the password hash.
$result = $controller->login($email, $pass);

if ($result['success']) {
    // Store what the rest of the app needs to know "who's logged in"
    // without querying the database again on every page.
    $_SESSION['customer_id']    = $result['customer']['customer_id'];
    $_SESSION['customer_name']  = $result['customer']['customer_name'];
    $_SESSION['customer_email'] = $result['customer']['customer_email'];
    $_SESSION['user_role']      = $result['customer']['user_role'];

    echo json_encode(["success" => true, "message" => "Login successful."]);
} else {
    echo json_encode(["success" => false, "message" => $result['error']]);
}