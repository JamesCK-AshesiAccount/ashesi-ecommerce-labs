<?php
require_once BASE_PATH . '/core/registration_validation.php';
$pageTitle = 'Register';
$old = $_SESSION['registration_old'] ?? [];
$errors = $_SESSION['registration_errors'] ?? [];
unset($_SESSION['registration_old'], $_SESSION['registration_errors']);
require BASE_PATH . '/views/layout/header.php';
?>
<section class="form-panel registration-panel">
<p class="eyebrow">JOIN THE COMMUNITY</p><h1>Create an account</h1>
<p class="muted">Thoughtful finds, delivered to your door. All fields are required.</p>
<form method="POST" action="<?= BASE_URL ?>/actions/register.php" id="register-form" data-weak-passwords="<?= clean(json_encode(registration_weak_passwords())) ?>">
<input type="hidden" name="csrf_token" value="<?= clean(csrf_token()) ?>">
<?php foreach (['customer_name'=>['Full name','text',100,'name'], 'customer_email'=>['Email','email',50,'email']] as $field=>$settings): ?>
<label for="<?= $field ?>"><?= $settings[0] ?></label>
<input id="<?= $field ?>" type="<?= $settings[1] ?>" name="<?= $field ?>" required maxlength="<?= $settings[2] ?>" autocomplete="<?= $settings[3] ?>" value="<?= clean($old[$field] ?? '') ?>" aria-describedby="<?= $field ?>-error">
<small class="field-error" id="<?= $field ?>-error" aria-live="polite"><?= clean($errors[$field] ?? '') ?></small>
<?php endforeach; ?>
<label for="customer_pass">Password</label>
<div class="password-control"><input id="customer_pass" type="password" name="customer_pass" required minlength="12" maxlength="72" autocomplete="new-password" aria-describedby="password-help customer_pass-error"><button type="button" class="password-toggle" data-password-toggle="customer_pass" aria-controls="customer_pass" aria-pressed="false">Show password</button></div>
<small class="muted" id="password-help">Use 12 or more characters (maximum 72 bytes), uppercase and lowercase letters, numbers and symbols. Avoid common passwords.</small>
<meter id="password-strength" min="0" max="4" value="0" aria-label="Password strength"></meter><small id="password-strength-label" aria-live="polite">Enter a password</small>
<small class="field-error" id="customer_pass-error" aria-live="polite"><?= clean($errors['customer_pass'] ?? '') ?></small>
<label for="confirm_password">Confirm password</label>
<div class="password-control"><input id="confirm_password" type="password" name="confirm_password" required minlength="12" maxlength="72" autocomplete="new-password" aria-describedby="confirm_password-error"><button type="button" class="password-toggle" data-password-toggle="confirm_password" aria-controls="confirm_password" aria-pressed="false">Show password</button></div>
<small class="field-error" id="confirm_password-error" aria-live="polite"><?= clean($errors['confirm_password'] ?? '') ?></small>
<?php foreach (['customer_country'=>['Country','text',30,'country-name'], 'customer_city'=>['City','text',30,'address-level2'], 'customer_contact'=>['Contact number','tel',15,'tel']] as $field=>$settings): ?>
<label for="<?= $field ?>"><?= $settings[0] ?></label>
<?php if ($field === 'customer_country'): ?>
<select id="customer_country" name="customer_country" required autocomplete="country-name" aria-describedby="customer_country-error">
<option value="">Choose your country</option>
<?php foreach (['Ghana','Nigeria','Togo','Benin','Burkina Faso',"Cote d'Ivoire",'Kenya','South Africa','United Kingdom','United States','Canada','Other'] as $country): ?>
<option value="<?= clean($country) ?>" <?= ($old['customer_country'] ?? '') === $country ? 'selected' : '' ?>><?= clean($country) ?></option>
<?php endforeach; ?></select>
<?php else: ?>
<input id="<?= $field ?>" type="<?= $settings[1] ?>" name="<?= $field ?>" required maxlength="<?= $settings[2] ?>" autocomplete="<?= $settings[3] ?>" value="<?= clean($old[$field] ?? '') ?>" aria-describedby="<?= $field ?>-error">
<?php endif; ?>
<small class="field-error" id="<?= $field ?>-error" aria-live="polite"><?= clean($errors[$field] ?? '') ?></small>
<?php endforeach; ?>
<button class="button" type="submit">Create account</button></form><p>Already have an account? <a class="text-link" href="<?= BASE_URL ?>/index.php?page=login">Log in</a></p></section>
<?php require BASE_PATH . '/views/layout/footer.php'; ?>
