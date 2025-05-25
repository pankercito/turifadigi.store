<?php require_once 'views/layouts/header.php'; ?>
<!-- Agregar Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<!-- Estilos personalizados para los iconos del signup -->
<style>
  .icon-signup {
    padding-right: 8px;
    opacity: 0.8;
    font-size: 1.1em;
    vertical-align: middle;
    transition: color 0.2s;
  }

  .icon-signup.nombre {
    color: #2962ff;
  }

  .icon-signup.apellido {
    color: #4173ff;
  }

  .icon-signup.cedula {
    color: #5a85ff;
  }

  .icon-signup.ubicacion {
    color: #7296ff;
  }

  .icon-signup.usuario {
    color: #8aa7ff;
  }

  .icon-signup.password {
    color: #a2b8ff;
  }

  .icon-signup.telefono {
    color: #baccff;
  }

  .icon-signup.correo {
    color: #d1deff;
  }

  /* Estilo de error personalizado para los inputs del signup */
  .input-error-signup {
    border: 2px solid #2962ff !important;
    background: rgba(41, 98, 255, 0.07) !important;
    box-shadow: 0 0 0 3px rgba(41, 98, 255, 0.12);
    transition: all 500ms ease;
  }

  /* Efecto hover/focus para los inputs del signup (diferente al error) */
  .input-hover-signup {
    transition: all 500ms ease;
  }

  .input-hover-signup:focus,
  .input-hover-signup:hover {
    border: 1.5px solid #90caf9;
    background: #f5f8ff;
    box-shadow: 0 0 0 2px rgba(41, 98, 255, 0.08);
    outline: none;
  }

  input#password_signup {
    height: 60px;
    width: 100%;
    border: 1px solid #2962ff;
    background-color: var(--zefxa-white);
    padding-left: 30px;
    padding-right: 30px;
    outline: none;
    font-size: 14px;
    color: var(--zefxa-gray);
    display: block;
    font-weight: 500;
    line-height: 60px;
    border-radius: 10px;
  }

  .password-toggle {
    position: absolute;
    right: 10px;
    top: 28%;
    cursor: pointer;
  }
