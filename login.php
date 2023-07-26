<?php
require 'includes/config/database.php';
$db = conectarDB();

$errores = [];

//Autenticar el usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    $email = mysqli_real_escape_string($db, filter_var($_POST['email'], FILTER_VALIDATE_EMAIL));
    $password = mysqli_real_escape_string($db, $_POST['password']);

    if (!$email) {
        $errores[] = "El email es obligatorio o ingresaste un email no valido";
    }
    if (!$password) {
        $errores[] = "El password es obligatorio o ingresaste uno no valido";
    }

    if (empty($errores)) {
        //Revisar si el usuario existe
        $sql = "SELECT * FROM usuarios WHERE email = '{$email}'";
        $sqlResult = mysqli_query($db, $sql);

        if ($sqlResult->num_rows) {
            //Revisar si el password es correcto
            $usuario = mysqli_fetch_assoc($sqlResult);

            $auth = password_verify($password, $usuario['password']);

            if ($auth) {
                //El usuario esta autenficado
                session_start();

                //LLenar el arreglo de la sesion
                $_SESSION['usuario'] = $usuario['email'];
                $_SESSION['login'] = true;


                header('Location: www/bienesraices_inicio/admin');
            } else {
                $errores[] = "El password es incorrecto";
            }
        } else {
            $errores[] =  "El usuario no existe";
        }
    }
}
// echo "<pre>";
// var_dump($_SERVER);
// echo "</pre>";



require 'includes/funciones.php';
incluirTemplate('header');
?>
<main class="contenedor seccion contenido-centrado">
    <h1>Login Administrador</h1>
    <?php foreach ($errores as $error) : ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
    <?php endforeach; ?>
    <form method="POST" action="" class="formulario" novalidate>
        <fieldset>
            <legend>Email y Password</legend>

            <label for="email">E-mail</label>
            <input type="email" name="email" placeholder="Tu Email" id="email">

            <label for="password">Password</label>
            <input type="password" name="password" placeholder="Tu password" id="password">
        </fieldset>
        <input type="submit" value="Iniciar Sesion" class="boton boton-verde">
    </form>
</main>


<?php incluirTemplate('footer'); ?>