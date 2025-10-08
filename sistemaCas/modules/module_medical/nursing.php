<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial Médico - Área de Enfermería</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
</head>
<body class="text-gray-800">

<!-- Navbar -->
<?php include '../shared/navbar.php'; ?>

<!-- Espaciador para evitar que la tabla se superponga al navbar -->
<div class="mt-4"></div>

<div class="container mx-auto p-6 bg-white rounded-lg shadow-md">
    <h1 class="text-2xl font-bold mb-6 text-gray-700 text-center">Perfil médico</h1>

    <!-- Filtros -->
 <div class="flex justify-between items-center mb-4">
        <div>
            <label for="filterYear" class="mr-2 text-gray-700 font-bold">Filtrar por año:</label>
            <select id="filterYear" class="border border-gray-300 rounded px-2 py-1">
                <option value="">Todos los años</option>
            </select>
        </div>
        <div>
            <label for="filterGrade" class="mr-2 text-gray-700 font-bold">Filtrar por grado:</label>
            <select id="filterGrade" class="border border-gray-300 rounded px-2 py-1">
                <option value="">Todos los grados</option>
            </select>
        </div>
        <div>
            <label for="searchInput" class="mr-2 text-gray-700 font-bold">Buscar:</label>
            <input 
                type="text" 
                id="searchInput" 
                class="border border-gray-300 rounded px-2 py-1" 
                placeholder="Buscar estudiante..."
            />
        </div>
    </div>


    <!-- Frase de registros -->
    <p id="totalData" class="text-gray-600 mb-4"></p>

    <!-- Tabla -->
    <div class="overflow-x-auto">
        <table id="miTabla" class="w-full bg-white border border-gray-300 rounded-lg shadow-md text-center">
            <thead>
                <tr class="bg-gray-100">
                    <th class="text-sm p-2">Carnet</th>
                    <th class="text-sm p-2">Nombres</th>
                    <th class="text-sm p-2">Apellidos</th>
                    <th class="text-sm p-2">Grado</th>
                    <th class="text-sm p-2">Última visita</th>
                    <th class="text-sm p-2">Ciclo</th>
                    <th class="text-sm p-2">Acción</th>
                </tr>
            </thead>
            <tbody>
                <!-- Filas generadas dinámicamente -->
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div id="pagination" class="flex justify-center mt-4"></div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const yearSelect = document.getElementById("filterYear");
    const gradeSelect = document.getElementById("filterGrade");
    const searchInput = document.getElementById("searchInput");
    const tableBody = document.querySelector("#miTabla tbody");
    const totalData = document.getElementById("totalData");
    const pagination = document.getElementById("pagination");

    const rowsPerPage = 30;
    let currentPage = 1;
    let filteredData = [];

    // Función para cargar estudiantes, años y grados
    const loadStudentsAndFilters = async (year = "", grade = "", search = "") => {
        let url = `../api/get_student.php?action=recent_or_previous&get_years=true`;
        if (year) url += `&ciclo_actual=${year}`;
        if (grade) url += `&grado=${grade}`;
        if (search) url += `&search=${encodeURIComponent(search)}`;

        try {
            const response = await fetch(url);
            const data = await response.json();

            // Cargar años en el filtro
            if (data.years && yearSelect.options.length === 1) {
                yearSelect.innerHTML += data.years
                    .map(year => `<option value="${year}">${year}</option>`)
                    .join('');
            }

            // Cargar grados en el filtro
            if (data.grados && gradeSelect.options.length === 1) {
                gradeSelect.innerHTML += data.grados
                    .map(grade => `<option value="${grade}">${grade}</option>`)
                    .join('');
            }

            // Renderizar tabla
            if (data.success && data.data.length > 0) {
                filteredData = data.data;
                currentPage = 1;
                renderTable();
                renderPagination();
            } else {
                tableBody.innerHTML = `<tr><td colspan="7" class="text-center text-gray-500 p-4">No se encontraron estudiantes.</td></tr>`;
                totalData.textContent = '';
                pagination.innerHTML = '';
            }
        } catch (error) {
            console.error('Error al cargar los datos:', error);
            tableBody.innerHTML = `<tr><td colspan="7" class="text-center text-red-500 p-4">Error al cargar los datos.</td></tr>`;
        }
    };

    // Función para renderizar la tabla
    const renderTable = () => {
        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        const pageData = filteredData.slice(start, end);

        tableBody.innerHTML = pageData.map(student => `
            <tr class="hover:bg-gray-100">
                <td class="border p-2">${student.carnet || 'N/A'}</td>
                <td class="border p-2">${student.nombres || 'N/A'}</td>
                <td class="border p-2">${student.apellidos || 'N/A'}</td>
                <td class="border p-2">${student.grado || 'N/A'}</td>
                <td class="border p-2">${student.updated_at || 'N/A'}</td>
                <td class="border p-2">${student.cicloActual || 'N/A'}</td>
                <td class="border p-2">
                    <a href="./medical_profile.php?codigo_alumno=${encodeURIComponent(student.codigo_alumno)}&carnet=${encodeURIComponent(student.carnet)}&nombres=${encodeURIComponent(student.nombres)}&apellidos=${encodeURIComponent(student.apellidos)}&grado=${encodeURIComponent(student.grado)}&fecha=${encodeURIComponent(student.updated_at)}&ciclo_actual=${encodeURIComponent(student.cicloActual)}" 
   class="text-blue-500 hover:underline">Historial médico</a>

                </td>
            </tr>
        `).join('');

        totalData.textContent = `Mostrando ${start + 1} a ${Math.min(end, filteredData.length)} de ${filteredData.length} registros.`;
    };

    // Función para renderizar la paginación
    const renderPagination = () => {
        pagination.innerHTML = '';
        const totalPages = Math.ceil(filteredData.length / rowsPerPage);

        for (let i = 1; i <= totalPages; i++) {
            const button = document.createElement("button");
            button.textContent = i;
            button.className = `px-3 py-1 mx-1 rounded ${i === currentPage ? "bg-red-900 text-white" : "bg-gray-200 hover:bg-red-400"}`;
            button.addEventListener("click", () => {
                currentPage = i;
                renderTable();
            });
            pagination.appendChild(button);
        }
    };

    // Eventos de filtros
    yearSelect.addEventListener("change", () => loadStudentsAndFilters(yearSelect.value, gradeSelect.value, searchInput.value));
    gradeSelect.addEventListener("change", () => loadStudentsAndFilters(yearSelect.value, gradeSelect.value, searchInput.value));
    searchInput.addEventListener("input", () => loadStudentsAndFilters(yearSelect.value, gradeSelect.value, searchInput.value));

    // Cargar datos iniciales
    loadStudentsAndFilters();
});

</script>

</body>
</html>
