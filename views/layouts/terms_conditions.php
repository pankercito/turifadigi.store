<?php require_once 'views/layouts/header.php'; ?>


<div id="termsAndConditionsModal">
    <div id="myCustomTermsContainer">
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const customTermsContainerId = 'myCustomTermsContainer'; // El ID de tu contenedor

        // 1. Inicializar i18n y cargar los términos por primera vez
        i18n.init().then(() => {
            // Este código se ejecuta SÓLO después de que i18n.init() haya terminado
            // de cargar los archivos de traducción iniciales.
            i18n.loadAndDisplayTerms(customTermsContainerId);
        }).catch(error => {
            console.error("Error durante la inicialización de i18n:", error);
        });

        // 2. Escuchar el evento 'languageChanged' para actualizar los términos
        //    cuando el usuario cambie el idioma.
        window.addEventListener("languageChanged", (event) => {
            // Cuando el idioma cambie, volvemos a cargar y mostrar los términos
            // Esto asegura que displayTermsAndConditions limpie el contenido anterior
            // y cargue el nuevo idioma.
            i18n.loadAndDisplayTerms(customTermsContainerId);
            console.log(`Idioma cambiado a: ${event.detail.language}. Términos actualizados.`);
        });

        // NOTA: Si tenías un botón "Ver Términos y Condiciones"
        // y un modal que se ocultaba/mostraba, esa lógica iría aquí
        // dentro del DOMContentLoaded, PERO FUERA del primer .then()
        // ya que los event listeners se pueden configurar antes de que i18n.init termine.
        // Solo asegúrate de que cuando se haga clic en ese botón, i18n.loadAndDisplayTerms
        // se llame. Si tu página de términos no es un modal, esto no es necesario.
    });
</script>
<style>
    #termsAndConditionsModal {
        margin-top: 110px;
    }

    /* Contenedor principal de los términos y condiciones */
    .terms-container {
        max-width: 900px;
        /* Limita el ancho para mejor legibilidad en pantallas grandes */
        margin: 40px auto;
        /* Centra el contenedor y añade espacio superior/inferior */
        padding: 30px;
        /* Espacio interno para que el contenido no pegue a los bordes */
        background-color: #ffffff;
        /* Fondo blanco para el contenido */
        border-radius: 8px;
        /* Bordes ligeramente redondeados */
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        /* Sombra suave para un efecto elevado */
        font-family: 'Arial', sans-serif;
        /* Fuente legible */
        line-height: 1.6;
        /* Espaciado entre líneas para mejor lectura */
        color: #333;
        /* Color de texto general */
    }

    /* Título principal de los términos */
    .terms-container h1 {
        font-size: 2.5em;
        /* Tamaño grande para el título principal */
        color: #0056b3;
        /* Un color distintivo para el título */
        text-align: center;
        /* Centra el título */
        margin-bottom: 40px;
        /* Espacio debajo del título */
        border-bottom: 2px solid #eee;
        /* Línea divisoria sutil */
        padding-bottom: 15px;
        /* Espacio entre el título y la línea */
    }

    /* Estilos para cada sección individual de los términos */
    .term-section {
        margin-bottom: 30px;
        /* Espacio entre cada sección */
    }

    /* Título de cada sección (e.g., "1. Identificación de la Empresa") */
    .term-section h2 {
        font-size: 1.8em;
        /* Tamaño adecuado para los subtítulos */
        color: #007bff;
        /* Color ligeramente diferente para los subtítulos */
        margin-bottom: 15px;
        /* Espacio debajo del subtítulo */
        padding-bottom: 5px;
        /* Espacio entre el subtítulo y la línea */
        border-bottom: 1px solid #f0f0f0;
        /* Línea divisoria más delgada */
    }

    /* Párrafos de contenido */
    .term-section p {
        font-size: 1.1em;
        /* Tamaño de fuente para el contenido */
        margin-bottom: 15px;
        /* Espacio entre párrafos */
        text-align: justify;
        /* Justifica el texto para una apariencia más formal */
    }

    /* Estilo para listas o elementos que pueden tener saltos de línea internos */
    /* Esto se aplica a los <br> que insertamos para los saltos de línea en el JSON */
    .term-section p br {
        margin-bottom: 0.5em;
        /* Añade un pequeño espacio vertical entre líneas forzadas */
    }

    /* Media Queries para Responsividad */
    @media (max-width: 768px) {
        #termsAndConditionsModal {
            margin-top: 10vw;
        }

        .terms-container {
            margin: 20px auto;
            padding: 20px;
        }

        .terms-container h1 {
            font-size: 2em;
            margin-bottom: 30px;
        }

        .term-section h2 {
            font-size: 1.5em;
        }

        .term-section p {
            font-size: 1em;
        }
    }

    @media (max-width: 480px) {
        #termsAndConditionsModal {
            margin-top: 15vw;
        }

        .terms-container {
            margin: 15px;
            /* Más margen en móviles para que no pegue a los bordes de la pantalla */
            padding: 15px;
        }

        .terms-container h1 {
            font-size: 1.8em;
            margin-bottom: 20px;
        }

        .term-section h2 {
            font-size: 1.3em;
        }

        .term-section p {
            font-size: 0.95em;
        }
    }
</style>

<?php require_once 'views/layouts/footer.php'; ?>