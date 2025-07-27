<?php
class Account {
    public function create($user_id, $currency) {
        $db = Database::getInstance();
        $account_number = 'AC' . time() . rand(100,999);
        $stmt = $db->prepare("INSERT INTO accounts (user_id, account_number, currency) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $account_number, $currency]);
        return $account_number;
    }

    public function getByUser($user_id) {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM accounts WHERE user_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>