<?php
/**
 * Created by PhpStorm.
 * User: Mike
 */

/**
 * @var array $data from \App\Views\Base::render()
 * @var \App\Core\Router $router from \App\Core\App::getRouter()
 */

$detail_class = 'd-none';
$item_number = $data['cur_number'];
$space_mult = 4 + ($data['count_all'] / 100 > 1);
?>
<section class="col-lg-12">
    <h3 class="main-header">Public Phonebook</h3>
    <ul class="public-phonebook list-unstyled">
        <? foreach ($data['all_contacts'] as $contact):
            $item_number++;
            $str_num = str_pad($item_number . '.', 4, '*');
            $str_num = str_replace("*", "&nbsp;", $str_num);
            ?>
            <li class="contact-item<?= ($contact['last_name'] === 'Nykytenko') ? ' highlight-me' : '' ?>">
                <span class="item-number contact-name">
                    <?= $str_num . $contact['first_name'] . ' ' . $contact['last_name'] ?></span>
                <a href="" data-id="<?= $contact['id'] ?>" class="details-toggler"
                   title="click to view more details">view details</a>

                <div id="public-phonebook-<?= $contact['id'] ?>" class="card-deck detail-container d-none">
                    <div class="pb-address card mx-2">
                        <p class="card-header text-center">Address</p>
                        <div class="card-body">
                            <span><?= $contact['address'] ?></span><br>
                            <span><?= $contact['zip'] ?> <?= $contact['city'] ?></span><br>
                            <span><?= $contact['country'] ?></span>
                        </div>
                    </div>
                    <div class="pb-phones card mx-2">
                        <p class="card-header text-center">Phone numbers</p>
                        <ul class="card-body list-unstyled">
                            <? $phones = explode(',', $contact['phones']);
                            foreach ($phones as $phone):
                                $phone_f = $phone;
                                if (preg_match('/^(\d{3})(\d{3})(\d{4})$/', $phone, $matches)) {
                                    $phone_f = $matches[1] . '-' . $matches[2] . '-' . $matches[3];
                                } ?>
                                <li class="card-text">+<?= $contact['phonecode'] . '-' . $phone_f ?></li>
                            <? endforeach; ?>
                        </ul>
                    </div>
                    <div class="pb-emails card mx-2">
                        <p class="card-header text-center">Emails</p>
                        <ul class="card-body list-unstyled">
                            <? $emails = explode(',', $contact['emails']);
                            foreach ($emails as $email): ?>
                                <li class="card-text"><?= $email ?></li>
                            <? endforeach; ?>
                        </ul>
                    </div>
                </div>
            </li>
        <? endforeach; ?>
    </ul>

    <!--        Pagination       -->
    <small class="d-block text-right mt-3">
        <nav aria-label="Page navigation" class="navbar-text">
            <ul class="pagination px-5">
                <?php $cnt = 0;
                foreach ($data['pagination']->buttons as $button) :
                    if ($cnt == 1) : ?>
                        <li class="page-item show-other-pages order-2"><span class="page-link"> ... </span></li>
                        <div class="card-deck btn-group-vertical paging mx-auto order-3">
                    <? endif;
                    if ($cnt == count($data['pagination']->buttons) - 1) : ?>
                        </div>
                    <? endif; ?>
                    <? if ($button->isActive) : ?>
                    <li class="page-item">
                        <a class="page-link clickable-pagelink"
                           href='<?= $router->buildUri('phonebook.index') . '?page=' . $button->page ?>'>
                            <?= $button->text ?></a>
                    </li>
                <?php else : ?>
                    <li class="page-item<?= ($button->isCurrent) ? ' active' : ' disabled' ?>">
                        <span class="page-link"><?= $button->text ?></span>
                    </li>
                <?php endif;
                    $cnt++;
                endforeach; ?>
            </ul>
        </nav>
    </small>

</section>
<script src="/js/phonebook.js"></script>