<?php
ini_set('display_errors', '0');
ini_set('log_errors', '1');
require_once dirname(__DIR__) . '/core/core.php';
require_once BASE_PATH . '/controllers/CustomerController.php';
require_once BASE_PATH . '/core/registration_validation.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('index.php?page=register');
$token = $_POST['csrf_token'] ?? null;
if (!is_string($token) || !isset($_SESSION['registration_csrf']) || !hash_equals($_SESSION['registration_csrf'], $token)) {
  set_flash('error', 'Your form expired. Please try again.');
  redirect('index.php?page=register');
}
$data = [];
foreach (['customer_name','customer_email','customer_country','customer_city','customer_contact','customer_pass','confirm_password'] as $field) {
  $value = $_POST[$field] ?? '';
  $data[$field] = is_string($value) ? $value : '';
  if (!in_array($field, ['customer_pass','confirm_password'], true)) $data[$field] = trim($data[$field]);
}
$data['customer_email'] = strtolower($data['customer_email']);
$_SESSION['registration_old'] = array_diff_key($data, array_flip(['customer_pass','confirm_password']));
$errors = registration_errors($data);
if ($errors) {
  $_SESSION['registration_errors'] = $errors;
  set_flash('error', 'Please correct the highlighted fields.');
  redirect('index.php?page=register');
}
try {
  $result = (new CustomerController())->register($data['customer_name'], $data['customer_email'], $data['customer_pass'], $data['customer_country'], $data['customer_city'], $data['customer_contact'], $data['confirm_password']);
} catch (Throwable $error) {
  // do not log submitted passwords or other customer details
  log_err('Registration failed: ' . get_class($error) . ' (code ' . $error->getCode() . ').');
  $result = ['success'=>false, 'message'=>'Registration is temporarily unavailable. Please try again later.'];
}
set_flash($result['success'] ? 'success' : 'error', $result['message']);
if ($result['success']) {
  unset($_SESSION['registration_old'], $_SESSION['registration_errors'], $_SESSION['registration_csrf']);
  redirect('index.php?page=login');
}
redirect('index.php?page=register');
