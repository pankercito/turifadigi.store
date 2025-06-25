<style>
    .container-fluid {
        margin-top: 100px;
    }

    .verificar-form {
        display: flex;
        margin-bottom: 30px;
        justify-content: center;
    }

    .input-boleto {
        width: 20%;
        padding: 8px 12px;
        font-size: 1em;
        border-radius: 6px;
        border: 1px solid #ccc;
        margin-right: 8px;
    }

    .btn-verificar {
        padding: 8px 18px;
        font-size: 1em;
        border-radius: 6px;
        border: none;
        background: #2d7a2d;
        color: #fff;
        cursor: pointer;
    }

    .btn-verificar:hover {
        background: #256025;
    }

    @media (max-width: 620px) {
        .input-boleto {
            width: 55%;
            padding: 8px 12px;
            font-size: 1em;
            border-radius: 6px;
            border: 1px solid #ccc;
            margin-right: 8px;
        }
    }

    .section-title__title.center {
        margin: 20px;
        display: flex;
        justify-content: center;
    }
</style>

<div class="container-fluid">
    <div class="section-title__title center" data-i18n="ticket_details">
        ticket details
    </div>
    <div id="boletoContainer"></div>
</div>

<script>
    const urlParams = new URLSearchParams(window.location.search);
    const params = {};
    for (const [key, value] of urlParams.entries()) {
        params[key] = value;
    }

    let entries = params.boleto.split('-');
    const idRifa = entries[0] ? entries[0].trim() : '';
    const numeroBoleto = entries[1] ? entries[1].trim() : '';

    console.log("Parámetros de URL:", idRifa, numeroBoleto);

    document.addEventListener('DOMContentLoaded', async function(e) {

        const container = document.getElementById('boletoContainer');

        container.innerHTML = i18n.t("searching");

        let url = '/api/get_tickets?id_rifa=' + encodeURIComponent(idRifa) + '&id_boleto=' + encodeURIComponent(numeroBoleto);

        try {
            const response = await fetch(url, {
                method: "POST",
            });
            const data = await response.json();

            // Si no hay datos reales, muestra el boleto de ejemplo y un mensaje
            if (!data.success || !data.data || data.data.length === 0) {
                console.log("No llegaron datos reales desde la API, mostrando boleto de ejemplo.");
                container.innerHTML = `
                <div style="color:red; margin-bottom:10px; text-align:center;">
                    <span data-i18n="ticket_example">Ticket Example</span>
                </div>
                `;

                setTimeout(() => {
                    const aviso = document.createElement('span');
                    aviso.style.color = 'orange';
                    aviso.style.display = 'block';
                    aviso.style.textAlign = 'center';
                    aviso.style.marginTop = '10px';
                    aviso.style.marginBlock = '10px';
                    aviso.innerHTML = `${i18n.t("ticket_example_notice")}`;
                    container.appendChild(aviso);
                }, 400);

                // Generar datos aleatorios de ejemplo
                setTimeout(() => {
                    renderBoleto({
                        items: {
                            [`${i18n.t("ticket_name")}`]: "Juan Perez",
                            [`${i18n.t("ticket_phone")}`]: "123456789",
                            [`${i18n.t("ticket_price")}`]: "100$",
                            [`${i18n.t("ticket_state")}`]: "Activo",
                        },
                        fecha_compra: "20/05/2025 14:30",
                        numero: "0000",
                        id_boleto: "0000",
                        id_rifa: "0000",
                        wnerr: "0000",
                    });
                }, 1000);

                return;
            }

            // Si buscas un boleto específico, muestra solo el primero
            const boleto = numeroBoleto ? data.data : null;

            setTimeout(() => {
                if (boleto) {
                    container.innerHTML = "";
                    renderBoleto({
                        items: {
                            [`${i18n.t("ticket_name")}`]: boleto?.cliente != null ? boleto.cliente + " " + boleto.a_cliente : i18n.t("no_purchases"),
                            [`${i18n.t("ticket_phone")}`]: boleto?.telefono != null ? boleto.telefono : i18n.t("no_purchases"),
                            [`${i18n.t("ticket_price")}`]: boleto?.precio_boleto != null ? boleto.precio_boleto + "$" : i18n.t("no_purchases"),
                            [`${i18n.t("ticket_state")}`]: boleto?.estado != null ? boleto.estado : i18n.t("no_purchases"),
                        },
                        fecha_compra: boleto?.fecha_compra ? boleto.fecha_compra : i18n.t("no_purchases"),
                        numero: boleto?.numero_boleto ? boleto.numero_boleto : "0000",
                        id_boleto: boleto?.id_boleto ? boleto.id_boleto : "0000",
                        id_rifa: boleto?.id_rifa ? boleto.id_rifa : "0000",
                        ganador: boleto?.boleto_es ? boleto.boleto_es : "0000",
                    });
                } else if (data.data && data.data.length > 0) {
                    // Si buscas todos los boletos de la rifa, puedes listarlos aquí
                    container.innerHTML = data.data.map(boleto => `
                <div>${boleto.numero_boleto} - ${boleto.estado_boleto}</div>
            `).join('');
                }
            }, 400);

        } catch (err) {
            container.innerHTML = '<div style="color:red;">Error de conexión con el servidor.</div>';
        }
    });
</script>