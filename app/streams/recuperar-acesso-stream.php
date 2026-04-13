
<?php

use App\Core\Session;
use App\Core\Data;

if (Session::on()) {
    redirect('test');
}

$title   = 'Acesso a área administrativa';
$message = '';

$email = post('email');

if ($tc = tc_get()) {
    $message = '<div class="alert alert-warning" role="alert">Aguarde um momento para a próxima solicitação.</div>';
}

if ($rc = rc_get()) {
    exit('Solicitação repetida.').
}

$attempts = 1;

if ($email AND !$tc) {
	$data = Data::one('SELECT * FROM nano_users WHERE email=?',[$email]);
	if (!$data) {
		$remaining = 4 - $attempts;
		$message = '<div class="alert alert-danger" role="alert">E-mail inexistente na base de dados. ' . $remaining . ' tentativa(s) restante(s)</div>';
	} else {
		$hash  = sha1(uniqid());
		$link  = url("acessar/?hash=$hash");
		$sent  = true;
		if ($sent) {
            include_once APP . 'functions/mail.php';
            $title = 'Link de acesso :: ' . $app;
            $name = explode(" ", $data['name'])[0];
            $html  = 'Olá, ' . $name . '<br><br>Seu link de acesso é <a href="' . $link . '">' . $link . '</a>';
            $sent = sendMail($email,$name,$title,$html);
		}
		tc_set(15);
        if ($sent) {
            $title	='Solicitação recebida!';
            $message = 'Em breve você receberá um link de recuperação em seu e-mail.';
            $_SESSION['counter_hash'] = sha1($hash);
            $_SESSION['time_hash'] = time() + 600;
            $_SESSION['email'] = $email;
            rc_set();
            include APP . 'views/blank.php';
            exit;
        }
	}
}

if (isset($sent) && $sent===false) {
	$title	='Falha na recuperação!';
    $message = 'Falha ao enviar e-mail de recuperação.';
    $gray = '100%';
	include APP . 'views/blank.php';
	exit;
}

$email = $_GET['email'] ?? $email;

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="description" content="Recuperar acesso">
    
    <meta name="author" content="Daniel Eskelsen">
    <meta name="theme-color" content="#4482A1">
    <meta property="og:url" content="<?= url('recuperar-acesso'); ?>">
    <link rel="icon" href="<?= rel('ups/icon.png'); ?>">

    <title>Recuperar acesso » <?= $app; ?></title>

    <link rel="canonical" href="<?= url('recuperar-acesso'); ?>">

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
    <link href="https://getbootstrap.com/docs/4.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/4.0/examples/sign-in/signin.css" rel="stylesheet">
    
    <style>
	
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }
      
    textarea, table {
        width: 100%;
        height: 100%; 
        box-sizing: border-box;
    }
    
    a {
        color: #6c757d;
        text-decoration: none;
    }

    a:hover {
        color: #54595eff;
        text-decoration: none;
    }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
      
    .disappear {
        animation: hide 6s linear 2s 1 forwards;
    }

    @keyframes hide {
        to {
            opacity: 0;
        }
    }
	
	form i { z-index:200; margin-left: 120px; margin-top: 8px; margin-bottom: 2px; font-weight: 700; font-size: 18px; position: absolute; cursor: pointer; } 
    
	</style>
  </head>

  <body class="text-center">
  
      <form class="form-signin" method="post">
      <img class="mb-4" src="<?= rel('ups/icon.png'); ?>" alt="" width="72" height="72">
      <h1 class="h3 mb-3 font-weight-normal">Recuperar acesso</h1>
	  <p>Acesso a área administrativa</p>
	  
	  <?= rc_form(); ?>
	  <?= csrf(); ?>

      <label for="inputEmail" class="sr-only">Email</label>
      <input type="email" id="inputEmail" name="email" class="form-control mb-2" placeholder="E-mail" value="<?= $email; ?>" required>
	  
      <?= empty($message) ? '' : $message; ?>
	  	  
      <button class="btn btn-lg btn-primary btn-block" type="submit">Recuperar</button>
      <p class="mt-3 mb-2 text-muted"><a href="registrar">Criar conta</a> | <a href="login">Acessar conta</a></p>
      <p class="mt-5 mb-3 text-muted"><a href="<?= $site; ?>" target="_blank">&copy; <?= $mark . ' ' . date('Y'); ?></a></p>
    </form>	
  </body>
</html>