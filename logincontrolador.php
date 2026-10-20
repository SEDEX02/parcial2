<?php
require_once __DIR__ . "/../modelo/administradorrepositorio.php";

class logincontrolador {


    public function login($identificador, $password) {

        $repositorio = new administradorrepositorio();
        $administrador = $repositorio->login($identificador, $password);

        if (!$administrador) {
            return false;
        }
        return ["id" => $administrador["id"], "nombre" => $administrador["nombre"]];
    }

    public function logout() {
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $p["path"], $p["domain"], $p["secure"], $p["httponly"]);
        }

        session_destroy();
    }
}
