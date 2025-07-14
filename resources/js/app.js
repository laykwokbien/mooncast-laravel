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

const detail = document.getElementById('detail'),
closebtn = document.getElementById('close-detail');

const searchinput = document.getElementById('search-page'),
searchbtn = document.getElementById('search-btn'),
editbtn = document.getElementById('editBtn'),
passwordCheck = document.getElementsByClassName('password_checkbox');

const sidebar = document.querySelector('header'),
section = document.querySelector('section'),
navcontrol = document.getElementById('collapse_expandbtn'),
navtext = document.getElementsByClassName('nav-text'),
icon = document.getElementById('expand_collapseIcon');

const transition = '0.4s ease-in-out';

const dropdownbutton = document.getElementById('dropdown-button');

const dropdown = document.querySelectorAll('div.dropdown')

const file = document.getElementById('file');

const select_all = document.getElementById('all_checkbox');

if (select_all){
  const child_check = document.querySelectorAll('input.child_check');
  select_all.addEventListener('change', function () {
    if (this.checked){
      child_check.forEach(element => {
        element.checked = this.checked;
      });
    } else {
      child_check.forEach(element => {
        element.checked = this.checked;
      });
    }
  });
  child_check.forEach(element => {
    element.addEventListener('change', function (){
      let checks = [];
      child_check.forEach(element => {
        checks.push(element.checked);
      })

      let all_check = checks.every(value => value === true)

      if (all_check){
        select_all.checked = true
      }

      if (this.checked == false){
        select_all.checked = false
      }
    });
  });
}

if (file) {
  const filename = document.getElementById('filename');
  file.addEventListener('change', function () {
    filename.textContent = this.files[0]?.name || 'Belum ada File'
  });
}

if (detail && closebtn){
  closebtn.addEventListener('click', () => {
    detail.remove();
  });
}

if (passwordCheck){
  const passwordInput = document.getElementsByClassName('password_input');
  
  for (let i = 0; i < passwordCheck.length; i++){
    passwordCheck[i].addEventListener('click', () => {
      if (passwordInput[i].getAttribute('type') ==  'password'){
        passwordInput[i].setAttribute('type', 'text');
      } else {
        passwordInput[i].setAttribute('type', 'password');
      }
    });
  }
}

if (editbtn){
  const input = document.querySelectorAll('input'),
  button = document.getElementById('submitBtn');

  for (let i = 0; i < input.length; i++){
          input[i].readOnly = true
  }

  editbtn.addEventListener('click', () => {
    if (editbtn.innerText == 'Edit'){
      editbtn.innerText = 'Cancel';
    } else{
      editbtn.innerText = 'Edit';
    }

    for (let i = 0; i < input.length; i++){
      if (input[i].type != 'checkbox'){
        if (input[i].readOnly == true){
          input[i].readOnly = false;
          input[i].style.color = '#fff';
        } else {
          input[i].readOnly = true
          input[i].style.color = '#aaa';
        }
      }
    }
    
    if (button.disabled === true){
      button.disabled = false;
    } else {
      button.disabled = true
    }

    localStorage.setItem('enable', !button.disabled);
  });

  window.addEventListener('DOMContentLoaded', () => {
    const enable = localStorage.getItem('enable');

    if (enable){
      button.disabled = true
      for (let i = 0; i < input.length; i++){
        if (input[i].type != 'checkbox'){
          input[i].readOnly = true;
          input[i].style.color = '#aaa';
        }
      }
    } else {
      button.disabled = false
      for (let i = 0; i < input.length; i++){
        if (input[i].type != 'checkbox'){
          input[i].readOnly = false;
          input[i].style.color = '#fff';
        }
      }
    }
  });
}

if (searchinput && searchbtn) {
  searchbtn.addEventListener('click', () => {
    let page = searchinput.value;
    let url = searchbtn.getAttribute('data-url');
    window.location.replace(`${url}?page=${page}`);
  })
}

if (sidebar){
  navcontrol.addEventListener('click', () => {
    section.classList.toggle('expand-mode');
    sidebar.classList.toggle('expand');

    section.style.transition = transition
    sidebar.style.transition = transition
    
    if (navtext[0].classList.contains('hidden')){
      const textinterval = setInterval(() => {
        for (let i = 0; i < navtext.length; i++){
          navtext[i].classList.toggle('hidden');
        }
        clearInterval(textinterval)
      }, 350);
    } else {
      for (let i = 0; i < navtext.length; i++){
        navtext[i].classList.toggle('hidden');
      }
    }
    icon.classList.toggle('bi-chevron-double-left')
    icon.classList.toggle('bi-chevron-double-right')
    let isExpanded = sidebar.classList.contains('expand');
    localStorage.setItem('navbarExpanded', isExpanded);
  });
}

window.addEventListener('DOMContentLoaded', () => {
  const expand = localStorage.getItem('navbarExpanded');
  if (expand == 'true'){
    section.classList.add('expand-mode');
    sidebar.classList.add('expand');
    for (let i = 0; i < navtext.length; i++){
      navtext[i].classList.remove('hidden');
    }
    icon.classList.add('bi-chevron-double-left')
    icon.classList.remove('bi-chevron-double-right')
  } else {
    section.classList.remove('expand-mode');
    sidebar.classList.remove('expand');
    for (let i = 0; i < navtext.length; i++){
      navtext[i].classList.add('hidden');
    }
    icon.classList.remove('bi-chevron-double-left')
    icon.classList.add('bi-chevron-double-right')
  }
});

if (dropdownbutton){
  const dropdownbody = document.getElementById('dropdown-body'),
  dropdownhandler = document.getElementById('dropdown-handler');
  dropdownhandler.addEventListener('click',() => {
    dropdownbody.classList.toggle('close-dropdown-body');  
  })
}

for (let i = 0; i < dropdown.length; i++){
  const dropdownbtn = dropdown[i].querySelector('#dropdown-btn'),
  allmenu = document.querySelectorAll('#dropdown-menu'),
  allicon = document.querySelectorAll('.dropdown-btn .nav-text > i');
  dropdownbtn.addEventListener('click', () => {
    const menu = dropdown[i].querySelector('#dropdown-menu'),
    icon = dropdownbtn.querySelector('.nav-text > i');
    if (menu.classList.contains('close-dropdown')){
      allmenu.forEach(menu => {
        menu.classList.add('close-dropdown')
      });
      allicon.forEach(icon => {
        icon.classList.add('bi-chevron-right')
        icon.classList.remove('bi-chevron-left')
      })
    }
    if (icon.classList.contains('bi-chevron-right')){
      icon.classList.remove('bi-chevron-right')
      icon.classList.add('bi-chevron-left')
    } else {
      icon.classList.add('bi-chevron-right')
      icon.classList.remove('bi-chevron-left')
    }
    menu.classList.toggle('close-dropdown');
  });
}

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