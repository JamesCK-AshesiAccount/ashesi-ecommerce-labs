<?php
require_admin(); require_once BASE_PATH.'/controllers/ProductController.php'; $pc=new ProductController();
$editId=filter_input(INPUT_GET,'edit_id',FILTER_VALIDATE_INT); $editing=$editId && $editId>0 ? $pc->getProductById($editId) : false;
if (isset($_GET['edit_id']) && !$editing) { set_flash('error', 'That product could not be found.'); redirect('index.php?page=products'); }
$categories=$pc->getAllCategories(); $brands=$pc->getAllBrands(); $pageTitle='Manage products'; require BASE_PATH.'/views/layout/header.php'; ?>
<section class="admin-panel"><p class="eyebrow">ADMIN</p><h1><?= $editing?'Edit product':'Manage products' ?></h1>
<?php if(!$categories || !$brands): ?><div class="flash error">Add at least one category and one brand before creating products.</div><?php endif; ?>
<form method="post" enctype="multipart/form-data" action="<?= BASE_URL ?>/actions/<?= $editing?'update_product':'add_product' ?>.php" class="admin-form" id="product-form">
<input type="hidden" name="csrf_token" value="<?= clean(csrf_token()) ?>">
<?php if($editing): ?><input type="hidden" name="product_id" value="<?= (int)$editing['product_id'] ?>"><?php endif; ?>
<label>Product title<input name="product_title" required maxlength="200" value="<?= clean($editing['product_title']??'') ?>"></label>
<label>Price (GH₵)<input type="number" name="product_price" min="0" step="0.01" required value="<?= clean($editing['product_price']??'') ?>"></label>
<label>Description<textarea name="product_desc" maxlength="500" rows="4"><?= clean($editing['product_desc']??'') ?></textarea></label>
<label>Keywords<input name="product_keywords" maxlength="100" value="<?= clean($editing['product_keywords']??'') ?>" placeholder="e.g. shoes, casual, leather"></label>
<label>Category<select name="product_cat" required><option value="">Choose category</option><?php foreach($categories as $c): ?><option value="<?= (int)$c['cat_id'] ?>" <?= (int)($editing['product_cat']??0)===(int)$c['cat_id']?'selected':'' ?>><?= clean($c['cat_name']) ?></option><?php endforeach; ?></select></label>
<label>Brand<select name="product_brand" required><option value="">Choose brand</option><?php foreach($brands as $b): ?><option value="<?= (int)$b['brand_id'] ?>" <?= (int)($editing['product_brand']??0)===(int)$b['brand_id']?'selected':'' ?>><?= clean($b['brand_name']) ?></option><?php endforeach; ?></select></label>
<label>Product image <small>(JPG, PNG, GIF or WEBP; max 5 MB<?= $editing?' · leave empty to keep current image':'' ?>)</small><input type="file" name="product_image" accept="image/jpeg,image/png,image/gif,image/webp" <?= $editing?'':'required' ?>></label>
<?php if($editing && !empty($editing['product_image'])): ?><img class="current-product-image" src="<?= BASE_URL ?>/images/products/<?= rawurlencode(basename($editing['product_image'])) ?>" alt="Current image for <?= clean($editing['product_title']) ?>" width="160" height="160"><?php endif; ?>
<button class="button" type="submit" <?= (!$categories || !$brands)?'disabled':'' ?>><?= $editing?'Save product':'Add product' ?></button><?php if($editing): ?> <a class="text-link" href="<?= BASE_URL ?>/index.php?page=products">Cancel</a><?php endif; ?></form>
<h2>Products</h2><div class="table-wrap"><table><thead><tr><th>ID</th><th>Product</th><th>Price</th><th>Category</th><th>Brand</th><th>Action</th></tr></thead><tbody>
<?php foreach($pc->getAllProducts() as $p): ?><tr><td><?= (int)$p['product_id'] ?></td><td><?= clean($p['product_title']) ?></td><td>GH₵ <?= number_format((float)$p['product_price'],2) ?></td><td><?= clean($p['cat_name']??'') ?></td><td><?= clean($p['brand_name']??'') ?></td><td><a class="text-link" href="<?= BASE_URL ?>/index.php?page=products&edit_id=<?= (int)$p['product_id'] ?>">Edit</a></td></tr><?php endforeach; ?>
</tbody></table></div></section><?php require BASE_PATH.'/views/layout/footer.php'; ?>
