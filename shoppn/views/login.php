<?php require_once "../core/core.php"; ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <link rel="stylesheet" href="../css/style.css">
	<title>Login</title>
</head>
<body>
	<h1>Customer Login</h1>

	<form id="loginForm">
		<label>Email</label>
		<input type="email" id="customer_email" name="customer_email" required>

				<label>Password</label>
		<input type="password" id="customer_pass" name="customer_pass" required>
		<label style="text-transform:none; letter-spacing:0; cursor:pointer;">
			<input type="checkbox" onclick="togglePassword()"> Show password
		</label>

		<button type="button" onclick="loginCustomer()">Login</button>
	</form>

	<p id="formMessage"></p>

	<p><a href="register.php">Create an account</a></p>

	<script src="../js/customer.js"></script>
</body>
</html>