<?php
class Transaction {
    public function create($account_id, $type, $amount, $currency, $description) {
        $db = Database::getInstance();
        $db->beginTransaction();
        try {
            // Insert transaction
            $stmt = $db->prepare("INSERT INTO transactions (account_id, type, amount, currency, description) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$account_id, $type, $amount, $currency, $description]);

            // Update account balance
            if ($type === 'deposit') {
                $db->prepare("UPDATE accounts SET balance = balance + ? WHERE id = ?")->execute([$amount, $account_id]);
            } elseif ($type === 'withdraw') {
                $db->prepare("UPDATE accounts SET balance = balance - ? WHERE id = ?")->execute([$amount, $account_id]);
            }

            $db->commit();
            return true;
        } catch (Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    public function getByAccount($account_id) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM transactions WHERE account_id = ?");
        $stmt->execute([$account_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
