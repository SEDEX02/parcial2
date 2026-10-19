<?php
$c = $_GET['controller'] ?? 'administrador';
$a = $_GET['action'] ?? 'index';
$clase = ucfirst($c) . 'Controller';
require_once __DIR__ . "/controllers/$clase.php";
(new $clase())->$a();
