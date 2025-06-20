<?php include_once "views/layouts/header.php"; ?>

<div class="container-lg">
  <div class="row">
    <div class="col-lg-2"></div>
    <div class="col-lg-8 mb-4">
      <h1 style="color: #5497CC;" class=" text-center" data-i18n="admin_tickets">Administración de Boletos</h1>
      <h5 class=" text-center" data-i18n="admin_tickets_desc">Gestión de Boletos de Rifa</h5>
    </div>
    <div class="col-lg-2"></div>
  </div>
  <div class="row mb-3">
    <div class="col-lg-2"></div>
    <div class="col-lg-8 mb-2 d-flex justify-content-center">
    </div>
  </div>
  <div class="row justify-content-center mb-3">
    <div class="col-lg-12 table-responsive">
      <table id="tabla" class="table table-sm table-bordered table-hover mb-0">
        <thead class="align-middle">
          <tr>
            <th class="text-center bg-body-tertiary" width="1%" data-i18n-th="datatable_number">NUMBER</th>
            <th class="text-center bg-body-tertiary" width="18%" data-i18n-th="purchase_date_2">FECHA DE COMPRA</th>
            <th class="text-center bg-body-tertiary" width="20%" data-i18n-th="datatable_buyer">COMPRADOR</th>
            <th class="text-center bg-body-tertiary" width="1%" data-i18n-th="datatable_raffle">SORTEO</th>
            <th class="text-center bg-body-tertiary" width="15%" data-i18n-th="datatable_tickets">BOLETOS</th>
            <th class="text-center bg-body-tertiary" width="10%" data-i18n-th="datatable_amount">MONTO</th>
            <th class="text-center bg-body-tertiary" width="14%" data-i18n-th="datatable_status">ESTADO</th>
            <th class="text-center bg-body-tertiary" width="14%" data-i18n-th="datatable_actions">ACCIONES</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>


<?php include_once "views/layouts/footer.php"; ?>


