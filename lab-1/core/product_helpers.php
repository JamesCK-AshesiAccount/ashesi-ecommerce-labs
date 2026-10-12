<?php
// check product fields before handling the upload
function product_form_data($post) {
  $data = [
    'cat'=>filter_var($post['product_cat'] ?? null, FILTER_VALIDATE_INT),
    'brand'=>filter_var($post['product_brand'] ?? null, FILTER_VALIDATE_INT),
    'title'=>strip_tags(input_text($post, 'product_title')),
    'price'=>filter_var($post['product_price'] ?? null, FILTER_VALIDATE_FLOAT),
    'desc'=>strip_tags(input_text($post, 'product_desc')),
    'keywords'=>strip_tags(input_text($post, 'product_keywords'))
  ];
  if (!$data['cat'] || $data['cat'] < 1 || !$data['brand'] || $data['brand'] < 1 || $data['title'] === '' || strlen($data['title']) > 200 || input_text($post, 'product_price') === '' || $data['price'] === false || !is_finite((float)$data['price']) || $data['price'] < 0 || strlen($data['desc']) > 500 || strlen($data['keywords']) > 100) {
    throw new InvalidArgumentException('Check the product fields. Choose a category and brand, enter a title and a valid price.');
  }
  return $data;
}
// save only real uploaded images with a random filename
function save_product_image($file, $required) {
  if (!$file || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
    if ($required) throw new InvalidArgumentException('Please choose a product image.');
    return null;
  }
  if (($file['error'] ?? null) !== UPLOAD_ERR_OK) throw new InvalidArgumentException('Image upload failed. Choose an image up to 5 MB.');
  if (!is_string($file['tmp_name'] ?? null) || !is_uploaded_file($file['tmp_name'])) throw new InvalidArgumentException('Please choose a valid uploaded image.');
  if (filesize($file['tmp_name']) > 5 * 1024 * 1024) throw new InvalidArgumentException('Image must be 5 MB or smaller.');
  $types = ['image/jpeg'=>'jpg', 'image/png'=>'png', 'image/gif'=>'gif', 'image/webp'=>'webp'];
  $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
  if (!isset($types[$mime]) || @getimagesize($file['tmp_name']) === false) throw new InvalidArgumentException('Upload a real JPG, PNG, GIF or WEBP image.');
  $name = 'product_' . bin2hex(random_bytes(16)) . '.' . $types[$mime];
  $dir = BASE_PATH . '/images/products/';
  if (!is_dir($dir) && !mkdir($dir, 0755, true)) throw new RuntimeException('Upload directory unavailable.');
  if (!move_uploaded_file($file['tmp_name'], $dir . $name)) throw new RuntimeException('Could not save uploaded image.');
  return $name;
}
