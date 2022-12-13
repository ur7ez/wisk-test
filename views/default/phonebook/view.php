<?php
/**
 * Created by PhpStorm.
 * User: Mike
 */

/**
 * @var array $data from \App\Views\Base::render()
 * @var \App\Core\Router $router from \App\Core\App::getRouter()
 */
?>
<section class="col-lg-12 my-contact-section">
    <h3 class="main-header">My Contact</h3>

    <!--  render the first available user contact so far  -->
    <? foreach ($data['user_contacts'] as $contact):
        $id = $contact['id']; ?>
        <form class="my-contact-form" action="<?= $router->buildUri('phonebook.edit') ?>" method="post"
              enctype="multipart/form-data" id="my-contact-form">
            <input type="hidden" name="id" value="<?= $id ?>">

            <div class="form-inline form-row publish-my-contact">
                <label for="published_<?= $id ?>" class="mr-2">Publish my contact: </label>
                <input type="checkbox" id="published_<?= $id ?>" name="published"
                       value="1"<?= $contact['published'] ? ' checked' : '' ?>>
            </div>

            <div class="form-row contact-dataset justify-content-center">

                <div class="form-group">
                    <fieldset class="contact-address">
                        <legend class="col-form-label text-center">Contact</legend>

                        <div class="form-group mb-0">
                            <label for="fname<?= $id ?>">Firstname:</label>
                            <input type="text" class="form-control-sm" id="fname<?= $id ?>"
                                   value="<?= $contact['first_name'] ?>"
                                   name="first_name" autofocus required>
                        </div>

                        <div class="form-group mb-0">
                            <label for="lname<?= $id ?>">Lastname:</label>
                            <input type="text" class="form-control-sm" id="lname<?= $id ?>"
                                   value="<?= $contact['last_name'] ?>"
                                   name="last_name" required>
                        </div>

                        <div class="form-group mb-0">
                            <label for="address<?= $id ?>">Address:</label>
                            <input type="text" class="form-control-sm" id="address<?= $id ?>"
                                   value="<?= $contact['address'] ?>"
                                   name="address">
                        </div>

                        <div class="form-group mb-0">
                            <label for="zip<?= $id ?>">ZIP/City:</label>
                            <input type="text" class="form-control-sm" id="zip<?= $id ?>" value="<?= $contact['zip'] ?>"
                                   name="zip" title="contact's ZIP code" placeholder="ZIP Code">
                            <input type="text" class="form-control-sm" id="city<?= $id ?>"
                                   value="<?= $contact['city'] ?>"
                                   name="city" title="contact's city" placeholder="Apartment, studio, or floor">
                        </div>

                        <div class="input-group ml-0">
                            <div class="input-group-prepend">
                                <label class="input-group-text" for="country<?= $id ?>">Country: </label>
                            </div>
                            <select class="custom-select" id="country<?= $id ?>" name="country_id" required>
                                <? foreach ($data['countries'] as $country):
                                    $own_country = ($contact['country_id'] === $country['id']) ? ' selected' : ''; ?>
                                    <option value="<?= $country['id'] ?>"<?= $own_country ?>><?= $country['nicename'] ?></option>
                                <? endforeach; ?>
                            </select>
                        </div>
                    </fieldset>
                </div>

                <div class="form-group">
                    <fieldset class="contact-phones col">
                        <legend class="col-form-label text-center">Phones</legend>
                        <? foreach ($data['contact_phones'] as $phone): ?>
                            <div class="form-text">
                                <input type="tel" value="<?= $phone['phone'] ?>" class="form-control-sm" required
                                       name="<?= $phone['id'] ?>_phones!phone" title="contact's phone number">
                                <input type="checkbox" value="1" class="form-control-sm"
                                       name="<?= $phone['id'] ?>_phones!published" title="check to publish this phone"
                                    <?= $phone['published'] ? ' checked' : '' ?>>
                            </div>
                        <? endforeach; ?>
                    </fieldset>
                    <div class="add-button" data-type="tel" data-name1="_phones!phone"
                         data-name2="_phones!published" data-class="contact-phones"
                         title="add new field">Add
                    </div>
                </div>

                <div class="form-group">
                    <fieldset class="contact-emails col">
                        <legend class="col-form-label text-center">Emails</legend>
                        <? foreach ($data['contact_emails'] as $email): ?>
                            <div class="form-text">
                                <input type="email" value="<?= $email['email'] ?>" class="form-control-sm" required
                                       name="<?= $email['id'] ?>_emails!email" title="contact's email">
                                <input type="checkbox" value="1" class="form-control-sm"
                                       name="<?= $email['id'] ?>_emails!published"
                                       title="check to publish this email"
                                    <?= $email['published'] ? ' checked' : '' ?>>
                            </div>
                        <? endforeach; ?>
                    </fieldset>
                    <div class="add-button" data-type="email" data-name1="_emails!email"
                         data-name2="_emails!published" data-class="contact-emails"
                         title="add new field">Add
                    </div>
                </div>

            </div>

            <div class="row justify-content-around">
                <button type="reset" class="btn btn-secondary mb-2">Reset</button>
                <button type="submit" class="btn btn-primary mb-2">Save</button>
            </div>
        </form>

    <? endforeach; ?>

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
                           href='<?= $router->buildUri('phonebook.view') . '?page=' . $button->page ?>'>
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

    <template data-class="form-text" class="add-phone-or-email">
        <input value="" class="form-control-sm" title="" required>
        <input type="checkbox" value="1" title="">
    </template>
</section>
<script src="/js/my_contacts.js"></script>