<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.84.0">
    <title><?= CONF_SITE_TITLE ?></title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/sign-in/">

    <!-- Bootstrap core CSS -->
    <link href="<?= base_url() ?>app_minds/views/assets/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>


    <!-- Custom styles for this template -->
    <link href="<?= base_url() ?>app_minds/views/assets/css/signin.css" rel="stylesheet">
</head>
<body class="text-center">

<main class="form-signin">

    <form method="post" action="<?= base_url() ?>/signin/signin">
        <img class="mb-4" src="<?= base_url() ?>app_minds/views/assets/img/logo.png" alt="" width="72" height="57">
        <h1 class="h3 mb-3 fw-normal">Acesso ao Portal</h1>

        <div class="row">
<!--            --><?//= showFlash() ?>
            <?= $this->session->flashdata('flash_msg'); ?>
        </div>

        <div class="form-floating">
            <input type="email" name="mail" class="form-control" id="mail" placeholder="Seu email" required>
            <label for="mail">Email</label>
        </div>
        <div class="form-floating">
            <input type="password" class="form-control" id="pass" name="pass" placeholder="Senha" required>
            <label for="pass">Senha</label>
        </div>

        <div class="checkbox mb-3">
            <label>
                <a href="<?= base_url() ?>users/new" class="btn btn-sm btn-info">Cadastra usuário para testes</a>
            </label>
        </div>
        <button class="w-100 btn btn-lg btn-primary" type="submit">Acessar</button>
        <p class="mt-5 mb-3 text-muted">&copy; <?= date('Y') ?> SofMinds <?= CONF_SITE_VERSION ?></p>
    </form>
</main>

</body>
</html>
