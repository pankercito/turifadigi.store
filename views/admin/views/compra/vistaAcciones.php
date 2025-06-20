<?php

use App\Controllers\BoletoController;

$idC = $_GET['acvi'] ?? null;

if (!isset($idC)) {
  echo '<div class="alerta danger">Error: ID de compra no obtenido.</div>';
  exit;
}

$id = intval($idC);
$controller = new BoletoController();

$response = $controller->obtenerCompra($id);

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
<div class="form-section">
  <h4 class="form-section-title" data-i18n="comprobante_title">
    <i class="fas fa-file-invoice"></i>
    <span id="comprobante-title-text">COMPROBANTE DE PAGO</span>
  </h4>
  <div class="form-row-custom">
    <div class="form-group-custom" style="flex: 1; margin-right: 10px;">
      <label class="required" id="label-titular">Titular</label>
      <input type="text" class="form-control-custom" id="titular" disabled value="<?php echo htmlspecialchars($titular); ?>">
    </div>
    <div class="form-group-custom" style="flex: 1;">
      <label class="required" id="label-referencia">Referencia de pago</label>
      <input type="text" class="form-control-custom" id="referencia" disabled value="<?php echo htmlspecialchars($referencia); ?>">
    </div>
  </div>
  <div class="form-group-custom">
    <label class="required" id="label-monto-pagar">Monto a pagar</label>
    <input type="text" class="form-control-custom" id="monto_pagar" disabled value="<?php echo htmlspecialchars($montoa_pagar); ?> $">
  </div>
  <style>
    .form-row-custom {
      display: flex;
      gap: 10px;
      width: 100%;
    }

    @media (max-width: 600px) {
      .form-row-custom {
        flex-direction: column;
        gap: 0;
      }

      .form-row-custom .form-group-custom {
        margin-right: 0 !important;
      }
    }
  </style>

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
      "zelle" => "$",
      "paypal" => "$",
      "pago_movil" => "BS",
      "banco_venezuela" => "BS",
      "davivienda" => "COP",
      "bancolombia" => "COP"
    ];

  ?>
    <?php foreach ($methods as $key => $label):
      if ($key == $selectedMethod) {
    ?>
        <div class="form-group-custom">
          <label class="required" id="label-monto-pagado">Monto Pagado</label>
          <input type="text" class="form-control-custom" id="monto_pagado" disabled value="<?php echo htmlspecialchars($monto_pagado . " " . $moneda[$key]); ?>">
        </div>
        <div class="form-group-custom input-con-icono">
          <label class="required" id="label-metodo-pago">Metodo de pago</label>
          <img class="input-con-icono icono" src="<?php echo htmlspecialchars($icons[$key]); ?>" alt="Logo <?php echo htmlspecialchars($label); ?>">
          <input type="text" class="form-control-custom" id="metodo_pago" disabled value="<?php echo htmlspecialchars($label); ?>">
        </div>
        <style>
          .input-con-icono {
            position: relative;
          }

          .input-con-icono input {
            padding-left: 75px;
            /* Espacio para que la imagen no tape el texto */
            width: 100%;
            box-sizing: border-box;
            /* Incluye el padding en el ancho total */
          }

          .input-con-icono .icono {
            position: absolute;
            top: 70%;
            padding: 0 0 0 10px;
            transform: translateY(-50%);
            width: 65px;
            /* height: 65px; */
          }
        </style>
  <?php
      }
    endforeach;
  }

  renderPaymentMethodDropdown($metodo_pago, $monto_pagado);
  ?>

</div>
<link rel="stylesheet" href="assets/css/dropdown-search-method.css">

<head>
  <link rel="stylesheet" href="/assets/css/payment.css">
  <link rel="stylesheet" href="/assets/css/datos_personales.css">
</head>