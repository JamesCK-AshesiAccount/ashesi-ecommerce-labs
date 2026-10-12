<?php
require_admin(); require_once BASE_PATH.'/controllers/ProductController.php'; $pc=new ProductController();
$editId=filter_input(INPUT_GET,'edit_id',FILTER_VALIDATE_INT); $editing=$editId && $editId>0 ? $pc->getCategoryById($editId) : false;
if (isset($_GET['edit_id']) && !$editing) { set_flash('error', 'That category could not be found.'); redirect('index.php?page=category'); }
$pageTitle='Manage categories'; require BASE_PATH.'/views/layout/header.php'; ?>
<section class="admin-panel"><p class="eyebrow">ADMIN</p><h1><?= $editing?'Edit category':'Manage categories' ?></h1>
<form method="post" action="<?= BASE_URL ?>/actions/<?= $editing?'update_category':'add_category' ?>.php" class="admin-form">
<input type="hidden" name="csrf_token" value="<?= clean(csrf_token()) ?>">
<?php if($editing): ?><input type="hidden" name="cat_id" value="<?= (int)$editing['cat_id'] ?>"><?php endif; ?>
<label>Category name<input name="cat_name" required maxlength="100" value="<?= clean($editing['cat_name']??'') ?>"></label>
<button class="button" type="submit"><?= $editing?'Save changes':'Add category' ?></button><?php if($editing): ?> <a class="text-link" href="<?= BASE_URL ?>/index.php?page=category">Cancel</a><?php endif; ?></form>
<h2>Existing categories</h2><table><thead><tr><th>ID</th><th>Name</th><th>Action</th></tr></thead><tbody>
<?php foreach($pc->getAllCategories() as $c): ?><tr><td><?= (int)$c['cat_id'] ?></td><td><?= clean($c['cat_name']) ?></td><td><a class="text-link" href="<?= BASE_URL ?>/index.php?page=category&edit_id=<?= (int)$c['cat_id'] ?>">Edit</a></td></tr><?php endforeach; ?>
</tbody></table></section><?php require BASE_PATH.'/views/layout/footer.php'; ?>
