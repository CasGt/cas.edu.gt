<?php
validateAccess('estudiantes');
?>
<div id="shared-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg">
        <div class="bg-red-900 text-white px-4 py-2 flex justify-between items-center">
            <h2 class="text-xl font-bold" id="modal-title">Editar Estudiante</h2>
            <button class="text-white hover:text-gray-300" onclick="closeModal()">&times;</button>
        </div>
        <div class="p-4" id="modal-content">
        </div>
        <div class="bg-gray-100 px-4 py-2 flex justify-end">
    <button class="bg-red-900 text-white px-4 py-2 rounded hover:bg-red-700" onclick="saveChanges()">Guardar Cambios</button>
    <button class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 ml-2" onclick="closeModal()">Cerrar</button>
</div>
    </div>
</div>

<script>
    window.openModalEstudiante = function (codigoAlumno, cicloActual) {
        console.log("Código Alumno:", codigoAlumno, "Año:", cicloActual);
        console.log(`../../modules/api/get_student.php?codigo_alumno=${codigoAlumno}&year=${cicloActual}`);

        fetch(`../../modules/api/get_student.php?codigo_alumno=${codigoAlumno}&year=${cicloActual}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const student = data.data;
                    const modalContent = `
                       <form>
                        <!-- Campo oculto para codigo_alumno type="hidden" -->
                        <input type="hidden" id="id" value="${student.id_alumno}">
                        <input type="hidden" id="codigo_alumno" value="${student.codigo_alumno}">
                    
                        <!-- Campo oculto para year -->
                        <input type="hidden" id="year" value="${student.cicloActual}">
                    
                        <div class="mb-4">
                            <label for="carnet" class="block text-sm font-medium">Carnet:</label>
                            <input type="text" id="carnet" value="${student.carnet}" class="w-full border rounded" >
                        </div>
                        <div class="mb-4">
                            <label for="nombres" class="block text-sm font-medium">Nombres:</label>
                            <input type="text" id="nombres" value="${student.nombres}" class="w-full border rounded">
                        </div>
                        <div class="mb-4">
                            <label for="apellidos" class="block text-sm font-medium">Apellidos:</label>
                            <input type="text" id="apellidos" value="${student.apellidos}" class="w-full border rounded">
                        </div>
                        <div class="mb-4">
                            <label for="correo" class="block text-sm font-medium">Correo:</label>
                            <input type="text" id="correo" value="${student.correo}" class="w-full border rounded">
                        </div>
                        <div class="mb-4">
                            <label for="grado" class="block text-sm font-medium">Grado:</label>
                            <input type="text" id="grado" value="${student.grado}" class="w-full border rounded">
                        </div>
                        <div class="mb-4">
                            <label for="nacimiento" class="block text-sm font-medium">Fecha de Nacimiento:</label>
                            <input type="date" id="nacimiento" value="${student.fecha_nacimiento}" class="w-full border rounded">
                        </div>
                        <div class="mb-4">
                            <label for="correo_encargado" class="block text-sm font-medium">Correo del Encargado:</label>
                            <input type="email" id="correo_encargado" value="${student.correo_encargado}" class="w-full border rounded">
                        </div>
                    </form>

                    `;

                    document.getElementById('modal-content').innerHTML = modalContent;
                    document.getElementById('shared-modal').classList.remove('hidden');
                } else {
                    alert(data.message || 'Error al cargar la información del estudiante.');
                }
            })
            .catch(error => {
                console.error('Error al abrir el modal:', error);
                alert('Ocurrió un error al intentar cargar los datos del estudiante.');
            });
    };
    
    window.saveChanges = function () {
    const id = document.getElementById("id").value;    
    const codigo_alumno = document.getElementById("codigo_alumno").value;    
    const carnet = document.getElementById("carnet").value;
    const nombres = document.getElementById("nombres").value;
    const apellidos = document.getElementById("apellidos").value;
    const correo = document.getElementById("correo").value;
    const grado = document.getElementById("grado").value;
    const nacimiento = document.getElementById("nacimiento").value;
    const correoEncargado = document.getElementById("correo_encargado").value;
    const year = document.getElementById("year").value;

    const payload = {
        action: "update_student",
        id,
        codigo_alumno,
        carnet,
        nombres,
        apellidos,
        correo,
        grado,
        nacimiento,
        correo_encargado: correoEncargado,
        year,
    };

    fetch("../../modules/api/put_student.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(payload),
    })
    .then((response) => response.json())
    .then((data) => {
        console.log("Respuesta del servidor:", data);

        if (data.message) {
            alert(data.message);
            closeModal();
            setTimeout(() => window.location.reload(), 500);
        } else {
            alert(data.error || "Error al actualizar el estudiante.");
        }
    })
    .catch((error) => {
        console.error("Error al guardar los cambios:", error);
        alert("Ocurrió un error al intentar guardar los cambios.");
    });
};

    window.closeModal = function () {
        document.getElementById('shared-modal').classList.add('hidden');
    };
</script>
