<?php
require_once dirname(__DIR__) . '/core/core.php';
require_admin();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('index.php?page=category');
require_csrf('index.php?page=category');
require_once BASE_PATH . '/controllers/ProductController.php';
$name = strip_tags(input_text($_POST, 'cat_name'));
$id = filter_var($_POST['cat_id'] ?? null, FILTER_VALIDATE_INT);
if ($name === '' || strlen($name) > 100 || !$id || $id < 1) {
  set_flash('error', 'Enter a valid category name up to 100 characters.' );
  redirect('index.php?page=category');
}
try {
  $controller = new ProductController();
  if (!$controller->getCategoryById($id)) throw new InvalidArgumentException('That category could not be found.');
  $ok = $controller->updateCategory($id, $name);
  set_flash($ok ? 'success' : 'error', $ok ? 'Category updated.' : 'Could not save category.');
} catch (InvalidArgumentException $error) {
  set_flash('error', $error->getMessage());
} catch (Throwable $error) {
  log_err('Category save failed (code ' . $error->getCode() . ').');
  set_flash('error', 'Could not save category. Please try again later.');
}
redirect('index.php?page=category');
