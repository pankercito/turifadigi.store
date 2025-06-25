<?php require_once 'views/layouts/header.php'; ?>

<section class="login-section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6 col-md-8">
        <div class="login-card">
          <div class="login-header text-center mb-4">
            <h2 class="login-title" data-i18n="login_title">Iniciar Sesión</h2>
          </div>

          <form id="form-login" class="login-form" method="post">
            <div class="form-group mb-3">
              <div class="input-group">
                <input
                  type="text"
                  name="usuario"
                  id="usuario"
                  class="form-control"
                  data-i18n-placeholder="username_placeholder"
                  placeholder="Nombre de usuario"
                  autocomplete="username">
              </div>
            </div>

            <div class="form-group mb-4">
              <div class="input-group">
                <input
                  type="password"
                  name="password"
                  id="password"
                  class="form-control"
                  data-i18n-placeholder="password_placeholder"
                  placeholder="Contraseña"
                  autocomplete="current-password">
                <span class="input-group-text password-toggle" onclick="togglePasswordVisibility()">
                  <i class="fas fa-eye"></i>
                </span>
              </div>
            </div>

            <div class="form-group text-center">
              <button type="submit" class="btn btn-primary w-100" id="btn-login" data-i18n="login_btn">
                Iniciar Sesión
              </button>
            </div>
          </form>

          <div class="text-center mt-4">
            <p class="register-link">
              <span data-i18n="no_account">¿No tienes cuenta?</span> <a href="/signup" data-i18n="register_here">Regístrate aquí</a>
            </p>
            <p class="forgot-password-link mt-2">
              <a class="href" onclick="enviarMensajeWhatsAppRestablecerContrasena()">¿Olvidaste tu contraseña?</a>
            </p>
          </div>

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

<style>
  /* Estilos principales */
  .login-section {
    min-height: 100vh;
    display: flex;
    align-items: center;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    padding: 2rem 0;
  }

  .login-card {
    background: rgba(255, 255, 255, 0.95);
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
  }

  .login-title {
    color: #2c3e50;
    font-weight: 600;
    margin-bottom: 1.5rem;
  }

  .form-control {
    height: 50px;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
    padding: 0 15px;
    font-size: 16px;
    transition: all 0.3s ease;
  }

  .form-control:focus {
    border-color: #4a90e2;
    box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.25);
  }

  .btn-primary {
    background-color: #4a90e2;
    border: none;
    padding: 12px;
    font-size: 16px;
    font-weight: 600;
    transition: all 0.3s ease;
  }

  .btn-primary:hover {
    background-color: #357abd;
    transform: translateY(-2px);
  }

  .password-toggle {
    cursor: pointer;
    background: transparent;
    border: 1px solid #e0e0e0;
    border-left: none;
    border-radius: 0 8px 8px 0;
  }

  .register-link a,
  .forgot-password-link a {
    color: #4a90e2;
    text-decoration: none;
    font-weight: 500;
  }

  .register-link a:hover,
  .forgot-password-link a:hover {
    color: #4a90e2;
    text-decoration: underline;
    cursor: pointer;
  }

  /* Responsive */
  @media (max-width: 768px) {
    .login-card {
      margin: 1rem;
      padding: 1.5rem;
    }
  }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
  function enviarMensajeWhatsAppRestablecerContrasena() {
    numeroTelefono = "584124578781"; // Reemplaza con el número de soporte,

    // Mensaje mejorado: más directo y con un toque de cordialidad
    const mensaje = encodeURIComponent("¡Hola! Necesito ayuda para restablecer mi contraseña en TurifaDigi. ¿Me podrían indicar los pasos a seguir? Gracias.");

    const enlaceWhatsApp = `https://wa.me/${numeroTelefono}?text=${mensaje}`;

    window.open(enlaceWhatsApp, '_blank');

    console.log("Intentando enviar mensaje de WhatsApp mejorado a:", numeroTelefono);
  }


  // Función para mostrar/ocultar contraseña
  function togglePasswordVisibility() {
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.querySelector('.password-toggle i');

    if (passwordInput.type === 'password') {
      passwordInput.type = 'text';
      eyeIcon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
      passwordInput.type = 'password';
      eyeIcon.classList.replace('fa-eye-slash', 'fa-eye');
    }
  }

  // Validación del formulario
  document.getElementById('form-login').addEventListener('submit', function(event) {
    event.preventDefault();

    let boton = document.getElementById('btn-login');
    const usuario = document.getElementById('usuario');
    const password = document.getElementById('password');
    let valid = true;











    if (usuario.value.trim() === '') {
      showToast('warning', i18n.t('empty_field'), i18n.t('username_required'));
      usuario.focus();
      valid = false;
    }

    if (password.value.trim() === '') {
      showToast('warning', i18n.t('empty_field'), i18n.t('password_required'));
      password.focus();
      valid = false;
    }










    if (valid) {
      if (boton.disabled) {
        return;
      }
      boton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';
      boton.disabled = true;

      const formData = new FormData(this);
      /* fetch('/api/login', { */
      /*     method: 'POST', */
      /*     body: new URLSearchParams(formData) */
      /*   }) */
      fetch('/api/login', {
          method: 'POST',
          body: new URLSearchParams(formData),
          headers: {
            'X-Language': localStorage.getItem('language') || 'es'
          }
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            showToast('success', 'Éxito', data.message);
            setTimeout(() => {
              window.location.href = '/sorteo';
            }, 2000);
          } else {
            showToast('error', 'Error', data.message);
            boton.innerHTML = 'Iniciar Sesión';
            boton.disabled = false;
          }
        })
        .catch(error => {
          showToast('error', 'Error', 'Hubo un problema al procesar la solicitud');
          boton.innerHTML = 'Iniciar Sesión';
          boton.disabled = false;
        });
    }
  });

  // Función para mostrar notificaciones
  function showToast(type, title, message) {
    const toast = new bootstrap.Toast(document.getElementById('notificationToast'));
    const toastTitle = document.getElementById('toastTitle');
    const toastMessage = document.getElementById('toastMessage');

    toastTitle.textContent = title;
    toastMessage.textContent = message;

    toast.show();
  }
</script>

<?php require_once 'views/layouts/footer.php'; ?>