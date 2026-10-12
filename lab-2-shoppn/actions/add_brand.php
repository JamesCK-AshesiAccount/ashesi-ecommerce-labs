<?php
require_once dirname(__DIR__) . '/core/core.php';
require_admin();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('index.php?page=brand');
require_csrf('index.php?page=brand');
require_once BASE_PATH . '/controllers/ProductController.php';
$name = strip_tags(input_text($_POST, 'brand_name'));
if ($name === '' || strlen($name) > 100) {
  set_flash('error', 'Enter a valid brand name up to 100 characters.' );
  redirect('index.php?page=brand');
}
try {
  $controller = new ProductController();
  $ok = $controller->addBrand($name);
  set_flash($ok ? 'success' : 'error', $ok ? 'Brand added.' : 'Could not save brand.');
} catch (InvalidArgumentException $error) {
  set_flash('error', $error->getMessage());
} catch (Throwable $error) {
  log_err('Brand save failed (code ' . $error->getCode() . ').');
  set_flash('error', 'Could not save brand. Please try again later.');
}
redirect('index.php?page=brand');
