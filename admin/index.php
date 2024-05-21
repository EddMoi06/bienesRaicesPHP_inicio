<?php
    
    require '../includes/config/database.php';

    $db = conectarDB();

    $query = "SELECT * FROM propiedades";

    $resultadoConsulta = mysqli_query($db, $query);

    $resultado = $_GET['resultado'] ?? null;

    require '../includes/funciones.php';
    incluirTemplates('header');
?>

    <main class="contenedor seccion">
        <h1>Administrador de Bienes Raices</h1>
        <?php if(intval($resultado) === 1): ?>
            <p class="alerta exito">Anuncio Creado Correctamente</p>
        <?php elseif(intval($resultado) === 2): ?>
            <p class="alerta exito">Anuncio Actualizado Correctamente</p>
        <?php endif; ?>
        <a href="/admin/propiedades/crear.php" class="boton boton-verde">Nueva Propiedad</a>

        <table class="propiedades">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Titulo</th>
                    <th>Imagen</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while($propiedades = mysqli_fetch_assoc($resultadoConsulta)): ?>
                <tr>
                    <td><?php echo $propiedades['id'] ?></td>
                    <td><?php echo $propiedades['titulo'] ?></td>
                    <td><img src="../imagenes/<?php echo $propiedades['imagen'] ?>" alt="<?php echo $propiedades['titulo'] ?>" class="imagen-tabla"></td>
                    <td><?php echo $propiedades['precio'] ?></td>
                    <td>
                        <a href="#" class="boton-rojo-block">Eliminar</a>
                        <a href="admin/propiedades/actualizar.php?id=<?php echo $propiedades['id']; ?>" class="boton-amarillo-block">Actualizar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>

<?php
    mysqli_close($db);
    incluirTemplates('footer');
?>
