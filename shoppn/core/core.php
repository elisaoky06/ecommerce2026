<?php

// ============================================================
// core.php
// ------------------------------------------------------------
// This file gets included at the top of every protected page in
// an app (require_once "../core/core.php";). It's the place for
// anything that needs to happen on EVERY page load - not just
// database access (that's what core/db_class.php is for).
// ============================================================

// Start output buffering. header('Location: ...') redirects fail if any
// output was already sent to the browser - buffering here means pages
// further down the line can still redirect safely even after printing
// something.
ob_start();

// session_start() must run before $_SESSION can be read/written anywhere
// else in the app.
session_start();

// Returns true if a customer is currently logged in.
function is_logged_in()
{
    return isset($_SESSION['customer_id']);
}

// Returns true if the logged-in customer's role is 1 (admin).
// Matches the role values used in customer_register_action.php, where
// a normal sign-up is hardcoded to role 2.
function is_admin()
{
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 1;
}

// Call this at the very top of any view that requires a logged-in
// customer. If nobody's logged in, send them to the login page and
// stop the rest of the script from running.
function require_login()
{
    if (!is_logged_in()) {
        header('Location: ../view/login.php');
        exit;
    }
}

// Call this at the very top of any view that only admins should see.
// If the current user isn't an admin, redirect to the homepage instead
// of letting the page render.
function require_admin()
{
    if (!is_admin()) {
        header('Location: ../index.php');
        exit;
    }
}

// TODO: session timeout
// Track the time of the last request. If too much time has passed
// since then, log the user out automatically.

// TODO: detect session hijacking
// Store the user's IP address and browser (User-Agent) at login.
// On every page load, compare them to the current request - if
// they don't match, something is wrong, so log the user out.

// TODO: secure logout
// A function that clears all session data, deletes the session
// cookie, destroys the session, and starts a fresh one - used by
// both a manual "log out" click and the automatic checks above.

?>