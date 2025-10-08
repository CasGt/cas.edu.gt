<div id="shared-modal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-lg">
        <div class="bg-red-900 text-white px-4 py-2 flex justify-between items-center">
            <h2 class="text-xl font-bold" id="modal-title">Título del Modal</h2>
            <button class="text-white hover:text-gray-300" onclick="closeModal()">×</button>
        </div>

        <div class="p-4" id="modal-content">

        </div>

        <div class="bg-gray-100 px-4 py-2 flex justify-end">
            <button class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600" onclick="closeModal()">Cerrar</button>
        </div>
    </div>
</div>

<script>
    function openModal(title, content) {
        document.getElementById('modal-title').innerText = title;
        document.getElementById('modal-content').innerHTML = content;
        document.getElementById('shared-modal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('shared-modal').classList.add('hidden');
    }
</script>