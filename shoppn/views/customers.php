<?php
require_once __DIR__ . "/../core/core.php";
require_admin();

require_once "../functions/customer_functions.php";

$customers = getAllCustomersList();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="../css/style.css">
	<title>All Customers</title>
</head>
<body>
	<h1>All Customers</h1>

	<nav>
		<a href="../index.php">Home</a> |
		<a href="admin/brand.php"> Manage Brands</a> |
		<a href="register.php">Register Customer</a> |
		<a href="admin/category.php">Manage Categories</a>
	</nav>

	<table border="1" cellpadding="5" cellspacing="0">
		<thead>
			<tr>
				<th>ID</th>
				<th>Name</th>
				<th>Email</th>
				<th>Country</th>
				<th>City</th>
				<th>Contact</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($customers as $customer) { ?>
				<tr>
					<td><?php echo htmlspecialchars($customer['customer_id']); ?></td>
					<td><?php echo htmlspecialchars($customer['customer_name']); ?></td>
					<td><?php echo htmlspecialchars($customer['customer_email']); ?></td>
					<td><?php echo htmlspecialchars($customer['customer_country']); ?></td>
					<td><?php echo htmlspecialchars($customer['customer_city']); ?></td>
					<td><?php echo htmlspecialchars($customer['customer_contact']); ?></td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
</body>
</html>