</style>
<!-- Agregar JS de Toastify -->
<!-- Agregar JS personalizado -->
<section class="contact-two">
  <div class="contact-two__img-1 wow fadeInLeft" data-wow-delay="300ms">
    <img src="assets/images/resources/contact-two-img-1.png" alt="" class="float-bob-x">
  </div>

  <div class="container">
    <div class="row">
      <div class="col-xl-8">
        <div class="contact-two__left">
          <div class="section-title text-left">
            <div class="section-title__tagline-box">
              <div class="section-title__tagline-shape">

              </div>

            </div>
            <h2 class="section-title__title" data-i18n="register_account">Registrar Cuenta</h2>
          </div>
          <form id="form-registro" class="contact-two__form" action="registro_usuario" method="post" autocomplete=off>
            <div class="row">
              <div class="col-xl-6 col-lg-6">
                <label for="nombre_signup" class="form-label" style="font-weight: bold;">
                  <i class="bi bi-person-fill icon-signup nombre"></i> <span data-i18n="first_name">Nombre</span> *
                </label>
                <div class="contact-two__input-box">
                  <input type="text" name="nombre" id="nombre_signup" data-i18n-placeholder="enter_first_name" placeholder="Ingrese su nombre" class="input-hover-signup">
                  <span id="nombre_signup_msg" style="display:block;font-size:0.95em;color:#e53935;margin-top:2px;"></span>
                </div>
              </div>
              <div class="col-xl-6 col-lg-6">
                <label for="apellido_signup" class="form-label" style="font-weight: bold;">
                  <i class="bi bi-person-badge-fill icon-signup apellido"></i> <span data-i18n="last_name">Apellido</span> *
                </label>
                <div class="contact-two__input-box">
                  <input type="text" name="apellido" id="apellido_signup" data-i18n-placeholder="enter_last_name" placeholder="Ingrese su apellido" class="input-hover-signup">
                  <span id="apellido_signup_msg" style="display:block;font-size:0.95em;color:#e53935;margin-top:2px;"></span>
                </div>
              </div>
              <div class="col-xl-6 col-lg-6">
                <label for="ubicacion_signup" class="form-label" style="font-weight: bold;">
                  <i class="bi bi-geo-alt-fill icon-signup ubicacion"></i> <span data-i18n="location">Ubicación</span> *
                </label>
                <div class="contact-two__input-box">
                  <input type="text" name="ubicacion" id="ubicacion_signup" data-i18n-placeholder="enter_location" placeholder="E.J: País, estado, dirección" class="input-hover-signup">
                  <span id="ubicacion_signup_msg" style="display:block;font-size:0.95em;color:#e53935;margin-top:2px;"></span>
                </div>
              </div>
              <div class="col-xl-6 col-lg-6">
                <label for="usuario_signup" class="form-label" style="font-weight: bold;">
                  <i class="bi bi-person-circle icon-signup usuario"></i> <span data-i18n="username">Nombre de usuario</span> *
                </label>
                <div class="contact-two__input-box">
                  <input type="text" name="usuario" id="usuario_signup" data-i18n-placeholder="enter_username" placeholder="Cree un nombre de usuario" class="input-hover-signup">
                  <span id="usuario_signup_msg" style="display:block;font-size:0.95em;color:#e53935;margin-top:2px;"></span>
                </div>
              </div>
              <div class="col-xl-6 col-lg-6">
                <label for="password_signup" class="form-label" style="font-weight: bold;">
                  <i class="bi bi-lock-fill icon-signup password"></i> <span data-i18n="password">Contraseña</span> *
                </label>
                <div class="contact-two__input-box" style="position: relative;">
                  <input type="password" name="password" id="password_signup" data-i18n-placeholder="enter_password" placeholder="Cree una contraseña" class="input-hover-signup" autocomplete="off">
                  <span class="password-toggle" id="password-togle" onclick="togglePasswordVisibilitySignup()">
                    <i class="bi bi-eye-fill" id="icon-eye-signup"></i>
                  </span>
                  <span id="password_signup_msg" style="display:block;font-size:0.95em;color:#e53935;margin-top:2px;"></span>
                </div>
              </div>
              <div class="col-xl-6 col-lg-6">
                <label for="telefono_signup" class="form-label" style="font-weight: bold;">
                  <i class="bi bi-telephone-fill icon-signup telefono"></i> <span data-i18n="phone">Teléfono</span> *
                </label>
                <div class="contact-two__input-box">
                  <input type="text" name="telefono" id="telefono_signup" data-i18n-placeholder="enter_phone" placeholder="Ingrese su número de teléfono" class="input-hover-signup">
                  <span id="telefono_signup_msg" style="display:block;font-size:0.95em;color:#e53935;margin-top:2px;"></span>
                </div>
              </div>
              <div class="col-xl-12 text-center">
                <div class="contact-two__btn-box">
                  <button type="submit" class="thm-btn contact-two__btn" id="buttonForm" data-i18n="register" disabled>Registrarme</button>
                </div>
              </div>
            </div>
          </form>

          <div class="result"></div>

          <a href="/login">
            <p class="contact-two__left-text" style="margin-top: 20px;" data-i18n="already_have_account">¿Ya tiene una cuenta?</p>
          </a>

        </div>
      </div>
    </div>
  </div>
</section>
<style>
  button#buttonForm {
    display: inline;
  }
