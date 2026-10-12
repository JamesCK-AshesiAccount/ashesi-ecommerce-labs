<?php
require_once dirname(__DIR__) . '/core/core.php';
require_admin();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('index.php?page=products');
require_csrf('index.php?page=products');
require_once BASE_PATH . '/controllers/ProductController.php';
require_once BASE_PATH . '/core/product_helpers.php';
$newImage = null;
$returnUrl = 'index.php?page=products';
try {
  $controller = new ProductController();
  $id = filter_var($_POST['product_id'] ?? null, FILTER_VALIDATE_INT);
  if (!$id || $id < 1) throw new InvalidArgumentException('Choose a valid product.');
  $product = $controller->getProductById($id);
  if (!$product) throw new InvalidArgumentException('That product could not be found.');
  $returnUrl .= '&edit_id=' . $id;
  $data = product_form_data($_POST);
  if (!$controller->getCategoryById($data['cat']) || !$controller->getBrandById($data['brand'])) throw new InvalidArgumentException('Choose an existing category and brand.');
  $newImage = save_product_image($_FILES['product_image'] ?? null, false);
  $filename = $newImage ?? $product['product_image'];
  if (!$controller->updateProduct($id, $data['cat'], $data['brand'], $data['title'], (float)$data['price'], $data['desc'], $filename, $data['keywords'])) throw new RuntimeException('Product save failed.');
  set_flash('success', 'Product updated.');
  redirect('index.php?page=products');
} catch (InvalidArgumentException $error) {
  set_flash('error', $error->getMessage());
} catch (Throwable $error) {
  log_err('Product save failed (code ' . $error->getCode() . ').');
  set_flash('error', 'Could not save product. Please try again later.');
}
// remove only a new upload when its database save failed
if ($newImage !== null) @unlink(BASE_PATH . '/images/products/' . $newImage);
redirect($returnUrl);
