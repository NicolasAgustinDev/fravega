<?php
//Carga el archivo donde está la clase conexion, que contiene el método conectar().
//Es necesario para que puedas conectarte a la base de datos desde este archivo.
require_once 'modelo/conexion.php';
//Inicia una sesión PHP
//Esto permite guardar información (como el nombre del usuario)
session_start();

//Verifica si el formulario se envió mediante el método POST
//Si es asi continua con el proceso
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Obtiene los valores enviados desde el formulario (usuario y clave).
    //El operador ?? '' significa: “si no existe el valor, usar una cadena vacía como valor por defecto”
    $usuario = $_POST["usuario"] ?? '';
    $clave = $_POST["clave"] ?? '';

    //Llama al método estático conectar() de la clase conexion para obtener una conexión a la base de datos (usando PDO)
    $db = conexion::conectar();
    //Define la consulta SQL para buscar al usuario que coincida con el nombre ingresado
    $sql = "SELECT * FROM usuario WHERE usuario = :usuario" ;
    //Prepara la consulta para que PDO la ejecute después de vincularle los valores
    $stmt = $db->prepare($sql);
    //Asocia el valor de la variable $usuario al parámetro :usuario de la consulta
    $stmt -> bindParam(':usuario', $usuario);
    //Ejecuta la consulta con el valor vinculado
    //Ahora PDO buscar al usuario
    $stmt -> execute();

    //Verifica si la consulta devolvió exactamente un resultado, es decir, que el usuario existe
    if($stmt -> rowCount() == 1){
        //Extrae la fila del usuario como un array asociativo
        $usuarioData = $stmt->fetch(PDO::FETCH_ASSOC);
        //
        if($clave == $usuarioData['clave']){
            //Guarda el nombre del usuario en la sesión para que esté disponible en otras páginas
            $_SESSION['usuario'] = $usuarioData['usuario'];
            //Redirige al usuario a index.php si la contraseña fue correcta.
            header("Location: index.php");
            //exit() se usa para detener el script y evitar que se siga ejecutando código innecesario
            exit();
        //Si la verificación de la contraseña falla, se muestra un mensaje de error.
        }else{
            echo "Contraseña Incorrecta";
        }
    //Si la consulta no encontró ningún usuario, se muestra otro mensaje indicando que el usuario no existe.
    }else{
        echo "Usuario no encontrado";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Login - SB Admin</title>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="bg-primary">
        <div id="layoutAuthentication">
            <div id="layoutAuthentication_content">
                <main>
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-5">
                                <div class="card shadow-lg border-0 rounded-lg mt-5">
                                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Login</h3></div>
                                    <div class="card-body">
                                        <form method="POST" action="login.php">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" name="usuario" id="usuario" type="text" placeholder="usuario" required/>
                                                <label for="usuario">Usuario</label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="clave" name="clave" type="password" placeholder="Password" required />
                                                <label for="clave">Contraseña</label>
                                            </div>
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" id="inputRememberPassword" type="checkbox" value="" />
                                                <label class="form-check-label" for="inputRememberPassword">Remember Password</label>
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                                <button class="btn btn-primary" type="submit">Iniciar Sesion</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
            <div id="layoutAuthentication_footer">
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Your Website 2023</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
    </body>
</html>
