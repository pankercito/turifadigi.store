<link rel="stylesheet" href="assets/css/boletos.css">
<link rel="stylesheet" href="assets/css/chip_alzar.css">
<link rel="stylesheet" href="assets/css/premio.css">

<?php require_once 'views/sorteo/datos_personales/premio.php'; ?>

<div class="container-fluid">
  <div class="text-center">
    <h1 class="lista-title" data-i18n="list_tickets">LISTA DE BOLETOS</h1>

    <div class="progressLoader">
      <style>
        #siguiente {
          scroll-margin-top: 110px;
        }

        .no-sort-activ {
          display: flex;
          justify-content: center;
          align-items: center;
          height: 100%;
          font-size: 20px;
          color: #000;
          margin: auto;
        }

        .progressLoader {
          display: flex;
          justify-content: center;
          align-items: center;
        }

        .progress {
          background: rgba(0, 0, 0, 0.137);
          justify-content: flex-start;
          border-radius: 100px;
          align-items: center;
          position: relative;
          padding: 0 5px;
          display: flex;
          height: 40px;
          width: 50%;
          margin: 20px 0 5px;
        }

        .progress-value {
          animation: load 2s normal forwards;
          transition: transform 0.2s ease-in-out;
          box-shadow: 0 10px 40px -10px #414141;
          border-radius: 100px;
          background: linear-gradient(45deg, #00bcd4, #2196f3);
          height: 30px;
          width: 0;
        }

        .progress-label {
          margin: 0 5px;
        }

        @media (max-width: 480px) {
          .progress-value {
            animation: load 2s normal forwards;
            transition: transform 0.2s ease-in-out;
            box-shadow: 0 10px 40px -10px #414141;
            border-radius: 100px;
            background: linear-gradient(45deg, #00bcd4, #2196f3);
            height: 20px;
            width: 0;
          }

          .progress {
            background: rgba(0, 0, 0, 0.137);
            justify-content: flex-start;
            border-radius: 100px;
            align-items: center;
            position: relative;
            padding: 0 5px;
            display: flex;
            height: 30px;
            width: 50%;
            margin: 20px 0 5px;
          }

        }
      </style>

      <div class="progress">
        <div class="progress-value"></div>
        <div class="progress-label">0%</div>
      </div>

    </div>

    <script>
      function animateProgressBar(total, comprado) {
        const progressValue = document.querySelector('.progress-value');
        const progressLabel = document.querySelector('.progress-label');
        let marc = window.innerWidth;

        var width = marc > 480 ? 5 : 12; // Valor inicial de la barra de progreso

        if (marc > 480 && 900 > marc) {

          width = 10; // Valor inicial de la barra de progreso

        }

        let totalWidth = comprado / total * 100;

        if (totalWidth > width) { // Cambia el valor de 100 por el total real de boletos
          const interval = setInterval(() => {
            if (width >= totalWidth) {
              clearInterval(interval);
            } else {
              width++;
              progressValue.style.width = width + '%';
              progressLabel.textContent = width + '%';
            }
          }, 30); // Cambia el tiempo para ajustar la velocidad de la animación
        } else if (totalWidth > 0 && totalWidth < 5) {
          progressValue.style.width = width + '%';
          progressLabel.textContent = '1%';
        } else {
          progressValue.style.width = width + '%';
          progressLabel.textContent = '0%';
        }
      }
    </script>

    <div class="elegir-title">
      <button id="btnRandomNumber" class="magic-button">
        <i class="fa fa-star"></i>
        <span data-i18n="choose_randomly">ELEGIR A LA SUERTE</span>
        <i class="fa fa-star"></i>
      </button>
    </div>

    <div class="contador-boletos">
      <button class="btn-circle">-</button>
      <span class="numero-boletos">2</span>
      <button class="btn-circle">+</button>
      <div class="total">Total: <span id="totalUSD">12 USD</span></div>
    </div>

    <div class="buscar-container">
      <input type="text" id="buscador" class="form-control" placeholder="Buscar boleto (ej: 001, 0020...)" data-i18n-placeholder="search_ticket_placeholder">
      <button class="btn-buscar" data-i18n="search">BUSCAR</button>
    </div>

    <div class="boletos-container">
      <div class="boletos-grid" id="boletosList">
        <!-- Los boletos se generarán dinámicamente aquí -->
      </div>
      <div class="loading-text" style="display: block;" data-i18n="loading_tickets">Cargando boletos...</div>
    </div>

    <div class="seleccionados-container" style="display: none;">
      <span class="seleccionados-text" data-i18n="selected">SELECCIONADOS</span>
      <div class="contador">0</div>
      <div class="boletos-seleccionados-chips"></div>
      <button class="btn btn-continuar" data-i18n="continue">CONTINUAR</button>
    </div>
  </div>

  <div id="datosPersonales" class="form-personal" style="display: none;">
    <div class="payment-subtitle" id="siguiente">
      <i class="fas fa-arrow-down"></i>
      <span data-i18n="slide_down_to_continue">deslice hacia abajo para continuar</span>
      <i class="fas fa-arrow-down"></i>
    </div>
    <div class="payment-section">
      <div class="payment-title">
        <i class="fas fa-money-bill"></i>
        <span data-i18n="payment_method">METODO DE PAGO</span>
      </div>
      <div class="payment-subtitle" data-i18n="payment_method_subtitle">Transferencia o depósito</div>

      <div class="payment-methods">
        <div class="payment-method" onclick="mostrarDatosDePago('pago_movil')">
          <img src="assets/img/webp/pago_movil.webp" alt="pago movil">
        </div>
        <div class="payment-method" onclick="mostrarDatosDePago('zelle')">
          <img src="assets/img/webp/zelle.webp" alt="zelle">
        </div>
        <div class="payment-method" onclick="mostrarDatosDePago('davivienda')">
          <img src="assets/img/webp/davivienda.webp" alt="davivienda">
        </div>
        <div class="payment-method" onclick="mostrarDatosDePago('paypal')">
          <img src="assets/img/webp/paypal.webp" alt="paypal">
        </div>
        <div class="payment-method" onclick="mostrarDatosDePago('banco_venezuela')">
          <img src="assets/img/webp/banco_venezuela.webp" alt="banco_de_Venezuela">
        </div>
        <!--<div class="payment-method" onclick="mostrarDatosDePago('bancolombia')">
          <img src="assets/img/webp/bancolombia.webp" alt="bancolombia">
        </div>-->
      </div>

      <div class="payment-info">
        <p id="paymentTitle"></p>
        <div id="paymentDetails">
          <!-- Los detalles se cargarán dinámicamente -->
        </div>
      </div>

      <div class="converter-container">
        <h3 class="text-center">Total</h3>
        <div class="conversion-result">
          <div class="amount">
            <span id="mapr"></span>
          </div>
        </div>
        <p class="exchange-rate" id="tasaEx" data-i18n="exchange_rate">Tasa de cambio: 1 USD = 1 USD</p>
        <div class="currency-option-conten" id="currency-option-conten">
          <-- carga dinamicamente -->
        </div>
      </div>
    </div>
    <?php require_once 'views/sorteo/datos_personales/comprobante.php'; ?>
    <button type="submit" class="btn-confirmar" data-i18n="confirm">CONFIRMAR</button>
  </div>
</div>

<link rel="stylesheet" href="assets/css/buscar_boletos.css">
<link rel="stylesheet" href="assets/css/datos_personales.css">

<!-- // Este script genera un enlace de WhatsApp con los datos del cliente y los boletos comprados] -->
<script>
  function generarEnlaceWhatsApp(data, ticketsComprados, nuw = "") {

    const nombre = data.nombre; // Reemplaza con el nombre del cliente
    const celular = data.telefono; // Reemplaza con el número de celular del cliente
    const numeroTelefono = "14077329524"; // Numero de la empresa en WhatsApp
    const listaTickets = ticketsComprados.join(', '); // Convierte el array de tickets en una cadena separada por comas

    const mensaje = `FELICIDADES, ${nombre}!\n\nHas registrado exitosamente tus numeros: ${listaTickets}.\n\nEn un lapso no mayor a 24 horas las asesoras verificaran tus boletos y los podras observar en nuestro verificador.\n\nAl contrario, de no estar pagos tus boletos, tendras un lapso maximo de 72 horas para realizarlo con soporte. Pasando su tiempo estimado, saldran a disponibles nuevamente.\n\nTus datos de registro:\nNombre: ${nombre}\nCelular: ${celular}\n\nUN PLACER PARA NOSOTROS QUE FORMES PARTE DE NUESTROS GANADORES, GRACIAS POR CONFIAR EN EL MEJOR SORTEO DE TODOS CON TURIFADIGITAL!`;

    const mensajeCodificado = encodeURIComponent(mensaje);
    const enlaceWhatsApp = `https://wa.me/${numeroTelefono}?text=${mensajeCodificado}`;

    // Crear un elemento <a> dinámicamente
    const enlace = document.createElement('a');
    enlace.href = enlaceWhatsApp;
    enlace.target = '_blank'; // Abre el enlace en una nueva pestaña
    enlace.style.display = 'none'; // Oculta el enlace

    // Agregar el enlace al body
    document.body.appendChild(enlace);

    // Simular un clic en el enlace
    enlace.click();

    // Eliminar el enlace del DOM (opcional, pero buena práctica)
    document.body.removeChild(enlace);
  }

  document.addEventListener('DOMContentLoaded', async function() {
    await i18n.init();
    const boletosSeleccionados = new Set();
    const minBoletos = "<?php echo $boletosMinimos ?>" == '' ? "0" : "<?php echo $boletosMinimos ?>";
    let cantidadSeleccion = "<?php echo $boletosMinimos ?>" == '' ? "0" : "<?php echo $boletosMinimos ?>";
    let precioUnitarioUSD = "<?php echo $precioBoleto ?>" == '' ? "0" : "<?php echo $precioBoleto ?>";
    const todosLosBoletos = [];
    let cargandoBoletos = false;

    // Referencias a elementos del DOM
    const boletosContainer = document.querySelector('.boletos-container');
    const boletosList = document.getElementById('boletosList');
    const loadingText = document.querySelector('.loading-text');
    const miniregist = document.getElementById('regist');
    const buscador = document.getElementById('buscador');
    const btnRandomNumber = document.getElementById('btnRandomNumber');
    const numeroBoletosSpan = document.querySelector('.numero-boletos');
    const totalUSDSpan = document.getElementById('totalUSD');
    const btnMenos = document.querySelector('.btn-circle:first-of-type');
    const btnMas = document.querySelector('.btn-circle:last-of-type');

    function debounce(func, wait) {
      let timeout;
      return function executedFunction(...args) {
        const later = () => {
          clearTimeout(timeout);
          func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
      };
    }
    //SECCION ANIMACION DE CARGA************
    // Función para mostrar el texto de carga 
    let dotCount = 0;

    function animateLoadingText() {
      let dots = '';
      dotCount = (dotCount % 3) + 1; // Ciclo de 1 a 3

      for (let i = 0; i < dotCount; i++) {
        dots += '.';
      }

      loadingText.textContent = 'Cargando boletos' + dots;
    }
    // puedes iniciar o detener el intervalo según sea necesario.
    // Por ejemplo, para iniciar cuando se muestra:
    function mostrarCargando() {
      loadingText.style.display = 'block';
      if (!window.loadingInterval) { // Verifica si el intervalo ya existe
        window.loadingInterval = setInterval(animateLoadingText, 500);
      }
    }

    // Y para detener cuando la carga finaliza y ocultar:
    function ocultarCargando() {
      loadingText.style.display = 'none';
      clearInterval(window.loadingInterval);
      window.loadingInterval = null; // Limpia la variable del intervalo
    }
    //FINAL DE SECCION ANIMACION DE CARGA************

    // Función para cargar más boletos
    async function cargarMasBoletos() {
      const fragment = document.createDocumentFragment();

      mostrarCargando(); // Mostrar el texto de carga
      let comprados = 0;

      await fetch('./api/get_tickets') // Petición al backend
        .then(response => response.json())
        .then(async data => {
          const boletos = data['data'];
          const success = data['success'];

          try {
            if (!success) {
              if (boletos.rifa_estado == 0) {
                // Hacer otra petición con parámetro wn=1
                await fetch('./api/get_tickets?wn=1')
                  .then(response => response.json())
                  .then(dataWn => {
                    const boletosWn = dataWn['data'];
                    const boletosLen = dataWn['total'];
                    const boletosSc = dataWn['success'];
                    // Si tampoco hay ganadores recientes, mostrar solo ese mensaje
                    if (boletosSc == false || boletosLen === 0) {
                      const nuevoBoleto = document.createElement('div');
                      boletosList.style.display = 'flex';
                      nuevoBoleto.classList.add('no-sort-activ');
                      nuevoBoleto.setAttribute('data-i18n', 'no_active_raffles');
                      nuevoBoleto.textContent = i18n.t("no_active_raffles") !== "no_active_raffles" ?
                        i18n.t("no_active_raffles") :
                        "Sin sorteos activos, ni ganadores recientes";
                      fragment.appendChild(nuevoBoleto);
                    } else {

                      boletosContainer.innerHTML = "";
                      const nuevoBoleto = document.createElement('div');
                      const msj = document.createElement('p');
                      msj.textContent = i18n.t("last_winners_tickets");
                      nuevoBoleto.id = "boletoContainer";
                      boletosContainer.appendChild(msj);
                      boletosContainer.appendChild(nuevoBoleto);

                      // Validar que el elemento con id 'boletoContainer' exista antes de hacer el foreach
                      setTimeout(() => {
                        const container = document.getElementById('boletoContainer');
                        if (container && Array.isArray(boletosWn)) {
                          let databot = boletosWn.data || "";
                          boletosWn.forEach(boleto => {
                            // Llama a renderBoleto con los datos del boleto
                            renderBoleto({
                              items: {
                                nombre: databot.nombre || i18n.t("no_purchases"),
                                telefono: databot.telefono || i18n.t("no_purchases"),
                                "Precio": databot.precio || i18n.t("no_purchases")
                              },
                              fecha_compra: boleto.fecha_compra || i18n.t("no_purchases"),
                              numero: boleto.numero_boleto || "",
                              id_boleto: boleto.id_boleto || "",
                              id_rifa: boleto.id_rifa || "",
                              ganador: true
                            });
                          });
                        }
                      }, 0);

                      setTimeout(() => {
                        animateProgressBar(10000, 10000)
                      }, 1000);
                    }
                  })
                  .catch(error => {
                    console.error('Error al cargar los boletos ganadores:', error);
                  });
                return;
              }
            }
          } catch (error) {
            console.error('Error al cargar los boletos:', error);
          }

          // Si no hay boletos, no hacer nada aquí porque ya se maneja arriba

          boletos.forEach(boleto => {
            const nuevoBoleto = document.createElement('div');
            if (boleto.estado == "reservado" || boleto.estado == "vendido") {
              nuevoBoleto.classList.add('boleto', 'disabled');
              comprados++;
            } else {
              nuevoBoleto.className = 'boleto';
              nuevoBoleto.onclick = () => toggleBoleto(nuevoBoleto, boleto.numero_boleto);
            }
            nuevoBoleto.dataset.numero = boleto.numero_boleto;
            nuevoBoleto.textContent = boleto.numero_boleto;
            fragment.appendChild(nuevoBoleto);
            todosLosBoletos.push(nuevoBoleto);
          });

          setTimeout(() => {
            animateProgressBar(boletos.length, comprados) //Llama a la función de animación con el total y el número de boletos comprados
          }, 1000);

          boletosList.appendChild(fragment);

        })
        .catch(error => {
          console.error('Error al cargar los boletos:', error);
        })
        .finally(marc => {
          boletosList.appendChild(fragment);
          cargandoBoletos = false;
          ocultarCargando(); // Ocultar el texto de carga
        });
    }

    // Cargar los primeros boletos
    await cargarMasBoletos();

    // Inicializar el contador y total
    numeroBoletosSpan.textContent = cantidadSeleccion;
    actualizarTotal();
    actualizarContador();

    // Manejadores para los botones + y -
    btnMenos.addEventListener('click', () => {
      if (cantidadSeleccion > minBoletos) {
        cantidadSeleccion--;
        numeroBoletosSpan.textContent = cantidadSeleccion;
        actualizarTotal();
        actualizarContador();
      }
    });

    btnMas.addEventListener('click', () => {
      if (cantidadSeleccion < 10000) {
        cantidadSeleccion++;
        numeroBoletosSpan.textContent = cantidadSeleccion;
        actualizarTotal();
        actualizarContador();
      }
    });

    // Función para actualizar el total en USD
    function actualizarTotal() {
      const totalUSD = cantidadSeleccion * precioUnitarioUSD;

      // Actualizar el total en USD en el contador
      totalUSDSpan.textContent = `${totalUSD} USD`;
    }

    // Función para actualizar los chips de boletos seleccionados
    function actualizarChipsBoletos() {
      const contenedor = document.querySelector('.boletos-seleccionados-chips');
      contenedor.innerHTML = '';

      Array.from(boletosSeleccionados).sort().forEach(numero => {
        const chip = document.createElement('div');
        chip.className = 'boleto-chip';

        const numeroSpan = document.createElement('span');
        numeroSpan.textContent = numero;

        const removeButton = document.createElement('button');
        removeButton.className = 'chip-remove';
        removeButton.innerHTML = '×';
        removeButton.onclick = (e) => {
          e.stopPropagation();
          const boleto = document.querySelector(`.boleto[data-numero="${numero}"]`);
          if (boleto) {
            toggleBoleto(boleto, numero);
          }
        };

        chip.appendChild(numeroSpan);
        chip.appendChild(removeButton);
        contenedor.appendChild(chip);
      });

      // Actualizar el contador
      const contador = document.querySelector('.contador');
      contador.textContent = `${boletosSeleccionados.size} de ${cantidadSeleccion}`;
    }

    // Mostrar u ocultar el contenedor de seleccionados
    function toggleSelectedContainer() {
      const contenedor = document.querySelector('.boletos-grid');
      const boletos = contenedor.querySelectorAll('.selected');
      const selectedContainer = document.querySelector('.seleccionados-container');
      document.querySelector('.btn.btn-continuar').disabled = false;

      if (boletos.length >= 2) {
        selectedContainer.style.display = 'block'; // Hacer visible
      } else {
        selectedContainer.style.display = 'none'; // Hacer invisible
      }
    }

    // Función para alternar selección de boleto
    function toggleBoleto(elemento, numero) {
      if (elemento.classList.contains('selected')) {
        elemento.classList.remove('selected');
        boletosSeleccionados.delete(numero);
      } else {
        elemento.classList.add('selected');
        boletosSeleccionados.add(numero);
      }
      toggleSelectedContainer();

      actualizarContador();
      actualizarTotal();
      actualizarChipsBoletos();
      // Ejemplo de cómo usar la función después de obtener los datos del fetch:
    }

    // Función para elegir boletos al azar
    function elegirBoletosAlAzar() {
      boletosSeleccionados.clear();
      document.querySelectorAll('.boleto.selected').forEach(b => b.classList.remove('selected'));
      document.querySelector('.boletos-seleccionados-chips').innerHTML = ''; // Limpiar chips

      const boletosDisponibles = Array.from(todosLosBoletos).filter(b => !b.classList.contains('disabled'));

      if (boletosDisponibles.length < cantidadSeleccion) {
        alert('No hay suficientes boletos disponibles');
        return;
      }

      for (let i = 0; i < cantidadSeleccion; i++) {
        let indiceAleatorio;
        let boletoSeleccionado;

        do {
          indiceAleatorio = Math.floor(Math.random() * boletosDisponibles.length);
          boletoSeleccionado = boletosDisponibles[indiceAleatorio];
        } while (boletosSeleccionados.has(boletoSeleccionado.dataset.numero));

        setTimeout(() => {
          boletoSeleccionado.classList.add('selected');
          boletosSeleccionados.add(boletoSeleccionado.dataset.numero);
          actualizarContadorSeleccionados();
          actualizarChipsBoletos(); // Actualizar chips con retraso
          toggleSelectedContainer()

        }, i * 200);

        boletosDisponibles.splice(indiceAleatorio, 1);
      }
    }

    // Actualizar el contador de seleccionados
    function actualizarContadorSeleccionados() {
      const contadorElement = document.querySelector('.contador');
      contadorElement.textContent = `${boletosSeleccionados.size} de ${cantidadSeleccion}`;
    }

    // Evento para el botón de elegir a la suerte
    btnRandomNumber.addEventListener('click', elegirBoletosAlAzar);

    // Función de búsqueda en tiempo real
    function filtrarBoletos(busqueda) {
      busqueda = busqueda.trim();

      todosLosBoletos.forEach(boleto => {
        const numeroBoleto = boleto.dataset.numero;
        if (busqueda === '') {
          // Si no hay búsqueda, mostrar todos
          boleto.style.display = '';
          boleto.style.order = ''; // Restaurar orden original
        } else if (numeroBoleto.startsWith(busqueda)) {
          // Si el número coincide con la búsqueda, mostrar
          boleto.style.display = '';
          boleto.style.order = '1'; // Mover al principio
        } else {
          // Si no coincide, ocultar
          boleto.style.display = 'none';
          boleto.style.order = '2';
        }
      });
    }

    // Evento de entrada en el buscador (tiempo real)
    buscador.addEventListener('input', function(e) {
      filtrarBoletos(e.target.value);
    });

    // Evento del botón buscar
    document.querySelector('.btn-buscar').onclick = function() {
      filtrarBoletos(buscador.value);
    };

    // Actualizar el contador de boletos
    function actualizarContador() {
      const contador = document.getElementsByClassName('.contador');
      contador.textContent = `${boletosSeleccionados.size}`;
    }

    // Verificar disponibilidad de boletos seleccionados
    async function verificarBoletosSeleccionados() {
      const boletosArray = Array.from(boletosSeleccionados);
      console.log('Boletos seleccionados para verificar:', boletosArray);

      try {
        const response = await fetch('/verificarDisponibilidad', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Cache-Control': 'no-cache'
          },
          body: JSON.stringify({
            boletos: boletosArray
          })
        });

        if (!response.ok) {
          throw new Error(`Error HTTP: ${response.status}`);
        }

        const responseText = await response.text();

        if (!responseText.trim()) {
          throw new Error('Respuesta vacía del servidor');
        }

        const data = JSON.parse(responseText);

        if (!data.disponibles || !Array.isArray(data.disponibles)) {
          throw new Error('Formato de respuesta inválido');
        }

        const boletosNoDisponibles = data.disponibles.filter(b => !b.disponible).map(b => b.numero);

        if (boletosNoDisponibles.length > 0) {
          alert(`Los siguientes boletos no están disponibles: ${boletosNoDisponibles.join(', ')}`);
          document.querySelector('.btn.btn-continuar').disabled = false;

          return false;
        }

        return true;
      } catch (error) {
        console.error('Error en verificación:', error);
        alert('Error al verificar la disponibilidad de los boletos: ' + error.message);
        return false;
      }
    }

    // Continuar con el proceso
    document.querySelector('.btn.btn-continuar').onclick = function() {
      if (boletosSeleccionados.size < minBoletos) {
        alert('Debe seleccionar al menos 2 boletos para continuar');
        return;
      }

      const inputMapr = document.getElementById('mapr');
      const tasaEx = document.getElementById('tasaEx');

      // data de moneda
      fetch('./api/exchange_rate') // Asumiendo que esta API te da las tasas
        .then(response => response.json())
        .then(data => {
          // Ajusta estas líneas según la estructura de la respuesta de tu API
          const tasaBS = data.data.VES.precio;
          const tasaCOP = data.data.COP.precio;

          const opcionesMonedaHTML = crearOpcionesMoneda(precioUnitarioUSD, tasaBS, tasaCOP, boletosSeleccionados.size);

          // Agrega la sección de opciones de moneda al elemento deseado en tu HTML
          const contenedorOpciones = document.getElementById('currency-option-conten'); // Reemplaza con el ID de tu contenedor
          if (contenedorOpciones) {
            let dc = precioUnitarioUSD * boletosSeleccionados.size;
            const valorConvertido = dc;

            inputMapr.textContent = `${valorConvertido} $`;
            tasaEx.textContent = `Tasa de cambio: 1 USD = 1 USD`;
            contenedorOpciones.innerHTML = "";
            contenedorOpciones.appendChild(opcionesMonedaHTML);
          }
        })
        .catch(error => {
          console.error('Error al obtener las tasas de cambio:', error);
        });

      document.querySelector('.btn.btn-continuar').disabled = true;

      document.getElementById('datosPersonales').style.display = 'block';
      this.parentElement.style.display = 'none';

      window.location.href = "#siguiente";
    };

    const getInputValue = (selector) => {
      const element = document.querySelector(selector);
      return element ? element.value.trim() : '';
    };


    // Manejar el envío del formulario
    // Mejoras: 
    // - Evita doble envío
    // - Deshabilita botón durante proceso
    // - Maneja errores inesperados
    // - Limpia mensajes de SweetAlert correctamente

    const btnConfirmar = document.querySelector('.btn-confirmar');
    let procesandoCompra = false;

    btnConfirmar.onclick = async function(e) {
      Swal.fire({
        title: i18n.t('processing_purchase'),
        text: i18n.t('processing_purchase_text'),
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
          Swal.showLoading();
        }
      });

      e.preventDefault();

      if (procesandoCompra) return; // Evita doble envío
      procesandoCompra = true;
      btnConfirmar.disabled = true;

      try {
        if (miniregist.value == "0") {
          await fetch('./api/session_verfication?t=1', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json'
              }
            })
            .then(response => response.json())
            .then(async data => {

              if (!data.session) {
                window.location.href = '/login';
                return;
              }

              let value = data.user;

              // Obtener valores de forma segura
              const formData = {
                nombre: value.nombre + ' ' + value.apellido,
                telefono: value.telefono,
                ubicacion: value.ubicacion,
                id_usuario: value.id_usuario,
                monto_pago: getInputValue('#monto_pagado'),
                titular: getInputValue('#titular'),
                referencia: getInputValue('#referencia'),
                metodoPago: $('input[name="payment_method"]').val()
              };

              // Validar que todos los campos requeridos tengan valor
              const camposRequeridos = [{
                  campo: 'titular',
                  mensaje: 'comprobante_titular'
                },
                {
                  campo: 'referencia',
                  mensaje: 'comprobante_referencia'
                },
                {
                  campo: 'metodoPago',
                  mensaje: 'comprobante_metodo'
                }
              ];

              const camposFaltantes = camposRequeridos
                .filter(({
                  campo
                }) => !formData[campo])
                .map(({
                  mensaje
                }) => i18n.t(mensaje));

              if (camposFaltantes.length > 0) {
                Swal.close();
                Swal.fire({
                  title: i18n.t('incomplete_fields'),
                  text: i18n.t('incomplete_fields_text') + ':\n' + camposFaltantes.join('\n'),
                  icon: 'warning',
                  confirmButtonText: i18n.t('incomplete_fields_confirm'),
                  customClass: {
                    confirmButton: 'swal2-confirm'
                  }
                });
                return;
              }

              const boletosCargar = Array.from(boletosSeleccionados);

              try {
                // Preparar datos de la compra
                const datosCompra = {
                  boletos: boletosCargar,
                  ...formData,
                };

                await fetch('./api/process_purchase', {
                    method: 'POST',
                    headers: {
                      'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(datosCompra)
                  })
                  .then(response => response.json())
                  .then(async dataCompra => {
                    Swal.close();
                    if (dataCompra.success) {
                      generarEnlaceWhatsApp({
                        nombre: formData.nombre,
                        telefono: formData.telefono
                      }, boletosCargar);

                      await Swal.fire({
                        title: '¡Compra procesada correctamente!',
                        icon: 'success',
                        confirmButtonText: 'OK'
                      });
                      window.location.href = '/sorteo';
                    } else {
                      Swal.fire('Error', dataCompra.error || 'Error al procesar la compra', 'error');
                    }
                  })
                  .catch(error => {
                    Swal.close();
                    Swal.fire('Error', 'Error al procesar la compra', 'error');
                    console.error('Error al procesar la compra:', error);
                  });

              } catch (error) {
                Swal.close();
                console.error('Error:', error);
              }

            })
            .catch(error => {
              Swal.close();
              console.error('Error al verificar sesión:', error);
            });
        } else {
          // Unificar validación de campos de usuario y de pago
          const campos = [{
              nombre: 'nombre',
              label: 'Nombre',
              value: getInputValue('#nombre')
            },
            {
              nombre: 'apellido',
              label: 'Apellido',
              value: getInputValue('#apellido')
            },
            {
              nombre: 'prefijo_pais',
              label: 'Prefijo de país',
              value: getInputValue('#prefijo_pais')
            },
            {
              nombre: 'telefono',
              label: 'Teléfono',
              value: getInputValue('#telefono')
            },
            {
              nombre: 'monto_pagado',
              label: 'Monto pagado',
              value: getInputValue('#monto_pagado')
            },
            {
              nombre: 'titular',
              label: 'Titular',
              value: getInputValue('#titular')
            },
            {
              nombre: 'referencia',
              label: 'Referencia',
              value: getInputValue('#referencia')
            },
            {
              nombre: 'metodoPago',
              label: 'Método de pago',
              value: $('input[name="payment_method"]').val()
            }
          ];

          const camposFaltantes = campos.filter(c => !c.value).map(c => c.label);

          /*   if (camposFaltantes.length > 0) {
                Swal.close();
                Swal.fire({
                  title: i18n.t('incomplete_fields'),
                  text: i18n.t('incomplete_fields_text') + ':\n' + camposFaltantes.join('\n'),
                  icon: 'warning',
                  confirmButtonText: i18n.t('incomplete_fields_confirm'),
                  customClass: {
                    confirmButton: 'swal2-confirm'
                  }
                });
                return;
              } */

          if (camposFaltantes.length > 0) {
            Swal.close();
            Swal.fire({
              title: i18n.t('incomplete_fields'),
              text: i18n.t('incomplete_fields_text') + ':\n' + camposFaltantes.join('\n'),
              icon: 'warning',
              confirmButtonText: i18n.t('incomplete_fields_confirm'),
              customClass: {
                confirmButton: 'swal2-confirm'
              }
            });
            return;
          }

          const initData = {
            nombre: campos[0].value,
            apellido: campos[1].value,
            prefijo_pais: campos[2].value,
            telefono: campos[3].value
          };

          // CREAR USUARIO DINAMICAMENTE
          await fetch('./api/riffle_singup', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json'
              },
              body: JSON.stringify(initData)
            })
            .then(response => response.json())
            .then(async data => {

              if (!data.success) {
                Swal.close();
                Swal.fire('Error', "Error: response riffle sinup", 'error');
                return;
              }

              let idrio = data.id_usuario;

              await fetch('./api/riffle_verfication?t=1', {
                  method: 'POST',
                  headers: {
                    'Content-Type': 'application/json'
                  },
                  body: JSON.stringify({
                    id_usuario: idrio
                  })
                })
                .then(response => response.json())
                .then(async data => {

                  let value = data.user;

                  // Obtener valores de forma segura
                  const formData = {
                    nombre: value.nombre + ' ' + value.apellido,
                    telefono: value.telefono,
                    ubicacion: value.ubicacion,
                    id_usuario: value.id_usuario,
                    monto_pago: getInputValue('#monto_pagado'),
                    titular: getInputValue('#titular'),
                    referencia: getInputValue('#referencia'),
                    metodoPago: $('input[name="payment_method"]').val()
                  };

                  const boletosCargar = Array.from(boletosSeleccionados);

                  try {
                    // Preparar datos de la compra
                    const datosCompra = {
                      boletos: boletosCargar,
                      ...formData,
                    };

                    await fetch('./api/process_purchase', {
                        method: 'POST',
                        headers: {
                          'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(datosCompra)
                      })
                      .then(response => response.json())
                      .then(async dataCompra => {
                        Swal.close();
                        if (dataCompra.success) {
                          generarEnlaceWhatsApp({
                            nombre: formData.nombre,
                            telefono: formData.telefono
                          }, boletosCargar);

                          await Swal.fire({
                            title: '¡Compra procesada correctamente!',
                            icon: 'success',
                            confirmButtonText: 'OK'
                          });
                          window.location.href = '/sorteo';
                        } else {
                          Swal.fire('Error', dataCompra.error || 'Error al procesar la compra', 'error');
                        }
                      })
                      .catch(error => {
                        Swal.close();
                        Swal.fire('Error', 'Error al procesar la compra', 'error');
                        console.error('Error al procesar la compra:', error);
                      });

                  } catch (error) {
                    Swal.close();
                    console.error('Error:', error);
                  }

                })
                .catch(error => {
                  Swal.close();
                  console.error('Error al verificar sesión:', error);
                });

            })
            .catch(error => {
              Swal.close();
              console.error('Error al verificar sesión:', error);
            });
        }
      } catch (err) {
        Swal.close();
        Swal.fire('Error inesperado', 'Ha ocurrido un error inesperado. Intente de nuevo.', 'error');
        console.error('Error inesperado:', err);
      } finally {
        procesandoCompra = false;
        btnConfirmar.disabled = false;
      }
    };


    // Actualizar estilos para el botón de remover en el chip
    const styles = document.createElement('style');
    styles.textContent = `
      .boleto-chip {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #007bff;
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 14px;
        cursor: pointer;
      }

      .chip-remove {
        background: none;
        border: none;
        color: white;
        font-size: 18px;
        cursor: pointer;
        padding: 0 4px;
        line-height: 1;
        border-radius: 50%;
        transition: background-color 0.2s;
      }

      .chip-remove:hover {
        background-color: rgba(255,255,255,0.2);
      }
    `;
    document.head.appendChild(styles);
  });

  function crearOpcionesMoneda(precioUnitarioUSD, tasaBS, tasaCOP, cantidadUSD) {
    const currencyOptionsDiv = document.createElement('div');
    currencyOptionsDiv.classList.add('currency-option');

    // Opción BS
    const bsOptionLabel = document.createElement('label');
    bsOptionLabel.classList.add('currency-option');
    const bsRadio = document.createElement('input');
    bsRadio.type = 'radio';
    bsRadio.name = 'currency';
    bsRadio.value = 'BS';
    bsRadio.onclick = function() {
      convertirMoneda(precioUnitarioUSD, cantidadUSD, tasaBS, 'BS');
    };
    bsOptionLabel.appendChild(bsRadio);
    bsOptionLabel.appendChild(document.createTextNode(' BS'));
    currencyOptionsDiv.appendChild(bsOptionLabel);

    // Opción COP
    const copOptionLabel = document.createElement('label');
    copOptionLabel.classList.add('currency-option');
    const copRadio = document.createElement('input');
    copRadio.type = 'radio';
    copRadio.name = 'currency';
    copRadio.value = 'COP';
    copRadio.onclick = function() {
      convertirMoneda(precioUnitarioUSD, cantidadUSD, tasaCOP, 'COP');
    };
    copOptionLabel.appendChild(copRadio);
    copOptionLabel.appendChild(document.createTextNode(' COP'));
    currencyOptionsDiv.appendChild(copOptionLabel);

    // Opción USD (marcado por defecto)
    const usdOptionLabel = document.createElement('label');
    usdOptionLabel.classList.add('currency-option');
    const usdRadio = document.createElement('input');
    usdRadio.type = 'radio';
    usdRadio.name = 'currency';
    usdRadio.value = 'USD';
    usdRadio.onclick = function() {
      convertirMoneda(precioUnitarioUSD, cantidadUSD, 1, 'USD'); // Tasa de cambio USD a USD es 1
    };
    usdRadio.checked = true;
    usdOptionLabel.appendChild(usdRadio);
    usdOptionLabel.appendChild(document.createTextNode(' USD'));
    currencyOptionsDiv.appendChild(usdOptionLabel);

    return currencyOptionsDiv;
  }

  function convertirMoneda(precioUnitarioUSD, cantidadV, tasaCambio, moneda) {
    let dc = precioUnitarioUSD * cantidadV;
    console.log(dc);
    const inputMapr = document.getElementById('mapr');
    const tasaEx = document.getElementById('tasaEx');
    const valorConvertido = (dc * tasaCambio).toFixed(2);

    inputMapr.textContent = `${valorConvertido} ${moneda}`;
    tasaEx.textContent = `Tasa de cambio: 1 USD = ${tasaCambio} ${moneda}`;
  }

  function mostrarDatosDePago(metodo) {
    const paymentTitle = document.getElementById('paymentTitle');
    const paymentDetails = document.getElementById('paymentDetails');
    switch (metodo) {
      case 'zelle':
        paymentTitle.textContent = 'ZELLE';
        paymentDetails.innerHTML = `
            <p class="subtitle">Datos de la cuenta</p>
            <p>Número de teléfono: +1 4074287580</p>
          `;
        break;

      case 'paypal':
        paymentTitle.textContent = 'PAYPAL';
        paymentDetails.innerHTML = `
            <p class="subtitle">Datos de la cuenta</p>
            <p>Nombre: Yorsin Cruz Osorio</p>
            <p>Usuario: @Yorsin0506</p>
          `;
        break;

      case 'banco_venezuela':
        paymentTitle.textContent = 'BANCO DE VENEZUELA';
        paymentDetails.innerHTML = `
            <p class="subtitle">Datos de la cuenta</p>
            <p>Nombre: Mailiny Cruz</p>
            <p>Cédula de identidad: 28517267</p>
            <p>Numero de cuenta: 01021234567891234567</p>
          `;
        break

      case 'davivienda':
        paymentTitle.textContent = 'DAVIVIENDA COLOMBIA';
        paymentDetails.innerHTML = `
            <p class="subtitle">Datos de la cuenta</p>
            <p>Tipo de cuenta: Ahorros</p>
            <p>Numero de cuenta: 4884 5018 1679</p>

          `;
        break;

      case 'pago_movil':
        paymentTitle.textContent = 'PAGO MOVIL';
        paymentDetails.innerHTML = `
              <p class="subtitle">Datos de la cuenta</p>
              <p>Número de teléfono: 04124124923</p>
              <p>Cédula de identidad: 28517267</p>
              <p>Banco: 0102 - Banco de Venezuela</p>  
            `;
        break;
      default:
        paymentTitle.textContent = 'Seleccione un método de pago';
        paymentDetails.innerHTML = '';
        break;
    }
  }
</script>
<link rel="stylesheet" href="assets/css/dropdown-search-method.css">

<head>
  <link rel="stylesheet" href="/assets/css/payment.css">
  <script src="/assets/js/payment.js"></script>
</head>