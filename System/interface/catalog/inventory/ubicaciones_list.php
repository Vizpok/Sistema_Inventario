<?php
require_once __DIR__ . '/../../../bootstrap.php';
header('Content-Type: application/json');

$ubicaciones = db()->select("SELECT ID_UBICACION, CODIGO_UBICACION FROM ubicaciones ORDER BY CODIGO_UBICACION ASC");
echo json_encode($ubicaciones);