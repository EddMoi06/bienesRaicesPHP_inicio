<?php
    
    $id = $_GET['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);
    
    if(!$id){
        header('Location: /admin');
    }
    
    require '../../includes/config/database.php';

    $db = conectarDB();

    $query = "SELECT * FROM propiedades WHERE id={$id}";
    $resultado = mysqli_query($db, $query);
    $propiedad = mysqli_fetch_assoc($resultado);

    
    $consulta = "SELECT * FROM vendedores";
    $resultado = mysqli_query($db, $consulta);

    $errores = [];

    $titulo = $propiedad['titulo'];
    $precio = $propiedad['precio'];
    $descripcion = $propiedad['descripcion'];
    $habitaciones = $propiedad['habitaciones'];
    $wc = $propiedad['wc'];
    $estacionamientos = $propiedad['estacionamiento'];
    $vendedor = $propiedad['vendedores_id'];
    $date = date('Y/m/d');
    $imagenPropiedad = $propiedad['imagen'];

    if($_SERVER['REQUEST_METHOD'] === 'POST'){

        // echo '<pre>';
        // var_dump($_POST);
        // echo '</pre>';

        // echo '<pre>';
        // var_dump($_FILES);
        // echo '</pre>';

        $titulo = mysqli_real_escape_string( $db, $_POST['titulo']);
        $precio = mysqli_real_escape_string( $db, $_POST['precio']);
        $descripcion = mysqli_real_escape_string( $db, $_POST['descripcion']);
        $habitaciones = mysqli_real_escape_string( $db, $_POST['habitaciones']);
        $wc = mysqli_real_escape_string( $db, $_POST['wc']);
        $estacionamientos = mysqli_real_escape_string( $db, $_POST['estacionamientos']);
        $vendedorId = mysqli_real_escape_string( $db, $_POST['vendedor']);

        $imagen = $_FILES['imagen'];

        if(!$titulo){
            $errores[] = "Debes Insertar un Titulo";
        }

        if(!$precio){
            $errores[] = "El Precio es Obligatorio";
        }

        if( strlen( $descripcion) < 50){
            $errores[] = "La descripcion es Obligatoria y debe ser mayor a 50";
        }

        if(!$habitaciones){
            $errores[] = "La cantidad de habitaciones son Obligatorias";
        }

        if(!$wc){
            $errores[] = "La cantidad de baños son Obligatorias";
        }

        if(!$estacionamientos){
            $errores[] = "La cantidad de estacionamientos son Obligatorias";
        }

        if(!$vendedorId){
            $errores[] = "Seleccione un vendedor";
        }

        $medidas = 1000 * 1000;

        if(empty($errores)){


            $carpetaImagenes = '../../imagenes/';

            if(!is_dir($carpetaImagenes)){
                mkdir($carpetaImagenes);
            }

            $nombreImagen = md5( uniqid( rand(), true)) . '.jpg';

            move_uploaded_file($imagen['tmp_name'] , $carpetaImagenes . "$nombreImagen");


            $query = " UPDATE propiedades SET titulo = '{$titulo}', precio = '{$precio}', imagen = '{$nombreImagen}', descripcion = '{$descripcion}', habitaciones = {$habitaciones}, wc = {$wc}, estacionamiento = {$estacionamientos}, vendedores_id = {$vendedorId} WHERE id = {$id}; ";

            // echo $query;

            $resultado = mysqli_query($db, $query);

            if($resultado){
                header('Location: /admin?resultado=2');
            }
        }
    }

    require '../../includes/funciones.php';
    incluirTemplates('header');
?>

    <main class="contenedor seccion">
        <h1>Actualizar Propiedad</h1>

        <a href="/admin" class="boton boton-verde">Volver</a>

        <?php foreach($errores as $error):?>
            <div class="alerta error">
                <?php echo $error?>
            </div>
        <?php endforeach?>

        <form class="formulario" method="POST" enctype="multipart/form-data">
            <fieldset>
                <legend>Información General</legend>

                <label for="titulo">Titulo:</label>
                <input type="text" id="titulo" name="titulo" placeholder="Titulo de la Propiedad" value="<?php echo $titulo ?>">

                <label for="precio">Precio:</label>
                <input type="number" id="precio" name="precio" placeholder="Precio de la Propiedad" value="<?php echo $precio ?>">

                <label for="imagen">Imagen:</label>
                <input type="file" id="imagen" accept="image/jpeg, image/png" name="imagen">
                <img src="../../imagenes/<?php echo $imagenPropiedad ?>" alt="Imagen Propiedad" class="imagen-tabla">

                <label for="descripcion">Descripción</label>
                <textarea id="descripcion" name="descripcion"> <?php echo $descripcion ?> </textarea>
            </fieldset>

            <fieldset>
                <legend>Información de la Propiedad</legend>

                <label for="habitaciones">Habitaciones:</label>
                <input type="number" id="habitaciones" name="habitaciones" placeholder="ej: 3" min="1" max="9" value="<?php echo $habitaciones?>">

                <label for="wc">Baños:</label>
                <input type="number" id="wc" name="wc" placeholder="ej: 3" min="1" max="9" value="<?php echo $wc?>">

                <label for="estacionamientos">Estacionamientos:</label>
                <input type="number" id="estacionamientos" name="estacionamientos" placeholder="ej: 1" min="1" max="9" value="<?php echo $estacionamientos ?>">
            </fieldset>

            <fieldset>
                <legend>Vendedor</legend>

                <select name="vendedor">
                    <option value="">-- Seleccione --</option>
                    <?php while($vendedor = mysqli_fetch_assoc($resultado)) : ?>
                        <option <?php echo $vendedorId === $vendedor['id'] ? 'selected' : '';?> value="<?php echo $vendedor['id']?>"> <?php echo $vendedor['nombrel'] . " " . $vendedor['apellido'];?></option>
                    <?php endwhile; ?>
                </select>
            </fieldset>

            <input type="submit" value="Actualizar Propiedad" class="boton boton-verde">

        </form>
    </main>

<?php
    incluirTemplates('footer');
?>
