<?php

require_once __DIR__ . '/../models/transaction.php';

class TransactionController
{
    private $transaction;

    public function __construct()
    {
        $this->transaction = new Transaction();
    }

    public function deposit($account_id, $amount, $currency)
    {
        return $this->transaction->create($account_id, 'deposit', $amount, $currency, 'Deposit');
    }

    public function withdraw($account_id, $amount, $currency)
    {
        return $this->transaction->create($account_id, 'withdraw', $amount, $currency, 'Withdraw');
    }

    public function transfer($from, $to, $amount, $currency)
    {
        return $this->transaction->transfer($from, $to, $amount, $currency);
    }

    public function getTransactions($account_id)
    {
        return $this->transaction->getByAccount($account_id);
    }
}
