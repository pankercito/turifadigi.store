<?php require_once 'views/layouts/header.php'; ?>
<!-- Agregar CSS de Toastify y Bootstrap Icons si no están ya en el header -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.css">
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

  input#re_password_signup {
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
            <h2 class="section-title__title" data-i18n="rest_password">Restablecer Contraseña</h2>
          </div>
          <form id="form-password" class="contact-two__form" action="registro_usuario" method="post" autocomplete=off>
            <div class="row">
              <div class="col-xl-12 col-lg-12">
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
                  <i class="bi bi-lock-fill icon-signup password"></i> <span data-i18n="enter_new_password">Contraseña</span> *
                </label>
                <div class="contact-two__input-box" style="position: relative;">
                  <input type="password" name="password" id="password_signup" data-i18n-placeholder="enter_new_password" placeholder="Cree una nueva contraseña" class="input-hover-signup" autocomplete="off">
                  <span class="password-toggle" id="password-togle" onclick="togglePasswordVisibilityReset('password_signup', 'icon-eye-signup')">
                    <i class="bi bi-eye-fill" id="icon-eye-signup"></i>
                  </span>
                  <span id="password_signup_msg" style="display:block;font-size:0.95em;color:#e53935;margin-top:2px;"></span>
                </div>
              </div>
              <div class="col-xl-6 col-lg-6">
                <label for="re_password_signup" class="form-label" style="font-weight: bold;">
                  <i class="bi bi-lock-fill icon-signup password"></i> <span data-i18n="enter_repeat_password">Contraseña</span> *
                </label>
                <div class="contact-two__input-box" style="position: relative;">
                  <input type="password" name="re_password" id="re_password_signup" data-i18n-placeholder="enter_repeat_password" placeholder="Repita la contraseña" class="input-hover-signup" autocomplete="off">
                  <span class="password-toggle" id="re_password-togle" onclick="togglePasswordVisibilityReset('re_password_signup', 'icon-eye-signup_2')">
                    <i class="bi bi-eye-fill" id="icon-eye-signup_2"></i>
                  </span>
                  <span id="re_password_signup_msg2"style="display:block;font-size:0.95em;color:#e53935;margin-top:2px;"></span>
                </div>
              </div>
              <div class="col-xl-12 text-center">
                <div class="contact-two__btn-box">
                  <button type="submit" class="thm-btn contact-two__btn" id="buttonForm" data-i18n="rest_password">Restablecer Contraseña</button>
                </div>
              </div>
            </div>
          </form>
          <div class="result"></div>
          <!-- Notificaciones Toast -->
          <div class="toast-container position-fixed bottom-0 end-0 p-3">
            <div id="notificationToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
              <div class="toast-header">
                <strong class="me-auto" id="toastTitle"></strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
              </div>
              <div class="toast-body" id="toastMessage"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  // Toggle de visibilidad de contraseña
  function togglePasswordVisibilityReset(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    if (input.type === 'password') {
      input.type = 'text';
      icon.classList.remove('bi-eye-fill');
      icon.classList.add('bi-eye-slash-fill');
    } else {
      input.type = 'password';
      icon.classList.remove('bi-eye-slash-fill');
      icon.classList.add('bi-eye-fill');
    }
  }
  // Validación en tiempo real para los campos
  const form = document.getElementById('form-password');
  const usuario = document.getElementById('usuario_signup');
  const password = document.getElementById('password_signup');
  const confirmPassword = document.getElementById('re_password_signup');
  const usuario_signup_msg = document.getElementById('usuario_signup_msg');
  const password_signup_msg = document.getElementById('password_signup_msg');
  const re_password_signup_msg = document.getElementById('re_password_signup_msg');
  const buttonForm = document.getElementById('buttonForm');

  usuario.addEventListener('keyup', function() {
    if (this.value.trim() === '') {
      usuario_signup_msg.textContent = 'Este campo es requerido.';
      this.classList.add('input-error-signup');
      buttonForm.disabled = true;
    } else {
      usuario_signup_msg.textContent = '';
      this.classList.remove('input-error-signup');
      buttonForm.disabled = false;
    }
  });
  password.addEventListener('keyup', function() {
    if (this.value.trim() === '') {
      password_signup_msg.textContent = 'Este campo es requerido.';
      this.classList.add('input-error-signup');
      buttonForm.disabled = true;
    } else {
      password_signup_msg.textContent = '';
      this.classList.remove('input-error-signup');
      buttonForm.disabled = false;
    }
  });

  confirmPassword.addEventListener('keyup', function() {
    if (this.value.trim() === '' || password.value !== this.value) {
      re_password_signup_msg.textContent = 'Las contraseñas no coinciden.';
      this.classList.add('input-error-signup');
      buttonForm.disabled = true;
    } else {
      re_password_signup_msg.textContent = '';
      this.classList.remove('input-error-signup');
      buttonForm.disabled = false;
    }
  });

  form.addEventListener('submit', function(event) {
    event.preventDefault();
    const usuarioValue = usuario.value.trim();
    const passwordValue = password.value.trim();
    const confirmPasswordValue = confirmPassword.value.trim();
    if (usuarioValue === '') {
      showToast('warning', 'Campo vacío', 'El campo usuario es requerido');
      usuario.classList.add('input-error-signup');
      usuario.focus();
      return;
    }
    if (passwordValue === '' || confirmPasswordValue === '') {
      showToast('warning', 'Campos vacíos', 'Ambos campos de contraseña son requeridos');
      if (passwordValue === '') {
        password.classList.add('input-error-signup');
        password.focus();
      } else {
        confirmPassword.classList.add('input-error-signup');
        confirmPassword.focus();
      }
      return;
    }
    if (passwordValue !== confirmPasswordValue) {
      showToast('warning', 'Contraseñas no coinciden', 'Las contraseñas ingresadas no coinciden');
      password.classList.add('input-error-signup');
      confirmPassword.classList.add('input-error-signup');
      password.focus();
      return;
    }

    fetch('api/get_user?usuario=' + usuarioValue)
      .then(response => response.json())
      .then(data => {
        if (data.success) {

          let html = `Usuario: ${usuarioValue} <br>Nombre: ${data.user.nombre + " "  + data.user.apellido} <br>Teléfono: ${data.user.telefono}`;

          Swal.fire({
            title: 'Confirmar Restablecimiento',
            html: html,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Restablecer Contraseña',
            cancelButtonText: 'Cancelar'
          }).then((result) => {
            if (result.isConfirmed) {
              let formData = new FormData(form);
              fetch('api/recovery_password', {
                  method: 'POST',
                  body: new URLSearchParams(formData)
                })
                .then(response => response.json())
                .then(data => {
                  if (data.success) {
                    showToast('success', 'Éxito', data.message);
                  } else {
                    showToast('error', 'Error', data.message);
                  }
                })
                .catch(error => {
                  showToast('error', 'Error', 'Hubo un error al procesar la solicitud');
                });
            }
          });
        } else {
          showToast('error', 'Error', 'Usuario no encontrado');
        }
      })
      .catch(error => {
        showToast('error', 'Error', 'Hubo un error:' + error.message);
      });
  });

  // Toast
  function showToast(type, title, message) {
    const toast = document.getElementById('notificationToast');
    const toastTitle = document.getElementById('toastTitle');
    const toastMessage = document.getElementById('toastMessage');
    const toastInstance = new bootstrap.Toast(toast, {
      autohide: true,
      delay: 5000
    });
    let headerClass = '';
    switch (type) {
      case 'success':
        headerClass = 'bg-success text-white';
        break;
      case 'error':
        headerClass = 'bg-danger text-white';
        break;
      case 'warning':
        headerClass = 'bg-warning text-white';
        break;
      default:
        headerClass = 'bg-info text-white';
    }
    toastTitle.textContent = title;
    toast.querySelector('.toast-header').className = `toast-header ${headerClass}`;
    toastMessage.textContent = message;
    toastInstance.show();
  }
</script>
<?php if (!empty($_SESSION['mensaje'])) {
  echo $_SESSION['mensaje'];
  unset($_SESSION['mensaje']);
} ?>
<?php require_once 'views/layouts/footer.php'; ?>