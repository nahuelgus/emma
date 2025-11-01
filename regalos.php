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
    ['id' => 'regalo10', 'titulo' => 'Bata de baño y toallon', 'descripcion' => 'Bata de baño y toallones con diseños infantiles.', 'imagen' => 'ropa-bano-toallon.jpg', 'is_marcable' => false],
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
            $texto_boton = $is_comprado ? '¡Ya Comprado! 🥳' : 'Ya lo compré / Lo voy a comprar ✅';
            $disabled = $is_comprado ? 'disabled' : '';
        ?>

        <div class="regalo-item <?php echo $clase_extra; ?>" data-id="<?php echo $regalo['id']; ?>">
            <img src="jpg/<?php echo $regalo['imagen']; ?>" alt="<?php echo $regalo['titulo']; ?>">
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

            // 1. Mostrar la alerta de confirmación (SweetAlert2)
            Swal.fire({
                title: '¿Estás seguro/a?',
                text: "Al confirmar, este regalo se marcará como comprado y se ocultará para el resto de los invitados.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#BAFFC9', // Verde Pastel
                cancelButtonColor: '#FFB3BA', // Rosa Pastel
                confirmButtonText: '¡Sí, lo compro!',
                cancelButtonText: 'Cancelar',
                customClass: {
                    confirmButton: 'swal2-confirm-button', // Clase para aplicar el color pastel con CSS si fuera necesario
                    cancelButton: 'swal2-cancel-button',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    
                    // Si el usuario confirma, procedemos con la acción
                    buttonElement.disabled = true;
                    buttonElement.textContent = 'Guardando...';

                    // 2. Usamos Fetch API para enviar la solicitud al servidor
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
                            // 3. Alerta de éxito (SweetAlert2)
                            Swal.fire({
                                title: '¡Gracias! 💖',
                                text: 'El regalo ha sido marcado y la lista se actualizó para todos.',
                                icon: 'success',
                                confirmButtonColor: '#BAE1FF' // Azul Pastel
                            });

                            // Ocultar el regalo en el cliente
                            itemElement.classList.add('comprado'); 
                            buttonElement.textContent = '¡Ya Comprado! 🥳';
                            
                        } else {
                            // 3. Alerta de error (SweetAlert2)
                            Swal.fire(
                                '¡Ups! 🐞',
                                data.message || 'Hubo un error al marcar el regalo. Intenta de nuevo.',
                                'error'
                            );
                            
                            // Revertir el estado del botón
                            buttonElement.disabled = false;
                            buttonElement.textContent = '¡Ya lo Compré! ✅';
                        }
                    })
                    .catch(error => {
                        // 3. Alerta de error de conexión (SweetAlert2)
                        console.error('Error de red:', error);
                        Swal.fire(
                            'Error de Conexión',
                            'No se pudo conectar con el servidor. Por favor, revisa tu conexión a internet.',
                            'error'
                        );
                        
                        // Revertir el estado del botón
                        buttonElement.disabled = false;
                        buttonElement.textContent = '¡Ya lo Compré! ✅';
                    });
                }
            });
        });
    });
</script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>