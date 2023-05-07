<?php
include '../includes/funciones.php';
// Proteger esta ruta.

$auth = estaAutenticado();
if(!$auth) {
    header('Location: /');
}

require '../includes/config/database.php';

$db = conectarDb();

$query = "SELECT * FROM propiedades";
$resultado = mysqli_query($db, $query);


// Validar la URL 
$mensaje = $_GET['mensaje'] ?? null;


// Importar el Template

incluirTemplate('header');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    echo "<pre>";
    var_dump($_POST);
    echo "</pre>";

    // Sanitizar número entero
    $id = $_POST['id_eliminar'];
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);

    // Eliminar... 

    $query = "DELETE FROM propiedades WHERE id = '${id}'";

    // echo $query;

    $resultado = mysqli_query($db, $query) or die(mysqli_error($db));
    // var_dump($resultado);
    // printf("Nuevo registro con el id %d.\n", mysqli_insert_id($db));

    if ($resultado) {
        header('location: /admin');
    }

}
?>

<h1 class="fw-300 centrar-texto">Administración</h1>

<main class="contenedor seccion contenido-centrado">


    <?php
        if ($mensaje == 1) {
            echo '<p class="alerta exito">Anuncio Creado Correctamente</p>';
        } else if ($mensaje == 2) {
        echo '<p class="alerta exito">Anuncio Actualizado Correctamente</p>';
        }
    ?>

    <a href="/admin/propiedades/crear.php" class="boton boton-verde">Nueva Propiedad</a>


    <table class="propiedades">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titulo</th>
                <th>Imagen</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            <?php while( $propiedad = mysqli_fetch_assoc($resultado) ): ?>
            <tr>
                <td><?php echo $propiedad['id']; ?></td>
                <td><?php echo $propiedad['titulo']; ?></td>
                <td>
                    <img src="/imagenes/<?php echo $propiedad['imagen']; ?>"" width="100" class="imagen-tabla">
                </td>
                <td>$ <?php echo $propiedad['precio']; ?></td>
                <td>
                <form method="POST">
                    <input type="hidden" name="id_eliminar" value="<?php echo $propiedad['id']; ?>">
                    <input type="submit" href="/admin/propiedades/borrar.php" class="boton boton-rojo" value="Borrar">
                </form>
                    
                    <a href="/admin/propiedades/actualizar.php?id=<?php echo $propiedad['id']; ?>" class="boton boton-verde">Actualizar</a>
                </td>
            </tr>

            <?php endwhile; ?>
        </tbody>
    </table>
</main>

<?php 
    incluirTemplate('footer');
?>