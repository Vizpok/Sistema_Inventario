<?php
require_once __DIR__ . '/../../../bootstrap.php';
header('Content-Type: application/json');

$categorias = db()->select("SELECT ID_CATEGORIA, NOMBRE FROM categorias ORDER BY NOMBRE ASC");
echo json_encode($categorias);
