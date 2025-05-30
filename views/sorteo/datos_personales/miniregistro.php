<input type="text" name="regist" class="d-none" id="regist" value="1">
<h2 class="form-section-title" data-i18n="ingrese_sus_datos">
    INGRESE SUS DATOS
</h2>
<div style="display: flex; flex-wrap: wrap; gap: 16px;">
    <div style="flex: 1 1 200px; min-width: 200px;">
        <div class="form-group-custom">
            <label class="required" data-i18n="first_name">Nombre</label>
            <input type="text" class="form-control-custom" id="nombre" name="nombre" data-i18n-placeholder="first_name" placeholder="Nombre" required onkeypress="return (event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32)">
        </div>
    </div>
    <div style="flex: 1 1 200px; min-width: 200px;">
        <div class="form-group-custom">
            <label class="required" data-i18n="last_name">Apellido</label>
            <input type="text" class="form-control-custom" id="apellido" name="apellido" data-i18n-placeholder="last_name" placeholder="Apellido" required onkeypress="return (event.charCode >= 65 && event.charCode <= 90) || (event.charCode >= 97 && event.charCode <= 122) || (event.charCode == 32)">
        </div>
    </div>
</div>
<div class="form-group-custom">
    <label class="required" data-i18n="phone">Número de Teléfono</label>
    <div class="form-group-phone">
        <select class="form-control-custom select-sm" id="prefijo_pais" name="prefijo_pais" required style="display: block;">
            <option value="" data-i18n="country">País</option>
            <option value="+1">Estados Unidos (+1)</option>
            <option value="+1">Canadá (+1)</option>
            <option value="+52">México (+52)</option>
            <option value="+55">Brasil (+55)</option>
            <option value="+54">Argentina (+54)</option>
            <option value="+591">Bolivia (+591)</option>
            <option value="+56">Chile (+56)</option>
            <option value="+57">Colombia (+57)</option>
            <option value="+506">Costa Rica (+506)</option>
            <option value="+53">Cuba (+53)</option>
            <option value="+593">Ecuador (+593)</option>
            <option value="+503">El Salvador (+503)</option>
            <option value="+502">Guatemala (+502)</option>
            <option value="+509">Haití (+509)</option>
            <option value="+504">Honduras (+504)</option>
            <option value="+1-671">Guam (+1-671)</option>
            <option value="+1-876">Jamaica (+1-876)</option>
            <option value="+599">Antillas Neerlandesas (+599)</option>
            <option value="+505">Nicaragua (+505)</option>
            <option value="+507">Panamá (+507)</option>
            <option value="+595">Paraguay (+595)</option>
            <option value="+51">Perú (+51)</option>
            <option value="+1-787">Puerto Rico (+1-787)</option>
            <option value="+1-809">República Dominicana (+1-809)</option>
            <option value="+597">Surinam (+597)</option>
            <option value="+598">Uruguay (+598)</option>
            <option value="+58">Venezuela (+58)</option>
            <option value="+1-264">Anguila (+1-264)</option>
            <option value="+1-268">Antigua y Barbuda (+1-268)</option>
            <option value="+1-242">Bahamas (+1-242)</option>
            <option value="+1-246">Barbados (+1-246)</option>
            <option value="+1-441">Bermudas (+1-441)</option>
            <option value="+1-284">Islas Vírgenes Británicas (+1-284)</option>
            <option value="+1-345">Islas Caimán (+1-345)</option>
            <option value="+1-767">Dominica (+1-767)</option>
            <option value="+1-473">Granada (+1-473)</option>
            <option value="+592">Guyana (+592)</option>
            <option value="+1-664">Montserrat (+1-664)</option>
            <option value="+1-869">San Cristóbal y Nieves (+1-869)</option>
            <option value="+1-758">Santa Lucía (+1-758)</option>
            <option value="+1-784">San Vicente y las Granadinas (+1-784)</option>
            <option value="+1-649">Islas Turcas y Caicos (+1-649)</option>
            <option value="+1-340">Islas Vírgenes de los Estados Unidos (+1-340)</option>
            <option value="+297">Aruba (+297)</option>
            <option value="+599-7">Curazao (+599-7)</option>
            <option value="+599-3">Bonaire, San Eustaquio y Saba (+599-3)</option>
        </select>
        <input type="tel" class="form-control-custom" id="telefono" name="telefono" data-i18n-placeholder="phone" placeholder="Número" required pattern="[0-9]{6,15}" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
    </div>
</div>
<style>
    .form-group-phone {
        display: flex;
        gap: 8px;
    }

    .select-sm {
        max-width: 100px;
        padding: 0.375rem 0.5rem;
        font-size: 0.9rem;
        line-height: 1.5;
        height: auto;
    }
</style>