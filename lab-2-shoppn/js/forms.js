document.addEventListener('DOMContentLoaded', () => {
  const forms = document.querySelectorAll('.admin-form, #login-form');
  forms.forEach(form => {
    const message = document.createElement('p');
    message.className = 'field-error';
    message.setAttribute('role', 'alert');
    form.appendChild(message);
    const check = () => {
      let error = '';
      for (const input of form.querySelectorAll('input, textarea, select')) {
        input.setCustomValidity('');
        if (input.type === 'hidden') continue;
        if (input.required && !input.value.trim()) error = 'Please complete all required fields.';
        if (input.type === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value.trim())) error = 'Enter a valid email address.';
        if (input.maxLength > 0 && new TextEncoder().encode(input.value).length > input.maxLength) error = `Keep this field within ${input.maxLength} bytes.`;
        if (input.name === 'product_price' && (!input.value.trim() || !Number.isFinite(Number(input.value)) || Number(input.value) < 0)) error = 'Enter a valid price of zero or more.';
        if (input.type === 'file' && input.files.length) {
          const file = input.files[0];
          if (!['image/jpeg','image/png','image/gif','image/webp'].includes(file.type)) error = 'Choose a JPG, PNG, GIF or WEBP image.';
          if (file.size > 5 * 1024 * 1024) error = 'Image must be 5 MB or smaller.';
        }
        if (error) { input.setCustomValidity(error); break; }
      }
      message.textContent = error;
      return !error;
    };
    form.addEventListener('input', check);
    form.addEventListener('change', check);
    form.addEventListener('submit', event => {
      if (!check() || !form.checkValidity()) { event.preventDefault(); form.reportValidity(); }
    });
  });
});
