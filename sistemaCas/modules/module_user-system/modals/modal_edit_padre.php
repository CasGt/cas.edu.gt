<?php

validateAccess('estudiantes');

?>
<div id="modal-padre" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg">
        <div class="bg-blue-300 text-white px-4 py-2 flex justify-between items-center">
            <h2 class="text-xl font-bold">Información del Padre</h2>
            <button class="text-white hover:text-gray-300" onclick="closeModalPadre()">&times;</button>
        </div>
        <div class="p-4" id="modal-padre-content">
            <!-- Aquí se insertará el formulario dinámicamente -->
        </div>
        <div class="bg-gray-100 px-4 py-2 flex justify-end">
            <button class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600" onclick="closeModalPadre()">Cerrar</button>
        </div>
    </div>
</div>

<script>
    // Función para abrir el modal de padre
    window.openModalPadre = function(codigoAlumno, year) {
        fetch(`../../modules/api/get_padre.php?codigo_alumno=${codigoAlumno}&year=${year}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const padre = data.data;
                    const modalContent = `
                        <form>
                            <div class="mb-4">
                                <label for="nombres_padre" class="block text-sm font-medium">Nombres:</label>
                                <input type="text" id="nombres_padre" value="${padre.nombres_padre}" class="w-full border rounded" readonly>
                            </div>
                            <div class="mb-4">
                                <label for="apellidos_padre" class="block text-sm font-medium">Apellidos:</label>
                                <input type="text" id="apellidos_padre" value="${padre.apellidos_padre}" class="w-full border rounded" readonly>
                            </div>
                            <div class="mb-4">
                                <label for="telefono_casa_padre" class="block text-sm font-medium">Teléfono casa:</label>
                                <input type="text" id="telefono_casa_padre" value="${padre.telefonocasa_padre}" class="w-full border rounded" readonly>
                            </div>
                            <div class="mb-4">
                                <label for="celular_padre" class="block text-sm font-medium">Teléfono celular:</label>
                                <input type="text" id="celular_padre" value="${padre.celular_padre}" class="w-full border rounded" readonly>
                            </div>
                            <div class="mb-4">
                                <label for="correo_padre" class="block text-sm font-medium">Correo:</label>
                                <input type="email" id="correo_padre" value="${padre.correo_padre}" class="w-full border rounded" readonly>
                            </div>
                        </form>
                    `;
                    document.getElementById('modal-padre-content').innerHTML = modalContent;
                    document.getElementById('modal-padre').classList.remove('hidden');
                } else {
                    alert(data.message || 'Error al cargar la información del padre.');
                }
            })
            .catch(error => {
                console.error('Error al abrir el modal del padre:', error);
                alert('Ocurrió un error al intentar cargar los datos del padre.');
            });
    };

    // Función para cerrar el modal de padre
    window.closeModalPadre = function() {
        document.getElementById('modal-padre').classList.add('hidden');
    };
</script>
