<?php
/**
 * Created by PhpStorm.
 * User: Mike Nykytenko
 */

namespace App\Entity;

class Phonebook extends Base
{
    /**
     * @param int $choose
     * @return string
     */
    public function getTableName($choose = 0) {
        $newsTables = [
            'contacts',
            'all_contacts',     // use 'published' field to filter only visible contacts
            'contact_phones',   // will use 'contact_id' to sync with cur contact
            'contact_emails',   // will use 'contact_id' to sync with cur contact
            'country'           // countries list
        ];
        return $newsTables[$choose];
    }

    /**
     * @param int $choose
     * @return mixed
     */
    public function getFields($choose = 0) {
        $phonebookFields = [
            'contacts' => [
                'id',           //phonebook contact id
                'user_id',      // id of the user used to have login to this App
                'first_name',
                'last_name',
                'address',
                'zip',
                'city',
                'country_id',
                'published',    // use 'published' field to filter only visible contacts
            ],
            'all_contacts' => [
                'name',         // username
                'id',           //phonebook contact id
                'user_id',      // id of the user used to have login to this App
                'first_name',
                'last_name',
                'address',
                'zip',
                'city',
                'country_id',
                'published',    // use 'published' field to filter only visible contacts
                'iso',
                'country',
                'phonecode',
                'phones',       // CSV-list of all published phones
                'emails',       // CSV-list of all published eamils
            ],
            'contact_phones' => [
                'id',           //phonebook contact id
                'contact_id',
                'phone',
                'published',
            ],
            'contact_emails' => [
                'id',           //phonebook contact id
                'contact_id',
                'email',
                'published',
            ],
            'country' => [
                'id',           //phonebook contact id
                'iso',
                'name',
                'nicename',
                'iso3',
                'numcode',
                'phonecode',
            ],
        ];
        return array_values($phonebookFields)[$choose];
    }

    /**
     * Проверяет поля таблицы, в которую будем
     * вносить изменения в методе parent::save()
     * @param $data
     * @throws \Exception
     */
    public function checkFields($data) {
        $msg = [];
        foreach ($data as $key => $val) {
            switch ($key) {
                case 'first_name':
                case 'last_name':
                    if (!is_string($val) || !strlen($val)) {
                        $msg[] = "phonebook $key can\'t be empty";
                    }
                    break;
                case 'country_id':
                case 'email':
                case 'phone':
                    if (!is_string($val) || !strlen($val)) {
                        $msg[] = "phonebook $key can\'t be empty";
                    }
                    break;
                default:
            }
        }
        if ($msg) {
            throw new \Exception(implode('; ', $msg));
        }
    }

    /**
     * get supplementary tables data pre-filtered by currently logged user id (users.id)
     * @param int $user_id
     * @param int $sub_table_id
     * @param string $sub_table_key_field
     * @param int $main_table_id
     * @param string $main_table_key_field
     * @return mixed|null
     */
    public function getByUserId($user_id,
                                $sub_table_id, $sub_table_key_field = 'contact_id',
                                $main_table_id = 0, $main_table_key_field = 'id') {
        $result = [];
        if (isset($user_id) && $user_id !== '' && isset($sub_table_id)) {
            $sql = "SELECT a.* FROM {$this->getTableName($sub_table_id)} a
                 INNER JOIN {$this->getTableName($main_table_id)} c
                 ON a.{$sub_table_key_field} = c.$main_table_key_field
                 WHERE c.user_id = {$this->conn->escape($user_id)};";
            $result = $this->conn->query($sql);
        }
        return $result;
    }
}