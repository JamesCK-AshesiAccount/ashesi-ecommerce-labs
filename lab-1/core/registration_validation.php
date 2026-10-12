<?php
// share the password rules with the registration page
function registration_weak_passwords() {
  return ['password', 'password123', 'password1234', 'password12345', 'password123456', 'qwerty123456', 'qwertyuiop123', 'welcome123456', 'letmein123456', 'administrator', 'admin12345678', 'iloveyou12345', 'changeme12345', '123456789012'];
}
function registration_password_error($password) {
  if (preg_match('//u', $password) !== 1 || preg_match_all('/./us', $password) < 12 || strlen($password) > 72)
    return 'Use at least 12 characters and no more than 72 bytes.';
  if (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password) || !preg_match('/[^a-zA-Z0-9\s]/u', $password))
    return 'Include uppercase and lowercase letters, a number, and a symbol.';
  $normalized = preg_replace('/[^a-z0-9]/', '', strtolower($password));
  if (in_array($normalized, registration_weak_passwords(), true)) return 'This password is too common. Choose a less predictable password.';
  return '';
}
function registration_errors($data) {
  $errors = [];
  foreach (['customer_name'=>[2,100], 'customer_email'=>[1,50], 'customer_country'=>[1,30], 'customer_city'=>[1,30], 'customer_contact'=>[7,15]] as $field=>$limits) {
    if (strlen($data[$field] ?? '') < $limits[0] || strlen($data[$field] ?? '') > $limits[1]) $errors[$field] = 'Enter ' . $limits[0] . '–' . $limits[1] . ' bytes for this field.';
  }
  if (!filter_var($data['customer_email'] ?? '', FILTER_VALIDATE_EMAIL)) $errors['customer_email'] = 'Enter a valid email address.';
  if (!preg_match('/^[0-9+\-\s]{7,15}$/', $data['customer_contact'] ?? '')) $errors['customer_contact'] = 'Use 7–15 characters: digits, +, spaces or hyphens.';
  $passwordError = registration_password_error($data['customer_pass'] ?? '');
  if ($passwordError) $errors['customer_pass'] = $passwordError;
  if (($data['customer_pass'] ?? '') !== ($data['confirm_password'] ?? '')) $errors['confirm_password'] = 'Passwords do not match.';
  return $errors;
}
