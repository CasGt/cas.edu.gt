<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../index.php');
    exit();
}

// Cargar permisos
$permissions = [
    'dashboard' => ['admin', 'assistant'],
    'usuarios' => ['admin', 'assistant'],
    'estudiantes' => ['admin', 'assistant'],
    'enfermeria' => ['admin', 'assistant', 'medical'],
    'wellness' => ['admin', 'assistant'],
    'administrativos' => ['admin']
];

$user_role = $_SESSION['user_role'] ?? null;

if (!$user_role) {
    header('Location: ../../index.php');
    exit();
}

// Verificar si el usuario tiene acceso a un módulo
function hasAccess($module, $role, $permissions) {
    return isset($permissions[$module]) && in_array($role, $permissions[$module]);
}
?>

<nav class="bg-red-900 text-white">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <div>
                <a href="../module_user-system/dashboard.php">
                    <img src="../../src/images/logo_cas_blanco.png" alt="Logo CAS" class="h-8">
                </a>
            </div>

            <button id="menu-toggle" class="lg:hidden focus:outline-none">
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>

          
            
            <ul class="hidden lg:flex space-x-6 items-center">
                
                <li>
                <a href="../module_user-system/dashboard.php" class="hover:text-yellow-300 font-semibold">Dashboard</a>
                </li>
            
                <?php if (hasAccess('usuarios', $user_role, $permissions)): ?>
                <li class="relative group">
                    <button class="hover:text-yellow-300">Control Usuarios</button>
                    <ul class="absolute hidden group-hover:block bg-gray-700 py-1 mt-0 rounded shadow-lg z-10 w-48">
                        <li><a href="../module_user-system/edit_users.php" class="block px-4 py-2 hover:bg-gray-600">Ver Usuarios</a></li>
                        <li><a href="../module_user-system/create_users.php" class="block px-4 py-2 hover:bg-gray-600">Registrar Nuevo Usuario</a></li>
                    </ul>
                </li>
                <?php endif; ?>
                <?php if (hasAccess('estudiantes', $user_role, $permissions)): ?>
                <li class="relative group">
                    <button class="hover:text-yellow-300">Control Estudiantes</button>
                    <ul class="absolute hidden group-hover:block bg-gray-700 py-1 mt-0 rounded shadow-lg z-10 w-48">
                        <li><a href="../module_user-system/activate_students.php" class="block px-4 py-2 hover:bg-gray-600">Gestión de nuevos formularios</a></li>
                        <li><a href="../module_user-system/view_students.php" class="block px-4 py-2 hover:bg-gray-600">Estudiantes Activos</a></li>
                        <li><a href="../module_user-system/create_students.php" class="block px-4 py-2 hover:bg-gray-600">Crear usuario estudiante</a></li>
                        <li><a href="../module_user-system/export_information.php" class="block px-4 py-2 hover:bg-gray-600">Exportar Información</a></li>
                    </ul>
                </li>
                <?php endif; ?>
                <?php if (hasAccess('enfermeria', $user_role, $permissions)): ?>
                <li class="relative group">
                    <button class="hover:text-yellow-300">Enfermería</button>
                    <ul class="absolute hidden group-hover:block bg-gray-700 py-1 mt-0 rounded shadow-lg z-10 w-48">
                        <li><a href="../module_medical/nursing.php" class="block px-4 py-2 hover:bg-gray-600">Perfil Médico</a></li>
                        <li><a href="../module_medical/medical_history.php" class="block px-4 py-2 hover:bg-gray-600">Historial de Asistencias</a></li>
                    </ul>
                </li>
                <?php endif; ?>
                <?php if (hasAccess('wellness', $user_role, $permissions)): ?>
              <!--  <li class="relative group" type="hidden">
                    <button class="hover:text-yellow-300">Wellness</button>
                    <ul class="absolute hidden group-hover:block bg-gray-700 py-1 mt-0 rounded shadow-lg z-10 w-48">
                        <li><a href="../module_wellness/view_listado_general.php" class="block px-4 py-2 hover:bg-gray-600">Ver listado</a></li>
                        <li><a href="../module_wellness/view_cupos_wellness.php" class="block px-4 py-2 hover:bg-gray-600">Ver cupos</a></li>
                        <li><a href="../module_wellness/view_wellness.php" class="block px-4 py-2 hover:bg-gray-600">Ver wellness</a></li>
                    </ul> -->
                </li>
                <?php endif; ?>
                <?php if (hasAccess('administrativos', $user_role, $permissions)): ?>
                <li class="relative group">
                    <button class="hover:text-yellow-300">Administrativos</button>
                    <ul class="absolute hidden group-hover:block bg-gray-700 py-1 mt-0 rounded shadow-lg z-10 w-48">
                        <li><a href="../module_user-system/view_teachers.php" class="block px-4 py-2 hover:bg-gray-600">Administrar Docentes</a></li>
                        <li><a href="../module_user-system/view_work_places.php" class="block px-4 py-2 hover:bg-gray-600">Administrar Plazas</a></li>
                    </ul>
                </li>
                <?php endif; ?>
                <li>
                    <a href="../shared/logout.php" class="hover:text-yellow-300">Cerrar Sesión</a>
                </li>
            </ul>
        </div>
    </div>

    <div id="mobile-menu" class="fixed inset-0 bg-black bg-opacity-90 text-white transform -translate-x-full transition-transform duration-300 overflow-y-auto">
        <div class="flex justify-between items-center p-4">
            <div class="text-xl font-bold">Dashboard</div>
            <button id="menu-close" class="focus:outline-none">
                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <ul class="space-y-6 mt-12 px-4">
            <?php if (hasAccess('usuarios', $user_role, $permissions)): ?>
            <li>
                <button class="block text-xl w-full text-left hover:text-yellow-300">Control Usuarios</button>
                <ul class="mt-0 pl-4 space-y-2">
                    <li><a href="../module_user-system/edit_users.php" class="block text-lg hover:text-yellow-400">Ver Usuarios</a></li>
                    <li><a href="../module_user-system/create_users.php" class="block text-lg hover:text-yellow-400">Registrar Nuevo Usuario</a></li>
                </ul>
            </li>
            <?php endif; ?>
            <?php if (hasAccess('estudiantes', $user_role, $permissions)): ?>
            <li>
                <button class="block text-xl w-full text-left hover:text-yellow-300">Control Estudiantes</button>
                <ul class="mt-0 pl-4 space-y-2">
                    <li><a href="../module_user-system/activate_students.php" class="block text-lg hover:text-yellow-400">Gestión de nuevos formularios</a></li>
                    <li><a href="../module_user-system/view_students.php" class="block text-lg hover:text-yellow-400">Estudiantes Activos</a></li>
                     <li><a href="../module_user-system/export_information.php" class="block text-lg hover:text-yellow-400">Exportar estudiantes</a></li>
                </ul>
            </li>
            <?php endif; ?>
            <?php if (hasAccess('enfermeria', $user_role, $permissions)): ?>
            <li>
                <button class="block text-xl w-full text-left hover:text-yellow-300">Enfermería</button>
                <ul class="mt-0 pl-4 space-y-2">
                    <li><a href="../module_medical/nursing.php" class="block text-lg hover:text-yellow-400">Perfil Médico</a></li>
                    <li><a href="../module_medical/medical_history.php" class="block text-lg hover:text-yellow-400">Historial de Asistencias</a></li>
                </ul>
            </li>
            <?php endif; ?>
            
            <?php if (hasAccess('administrativos', $user_role, $permissions)): ?>
            <li>
                <button class="block text-xl w-full text-left hover:text-yellow-300">Administrativos</button>
                <ul class="mt-0 pl-4 space-y-2">
                    <li><a href="../module_user-system/view_teachers.php" class="block text-lg hover:text-yellow-400">Administrar Docentes</a></li>
                    <li><a href="../module_user-system/view_work_places.php" class="block text-lg hover:text-yellow-400">Administrar Plazas</a></li>
                </ul>
            </li>
            <?php endif; ?>
            <li>
                <a href="/modules/shared/logout.php" class="block text-xl hover:text-yellow-300">Cerrar Sesión</a>
            </li>
        </ul>
    </div>
</nav>

<script>
    const menuToggle = document.getElementById('menu-toggle');
    const menuClose = document.getElementById('menu-close');
    const mobileMenu = document.getElementById('mobile-menu');

    menuToggle.addEventListener('click', () => {
        mobileMenu.classList.remove('-translate-x-full');
    });

    menuClose.addEventListener('click', () => {
        mobileMenu.classList.add('-translate-x-full');
    });
</script>
