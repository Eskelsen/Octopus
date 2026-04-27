<?php

use App\Core\Session;
use App\Core\Data;

if (Session::on()) {
    redirect('test');
}

$name       = post('name');
$email      = post('email');
$psw        = post('psw');
$confpsw    = post('confpsw');

if ($rc = rc_get()) {
    exit('Solicitação repetida.');
}

if ($tc = tc_get()) {
    $message = '<div class="alert alert-warning" role="alert">Aguarde um momento para a próxima solicitação.</div>';
    return;
}

if (!($name && $email && $psw && $confpsw)) {
    $message = '<div class="alert alert-warning" role="alert">Será necessário confirmar seu e-mail.</div>';
    return;
}

if (!verify_size($psw,8) OR !verify_size($confpsw,8)) {
    $message = '<div class="alert alert-warning" role="alert">A senha precisa ter mais de 8 caracteres.</div>';
    return;
}

if (!($psw_ok = ($psw===$confpsw))) {
    $message = '<div class="alert alert-warning" role="alert">As senhas precisam ser iguais.</div>';
    return;
}

tc_set(30);
rc_set();

$name = format_name($name);

$email = filter_var($email, FILTER_SANITIZE_EMAIL);

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    error_log("[registrar-stream] Falha na criação de conta via onboarding: $name <$email> [e-mail inválido]");
    $message = '<div class="alert alert-warning" role="alert">Endereço de e-mail inválido.</div>';
    return;
}

if (Data::one('SELECT * FROM octopus_users WHERE email=?',[$email])) {
    error_log("[registrar-stream] Falha na criação de conta via onboarding: $name <$email> [e-mail já cadastrado]");
    $message = '<div class="alert alert-warning" role="alert">E-mail já cadastrado. Faça <a href="login?email=' . $email . '">login</a>.</div>';
    return;
}

$hash  = sha1(uniqid());
$link  = url("onboarding/?hash=$hash");

# Onboarding baseado em sessão
$_SESSION['name'] = $name;
$_SESSION['email'] = $email;
$_SESSION['password'] = $name;
$_SESSION['created_at'] = date('Y-m-d H:i:s');
$_SESSION['counter_hash'] = sha1($hash);
$_SESSION['time_hash'] = time() + 600;

include_once APP . 'functions/mail.php';

$id = rand(127000,256000);

$firstname = explode(' ', $name)[0];
$message   = 'Olá ' . $firstname . ', receba as nossas boas-vindas à plataforma ' . $app . '. ';
$message  .=  'Esperamos que tenha uma boa experiência.<br><br>Seu link de acesso é: <a href="' . $link . '">' . $link . '</a>';

// sendMail($email,$firstname,'Boas-vindas :: ' . $app,$message);
// sendMail('eskelsen@yahoo.com','Eskelsen', $app . ' :: Onboarding',"Conta criada: *#$id*, $name");
// error_log($message);
error_log($app . ' :: Onboarding' . ' ' . "Conta criada: *#$id*, $name");

$title	= 'Solicitação efetuada!';
$message = 'Confirme seu e-mail para continuar o processo.';
include APP . 'views/blank.php';
exit;
