<?php
session_start();
session_regenerate_id(true);
header("Content-Type: text/html;charset=utf-8");

include '../../appmycas.cas.edu.gt/admin_docentes/model/conn.php';


		//Consultas---------------------------------------------------------------------------

    $query_inscritos = "SELECT  * FROM maestros where estado = 1 order by nivelPertence";


    $inscritos = $conn1->query($query_inscritos);
    if ($inscritos->num_rows > 0)
      {
      }
      $row_inscritos = $inscritos->fetch_array(MYSQLI_ASSOC);


		//Consultas ---------------------------------------------------------------------------

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Directory</title>
    <script src="https://cdn.tailwindcss.com"></script>
 <script src="../src/js/capturarAnio.js"></script>
</head>


<body class="bg-gray-100">
    <header class="py-8">
        <div class="flex items-center justify-between px-8">
            <img src="../src/img/logo_cas_red.webp" alt="Colegio Americano Logo" class="h-8">
            <a href="../index.html" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Return</a>
        </div>

        <div class="text-center mt-4">
            <hr class="mt-4 border-t-2 border-gray-300">
            <h1 class="py-2 text-3xl font-bold text-center text-black mb-6">CAS Directory</h1>
        </div>
    </header>

    <section class="py-5 bg-white">
        <div class="container mx-auto px-4">
            <div class="mb-6">
                <input id="search" type="text" class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring focus:ring-red-900" placeholder="Type something to filter (e.g., 'Middle School', 'Carlos')">
            </div>

            <!-- Teacher directory table -->
            <div class="overflow-x-auto">
                <table id="directory" class="min-w-full bg-white border border-gray-300 rounded-md">
                    <thead class="bg-red-900 text-white">
                        <tr>
                            <th class="p-3 text-left">First Name</th>
                            <th class="p-3 text-left">Last Name</th>
                            <th class="p-3 text-left">Level</th>
                            <th class="p-3 text-left">Position</th>
                            <th class="p-3 text-left">Ext.</th>
                            <th class="p-3 text-left">Email</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-700">
                        <?php do { ?>
                        <tr class="border-b border-gray-300 hover:bg-gray-200">
                            <td class="p-3"><?php echo explode(' ', $row_inscritos['nombresMaestro'])[0]; ?></td>
                            <td class="p-3"><?php echo explode(' ', $row_inscritos['apellidosMaestro'])[0]; ?></td>
                            <td class="p-3 text-red-900"><?php echo $row_inscritos['nivelPertence']; ?></td>
                            <td class="p-3"><?php echo $row_inscritos['puesto']; ?></td>
                            <td class="p-3"><?php echo $row_inscritos['extencionTel']; ?></td>
                            <td class="p-3"><a href="mailto:<?php echo $row_inscritos['emailMaestro']; ?>" class="text-blue-500 hover:underline"><?php echo $row_inscritos['emailMaestro']; ?></a></td>
                        </tr>
                        <?php } while ($row_inscritos = mysqli_fetch_assoc($inscritos)); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Contact information -->
    <section id="contact-us" class="py-10 bg-gray-100">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div>
                    <h5 class="text-xl font-semibold text-black mb-2">Address</h5>
                    <p class="text-gray-700">Offices: Km 92.5 Finca Camantulul<br>Santa Lucia Cotz. Escuintla, Guatemala, C.A.</p>
                </div>
                <div>
                    <h5 class="text-xl font-semibold text-black mb-2">Phone</h5>
                    <p class="text-gray-700">Secretary: +(502) 7955 2800</p>
                </div>
                <div>
                    <h5 class="text-xl font-semibold text-black mb-2">Email</h5>
                    <p class="text-gray-700">Support: lsantos@cas.edu.gt</p>
                </div>
            </div>
            <p class="text-gray-700">If you want to contact <strong>CAS</strong> more specifically, you can do so by using our main phone line (PBX) followed by the extension number you need, or by emailing the relevant staff members directly.</p>
        </div>
    </section>

    <!-- Contact form -->
    <section class="py-10 bg-white">
        <div class="container mx-auto px-4">
            <h3 class="text-2xl font-bold text-black mb-6">Contact Us</h3>
            <form action="php/contact-form.php" method="post" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 col-span-2">
                    <input type="text" name="contact_name" class="p-2 border border-gray-300 rounded focus:outline-none focus:ring focus:ring-red-900" placeholder="Full Name">
                    <input type="email" name="contact_email" class="p-2 border border-gray-300 rounded focus:outline-none focus:ring focus:ring-red-900" placeholder="Email">
                    <input type="text" name="contact_phone" class="p-2 border border-gray-300 rounded focus:outline-none focus:ring focus:ring-red-900" placeholder="Phone">
                </div>
                <textarea name="contact_message" class="col-span-2 p-2 border border-gray-300 rounded focus:outline-none focus:ring focus:ring-red-900" rows="4" placeholder="Message"></textarea>

                <!-- Submit button -->
                <button type="submit" class="col-span-2 py-2 bg-red-900 text-white font-bold rounded hover:bg-red-800">Send</button>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-6 text-center text-gray-500">
         <span id="year"></span> American School of the South
    </footer>


    <!-- Scripts -->
    <script type="text/javascript">
        let partial = function(fn) {
            let pastArgs = [...arguments].slice(1);
            return function() {
                let newArgs = [...arguments];
                return fn.apply(null, pastArgs.concat(newArgs));
            }
        };

        let extractRows = (tbody) => {
            var nodeList = tbody.querySelectorAll('tr');
            return [...nodeList].map((tr) => {
                tr.data = tr.innerText.toLowerCase();
                return tr;
            })
        };

        let updateVisibility = (text, row) => {
            row.style.display = row.data.indexOf(text) > -1 ? '' : 'none';
        };

        let filterRows = ({
            elem,
            rows,
            errorElem
        }) => {
            let inputVal = elem.value.toLowerCase();
            rows.forEach(partial(updateVisibility, inputVal));
            errorElem.innerHTML = rows.filter(tr => tr.style.display !== 'none').length === 0 ?
                `<div>No hay criterios de búsqueda para el ${inputVal} ingresado</div>` : '';
            errorElem.style.display = '';
        };

        const tableId = '#tabla';
        let tbody = document.querySelector(`${tableId} tbody`);
        let errorElem = document.createElement('tr');
        tbody.appendChild(errorElem);

        let rows = extractRows(tbody);
        let inputElem = document.getElementById('buscar');
        inputElem.onkeyup = partial(filterRows, {
            elem: inputElem,
            rows,
            errorElem
        });
    </script>
</body>

</html>

