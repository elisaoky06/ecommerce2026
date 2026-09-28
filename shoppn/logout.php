<?php

// This is an "action" file - the endpoint the "Logout" link sends the
// browser to. It clears the session so is_logged_in() returns false
// again, then sends the user back to the homepage.
session_start();

// Wipe every value out of the session array.
$_SESSION = [];

// Delete the session cookie itself, not just its contents, so the
// browser doesn't keep sending an old session id.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Actually destroy the session data on the server side.
session_destroy();

// Send the user back to the homepage.
header("Location:index.php");
exit;