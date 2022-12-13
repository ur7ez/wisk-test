<?php
/**
 * Created by PhpStorm.
 * User: Mike
 */

/**
 * @var \App\Core\Router $router from \App\Core\App::getRouter()
 */

?>
<section class="col-lg-12">
    <h3 class="main-header">Login</h3>

    <form action="<?= $router->buildUri('users.login') ?>" method="post" class="login-form">
        <div class="form-group row">
            <label for="login" class="col-sm-3 col-form-label">Username: </label>
            <div class="col-sm">
                <input type="text" id="login" name="login" class="form-control" autofocus required>
            </div>
        </div>
        <div class="form-group row">
            <label for="password" class="col-sm-3 col-form-label">Password: </label>
            <div class="col-sm">
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
        </div>
        <div class="btn-toolbar justify-content-between">
            <button type="submit" class="btn btn-success col-7">Login</button>
            <span class="my-1">or</span>
            <a class="btn btn-outline-info"
               href="<?= $router->buildUri('users.register') ?>">Register</a>
        </div>
    </form>
</section>