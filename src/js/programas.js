
document.querySelectorAll('.zoom-container').forEach(item => {
    const title = item.querySelector('h1');

    item.addEventListener('mouseover', () => {
        // Ajustar el ancho de la imagen seleccionada
        item.style.width = '50%';

        // Subir el título
        title.style.top = '25%';
    });

    item.addEventListener('mouseleave', () => {
        // Restaurar el ancho original de todas las imágenes al salir del área
        item.style.width = 'calc(100% / 4)';

        // Restaurar la posición del título
        title.style.top = '50%';
    });
});
