<?php include_once "views/layouts/header.php"; ?>
<?php include_once "views/layouts/slider.php"; ?>


<div class="container-lg">
  <div class="row">
    <div class="col-lg-2"></div>
    <div class="col-lg-8 mb-4">
      <h1 style="color: #5497CC;" class=" text-center" data-i18n="admin_raffles">Administración de Sorteos</h1>
      <h5 class=" text-center"> <strong data-i18n="admin_raffles_desc"> - Gestión de Sorteos activar/finalizar -</strong> </h5>
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
            <th class="text-center bg-body-tertiary" width="1%" data-i18n="fecha_creacion">#</th>
            <th class="text-center bg-body-tertiary" width="20%" data-i18n="fecha_creacion">FECHA DE CREACION</th>
            <th class="text-center bg-body-tertiary" width="25%" data-i18n="titulo">TITULO</th>
            <th class="text-center bg-body-tertiary" width="10%" data-i18n="id_sorteo">ID SORTEO</th>
            <th class="text-center bg-body-tertiary" width="20%" data-i18n="boletos_maximos">BOLETOS MAXIMOS</th>
            <th class="text-center bg-body-tertiary" width="10%" data-i18n="precio_boleto_2">PRECIO BOLETO</th>
            <th class="text-center bg-body-tertiary" width="10%" data-i18n="estado">ESTADO</th>
            <th class="text-center bg-body-tertiary" width="14%" data-i18n="acciones">ACCIONES</th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</div>


<?php include_once "views/layouts/footer.php"; ?>


