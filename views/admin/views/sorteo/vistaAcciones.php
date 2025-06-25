<?php

use App\Controllers\SorteoController;

$idC = $_GET['acvi'] ?? null;

if (!isset($idC)) {
  echo '<div class="alerta danger">Error: ID de sorteo no obtenido.</div>';
  exit;
}

$id = intval($idC);
$controller = new SorteoController();

$response = $controller->obtenerSorteo($id);

$data = $response["data"][0];

if ($response["success"] == null) {
  echo '
  <div class="alerta danger">Error al obtener los datos del sorteo.</div>';
  exit;
}

$id_configuracion = $data["id_configuracion"] ?? '';
$id_usuario = $data["usuario"] ?? '';
$titulo = $data["titulo"] ?? '';
$fecha_inicio = $data["fecha_inicio"] ?? '';
$fecha_final = $data["fecha_final"] ?? '';
$precio_boleto = $data["precio_boleto"] ?? '';
$boletos_minimos = $data["boletos_minimos"] ?? '';
$boletos_maximos = $data["boletos_maximos"] ?? '';
$numero_contacto = $data["numero_contacto"] ?? '';
$url_rifa = $data["url_rifa"] ?? '';
$texto_ejemplo = $data["texto_ejemplo"] ?? '';

$estado = '';

if ($data['estado'] == 1) {
  $estado = 'activo';
} else if ($data['estado'] == 2) {
  $estado = 'finalizado';
} elseif ($data['estado'] == 0 || $data['estado'] === '' || $data['estado'] === null || $data['estado'] === 'undefined') {
  $estado = 'desactivado';
}

?>
<div class="raffle-info-card">
  <h4 class="raffle-info-title">
    <i class="fas fa-ticket-alt"></i>
    Información de la Rifa
  </h4>
  <div class="raffle-info-grid">
    <div class="raffle-info-item">
      <span class="raffle-info-label">Estado</span>
      <span class="raffle-info-value">
        <span class="raffle-status raffle-status-<?php echo htmlspecialchars($estado); ?>">
          <?php echo htmlspecialchars(ucfirst($estado)); ?>
        </span>
      </span>
    </div>
    <div class="raffle-info-item">
      <span class="raffle-info-label">ID Usuario</span>
      <span class="raffle-info-value"><?php echo htmlspecialchars($id_usuario); ?></span>
    </div>
    <div class="raffle-info-item">
      <span class="raffle-info-label">Título</span>
      <span class="raffle-info-value"><?php echo htmlspecialchars($titulo); ?></span>
    </div>
    <div class="raffle-info-item">
      <span class="raffle-info-label">Fecha de inicio</span>
      <span class="raffle-info-value"><?php echo htmlspecialchars($fecha_inicio); ?></span>
    </div>
    <div class="raffle-info-item">
      <span class="raffle-info-label">Fecha final</span>
      <span class="raffle-info-value"><?php echo htmlspecialchars($fecha_final); ?></span>
    </div>
    <div class="raffle-info-item">
      <span class="raffle-info-label">Precio por boleto</span>
      <span class="raffle-info-value"><?php echo htmlspecialchars($precio_boleto); ?> $</span>
    </div>
    <div class="raffle-info-item">
      <span class="raffle-info-label">Boletos mínimos</span>
      <span class="raffle-info-value"><?php echo htmlspecialchars($boletos_minimos); ?></span>
    </div>
    <div class="raffle-info-item">
      <span class="raffle-info-label">Boletos máximos</span>
      <span class="raffle-info-value"><?php echo htmlspecialchars($boletos_maximos); ?></span>
    </div>
    <div class="raffle-info-item">
      <span class="raffle-info-label">Número de contacto</span>
      <span class="raffle-info-value"><?php echo htmlspecialchars($numero_contacto); ?></span>
    </div>
    <div class="raffle-info-item">
      <span class="raffle-info-label">URL de la rifa</span>
      <span class="raffle-info-value"><?php echo htmlspecialchars($url_rifa); ?></span>
    </div>
    <div class="raffle-info-item raffle-info-item-full">
      <span class="raffle-info-label">Texto ejemplo</span>
      <span class="raffle-info-value">
        <textarea class="raffle-info-textarea" disabled><?php echo htmlspecialchars($texto_ejemplo); ?></textarea>
      </span>
    </div>
  </div>
</div>

<style>
  .raffle-info-card {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 2px 16px rgba(0, 0, 0, 0.07);
    padding: 32px 28px 24px 28px;
    max-width: 700px;
    margin: 32px auto;
    font-family: 'Segoe UI', 'Arial', sans-serif;
  }

  .raffle-info-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 30px;
    display: flex;
    align-items: center;
    gap: 12px;
    letter-spacing: 0.5px;
    justify-content: center;
  }

  .raffle-info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 22px 32px;
  }

  .raffle-info-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
  }

  .raffle-info-item-full {
    grid-column: 1 / -1;
  }

  .raffle-info-label {
    font-size: 0.98rem;
    color: #718096;
    font-weight: 500;
    margin-bottom: 2px;
  }

  .raffle-info-value {
    font-size: 1.08rem;
    color: #22223b;
    background: #f7fafc;
    border-radius: 7px;
    padding: 8px 12px;
    border: 1px solid #e2e8f0;
    min-height: 36px;
    word-break: break-all;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .raffle-info-textarea {
    width: 100%;
    min-height: 60px;
    border: none;
    background: transparent;
    resize: vertical;
    color: #22223b;
    font-size: 1.08rem;
    font-family: inherit;
    padding: 0;
    outline: none;
  }

  .raffle-status {
    padding: 3px 14px;
    border-radius: 12px;
    font-size: 0.98rem;
    font-weight: 600;
  }

  .raffle-status-activo {
    background: #e6fffa;
    color: #319795;
    border: 1px solid #b2f5ea;
  }

  .raffle-status-desactivado {
    background: #fefcbf;
    color: #b7791f;
    border: 1px solid #faf089;
  }

  .raffle-status-finalizado {
    background: #fed7d7;
    color: #c53030;
    border: 1px solid #feb2b2;
  }

  @media (max-width: 700px) {
    .raffle-info-card {
      padding: 18px 6vw 18px 6vw;
    }

    .raffle-info-grid {
      grid-template-columns: 1fr;
      gap: 18px 0;
    }
  }
</style>

<link rel="stylesheet" href="/assets/css/datos_personales.css">
<?php if (!empty($_SESSION['mensaje'])) {
  echo $_SESSION['mensaje'];
  unset($_SESSION['mensaje']);
} ?>