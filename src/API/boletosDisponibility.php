<?php

header('Content-Type: application/json');

use App\Controllers\BoletoController;

try {
	$controller = new BoletoController();
	$id_rifa = $_GET['id_rifa']  ?? null;
	$boleto = $_GET['numero_boleto']  ?? null;
	$idboleto = $_GET['id_boleto']  ?? null;
	$wn = $_GET['wn'] ?? null;

	$data = null;
	if ($idboleto !== null) {
		$data = [
			'id_rifa' => $id_rifa
		];
		$controller->obtenerBoletos($wn, $data, $idboleto);
		exit;
	}

	if ($boleto !== null && $id_rifa !== null) {
		$data = [
			'boleto' => $boleto,
			'id_rifa' => $id_rifa
		];
	}
	$controller->obtenerBoletos($wn, $data);
} catch (Exception $e) {
	http_response_code(500);
	echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
