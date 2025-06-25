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
  <meta name="description" content="TuRifaDigital" />
  <!-- fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap" rel="stylesheet">

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/semantic-ui@2.4.2/dist/semantic.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- node  -->
  <script src="node_modules/easyqrcodejs/src/easy.qrcode.js"></script>
  <script src="node_modules/jquery/dist/jquery.min.js"></script>
  <script src="node_modules/jquery-ui/dist/jquery-ui.min.js"></script>
  <link rel="stylesheet" href="node_modules/animate.css/animate.min.css">
  <link rel="stylesheet" href="node_modules/animate.css/animate.compat.css">
  <script src="node_modules/lax.js/lib/lax.min.js"></script>
  <script src="node_modules/wow.js/dist/wow.min.js"></script>


  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <!-- FUNCION DE TICKETS -->
  <script src="assets/js/boletosTicket.js"></script>
  <script src="assets/js/jsbarcode.js"></script>
  <script src="node_modules/easyqrcodejs/src/easy.qrcode.js"></script>

  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

  <!-- TOASTYFY -->
  <script type="text/javascript" src="assets/js/ToastPersonalizado.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
  <link rel="stylesheet" type="text/css" href="assets/css/ToastPersonalizado.css">


  <!-- jQuery UI -->
  <link href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css" rel="stylesheet">

  <!-- Swiper -->
  <link href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/9.0.0/swiper-bundle.min.js"></script>

  <!-- template styles -->
  <link rel="stylesheet" href="assets/css/botonTickets.css" />
  <link rel="stylesheet" href="assets/css/custom.css" />
  <link rel="stylesheet" href="assets/css/custom_responsive.css" />
  <link rel="stylesheet" href="assets/css/dropdownMetodosPagos.css">
  <link rel="stylesheet" href="assets/css/dropdown-language.css">
</head>

<body>
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
                    <img class="inicMob" src="assets/img/webp/TuRifadigi.webp" alt="logo_turifadigital" style="max-width: 100px; height: auto;">
                    <li class="dropdown" style="display: none;"></li>
                    <?php
                    @$session = $_SESSION['usuario'] ?? '';
                    @$sessionRol = $_SESSION['rol_usuario'] ?? '';
                    // La validación original:
                    // $class = $sessionRol != 2 || $sessionRol != 3 ? 'element' : 'dropdown';

                    // Esta validación siempre será verdadera porque si $sessionRol es 2, entonces $sessionRol != 3 es true, y viceversa.
                    // Por lo tanto, siempre asignará 'element'.
                    // Para que sea 'dropdown' solo cuando $sessionRol sea 2 o 3, usa:

                    $class = ($sessionRol == 2 || $sessionRol == 3) ? 'dropdown' : 'element';
                    ?>
                    <li class="<?php echo $class ?>">
                      <a href="/">
                        <span data-i18n="home">Inicio</span>
                        <span class="main-menu-border"></span>
                      </a>
                      <?php
                      if (!empty($session) && $sessionRol == 2 || !empty($session) && $sessionRol == 3) : ?>
                        <ul class="dropdown-menu">
                          <li><a href="/compra_verificacion" data-i18n="verify purchases">Verificar compras</a></li>
                          <li><a href="/sorteo_verificacion" data-i18n="verify raffles">Verificar sorteos</a></li>
                          <li><a href="/crear_sorteo" data-i18n="create raffle">Crear sorteo</a></li>
                          <?php
                          if (!empty($session) && $sessionRol == 3) : ?>
                            <li><a href="/restablecer_contrasena" data-i18n="rest_password">Restablecer Contraseña</a></li>
                          <?php endif; ?>
                        </ul>
                      <?php endif; ?>
                    </li>
                    <?php
                    if ($session !== '') {
                      echo '
                    <li class="element">
                      <a href="/compras">
                        <span data-i18n="purchases">Compras</span> 
                        <span class="main-menu-border"></span>
                      </a>
                    </li>';
                    } ?>
                    <li class="element">
                      <a href="/verificar_boleto">
                        <span data-i18n="verify_ticket">Verificar Boleto</span>
                        <span class="main-menu-border"></span>
                      </a>
                    </li>
                    <li class="element">
                      <a href="/sorteo">
                        <span data-i18n="raffle">Sorteo</span>
                        <span class="main-menu-border"></span>
                      </a>
                    </li>
                    <?php
                    $session = $_SESSION['usuario'] ?? '';
                    if ($session === '') {
                      echo '
                      <li class="element">
                        <a href="/signup">
                          <span data-i18n="create_account">Crear Cuenta</span>
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
                              <a href="/login" class="main-menu__btn thm-btn" data-i18n="login_btn">Iniciar Sesi�n</a>
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
                                  <a href="" class="main-menu__btn thm-btn" onclick=session_destroy() data-i18n="logout_btn">Cerrar Sesi�n</a>
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
                window.location.href = '/sorteo';
              } else {
                alert('Error al cerrar sesi�n. Int�ntalo de nuevo.');
              }
            },
            error: function() {
              alert('Error en la solicitud. Int�ntalo de nuevo.');
            }
          });
        }
      </script>
    </header>
    <style>
      .mobile-nav__container {
        border-top: 1px solid rgba(255, 255, 255, 0);
        border-bottom: 1px solid rgba(255, 255, 255, 0);
      }

      li.element.mb-2.inicMob {
        border-bottom: 1px solid #8b451300;
      }

      img.inicMob {
        margin: -30px auto 15px;
      }

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