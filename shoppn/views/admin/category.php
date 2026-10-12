<?php
require_once __DIR__ . "/../../core/core.php";
require_admin();
require_once __DIR__ . "/../../controllers/ProductController.php";

$controller = new ProductController();
$categories = $controller->getAllCategories();

$editCat = null;
if (isset($_GET['edit_id'])) {
    $editCat = $controller->getCategoryById((int)$_GET['edit_id']);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../../css/style.css">
    <title>Manage Categories</title>
</head>
<body>
<div class="admin-wrap">
<?php include __DIR__ . '/../layout/sidebar.php'; ?>
<main class="admin-main">

    <h2>Manage Categories</h2>

    <?php if (!empty($_SESSION['success'])): ?>
        <p style="color:green;"><?= htmlspecialchars($_SESSION['success']); ?></p>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
        <p style="color:red;"><?= htmlspecialchars($_SESSION['error']); ?></p>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if ($editCat): ?>
        <form method="POST" action="../../actions/update_category_action.php" class="cat-form">
            <input type="hidden" name="cat_id" value="<?= (int)$editCat['cat_id']; ?>">
            <input type="text" name="cat_name" value="<?= htmlspecialchars($editCat['cat_name']); ?>" required>
            <button type="submit">Update</button>
            <a href="category.php"><button type="button">Cancel</button></a>
        </form>
    <?php else: ?>
        <form method="POST" action="../../actions/add_category_action.php" class="cat-form">
            <input type="text" name="cat_name" placeholder="Category name" required>
            <button type="submit">Add Category</button>
        </form>
    <?php endif; ?>

    <table border="1" cellpadding="6">
        <?php foreach ($categories as $cat): ?>
            <tr>
                <td><?= htmlspecialchars($cat['cat_name']); ?></td>
                <td>
                    <a href="category.php?edit_id=<?= (int)$cat['cat_id']; ?>"><button type="button">Edit</button></a>
                    <button type="button"
                        onclick="askDelete(<?= (int)$cat['cat_id']; ?>, <?= htmlspecialchars(json_encode($cat['cat_name']), ENT_QUOTES); ?>)">Delete</button>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <dialog id="deleteBox">
        <form method="POST" action="../../actions/delete_category_action.php">
            <p id="deleteMsg"></p>
            <input type="hidden" name="cat_id" id="deleteId">
            <button type="submit">Yes, delete</button>
            <button type="button" onclick="document.getElementById('deleteBox').close()">No</button>
        </form>
    </dialog>

</main>
</div>

    <script>
    function askDelete(id, name) {
        document.getElementById('deleteId').value = id;
        document.getElementById('deleteMsg').textContent = 'Delete "' + name + '"?';
        document.getElementById('deleteBox').showModal();
    }
    </script>

</body>
</html>