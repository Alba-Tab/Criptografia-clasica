<?php
// index.php
// Punto de entrada
include __DIR__ . '/views/header.php';
include __DIR__ . '/views/nav.php';

// Incluyo dinámicamente el contenido de la pestaña activa 
// (por ejemplo, via ?tab=displacement)
$tab = $_GET['tab'] ?? 'displacement';
$allowed = ['displacement','transposition','substitution','advanced'];
//verificamos que la pestaña es válida
// Si no es válida, se asigna la pestaña por defecto 'displacement'
$tab = in_array($tab, $allowed) ? $tab : 'displacement';
include __DIR__ . "/views/tabs/{$tab}.php";

include __DIR__ . '/views/footer.php';