<?php
require_once __DIR__ . '/core/core.php';
$page = input_text($_GET, 'page');
if ($page === '') {
  if (isset($_GET['pro_id'])) $page = 'product';
  elseif (isset($_GET['user_query'])) $page = 'search';
  else $page = 'home';
}
if (isset($_GET['add_cart'])) set_flash('error', 'The shopping cart is not available yet. Please check back soon.');
switch ($page) {
  case 'register': require BASE_PATH . '/views/register.php'; break;
  case 'account': require BASE_PATH . '/views/account/my_account.php'; break;
  case 'login': require BASE_PATH . '/views/login.php'; break;
  case 'home': require BASE_PATH . '/views/home.php'; break;
  case 'product': require BASE_PATH . '/views/single_product.php'; break;
  case 'search': require BASE_PATH . '/views/search_results.php'; break;
  case 'brand': require BASE_PATH . '/views/admin/brand.php'; break;
  case 'category': require BASE_PATH . '/views/admin/category.php'; break;
  case 'products': require BASE_PATH . '/views/admin/product.php'; break;
  default: http_response_code(404); echo 'Page not found.';
}