</style>
<script>
  // Eliminar todas las validaciones de campos y reglas
  // Solo dejar el envío simple del formulario

  const form = document.getElementById('form-registro');
  let isSubmitting = false;
  let boton = document.getElementById('buttonForm');

  form.addEventListener('submit', async function(event) {
    event.preventDefault();
    console.log('Formulario enviado (sin validaciones)');
    if (isSubmitting) return;
    isSubmitting = true;
    const submitButton = this.querySelector('button[type="submit"]');
    submitButton.disabled = true;

    try {
      boton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
      boton.disabled = true;

      const formData = new URLSearchParams(new FormData(this));
      console.log('Datos a enviar:', formData.toString());

      const response = await fetch('/registro_usuario', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8',
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          'X-Language': localStorage.getItem('language') || 'es'
        },
        body: formData.toString(),
        credentials: 'same-origin',
        mode: 'same-origin'
      });

      /* const response = await fetch('/registro_usuario', { */
      /*   method: 'POST', */
      /*   headers: { */
      /*     'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8', */
      /*     'Accept': 'application/json', */
      /*     'X-Requested-With': 'XMLHttpRequest' */
      /*   }, */
      /*   body: formData.toString(), */
      /*   credentials: 'same-origin', */
      /*   mode: 'same-origin' */
      /* }); */

      console.log('Respuesta recibida:', response);
      const data = await response.json();
      console.log('JSON recibido:', data);

      if (!response.ok) throw data;

      showToast('success', 'Éxito', data.message);

      Array.from(form.getElementsByTagName('input')).forEach(input => input.disabled = true);
      console.log('Redirigiendo a /login en 2 segundos...');
      setTimeout(() => {
        console.log('Redirigiendo ahora a /login');
        window.location.href = '/login';
      }, 2000);

    } catch (error) {
      console.error('Error en el catch:', error);
      showToast('error', 'Error', error.message || 'Hubo un error al procesar la solicitud');
      boton.innerHTML = 'Registrarme';
      boton.disabled = false;
    } finally {
      isSubmitting = false;
      submitButton.disabled = false;
      boton.innerHTML = 'Registrarme';
      boton.disabled = false;
      console.log('Finalizó el submit');
    }
  });

  function showToast(type, title, message) {
    switch (type) {
      case 'success':
        ToastPersonalizado.exito(title, message, 5000);
        break;
      case 'error':
        ToastPersonalizado.error(title, message, 5000);
        break;
      case 'warning':
        ToastPersonalizado.advertencia(title, message, 5000);
        break;
      case 'info':
        ToastPersonalizado.info(title, message, 5000);
        break;
    }
  }

  // Objeto para almacenar el estado de validación de cada campo
  const estadoValidacionCampos = {
    nombre: false,
    apellido: false,
    ubicacion: false,
    usuario: false,
    password: false,
    telefono: false,
  };

  // Función para verificar el estado general de validación
  function verificarEstadoValidacionGeneral() {
    const todosValidos = Object.values(estadoValidacionCampos).every(val => val === true);
    console.log('Estado de validación de campos:', estadoValidacionCampos);
    console.log(todosValidos ? '? Todos los campos son válidos' : '? Algunos campos no son válidos');
    if (todosValidos) {
      boton.disabled = false;
    }
    return todosValidos;
  }

  const inputNombre = document.getElementById('nombre_signup');
  const spanMsg = document.getElementById('nombre_signup_msg');

  inputNombre.addEventListener('keyup', function() {
    const valor = this.value;
    const esValido = /^[A-Za-zÁÉÍÓÚáéíóúÑñ ]{3,}$/.test(valor);
    estadoValidacionCampos.nombre = esValido;

    if (valor.length === 0) {
      spanMsg.textContent = '';
      spanMsg.removeAttribute('data-msg-key');
      spanMsg.style.color = '#e53935';
    } else if (!esValido) {
      spanMsg.textContent = i18n.t('name_validation');
      spanMsg.setAttribute('data-msg-key', 'name_validation');
      spanMsg.style.color = '#e53935';
    } else {
      spanMsg.textContent = i18n.t('valid_name');
      spanMsg.setAttribute('data-msg-key', 'valid_name');
      spanMsg.style.color = '#43a047';
    }
    verificarEstadoValidacionGeneral();
  });

  const inputApellido = document.getElementById('apellido_signup');
  const spanMsgApellido = document.getElementById('apellido_signup_msg');

  inputApellido.addEventListener('keyup', function() {
    const valor = this.value;
    const esValido = /^[A-Za-zÁÉÍÓÚáéíóúÑñ ]{3,}$/.test(valor);
    estadoValidacionCampos.apellido = esValido;

    if (valor.length === 0) {
      spanMsgApellido.textContent = '';
      spanMsgApellido.removeAttribute('data-msg-key');
      spanMsgApellido.style.color = '#e53935';
    } else if (!esValido) {
      spanMsgApellido.textContent = i18n.t('lastname_validation');
      spanMsgApellido.setAttribute('data-msg-key', 'lastname_validation');
      spanMsgApellido.style.color = '#e53935';
    } else {
      spanMsgApellido.textContent = i18n.t('valid_lastname');
      spanMsgApellido.setAttribute('data-msg-key', 'valid_lastname');
      spanMsgApellido.style.color = '#43a047';
    }
    verificarEstadoValidacionGeneral();
  });

  const inputUbicacion = document.getElementById('ubicacion_signup');
  const spanMsgUbicacion = document.getElementById('ubicacion_signup_msg');

  inputUbicacion.addEventListener('keyup', function() {
    const valor = this.value;
    const esValido = /^[A-Za-zÁÉÍÓÚáéíóúÑñ0-9 ,.]{3,}$/.test(valor);
    estadoValidacionCampos.ubicacion = esValido;

    if (valor.length === 0) {
      spanMsgUbicacion.textContent = '';
      spanMsgUbicacion.removeAttribute('data-msg-key');
      spanMsgUbicacion.style.color = '#e53935';
    } else if (!esValido) {
      spanMsgUbicacion.textContent = i18n.t('location_validation');
      spanMsgUbicacion.setAttribute('data-msg-key', 'location_validation');
      spanMsgUbicacion.style.color = '#e53935';
    } else {
      spanMsgUbicacion.textContent = i18n.t('valid_location');
      spanMsgUbicacion.setAttribute('data-msg-key', 'valid_location');
      spanMsgUbicacion.style.color = '#43a047';
    }
    verificarEstadoValidacionGeneral();
  });

  const inputUsuario = document.getElementById('usuario_signup');
  const spanMsgUsuario = document.getElementById('usuario_signup_msg');

  inputUsuario.addEventListener('keyup', function() {
    const valor = this.value;
    const esValido = /^[A-Za-zÁÉÍÓÚáéíóúÑñ0-9_-]{3,}$/.test(valor);
    estadoValidacionCampos.usuario = esValido;

    if (valor.length === 0) {
      spanMsgUsuario.textContent = '';
      spanMsgUsuario.removeAttribute('data-msg-key');
      spanMsgUsuario.style.color = '#e53935';
    } else if (!esValido) {
      spanMsgUsuario.textContent = i18n.t('username_validation');
      spanMsgUsuario.setAttribute('data-msg-key', 'username_validation');
      spanMsgUsuario.style.color = '#e53935';
    } else {
      spanMsgUsuario.textContent = i18n.t('valid_username');
      spanMsgUsuario.setAttribute('data-msg-key', 'valid_username');
      spanMsgUsuario.style.color = '#43a047';
    }
    verificarEstadoValidacionGeneral();
  });

  const inputPassword = document.getElementById('password_signup');
  const spanMsgPassword = document.getElementById('password_signup_msg');
  const eyefcor = document.getElementById('password-togle');
  inputPassword.addEventListener('keyup', function() {
    const valor = this.value;
    const longitudValida = valor.length >= 8;
    const esValido = longitudValida;
    estadoValidacionCampos.password = esValido;

    if (valor.length > 0) {
      eyefcor.classList.add('password-toggle-correction');
    } else {
      eyefcor.classList.remove('password-toggle-correction');
    }

    if (valor.length === 0) {
      spanMsgPassword.textContent = '';
      spanMsgPassword.removeAttribute('data-msg-key');
      spanMsgPassword.style.color = '#e53935';
    } else if (!esValido) {
      spanMsgPassword.textContent = i18n.t('password_validation');
      spanMsgPassword.setAttribute('data-msg-key', 'password_validation');
      spanMsgPassword.style.color = '#e53935';
    } else {
      spanMsgPassword.textContent = i18n.t('valid_password');
      spanMsgPassword.setAttribute('data-msg-key', 'valid_password');
      spanMsgPassword.style.color = '#43a047';
    }
    verificarEstadoValidacionGeneral();
  });

  const inputTelefono = document.getElementById('telefono_signup');
  const spanMsgTelefono = document.getElementById('telefono_signup_msg');

  inputTelefono.addEventListener('keyup', function() {
    const valor = this.value;
    const esValido = /^\d{10,}$/.test(valor);
    estadoValidacionCampos.telefono = esValido;

    if (valor.length === 0) {
      spanMsgTelefono.textContent = '';
      spanMsgTelefono.removeAttribute('data-msg-key');
      spanMsgTelefono.style.color = '#e53935';
    } else if (!esValido) {
      spanMsgTelefono.textContent = i18n.t('phone_validation');
      spanMsgTelefono.setAttribute('data-msg-key', 'phone_validation');
      spanMsgTelefono.style.color = '#e53935';
    } else {
      spanMsgTelefono.textContent = i18n.t('valid_phone');
      spanMsgTelefono.setAttribute('data-msg-key', 'valid_phone');
      spanMsgTelefono.style.color = '#43a047';
    }
    verificarEstadoValidacionGeneral();
  });

  // Función para alternar visibilidad de la contraseña en el signup
  function togglePasswordVisibilitySignup() {
    const passwordInput = document.getElementById('password_signup');
    const icon = document.getElementById('icon-eye-signup');
    if (passwordInput.type === 'password') {
      passwordInput.type = 'text';
      icon.classList.remove('bi-eye-fill');
      icon.classList.add('bi-eye-slash-fill');
    } else {
      passwordInput.type = 'password';
      icon.classList.remove('bi-eye-slash-fill');
      icon.classList.add('bi-eye-fill');
    }
  }

  // Actualizar mensajes de validación al cambiar el idioma
  document.addEventListener('DOMContentLoaded', () => {
    if (typeof i18n !== 'undefined') {
      const originalChangeLang = i18n.changeLang.bind(i18n);
      i18n.changeLang = async function(lang) {
        await originalChangeLang(lang);
        document.querySelectorAll('span[data-msg-key]').forEach(span => {
          const key = span.getAttribute('data-msg-key');
          if (key) {
            span.textContent = i18n.t(key);
          }
        });
      };
    } else {
      console.error('i18n no está definido. Asegúrate de que el archivo i18n.js esté cargado correctamente.');
    }
  });
</script>

<?php if (!empty($_SESSION['mensaje'])) {
  echo $_SESSION['mensaje'];
  unset($_SESSION['mensaje']);
} ?>

<style>
  .password-toggle-correction {
    position: absolute;
    right: 10px;
    top: 19%;
    cursor: pointer;
  }
</style>


<?php require_once 'views/layouts/footer.php'; ?>