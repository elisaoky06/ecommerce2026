<?php

require_once "core/core.php";

$sidebarFile = __DIR__ . '/views/layout/sidebar.php';
$useSidebar  = is_admin() && file_exists($sidebarFile);
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

<?php if ($useSidebar): ?>
<div class="admin-wrap">
<?php include $sidebarFile; ?>
<div class="page-main">
<?php endif; ?>

	<section class="hero">
		<?php if (!$useSidebar): ?>
		<nav class="hero-nav">
			<?php if (is_admin()): ?>
				<a href="views/admin/brand.php">Manage Brands</a>
				<a href="views/admin/category.php">Manage Categories</a>
				<a href="views/admin/product.php">Add Product</a>
				<a href="views/all_products.php">All Products</a>
				<a href="views/customers.php">Customers</a>
				<a href="logout.php">Logout</a>
			<?php elseif (is_logged_in()): ?>
				<a href="logout.php">Logout</a>
			<?php else: ?>
				<a href="views/login.php">Login</a>
				<a href="views/register.php">Register</a>
			<?php endif; ?>
		</nav>
		<?php endif; ?>

		<p class="eyebrow">
			<?php if (is_logged_in()): ?>
				Welcome <?= htmlspecialchars($_SESSION['customer_name']) ?>
			<?php else: ?>
				Welcome
			<?php endif; ?>
		</p>
		<h1>SHOPPN by Elisa.</h1>

		<?php if (!is_admin()): ?>
			<a href="views/products.php">Explore our shop &rarr;</a>
		<?php endif; ?>
	</section>

<?php if ($useSidebar): ?>
</div>
</div>
<?php endif; ?>

</body>
</html>