<?php
if (!isset($flash)) $flash=get_flash();
?>
<!DOCTYPE html><html lang="en"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= isset($pageTitle) ? clean($pageTitle).' | Shoppn' : 'Shoppn | Find things worth owning' ?></title>
<link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
<script defer src="<?= BASE_URL ?>/js/validate.js"></script>
<script defer src="<?= BASE_URL ?>/js/forms.js"></script>
</head><body>
<header class="site-header"><a class="logo" href="<?= BASE_URL ?>/index.php">shoppn<span>.</span></a>
<form class="search-form" action="<?= BASE_URL ?>/index.php" method="get"><input type="hidden" name="page" value="search"><input name="user_query" value="<?= clean(input_text($_GET, 'user_query')) ?>" placeholder="Search products..." aria-label="Search products"><button type="submit">Search</button></form>
<nav>
<a href="<?= BASE_URL ?>/index.php">Home</a>
<?php if (!is_logged_in()): ?><a href="<?= BASE_URL ?>/index.php?page=register">Register</a><a href="<?= BASE_URL ?>/index.php?page=login">Login</a>
<?php else: ?><span>Hi, <?= clean($_SESSION['customer_name'] ?? 'there') ?></span><a href="<?= BASE_URL ?>/index.php?page=account">My Account</a><form class="logout-form" method="post" action="<?= BASE_URL ?>/logout.php"><input type="hidden" name="csrf_token" value="<?= clean(csrf_token()) ?>"><button class="logout-button" type="submit">Logout</button></form><?php endif; ?>
<?php if (is_admin()): ?><a href="<?= BASE_URL ?>/index.php?page=brand">Brands</a><a href="<?= BASE_URL ?>/index.php?page=category">Categories</a><a href="<?= BASE_URL ?>/index.php?page=products">Products</a><?php endif; ?>
</nav></header>
<?php if ($flash): ?><div class="flash <?= clean($flash['type']) ?>"><?= clean($flash['message']) ?></div><?php endif; ?>
<div class="page-shell">
