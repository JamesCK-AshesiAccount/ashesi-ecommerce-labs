<?php
require_admin(); require_once BASE_PATH.'/controllers/ProductController.php'; $pc=new ProductController();
$editId=filter_input(INPUT_GET,'edit_id',FILTER_VALIDATE_INT); $editing=$editId && $editId>0 ? $pc->getBrandById($editId) : false;
if (isset($_GET['edit_id']) && !$editing) { set_flash('error', 'That brand could not be found.'); redirect('index.php?page=brand'); }
$pageTitle='Manage brands'; require BASE_PATH.'/views/layout/header.php'; ?>
<section class="admin-panel"><p class="eyebrow">ADMIN</p><h1><?= $editing?'Edit brand':'Manage brands' ?></h1>
<form method="post" action="<?= BASE_URL ?>/actions/<?= $editing?'update_brand':'add_brand' ?>.php" class="admin-form">
<input type="hidden" name="csrf_token" value="<?= clean(csrf_token()) ?>">
<?php if($editing): ?><input type="hidden" name="brand_id" value="<?= (int)$editing['brand_id'] ?>"><?php endif; ?>
<label>Brand name<input name="brand_name" required maxlength="100" value="<?= clean($editing['brand_name']??'') ?>"></label>
<button class="button" type="submit"><?= $editing?'Save changes':'Add brand' ?></button><?php if($editing): ?> <a class="text-link" href="<?= BASE_URL ?>/index.php?page=brand">Cancel</a><?php endif; ?></form>
<h2>Existing brands</h2><table><thead><tr><th>ID</th><th>Name</th><th>Action</th></tr></thead><tbody>
<?php foreach($pc->getAllBrands() as $b): ?><tr><td><?= (int)$b['brand_id'] ?></td><td><?= clean($b['brand_name']) ?></td><td><a class="text-link" href="<?= BASE_URL ?>/index.php?page=brand&edit_id=<?= (int)$b['brand_id'] ?>">Edit</a></td></tr><?php endforeach; ?>
</tbody></table></section><?php require BASE_PATH.'/views/layout/footer.php'; ?>
