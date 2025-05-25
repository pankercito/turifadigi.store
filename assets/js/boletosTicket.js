/**
 * Genera dinámicamente el HTML de un boleto y lo inserta en el contenedor.
 * @param {Object} data - Datos del boleto.
 * @param {Object} data.items - Objeto con pares clave/valor para los divs .item (ej: {nombre: "victor", telefono: "123456"})
 * @param {string} data.fecha_compra - Fecha de compra.
 * @param {string} data.numero - Número del boleto.
 * @param {string} data.id_boleto - ID para el código de barras.
 */
function renderBoleto(data) {
    const container = document.getElementById('boletoContainer');
    if (!container) {
        console.error("Error: El contenedor 'boletoContainer' no fue encontrado.");
        return;
    }

    // Genera los items dinámicamente
    const itemsHtml = Object.entries(data.items).map(
        ([label, value]) => `
            <div class="item">
                <span class="label">${label.charAt(0).toUpperCase() + label.slice(1)}</span>
                <span class="value">${value}</span>
            </div>`
    ).join('');

    const ganador = data.ganador ? `<p class="subabel win" data-i18n="winning_ticket">Boleto Ganador</p>` : "";
    const fondogan = data.ganador ? 'style="background: #007bff6e"' : "";

    // Crea un elemento contenedor para el boleto
    const boletoDiv = document.createElement('div');
    boletoDiv.className = 'raffle-ticket-wrapper';
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
                <span class="label" data-i18n="purchase_date">Fecha de compra:</span>
                <span class="value">${data.fecha_compra}</span>
                </div>
                </div>
                <div class="raffle-ticket-separator"></div>
                <div class="raffle-ticket-bottom" ${fondogan}>
                ${ganador}
                <p class="ticket-number">Nº ${data.numero}</p>
                <div class="barcode">
                    <img id="barcode_${data.id_boleto}" alt="Código de Barras">
                    <p class="barcode-text win"><span data-i18n="ticket_id">ID Boleto:</span> ${data.id_boleto}</p>
                </div>
            </div>
        </div>
    `;

    container.appendChild(boletoDiv);

    // Verificar y traducir el contenido del boleto
    // console.log("Verificando elementos con data-i18n antes de la traducción:");
    const i18nElements = boletoDiv.querySelectorAll('[data-i18n]');
    i18nElements.forEach(el => {
        // console.log(`Elemento encontrado: ${el.tagName}, Atributo data-i18n: ${el.getAttribute('data-i18n')}`);
    });

    if (typeof i18n.translatePage === 'function') {
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
                displayValue: false
            });
        } else {
            console.error(`Error: Elemento con ID barcode_${data.id_boleto} no encontrado para generar el código de barras.`);
        }
    });
}
