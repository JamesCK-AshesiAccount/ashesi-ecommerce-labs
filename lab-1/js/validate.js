document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('register-form');
  if (!form) return;
  // Custom messages replace native bubbles; the PHP policy remains authoritative.
  form.noValidate = true;
  const weak = JSON.parse(form.dataset.weakPasswords);
  const bytes = value => new TextEncoder().encode(value).length;
  const passwordError = value => {
    if ([...value].length < 12 || bytes(value) > 72) return 'Use at least 12 characters and no more than 72 bytes.';
    if (!/[A-Z]/.test(value) || !/[a-z]/.test(value) || !/[0-9]/.test(value) || !/[^a-zA-Z0-9\s]/u.test(value)) return 'Include uppercase and lowercase letters, a number, and a symbol.';
    if (weak.includes(value.toLowerCase().replace(/[^a-z0-9]/g, ''))) return 'This password is too common. Choose a less predictable password.';
    return '';
  };
  const show = (field, message) => {
    document.getElementById(`${field}-error`).textContent = message;
    form.elements[field].setAttribute('aria-invalid', message ? 'true' : 'false');
    return !message;
  };
  const validate = field => {
    const value = form.elements[field].value;
    let message = '';
    if (field === 'customer_pass') message = passwordError(value);
    else if (field === 'confirm_password') message = value && value === form.elements.customer_pass.value ? '' : 'Passwords do not match.';
    else {
      const limits = {customer_name:[2,100],customer_email:[1,50],customer_country:[1,30],customer_city:[1,30],customer_contact:[7,15]};
      const [min,max] = limits[field];
      if (bytes(value.trim()) < min || bytes(value.trim()) > max) message = `Enter ${min}–${max} bytes for this field.`;
      if (field === 'customer_email' && (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value.trim()) || form.elements[field].validity.typeMismatch)) message = 'Enter a valid email address.';
      if (field === 'customer_contact' && !/^[0-9+\-\s]{7,15}$/.test(value.trim())) message = 'Use 7–15 characters: digits, +, spaces or hyphens.';
    }
    return show(field, message);
  };
  const fields = ['customer_name','customer_email','customer_pass','confirm_password','customer_country','customer_city','customer_contact'];
  fields.forEach(field => form.elements[field].addEventListener('blur', () => validate(field)));
  form.querySelectorAll('[data-password-toggle]').forEach(button => button.addEventListener('click', () => {
    const input = form.elements[button.dataset.passwordToggle];
    const visible = input.type === 'password';
    input.type = visible ? 'text' : 'password';
    button.textContent = visible ? 'Hide password' : 'Show password';
    button.setAttribute('aria-pressed', String(visible));
  }));
  form.elements.customer_pass.addEventListener('input', () => {
    const value = form.elements.customer_pass.value;
    const accepted = !passwordError(value);
    const score = !value ? 0 : !accepted ? 1 : [...value].length >= 20 ? 4 : [...value].length >= 16 ? 3 : 2;
    document.getElementById('password-strength').value = score;
    document.getElementById('password-strength-label').textContent = ['Enter a password','Needs improvement','Meets requirements','Strong','Very strong'][score];
    validate('customer_pass');
    if (form.elements.confirm_password.value) validate('confirm_password');
  });
  form.elements.confirm_password.addEventListener('input', () => validate('confirm_password'));
  form.addEventListener('submit', event => {
    const results = fields.map(validate);
    if (results.includes(false)) {
      event.preventDefault();
      form.elements[fields[results.indexOf(false)]].focus();
    }
  });
});
