<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// index.php
// Punto de entrada
include __DIR__ . '/views/header.php';

// Definir $tab antes de incluir nav.php
$tab = $_GET['tab'] ?? 'displacement';
$allowed = ['displacement','transposition','substitution','advanced'];
$tab = in_array($tab, $allowed) ? $tab : 'displacement';

include __DIR__ . '/views/nav.php';
include __DIR__ . "/views/tabs/{$tab}.php";
include __DIR__ . '/views/footer.php';