<?php
session_start();
include '../../db/connection.php';
include '../shared/role_validation.php';
include '../shared/alerts.php';
include './modals/modal_edit_students.php';

validateAccess('estudiantes');

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
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Gestión de Formularios</h1>

        <!-- Filtros -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-4">
          
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
                <label for="search-input" class="text-gray-700 text-sm font-medium mr-2">Buscar:</label>
                <input 
                    type="text" 
                    id="search-input" 
                    class="border border-gray-300 rounded-md px-2 py-1 text-sm"
                    placeholder="Ingresa una palabra clave..."
                />
            </div>
        </div>

        <p id="total-data" class="text-gray-600 mb-4"></p>

        <div class="overflow-x-auto">
    <table class="table-auto w-full text-center text-xs">
        <thead>
            <tr class="bg-gray-200">
                <th class="px-2 py-1">No.</th>
                <th class="px-2 py-1">Acciones</th>
                <th class="px-2 py-1">Carnet</th>
                <th class="px-2 py-1">Nombres</th>
                <th class="px-2 py-1">Apellidos</th>
                <th class="px-2 py-1">Correo</th>
                <th class="px-2 py-1">Grado</th>
                <th class="px-2 py-1">Nacimiento</th>
                <th class="px-2 py-1">Correo Encargado</th>
                <th class="px-2 py-1">Fecha creación</th>
            </tr>
        </thead>
        <tbody id="table-rows" class="text-sm"></tbody>
    </table>
</div>
        <div id="pagination" class="flex justify-center mt-4"></div>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", () => {
    const tableRows = document.getElementById("table-rows");
    const totalData = document.getElementById("total-data");
    const gradeFilter = document.getElementById("grade-filter");
    const searchInput = document.getElementById("search-input");
    const pagination = document.getElementById("pagination");
    const rowsPerPage = 30;
    let currentPage = 1;
    let filteredData = [];

    window.updateStudentStatus = (id, codigoAlumno, updatedAt, estado) => {
        if (!id || !codigoAlumno || !updatedAt) {
            console.error("Datos inválidos:", { id, codigoAlumno, updatedAt, estado });
            alert("Error: Datos incompletos para actualizar el estado.");
            return;
        }

        const payload = { action: 'pending', id, codigo_alumno: codigoAlumno, updated_at: updatedAt, estado };

        console.log("Datos enviados al servidor:", payload);

        fetch('../api/put_student.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload),
        })
            .then((response) => response.json())
            .then((data) => {
                console.log("Respuesta del servidor:", data);

                if (data.message) {
                    alert(data.message);
                    loadTableData();
                } else {
                    alert('Error: ' + (data.error || 'Ocurrió un problema desconocido'));
                }
            })
            .catch((error) => {
                console.error('Error al actualizar el estado:', error);
                alert('Error en la solicitud');
            });
    };

    const loadTableData = async (grade = "", search = "") => {
        const query = `../api/get_student.php?action=pending&grade=${grade}&search=${search}`;

        try {
            const response = await fetch(query);
            const data = await response.json();

            if (data.success) {
                filteredData = data.data;
                currentPage = 1;
                renderTable();
                renderPagination();
            } else {
                console.error("Error en la respuesta del servidor:", data);
            }
        } catch (error) {
            console.error("Error al cargar los datos:", error);
        }
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
                <td>${start + index + 1}</td>
                <td>
                    <button 
                        class="bg-red-500 text-white px-1 py-0.5 rounded hover:bg-red-700"
                        onclick="updateStudentStatus(${row.id}, '${row.codigo_alumno}', '${row.updated_at}', 3)">
                        <img src="../../src/images/eliminar.png" alt="Eliminar" class="w-3 h-3 inline">
                    </button>
                    <button 
                        class="bg-green-500 text-white px-1 py-0.5 rounded hover:bg-green-700"
                        onclick="updateStudentStatus(${row.id}, '${row.codigo_alumno}', '${row.updated_at}', 1)">
                        <img src="../../src/images/activar.png" alt="Habilitar" class="w-3 h-3 inline">
                    </button>
                </td>
                <td>${row.carnet}</td>
                <td>${row.nombres}</td>
                <td>${row.apellidos}</td>
                <td>${row.correo}</td>
                <td>${row.grado}</td>
                <td>${row.fecha_nacimiento}</td>
                <td>${row.correo_encargado || "N/A"}</td>
                <td>${row.updated_at}</td>
            `;
            tableRows.appendChild(tr);
        });

        totalData.textContent = `Mostrando ${start + 1} a ${Math.min(end, filteredData.length)} de ${filteredData.length} registros`;
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
                updateActivePaginationButton();
            });
            pagination.appendChild(button);
        }
    };

    const updateActivePaginationButton = () => {
        Array.from(pagination.children).forEach((button, index) => {
            if (index + 1 === currentPage) {
                button.classList.add("bg-red-600", "text-white");
                button.classList.remove("bg-red-200", "hover:bg-red-400");
            } else {
                button.classList.remove("bg-red-600", "text-white");
                button.classList.add("bg-red-200", "hover:bg-red-400");
            }
        });
    };

    gradeFilter.addEventListener("change", () => loadTableData(gradeFilter.value, searchInput.value));
    searchInput.addEventListener("input", () => loadTableData(gradeFilter.value, searchInput.value));
    loadTableData();
});

    </script>
</body>
</html>
