<?php

class Transaction
{
    /**
     * Create new transaction (deposit/withdraw)
     * @param int $account_id
     * @param string $type ('deposit' | 'withdraw')
     * @param float $amount
     * @param string $currency
     * @param string $description
     * @return bool
     */
    public function create($account_id, $type, $amount, $currency, $description = '')
    {
        $db = Database::getInstance();
        $db->beginTransaction();

        try {
            // Insert transaction record
            $stmt = $db->prepare("
                INSERT INTO transactions (account_id, type, amount, currency, description)
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([$account_id, $type, $amount, $currency, $description]);

            // Update balance
            if ($type === 'deposit') {
                $update = $db->prepare("UPDATE accounts SET balance = balance + ? WHERE id = ?");
                $update->execute([$amount, $account_id]);
            } elseif ($type === 'withdraw') {
                // Optionally: check for negative balance
                $check = $db->prepare("SELECT balance FROM accounts WHERE id = ?");
                $check->execute([$account_id]);
                $currentBalance = $check->fetchColumn();

                if ($currentBalance < $amount) {
                    throw new Exception("Insufficient Balance");
                }

                $update = $db->prepare("UPDATE accounts SET balance = balance - ? WHERE id = ?");
                $update->execute([$amount, $account_id]);
            }

            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    /**
     * Transfer money between two accounts
     * @param int $from_account
     * @param int $to_account
     * @param float $amount
     * @param string $currency
     * @return bool
     */
    public function transfer($from_account, $to_account, $amount, $currency)
    {
        $db = Database::getInstance();
        $db->beginTransaction();

        try {
            // Withdraw from sender
            $withdraw = $db->prepare("SELECT balance FROM accounts WHERE id = ?");
            $withdraw->execute([$from_account]);
            $fromBalance = $withdraw->fetchColumn();

            if ($fromBalance < $amount) {
                throw new Exception("Insufficient funds for transfer");
            }

            $db->prepare("UPDATE accounts SET balance = balance - ? WHERE id = ?")
               ->execute([$amount, $from_account]);

            $db->prepare("UPDATE accounts SET balance = balance + ? WHERE id = ?")
               ->execute([$amount, $to_account]);

            // Insert transactions for both accounts
            $db->prepare("INSERT INTO transactions (account_id, type, amount, currency, description) VALUES (?, 'transfer', ?, ?, ?)")
               ->execute([$from_account, -$amount, $currency, "Transfer to account #$to_account"]);

            $db->prepare("INSERT INTO transactions (account_id, type, amount, currency, description) VALUES (?, 'transfer', ?, ?, ?)")
               ->execute([$to_account, $amount, $currency, "Transfer from account #$from_account"]);

            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    /**
     * Get all transactions for an account
     * @param int $account_id
     * @return array
     */
    public function getByAccount($account_id)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM transactions WHERE account_id = ? ORDER BY created_at DESC");
        $stmt->execute([$account_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>