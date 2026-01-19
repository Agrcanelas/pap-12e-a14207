<script>
document.getElementById('buscador').addEventListener('keyup', function(e) {
    let busqueda = e.target.value.toLowerCase();
    let productos = document.querySelectorAll('.producto-card'); // Asegúrate de que tus cards tengan esta clase

    productos.forEach(producto => {
        // Buscamos dentro del texto de la card (nombre, categoría, etc.)
        let texto = producto.innerText.toLowerCase();
        
        if (texto.includes(busqueda)) {
            producto.style.display = "block"; // Se muestra
            producto.style.opacity = "1";
        } else {
            producto.style.display = "none"; // Se oculta
        }
    });
});
</script>