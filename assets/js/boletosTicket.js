/**
 * Genera dinámicamente el HTML de un boleto y lo inserta en el contenedor.
 * @param {Object} data - Datos del boleto.
 * @param {Object} data.items - Objeto con pares clave/valor para los divs .item (ej: {nombre: "victor", telefono: "123456"})
 * @param {string} data.fecha_compra - Fecha de compra.
 * @param {string} data.numero - Número del boleto.
 * @param {string} data.id_boleto - ID para el código de barras.
 */
function renderBoleto(data) {
  const container = document.getElementById("boletoContainer");
  if (!container) {
    console.error("Error: El contenedor 'boletoContainer' no fue encontrado.");
    return;
  }

  // Genera los items dinámicamente
  const itemsHtml = Object.entries(data.items)
    .map(
      ([label, value]) => `
            <div class="item">
                <span class="label">${
                  label.charAt(0).toUpperCase() + label.slice(1)
                }</span>
                <span class="value">${value}</span>
            </div>`
    )
    .join("");

<<<<<<< HEAD
  const ganador = data.ganador
    ? `<p class="subabel win" data-i18n="winning_ticket">Boleto Ganador</p>`
    : "";
  const fondogan = data.ganador ? 'style="background: #007bff6e"' : "";
=======




    const ganador = (data.ganador === "premio1" || data.ganador === "premio2" || data.ganador === "premio3") ? `<p class="subabel win" data-i18n="winning_ticket">Boleto Ganador</p>` : "";
    const fondogan = (data.ganador === "premio1" || data.ganador === "premio2" || data.ganador === "premio3") ? 'style="background: #007bff9e"' : "";

    let premio = "";
    if (data.ganador === "premio1" || data.ganador === "premio2" || data.ganador === "premio3") {
        let premioLabel = "";
        switch (data.ganador) {
            case "premio1":
                premioLabel = `<span data-i18n="first_price" style="display:inline-block;padding:4px 12px;border-radius:18px;border:2px solid #FFD700;background:#fff8dc;color:#bfa100;font-weight:bold;box-shadow:0 2px 6px #ffd70044;letter-spacing:1px;">Primer Premio</span>`;
                break;
            case "premio2":
                premioLabel = `<span data-i18n="second_price" style="display:inline-block;padding:4px 12px;border-radius:18px;border:2px solid #C0C0C0;background:#f8f8ff;color:#6e6e6e;font-weight:bold;box-shadow:0 2px 6px #c0c0c044;letter-spacing:1px;">Segundo Premio</span>`;
                break;
            case "premio3":
                premioLabel = `<span data-i18n="third_price" style="display:inline-block;padding:4px 12px;border-radius:18px;border:2px solid #CD7F32;background:#fff8f0;color:#8c4c14;font-weight:bold;box-shadow:0 2px 6px #cd7f3244;letter-spacing:1px;">Tercer Premio</span>`;
                break;
            default:
                premioLabel = "";
        }
        premio = `<p class="subabel win" style="margin-top: 0px;">${premioLabel}</p>`;
    }
>>>>>>> d4b66c1688faa1092a5fd9280ffa33175827a234

  // Crea un elemento contenedor para el boleto
  const boletoDiv = document.createElement("div");
  boletoDiv.className = "raffle-ticket-wrapper";
  boletoDiv.innerHTML = `
        <div class="raffle-ticket-container">
            <div class="raffle-ticket-top">
                <div class="logo-container">
                    <img src="assets/img/webp/TuRifadigi.webp" alt="Logo de la Rifa">
                </div>
                <h2 class="raffle-name" data-i18n="turifadigital">Tu Rifa Digital</h2>
                <p class="subabel" data-i18n="ticket_details">Detalles de Boleto</p>
                ${itemsHtml}
                <div class="reference">
                <span class="label" ="purchase_date">Fecha de compra:</span>
                <span class="value">${data.fecha_compra}</span>
                </div>
                </div>
                <div class="raffle-ticket-separator"></div>
                <div class="raffle-ticket-bottom" ${fondogan}>
                ${ganador}
                ${premio}
                <p class="ticket-number">Nº ${data.numero}</p>
                <div class="barcode">
                    <div class="p-2" id="barcode_${data.id_boleto}" alt="qr"></div>
                    <p class="barcode-text win"><span data-i18n="raffle_id">ID Rifa:</span> ${data.id_rifa}</p>
                    </div>
                    </div>
                    </div>
                    `;
    // <p class="barcode-text win"><span data-i18n="ticket_id">ID Boleto:</span> ${data.id_boleto}</p>

  container.appendChild(boletoDiv);

  // Verificar y traducir el contenido del boleto
  // console.log("Verificando elementos con data-i18n antes de la traducción:");
  const i18nElements = boletoDiv.querySelectorAll("[data-i18n]");
  i18nElements.forEach((el) => {
    // console.log(`Elemento encontrado: ${el.tagName}, Atributo data-i18n: ${el.getAttribute('data-i18n')}`);
  });

  if (typeof i18n.translatePage === "function") {
    // console.log("i18n.translatePage es una función válida, ejecutando traducción...");
    i18n.translatePage();
  } else {
    console.error("Error: i18n.translatePage no es una función válida");
  }

  // Genera el código de barras cuando el DOM esté listo
  setTimeout(() => {
    const barcodeElement = document.getElementById(`barcode_${data.id_boleto}`);
    if (barcodeElement) {
      JsBarcode(barcodeElement, data.id_boleto, {
        format: "CODE128",
        lineColor: "#2962ff",
        width: 5,
        height: 100,
        displayValue: false,
      });
    } else {
      console.error(
        `Error: Elemento con ID barcode_${data.id_boleto} no encontrado para generar el código de barras.`
      );
    }
<<<<<<< HEAD
  });
=======

    // Genera el código de barras cuando el DOM esté listo
    setTimeout(() => {
        const barcodeElement = document.getElementById(`barcode_${data.id_boleto}`);


        var options_object = {
            // ====== Basic
            text: "turifadigital.org/boletos?boleto=" + data.id_rifa + "-" + data.id_boleto, // El texto que se codificará en el código QR
            width: 90,
            height: 90,
            colorDark: "#007bff",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H,
        };

        var qrcode = new QRCode(barcodeElement, options_object);

        // if (barcodeElement) {
        //     JsBarcode(barcodeElement, "ID-" + data.id_boleto, {
        //         format: "CODE128",
        //         lineColor: "#2962ff",
        //         width: 5,
        //         height: 100,
        //         displayValue: false
        //     });
        // } else {
        //     console.error(`Error: Elemento con ID barcode_${data.id_boleto} no encontrado para generar el código de barras.`);
        // }
    });
>>>>>>> d4b66c1688faa1092a5fd9280ffa33175827a234
}
