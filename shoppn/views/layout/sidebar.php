<?php
$current = basename($_SERVER['PHP_SELF']);

$links = [
    ['Home',              'index.php'],
    ['Manage Brands',     'views/admin/brand.php'],
    ['Manage Categories', 'views/admin/category.php'],
    ['Add Product',       'views/admin/product.php'],
    ['All Products',      'views/all_products.php'],
    ['Customers',         'views/customers.php'],
    ['View Store',        'views/products.php'],
];
$navName = $_SESSION['customer_name'] ?? 'Admin';
?>
<style>
body { padding-top: 113px !important; }
body:has(.admin-wrap) { padding: 73px 0 0 !important; }
.admin-wrap { display: block !important; min-height: 100vh; }
.admin-main { padding: 56px 8vw 80px; }
.admin-main table { max-width: none; }
.page-main .hero { margin: 0; min-height: 55vh; padding: 80px 8vw; }
.page-main .hero h1 { font-size: clamp(44px, 6vw, 88px); }

#navTopbar { position: fixed; top: 0; left: 0; right: 0; z-index: 50; height: 73px; display: grid; grid-template-columns: 1fr auto 1fr; align-items: center; padding: 0 8vw; background: #f0ede6; border-bottom: 1px solid #d8d3c8; }
#navTopbar .nav-btn, #navTopbar .nav-btn:hover { justify-self: start; display: flex; align-items: center; gap: 14px; padding: 0; background: none; border: 0; color: #0a0a0a; font: 500 12px 'DM Sans', sans-serif; letter-spacing: 0.2em; text-transform: uppercase; cursor: pointer; }
#navTopbar .nav-bars { display: flex; flex-direction: column; gap: 5px; }
#navTopbar .nav-bars i { display: block; width: 22px; height: 1.5px; background: #0a0a0a; }
#navTopbar .nav-logo { font-family: 'Cormorant Garamond', serif; font-size: 28px; letter-spacing: -0.02em; color: #0a0a0a; text-decoration: none; }
#navTopbar .nav-user { justify-self: end; font-size: 12px; letter-spacing: 0.15em; text-transform: uppercase; color: #6f6a60; }

#navOverlay { position: fixed; inset: 0; z-index: 60; background: rgba(5, 5, 5, 0.5); opacity: 0; pointer-events: none; transition: opacity 0.3s; }
#navOverlay.open { opacity: 1; pointer-events: auto; }

#navDrawer { position: fixed; top: 0; left: 0; z-index: 70; width: 320px; max-width: 85vw; height: 100vh; padding: 32px; background: #050505; color: #fff; display: flex; flex-direction: column; transform: translateX(-100%); transition: transform 0.3s ease; }
#navDrawer.open { transform: translateX(0); }
#navDrawer .nav-close, #navDrawer .nav-close:hover { align-self: flex-start; padding: 0; margin-bottom: 40px; background: none; border: 0; color: #fff; font: 500 12px 'DM Sans', sans-serif; letter-spacing: 0.2em; text-transform: uppercase; cursor: pointer; }
#navDrawer .nav-list { display: flex; flex-direction: column; gap: 6px; flex: 1; }
#navDrawer .nav-link { padding: 8px 0; color: #fff; font-family: 'Cormorant Garamond', serif; font-size: 30px; line-height: 1.2; text-decoration: none; opacity: 0.75; }
#navDrawer .nav-link:hover, #navDrawer .nav-link.active { opacity: 1; color: #b89b6a; }
#navDrawer .nav-foot { padding-top: 20px; border-top: 1px solid #2a2a2a; }
#navDrawer .nav-foot small { display: block; margin-bottom: 12px; font-size: 12px; letter-spacing: 0.15em; text-transform: uppercase; color: #999; }
#navDrawer .nav-foot a { color: #fff; font-size: 12px; letter-spacing: 0.2em; text-transform: uppercase; text-decoration: none; }
#navDrawer .nav-foot a:hover { color: #b89b6a; }
</style>

<div id="navTopbar">
    <button type="button" class="nav-btn" onclick="toggleNav()" aria-label="Menu">
        <span class="nav-bars"><i></i><i></i><i></i></span> Menu
    </button>
    <a href="<?= BASE_URL ?>index.php" class="nav-logo">SHOPPN.</a>
    <span class="nav-user"><?= htmlspecialchars($navName) ?></span>
</div>

<div id="navOverlay" onclick="toggleNav()"></div>

<div id="navDrawer">
    <button type="button" class="nav-close" onclick="toggleNav()">Close</button>
    <div class="nav-list">
        <?php foreach ($links as [$label, $path]): ?>
            <a href="<?= BASE_URL . $path ?>" class="nav-link <?= basename($path) === $current ? 'active' : '' ?>">
                <?= htmlspecialchars($label) ?>
            </a>
        <?php endforeach; ?>
    </div>
    <div class="nav-foot">
        <small><?= htmlspecialchars($navName) ?></small>
        <a href="<?= BASE_URL ?>logout.php">Logout</a>
    </div>
</div>

<script>
function toggleNav() {
    document.getElementById('navDrawer').classList.toggle('open');
    document.getElementById('navOverlay').classList.toggle('open');
}
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        document.getElementById('navDrawer').classList.remove('open');
        document.getElementById('navOverlay').classList.remove('open');
    }
});
</script>