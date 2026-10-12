<?php
require_login();
$pageTitle='My Account'; require BASE_PATH . '/views/layout/header.php';
?>
<section class="form-panel"><p class="eyebrow">YOUR ACCOUNT</p><h1>Welcome, <?= clean($_SESSION['customer_name']) ?></h1><p>You are signed in as <?= clean($_SESSION['customer_email']) ?>.</p><a class="button" href="<?= BASE_URL ?>/index.php#products">Continue shopping</a></section>
<?php require BASE_PATH . '/views/layout/footer.php'; ?>
