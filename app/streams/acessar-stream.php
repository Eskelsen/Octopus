<?php

use App\Core\Session;
use App\Core\Data;

if (Session::on()) {
    redirect('test');
}

$hash = $_GET['hash'] ?? null;

if (!$hash) {
	$title 	 = 'Hash ausente';
	$message = 'Para o login é necessário um hash de acesso.';
    $gray 	= '100%';
    include APP . 'views/blank.php';
    exit;
}

if (empty($_SESSION['time_hash'])) {
	$title   = 'Dispositivo não identificado.';
	$message = 'Você deve entrar pelo mesmo dispositivo que fez a solicitação.';
    $gray 	 = '75%';
    include APP . 'views/blank.php';
    exit;
}

if (time()>=$_SESSION['time_hash']) {
	$title 	 = 'Hash expirado';
	$message = 'Obtenha um novo <a href="../recuperar-acesso" style="font-family: inherit;">hash de acesso</a>.';
    $gray 	 = '50%';
    include APP . 'views/blank.php';
    exit;
}

$confirm = empty($_SESSION['counter_hash']) ? false : $_SESSION['counter_hash']==sha1($hash);

if (!$confirm) {
	$title 	 = 'Hash não reconhecido';
	$message = 'Hash de acesso não encontrado no sistema.';
    $gray 	 = '25%';
	include APP . 'views/blank.php';
	exit;
}

$email = $_SESSION['email'] ?? null;

$data = Data::one('SELECT * FROM nano_users WHERE email=?',[$email]);

if (!$data) {
	$title 	 = 'Algo inesperado aconteceu';
	$message = 'Usuário não encontrado no sistema.';
    $gray 	 = '100%';
	include APP . 'views/blank.php';
	exit;
}

$data['acc'] = $data['id'];

Session::regenerate();
Session::load($data);

error_log("[access/access] #$data[id] $data[name]: login via hash");

$title   = 'Sinta-se em casa!';
$gray    = false;
$message = 'Redirecionando...';
$blink 	 = 'blink_me';

refresh('/', 3);

include APP . 'views/blank.php';
exit;
