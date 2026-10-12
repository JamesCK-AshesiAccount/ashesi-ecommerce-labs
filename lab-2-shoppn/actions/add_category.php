<?php
require_once dirname(__DIR__) . '/core/core.php';
require_admin();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('index.php?page=category');
require_csrf('index.php?page=category');
require_once BASE_PATH . '/controllers/ProductController.php';
$name = strip_tags(input_text($_POST, 'cat_name'));
if ($name === '' || strlen($name) > 100) {
  set_flash('error', 'Enter a valid category name up to 100 characters.' );
  redirect('index.php?page=category');
}
try {
  $controller = new ProductController();
  $ok = $controller->addCategory($name);
  set_flash($ok ? 'success' : 'error', $ok ? 'Category added.' : 'Could not save category.');
} catch (InvalidArgumentException $error) {
  set_flash('error', $error->getMessage());
} catch (Throwable $error) {
  log_err('Category save failed (code ' . $error->getCode() . ').');
  set_flash('error', 'Could not save category. Please try again later.');
}
redirect('index.php?page=category');
