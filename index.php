<?php
session_start();

$logueado = !empty($_SESSION["logueado"]);

/* ---------- LOGOUT (solo por POST) ---------- */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["logout"])) {
    if ($logueado && hash_equals($_SESSION["csrf"] ?? "", $_POST["csrf"] ?? "")) {
        require_once "controlador/logincontrolador.php";
        (new logincontrolador())->logout();
    }
    header("Location: vista/login.php");
    exit;
}

/* ---------- USUARIO NO LOGUEADO ---------- */
if (!$logueado) {

    $esLogin = $_SERVER["REQUEST_METHOD"] === "POST"
        && ($_POST["controlador"] ?? "") === "logincontrolador"
        && ($_POST["accion"] ?? "") === "login";

    if ($esLogin) {
        require_once "controlador/logincontrolador.php";
        $controlador = new logincontrolador();

        $identificador = trim($_POST["identificador"] ?? "");
        $password      = $_POST["password"] ?? "";

        $usuario = ($identificador !== "" && $password !== "")
            ? $controlador->login($identificador, $password)
            : false;

        if ($usuario) {
            session_regenerate_id(true);          
            $_SESSION["logueado"] = true;
            $_SESSION["usuario"]  = $usuario;     
            $_SESSION["csrf"]     = bin2hex(random_bytes(32));
            header("Location: index.php");
        } else {
            header("Location: vista/login.php?error=1");
        }
        exit;
    }

    header("Location: vista/login.php");
    exit;
}

/* ---------- USUARIO LOGUEADO ---------- */
include "vista/menu.php";

// Controladores permitidos (uno por módulo)
$permitidos = [
    "administradorcontrolador",   
    "salacontrolador",            
    "peliculacontrolador",       
    "funcioncontrolador",       
];

// Vistas que se abren por GET (index.php?a=codigo). Cada quien agrega las de su módulo.
$vistas = [
    "admcre" => "addAdministradorVista",  "admlis" => "listAdministradorVista",
    "salcre" => "addSalaVista",           "sallis" => "listSalaVista",
    "pelcre" => "addPeliculaVista",       "pellis" => "listPeliculaVista",
    "funcre" => "addFuncionVista",        "funlis" => "listFuncionVista",
];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $patron      = '/^[A-Za-z0-9_]+$/';
    $controlador = $_POST["controlador"] ?? "";
    $accion      = $_POST["accion"] ?? "";
    $vista1      = $_POST["vista1"] ?? "";

    if (preg_match($patron, $controlador) && preg_match($patron, $accion)
        && preg_match($patron, $vista1)
        && in_array($controlador, $permitidos)
        && hash_equals($_SESSION["csrf"] ?? "", $_POST["csrf"] ?? "")
        && file_exists("controlador/{$controlador}.php")) {

        require_once "controlador/{$controlador}.php";

        $con = class_exists($controlador) ? new $controlador() : null;

        if ($con !== null && is_callable([$con, $accion])) {
            $resp = $con->$accion($_POST);
            if (file_exists("vista/{$vista1}.php")) {
                require_once "vista/{$vista1}.php";
            }
        } else {
            echo "Acción no permitida";
        }
    } else {
        echo "no existe";
    }

} elseif (isset($_GET["a"])) {

    $a = $_GET["a"];

    if (isset($vistas[$a]) && file_exists("vista/{$vistas[$a]}.php")) {
        if ($a === "funcre" || $a === "funlis") {
            require_once "controlador/funcioncontrolador.php";
            $con = new funcioncontrolador();
            $resp = $con->{$vistas[$a]}($_GET);
        }
        include "vista/{$vistas[$a]}.php";
    } else {
        echo "Vista no disponible";
    }
}
