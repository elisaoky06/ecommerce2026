<?php

require_once "core/core.php";
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="css/style.css">
	<title>Ecom LAB</title>
</head>
<body>

	<section class="hero">
		<nav class="hero-nav">
			<?php if (is_logged_in()): ?>
				
				<a href="logout.php">Logout</a>

				<?php if (is_admin()): ?>
					<a href="views/admin/brand.php">Manage Brands</a>
					<a href="views/admin/category.php">Manage Categories</a>

				<?php else: ?>
					<a href="views/browse.php">Brands</a>
				<?php endif; ?>
			<?php else: ?>
				<a href="views/login.php">Login</a>
				<a href="views/register.php">Register</a>
			<?php endif; ?>
		</nav>

		<p class="eyebrow">
			<?php if (is_logged_in()): ?>
				Welcome <?= htmlspecialchars($_SESSION['customer_name']) ?>
			<?php else: ?>
				Welcome
			<?php endif; ?>
		</p>
		<h1>SHOPPN by Elisa.</h1>

		<?php if (!is_admin()): ?>
			<a href="views/browse.php">Explore our brands & categories &rarr;</a>
		<?php endif; ?>
	</section>
</body>
</html>