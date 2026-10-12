<?php
require_once BASE_PATH . '/controllers/ProductController.php';
if (!isset($productController)) $productController=new ProductController();
$sidebarCategories=$productController->getAllCategories();
$sidebarBrands=$productController->getAllBrands();
?>
<aside class="sidebar">
  <section><h3>Categories</h3><ul>
  <?php foreach ($sidebarCategories as $cat): ?><li><a href="<?= BASE_URL ?>/index.php?cat=<?= (int)$cat['cat_id'] ?>"><?= clean($cat['cat_name']) ?></a></li><?php endforeach; ?>
  <?php if (!$sidebarCategories): ?><li class="muted">No categories yet.</li><?php endif; ?>
  </ul></section>
  <section><h3>Brands</h3><ul>
  <?php foreach ($sidebarBrands as $brand): ?><li><a href="<?= BASE_URL ?>/index.php?brand=<?= (int)$brand['brand_id'] ?>"><?= clean($brand['brand_name']) ?></a></li><?php endforeach; ?>
  <?php if (!$sidebarBrands): ?><li class="muted">No brands yet.</li><?php endif; ?>
  </ul></section>
</aside>
