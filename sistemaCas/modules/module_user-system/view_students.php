<?php
session_start();
require '../../db/connection.php';
require '../shared/role_validation.php';
require '../shared/alerts.php';
require './modals/modal_edit_students.php';
require './modals/modal_edit_padre.php';
require './modals/modal_edit_madre.php';
header('Content-Type: text/html; charset=utf-8');

validateAccess('estudiantes');

$years = [];
$query_years = "SELECT DISTINCT cicloActual FROM alumno WHERE cicloActual IS NOT NULL AND cicloActual != '' ORDER BY cicloActual ASC";
$result_years = $conn->query($query_years);
if ($result_years && $result_years->num_rows > 0) {
    while ($row = $result_years->fetch_assoc()) {
        $years[] = $row['cicloActual'];
    }
}

$grades = [];
$query_grades = "SELECT DISTINCT grado_alumno FROM alumno WHERE grado_alumno IS NOT NULL AND grado_alumno != '' ORDER BY grado_alumno ASC";
$result_grades = $conn->query($query_grades);
if ($result_grades && $result_grades->num_rows > 0) {
    while ($row = $result_grades->fetch_assoc()) {
        $grades[] = $row['grado_alumno'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información Estudiantes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../../src/css/styles.css">
</head>
<body class="bg-gray-100">
    <?php include '../shared/navbar.php'; ?>
    
    <div class="container mx-auto px-4 py-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Información estudiantes inscritos</h1>

        <!-- Filtros -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-4">
            <div class="mb-2 md:mb-0">
                <label for="year-filter" class="text-gray-700 text-sm font-medium mr-2">Selecciona el año:</label>
                <select id="year-filter" class="border border-gray-300 rounded-md px-2 py-1 text-sm">
                    <?php foreach ($years as $year): ?>
                        <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-2 md:mb-0">
                <label for="grade-filter" class="text-gray-700 text-sm font-medium mr-2">Selecciona el grado:</label>
                <select id="grade-filter" class="border border-gray-300 rounded-md px-2 py-1 text-sm">
                    <option value="">Todos</option>
                    <?php foreach ($grades as $grade): ?>
                        <option value="<?php echo $grade; ?>">
                            <?php echo is_numeric($grade) && intval($grade) >= 1 && intval($grade) <= 12 ? 'G0' . $grade : $grade; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="search-input" class="text-gray-700 text-sm font-medium mr-2">Buscar por nombre/apellido/carnet:</label>
                <input 
                    type="text" 
                    id="search-input" 
                    class="border border-gray-300 rounded-md px-2 py-1 text-sm"
                    placeholder="Ingresa una palabra clave..."
                />
            </div>
        </div>
        <p id="total-data" class="text-gray-600 mb-4"></p>
        <?php include '../shared/table.php'; ?>
        <div id="pagination" class="flex justify-center mt-4"></div>
    </div>
    <?php include './modals/modal_edit_students.php'; ?>

    <script>
    document.addEventListener("DOMContentLoaded", () => {
    const tableHeaders = document.getElementById("table-headers");
    const tableRows = document.getElementById("table-rows");
    const totalData = document.getElementById("total-data");
    const yearFilter = document.getElementById("year-filter");
    const gradeFilter = document.getElementById("grade-filter");
    const searchInput = document.getElementById("search-input");
    const pagination = document.getElementById("pagination");
    const headers = [
        "No.", "Editar", "Carnet", "Nombres", "Apellidos",
        "Correo", "Grado", "Nacimiento", "Encargado que llenó el formulario", "Padre", "Madre"
    ];
    const rowsPerPage = 30;
    let currentPage = 1;
    let filteredData = [];
    headers.forEach(header => {
        const th = document.createElement("th");
        th.className = "border border-gray-200 px-2 py-1 text-sm";
        th.textContent = header;
        if (header === "Correo") {
            th.id = "copy-emails";
            th.style.cursor = "pointer";
            th.title = "Haga clic para copiar todos los correos";
            th.addEventListener("click", () => {
                const emails = [];
                tableRows.querySelectorAll("tr").forEach(row => {
                    const emailCell = row.querySelector("td:nth-child(6)");
                    if (emailCell && emailCell.textContent.trim() !== "") {
                        emails.push(emailCell.textContent.trim());
                    }
                });
                if (emails.length > 0) {
                    const emailsString = emails.join(", ");
                    // Copiar al portapapeles
                    navigator.clipboard.writeText(emailsString)
                        .then(() => {
                            alert("Correos copiados al portapapeles.");
                        })
                        .catch(err => {
                            console.error("Error al copiar los correos:", err);
                        });
                } else {
                    alert("No hay correos para copiar.");
                }
            });
        }

        tableHeaders.appendChild(th);
    });
    const loadTableData = (year, grade = "", search = "", page = 1, rowsPerPage = 30) => {
        const status = 1; // Estado fijo para view_student
        const formattedGrade = grade.startsWith("G0") ? grade.slice(2) : grade;
        fetch(`../api/get_student.php?year=${year}&status=${status}&grade=${formattedGrade}&search=${search}&page=${page}&rowsPerPage=${rowsPerPage}`)
            .then(response => response.json())
            .then(data => {
                console.log("Datos recibidos del servidor:", data);
                if (data.success) {
                    filteredData = data.data;
                    currentPage = page;
                    renderTable();
                } else {
                    tableRows.innerHTML = `<tr><td colspan="10" class="text-center text-gray-500">No se encontraron datos.</td></tr>`;
                }
            })
            .catch(error => console.error("Error al cargar los datos:", error));
    };
    const renderTable = () => {
        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        const pageData = filteredData.slice(start, end);
        tableRows.innerHTML = "";
        pageData.forEach((row, index) => {
            const tr = document.createElement("tr");
            tr.className = "border-t border-gray-200";
            tr.innerHTML = `
                <td class="border border-gray-200 px-2 py-1 text-sm">${start + index + 1}</td>
            
                <td class="border border-gray-200 px-2 py-1 text-center text-sm">
                    <button class="bg-gray-300 text-white px-2 py-1 rounded hover:bg-gray-400" onclick="openModalEstudiante('${row.codigo_alumno}','${row.cicloActual}')">
                    
                        <img src="../../src/images/lapiz.png" alt="Editar" class="w-4 h-4 inline">
                    </button>
                </td>
                <td class="border border-gray-200 px-2 py-1 text-sm">${row.carnet}</td>
                <td class="border border-gray-200 px-2 py-1 text-sm">${row.nombres}</td>
                <td class="border border-gray-200 px-2 py-1 text-sm">${row.apellidos}</td>
                <td class="border border-gray-200 px-2 py-1 text-sm">${row.correo}</td>
                <td class="border border-gray-200 px-2 py-1 text-sm">${row.grado}</td>
                <td class="border border-gray-200 px-2 py-1 text-sm">${row.fecha_nacimiento}</td>
                <td class="border border-gray-200 px-2 py-1 text-sm">${row.correo_encargado || ''}</td>
                <td class="border border-gray-200 px-2 py-1 text-center text-sm">
                    <button class="bg-gray-300 text-white px-2 py-1 rounded hover:bg-red-300" onclick="openModalPadre('${row.codigo_alumno}','${row.cicloActual}')">
                        <img src="../../src/images/hombre.png" alt="Padre" class="w-4 h-4 inline">
                    </button>
                </td>
                <td class="border border-gray-200 px-2 py-1 text-center text-sm">
                    <button class="bg-gray-300 text-white px-2 py-1 rounded hover:bg-pink-300" onclick="openModalMadre('${row.codigo_alumno}','${row.cicloActual}')">
                        <img src="../../src/images/mujer.png" alt="Madre" class="w-4 h-4 inline">
                    </button>
                </td>
            `;
            tableRows.appendChild(tr);
        });
        totalData.textContent = `Mostrando ${start + 1} a ${Math.min(end, filteredData.length)} de ${filteredData.length} registros`;
        renderPagination();
    };
    const renderPagination = () => {
        pagination.innerHTML = "";
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
    yearFilter.addEventListener("change", () => loadTableData(yearFilter.value, gradeFilter.value, searchInput.value));
    gradeFilter.addEventListener("change", () => loadTableData(yearFilter.value, gradeFilter.value, searchInput.value));
    searchInput.addEventListener("input", () => loadTableData(yearFilter.value, gradeFilter.value, searchInput.value));

    loadTableData(yearFilter.value || new Date().getFullYear());
});


    </script>
</body>
</html>
