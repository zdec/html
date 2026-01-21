/**
 * Footer Component
 * Componente reutilizable para el footer del sitio
 */
function loadFooter() {
    const footerHTML = `
        <!-- Footer Area Start -->
        <div class="footer-area">
            <div class="footer-container">
                <div class="footer-top">
                    <div class="container">
                        <div class="row">
                            <!-- Start single blog -->
                            <div class="col-md-6 col-lg-3 mb-md-30px mb-lm-30px">
                                <div class="single-wedge">
                                    <div class="footer-logo">
                                        <a href="index.html"><img src="${SiteConfig.images.footerLogo}" alt=""></a>
                                    </div>
                                    <p class="about-text">Empresa de seguridad informatica y de las telecomicaciones, estamos orientados con tus objetivos.
                                    </p>
                                    <ul class="link-follow">
                                        <li>
                                            <a class="m-0" title="Twitter" target="_blank" rel="noopener noreferrer" href="${SiteConfig.social.facebook}"><i class="fa fa-facebook"
                                                aria-hidden="true"></i></a>
                                        </li>
                                        <li>
                                            <a title="Tumblr" target="_blank" rel="noopener noreferrer" href="${SiteConfig.social.tumblr}"><i class="fa fa-tumblr" aria-hidden="true"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a title="Facebook" target="_blank" rel="noopener noreferrer" href="${SiteConfig.social.twitter}"><i class="fa fa-twitter" aria-hidden="true"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a title="Instagram" target="_blank" rel="noopener noreferrer" href="${SiteConfig.social.instagram}"><i class="fa fa-instagram" aria-hidden="true"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!-- End single blog -->
                            <!-- Start single blog -->
                            <div class="col-md-6 col-lg-3 col-sm-6 mb-lm-30px pl-lg-60px">
                                <div class="single-wedge">
                                    <h4 class="footer-herading">Servicios</h4>
                                    <div class="footer-links">
                                        <div class="footer-row">
                                            <ul class="align-items-center">
                                                ${SiteConfig.footer.services.map(service => 
                                                    `<li class="li"><a class="single-link" href="${service.href}" realhref="${service.realhref}">${service.text}</a></li>`
                                                ).join('')}
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End single blog -->
                            <!-- Start single blog -->
                            <div class="col-md-6 col-lg-3 col-sm-6 mb-lm-30px pl-lg-40px">
                                <div class="single-wedge">
                                    <h4 class="footer-herading">Mi Cuenta</h4>
                                    <div class="footer-links">
                                        <div class="footer-row">
                                            <ul class="align-items-center">
                                                ${SiteConfig.footer.services.map(service => 
                                                    `<li class="li"><a class="single-link" href="${service.href}" realhref="${service.realhref}">${service.text}</a></li>`
                                                ).join('')}
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End single blog -->
                            <!-- Start single blog -->
                            <div class="col-md-6 col-lg-3 col-sm-12">
                                <div class="single-wedge">
                                    <h4 class="footer-herading">Informacion de Contacto</h4>
                                    <div class="footer-links">
                                        <!-- News letter area -->
                                        <p class="address">Direccion: ${SiteConfig.contact.address}.</p>
                                        <p class="phone">Phone/Fax:<a href="tel:${SiteConfig.contact.phoneFormatted.replace(/\s/g, '')}"> ${SiteConfig.contact.phone}</a></p>
                                        <p class="mail">Email:<a href="mailto:${SiteConfig.contact.email}"> ${SiteConfig.contact.email}</a></p>
                                        <p class="mail"><a href="https://${SiteConfig.contact.email}"> ${SiteConfig.contact.email}</a></p>
                                        <!-- News letter area  End -->
                                    </div>
                                </div>
                            </div>
                            <!-- End single blog -->
                        </div>
                    </div>
                </div>
                <div class="footer-bottom">
                    <div class="container">
                        <div class="line-shape-top line-height-1">
                            <div class="row flex-md-row-reverse align-items-center">
                                <div class="col-md-6 text-center text-md-end">
                                    <div class="payment-mth"><a href="#"><img class="img img-fluid" src="assets/images/icons/payment.png" alt="payment-image"></a></div>
                                </div>
                                <div class="col-md-6 text-center text-md-start">
                                    <p class="copy-text"> © ${SiteConfig.footer.copyright.year} <strong>${SiteConfig.footer.copyright.company}</strong> Creado con <i class="fa fa-heart"
                                        aria-hidden="true"></i> Por <a class="IT Secur" href="${SiteConfig.footer.copyright.creatorUrl}">
                                            <strong> ${SiteConfig.footer.copyright.creator} </strong></a>.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer Area End -->
    `;
    
    // Insertar el footer antes del cierre de main-wrapper
    const mainWrapper = document.querySelector('.main-wrapper');
    if (mainWrapper) {
        mainWrapper.insertAdjacentHTML('beforeend', footerHTML);
    } else {
        console.error('No se encontró .main-wrapper para insertar el footer');
    }
}
