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

$name = $_SESSION['name'] ?? null;
$email = $_SESSION['email'] ?? null;
$password = $_SESSION['password'] ?? null;
$created_at = $_SESSION['created_at'] ?? null;

$ok = $name AND $email AND $password AND $created_at;

if (!$ok) {
	$title 	 = 'Não foi possível concluir o processo';
	$message = 'Os dados associados a conta foram perdidos.';
    $gray 	 = '100%';
	include APP . 'views/blank.php';
	exit;
}

if (Data::one('SELECT * FROM nano_users WHERE email=?',[$email])) {
    error_log("[onboarding] Falha na criação de conta via onboarding: $name <$email> [e-mail já cadastrado]");
    $message = '<div class="alert alert-warning" role="alert">E-mail já cadastrado. Faça <a href="login?email=' . $email . '">login</a>.</div>';
    return;
}

$values = [
    'name' 		 => $name,
    'email' 	 => $email,
    'password'   => sha1($password),
    'created_at' => $created_at
];

$id = Data::insert('nano_users',$values);

if (!$id) {
    error_log("[onboarding] Falha na criação de conta via onboarding: $name <$email> [erro desconhecido]");
    $message = '<div class="alert alert-warning" role="alert">Erro ao criar conta. Entre em contato conosco.</div>';
    return;
}

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

error_log("[onboarding] #$data[id] $data[name]: login via hash");

$title   = 'Sinta-se em casa!';
$gray    = false;
$message = 'Redirecionando...';
$blink 	 = 'blink_me';

refresh('/', 3);

include APP . 'views/blank.php';
exit;
