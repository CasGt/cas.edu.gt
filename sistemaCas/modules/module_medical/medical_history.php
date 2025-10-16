<?php
session_start();
include '../shared/role_validation.php';
include '../shared/alerts.php';
include '../../db/connection.php';
validateAccess('medicina');
?>
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

<?php include '../shared/navbar.php'; ?>

<div class="mt-4"></div>

<div class="container mx-auto p-6 bg-white rounded-lg shadow-md">
    <h1 class="text-2xl font-bold mb-6 text-gray-700 text-center">Historial médico brindado</h1>

    <div class="flex justify-between items-center mb-4">
        <div>
            <label for="filterYear" class="mr-2 text-gray-700 font-bold">Filtrar por año:</label>
            <select id="filterYear" class="border border-gray-300 rounded px-2 py-1">
                <option value="">Todos los años</option>
                <option value="2025">2025</option>
                <option value="2024">2024</option>
                <option value="2023">2023</option>
            </select>
        </div>
        <div>
            <label for="searchInput" class="mr-2 text-gray-700 font-bold">Buscar:</label>
            <input 
                type="text" 
                id="searchInput" 
                class="border border-gray-300 rounded px-2 py-1" 
                placeholder="Buscar por código o fecha..."
            />
        </div>
    </div>

    <div class="overflow-x-auto">
        <table id="miTabla" class="w-full bg-white border border-gray-300 rounded-lg shadow-md text-center">
            <thead>
                <tr class="bg-gray-100">
                    <th class="text-sm p-2">ID Historia</th>
                    <th class="text-sm p-2">Código Alumno</th>
                    <th class="text-sm p-2">Fecha Asistencia</th>
                    <th class="text-sm p-2">Observación</th>
                    <th class="text-sm p-2">Síntomas</th>
                    <th class="text-sm p-2">Tratamiento</th>
                    <th class="text-sm p-2">Relevancia</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    <div id="pagination" class="flex justify-center mt-4"></div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const yearSelect = document.getElementById("filterYear");
    const searchInput = document.getElementById("searchInput");
    const tableBody = document.querySelector("#miTabla tbody");
    const pagination = document.getElementById("pagination");

    const rowsPerPage = 10;
    let currentPage = 1;
    let filteredData = [];

    const loadMedicalConsultations = async (year = "", search = "") => {
        let url = `../api/get_medical_consultations.php`;
        const params = new URLSearchParams();
        if (year) params.append("year", year);
        if (search) params.append("search", search);

        try {
            const response = await fetch(`${url}?${params.toString()}`);
            const data = await response.json();

            if (data.success) {
                filteredData = data.data;
                currentPage = 1;
                renderTable();
                renderPagination();
            } else {
                tableBody.innerHTML = `<tr><td colspan="7" class="text-center text-gray-500 p-4">No se encontraron registros.</td></tr>`;
            }
        } catch (error) {
            console.error('Error al cargar los datos:', error);
            tableBody.innerHTML = `<tr><td colspan="7" class="text-center text-red-500 p-4">Error al cargar los datos.</td></tr>`;
        }
    };

    const renderTable = () => {
        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        const pageData = filteredData.slice(start, end);

        tableBody.innerHTML = pageData.length
            ? pageData.map(row => `
                <tr class="hover:bg-gray-100">
                    <td class="border p-2">${row.id_historial_asistencia_medica}</td>
                    <td class="border p-2">${row.codigo_alumno}</td>
                    <td class="border p-2">${row.fecha_asistencia}</td>
                    <td class="border p-2">${row.observacion || 'N/A'}</td>
                    <td class="border p-2">${row.sintomas || 'N/A'}</td>
                    <td class="border p-2">${row.tratamiento || 'N/A'}</td>
                    <td class="border p-2">${row.relevancia || 'N/A'}</td>
                </tr>
            `).join('')
            : `<tr><td colspan="7" class="text-center text-gray-500 p-4">No se encontraron registros.</td></tr>`;
    };

 const renderPagination = () => {
    pagination.innerHTML = '';
    const totalPages = Math.ceil(filteredData.length / rowsPerPage);

    const maxVisibleButtons = 7; 
    let startPage = Math.max(1, currentPage - Math.floor(maxVisibleButtons / 2));
    let endPage = Math.min(totalPages, startPage + maxVisibleButtons - 1);

    if (endPage - startPage + 1 < maxVisibleButtons) {
        startPage = Math.max(1, endPage - maxVisibleButtons + 1);
    }

    if (currentPage > 1) {
        const firstButton = document.createElement("button");
        firstButton.textContent = "«";
        firstButton.className = "px-3 py-1 mx-1 rounded bg-gray-200 hover:bg-red-400";
        firstButton.addEventListener("click", () => {
            currentPage = 1;
            renderTable();
            renderPagination();
        });
        pagination.appendChild(firstButton);
    }

    if (currentPage > 1) {
        const prevButton = document.createElement("button");
        prevButton.textContent = "‹";
        prevButton.className = "px-3 py-1 mx-1 rounded bg-gray-200 hover:bg-red-400";
        prevButton.addEventListener("click", () => {
            currentPage--;
            renderTable();
            renderPagination();
        });
        pagination.appendChild(prevButton);
    }

    for (let i = startPage; i <= endPage; i++) {
        const button = document.createElement("button");
        button.textContent = i;
        button.className = `px-3 py-1 mx-1 rounded ${i === currentPage ? "bg-red-900 text-white" : "bg-gray-200 hover:bg-red-400"}`;
        button.addEventListener("click", () => {
            currentPage = i;
            renderTable();
            renderPagination();
        });
        pagination.appendChild(button);
    }

    if (currentPage < totalPages) {
        const nextButton = document.createElement("button");
        nextButton.textContent = "›";
        nextButton.className = "px-3 py-1 mx-1 rounded bg-gray-200 hover:bg-red-400";
        nextButton.addEventListener("click", () => {
            currentPage++;
            renderTable();
            renderPagination();
        });
        pagination.appendChild(nextButton);
    }

    if (currentPage < totalPages) {
        const lastButton = document.createElement("button");
        lastButton.textContent = "»";
        lastButton.className = "px-3 py-1 mx-1 rounded bg-gray-200 hover:bg-red-400";
        lastButton.addEventListener("click", () => {
            currentPage = totalPages;
            renderTable();
            renderPagination();
        });
        pagination.appendChild(lastButton);
    }
};

    yearSelect.addEventListener("change", () => loadMedicalConsultations(yearSelect.value, searchInput.value));
    searchInput.addEventListener("input", () => loadMedicalConsultations(yearSelect.value, searchInput.value));

    loadMedicalConsultations();
});
</script>

</body>
</html>

