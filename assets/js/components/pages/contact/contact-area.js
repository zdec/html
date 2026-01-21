/**
 * Contact Area Component
 * Componente reutilizable para el área de contacto con formulario e información
 */
function loadContactArea() {
    // Obtener datos de configuración
    const config = typeof SiteConfig !== 'undefined' ? SiteConfig : {};
    const contact = config.contact || {};
    const address = contact.address && contact.city 
        ? `${contact.city}<br>${contact.address}` 
        : (contact.address || "Medellin, Colombia Cra 32 # 77 S 371");
    const phone = contact.phone || "+57 310 6707901";
    const email = contact.email || "ventas@itsecursas.co";
    const web = contact.website || "https://itsecursas.co/";
    
    const contactAreaHTML = `
        <!-- Contact Area Start -->
        <div class="contact-area">
            <div class="container">
                <div class="contact-wrapper">
                    <div class="row">
                        <div class="col-12">
                            <div class="contact-form">
                                <div class="contact-title mb-30">
                                    <h2 class="title">Enviar una solicitud</h2>
                                </div>
                                <form class="contact-form-style" id="contact-form" action="https://whizthemes.com/nazmul/php/mail.php" method="post">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <input name="name" placeholder="Nombre*" type="text" />
                                        </div>
                                        <div class="col-lg-6">
                                            <input name="email" placeholder="Email*" type="email" />
                                        </div>
                                        <div class="col-lg-12">
                                            <input name="subject" placeholder="Asunto*" type="text" />
                                        </div>
                                        <div class="col-lg-12 text-center">
                                            <textarea name="message" placeholder="Mensaje*"></textarea>
                                            <button class="btn btn-primary" type="submit">Enviar</button>
                                        </div>
                                    </div>
                                </form>
                                <p class="form-messege"></p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="contact-info">
                                <div class="single-contact">
                                    <div class="icon-box">
                                        <img src="assets/images/icons/contact-1.png" alt="">
                                    </div>
                                    <div class="info-box">
                                        <h5 class="title">Direccion</h5>
                                        <p>${address}</p>
                                    </div>
                                </div>
                                <div class="single-contact">
                                    <div class="icon-box">
                                        <img src="assets/images/icons/contact-2.png" alt="">
                                    </div>
                                    <div class="info-box">
                                        <h5 class="title">Contacto</h5>
                                        <p><a href="tel:${phone.replace(/\s/g, '')}">${phone}</a></p>
                                    </div>
                                </div>
                                <div class="single-contact m-0">
                                    <div class="icon-box">
                                        <img src="assets/images/icons/contact-3.png" alt="">
                                    </div>
                                    <div class="info-box">
                                        <h5 class="title">Email/Web</h5>
                                        <p><a href="mailto:${email}">${email}</a></p>
                                        <p><a href="${web}" target="_blank">${web.replace(/^https?:\/\//, '')}</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Contact Area End -->
    `;
    
    // Insertar después del breadcrumb area
    const breadcrumbArea = document.querySelector('.breadcrumb-area');
    if (breadcrumbArea) {
        breadcrumbArea.insertAdjacentHTML('afterend', contactAreaHTML);
    } else {
        // Si no hay breadcrumb, buscar otro punto de referencia
        const mainWrapper = document.querySelector('.main-wrapper');
        if (mainWrapper) {
            const firstSection = mainWrapper.querySelector('.section, .contact-area, .contact-map');
            if (firstSection) {
                firstSection.insertAdjacentHTML('beforebegin', contactAreaHTML);
            } else {
                mainWrapper.insertAdjacentHTML('afterbegin', contactAreaHTML);
            }
        } else {
            console.error('No se encontró lugar para insertar el contact area');
        }
    }
}
