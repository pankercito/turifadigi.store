<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title> TuRifaDigi </title>
  <!-- favicons Icons -->

  <link rel="apple-touch-icon" sizes="180x180" href="assets/img/ico/TuRifadigi.ico" />
  <link rel="icon" type="image/png" sizes="32x32" href="assets/img/ico/TuRifadigi.ico" />
  <link rel="icon" type="image/png" sizes="16x16" href="assets/img/ico/TuRifadigi.ico" />
  <link rel="manifest" href="assets/img/favicons/site.webmanifest" />
  <meta name="description" content="TuRifaDigital" />
  <!-- fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap fallback -->
  <noscript>
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
  </noscript>

  <!-- FUNCION DE TICKETS -->
  <script src="assets/js/boletosTicket.js"></script>
  <script src="assets/js/jsbarcode.js"></script>

  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <noscript>
    <link rel="stylesheet" href="vendor/fontawesome/css/all.min.css">
  </noscript>

  <!-- TOASTYFY -->
  <script type="text/javascript" src="assets/js/ToastPersonalizado.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
  <link rel="stylesheet" type="text/css" href="assets/css/ToastPersonalizado.css">


  <!-- jQuery UI -->
  <link href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css" rel="stylesheet">

  <!-- Swiper -->
  <link href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" rel="stylesheet">
  <noscript>
    <link rel="stylesheet" href="vendor/swiper/swiper.min.css">
  </noscript>

  <!-- Recursos locales personalizados -->
  <link rel="stylesheet" href="vendor/animate/animate.min.css" />
  <link rel="stylesheet" href="vendor/animate/custom-animate.css" />
  <link rel="stylesheet" href="vendor/jarallax/jarallax.css" />
  <link rel="stylesheet" href="vendor/jquery-magnific-popup/jquery.magnific-popup.css" />
  <link rel="stylesheet" href="vendor/odometer/odometer.min.css" />
  <link rel="stylesheet" href="vendor/zefxa-icons/style.css">
  <link rel="stylesheet" href="vendor/owl-carousel/owl.carousel.min.css" />
  <link rel="stylesheet" href="vendor/owl-carousel/owl.theme.default.min.css" />
  <link rel="stylesheet" href="vendor/bootstrap-select/css/bootstrap-select.min.css" />
  <link rel="stylesheet" href="vendor/nice-select/nice-select.css" />
  <link rel="stylesheet" href="vendor/reey-font/stylesheet.css" />

  <!-- template styles -->
  <link rel="stylesheet" href="assets/css/botonTickets.css" />
  <link rel="stylesheet" href="assets/css/custom.css" />
  <link rel="stylesheet" href="assets/css/custom_responsive.css" />

  <link rel="stylesheet" href="assets/css/dropdownMetodosPagos.css">

  <link rel="stylesheet" href="assets/css/dropdown-language.css">

  <!-- Scripts adicionales CSS -->
  <link rel="stylesheet" href="vendor/bootstrap-datepicker/css/bootstrap-datepicker.min.css">
  <link rel="stylesheet" href="vendor/bootstrap-timepicker/css/bootstrap-timepicker.min.css">
  <link rel="stylesheet" href="vendor/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
  <link rel="stylesheet" href="vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css">
  <link rel="stylesheet" href="vendor/select2/css/select2.min.css">
  <link rel="stylesheet" href="vendor/datatables/css/dataTables.bootstrap5.min.css">
  <link rel="stylesheet" href="vendor/dropzone/dropzone.css">
  <link rel="stylesheet" href="vendor/summernote/summernote-bs4.min.css">
  <link rel="stylesheet" href="vendor/toastr/toastr.min.css">

</head>

