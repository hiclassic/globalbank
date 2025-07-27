<?php
require_once __DIR__ . '/../models/account.php';

class AccountController {
    public function createAccount($user_id, $currency) {
        $account = new Account();
        return $account->create($user_id, $currency);
    }

    public function getAccounts($user_id) {
        $account = new Account();
        return $account->getByUser($user_id);
    }
}
?>