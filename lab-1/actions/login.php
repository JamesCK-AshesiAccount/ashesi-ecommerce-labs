<?php
require_once dirname(__DIR__) . '/core/core.php';
require_once BASE_PATH . '/controllers/CustomerController.php';
if ($_SERVER['REQUEST_METHOD']!=='POST') redirect('index.php?page=login');
require_csrf('index.php?page=login');
$email=strtolower(input_text($_POST, 'customer_email'));
$pass=is_string($_POST['customer_pass'] ?? null) ? $_POST['customer_pass'] : '';
if (!filter_var($email,FILTER_VALIDATE_EMAIL) || $pass==='') { set_flash('error','Enter a valid email and password.'); redirect('index.php?page=login'); }
try { $customer=(new CustomerController())->login($email,$pass); }
catch (Throwable $error) {
  log_err('Login failed (code ' . $error->getCode() . ').');
  set_flash('error', 'Login is temporarily unavailable. Please try again later.');
  redirect('index.php?page=login');
}
if (!$customer) { set_flash('error','Email or password is incorrect.'); redirect('index.php?page=login'); }
session_regenerate_id(true);
unset($_SESSION['registration_csrf']);
$_SESSION['customer_id']=(int)$customer['customer_id']; $_SESSION['customer_name']=$customer['customer_name']; $_SESSION['customer_email']=$customer['customer_email']; $_SESSION['user_role']=(int)$customer['user_role'];
set_flash('success','Welcome back, '.$customer['customer_name'].'!'); redirect('index.php');
