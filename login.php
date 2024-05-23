<?php

    require './includes/config/database.php';

    $db = conectarDB();

    $errores = [];

    if($_SERVER['REQUEST_METHOD'] === "POST"){

        $email = mysqli_real_escape_string($db, filter_var($_POST['email'], FILTER_VALIDATE_EMAIL));
        $password = mysqli_real_escape_string($db, $_POST['password']);

        if(!$email){
            $errores[] = 'El Email es Obligatorio';
        }

        if(!$password){
            $errores[] = 'La Contraseña es Obligatoria';
        }

        if(empty($errores)){
            $query = "SELECT * FROM usuarios WHERE email = '{$email}'";
            $resultado = mysqli_query($db, $query);

            if( $resultado->num_rows ){
                $usuario = mysqli_fetch_assoc($resultado);
                $auth = password_verify($password, $usuario['password']);

                if($auth){
                    session_start();

                    $_SESSION['usuario'] = $usuario['usuario'];
                    $_SESSION['login'] = true;

                    header('location: /admin');
                }else{
                    $errores[] = "La Contraseña no es Correcta";
                }
            }else{
                $errores[] = "El Usuario no Existe";
            }
        }
    }

    require './includes/funciones.php';

    incluirTemplates('header');
?>

    <main class="contenedor seccion contenido-centrado">
        <h1>Iniciar Sesión</h1>

        <?php foreach($errores as $error):?>
            <div class="alerta error">
                <?php echo $error?>
            </div>
        <?php endforeach?>

        <form method="post" class="formulario">
            <fieldset>
                <legend>Email y Contraseña</legend>

                <label for="email">E-mail</label>
                <input type="email" name="email" placeholder="Ej: correo@correo.com" id="email" required>

                <label for="password">Contraseña</label>
                <input type="password" name="password" placeholder="Contraseña.." id="password" required>
            </fieldset>

            <input type="submit" class="boton boton-verde" value="Inicia Sesión">
        </form>
    </main>