<script>
  // CARGA DE LA TABLA
  function getPurchases() {

    fetch('./api/get_purchase?cmp=1', {
        method: 'POST',
        header: {
          'Content-Type': 'application/json'
        }
      }).then(response => response.json())
      .then(dataD => {
        if (dataD.success) {

          let i = 0;
          let data = dataD.data;

          data.forEach((elemento, index) => {
            let boletos = elemento.boletos ? elemento.boletos.join(", ") : "";
            let acciones;
            if (['aprobado', 'rechazado'].includes(elemento['estado_pago'])) {
              acciones = `
              <div class="btn-group" role="group" aria-label="Basic example">
                <button type="button" class="btn btn-secondary btn-sm"
                  onclick="pregunta(${elemento['id_compra']}, '${elemento['estado_pago']}')"
                  data-bs-toggle-tooltip="tooltip"
                  data-bs-placement="top"
                  data-bs-custom-class="custom-tooltip tooltip-inner"
                  data-bs-title="Confirmar Pago">
                  <i class="fa-solid fa-arrows-up-down-left-right fa-md"></i>
                </button>
              </div>`;
            } else {
              acciones = `
              <div class="btn-group" role="group" aria-label="Basic example">
                <button type="button" class="btn btn-info btn-sm"
                  onclick="pregunta(${elemento['id_compra']})"
                  data-bs-toggle-tooltip="tooltip"
                  data-bs-placement="top"
                  data-bs-custom-class="custom-tooltip tooltip-inner"
                  data-bs-title="Confirmar Pago">
                  <i class="fa-solid fa-pen"></i>
                </button>
              </div>`;
            }
            data[i]['contador'] = (i + 1);
            data[i]['sorteo'] = elemento['id_rifa'];
            data[i]['boletos'] = boletos;
            data[i]['acciones'] = acciones;

            if (elemento['estado_pago'] == 'aprobado') {
              data[i]['estado'] = `<div class="btn-group" role="group" aria-label="Estado">
              <button type="button" class="btn btn-success btn-sm" disabled>
              <i class="fa-solid fa-check"></i> ${i18n.t("paid")}
              </button>
            </div>`;
            } else if (elemento['estado_pago'] == 'rechazado') {
              data[i]['estado'] = `<div class="btn-group" role="group" aria-label="Estado">
              <button type="button" class="btn btn-danger btn-sm" disabled>
              <i class="fa-solid fa-times"></i> ${i18n.t("rejected")}
              </button>
            </div>`;
            } else {
              data[i]['estado'] = `<div class="btn-group" role="group" aria-label="Estado">
              <button type="button" class="btn btn-info btn-sm" disabled>
              <i class="fa-solid fa-hourglass-half"></i> ${i18n.t("pending")}
              </button>
            </div>`;
            }

            i++;
          })

          const datos = {
            'id_tabla': '#tabla',
            'data': data,
            'columns': [{
                'data': 'contador',
                'title': i18n.t("datatable_number"),
                'className': 'text-center'
              },
              {
                'data': 'fecha_compra',
                'title': i18n.t("purchase_date_2"),
                'className': 'text-center'
              },
              {
                'data': 'cliente',
                'title': i18n.t("datatable_buyer"),
                'className': 'text-center'
              },
              {
                'data': 'sorteo',
                'title': i18n.t("datatable_raffle"),
                'className': 'text-center'
              },
              {
                'data': 'boletos',
                'title': i18n.t("datatable_tickets"),
                'className': 'text-center'
              },
              {
                'data': 'total',
                'title': i18n.t("datatable_amount"),
                'className': 'text-center'
              },
              {
                'data': 'estado',
                'title': i18n.t("datatable_status"),
                'className': 'text-center'
              },
              {
                'data': 'acciones',
                'title': i18n.t("datatable_actions"),
                'className': 'text-center'
              }
            ]
          };

          cargar_tabla_boletos(datos);

        } else {
          console.error('Error al cargar los datos de la tabla:', data.error);
        }
      })
      .catch(error => console.error('Error:', error));

    // SWEETAL ALERT PARA CONFIRMAR PAGO
  }

  async function pregunta(id, condition = false) {

    const response = await fetch('./admin/views/compra/accions_view?acvi=' + id, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
    });
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const htmlPersonalizado = await response.text();

    if (condition == 'aprobado') {
      Swal.fire({
        title: `<h5><span class="pago-confirmado">${i18n.t("payment_confirm")}<span class="icono-confirmado"></span><i class="fa-solid fa-check-circle"></i></span></h5>`,
        html: htmlPersonalizado,
        showCloseButton: true,
        focusConfirm: false,
        confirmButtonText: i18n.t("btn_close"),
        didOpen: () => {
          //  TRANSLATE ZONE 
          if (typeof i18n !== 'undefined' && typeof i18n.t === 'function') {
            document.getElementById('comprobante-title-text').textContent = i18n.t("comprobante_title");
            document.getElementById('label-titular').innerText = i18n.t("comprobante_titular_title");
            document.getElementById('label-referencia').innerText = i18n.t("comprobante_referencia");
            document.getElementById('label-monto-pagar').innerText = i18n.t("comprobante_amonto");
            document.getElementById('label-monto-pagado').innerText = i18n.t("comprobante_monto");
            document.getElementById('label-metodo-pago').innerText = i18n.t("comprobante_metodo");
          } else {
            console.error("i18n object or i18n.t function is not defined.");
          }
        }
      });
      return;
    }
    if (condition == 'rechazado') {
      Swal.fire({
        title: `<h5><span class="pago-rechazado">${i18n.t("payment_confirm")}<span class="icono-rechazado"></span><i class="fa-solid fa-circle-xmark"></i></span></h5>`,
        html: htmlPersonalizado,
        showCloseButton: true,
        focusConfirm: false,
        confirmButtonText: i18n.t("btn_close"),
        didOpen: () => {
          //  TRANSLATE ZONE 
          if (typeof i18n !== 'undefined' && typeof i18n.t === 'function') {
            document.getElementById('comprobante-title-text').textContent = i18n.t("comprobante_title");
            document.getElementById('label-titular').innerText = i18n.t("comprobante_titular_title");
            document.getElementById('label-referencia').innerText = i18n.t("comprobante_referencia");
            document.getElementById('label-monto-pagar').innerText = i18n.t("comprobante_amonto");
            document.getElementById('label-monto-pagado').innerText = i18n.t("comprobante_monto");
            document.getElementById('label-metodo-pago').innerText = i18n.t("comprobante_metodo");
          } else {
            console.error("i18n object or i18n.t function is not defined.");
          }
        }
      });
      return;
    }

    Swal.fire({
      html: htmlPersonalizado,
      showCloseButton: true,
      showCancelButton: true,
      showDenyButton: true,
      focusConfirm: false,
      confirmButtonText: i18n.t("btn_verify"),
      denyButtonText: i18n.t("btn_reject"),
      cancelButtonText: i18n.t("btn_cancel"),
      didOpen: () => {
        //  TRANSLATE ZONE 
        if (typeof i18n !== 'undefined' && typeof i18n.t === 'function') {
          document.getElementById('comprobante-title-text').textContent = i18n.t("comprobante_title");
          document.getElementById('label-titular').innerText = i18n.t("comprobante_titular_title");
          document.getElementById('label-referencia').innerText = i18n.t("comprobante_referencia");
          document.getElementById('label-monto-pagar').innerText = i18n.t("comprobante_amonto");
          document.getElementById('label-monto-pagado').innerText = i18n.t("comprobante_monto");
          document.getElementById('label-metodo-pago').innerText = i18n.t("comprobante_metodo");
        } else {
          console.error("i18n object or i18n.t function is not defined.");
        }
      }

    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire({
          title: "¡Procesando!",
          html: "Por favor, espera mientras se completa la operación...",
          timerProgressBar: true,
          didOpen: () => {
            Swal.showLoading();

            fetch("./api/change_purchase_status?est=enabled&id=" + id, {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json'
                }
              })
              .then(response => response.json())
              .then(data => {
                Swal.close();

                let timerInterval;
                Swal.fire({
                  icon: 'success',
                  title: '¡Éxito al aceptar!',
                  timer: 3000,
                  timerProgressBar: true,
                  didOpen: () => {
                    Swal.showLoading();
                    const timer = Swal.getPopup().querySelector("b");
                    timerInterval = setInterval(() => {
                      if (timer) timer.textContent = `${Swal.getTimerLeft()}`;
                    }, 100);
                  },
                  willClose: () => {
                    clearInterval(timerInterval);
                    window.location.reload();
                  }
                }).then((result) => {
                  if (result.dismiss === Swal.DismissReason.timer) {
                    // No action needed
                  }
                });

              })
              .catch((error) => {
                Swal.close();
                console.error('Error en la petición:', error);
                Swal.fire({
                  icon: 'error',
                  title: '¡Error!',
                  text: 'Hubo un problema al procesar la solicitud.',
                  timer: 3000,
                  showConfirmButton: false
                });
              });

          },
        });
      } else if (result.isDenied) {
        Swal.fire({
          title: "¡Procesando!",
          html: "Por favor, espera mientras se completa la operación...",
          timerProgressBar: true,
          didOpen: () => {
            Swal.showLoading();
            fetch("./api/change_purchase_status?est=disabled&id=" + id, {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json'
                }
              })
              .then(response => response.json())
              .then(data => {
                Swal.close();
                let timerInterval;
                Swal.fire({
                  icon: 'warning',
                  title: '¡Éxito al rechazar!',
                  timer: 3000,
                  timerProgressBar: true,
                  didOpen: () => {
                    Swal.showLoading();
                    const timer = Swal.getPopup().querySelector("b");
                    timerInterval = setInterval(() => {
                      if (timer) timer.textContent = `${Swal.getTimerLeft()}`;
                    }, 100);
                  },
                  willClose: () => {
                    clearInterval(timerInterval);
                    window.location.reload();
                  }
                }).then((result) => {
                  if (result.dismiss === Swal.DismissReason.timer) {
                    // No action needed
                  }
                });
              })
              .catch((error) => {
                Swal.close();
                console.error('Error en la petición:', error);
                Swal.fire({
                  icon: 'error',
                  title: '¡Error!',
                  text: 'Hubo un problema al procesar la solicitud.',
                  timer: 3000,
                  showConfirmButton: false
                });
              });
          },
        });

      }
    });
  }

  // Llamar a la función inmediatamente al cargar la página (opcional)  
  document.addEventListener('DOMContentLoaded', function() {
    setTimeout(getPurchases, 300); // Esperar 1 segundo antes de la primera llamada

    // Llamar a la función cada 30 segundos (30000 milisegundos)
    const intervalId = setInterval(getPurchases, 30000);
  });