<body class="custom-cursor">





  <div class="custom-cursor__cursor"></div>
  <div class="custom-cursor__cursor-two"></div>
  <div class="page-wrapper">
    <header class="main-header">
      <div class="main-menu__top">
      </div>
      <nav class="main-menu">
        <div class="main-menu__wrapper" style="background-color: #201f23;">
          <div class="container">
            <div class="main-menu__wrapper-inner">
              <div class="main-menu__left">
                <div class="main-menu__logo">
                </div>
                <div class="main-menu__main-menu-box">
                  <a href="#" class="mobile-nav__toggler"><i class="fa fa-bars"></i></a>
                  <ul class="main-menu__list">
                    <li class="dropdown" style="display: none;"></li>
                    <?php
                    @$session = $_SESSION['usuario'] ?? '';
                    @$sessionRol = $_SESSION['rol_usuario'] ?? '';
                    $class = $sessionRol != 2 ? 'element' : 'dropdown';
                    ?>
                    <li class="<?php echo $class ?>">
                      <a href="/"><span data-i18n="home">Inicio</span>
                        <span class="main-menu-border"></span>
                      </a>
                      <?php
                      if (!empty($session) && $sessionRol == 2) : ?>
                        <ul class="dropdown-menu">
                          <li><a href="/compra_verificacion" data-i18n="verify purchases">Verificar compras</a></li>
                          <li><a href="/sorteo_verificacion" data-i18n="verify raffles">Verificar sorteos</a></li>
                          <li><a href="/crear_sorteo" data-i18n="create raffle">Crear sorteo</a></li>
                        </ul>
                      <?php endif; ?>
                    </li>
                    <?php
                    if ($session !== '') {
                      echo '
                    <li class="element">
                      <a href="/compras" data-i18n="purchases">Compras
                        <span class="main-menu-border"></span>
                      </a>
                    </li>';
                    } ?>
                    <li class="element">
                      <a href="/verificar_boleto" data-i18n="verify_ticket">Verificar Boleto
                        <span class="main-menu-border"></span>
                      </a>
                    </li>
                    <li class="element">
                      <a href="/sorteo" data-i18n="raffle">Sorteo
                        <span class="main-menu-border"></span>
                      </a>
                    </li>
                    <?php
                    $session = $_SESSION['usuario'] ?? '';
                    if ($session === '') {
                      echo '
                      <li class="element">
                        <a href="/signup" data-i18n="create_account">Crear Cuenta
                          <span class="main-menu-border"></span>
                        </a>
                      </li>';
                    }

                    if ($session === '') {
                      echo '
                            <li class="element mb-2 inicMob">
                              <div class="custom-language-select  " id="custom-language-select">
                                <div class="custom-language-button">
                                  <span class="custom-language-selected">Idioma</span>
                                  <span class="custom-language-arrow"><i class="fa-solid fa-arrow-down"></i></span>
                                </div>
                                <div class="custom-language-options">
                                  <div class="custom-language-option" data-value="es">Esp</div>
                                  <div class="custom-language-option" data-value="en">Eng</div>
                                </div>
                                <input type="hidden" name="language" value="es">
                              </div>
                            </li>
                            <li class="element mb-2 inicMob">
                              <div class="main-menu__btn-box">
                                <a href="/login" class="main-menu__btn thm-btn" data-i18n="login_btn">Iniciar Sesion</a>
                              </div>
                            </li>
                            ';
                    } else {
                      echo '
                            <li class="element mb-2 inicMob">
                              <div class="custom-language-select" id="custom-language-select">
                                <div class="custom-language-button">
                                  <span class="custom-language-selected">Idioma</span>
                                  <span class="custom-language-arrow"><i class="fa-solid fa-arrow-down"></i></span>
                                </div>
                                <div class="custom-language-options">
                                  <div class="custom-language-option" data-value="es">Esp</div>
                                  <div class="custom-language-option" data-value="en">Eng</div>
                                </div>
                                <input type="hidden" name="language" value="es">
                              </div>
                            </li>
                            <li class="element mb-2 inicMob">
                              <div class="main-menu__btn-box">
                                <a href="/login" class="main-menu__btn thm-btn" onclick=session_destroy() data-i18n="logout_btn">Cerrar Sesion</a>
                              </div>
                            </li>
                            ';
                    } ?>
                  </ul>

                </div>
              </div>
              <?php
              if ($session === '') {
                echo '
                            <div class="main-menu__right">

                            
                <div class="custom-language-select" id="custom-language-select">
                                <div class="custom-language-button">
                                  <span class="custom-language-selected">Idioma</span>
                                  <span class="custom-language-arrow"><i class="fa-solid fa-arrow-down"></i></span>
                                </div>
                                <div class="custom-language-options">
                                  <div class="custom-language-option" data-value="es">Esp</div>
                                  <div class="custom-language-option" data-value="en">Eng</div>
                                </div>
                                <input type="hidden" name="language" value="es">
                              </div>

                            
                            <div class="main-menu__btn-box">
                              <a href="/login" class="main-menu__btn thm-btn" data-i18n="login_btn">Iniciar Sesión</a>
                              </div>

                              </div>';
              } else {
                echo '<div class="main-menu__right">
                    <div class="custom-language-select" id="custom-language-select">
                                <div class="custom-language-button">
                                  <span class="custom-language-selected">Idioma</span>
                                  <span class="custom-language-arrow"><i class="fa-solid fa-arrow-down"></i></span>
                                </div>
                                <div class="custom-language-options">
                                  <div class="custom-language-option" data-value="es">Esp</div>
                                  <div class="custom-language-option" data-value="en">Eng</div>
                                </div>
                                <input type="hidden" name="language" value="es">
                              </div>
                              
                                  <div class="main-menu__btn-box">
                                  <a href="" class="main-menu__btn thm-btn" onclick=session_destroy() data-i18n="logout_btn">Cerrar Sesión</a>
                                  </div>

                                  </div>';
              } ?>
            </div>
          </div>
        </div>
      </nav>
      <script>
        function session_destroy() {
          $.ajax({
            url: './api/session_destroy',
            type: 'POST',
            dataType: 'json',
            success: function(response) {
              if (response.status === 'success') {
                window.location.href = './';
              } else {
                alert('Error al cerrar sesión. Inténtalo de nuevo.');
              }
            },
            error: function() {
              alert('Error en la solicitud. Inténtalo de nuevo.');
            }
          });
        }
      </script>
    </header>
    <style>
      .inicMob {
        margin: 20px 0;
        display: none;
      }

      @media (max-width: 765px) {
        .inicMob {
          margin: 20px 0;
          display: block;
        }
      }
    </style>

    <div class="stricky-header stricked-menu main-menu">
      <div class="sticky-header__content"></div><!-- /.sticky-header__content -->
    </div><!-- /.stricky-header -->