<?php

@session_start();

use App\Controllers\BoletoController;

$idC = $_GET['acvi'] ?? null;
$idU = $_SESSION['id_usuario'] ?? null;

if (!isset($idC)) {
  echo '<div class="alerta danger">Error: ID de compra no obtenido.</div>';
  exit;
}

$id = intval($idC);
$controller = new BoletoController();

$response = $controller->obtenerCompra($id,);

$data = $response["data"][0];

if ($response["success"] == null) {
  echo '
  <div class="alerta danger">Error al obtener los datos de la compra.</div>';
  exit;
}

$titular = $data["titular"] ?? '';
$referencia = $data["referencia"] ?? '';
$monto_pagado = $data["monto_pagado"] ?? '';
$metodo_pago = $data["metodo_pago"] ?? '';
$montoa_pagar = $data["total"] ?? '';

?>

<div class="comprobante-container">
  <h4 class="comprobante-title" id="label-comprobante-title">
    <i class="fas fa-file-invoice"></i>
    <span id="payment-confirm-text"></span>
  </h4>
  <div class="comprobante-row">
    <div class="comprobante-group">
      <label class="comprobante-label" id="label-titular">Titular</label>
      <input type="text" class="comprobante-input" id="titular" disabled value="<?php echo htmlspecialchars($titular); ?>">
    </div>
    <div class="comprobante-group">
      <label class="comprobante-label" id="label-referencia">Referencia de pago</label>
      <input type="text" class="comprobante-input" id="referencia" disabled value="<?php echo htmlspecialchars($referencia); ?>">
    </div>
  </div>
  <div class="comprobante-group">
    <label class="comprobante-label" id="label-monto-pagar">Monto a pagar</label>
    <input type="text" class="comprobante-input" id="monto_pagar" disabled value="<?php echo htmlspecialchars($montoa_pagar); ?> $">
  </div>
  <?php
  function renderPaymentMethodDropdown($selectedMethod, $monto_pagado)
  {
    $methods = [
      "pago_movil" => "Pago Movil",
      "zelle" => "Zelle",
      "davivienda" => "Davivienda",
      "paypal" => "Paypal",
      "banco_venezuela" => "Banco de Venezuela",
      "bancolombia" => "Bancolombia"
    ];

    $icons = [
      "pago_movil" => "assets/img/svg/pago_movil.svg",
      "zelle" => "assets/img/svg/zelle.svg",
      "davivienda" => "assets/img/svg/davivienda.svg",
      "paypal" => "assets/img/svg/paypal.svg",
      "banco_venezuela" => "assets/img/svg/banco_venezuela.svg",
      "bancolombia" => "assets/img/svg/bancolombia.svg"
    ];

    $moneda = [
      "pago_movil" => "Bs",
      "zelle" => "$",
      "davivienda" => "COP",
      "paypal" => "$",
      "banco_venezuela" => "Bs",
      "bancolombia" => "COP"
    ];

    foreach ($methods as $key => $label):
      if ($key == $selectedMethod) {
  ?>
        <div class="comprobante-group">
          <label class="comprobante-label" id="label-monto-pagado" >Monto Pagado</label>
          <input type="text" class="comprobante-input" id="monto_pagado" disabled value="<?php echo htmlspecialchars($monto_pagado . " " . $moneda[$key]); ?>">
        </div>
        <div class="comprobante-group comprobante-method">
          <label class="comprobante-label" id="label-metodo" >Metodo de pago</label>
          <div class="comprobante-method-box">
            <img class="comprobante-icon" src="<?php echo htmlspecialchars($icons[$key]); ?>" alt="Logo <?php echo htmlspecialchars($label); ?>">
            <input type="text" class="comprobante-input comprobante-method-input" id="metodo_pago" disabled value="<?php echo htmlspecialchars($label); ?>">
          </div>
        </div>
  <?php
      }
    endforeach;
  }

  renderPaymentMethodDropdown($metodo_pago, $monto_pagado);
  ?>
</div>

<style>
  .comprobante-container {
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 4px 24px 0 rgba(44, 62, 80, 0.10);
    padding: 32px 28px 24px 28px;
    max-width: 540px;
    margin: 20px auto;
    font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
    color: #222;
  }

  .comprobante-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2d6cdf;
    margin: 25px auto 28px auto;
    display: flex;
    align-items: center;
    gap: 10px;
    letter-spacing: 0.5px;
    justify-content: center;
  }

  .comprobante-row {
    display: flex;
    gap: 18px;
    margin-bottom: 18px;
  }

  .comprobante-group {
    flex: 1;
    display: flex;
    flex-direction: column;
    margin-bottom: 18px;
  }

  .comprobante-label {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 7px;
    color: #4a4a4a;
    letter-spacing: 0.2px;
  }

  .comprobante-input {
    background: #f5f8fa;
    border: 1.5px solid #e3e8ee;
    border-radius: 8px;
    padding: 12px 16px;
    font-size: 1rem;
    color: #222;
    transition: border 0.2s;
    outline: none;
    font-weight: 500;
  }

  .comprobante-input:disabled {
    background: #f5f8fa;
    color: #888;
    opacity: 1;
  }

  .comprobante-method {
    margin-bottom: 0;
  }

  .comprobante-method-box {
    display: flex;
    align-items: center;
    gap: 14px;
  }

  .comprobante-icon {
    width: 48px;
    height: 48px;
    object-fit: contain;
    background: #f5f8fa;
    border-radius: 8px;
    border: 1px solid #e3e8ee;
    padding: 6px;
  }

  .comprobante-method-input {
    flex: 1;
    margin-left: 0;
  }

  @media (max-width: 700px) {
    .comprobante-container {
      padding: 18px 8px 14px 8px;
      max-width: 98vw;
    }

    .comprobante-title {
      font-size: 1.1rem;
    }

    .comprobante-row {
      flex-direction: column;
      gap: 0;
    }

    .comprobante-icon {
      width: 50px;
      height: 47px;
    }
  }
</style>
<link rel="stylesheet" href="assets/css/dropdown-search-method.css">
<link rel="stylesheet" href="/assets/css/payment.css">
<link rel="stylesheet" href="/assets/css/datos_personales.css">