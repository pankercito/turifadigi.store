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
<div class="form-section">
  <h4 class="form-section-title" data-i18n="comprobante_title">
    <i class="fas fa-file-invoice"></i>
    COMPROBANTE DE PAGO
  </h4>
  <div class="form-group-custom">
    <label class="required">Titular</label>
    <input type="text" class="form-control-custom" id="titular" disabled value="<?php echo htmlspecialchars($titular); ?>">
  </div>

  <div class="form-group-custom">
    <label class="required">Referencia de pago</label>
    <input type="text" class="form-control-custom" id="referencia" disabled value="<?php echo htmlspecialchars($referencia); ?>">
  </div>

  <div class="form-group-custom">
    <label class="required">Monto a pagar</label>
    <input type="text" class="form-control-custom" id="monto_pagar" disabled value="<?php echo htmlspecialchars($montoa_pagar); ?> $">
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

  ?>
    <?php foreach ($methods as $key => $label):
      if ($key == $selectedMethod) {
    ?>
        <div class="form-group-custom">
          <label class="required">Monto Pagado</label>
          <input type="text" class="form-control-custom" id="monto_pagado" disabled value="<?php echo htmlspecialchars($monto_pagado . " " . $moneda[$key]); ?>">
        </div>
        <div class="form-group-custom input-con-icono">
          <label class="required">Metodo de pago</label>
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