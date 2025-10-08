<?php

validateAccess('estudiantes');

?>
<div id="modal-madre" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg">
        <div class="bg-pink-300 text-white px-4 py-2 flex justify-between items-center">
            <h2 class="text-xl font-bold">Información del madre</h2>
            <button class="text-white hover:text-gray-300" onclick="closeModalmadre()">&times;</button>
        </div>
        <div class="p-4" id="modal-madre-content">
            <!-- Aquí se insertará el formulario dinámicamente -->
        </div>
        <div class="bg-gray-100 px-4 py-2 flex justify-end">
            <button class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600" onclick="closeModalmadre()">Cerrar</button>
        </div>
    </div>
</div>

<script>
    // Función para abrir el modal de madre
    window.openModalMadre = function(codigoAlumno, year) {
        fetch(`../../modules/api/get_madre.php?codigo_alumno=${codigoAlumno}&year=${year}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const madre = data.data;
                    const modalContent = `
                        <form>
                            <div class="mb-4">
                                <label for="nombres_madre" class="block text-sm font-medium">Nombres:</label>
                                <input type="text" id="nombres_madre" value="${madre.nombres_madre}" class="w-full border rounded" readonly>
                            </div>
                            <div class="mb-4">
                                <label for="apellidos_madre" class="block text-sm font-medium">Apellidos:</label>
                                <input type="text" id="apellidos_madre" value="${madre.apellidos_madre}" class="w-full border rounded" readonly>
                            </div>
                            <div class="mb-4">
                                <label for="telefono_casa_madre" class="block text-sm font-medium">Teléfono casa:</label>
                                <input type="text" id="telefono_casa_madre" value="${madre.telefonocasa_madre}" class="w-full border rounded" readonly>
                            </div>
                            <div class="mb-4">
                                <label for="celular_madre" class="block text-sm font-medium">Teléfono celular:</label>
                                <input type="text" id="celular_madre" value="${madre.celular_madre}" class="w-full border rounded" readonly>
                            </div>
                            <div class="mb-4">
                                <label for="correo_madre" class="block text-sm font-medium">Correo:</label>
                                <input type="email" id="correo_madre" value="${madre.correo_madre}" class="w-full border rounded" readonly>
                            </div>
                        </form>
                    `;
                    document.getElementById('modal-madre-content').innerHTML = modalContent;
                    document.getElementById('modal-madre').classList.remove('hidden');
                } else {
                    alert(data.message || 'Error al cargar la información del madre.');
                }
            })
            .catch(error => {
                console.error('Error al abrir el modal del madre:', error);
                alert('Ocurrió un error al intentar cargar los datos del madre.');
            });
    };

    // Función para cerrar el modal de madre
    window.closeModalmadre = function() {
        document.getElementById('modal-madre').classList.add('hidden');
    };
</script>
