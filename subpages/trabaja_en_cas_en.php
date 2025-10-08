<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ofertas Laborales - Imágenes</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <!-- Header -->
    <header class="py-8">
        <div class="flex items-center justify-between px-8">
            <img src="../src/img/logo_cas_red.webp" alt="Colegio Americano Logo" class="h-16">
            <a href="https://cas.edu.gt/" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Regresar</a>
        </div>

        <div class="text-center mt-4">
            <hr class="mt-4 border-t-2 border-gray-300">
            <h1 class="py-2 text-3xl font-bold text-center text-black mb-6">Ofertas Laborales</h1>
        </div>
    </header>

    <!-- Grid de imágenes -->
   <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
    <?php
    // Directorio de las imágenes
    $directory = '../sistemaCas/src/images/works_places/';
    $images = glob($directory . '*.{jpg,png,gif}', GLOB_BRACE);

    if (!empty($images)) {
        foreach ($images as $image) {
            $imageName = basename($image);
            echo "<div class='bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300'>
                    <img src='$directory$imageName' alt='$imageName' class='w-full h-60 md:h-72 lg:h-96 object-contain'>
                    <div class='p-4'>
                        
                    </div>
                  </div>";
        }
    } else {
        echo "<p class='text-center col-span-full'>No hay imágenes disponibles.</p>";
    }
    ?>
    </div>

    <!-- Sección de Guatemala y trabajar en CAS -->
    <section class="relative bg-cover bg-center mt-12" style="background-image: url('ruta_a_fondo_guatemala.jpg');">
        <div class="bg-red-900  py-16">
            <div class="container mx-auto px-4 text-center text-white">
                <h2 class="text-5xl font-bold mb-8">Explora una Nueva Aventura en Guatemala</h2>
                <p class="text-xl mb-12 max-w-2xl mx-auto leading-relaxed">
                    Guatemala, con su riqueza cultural, paisajes impresionantes y una comunidad vibrante, te ofrece no solo una experiencia educativa única, sino también una oportunidad de vivir una aventura inolvidable. Desde sus volcanes majestuosos hasta su herencia maya, este país te invita a descubrir cada rincón mientras enriqueces la vida de nuestros estudiantes.
                </p>

                <img src="../src/img/visita_guatemala.png" alt="Paisaje de Guatemala" class="mx-auto rounded-lg shadow-lg mb-12 h-96 w-full object-cover transition-transform transform hover:scale-105 duration-500 ease-in-out">

                <div class="bg-white text-gray-900 p-12 rounded-lg shadow-md max-w-3xl mx-auto">
                    <h3 class="text-4xl font-semibold mb-6">Únete al CAS: Una Comunidad Global</h3>
                    <p class="text-lg mb-6">
                        En el Colegio Americano del Sur (CAS), estamos comprometidos con brindar a nuestros estudiantes una experiencia educativa integral. Como parte de este compromiso, contratamos a docentes extranjeros que aportan no solo su experiencia en la enseñanza, sino también la riqueza de haber viajado y conocido otras culturas, algo que comparten con los estudiantes en diversas áreas de estudio.
                    </p>
                    <p class="text-lg mb-8">
                        Invitamos a docentes de todo el mundo a formar parte de nuestra gran familia en CAS, viviendo y trabajando en un entorno multicultural en el corazón de Guatemala. La experiencia de enseñar aquí enriquecerá tanto tu vida como la de nuestros estudiantes.
                    </p>

                  
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-6 text-center text-gray-500 mt-12">
        Colegio Americano del Sur
    </footer>
</body>
</html>
