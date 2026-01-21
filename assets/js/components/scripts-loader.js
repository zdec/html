/**
 * Scripts Loader Component
 * Carga todos los scripts necesarios para el sitio
 */
function loadScripts() {
    const scriptsHTML = `
        <!-- Global Vendor, plugins JS -->
        <!-- JS Files
        ============================================ -->
        <script src="assets/js/vendor/bootstrap.bundle.min.js"></script>
        <script src="assets/js/vendor/jquery-3.6.0.min.js"></script>
        <script src="assets/js/vendor/jquery-migrate-3.3.2.min.js"></script>
        <script src="assets/js/vendor/modernizr-3.11.2.min.js"></script>
        <script src="assets/js/plugins/jquery.countdown.min.js"></script>
        <script src="assets/js/plugins/swiper-bundle.min.js"></script>
        <script src="assets/js/plugins/scrollUp.js"></script>
        <script src="assets/js/plugins/venobox.min.js"></script>
        <script src="assets/js/plugins/jquery-ui.min.js"></script>
        <script src="assets/js/plugins/mailchimp-ajax.js"></script>

        <!-- Minify Version -->
        <!-- <script src="assets/js/vendor.min.js"></script>
        <script src="assets/js/plugins.min.js"></script>
        <script src="assets/js/main.min.js"></script> -->

        <!--Main JS (Common Activation Codes)-->
        <script src="assets/js/main.js"></script>
    `;
    
    // Insertar los scripts antes del cierre del body
    const body = document.querySelector('body');
    if (body) {
        body.insertAdjacentHTML('beforeend', scriptsHTML);
    } else {
        console.error('No se encontró el body para insertar los scripts');
    }
}
