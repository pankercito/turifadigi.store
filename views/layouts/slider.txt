<section class="main-slider">
    <div class="swiper-container thm-swiper__slider" data-swiper-options='{"slidesPerView": 1, "loop": true,
                "effect": "fade",
                "pagination": {
                "el": "#main-slider-pagination",
                "type": "bullets",
                "clickable": true
                },
                "navigation": {
                "nextEl": "#main-slider__swiper-button-next",
                "prevEl": "#main-slider__swiper-button-prev"
                },
                "autoplay": {
                    "delay": 8000
                } 
            }'>
        <div class="swiper-wrapper">

            <div class="swiper-slide">
                <div class="main-slider__bg" style="background-image: url(assets/img/backgrounds/sorteo.webp);"></div>
                <div class="main-slider__shape-bg" style="background-image: url(assets/img/shapes/main-slider-shape-bg.png);"></div>
                <div class="main-slider__shape-1 float-bob-y">
                    <img src="assets/img/shapes/main-slider-shape-1.png" alt="">
                </div>
                <div class="main-slider__shape-2 img-bounce">
                    <img src="assets/img/shapes/main-slider-shape-2.png" alt="">
                </div>
                <div class="container">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="main-slider__content">
                                <h2 class="main-slider__title" data-i18n="best_option">TuRifaDigital <br> <span>Tu mejor opción</span> <br> para rifas</h2>
                                <p class="main-slider__text" data-i18n="easy_safe">Crea y gestiona tus rifas de manera <br> fácil y segura.</p>
                                <div class="main-slider__btn-box">
                                    <a href="/login" class="main-slider__btn thm-btn" data-i18n="start_now">Comenzar ahora</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="swiper-slide">
                <div class="main-slider__bg" style="background-image: url(assets/img/backgrounds/MotoYorsi.webp);"></div>
                <div class="main-slider__shape-bg" style="background-image: url(assets/img/shapes/main-slider-shape-bg.png);"></div>
                <div class="main-slider__shape-1 float-bob-y">
                    <img src="assets/img/shapes/main-slider-shape-1.png" alt="">
                </div>
                <div class="main-slider__shape-2 img-bounce">
                    <img src="assets/img/shapes/main-slider-shape-2.png" alt="">
                </div>
                <div class="container">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="main-slider__content">
                                <h2 class="main-slider__title" data-i18n="manage_raffles">Gestiona tus <br> <span>Rifas</span> <br> con facilidad</h2>
                                <p class="main-slider__text" data-i18n="full_control">Control total sobre tus sorteos <br> y participantes.</p>
                                <?php
                                $session = $_SESSION['usuario'] ?? '';
                                if ($session === '') {
                                    echo '<div class="main-slider__btn-box">
                            <a href="/login" class="main-slider__btn thm-btn" data-i18n="register_free">Regístrate gratis</a>
                          </div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="swiper-slide">
                <div class="main-slider__bg" style="background-image: url(assets/img/backgrounds/iPhoneback.webp);"></div>
                <div class="main-slider__shape-bg" style="background-image: url(assets/img/shapes/main-slider-shape-bg.png);"></div>
                <div class="main-slider__shape-1 float-bob-y">
                    <img src="assets/img/shapes/main-slider-shape-1.png" alt="">
                </div>
                <div class="main-slider__shape-2 img-bounce">
                    <img src="assets/img/shapes/main-slider-shape-2.png" alt="">
                </div>
                <div class="container">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="main-slider__content">
                                <h2 class="main-slider__title" data-i18n="payment_system">Sistema de <br> <span>Pagos</span> <br> Seguro</h2>
                                <p class="main-slider__text" data-i18n="multiple_methods">Múltiples métodos de pago <br> y transacciones seguras.</p>
                                <div class="main-slider__btn-box">
                                    <a href="/login" class="main-slider__btn thm-btn" data-i18n="learn_more">Conoce más</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="swiper-pagination" id="main-slider-pagination"></div>
    </div>
</section>