<?php

use App\Controllers\SorteoController;

$sorteoController = new SorteoController();
$sorteo = $sorteoController->obtenerSorteoActivo();

function getTextoByLang($jsonText, $idioma = 'ES')
{
  if (!$jsonText) return '';
  if (is_array($jsonText)) {
    $idioma = strtoupper($idioma);
    $text = $jsonText[$idioma] ?? $jsonText['ES'] ?? $jsonText['EN'] ?? '';
    // Reemplaza secuencias escapadas y saltos reales por un espacio
    $text = preg_replace('/\\\\\\
|\\\
|\\\/', ' ', $text); // secuencias escapadas
    $text = preg_replace("/\r\n|\n|\r/", " ", $text); // saltos reales
    return $text;
  }
  // LIMPIA el string si viene con comillas escapadas
  $jsonText = trim($jsonText, "\"");
  $jsonText = stripslashes($jsonText);

  $arr = json_decode($jsonText, true);
  if (is_array($arr)) {
    $idioma = strtoupper($idioma);
    $text = $arr[$idioma] ?? $arr['ES'] ?? $arr['EN'] ?? '';
    // Reemplaza secuencias escapadas y saltos reales por un espacio
    $text = preg_replace('/\\\\\\
|\\\
|\\\/', ' ', $text); // secuencias escapadas
    $text = preg_replace("/\r\n|\n|\r/", " ", $text); // saltos reales
    return $text;
  }
  return '';
}

$idioma = 'ES';
if (isset($_COOKIE['language'])) {
  $idioma = strtoupper($_COOKIE['language']);
}
if (isset($_GET['lang'])) {
  $idioma = strtoupper($_GET['lang']);
}
if (!in_array($idioma, ['ES', 'EN'])) {
  $idioma = 'ES';
}

// Obtener texto traducido para el título
$titulo = 'Título no disponible';
if ($idioma === 'EN') {
  $titulo = 'Title not available';
}

$descripcion = '';
$textoImportante = '';
$premiosMulti = [];
$precioBoleto = '';
$boletosMinimos = '';
$urlRifa = '';
$numeroContacto = '';

if ($sorteo['success'] && isset($sorteo['data'])) {
  // Título y descripción general
  $titulo = getTextoByLang($sorteo['data']['titulo'] ?? '', $idioma);
  $descripcion = getTextoByLang($sorteo['data']['descripcion'] ?? '', $idioma);
  $textoImportante = getTextoByLang($sorteo['data']['texto_importante'] ?? '', $idioma);

  // Datos de configuración
  if (isset($sorteo['data']['configuracion'])) {
    $precioBoleto = $sorteo['data']['configuracion']['precio_boleto'] ?? '1';
    $boletosMinimos = $sorteo['data']['configuracion']['boletos_minimos'] ?? '1';
  }
  $urlRifa = $sorteo['data']['url_rifa'] ?? '';
  $numeroContacto = $sorteo['data']['numero_contacto'] ?? '';

  // Premios multilenguaje
  if (isset($sorteo['data']['premios']['nombres'], $sorteo['data']['premios']['descripciones'])) {
    $nombres = $sorteo['data']['premios']['nombres'];
    $descripciones = $sorteo['data']['premios']['descripciones'];
    foreach ($nombres as $i => $nombre) {
      $premiosMulti[] = [
        'nombre' => getTextoByLang($nombre, $idioma),
        'descripcion' => getTextoByLang($descripciones[$i] ?? '', $idioma)
      ];
    }
  }
}
?>
<link rel="stylesheet" href="assets/css/premio.css">

<main class="supergana-section" role="main">
  <div class="container">
    <div class="row">
      <div class="col-xl-12">
        <article class="supergana-content text-center">

          <h1 class="section-title__title"><?php echo htmlspecialchars($titulo); ?></h1>
          <?php if ($descripcion): ?>
            <div class="mb-4" style="font-size:1.1em;"><strong><?php echo nl2br(htmlspecialchars($descripcion)); ?></strong></div>
          <?php endif; ?>

          <div class="prize-details">
            <div class="alert alert-info mb-4" role="alert">
              <strong data-i18n="80_percent">Al completarse el 80% juega nuestra rifa</strong>
            </div>

            <section class="lottery-info mb-4" aria-label="Información básica">
              <p data-i18n="play_supergana">📍 Juega por la lotería de SuperGana</p>
              <p data-i18n="price_ticket">🎟️ Valor del boleto: <span class="price">$<?php echo htmlspecialchars((string)$precioBoleto); ?></span></p>
              <p data-i18n="minimum_tickets">🎟️ Compra mínima: <span class="min-tickets"><?php echo htmlspecialchars((string)$boletosMinimos); ?> boletos</span> en adelante</p>
            </section>

            <section class="prize-list mb-4" aria-label="Lista de premios">
              <?php foreach ($premiosMulti as $premio): ?>
                <div class="prize-item" role="article">
                  <h2><?php echo htmlspecialchars($premio['nombre']); ?></h2>
                  <div class="premio-descripcion">
                    <?php echo nl2br(htmlspecialchars($premio['descripcion'])); ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </section>

            <section class="date-info mb-4" aria-label="Fecha del sorteo">
              <h2 data-i18n="when_raffle_plays">🗓️ ¿Cuándo se juega la rifa?</h2>
              <p data-i18n="when_raffle_plays_desc">La fecha del sorteo será anunciada una vez se alcance el 80% de los boletos vendidos</p>
            </section>

            <section class="official-link mb-4" aria-label="Enlace oficial">
              <h2 data-i18n="official_link">🔗 Enlace oficial para seguir el sorteo:</h2>
              <a href="<?php echo htmlspecialchars((string)$urlRifa); ?>" target="_blank" class="thm-btn" rel="noopener">
                LOTERIA OFICIAL <i class="fas fa-external-link-alt" aria-hidden="true"></i>
                <span class="sr-only">(se abre en una nueva ventana)</span>
              </a>
            </section>

            <section class="contact-info mb-4" aria-label="Información de contacto">
              <h2 data-i18n="contact_number">📞 Número de contacto:</h2>
              <p><a href="tel:<?php echo htmlspecialchars((string)$numeroContacto); ?>" class="phone-number">
                  <strong>+1 <?php echo htmlspecialchars((string)$numeroContacto); ?></strong>
                </a></p>
            </section>
            <!-- <?php if ($textoImportante): ?> -->
            <!--   <aside class="example-box" role="complementary"> -->
            <!--     <h2>Información importante</h2> -->
            <!--     <p class="example-text"><?php echo nl2br(htmlspecialchars($textoImportante)); ?></p> -->
            <!--   </aside> -->
            <!-- <?php endif; ?> -->
          </div>
        </article>
      </div>
    </div>
  </div>
</main>