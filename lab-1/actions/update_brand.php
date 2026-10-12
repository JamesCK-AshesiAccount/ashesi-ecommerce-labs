<?php
require_once dirname(__DIR__) . '/core/core.php';
require_admin();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('index.php?page=brand');
require_csrf('index.php?page=brand');
require_once BASE_PATH . '/controllers/ProductController.php';
$name = strip_tags(input_text($_POST, 'brand_name'));
$id = filter_var($_POST['brand_id'] ?? null, FILTER_VALIDATE_INT);
if ($name === '' || strlen($name) > 100 || !$id || $id < 1) {
  set_flash('error', 'Enter a valid brand name up to 100 characters.' );
  redirect('index.php?page=brand');
}
try {
  $controller = new ProductController();
  if (!$controller->getBrandById($id)) throw new InvalidArgumentException('That brand could not be found.');
  $ok = $controller->updateBrand($id, $name);
  set_flash($ok ? 'success' : 'error', $ok ? 'Brand updated.' : 'Could not save brand.');
} catch (InvalidArgumentException $error) {
  set_flash('error', $error->getMessage());
} catch (Throwable $error) {
  log_err('Brand save failed (code ' . $error->getCode() . ').');
  set_flash('error', 'Could not save brand. Please try again later.');
}
redirect('index.php?page=brand');
