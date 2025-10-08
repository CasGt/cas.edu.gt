<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <nav class="bg-white shadow-md p-4 flex justify-between items-center">
        <h1 class="text-xl font-bold text-gray-800">Calendario</h1>
        <a href="/" class="bg-red-900 text-white px-4 py-2 rounded-lg hover:bg-red-600">
            Regresar
        </a>
    </nav>

    <main class="flex justify-center items-center h-screen">
        <iframe 
            src="https://calendar.google.com/calendar/embed?src=cas.edu.gt_spdr1fk92n8pmek93jfnit88d8%40group.calendar.google.com&ctz=America%2FGuatemala"
            style="border: 0" 
            width="1280" 
            height="720" 
            frameborder="0" 
            scrolling="no">
        </iframe>
    </main>

</body>
</html>
