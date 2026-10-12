<?php
require_once BASE_PATH . '/controllers/ProductController.php';
$query=substr(input_text($_GET, 'user_query'), 0, 200); $productController=new ProductController();
$products=$query==='' ? [] : $productController->searchProducts($query);
$pageTitle='Search results'; require BASE_PATH . '/views/layout/header.php';
?>
<div class="content-layout"><?php require BASE_PATH . '/views/layout/sidebar.php'; ?><main class="main-content"><div class="section-heading"><div><p class="eyebrow">SEARCH</p><h2>Results for “<?= clean($query) ?>”</h2></div><span class="muted"><?= count($products) ?> item(s)</span></div>
<?php if ($query===''): ?><div class="empty-state"><h3>What are you looking for?</h3><p>Enter a product name or keyword in the search bar.</p></div>
<?php elseif (!$products): ?><div class="empty-state"><h3>No products found</h3><p>Try a different product name or keyword.</p></div>
<?php else: ?><div class="product-grid"><?php foreach ($products as $product): require BASE_PATH . '/views/layout/product_card.php'; endforeach; ?></div><?php endif; ?></main></div>
<?php require BASE_PATH . '/views/layout/footer.php'; ?>