<script>
  // CARGA DE LA TABLA
  function getRaffles() {

    fetch('./api/get_sorteo?cmp=1', {
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
            let acciones = `<div class="btn-group" role="group" aria-label="Basic example">
              <button type="button" class="btn btn-info btn-sm" onclick="obsSorteo(${elemento['id_rifa']})" data-bs-toggle-tooltip="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip tooltip-inner" data-bs-title="Editar Sorteo">
                <i class="fa-solid fa-arrows-up-down-left-right fa-md"></i>
              </button>
              <button type="button" class="btn btn-danger btn-sm" 
                onclick="${elemento['estado'] == 2 ? `obsSorteo(${elemento['id_rifa']})` : `cambiarEstado(${elemento['id_rifa']})`}" 
                data-bs-toggle-tooltip="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip tooltip-inner" data-bs-title="Cambiar Estado"
                ${elemento['estado'] == 2 ? 'disabled' : ''}>
                <i class="fa-solid fa-pen"></i>
              </button>
            </div>`;

            let estado = '';

            if (elemento['estado'] == 1) {
              estado = 'activo';
            } else if (elemento['estado'] == 2) {
              estado = 'finalizado';
            } else if (elemento['estado'] == 0 || elemento['estado'] === '' || elemento['estado'] === null || typeof elemento['estado'] === 'undefined') {
              estado = 'desactivado';
            }

            data[i]['contador'] = (i + 1);
            data[i]['id_rifa'] = elemento['id_rifa'];
            data[i]['titulo'] = elemento['titulo'];
            data[i]['fecha_creacion'] = elemento['fecha_creacion'];
            data[i]['boletos_maximos'] = elemento['configuracion']['boletos_maximos'];
            data[i]['precio_boleto'] = elemento['configuracion']['precio_boleto'];
            data[i]['estado'] = estado;
            data[i]['acciones'] = acciones;

            i++;
          })

          const datos = {
            'id_tabla': '#tabla',
            'data': data,
            'columns': [{
                'data': 'contador',
                'title': '#',
                'className': 'text-center'
              },
              {
                'data': 'fecha_creacion',
                'title': 'FECHA DE CREACION',
                'className': 'text-center'
              },
              {
                'data': 'titulo',
                'title': 'TITULO',
                'className': 'text-center'
              },
              {
                'data': 'id_rifa',
                'title': 'ID SORTEO',
                'className': 'text-center'
              },
              {
                'data': 'boletos_maximos',
                'title': 'BOLETOS MAXIMOS',
                'className': 'text-center'
              },
              {
                'data': 'precio_boleto',
                'title': 'PRECIO BOLETO',
                'className': 'text-center'
              },
              {
                'data': 'estado',
                'title': 'ESTADO',
                'className': 'text-center'
              },
              {
                'data': 'acciones',
                'title': 'ACCIONES',
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

  }

  // Llamar a la función inmediatamente al cargar la página (opcional)
  document.addEventListener('DOMContentLoaded', function() {
    setTimeout(getRaffles, 300); // Esperar 1 segundo antes de la primera llamada

    // Llamar a la función cada 30 segundos (30000 milisegundos)
    const intervalId = setInterval(getRaffles, 300000);
  });

  // Llamar a la función cada 30 segundos (30000 milisegundos)
  const intervalId = setInterval(getRaffles, 180000);
  // FUNCION PARA CAMBIAR ESTADO DEL SORTEO
  async function cambiarEstado(id) {
    Swal.fire({
      title: 'Cambio de estado',
      text: 'Al activar este sorteo, se desactivarán automáticamente los demás sorteos activos. ¿Deseas continuar?',
      showCloseButton: true,
      showCancelButton: true,
      showDenyButton: true,
      focusConfirm: false,
      confirmButtonText: `
              activar`,
      denyButtonText: `
              finalizar`,
      cancelButtonText: `
              cancelar `,

    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire({
          title: "¡Procesando!",
          html: "Por favor, espera mientras se completa la operación...",
          timerProgressBar: true,
          didOpen: () => {
            Swal.showLoading();

            fetch("./api/change_draw_status?est=enabled&id=" + id, {
                method: 'POST', // O el método HTTP que necesites
                headers: {
                  'Content-Type': 'application/json'
                }
              })
              .then(response => response.json()) // O response.text() si esperas texto plano
              .then(data => {
                Swal.close(); // Cierra el SweetAlert de "Procesando"

                let timerInterval;
                Swal.fire({
                  icon: 'success', // 'success' O 'error', 'warning', 'info', 'question' según el resultado
                  title: '¡Éxito al activar!', // O el título que corresponda
                  timer: 3000,
                  timerProgressBar: true,
                  didOpen: () => {
                    Swal.showLoading();
                  },
                  willClose: () => {
                    clearInterval(timerInterval);
                    window.location.reload();
                  }
                }).then((result) => {
                  /* Read more about handling dismissals below */
                  if (result.dismiss === Swal.DismissReason.timer) {}
                });

              })
              .catch((error) => {
                Swal.close(); // Asegúrate de cerrar el SweetAlert de "Procesando" en caso de error
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

        // window.location.href = "/confirmarBoleto/" + id;
      } else if (result.isDenied) {
        // Mostrar el formulario HTML obtenido de la ruta indicada
        fetch('/admin/views/sorteo/action_riffle?id=' + id, {
            method: 'GET',
            headers: {
              'Content-Type': 'text/html'
            }
          })
          .then(response => response.text())
          .then(htmlForm => {
            Swal.fire({
              title: 'Finalizar sorteo',
              html: htmlForm,
              showCancelButton: true,
              confirmButtonText: 'Finalizar',
              cancelButtonText: 'Cancelar',
              focusConfirm: false,
              preConfirm: () => {
                const form = Swal.getPopup().querySelector('form');
                if (!form) {
                  Swal.showValidationMessage('No se encontró el formulario');
                  return false;
                }
                // Validar que todos los campos requeridos no estén vacíos
                const requiredFields = form.querySelectorAll('[required]');
                for (let field of requiredFields) {
                  if (!field.value || field.value.trim() === '') {
                    Swal.showValidationMessage(`El campo "${field.getAttribute('id') || field.placeholder || 'requerido'}" no puede estar vacío`);
                    field.focus();
                    return false;
                  }
                  // Validar que el valor sea numérico y de 4 dígitos exactos (formato "0000")
                  if (field.type === 'number' || field.getAttribute('type') === 'number' || field.getAttribute('pattern') === '0000') {
                    if (!validarFormatoCuatroDigitos(field.value)) {
                      Swal.showValidationMessage(`El campo "${field.getAttribute('id') || field.placeholder || 'requerido'}" debe tener exactamente 4 dígitos numéricos (formato "0000")`);
                      field.focus();
                      return false;
                    }
                  }
                }
                // Obtener los datos del formulario
                const formData = new FormData(form);
                const data = {};
                formData.forEach((value, key) => {
                  data[key] = value;
                });
                return data;
              }
            }).then((result) => {
              if (result.isConfirmed && result.value) {
                Swal.fire({
                  title: "¡Procesando!",
                  html: "Por favor, espera mientras se completa la operación...",
                  timerProgressBar: true,
                  didOpen: () => {
                    Swal.showLoading();
                    fetch("./api/change_draw_status?est=disabled&id=" + id, {
                        method: 'POST',
                        headers: {
                          'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(result.value)
                      })
                      .then(response => response.json())
                      .then(data => {
                        Swal.close();
                        let timerInterval;
                        Swal.fire({
                          icon: 'info',
                          title: '¡Éxito al finalizar!',
                          timer: 3000,
                          timerProgressBar: true,
                          didOpen: () => {
                            Swal.showLoading();
                          },
                          willClose: () => {
                            clearInterval(timerInterval);
                            window.location.reload();
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
                  }
                });
              }
            });
          })
          .catch(error => {
            Swal.fire({
              icon: 'error',
              title: '¡Error!',
              text: 'No se pudo cargar el formulario.',
              timer: 3000,
              showConfirmButton: false
            });
          });
      }
    });

    // const response = await fetch('./admin/views/sorteo/cambiar_estado?id=' + id, {
    //   method: 'POST',
    //   headers: {
    //     'Content-Type': 'application/json'
    //   },
    // });

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const result = await response.json();

    if (result.success) {
      Swal.fire({
        title: 'Estado actualizado',
        text: 'El estado del sorteo ha sido modificado correctamente',
        icon: 'success',
        confirmButtonText: 'Aceptar'
      }).then(() => {
        location.reload();
      });
    } else {
      Swal.fire({
        title: 'Error',
        text: result.message,
        icon: 'error',
        confirmButtonText: 'Aceptar'
      });
    }
  }

  // FUNCION PARA VER DETALLES DEL SORTEO
  async function obsSorteo(id, condition = false) {
    try {
      const response = await fetch('./admin/views/sorteo/only_view?acvi=' + id, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
      });

      if (!response.ok) {
        throw new Error(`Error HTTP! estado: ${response.status}`);
      }

      const htmlPersonalizado = await response.text();

      Swal.fire({
        html: htmlPersonalizado,
        showCloseButton: true,
        focusConfirm: false,
        confirmButtonText: 'cerrar',
        showCancelButton: false,
      });

      return;

    } catch (error) {
      console.error('Error al obtener los detalles:', error);
      Swal.fire({
        title: 'Error',
        text: 'No se pudieron obtener los detalles del sorteo',
        icon: 'error',
        confirmButtonText: 'Aceptar'
      });
    }
  }

  function validarFormatoCuatroDigitos(input) {
    const regex = /^\d{4}$/; // ^ inicio, \d un dígito, {4} exactamente 4 veces, $ fin
    if (regex.test(input)) {
      return true;
    } else {
      return false;
    }
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