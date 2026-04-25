<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?= img('ups/icon.png'); ?>">
    <title>Exemplos</title>
    <style>
        html, body {
            height: 100%;
            margin: 0;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #fff;
            font-family: -apple-system, BlinkMacSystemFont, 
                         "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            color: #2f2f2f;
        }

        .container {
            text-align: center;
            max-width: 600px;
            padding: 24px;
        }

        h1 {
            font-size: 2rem;
            font-weight: 500;
            margin-bottom: 12px;
        }

        p {
            line-height: 1;
            color: #3a3a3a;
        }

        a {
            color: #555;
            text-decoration: none;
        }
    </style>
</head>
<body>

    <div class="container">
        <a href=""><img class="mb-4" src="<?= img('ups/icon.png'); ?>" alt="" width="120"></a>
        <!-- Chemistry icons created by Freepik - Flaticon in https://www.flaticon.com/free-icons/chemistry -->
        <h1>Exemplos</h1>
        <p><a href="um/exemplo">Exemplo</a>
        | <a href="um/outro/exemplo">Outro exemplo</a>
        | <a href="registrar">Acesso</a>
        | <a href="redirect">Redirecionamento</a></p>
        <p>&nbsp;</p>
        <p><a href="<?= url() ?>" style="color: #0a0;font-weight: bold">Voltar</a></p>
    </div>

</body>
</html>