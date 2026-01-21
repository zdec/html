/**
 * Modals Component
 * Componentes reutilizables para los modales (quickview, cart, wishlist, compare)
 * Nota: Estos modales solo se necesitan en páginas con productos
 */
function loadModals() {
    const modalsHTML = `
        <!-- Modal -->
        <div class="modal modal-2 fade" id="exampleModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> <i class="pe-7s-close"></i></button>
                        <div class="row">
                            <div class="col-lg-6 col-sm-12 col-xs-12 mb-lm-30px mb-md-30px mb-sm-30px">
                                <!-- Swiper -->
                                <div class="swiper-container gallery-top">
                                    <div class="swiper-wrapper">
                                        <div class="swiper-slide">
                                            <img class="img-responsive m-auto" src="assets/images/product-image/zoom-image/1.webp" alt="">
                                        </div>
                                        <div class="swiper-slide">
                                            <img class="img-responsive m-auto" src="assets/images/product-image/zoom-image/2.webp" alt="">
                                        </div>
                                        <div class="swiper-slide">
                                            <img class="img-responsive m-auto" src="assets/images/product-image/zoom-image/3.webp" alt="">
                                        </div>
                                        <div class="swiper-slide">
                                            <img class="img-responsive m-auto" src="assets/images/product-image/zoom-image/4.webp" alt="">
                                        </div>
                                        <div class="swiper-slide">
                                            <img class="img-responsive m-auto" src="assets/images/product-image/zoom-image/5.webp" alt="">
                                        </div>
                                    </div>
                                </div>
                                <div class="swiper-container gallery-thumbs mt-20px slider-nav-style-1 small-nav">
                                    <div class="swiper-wrapper">
                                        <div class="swiper-slide">
                                            <img class="img-responsive m-auto" src="assets/images/product-image/small-image/1.webp" alt="">
                                        </div>
                                        <div class="swiper-slide">
                                            <img class="img-responsive m-auto" src="assets/images/product-image/small-image/2.webp" alt="">
                                        </div>
                                        <div class="swiper-slide">
                                            <img class="img-responsive m-auto" src="assets/images/product-image/small-image/3.webp" alt="">
                                        </div>
                                        <div class="swiper-slide">
                                            <img class="img-responsive m-auto" src="assets/images/product-image/small-image/4.webp" alt="">
                                        </div>
                                        <div class="swiper-slide">
                                            <img class="img-responsive m-auto" src="assets/images/product-image/small-image/5.webp" alt="">
                                        </div>
                                    </div>
                                    <!-- Add Arrows -->
                                    <div class="swiper-buttons">
                                        <div class="swiper-button-next"></div>
                                        <div class="swiper-button-prev"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12 col-xs-12" data-aos="fade-up" data-aos-delay="200">
                                <div class="product-details-content quickview-content">
                                    <h2>Antena Satelital</h2>
                                    <div class="pricing-meta">
                                        <ul class="d-flex">
                                            <li class="new-price">$9'800.500</li>
                                        </ul>
                                    </div>
                                    <div class="pro-details-rating-wrap">
                                        <div class="rating-product">
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                        </div>
                                        <span class="read-review"><a class="reviews" href="#">( 2 Review )</a></span>
                                    </div>
                                    <p class="mt-30px">
                                        Starlink Maritime es la solución de internet satelital diseñada especialmente para embarcaciones y operaciones marítimas que requieren conectividad confiable, rápida y global sin importar dónde se encuentren en los océanos del mundo. Aprovechando la más grande constelación de satélites en órbita terrestre baja, Starlink ofrece acceso continuo a internet incluso en aguas internacionales, ideal para barcos comerciales, pesca, investigación y yates privados.
                                    </p>
                                    <div class="pro-details-categories-info pro-details-same-style d-flex m-0">
                                        <span>SKU:</span>
                                        <ul class="d-flex">
                                            <li>
                                                <a href="#">Ch-256xl</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="pro-details-categories-info pro-details-same-style d-flex m-0">
                                        <span>Categoria: </span>
                                        <ul class="d-flex">
                                            <li>
                                                <a href="#">Comunicacion, </a>
                                            </li>
                                            <li>
                                                <a href="#">Antenas Satelitales</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="pro-details-categories-info pro-details-same-style d-flex m-0">
                                        <span>Tags: </span>
                                        <ul class="d-flex">
                                            <li>
                                                <a href="#">Wi-fi, </a>
                                            </li>
                                            <li>
                                                <a href="#">Antena</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="pro-details-quality">
                                        <div class="cart-plus-minus">
                                            <input class="cart-plus-minus-box" type="text" name="qtybutton" value="1" />
                                        </div>
                                        <div class="pro-details-cart">
                                            <button class="add-cart"> Añadir</button>
                                        </div>
                                        <div class="pro-details-compare-wishlist pro-details-wishlist ">
                                            <a href="#" realhref="wishlist.html"><i class="pe-7s-like"></i></a>
                                        </div>
                                    </div>
                                    <div class="payment-img">
                                        <a href="#"><img src="assets/images/icons/payment.png" alt=""></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal end -->
        <!-- Modal Cart -->
        <div class="modal customize-class fade" id="exampleModal-Cart" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="pe-7s-close"></i></button>
                        <div class="tt-modal-messages">
                            <i class="pe-7s-check"></i> Añadido al carrito con éxito!
                        </div>
                        <div class="tt-modal-product">
                            <div class="tt-img">
                                <img src="assets/images/product-image/1.webp" alt="Detalle Orden">
                            </div>
                            <h2 class="tt-title"><a href="#">Detalle de la orden</a></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>     
        <!-- Modal wishlist -->
        <div class="modal customize-class fade" id="exampleModal-Wishlist" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="pe-7s-close"></i></button>
                        <div class="tt-modal-messages">
                            <i class="pe-7s-check"></i> Añadido al carrito con éxito!
                        </div>
                        <div class="tt-modal-product">
                            <div class="tt-img">
                                <img src="assets/images/product-image/1.webp" alt="Detalle Orden">
                            </div>
                            <h2 class="tt-title"><a href="#">Detalle de la orden</a></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>    
        <!-- Modal compare -->
        <div class="modal customize-class fade" id="exampleModal-Compare" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="pe-7s-close"></i></button>
                        <div class="tt-modal-messages">
                            <i class="pe-7s-check"></i> Añadido para comparar exitosamente!
                        </div>
                        <div class="tt-modal-product">
                            <div class="tt-img">
                                <img src="assets/images/product-image/1.webp" alt="Detalle">
                            </div>
                            <h2 class="tt-title"><a href="#">Detalles</a></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Insertar los modales antes del cierre del body o antes del footer
    const footer = document.querySelector('.footer-area');
    if (footer) {
        footer.insertAdjacentHTML('beforebegin', modalsHTML);
    } else {
        // Si no hay footer, insertar antes del cierre de main-wrapper
        const mainWrapper = document.querySelector('.main-wrapper');
        if (mainWrapper) {
            mainWrapper.insertAdjacentHTML('beforeend', modalsHTML);
        } else {
            console.error('No se encontró lugar para insertar los modales');
        }
    }
}
