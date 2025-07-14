import './bootstrap';

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}

const password = document.querySelector('input[name=password]'),
icon = document.querySelector('i');

icon.addEventListener('click', () => {
    if (icon.classList.contains('bi-eye-fill')){
        icon.classList.remove('bi-eye-fill')
        password.type = 'text'
        icon.classList.add('bi-eye-slash-fill')
    } else {
        icon.classList.add('bi-eye-fill')
        password.type = 'password'
        icon.classList.remove('bi-eye-slash-fill')
    }
})

document.querySelectorAll('input').forEach(input => {
    const id = input.getAttribute('id');
    const label = document.querySelector(`label[for="${id}"]`);
    
    if (!label) return;

    input.addEventListener('input', () => {
        if (input.value.trim() !== '') {
            label.classList.add('valid');
        } else {
            label.classList.remove('valid');
        }
    });
});


const check = setInterval(() => {
  const top_popup = document.getElementById('top-popup');

  if (top_popup){
    top_popup.classList.add('close');
    top_popup.innerText = '';
    
    clearInterval(check);
  }
}, 2500);

const remove = setInterval(() => {
  const top_popup = document.getElementById('top-popup');

  top_popup.remove();
  clearInterval(remove);
}, 5000);