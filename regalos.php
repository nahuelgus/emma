<?php
// Incluir la configuración de la base de datos
require_once 'db_config.php';

// Definir los IDs de los regalos comprados
$comprados = [];

// Preparar la consulta para obtener los IDs de los regalos ya marcados
$sql = "SELECT regalo_id FROM regalos_comprados";

if ($result = mysqli_query($link, $sql)) {
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $comprados[] = $row['regalo_id'];
        }
    }
} else {
    // Manejo de error de consulta
    // Nota: Esto solo se mostrará si hay un problema con la DB.
    // echo "ERROR: No se pudo ejecutar $sql. " . mysqli_error($link);
}

// ==========================================================
// LISTA MAESTRA DE REGALOS
// is_marcable: true si tiene el botón "Ya lo Compré" (para no repetir)
// ==========================================================
$lista_regalos = [
    // REGALOS MARCADOS (CON BOTÓN)
    ['id' => 'regalo1', 'titulo' => 'Mesa y silla', 'descripcion' => 'Una mesita y silla de su tamaño para dibujar y jugar.', 'imagen' => 'mesa-silla-infantil.jpg', 'is_marcable' => true],
    ['id' => 'regalo2', 'titulo' => 'Caballo mecedor', 'descripcion' => 'Caballito, pony o mecedor de algún animal para el patio o interior.', 'imagen' => 'caballo-mecedor.jpg', 'is_marcable' => true],
    ['id' => 'regalo3', 'titulo' => 'Mochila para el jardín', 'descripcion' => 'Mochila cómoda y colorida para llevar al jardín (¡próximamente!).', 'imagen' => 'mochila-jardin.jpg', 'is_marcable' => true],
    ['id' => 'regalo4', 'titulo' => 'Pelela', 'descripcion' => 'Para empezar a practicar y dejar los pañales.', 'imagen' => 'pelela-entrenamiento.jpg', 'is_marcable' => true],
    ['id' => 'regalo5', 'titulo' => 'Gorra de pileta', 'descripcion' => 'Gorra de natación para proteger su cabello y orejas.', 'imagen' => 'gorra-pileta.jpg', 'is_marcable' => true],

    // REGALOS SIN MARCAR (SIN BOTÓN)
    ['id' => 'regalo6', 'titulo' => 'Malla de pileta', 'descripcion' => 'Malla de baño talle 4.', 'imagen' => 'malla-pileta.jpg', 'is_marcable' => false],
    ['id' => 'regalo7', 'titulo' => 'Bodies y remeras (Talle 4)', 'descripcion' => 'Bodies y remeras de algodón. ¡Talle 4!', 'imagen' => 'bodies-remeras.jpg', 'is_marcable' => false],
    ['id' => 'regalo8', 'titulo' => 'Juguete de Stitch', 'descripcion' => 'Cualquier muñeco o peluche del personaje Stitch.', 'imagen' => 'juguete-stitch.jpg', 'is_marcable' => false],
    ['id' => 'regalo9', 'titulo' => 'Pantalones, buzos y shorts (Talle 4)', 'descripcion' => 'Ropa cómoda de abrigo o verano. ¡Talle 4!', 'imagen' => 'pantalones-buzos-shorts.jpg', 'is_marcable' => false],
    ['id' => 'regalo10', 'titulo' => 'Ropa de baño para pileta y toallón', 'descripcion' => 'Trajes de baño y toallones con diseños infantiles.', 'imagen' => 'ropa-bano-toallon.jpg', 'is_marcable' => false],
    ['id' => 'regalo11', 'titulo' => 'Libros interactivos', 'descripcion' => 'Libros con texturas, sonidos o solapas. ¡Le encantan!', 'imagen' => 'libros-interactivos.jpg', 'is_marcable' => false],
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Regalos de Emma</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="regalos-header">
        <h1 class="animate__animated animate__heartBeat animate__infinite">🎁 Lista de Regalos</h1>
        <p>Tu presencia es nuestro mejor regalo, ¡pero si deseas hacer uno, aquí tienes algunas ideas para Emma!</p>
        
        <div class="nota-importante">
            <p>👚 **Talle de Ropa: Emma usa Talle 4**</p>
            <p>⚠️ El botón **"¡Ya lo Compré! ✅"** es para regalos grandes/específicos (como la mesa o el mecedor) y sirve para que **otros invitados no repitan ese mismo regalo**.</p>
        </div>
    </header>

    <div class="regalos-grid" id="regalos-list">
        
        <?php foreach ($lista_regalos as $regalo): 
            // Lógica para determinar si el regalo está comprado y sus atributos
            $is_comprado = in_array($regalo['id'], $comprados);
            $clase_extra = $is_comprado ? 'comprado' : '';
            $texto_boton = $is_comprado ? '¡Ya Comprado! 🥳' : '¡Ya lo Compré! ✅';
            $disabled = $is_comprado ? 'disabled' : '';
        ?>

        <div class="regalo-item <?php echo $clase_extra; ?>" data-id="<?php echo $regalo['id']; ?>">
            <img src="/jpg/<?php echo $regalo['imagen']; ?>" alt="<?php echo $regalo['titulo']; ?>">
            <h3><?php echo $regalo['titulo']; ?></h3>
            <p><?php echo $regalo['descripcion']; ?></p>
            
            <?php if ($regalo['is_marcable']): ?>
                <button class="comprado-button" data-id="<?php echo $regalo['id']; ?>" <?php echo $disabled; ?>>
                    <?php echo $texto_boton; ?>
                </button>
            <?php else: ?>
                <p class="nota-regalo-abierto">¡Regalo libre!</p>
            <?php endif; ?>
        </div>

        <?php endforeach; ?>
        
    </div>

    <footer>
        <p><a href="index.html">Volver a la Invitación</a></p>
    </footer>

    <script>
        document.querySelectorAll('.comprado-button').forEach(button => {
            button.addEventListener('click', function() {
                const regaloId = this.getAttribute('data-id');
                const buttonElement = this;
                const itemElement = this.closest('.regalo-item');

                // Deshabilitar el botón inmediatamente para evitar clics dobles
                buttonElement.disabled = true;
                buttonElement.textContent = 'Guardando...';

                // Usamos Fetch API para enviar la solicitud al servidor
                fetch('marcar_comprado.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'regalo_id=' + encodeURIComponent(regaloId)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Si el servidor confirma (SUCCESS), actualizamos el cliente
                        itemElement.classList.add('comprado'); // Oculta el item (ver style.css)
                        buttonElement.textContent = '¡Ya Comprado! 🥳';
                        alert('¡Muchas gracias por tu regalo! 🎉 Este ítem ha sido marcado y actualizado para todos.');
                    } else {
                        // Si hay un error de la DB
                        alert('Hubo un error al marcar el regalo: ' + data.message);
                        buttonElement.disabled = false;
                        buttonElement.textContent = '¡Ya lo Compré! ✅';
                    }
                })
                .catch(error => {
                    // Si hay un error de conexión
                    console.error('Error de red:', error);
                    alert('Error de conexión con el servidor. Intenta de nuevo.');
                    buttonElement.disabled = false;
                    buttonElement.textContent = '¡Ya lo Compré! ✅';
                });
            });
        });
    </script>
</body>
</html>