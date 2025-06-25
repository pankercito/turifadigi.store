<div class="form-section">
  <?php
  if (!@$_SESSION['usuario']) {
    require_once 'views/sorteo/datos_personales/miniregistro.php';
  } else {
    echo '<input type="text" name="regist" class="d-none" id="regist" value="0">';
  }
  ?>
  <h2 class="form-section-title">
    <i class="fas fa-file-invoice"></i>
    <span data-i18n="comprobante_title">COMPROBANTE DE PAGO</span>
    <i class="fas fa-file-invoice"></i>
  </h2>
  <div class="form-group-custom">
    <label class="required" data-i18n="comprobante_titular">Titular del pago (Telefono del titular del pago, si es Pago Movil)</label>
    <input type="text" class="form-control-custom" id="titular" placeholder="Nombre Apellido (o telefono)" data-i18n-placeholder="first_last_name_phone_placeholder" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || (event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122)">
  </div>

  <div class="form-group-custom">
    <label class="required" data-i18n="comprobante_referencia">Referen del pago</label>
    <input type="text" class="form-control-custom" id="referencia" placeholder="123456" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || (event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122)">
  </div>

  <div class="form-group-custom">
    <label class="required" data-i18n="comprobante_monto">Monto Pagado</label>
    <input type="text" class="form-control-custom" id="monto_pagado" placeholder="0.00" onkeypress="return (event.charCode >= 48 && event.charCode <= 57) || event.charCode == 46 || event.charCode == 44">
  </div>

  <div class="form-group-custom">
    <label data-i18n="comprobante_metodo">Método de pago</label>

    <div class="ui fluid selection dropdown dropdown-metodo-pago" style="display: block !important;">
      <input type="hidden" name="payment_method">
      <i class="dropdown icon"></i>
      <div class="default text" data-i18n="comprobante_metodo_seleccionar">Seleccione método de pago</div>
      <div class="menu" style="display: none;" data-silent="true">
        <div class="item" data-value="pago_movil">
          <img src="assets/img/svg/pago_movil.svg" alt="Logo Pago Movil" class="payment-icon">
          <span class="text" data-i18n="comprobante_metodo_pago_movil">Pago Movil</span>
        </div>
        <div class="item" data-value="zelle">
          <img src="assets/img/svg/zelle.svg" alt="Logo Zelle" class="payment-icon">
          <span class="text" data-i18n="comprobante_metodo_zelle">Zelle</span>
        </div>
        <div class="item" data-value="davivienda">
          <img src="assets/img/svg/davivienda.svg" alt="Logo Davivienda" class="payment-icon">
          <span class="text" data-i18n="comprobante_metodo_davivienda">Davivienda</span>
        </div>
        <div class="item" data-value="paypal">
          <img src="assets/img/svg/paypal.svg" alt="Logo Paypal" class="payment-icon">
          <span class="text" data-i18n="comprobante_metodo_paypal">Paypal</span>
        </div>
        <div class="item" data-value="banco_venezuela">
          <img src="assets/img/svg/banco_venezuela.svg" alt="Logo Banco de Venezuela" class="payment-icon">
          <span class="text" data-i18n="comprobante_metodo_banco_venezuela">Banco de Venezuela</span>
        </div>
        <div class="item" data-value="bancolombia">
          <img src="assets/img/svg/bancolombia.svg" alt="Logo Bancolombia" class="payment-icon">
          <span class="text" data-i18n="comprobante_metodo_bancolombia">Bancolombia</span>
        </div>
      </div>
    </div>
  </div>
</div>