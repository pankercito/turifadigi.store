<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualización de Moneda</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/font-awesome@4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="icon-wrapper">
                <span class="coin-icon">💰</span>
            </div>
            <h1 class="title">¡Actualización Exitosa!</h1>
            <p class="message">El valor de la moneda se ha actualizado correctamente.</p>
            <p class="message">El precio de 1 USD es aproximadamente: <br>
                <i class="fa-solid fa-money-bill"></i> <?php echo $cop_price ?> COP
                <i class="fa-solid fa-money-bill"></i> <?php echo $ves_price ?> VES
            </p>

            <div class="actions">
                <button class="btn back-home">Volver al Inicio</button>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const backHomeBtn = document.querySelector('.back-home');

            // Ejemplo de funcionalidad (puedes personalizar esto)
            backHomeBtn.addEventListener('click', () => {
                window.location.href = '/'; // Ejemplo de redirección
            });

            // Pequeño script para el efecto de la tarjeta (ya manejado con CSS animation)
            // Pero si quisieras más control con JS:
            const card = document.querySelector('.card');
            card.style.opacity = '1';
            card.style.transform = 'scale(1)';
        });
    </script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #6495ED, #4682B4, #ADD8E6, #87CEFA);
            /* Gradiente de azules */
            background-size: 300% 300%;
            animation: gradientAnimation 10s ease infinite;
            color: #f8f9fa;
            /* Texto claro para contraste con el azul */
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.9);
            /* Blanco ligeramente transparente */
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            padding: 40px;
            text-align: center;
            max-width: 450px;
            width: 90%;
            animation: fadeInScale 0.8s ease-out forwards;
            color: #343a40;
            /* Texto oscuro para la tarjeta */
        }

        .icon-wrapper {
            background-color: #4682B4;
            /* Tono de azul más oscuro para el icono */
            border-radius: 50%;
            width: 100px;
            height: 100px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .coin-icon {
            font-size: 50px;
            color: #FFF;
            /* Blanco para el icono */
            line-height: 1;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        }

        .title {
            font-size: 2.5em;
            color: #007bff;
            /* Azul más brillante para el título de éxito */
            margin-bottom: 15px;
            font-weight: 700;
        }

        .message {
            font-size: 1.1em;
            color: #555;
            /* Gris oscuro para el mensaje */
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .actions {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .btn {
            background-color: #28a745;
            /* Verde para los botones de acción (manteniendo la indicación de positivo) */
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 30px;
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            text-decoration: none;
            font-weight: 600;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .btn:hover {
            background-color: #1e7e34;
            /* Tono de verde más oscuro al pasar el ratón */
            transform: translateY(-2px);
        }

        .btn.view-details {
            background-color: #0056b3;
            /* Azul más oscuro para "Ver Detalles" */
        }

        .btn.view-details:hover {
            background-color: #003d82;
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.8);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Animación de fondo */
        @keyframes gradientAnimation {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        @media (max-width: 600px) {
            .card {
                padding: 30px;
            }

            .title {
                font-size: 2em;
            }

            .message {
                font-size: 1em;
            }

            .btn {
                width: 100%;
                margin-bottom: 10px;
            }

            .actions {
                flex-direction: column;
            }
        }
    </style>
</body>

</html>