</script>
<style>
  .form-section-title {
    justify-content: center;
  }

  .alerta.danger {
    margin: 21px 21px 0;
    color: #842029;
    background-color: #f8d7da;
    border-color: #f5c2c7;
    position: relative;
    padding: 1rem 1rem;
    border: 1px solid transparent;
    border-radius: .25rem;
  }

  .pago-rechazado {
    background-color: #f8d7da;
    /* rojo claro de fondo */
    color: #842029;
    margin: 20px 0px -20px;
    /* rojo oscuro del texto */
    padding: 10px 20px;
    /* Espacio interior */
    border-radius: 5px;
    /* Bordes redondeados */
    border: 1px solid #f5c2c7;
    /* Borde sutil */
    display: inline-block;
    /* Para que el ancho se ajuste al contenido */
    font-weight: bold;
    /* Texto en negrita */
    box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
  }

  .pago-confirmado {
    background-color: #d4edda;
    /* Verde claro de fondo */
    color: #155724;
    margin: 20px 0px -20px;
    /* Verde oscuro del texto */
    padding: 10px 20px;
    /* Espacio interior */
    border-radius: 5px;
    /* Bordes redondeados */
    border: 1px solid #c3e6cb;
    /* Borde sutil */
    display: inline-block;
    /* Para que el ancho se ajuste al contenido */
    font-weight: bold;
    /* Texto en negrita */
    box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
    /* Sombra suave */
  }

  .icono-confirmado {
    margin-right: 8px;
    /* Espacio entre el icono y el texto */
    font-size: 1.2em;
    /* Tamaño del icono */
    vertical-align: middle;
    /* Alineación vertical con el texto */
  }

  .icono-rechazado {
    margin-right: 8px;
    /* Espacio entre el icono y el texto */
    font-size: 1.2em;
    /* Tamaño del icono */
    vertical-align: middle;
    /* Alineación vertical con el texto */
  }

  .container-lg {
    margin-top: 110px;
  }

  @media (max-width: 765px) {
    .container-lg {
      margin-top: 60px;
    }
  }
</style>
<?php if (!empty($_SESSION['mensaje'])) {
  echo $_SESSION['mensaje'];
  unset($_SESSION['mensaje']);
} ?>