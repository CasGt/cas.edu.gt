<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Becas</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-50 text-gray-800 font-sans">

  <header class="bg-blue-600 text-white py-4 shadow-md">
    <div class="container mx-auto px-4 flex justify-between items-center">
      <h1 class="text-2xl font-bold">Colegio Americano del Sur - CAS</h1>
      <nav>
        <a href="#features" class="text-sm font-medium hover:underline">Inicio</a>
        <a href="#procedimiento" class="ml-4 text-sm font-medium hover:underline">Proceso</a>
        <a href="#contacto" class="ml-4 text-sm font-medium hover:underline">Contacto</a>
      </nav>
    </div>
  </header>

  <main class="container mx-auto px-4 mt-8">

    <!-- Breadcrumb -->
    <section class="text-center mb-8">
      <h2 class="text-4xl font-bold text-blue-600">Becas</h2>
      <p class="text-gray-500 mt-2">Asociación Becaria Guatemalteca</p>
    </section>

    <!-- Features -->
    <section id="features" class="bg-white shadow rounded-lg p-8 mb-10">
      <h2 class="text-2xl font-bold text-gray-700 mb-4">Becas de <span class="text-blue-600">Asociación Becaria Guatemalteca</span></h2>
      <p class="text-gray-600 leading-relaxed mb-4">
        El Colegio Americano del Sur - CAS - es una institución comprometida con el desarrollo de la comunidad...
      </p>
      <p class="text-gray-600 leading-relaxed mb-4">
        La ABG tiene como objetivo primordial brindar a estudiantes de limitados recursos económicos...
      </p>
      <p class="text-gray-600 leading-relaxed">
        Cada año, la Junta Directiva de la ABG analiza la apertura de convocatoria para becas de acuerdo a la situación...
      </p>
    </section>

    <!-- Tabs -->
<section class="mb-10">
  <h2 class="text-2xl font-bold text-gray-700 mb-4 text-center">Experiencias de estudiantes</h2>

  <!-- Contenedor de botones y PDFs -->
  <div class="grid grid-cols-1 gap-4">
    <!-- Botones por encima del PDF -->
    <div class="flex justify-center space-x-4">
      <button
        class="p-2 px-4 border rounded bg-blue-100 hover:bg-blue-200 focus:ring-2 focus:ring-blue-400 focus:outline-none"
        onclick="showPDF('tab-1')"
      >
        Estudiante 2021
      </button>
      <button
        class="p-2 px-4 border rounded hover:bg-blue-100 focus:ring-2 focus:ring-blue-400 focus:outline-none"
        onclick="showPDF('tab-2')"
      >
        Estudiante 2019
      </button>
      <button
        class="p-2 px-4 border rounded hover:bg-blue-100 focus:ring-2 focus:ring-blue-400 focus:outline-none"
        onclick="showPDF('tab-3')"
      >
        Estudiante 2018
      </button>
    </div>

    <!-- Contenedor del PDF -->
    <div class="border rounded shadow-lg bg-white p-4">
      <div id="tab-1" class="pdf-container">
        <iframe
          src="https://docs.google.com/gview?url=https://www.cas.edu.gt/assets/img/Cartas2021.pdf&embedded=true"
          class="w-full h-[600px] border rounded"
        ></iframe>
      </div>
      <div id="tab-2" class="pdf-container hidden">
        <iframe
          src="https://docs.google.com/gview?url=https://www.cas.edu.gt/assets/img/Carta2019.pdf&embedded=true"
          class="w-full h-[600px] border rounded"
        ></iframe>
      </div>
      <div id="tab-3" class="pdf-container hidden">
        <iframe
          src="https://docs.google.com/gview?url=https://www.cas.edu.gt/assets/img/Carta2018.pdf&embedded=true"
          class="w-full h-[600px] border rounded"
        ></iframe>
      </div>
    </div>
  </div>
</section>

<script>
  // Mostrar por defecto el primer PDF
  window.onload = function () {
    showPDF('tab-1');
  };

  function showPDF(tabId) {
    // Oculta todos los PDFs
    document.querySelectorAll('.pdf-container').forEach((el) => {
      el.classList.add('hidden');
    });
    // Muestra el PDF correspondiente
    document.getElementById(tabId).classList.remove('hidden');
  }
</script>



    <!-- Requisitos -->
    <section class="bg-white shadow rounded-lg p-8 mb-10">
      <h2 class="text-2xl font-bold text-gray-700 mb-4 text-center">Requisitos de Inscripción</h2>
      <ul class="space-y-2 text-gray-600 list-disc pl-5">
        <li>Ser estudiante de nuevo ingreso en el CAS.</li>
        <li>Promedio superior a 85 puntos.</li>
        <li>Carta de solicitud dirigida a ABG.</li>
        <li>Estar cursando de Segundo Primaria a Sexto Primaria.</li>
      </ul>
    </section>

    <!-- Proceso de selección -->
    <section id="procedimiento" class="bg-white shadow rounded-lg p-8">
      <h2 class="text-2xl font-bold text-gray-700 mb-4">Proceso de selección</h2>
      <ol class="list-decimal pl-5 space-y-2 text-gray-600">
        <li>Realizar prueba de habilidad académica con el departamento de orientación del CAS.</li>
        <li>Recibirá una notificación si el estudiante continúa el proceso.</li>
        <li>Completar el estudio socioeconómico de ABG.</li>
        <li>La Asociación realiza un análisis de la papelería recibida.</li>
        <li>La ABG selecciona a los candidatos que cumplen con los requisitos.</li>
        <li>Un comité de ABG visita al candidato para hacer una entrevista al estudiante...</li>
      </ol>
    </section>

    <!-- Contacto -->
    <section id="contacto" class="mt-10 text-center">
      <h2 class="text-2xl font-bold text-gray-700 mb-4">¿Cómo aplicar?</h2>
      <p class="text-gray-600 mb-4">
        Los padres interesados en que sus hijos estudien en el CAS con apoyo financiero deben comunicarse...
      </p>
      <a href="tel:46361832" class="text-blue-600 font-bold hover:underline">4636-1832</a> o 
      <a href="mailto:secretariacas@cas.edu.gt" class="text-blue-600 font-bold hover:underline">secretariacas@cas.edu.gt</a>
    </section>
  </main>

  <footer class="bg-gray-800 text-white text-center py-4 mt-10">
    <p>© 2024 Colegio Americano del Sur. Todos los derechos reservados.</p>
  </footer>

</body>

</html>
