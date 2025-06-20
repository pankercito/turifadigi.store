<?php
require_once 'views/layouts/header.php';
?>
<!-- Agregar CSS de Toastify -->
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<!-- Agregar CSS personalizado -->
<link rel="stylesheet" type="text/css" href="assets/css/ToastPersonalizado.css">
<!-- Agregar Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<section class="contact-two">
  <div class="container">
    <div class="section-title text-center">
      <div class="section-title__tagline-box">
        <div class="section-title__tagline-shape"></div>
        <span class="section-title__tagline"></span>
      </div>
      <h2 class="section-title__title" data-i18n="config_sorteo_title">Configuración del Sorteo</h2>
    </div>
    <div class="row">
      <div class="col-xl-8 mx-auto">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title" data-i18n="create_new_raffle">Crear Nuevo Sorteo</h3>
          </div>
          <div class="card-body">
            <form id="form-config" class="contact-two__form" enctype="multipart/form-data">
              <div class="form-group mb-3">
                <label for="imagen" class="form-label" style="font-weight: bold;">
                  <i class="bi bi-image icon-signup"></i> <span data-i18n="banner_sorteo">Banner del Sorteo</span>
                </label>
                <input type="file" class="form-control input-hover-signup" id="imagen" name="imagen" accept=".jpg,.jpeg,.png">
                <small class="form-text text-muted" data-i18n="banner_sorteo_help">(Opcional, PNG o JPG, máximo 5MB)</small>
              </div>

              <div class="mb-3">
                <label for="titulo" class="form-label" style="font-weight: bold;">
                  <i class="bi bi-card-heading icon-signup"></i> <span data-i18n="title_sorteo">Título del Sorteo</span> *
                </label>
                <input type="text" class="form-control input-hover-signup" id="titulo" name="titulo" value='{"ES":"🎉 ¡POR EL SUPERGANA! 🎉","EN":"🎉 BY SUPERGANA 🎉"}' required>
              </div>

              <div class="row">
                <div class="col-md-4">
                  <div class="mb-3">
                    <label for="fecha_inicio" class="form-label" style="font-weight: bold;">
                      <i class="bi bi-calendar-event icon-signup"></i> <span data-i18n="fecha_inicio">Fecha de Inicio</span> *
                    </label>
                    <input type="date" class="form-control input-hover-signup" id="fecha_inicio" name="fecha_inicio" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="mb-3">
                    <label for="fecha_final" class="form-label" style="font-weight: bold;">
                      <i class="bi bi-calendar-x icon-signup"></i> <span data-i18n="fecha_final">Fecha de Fin</span> *
                    </label>
                    <input type="date" class="form-control input-hover-signup" id="fecha_final" name="fecha_final" required>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="mb-3">
                    <label for="precio_boleto" class="form-label" style="font-weight: bold;">
                      <i class="bi bi-currency-dollar icon-signup"></i> <span data-i18n="precio_boleto">Precio del Boleto ($)</span> *
                    </label>
                    <input type="number" step="0.01" class="form-control input-hover-signup" id="precio_boleto" name="precio_boleto" value="3" required>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="boletos_minimos" class="form-label" style="font-weight: bold;">
                      <i class="bi bi-ticket-perforated icon-signup"></i> <span data-i18n="compra_minima">Compra Mínima de Boletos</span> *
                    </label>
                    <input type="number" class="form-control input-hover-signup" id="boletos_minimos" name="boletos_minimos" value="2" required>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="mb-3">
                    <label for="boletos_maximos" class="form-label" style="font-weight: bold;">
                      <i class="bi bi-ticket-detailed icon-signup"></i> <span data-i18n="compra_maxima">Compra Máxima de Boletos</span> *
                    </label>
                    <input type="number" class="form-control input-hover-signup" id="boletos_maximos" name="boletos_maximos" value="500" required>
                  </div>
                </div>
              </div>
              <h4 class="mt-4 mb-3" style="font-weight: bold;">
                <i class="bi bi-gift icon-signup"></i> <span data-i18n="premios">Premios</span>
              </h4>
              <div id="premios-container">
                <div class="card mb-3 premio-item">
                  <div class="card-body">
                    <div class="mb-3">
                      <label class="form-label" style="font-weight: bold;">
                        <i class="bi bi-trophy icon-signup"></i> <span data-i18n="nombre_premio">Nombre del Premio</span> *
                      </label>
                      <input type="text" class="form-control input-hover-signup" name="premios[0][nombre]" value='{"ES":"🛵 Premio Mayor", "EN":"🛵 Grand Prize"}' required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label" style="font-weight: bold;">
                        <i class="bi bi-card-text icon-signup"></i> <span data-i18n="descripcion_premio">Descripción del Premio</span> *
                      </label>
                      <textarea class="form-control input-hover-signup" name="premios[0][descripcion]" rows="3" required>{"EN": "If you're in the United States, you win a motorcycle! If you're in another country, you win the cash equivalent of the motorcycle in your local currency.", "ES": "Si estás en Estados Unidos ¡Ganas una moto! Si estás en otro país, ganas el valor de la moto al cambio de la moneda local desde donde participes"}</textarea>
                    </div>
                  </div>
                </div>
                <div class="card mb-3 premio-item">
                  <div class="card-body">
                    <div class="mb-3">
                      <label class="form-label" style="font-weight: bold;">
                        <i class="bi bi-trophy icon-signup"></i> <span data-i18n="nombre_premio">Nombre del Premio</span> *
                      </label>
                      <input type="text" class="form-control input-hover-signup" name="premios[1][nombre]" value='{"ES":"📱 Segundo Premio", "EN":"📱 Second Prize"}' required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label" style="font-weight: bold;">
                        <i class="bi bi-card-text icon-signup"></i> <span data-i18n="descripcion_premio">Descripción del Premio</span> *
                      </label>
                      <textarea class="form-control input-hover-signup" name="premios[1][descripcion]" rows="3" required>{"EN": "Win an iPhone 16 Pro Max! Open to participants from any country. This prize is activated when 60% of the tickets are sold.", "ES": "¡Gana un iPhone 16 Pro Max! Abierto a participantes de cualquier país. Este premio se activa con el 60% de los boletos vendidos."}</textarea>
                    </div>
                  </div>
                </div>

                <div class="card mb-3 premio-item">
                  <div class="card-body">
                    <div class="mb-3">
                      <label class="form-label" style="font-weight: bold;">
                        <i class="bi bi-trophy icon-signup"></i> Nombre del Premio *
                      </label>
                      <input type="text" class="form-control input-hover-signup" name="premios[2][nombre]" value='{"ES":"💵 Tercer Premio", "EN":"💵 Third Prize"}' required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label" style="font-weight: bold;">
                        <i class="bi bi-card-text icon-signup"></i> <span data-i18n="descripcion_premio">Descripción del Premio</span> *
                      </label>
                      <textarea class="form-control input-hover-signup" name="premios[2][descripcion]" rows="3" required>{"EN": "Your chance to win $1,000 cash begins when we reach 30% of raffle tickets sold!", "ES": "¡Tu oportunidad de ganar $1000 en efectivo comienza cuando alcancemos el 30% de los boletos vendidos en la rifa!"}</textarea>
                    </div>
                  </div>
                </div>
              </div>
              <div class="mb-3">
                <label for="numero_contacto" class="form-label" style="font-weight: bold;">
                  <i class="bi bi-telephone-fill icon-signup"></i> <span data-i18n="contacto_numero">Número de Contacto</span> *
                </label>
                <input type="text" class="form-control input-hover-signup" id="numero_contacto" name="numero_contacto" value="407-428-7580" required>
              </div>
              <div class="mb-3">
                <label for="url_rifa" class="form-label" style="font-weight: bold;">
                  <i class="bi bi-link-45deg icon-signup"></i> <span data-i18n="url_loteria">URL de la Lotería</span> *
                </label>
                <input type="url" class="form-control input-hover-signup" id="url_rifa" name="url_rifa" value="https://tripletachira.com/" required>
              </div>
              <div class="mb-3 d-none">
                <label for="texto_ejemplo" class="form-label" style="font-weight: bold;">
                  <i class="bi bi-chat-left-text icon-signup"></i> <span data-i18n="texto_ejemplo">Texto de Ejemplo</span> *
                </label>
                <textarea class="form-control input-hover-signup" id="texto_ejemplo" name="texto_ejemplo" rows="3" required>{"ES":"","EN":""}</textarea>
              </div>
              <button type="submit" class="btn btn-primary" data-i18n="create_raffle2">Crear Sorteo</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div id="notificationToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-header">
        <strong class="me-auto" id="toastTitle"></strong>
        <small class="text-muted" id="toastTime"></small>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
      <div class="toast-body" id="toastMessage"></div>
    </div>

  </div>

