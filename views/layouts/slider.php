<<<<<<< HEAD
<section class="main-slider">
    <div id="mainCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active" style="height: 850px;">
=======
<!-- <section class="main-slider">
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
>>>>>>> 27704c9bd19dc94788d5b70591d251d55bc374ef
                <div class="main-slider__bg" style="background-image: url(assets/img/backgrounds/sorteo.webp);"></div>
                <div class="main-slider__shape-bg" style="background-image: url(assets/img/shapes/main-slider-shape-bg.png);"></div>
                <div class="main-slider__shape-1 float-bob-y">
                    <img src="assets/img/shapes/main-slider-shape-1.png" alt="">
                </div>
                <div class="main-slider__shape-2 img-bounce">
                    <img src="assets/img/shapes/main-slider-shape-2.png" alt="">
                </div>
                <div class="container">
                    <div class="strucs">
                        <div class="cs_main-slider__content">
                            <h2 class="main-slider__title">TuRifaDigital<br> <span data-i18n="best_option">Tu mejor opción</span> <br> <span data-i18n="for_raffles">para rifas</span></h2>
                            <p class="main-slider__text"> <span data-i18n="easy_safe">Crea y gestiona tus rifas de manera</span> <br> <span data-i18n="easy_an_safe">fácil y segura.</span></p>
                            <div class="main-slider__btn-box">
                                <a href="/sorteo" class="main-slider__btn thm-btn" data-i18n="start_now">Comenzar ahora</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="carousel-item" style="height: 850px;">
                <div class="main-slider__bg" style="background-image: url(assets/img/backgrounds/MotoYorsi.webp);"></div>
                <div class="main-slider__shape-bg" style="background-image: url(assets/img/shapes/main-slider-shape-bg.png);"></div>
                <div class="main-slider__shape-1 float-bob-y">
                    <img src="assets/img/shapes/main-slider-shape-1.png" alt="">
                </div>
                <div class="main-slider__shape-2 img-bounce">
                    <img src="assets/img/shapes/main-slider-shape-2.png" alt="">
                </div>
                <div class="container">
<<<<<<< HEAD
                    <div class="strucs">
                        <div class="cs_main-slider__content">
                            <h2 class="main-slider__title" ">TuRifaDigital<br><span data-i18n="manage_raffles">Gestiona tus boletos</span><br><span data-i18n="m_raffle">manera facil y rapida</span></h2>
                            <p class="main-slider__text"><span data-i18n="full_control"> Seleccion facil e intuitiva</span> <br> <span data-i18n="ur_tickets"> </span> </p>
                            <?php
                            $session = $_SESSION['usuario'] ?? '';
                            if ($session === '') {
                                echo '<div class="main-slider__btn-box">
                                        <a href="/sorteo" class="main-slider__btn thm-btn" data-i18n="register_free">Regístrate gratis</a>
                                    </div>';
                            }
                            ?>
=======
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="main-slider__content">
                                <h2 class="main-slider__title" data-i18n="manage_raffles">Gestiona tus <br> <span>Rifas</span> <br> con facilidad</h2>
                                <p class="main-slider__text" data-i18n="full_control">Control total sobre tus sorteos <br> y participantes.</p>
                                <?php
                                $session = $_SESSION['usuario'] ?? '';
                                if ($session === '') {
                                    echo '<div class="main-slider__btn-box">
                            <a href="/login" class="turifaboton-style" data-i18n="register_free">Regístrate gratis</a>
                          </div>';
                                }
                                ?>
                            </div>
