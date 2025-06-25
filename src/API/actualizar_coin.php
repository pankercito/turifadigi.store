<?php
// Fetching JSON
header('Content-Type: application/json');

use App\Controllers\MonedaController;

// $url = 'https://ve.dolarapi.com/v1/dolares/paralelo';

// Obtener el contenido JSON de la URL
// $json_data = @file_get_contents($url);

// $paralelo = $json_data;
// TU CLAVE DE API (la has omitido por seguridad, asegúrate de que sea correcta)
$api_key = '97646d87748b27bb76ec3773';
$COP_URL = "https://v6.exchangerate-api.com/v6/{$api_key}/pair/USD/COP/1";
$VES_URL = "https://v6.exchangerate-api.com/v6/{$api_key}/pair/USD/VES/1";
$responsecop_json = file_get_contents($COP_URL);
$responseves_json = file_get_contents($VES_URL);

$monedamodel = new MonedaController();

// Continuando si obtuvimos un resultado
if (false !== $responsecop_json && false !== $responseves_json) {
    // Try/catch para la operacion json_decode
    try {
        // Decodificando
        // $responseves = json_decode($paralelo);
        $responseves = json_decode($responseves_json);
        $responsecop = json_decode($responsecop_json);

        // Comprobar si fue exitoso
        if ('success' === $responsecop->result && 'success' === $responseves->result) {

            // TU CODIGO DE APLICACION AQUI, e.g.
            $base_price_usd = 1; // Tu precio en USD
            // $ves_price = ($base_price_usd * $responseves->promedio);
            $ves_price = ($base_price_usd * $responseves->conversion_result);
            $cop_price = ($base_price_usd * $responsecop->conversion_result);

            try {
                $monedamodel->actualizarPrecio(["VES", "COP"], [$ves_price, $cop_price]);

                // Mostrar el resultado en la pagina
                $response = array(
                    "message" => "Se actualizó en base de datos.",
                    "USD_VES" => $base_price_usd . " USD es aproximadamente: " . $ves_price . " VES",
                    "USD_COP" => $base_price_usd . " USD es aproximadamente: " . $cop_price . " COP"
                );

                echo json_encode($response);
                // require_once __DIR__ . '/../../views/errors/201.php';
            } catch (Exception $th) {
                echo "error al cargar en base de datos: " . $th;
            }
        } else {
            // Manejar el caso de error de la API
            echo "Error al obtener el tipo de cambio"; // Asegurate de que 'error' sea el campo correcto
        }
    } catch (Exception $e) {
        // Manejar el error de parseo JSON
        echo "Error al procesar la respuesta de la API: " . $e->getMessage();
    }
} else {
    // Manejar el error al obtener datos de la API
    echo "Error al conectar con la API de tipo de cambio.";
}