</section>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('form-config');
    const submitBtn = form.querySelector('button[type="submit"]');
    form.onsubmit = function(e) {
      e.preventDefault();
      // Deshabilitar el botón
      submitBtn.disabled = true;
      submitBtn.textContent = 'Creando sorteo...';
      const formData = new FormData(form);
      Swal.fire({
        title: i18n.t('processing'),
        html: i18n.t('please_wait'),
        timerProgressBar: true,
        didOpen: () => {
          Swal.showLoading();

          fetch('/crear_sorteo', {
              method: 'POST',
              body: formData
            })
            .then(response => response.json())
            .then(data => {
              if (data.success) {
                let timerInterval;
                Swal.fire({
                  title: i18n.t('create_raffle_success'),
                  html: i18n.t('create_raffle_success_redirect'),
                  timer: 3000,
                  timerProgressBar: true,
                  didOpen: () => {
                    Swal.showLoading();
                    const timer = Swal.getPopup().querySelector("b");
                    timerInterval = setInterval(() => {
                      timer.textContent = `${Swal.getTimerLeft()}`;
                    }, 100);
                  },
                  willClose: () => {
                    clearInterval(timerInterval);
                    window.location.href = '/sorteo_verificacion';
                  }
                });
              } else {
                showToast('error', 'Error', data.error || 'Error al crear el sorteo');
              }
            })
            .catch(error => {
              showToast('error', 'Error', 'Ocurrió un error al procesar la solicitud');
              console.error(error);
            })
            .finally(() => {
              // Habilitar el botón nuevamente
              submitBtn.disabled = false;
              submitBtn.textContent = 'Crear Sorteo';
            });
        },
      });
    };

    // Función para mostrar notificaciones
    function showToast(type, title, message) {
      const toast = document.getElementById('notificationToast');
      const toastTitle = document.getElementById('toastTitle');
      const toastMessage = document.getElementById('toastMessage');

      if (!toast || !toastTitle || !toastMessage) {
        alert(message); // Fallback si no existe el toast
        return;
      }

      toastTitle.textContent = title;
      toastMessage.textContent = message;

      new bootstrap.Toast(toast).show();
    }
  });
</script>


<?php
require_once 'views/layouts/footer.php';
?>