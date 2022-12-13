<?php
/**
 * Created by PhpStorm.
 * User: Mike Nykytenko
 */

namespace App\Controllers;

use App\Core\App;
use App\Core\Pagination;
use App\Entity\Phonebook;

class PhonebookController extends Base
{
    /** @var phonebook */
    private $phonebookModel;

    public function __construct($params = []) {
        parent::__construct($params);

        $this->phonebookModel = new Phonebook(App::getConnection());
    }

    public function indexAction() {
        $param = $this->params;
        $page = isset($param['query']['page']) ? (int)$param['query']['page'] : 1;
        $itemsCount = 0;

        if (empty($param[0])) {
            $this->data['all_contacts'] = $this->phonebookModel->list(
                ['published' => 1],
                1,
                $this->itemsPerPage,
                $this->itemsPerPage * ($page - 1),
                [],
                $itemsCount
            );
        }
        $this->data['count_all'] = $itemsCount;
        $this->data['cur_number'] = $this->itemsPerPage * ($page - 1);
        $this->data['pagination'] = new Pagination([
            'itemsCount' => $itemsCount,
            'itemsPerPage' => $this->itemsPerPage,
            'currentPage' => $page,
        ]);
    }

    public function viewAction() {
        $param = $this->params;
        $page = isset($param['query']['page']) ? (int)$param['query']['page'] : 1;
        $itemsCount = 0;

        $id = App::getSession()->get('id');  // users.id = contacts.user_id
        $contact = $this->phonebookModel->list(
            ['user_id' => $id],
            1,
            1,
            $page - 1,
            [],
            $itemsCount
        );
        if (!empty($contact)) {
            $contact_id = $contact[0]['id'];
            App::getSession()->set('contact_id', $contact_id);

            $this->data['user_contacts'] = $contact;
            $this->data['contact_phones'] = $this->phonebookModel->list(
                ['contact_id' => $contact_id], 2
            );
            $this->data['contact_emails'] = $this->phonebookModel->list(
                ['contact_id' => $contact_id], 3
            );
            $this->data['countries'] = $this->phonebookModel->list(
                [], 4);

            $this->data['count_all'] = $itemsCount;
            $this->data['pagination'] = new Pagination([
                'itemsCount' => $itemsCount,
                'itemsPerPage' => 1,
                'currentPage' => $page,
            ]);

        } else {
            $this->page404();
        }
    }

    public function editAction() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = App::getSession()->get('contact_id');     //isset($_POST['id']) ? $_POST['id'] : null;
            $user_id = App::getSession()->get('id');

            try {
                $this->data['contacts'] = [
                    'user_id' => $user_id,
                    'first_name' => $_POST['first_name'],
                    'last_name' => $_POST['last_name'],
                    'address' => $_POST['address'],
                    'zip' => $_POST['zip'],
                    'city' => $_POST['city'],
                    'country_id' => $_POST['country_id'],
                    'published' => $_POST['published'],
                ];

                // collecting data for emails and phones:
                $this->data = array_merge(
                    $this->data,
                    $this->collectMultiData($_POST, 'contact_id', $id, 'published')
                );
                // and updating data for emails and phones:
                foreach ($this->data['phones'] as $phones_id => $data) {
                    $this->phonebookModel->save($data, $phones_id, 2);
                }
                foreach ($this->data['emails'] as $emails_id => $data) {
                    $this->phonebookModel->save($data, $emails_id, 3);
                }

                $this->phonebookModel->save($this->data['contacts'], $id, 0);

                App::getSession()->setFlash('Data has been saved');
            } catch (\Exception $e) {
                App::getSession()->setFlash($e->getMessage());
            }
        }
    }

    /**
     * collecting data array for contact_emails and contact_phones tables
     * @param $data_arr
     * @param $outer_table_id_name
     * @param $outer_table_id
     * @param string $chkbox_field_name
     * @return array
     */
    private function collectMultiData($data_arr, $outer_table_id_name, $outer_table_id, $chkbox_field_name = 'published') {
        $data = [];

        foreach ($data_arr as $key => $val) {
            preg_match_all('/(^\d*)_(.*)!(\w+$)/i', $key, $matches);
            if (!($matches && $matches[2])) continue;
            $type = $matches[2][0];
            $id = $matches[1][0];
            $name = $matches[3][0];

            $data[$type][$id][$outer_table_id_name] = $outer_table_id;
            $data[$type][$id][$chkbox_field_name] = '0';
            $data[$type][$id][$name] = ($val === 'on' && $name === $chkbox_field_name) ? '1' : $val;
        }
        return $data;
    }
}