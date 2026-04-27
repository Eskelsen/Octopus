<?php

include 'registrar-process.php';

$email_to = ($email) ? '?email=' . $email : '';

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />

    <meta name="description" content="Registrar">
    
    <meta name="author" content="Daniel Eskelsen">
    <meta name="theme-color" content="#4482A1">
    <meta property="og:url" content="<?= url('registrar'); ?>">
    <link rel="icon" href="<?= img('ups/icon.png'); ?>">

    <title>Criar Conta » <?= $app; ?></title>

    <link rel="canonical" href="<?= url('registrar'); ?>">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #f9f9f9;
        }
        a {
            color: #6c757d;
            text-decoration: none;
        }

        a:hover {
            color: #54595eff;
            text-decoration: none;
        }
        .card {
            background: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            width: 100%;
            max-width: 380px;
            text-align: center;
        }
        .card h2 {
            margin-bottom: 1rem;
            text-align: center;
            font-weight: 500;
        }
        .form-group {
            margin-bottom: 0.6rem;
            position: relative;
        }
        .form-group input {
            width: 100%;
            padding: 0.75rem 1rem;
            padding-right: 2.8rem;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 1rem;
            box-sizing: border-box;
        }
        .form-group .toggle-pass {
            position: absolute;
            top: 50%;
            right: 0.8rem;
            transform: translateY(-50%);
            cursor: pointer;
            color: #666;
            font-size: 1.2rem;
        }
        .btn-submit {
            width: 100%;
            padding: 0.8rem;
            background: #007bff;
            border: none;
            color: white;
            border-radius: 6px;
            font-size: 1rem;
            cursor: pointer;
        }
        .btn-submit:hover {
            background: #0056b3;
        }

        .hr-muted {
            border: none;
            border-top: 1px solid #e0e0e0;
        }

        .alert {
            box-sizing: border-box;
            width: 100%;
            max-width: 100%;
            position: relative;
            padding: 0.75rem 1.25rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            border-radius: 0.375rem;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .alert strong {
            font-weight: 600;
        }

        /* Variantes */

        .alert-primary {
            color: #084298;
            background-color: #cfe2ff;
            border-color: #b6d4fe;
        }

        .alert-secondary {
            color: #41464b;
            background-color: #e2e3e5;
            border-color: #d3d6d8;
        }

        .alert-success {
            color: #0f5132;
            background-color: #d1e7dd;
            border-color: #badbcc;
        }

        .alert-danger {
            color: #842029;
            background-color: #f8d7da;
            border-color: #f5c2c7;
        }

        .alert-warning {
            color: #664d03;
            background-color: #fff3cd;
            border-color: #ffecb5;
        }

        .alert-info {
            color: #055160;
            background-color: #cff4fc;
            border-color: #b6effb;
        }

        .alert-light {
            color: #636464;
            background-color: #fefefe;
            border-color: #fdfdfe;
        }

        .alert-dark {
            color: #141619;
            background-color: #d3d3d4;
            border-color: #bcbebf;
        }
    </style>
</head>
<body>
<div class="card">
    <form method="post" action="registrar">
        <a href=""><img class="mb-4" src="<?= img('ups/icon.png'); ?>" alt="" width="172" style="opacity: 0.3;"></a>
        <!-- Chemistry icons created by Freepik - Flaticon in https://www.flaticon.com/free-icons/chemistry -->

        <h2>Crie sua conta</h2>

        <?= rc_form() ?>
        <?= csrf() ?>

        <div class="form-group">
            <input type="text" id="nome" name="name" placeholder="Nome completo" value="<?= $name; ?>" required>
        </div>

        <div class="form-group">
            <input type="email" id="email" name="email" placeholder="E-mail principal" value="<?= $email; ?>" required>
        </div>

        <div class="form-group">
            <input type="password" id="senha" name="psw" placeholder="Senha" value="<?= $psw; ?>" required>
            <i class="bi bi-eye-slash toggle-pass" data-target="senha"></i>
        </div>

        <div class="form-group">
            <input type="password" id="confirmaSenha" name="confpsw" placeholder="Confirme sua senha" value="<?= $confpsw; ?>" required>
            <i class="bi bi-eye-slash toggle-pass" data-target="confirmaSenha"></i>
        </div>
        <?= empty($message) ? '' : $message; ?>
        <button type="submit" class="btn-submit">Cadastrar</button>
        <p class="mt-3 mb-5 text-muted">Já possui conta? <a href="login<?= $email_to; ?>">Acessar</a></p>
    </form>
    <hr class="mt-5 hr-muted">
    <p class="mt-4 pt-2 text-muted small">
        <a href="<?= $site; ?>" target="_blank">
            &copy; <?= $mark . ' ' . date('Y'); ?>
        </a>
    </p>
</div>

<script>
document.querySelectorAll('.toggle-pass').forEach(icon => {
    icon.addEventListener('click', () => {
        const input = document.getElementById(icon.dataset.target);

        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        } else {
            input.type = "password";
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        }
    });
});
</script>

</body>
</html>