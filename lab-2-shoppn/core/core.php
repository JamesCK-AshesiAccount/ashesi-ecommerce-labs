<?php
ini_set('display_errors', '0');
ini_set('log_errors', '1');
if (session_status() === PHP_SESSION_NONE) {
  session_set_cookie_params(['httponly'=>true, 'samesite'=>'Lax', 'secure'=>!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off']);
  session_start();
}
date_default_timezone_set('Africa/Accra');
define('BASE_PATH', dirname(__DIR__));
// use the current project folder in links
$scriptDirectory = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php'));
if (basename($scriptDirectory) === 'actions') $scriptDirectory = dirname($scriptDirectory);
define('BASE_URL', rtrim($scriptDirectory, '/.'));
require_once BASE_PATH . '/core/db_class.php';

function get_ip() { return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1'; }
function redirect($url) { header('Location: ' . BASE_URL . '/' . ltrim($url, '/')); exit; }
function is_logged_in() { return isset($_SESSION['customer_id']); }
function is_admin() { return is_logged_in() && isset($_SESSION['user_role']) && (int)$_SESSION['user_role'] === 1; }
function require_login() { if (!is_logged_in()) { set_flash('error', 'Please log in first.'); redirect('index.php?page=login'); } }
function require_admin() { if (!is_admin()) { set_flash('error', 'Administrator access is required.'); redirect('index.php'); } }
function set_flash($type, $message) { $_SESSION['flash'] = ['type'=>$type, 'message'=>$message]; }
function get_flash() { if (!isset($_SESSION['flash'])) return null; $f=$_SESSION['flash']; unset($_SESSION['flash']); return $f; }
function clean($value) { return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function log_err($message) { error_log('[Shoppn] ' . $message); }
function csrf_token() {
  if (empty($_SESSION['registration_csrf'])) $_SESSION['registration_csrf'] = bin2hex(random_bytes(32));
  return $_SESSION['registration_csrf'];
}

function require_csrf($url) {
  $token = $_POST['csrf_token'] ?? null;
  if (!is_string($token) || empty($_SESSION['registration_csrf']) || !hash_equals($_SESSION['registration_csrf'], $token)) {
    set_flash('error', 'Your form expired. Please try again.');
    redirect($url);
  }
}
function input_text($values, $key) {
  return isset($values[$key]) && is_string($values[$key]) ? trim($values[$key]) : '';
}
// keep database details out of error pages
set_exception_handler(function ($error) {
  log_err('Request failed: ' . get_class($error) . ' (code ' . $error->getCode() . ').');
  http_response_code(500);
  echo 'Shoppn is temporarily unavailable. Please try again later.';
});
