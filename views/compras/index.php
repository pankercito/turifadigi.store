<?php include_once "views/layouts/header.php"; ?>

<div class="separador"></div>
<style>
  .separador {
    margin-top: 100px;
  }
</style>

<div class="container-lg">
  <div class="row">
    <div class="col-lg-2"></div>
    <div class="col-lg-8 mb-4">
      <h1 style="color: #5497CC;" class=" text-center" data-i18n="purchased_tickets">Boletos Comprados</h1>
      <h5 class=" text-center" data-i18n="purchased_tickets_desc"> <strong>- Gestión de Boletos de Rifa -</strong> </h5>
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
            <th class="text-center bg-body-tertiary" width="1%">#</th>
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
    fetch('./api/get_purchase', {
        method: 'POST',
        headers: { // Cambiado 'header' a 'headers' (es el nombre correcto en Fetch API)
          'Content-Type': 'application/json'
        }
      })
      .then(response => {
        // Siempre verifica si la respuesta es OK antes de intentar parsear el JSON
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
      })
      .then(dataD => {
        if (dataD.success) {
          let data = dataD.data;

          data.forEach((elemento, index) => {
            let boletos = elemento.boletos ? elemento.boletos.join(", ") : "";
            let acciones = elemento['estado'] == 'aprobado' ?
              `<div class="btn-group" role="group" aria-label="Basic example">
              <button type="button" class="btn btn-secondary btn-sm" onclick="pregunta(${elemento['id_compra']}, 1, 1)" data-bs-toggle-tooltip="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip tooltip-inner" data-bs-title="Confirmar Pago" ${elemento['estado'] === 'Pagado' ? 'disabled' : ''}>
                <i class="fa-solid fa-arrows-up-down-left-right fa-md"></i>
              </button>
            </div>` :
              `<div class="btn-group" role="group" aria-label="Basic example">
              <button type="button" class="btn btn-info btn-sm" onclick="pregunta(${elemento['id_compra']}, 1, null)" data-bs-toggle-tooltip="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip tooltip-inner" data-bs-title="Confirmar Pago">
                  <i class="fa-solid fa-pen"></i>
              </button>`;

            data[index]['contador'] = (index + 1); // Usa 'index' directamente
            data[index]['sorteo'] = elemento['id_rifa'];
            data[index]['boletos'] = boletos;
            data[index]['acciones'] = acciones;

            if (elemento['estado'] == 'aprobado') {
              data[index]['estado'] = `<small class="d-inline-flex px-2 py-1 fw-semibold text-success-emphasis bg-success-subtle border border-success-subtle rounded-2" data-i18n="paid">${i18n.t("paid")}</small>`;
            } else if (elemento['estado'] == 'pendiente') {
              data[index]['estado'] = `<small class="d-inline-flex px-2 py-1 fw-semibold text-warning-emphasis bg-warning-subtle border border-warning-subtle rounded-2" data-i18n="pending">${i18n.t("pending")}</small>`;
            } else if (elemento['estado'] == 'rechazado') {
              data[index]['estado'] = `<small class="d-inline-flex px-2 py-1 fw-semibold text-danger-emphasis bg-danger-subtle border border-danger-subtle rounded-2" data-i18n="rejected">${i18n.t("rejected")}</small>`;
            } else {
              data[index]['estado'] = `<small class="d-inline-flex px-2 py-1 fw-semibold text-secondary-emphasis bg-secondary-subtle border border-secondary-subtle rounded-2">${elemento['estado']}</small>`;
            }
          });

          const datos = {
            'id_tabla': '#tabla',
            'data': data,
            'columns': [{
                'data': 'contador',
                'title': "#",
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

          // Asumo que 'cargar_tabla_boletos' es una función global o accesible
          cargar_tabla_boletos(datos);

        } else {
          console.error('Error al cargar los datos de la tabla:', dataD.error);
        }
      })
      .catch(error => console.error('Error en la solicitud de compra:', error)); // Mensaje más específico
  }

  // Llamar a la función inmediatamente al cargar la página (opcional)
  document.addEventListener('DOMContentLoaded', () => {
    setTimeout(getPurchases, 300); // Esperar 1 segundo antes de la primera llamada

    // Llamar a la función cada 30 segundos (30000 milisegundos)
    const intervalId = setInterval(getPurchases, 30000);
  });

  // SWEETAL ALERT PARA CONFIRMAR PAGO

  async function pregunta(id, condition = false, pagdo = null) {

    const response = await fetch('./compras/view/accions_view?acvi=' + id, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
    });
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const htmlPersonalizado = await response.text();

    if (condition) {
      let title = pagdo == null ? '' : `<h5><span class="pago-confirmado">${i18n.t("payment_confirm")}<span class="icono-confirmado"></span><i class="fa-solid fa-check-circle"></i></span></h5>`;
      Swal.fire({
        title: title,
        html: htmlPersonalizado,
        showCloseButton: true,
        focusConfirm: false,
        confirmButtonText: i18n.t("btn_close"),
        width: '850px',
        didOpen: () => {
          // This function runs AFTER SweetAlert has rendered the HTML
          // Now you can safely execute your JavaScript that interacts with elements inside the SweetAlert
          if (typeof i18n !== 'undefined' && typeof i18n.t === 'function') {
            document.getElementById('payment-confirm-text').innerText = i18n.t("comprobante_title");
            document.getElementById('label-titular').innerText = i18n.t("comprobante_titular_title");
            document.getElementById('label-referencia').innerText = i18n.t("comprobante_referencia");
            document.getElementById('label-monto-pagar').innerText = i18n.t("comprobante_amonto");
            document.getElementById('label-monto-pagado').innerText = i18n.t("comprobante_monto");
            document.getElementById('label-metodo').innerText = i18n.t("comprobante_metodo");
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
      confirmButtonText: `
              verificar`,
      denyButtonText: `
              rechazar`,
      cancelButtonText: `
              cancelar `,

    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = "/confirmarBoleto/" + id;
      }
    });
  }
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

  .pago-confirmado {
    background-color: #d4edda;
    /* Verde claro de fondo */
    color: #155724;
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
</style>
<?php if (!empty($_SESSION['mensaje'])) {
  echo $_SESSION['mensaje'];
  unset($_SESSION['mensaje']);
} ?>