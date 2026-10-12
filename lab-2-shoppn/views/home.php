<?php
require_once BASE_PATH . '/controllers/ProductController.php';
$productController=new ProductController();
if (isset($_GET['cat']) && filter_var($_GET['cat'], FILTER_VALIDATE_INT) && (int)$_GET['cat']>0) {
  $products=$productController->getProductsByCategory((int)$_GET['cat']); $heading='Products in this category';
} elseif (isset($_GET['brand']) && filter_var($_GET['brand'], FILTER_VALIDATE_INT) && (int)$_GET['brand']>0) {
  $products=$productController->getProductsByBrand((int)$_GET['brand']); $heading='Products by this brand';
} else { $products=$productController->getFeaturedProducts(6); $heading='Featured products'; }
$pageTitle='Home'; require BASE_PATH . '/views/layout/header.php';
?>
<section class="hero homepage-hero" aria-labelledby="homepage-headline">
  <img class="homepage-hero-image" src="<?= BASE_URL ?>/images/homepage/hero.jpg" width="1199" height="822" alt="People walking in casual streetwear, with relaxed trousers, shorts and layered tops" fetchpriority="high">
  <div class="homepage-hero-content">
    <p class="eyebrow">A MORE THOUGHTFUL WAY TO SHOP</p>
    <h1 id="homepage-headline">Find things worth owning.</h1>
    <p>Everyday favourites. Unexpected finds. All in one place.</p>
    <a class="button" href="<?= BASE_URL ?>/index.php#products">Shop Now</a>
  </div>
</section>
<section class="homepage-collections" aria-labelledby="collections-heading">
  <div class="section-heading"><div><p class="eyebrow">FIND YOUR EVERYDAY</p><h2 id="collections-heading">A little inspiration</h2></div></div>
  <div class="homepage-collection-grid">
    <article class="homepage-collection">
      <a class="homepage-collection-image-link" href="<?= BASE_URL ?>/index.php#products" aria-label="Shop everyday styles">
        <img src="<?= BASE_URL ?>/images/homepage/collection-1.jpg" width="735" height="732" alt="A group modelling black, white and grey shirts, relaxed trousers and tailored layers" loading="lazy" decoding="async">
      </a>
      <div class="homepage-collection-caption"><h3>Everyday, considered</h3><a class="text-link" href="<?= BASE_URL ?>/index.php#products">Shop everyday styles <span aria-hidden="true">↗</span></a></div>
    </article>
    <article class="homepage-collection">
      <a class="homepage-collection-image-link" href="<?= BASE_URL ?>/index.php#products" aria-label="Explore colourful styles">
        <img src="<?= BASE_URL ?>/images/homepage/collection-2.jpg" width="735" height="543" alt="A group in colourful streetwear, including green layers, bright caps and patterned tops" loading="lazy" decoding="async">
      </a>
      <div class="homepage-collection-caption"><h3>Colour your everyday</h3><a class="text-link" href="<?= BASE_URL ?>/index.php#products">Explore colourful styles <span aria-hidden="true">↗</span></a></div>
    </article>
  </div>
</section>
<div class="content-layout"><?php require BASE_PATH . '/views/layout/sidebar.php'; ?>
<main class="main-content" id="products"><div class="section-heading"><div><p class="eyebrow">THE COLLECTION</p><h2><?= clean($heading) ?></h2></div><span class="muted"><?= count($products) ?> item(s)</span></div>
<?php if (!$products): ?><div class="empty-state"><h3>No products found</h3><p>There are no products to show here yet. Try another category or check back soon.</p></div>
<?php else: ?><div class="product-grid"><?php foreach ($products as $product): ?><?php require BASE_PATH . '/views/layout/product_card.php'; ?><?php endforeach; ?></div><?php endif; ?>
</main></div>
<?php require BASE_PATH . '/views/layout/footer.php'; ?>
