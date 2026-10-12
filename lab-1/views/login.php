<?php
$pageTitle='Login'; require BASE_PATH . '/views/layout/header.php';
?>
<section class="form-panel"><p class="eyebrow">WELCOME BACK</p><h1>Log in to Shoppn</h1><form method="post" action="<?= BASE_URL ?>/actions/login.php" id="login-form">
<input type="hidden" name="csrf_token" value="<?= clean(csrf_token()) ?>">
<label>Email<input type="email" name="customer_email" autocomplete="email" required maxlength="50"></label>
<label>Password<input type="password" name="customer_pass" autocomplete="current-password" required></label>
<button class="button" type="submit">Log in</button></form><p>New to Shoppn? <a class="text-link" href="<?= BASE_URL ?>/index.php?page=register">Create an account</a></p></section>
<?php require BASE_PATH . '/views/layout/footer.php'; ?>
