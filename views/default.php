<?php
/**
 * Created by PhpStorm.
 * User: Mike Nykytenko
 */

/**
 * @var array $data from \App\Views\Base::render()
 * @var \App\Core\Router $router from \App\Core\App::getRouter()
 */
$session = \App\Core\App::getSession();
$action = strtolower($router->getAction(true));
$ctrlr = strtolower($router->getController(true));
$xhr = isset($_GET['transport']) && $_GET['transport'] === 'xhr';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= \App\Core\Config::get('siteName') ?></title>
    <link rel="icon" href="/favicon.png" type="image/png">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet"
          href="<?= (App\Core\Config::get('debug')) ? '/css/bootstrap.min.css' : 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css' ?>">
    <link rel="stylesheet" href="/css/default.css">
</head>
<body class="bg-light">
<h1 class="main-header"><?= \App\Core\Config::get('siteName') ?></h1>
<nav class="nav justify-content-md-between">
    <div class="menu">
        <?php if ($_SERVER['REQUEST_URI'] !== '/'): ?>
            <a class="btn btn-primary"
               href="<?= $router->buildUri('.') ?>">Home</a>
        <?php endif; ?>
        <?php if ($session::get('login')): ?>
            <a class="btn btn-primary"
               id="user-logout" href="<?= $router->buildUri('users.logout') ?>">Logout</a>
        <?php else: ?>
            <a class="btn btn-primary<?= ($action === 'login') ? ' active' : '' ?>"
               id="user-login" href="<?= $router->buildUri('users.login') ?>">Login</a>
        <?php endif; ?>
        <a class="btn btn-primary<?= ($ctrlr === 'phonebook' && $action === 'index' && $xhr) ? ' active' : '' ?>"
           id="public-phonebook" href="<?= $router->buildUri('phonebook.index') ?>">Public Phonebook</a>
        <?php if ($session::get('login')): ?>
            <a class="btn btn-primary<?= ($ctrlr === 'phonebook' && $action === 'view' && $xhr) ? ' active' : '' ?>"
               id="my-contact" href="<?= $router->buildUri('phonebook.view') ?>">My Contact</a>
        <?php endif; ?>
    </div>
    <?php if ($session::get('login')): ?>
        <div class="welcome-msg mr-4 align-self-center">
            Wellcome, <span><i><?= $session::get('name') . ' (' . $session::get('login') . ')' ?></i></span>
        </div>
    <?php endif; ?>
</nav>

<main class="container-flex pt-3 px-3 pb-1" role="main">
    <?php if ($session::hasFlash()):
        foreach ($session::flash() as $msg): ?>
            <div class="alert alert-info py-1" role="alert">
                <?= $msg ?>
            </div>
        <?php endforeach;
    endif; ?>
    <div class="content">
        <?= $data['content'] ?>
    </div>
</main>

<footer class="footer">
    <div class="container">
        <span class="text-muted">(c) Mike Nykytenko. April, 2018</span>
    </div>
</footer>
<script src="/js/default.js"></script>
</body>
</html>