>>>>>>> 27704c9bd19dc94788d5b70591d251d55bc374ef
                        </div>
                    </div>
                </div>
            </div>

            <div class="carousel-item" style="height: 850px;">
                <div class="main-slider__bg" style="background-image: url(assets/img/backgrounds/iPhoneback.webp);"></div>
                <div class="main-slider__shape-bg" style="background-image: url(assets/img/shapes/main-slider-shape-bg.png);"></div>
                <div class="main-slider__shape-1 float-bob-y">
                    <img src="assets/img/shapes/main-slider-shape-1.png" alt="">
                </div>
                <div class="main-slider__shape-2 img-bounce">
                    <img src="assets/img/shapes/main-slider-shape-2.png" alt="">
                </div>
                <div class="container">
                    <div class="strucs">

                        <div class="cs_main-slider__content">
                            <h2 class="main-slider__title" >TuRifaDigital<br><span data-i18n="payment_system">Sistema de</span> <br> <span data-i18n="payment_system2">Pagos</span> <br> <span data-i18n="payment_system3">Seguro</span> </h2>
                            <p class="main-slider__text"> <span data-i18n="multiple_methods">Múltiples métodos de pago</span> <br><span data-i18n="multiple_methods2">y transacciones seguras.</span> </p>
                            <div class="main-slider__btn-box">
                                <a href="/login" class="main-slider__btn thm-btn" data-i18n="learn_more">Conoce más</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
<<<<<<< HEAD
</section>

