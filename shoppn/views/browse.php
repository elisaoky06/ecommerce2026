<?php
require_once __DIR__ . "/../core/core.php";
require_login();
require_once __DIR__ . "/../controllers/ProductController.php";

$controller = new ProductController();
$brands = $controller->getAllBrands();
$categories = $controller->getAllCategories();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="../css/style.css">
	<title>Brands and Categories</title>
</head>
<body>
	<nav>
		<a href="../index.php">Home</a>
		<a href="../logout.php">Logout</a>
	</nav>

	<div class="columns">
		<section>
			<h2>Brands</h2>
			<table>
				<?php foreach ($brands as $brand): ?>
					<tr>
						<td><?= htmlspecialchars($brand['brand_name']); ?></td>
					</tr>
				<?php endforeach; ?>
			</table>
		</section>

		<section>
			<h2>Categories</h2>
			<table>
				<?php foreach ($categories as $cat): ?>
					<tr>
						<td><?= htmlspecialchars($cat['cat_name']); ?></td>
					</tr>
				<?php endforeach; ?>
			</table>
		</section>
	</div>
</body>
</html>