/**
 * Map Area Component
 * Componente reutilizable para el área del mapa de Google Maps
 */
function loadMapArea() {
    // Obtener datos de configuración
    const config = typeof SiteConfig !== 'undefined' ? SiteConfig : {};
    const contact = config.contact || {};
    const address = contact.city && contact.address 
        ? `${contact.city}, ${contact.address}` 
        : (contact.city || contact.address || "Medellin, Colombia Cra 32 # 77 S 371");
    
    // Codificar la dirección para la URL de Google Maps
    const mapAddress = encodeURIComponent(address);
    const mapUrl = `https://maps.google.com/maps?q=${mapAddress}&t=&z=13&ie=UTF8&iwloc=&output=embed`;
    
    const mapAreaHTML = `
        <!-- map Area Start -->
        <div class="contact-map">
            <div id="mapid">
                <div class="mapouter">
                    <div class="gmap_canvas">
                        <iframe id="gmap_canvas" src="${mapUrl}" title="Mapa de ubicación"></iframe>
                        <a href="https://sites.google.com/view/maps-api-v2/mapv2"></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- map Area End -->
    `;
    
    // Insertar antes del footer o al final del main-wrapper
    const footer = document.querySelector('.footer-area');
    if (footer) {
        footer.insertAdjacentHTML('beforebegin', mapAreaHTML);
    } else {
        const mainWrapper = document.querySelector('.main-wrapper');
        if (mainWrapper) {
            mainWrapper.insertAdjacentHTML('beforeend', mapAreaHTML);
        } else {
            console.error('No se encontró lugar para insertar el map area');
        }
    }
}
