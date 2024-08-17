<?php

namespace Controllers;

use MVC\Router;
use Classes\Email;
use Model\Usuario;

class LoginController
{
    public static function login(Router $router)
    {
        $alertas = [];
        $numero = isset($_GET['resultado']) ? s($_GET['resultado']) : null;
        Usuario::successAlert($numero);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarLogin();

            if (empty($alertas)) {
                $usuario = Usuario::where('email', $auth->email);

                if ($usuario) {
                    if ($usuario->comprobarPasswordAndValidar($auth->password)) {
                        // Autenticar al usuario
                        session_start();

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        // Redireccionamiento
                        if ($usuario->admin) {
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header("Location: /admin");
                        } else {
                            header("Location: /cita");
                        }
                    }
                } else {
                    Usuario::setAlerta('error', 'El usuario no existe');
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/login', [
            'alertas' => $alertas
        ]);
    }

    public static function logout()
    {
        session_start();
        $_SESSION = [];
        header('Location: /');
    }

    public static function olvide(Router $router)
    {
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();

            if (empty($alertas)) {
                $usuario = Usuario::where('email', $auth->email);

                if ($usuario && $usuario->confirmado) {
                    // Crear token de recuperacion
                    $usuario->crearToken();

                    // Guardar token en la base de datos
                    $usuario->guardar();

                    // Enviar el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();

                    header("Location: /mensaje?tipo=recuperar");
                } else {
                    Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/olvide-password', [
            'alertas' => $alertas
        ]);
    }

    public static function recuperar(Router $router)
    {
        $alertas = [];
        $error = false;
        $token = isset($_GET['token']) ? s($_GET['token']) : 0;
        $usuario = Usuario::where('token', $token);

        if (empty($usuario)) {
            $error = true;
            Usuario::setAlerta('error', 'Token invalido');
        } else {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $auth = new Usuario($_POST);
                $alertas = $auth->validarPassword();

                if (empty($alertas)) {
                    $auth->hashPassword();
                    $usuario->password = $auth->password;
                    $usuario->token = null;
                    $resultado = $usuario->guardar();

                    if ($resultado) {
                        header("Location: /?resultado=2");
                    }
                }
            }
        }

        $alertas = Usuario::getAlertas();
        $router->render('/auth/recuperar-password', [
            'alertas' => $alertas,
            'error' => $error
        ]);
    }

    public static function crear(Router $router)
    {
        $usuario = new Usuario();

        // Alertas vacias
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            // Revisar que alerta este vacio
            if (empty($alertas)) {
                // Verificar que el usuario no este registrado
                $resultado = $usuario->existeUsuario();

                if ($resultado->num_rows) {
                    Usuario::setAlerta('error', 'El email ya esta registrado');
                } else {
                    // Hashear el password
                    $usuario->hashPassword();

                    // Generar un token unico
                    $usuario->crearToken();

                    // Enviar el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();

                    // Crear el usuario
                    $resultado = $usuario->guardar();
                    if ($resultado) {
                        header('Location: /mensaje?tipo=confirmar');
                    }
                }
            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router)
    {
        $mensaje['confirmar']['titulo'] = 'Confirma tu cuenta';
        $mensaje['confirmar']['descripcion'] = 'Hemos enviado las instrucciones para confirmar tu cuenta a tu email';

        $mensaje['recuperar']['titulo'] = 'Restablece tu contraseña';
        $mensaje['recuperar']['descripcion'] = 'Hemos enviado las instrucciones para restablecer tu contraseña a tu email';

        if (isset($_GET['tipo'])) {
            $tipo = s($_GET['tipo']);
        } else {
            header("Location: /");
        }
        $router->render('auth/mensaje', [
            'mensaje' => $mensaje[$tipo],
        ]);
    }

    public static function confirmar(Router $router)
    {
        $alertas = [];
        $token = isset($_GET['token']) ? s($_GET['token']) : 0;
        $usuario = Usuario::where('token', $token);

        if (empty($usuario)) {
            // Mostrar mensaje de error
            Usuario::setAlerta('error', 'Token no valido');
        } else {
            // Confirmar el usuario
            $usuario->confirmado = "1";
            $usuario->token = null;
            $usuario->guardar();

            header("Location: /?resultado=1");
        }

        // Obtener alertas
        $alertas = Usuario::getAlertas();

        //Renderizar la vista
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }
}
