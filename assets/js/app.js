const formulario = document.querySelector('#formMascota');
if (formulario) {
  formulario.addEventListener('submit', (evento) => {
    let valido = true;
    formulario.querySelectorAll('[required]').forEach((campo) => {
      const error = campo.parentElement.querySelector('small');
      campo.classList.remove('invalido');
      error.textContent = '';
      if (!campo.checkValidity()) {
        valido = false;
        campo.classList.add('invalido');
        error.textContent = campo.name === 'peso'
          ? 'El peso debe ser numérico y mayor que cero.'
          : 'Revisa este campo obligatorio.';
      }
    });
    if (!valido) {
      evento.preventDefault();
      formulario.querySelector('.invalido')?.focus();
    }
  });
}
document.querySelector('.alerta button')?.addEventListener('click', (e) => e.currentTarget.parentElement.remove());

document.querySelectorAll('.menu-principal').forEach((boton) => {
  boton.addEventListener('click', () => {
    const submenu = document.getElementById(boton.getAttribute('aria-controls'));
    const abierto = boton.getAttribute('aria-expanded') === 'true';
    boton.setAttribute('aria-expanded', String(!abierto));
    submenu?.classList.toggle('abierto', !abierto);
  });
});

function abrirMenu() {
  document.querySelector('#sidebar')?.classList.add('mostrar');
  document.querySelector('#overlay')?.classList.add('mostrar');
}

function cerrarMenu() {
  document.querySelector('#sidebar')?.classList.remove('mostrar');
  document.querySelector('#overlay')?.classList.remove('mostrar');
}

document.querySelectorAll('.submenu a').forEach((enlace) => enlace.addEventListener('click', () => {
  if (window.innerWidth <= 992) cerrarMenu();
}));
