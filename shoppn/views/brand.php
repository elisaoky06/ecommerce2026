<?php
require_once __DIR__ . "/../../core/core.php";
require_admin();
require_once __DIR__ . "/../../controllers/ProductController.php";

$controller = new ProductController();
$brands = $controller->getAllBrands();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../../css/style.css">
    <title>Manage Brands</title>
</head>
<body>
<div class="admin-wrap">
<?php include __DIR__ . '/sidebar.php'; ?>
<main class="admin-main">

    <h2>Manage Brands</h2>

    <?php if (!empty($_SESSION['success'])): ?>
        <p style="color:green;"><?= htmlspecialchars($_SESSION['success']); ?></p>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
        <p style="color:red;"><?= htmlspecialchars($_SESSION['error']); ?></p>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form method="POST" action="../../actions/add_brand_action.php">
        <input type="text" name="brand_name" placeholder="Brand name" required>
        <button type="submit">Add Brand</button>
    </form>

    <table border="1" cellpadding="6">
        <?php foreach ($brands as $brand): ?>
            <tr>
                <td><?= htmlspecialchars($brand['brand_name']); ?></td>
                <td>
                    <button type="button"
                        onclick="askEdit(<?= (int)$brand['brand_id']; ?>, <?= htmlspecialchars(json_encode($brand['brand_name']), ENT_QUOTES); ?>)">Edit</button>
                    <button type="button"
                        onclick="askDelete(<?= (int)$brand['brand_id']; ?>, <?= htmlspecialchars(json_encode($brand['brand_name']), ENT_QUOTES); ?>)">Delete</button>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <dialog id="editBox">
        <form method="POST" action="../../actions/update_brand_action.php">
            <input type="hidden" name="brand_id" id="editId">
            <input type="text" name="brand_name" id="editName" required>
            <button type="submit">Save</button>
            <button type="button" onclick="document.getElementById('editBox').close()">Cancel</button>
        </form>
    </dialog>

    <dialog id="deleteBox">
        <form method="POST" action="../../actions/delete_brand_action.php">
            <p id="deleteMsg"></p>
            <input type="hidden" name="brand_id" id="deleteId">
            <button type="submit">Yes, delete</button>
            <button type="button" onclick="document.getElementById('deleteBox').close()">No</button>
        </form>
    </dialog>

</main>
</div>

    <script>
    function askEdit(id, name) {
        document.getElementById('editId').value = id;
        document.getElementById('editName').value = name;
        document.getElementById('editBox').showModal();
    }
    function askDelete(id, name) {
        document.getElementById('deleteId').value = id;
        document.getElementById('deleteMsg').textContent = 'Delete "' + name + '"?';
        document.getElementById('deleteBox').showModal();
    }
    </script>
</body>
</html>