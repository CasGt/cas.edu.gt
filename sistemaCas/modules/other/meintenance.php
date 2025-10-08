<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Módulo en Mantenimiento</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .maintenance-container {
            text-align: center;
        }
        .maintenance-image {
            max-width: 400px;
            margin: 0 auto 20px;
        }
        .back-button {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #ffc107;
            color: #000;
            font-weight: bold;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .back-button:hover {
            background-color: #ff9800;
        }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <img src="../../src/images/mantenimiento.png" alt="Página en mantenimiento" class="maintenance-image">
        <h1 class="text-2xl font-bold text-gray-700">¡Estamos trabajando en ello!</h1>
        <p class="text-gray-600 mt-2">La página estará disponible pronto. Gracias por tu paciencia.</p>
        <a href="javascript:history.back()" class="back-button">Volver</a>
    </div>
</body>
</html>
