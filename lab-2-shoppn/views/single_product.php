<?php
require_once BASE_PATH . '/controllers/ProductController.php';
$id=filter_input(INPUT_GET,'pro_id',FILTER_VALIDATE_INT);
if (!$id || $id<1) { set_flash('error','Please select a valid product.'); redirect('index.php'); }
$productController=new ProductController(); $product=$productController->getProductById($id);
if (!$product) { set_flash('error','That product could not be found.'); redirect('index.php'); }
$pageTitle=$product['product_title']; require BASE_PATH . '/views/layout/header.php';
?>
<div class="breadcrumb"><a href="<?= BASE_URL ?>/index.php">Home</a> / Product details</div>
<section class="single-product"><div class="single-image"><?php if (!empty($product['product_image']) && is_file(BASE_PATH.'/images/products/'.$product['product_image'])): ?><img src="<?= BASE_URL ?>/images/products/<?= rawurlencode($product['product_image']) ?>" alt="<?= clean($product['product_title']) ?>"><?php else: ?><div class="image-placeholder">SHOPPN</div><?php endif; ?></div>
<div class="single-info"><p class="eyebrow"><?= clean($product['cat_name'] ?? 'Collection') ?><?= !empty($product['brand_name']) ? ' / '.clean($product['brand_name']) : '' ?></p><h1><?= clean($product['product_title']) ?></h1><p class="single-price">GH₵ <?= number_format((float)$product['product_price'],2) ?></p><p><?= nl2br(clean($product['product_desc'] ?? 'No description available yet.')) ?></p><?php if (!empty($product['product_keywords'])): ?><p class="muted"><strong>Keywords:</strong> <?= clean($product['product_keywords']) ?></p><?php endif; ?><a class="button" href="<?= BASE_URL ?>/index.php?add_cart=<?= (int)$product['product_id'] ?>">Add to Cart</a><p class="muted">The shopping cart is coming soon.</p><a class="button secondary" href="<?= BASE_URL ?>/index.php">Continue browsing</a></div></section>
<?php require BASE_PATH . '/views/layout/footer.php'; ?>
