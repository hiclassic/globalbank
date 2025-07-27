<?php

class Report {
    public function monthlyStatement($account_id, $month) {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            SELECT * FROM transactions 
            WHERE account_id = ? 
            AND MONTH(created_at) = ?
        ");
        $stmt->execute([$account_id, $month]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>
