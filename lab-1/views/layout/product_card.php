<article class="product-card">
<a href="<?= BASE_URL ?>/index.php?page=product&pro_id=<?= (int)$product['product_id'] ?>" class="product-card-link">
<div class="product-image"><?php if (!empty($product['product_image']) && is_file(BASE_PATH.'/images/products/'.$product['product_image'])): ?><img src="<?= BASE_URL ?>/images/products/<?= rawurlencode($product['product_image']) ?>" alt="<?= clean($product['product_title']) ?>"><?php else: ?><div class="image-placeholder">SHOPPN</div><?php endif; ?></div>
<div class="product-info"><p class="product-category"><?= clean($product['cat_name'] ?? 'Collection') ?><?= !empty($product['brand_name']) ? ' · '.clean($product['brand_name']) : '' ?></p><h3><?= clean($product['product_title']) ?></h3><p class="price">GH₵ <?= number_format((float)$product['product_price'],2) ?></p></div>
</a><a class="text-link" href="<?= BASE_URL ?>/index.php?page=product&pro_id=<?= (int)$product['product_id'] ?>">View details →</a>
<a class="text-link" href="<?= BASE_URL ?>/index.php?add_cart=<?= (int)$product['product_id'] ?>">Add to Cart</a>
</article>
