<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
<div class="mapContainerBox">
  <?php
    include(plugin_dir_path(__FILE__) . '../Admin/FormatLotes.php'); // Incluye el archivo con la clase
    $lotesFormatted = FormatLotes::LotesFormatted();
    $phone = esc_attr(get_option('wow_phone'));
  ?>
  <div class="first-data-wrapper">
    <p class="map-description">Haga clic sobre el lote de su interés, para ver sus características.</p>
    <button data-contact="true" data-phone="<?php echo $phone ?>" id="whatsapp-button-first" class="whatsapp-button">Contáctenos para más información</button>
  </div>
  <div class="mapContainer">
    <input type="hidden" id="infoLotes" data-infoLotes='<?php echo json_encode($lotesFormatted); ?>' />
    <!-- A SVG must be included, check the example below, must contain id and data-name-->
    <!-- <svg id="svgImage" data-name="Lotes" xmlns="http://www.w3.org/2000/svg" width="202.1cm" height="202.1cm" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 5729.3 5729.9"> -->
  </div>
  <div class="first-data-wrapper">
    <p class="map-description"><strong>Código de Color: </strong> <span class="verde"></span> (Disponible), <span class="amarillo"></span> (Amarrado), <span class="rojo"></span> (Vendido)</p>
    <a class="go-back" href="https://www.condominiodistritosanjuan.cr/">Volver al inicio</a>
  </div>

  <!-- Modal -->  
  <div id="modal" class="hide">
    <div id="modal-wrapper" class="modal-wrapper">
      <img id="modal-close-icon" class="close-icon" src="<?php echo esc_url(plugins_url('img/close-icon.svg', __FILE__)); ?>" alt="Close icon">
      <div class="header">
        <div class="section">
          <span class="title-header">Sección</span>
          <span id="modal-seccion" class="pill-title">B</span>
        </div>
        <div class="lote-section">
          <span class="title-header">Lote</span>
          <span id="modal-lote" class="pill-title">2</span>
        </div>
      </div>
      <div class="description">
        <div class="description-element">
          <span class="description-title">Precio por m²:</span>
          <span id="modal-precioMc" class="description-value">₡15 000</span>
        </div>
        <div class="description-element">
          <span class="description-title">Superfice Total:</span>
          <span id="modal-superficieTotal" class="description-value">625.15 m²</span>
        </div>
        <div class="description-element">
          <span class="description-title">Metros de fondo:</span>
          <span id="modal-metroFondo" class="description-value">41.5 m</span>
        </div>
        <div class="description-element">
          <span class="description-title">Metros de frente:</span>
          <span id="modal-metroFrente" class="description-value">15 m</span>
        </div>
        <div class="description-element">
          <span class="description-title">Número de filial:</span>
          <span id="modal-numeroFilial" class="description-value">48 B</span>
        </div>
        <div class="description-element">
          <span class="description-title">Estado:</span>
          <span id="modal-estado" class="description-value pill">Vendido</span>
        </div>
        <div class="whatsapp-values">
          <p id="modal-whats-text" class="whatsapp-text">
            Esta propiedad está disponible en su totalidad. Si desea obtener más información o programar una cita para verla, puede contactarnos a través de WhatsApp.
          </p>
          <button data-phone="<?php echo $phone ?>" id="whatsapp-button" class="whatsapp-button">Más información</button>
        </div>
      </div>
    </div>
  </div>
</div>
