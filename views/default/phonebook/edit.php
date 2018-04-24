<?php
/**
 * Created by PhpStorm.
 * User: Mike Nykytenko
 */

$session = \App\Core\App::getSession();
?>
<? if ($session->hasFlash()):
    foreach ($session->flash() as $msg): ?>
        <div class="alert alert-info py-1" role="alert">
            <?= $msg ?>
        </div>
    <? endforeach;
endif; ?>