<?php require_once "core/core.php"; ?>
<!--
	This is the homepage / entry point of the app.
	It links out to the two customer pages under the view/ folder.
-->
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="css/style.css">
	<title>Ecom LAB</title>
</head>
<body>
	<h1>Welcome to Elisa Okyere's shoppn Website</h1>
	<nav>
		<?php if (is_logged_in()): ?>
			Welcome <?= htmlspecialchars($_SESSION['customer_name']) ?> |
			<a href="view/my_account.php">My Account</a> |
			<a href="logout.php">Logout</a>
		<?php else: ?>
			<a href="views/login.php">Login</a> |
			<a href="views/register.php">Register</a>
		<?php endif; ?>
		|
		<a href="views/customers.php">View All Customers</a>
	</nav>
</body>
</html>