<style>
    .main-slider .container {
        position: normal;
        padding-top: 250px;
        padding-bottom: 0px;
        z-index: 30;
    }

    .main-slider__title {
        opacity: 100%;
    }

    @media only screen and (max-width: 767px) {
        .main-slider__title {
            font-size: 50px;
            line-height: 65px;
            font-weight: 1000;
        }
    }

    .main-slider__text {
        position: relative;
        margin-top: -200px;
        margin-bottom: 40px;
        opacity: 100%;
    }

    @media only screen and (max-width: 767px) {
        .main-slider__text {
            font-size: 30px;
            line-height: 51px;
        }
    }

    .main-slider__btn-box {
        opacity: 100%;
    }

    .cs_main-slider__content {
        opacity: 100%;
    }

    .strucs {
        display: flex;
    }






    /* fondo animacion  */
    .main-slider__bg {
        mix-blend-mode: luminosity;
        filter: grayscale(100%);
        -webkit-filter: grayscale(100%);
        background-size: cover;
        /* La imagen siempre cubre el div */
        background-position: center center;

        /* Propiedades para la transformación */
        transform: scale(1);
        /* Escala base sin zoom */
        /* La transición se aplica cuando la propiedad 'transform' cambia */
        transition: transform 5s ease;
        will-change: transform;
        /* Sugerencia al navegador para optimizar la animación */
    }

    /* Animación de entrada para el carousel-item activo */
    .carousel-item.active .main-slider__bg {
        /* Aplica la animación inicial al cargar la página */
        animation: initialZoom 5s ease forwards;
    }

    /* Para los carousel-items que no están activos */
    .carousel-item:not(.active) .main-slider__bg {
        transform: scale(1);
        /* Asegura que los que salen o no están activos estén en su tamaño normal */
        animation: none;
        /* Elimina cualquier animación en curso */
    }

    /* Define la animación para el zoom inicial */
    @keyframes initialZoom {
        0% {
            transform: scale(1);
            /* Inicia sin zoom */
        }

        100% {
            transform: scale(1.05);
            /* Termina con el zoom deseado */
        }
=======
</section> -->


<!--Main Slider Start-->
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
                <div class="main-slider__bg"
                    style="background-image: url(assets/img/backgrounds/sorteo.webp);"></div>
                <div class="main-slider__shape-bg"
                    style="background-image: url(assets/img/shapes/main-slider-shape-bg.png);"></div>
                <div class="main-slider__shape-1 float-bob-y">
                    <!-- <img src="assets/img/shapes/main-slider-shape-1.png" alt=""> -->
                </div>
                <div class="main-slider__shape-2 img-bounce">
                    <!-- <img src="assets/img/shapes/main-slider-shape-2.png" alt=""> -->
                </div>
                <div class="main-slider__shape-3 float-bob-x">
                    <!--  <img src="assets/img/shapes/main-slider-shape-3.png" alt=""> -->
                </div>
                <div class="container">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="main-slider__content">
                                <h2 class="main-slider__title" data-i18n="best_option">TuRifaDigital <br> <span>Tu mejor opción</span> <br>
                                    para rifas</h2>
                                <p class="main-slider__text" data-i18n="easy_safe">Crea y gestiona tus rifas de manera <br>
                                    fácil y segura.</p>
                                <div class="">
                                    <a href="/login" class="turifaboton-style" data-i18n="start_now">Comenzar ahora</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="swiper-slide">
                <div class="main-slider__bg"
                    style="background-image: url(assets/img/backgrounds/MotoYorsi.webp);"></div>
                <div class="main-slider__shape-bg"
                    style="background-image: url(assets/img/shapes/main-slider-shape-bg.png);"></div>
                <div class="main-slider__shape-1 float-bob-y">
                    <!-- <img src="assets/img/shapes/main-slider-shape-1.png" alt=""> -->
                </div>
                <div class="main-slider__shape-2 img-bounce">
                    <!-- <img src="assets/img/shapes/main-slider-shape-2.png" alt=""> -->
                </div>
                <div class="main-slider__shape-3 float-bob-x">
                    <!-- <img src="assets/img/shapes/main-slider-shape-3.png" alt=""> -->
                </div>
                <div class="container">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="main-slider__content">
                                <h2 class="main-slider__title" data-i18n="manage_raffles">Gestiona tus <br> <span>Rifas</span> <br>
                                    con facilidad</h2>
                                <p class="main-slider__text" data-i18n="full_control">Control total sobre tus sorteos <br>
                                    y participantes.</p>
                                <?php
                                $session = $_SESSION['usuario'] ?? '';
                                if ($session === '') {
                                    /* echo '<div class="main-slider__btn-box"> */
                                    /*     <a href="/login" class="turifaboton-style" data-i18n="register_free">Regístrate gratis</a> */
                                    /* </div>'; */
                                }
                                ?>
                                <!-- <div class=""> -->
                                <!--     <a href="/login" class="turifaboton-style">Regístrate gratis</a> -->
                                <!-- </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="swiper-slide">
                <div class="main-slider__bg"
                    style="background-image: url(assets/img/backgrounds/iPhoneback.webp);"></div>
                <div class="main-slider__shape-bg"
                    style="background-image: url(assets/img/shapes/main-slider-shape-bg.png);"></div>
                <div class="main-slider__shape-1 float-bob-y">
                    <!-- <img src="assets/img/shapes/main-slider-shape-1.png" alt=""> -->
                </div>
                <div class="main-slider__shape-2 img-bounce">
                    <!-- <img src="assets/img/shapes/main-slider-shape-2.png" alt=""> -->
                </div>
                <div class="main-slider__shape-3 float-bob-x">
                    <!-- <img src="assets/img/shapes/main-slider-shape-3.png" alt=""> -->
                </div>
                <div class="container">
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="main-slider__content">
                                <h2 class="main-slider__title" data-i18n="payment_system">Sistema de <br> <span>Pagos</span> <br>
                                    Seguro</h2>
                                <p class="main-slider__text" data-i18n="multiple_methods">Múltiples métodos de pago <br>
                                    y transacciones seguras.</p>
                                <div class="">
                                    <a href="/login" class="turifaboton-style" data-i18n="learn_more">Conoce más</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



        </div>

        <div class="swiper-pagination" id="main-slider-pagination"></div>
        <!-- If we need navigation buttons -->

    </div>
</section>
<!--Main Slider End-->


<style>
    .turifaboton-style {
        background-color: var(--zefxa-base);
        color: var(--zefxa-white);
        padding: 12px 24px;
        border: none;
        border-radius: 4px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .turifaboton-style:hover {
        background-color: var(--zefxa-black);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .turifaboton-style:active {
        transform: translateY(0);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
>>>>>>> 27704c9bd19dc94788d5b70591d251d55bc374ef
    }
</style>