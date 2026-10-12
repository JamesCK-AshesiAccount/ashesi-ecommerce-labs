<?php
require_once __DIR__ . '/core/core.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('index.php');
require_csrf('index.php');
$_SESSION=[]; if (ini_get('session.use_cookies')) { $p=session_get_cookie_params(); setcookie(session_name(),'',time()-42000,$p['path'],$p['domain'],$p['secure'],$p['httponly']); }
session_destroy(); header('Location: '.BASE_URL.'/index.php'); exit;
