<?php
class Report {

    /**
     * Get all transactions for the given month
     */
    public function monthlyStatement($accountId, $month) {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            SELECT * FROM transactions 
            WHERE account_id = ? AND MONTH(created_at) = ?
            ORDER BY created_at DESC
        ");
        $stmt->execute([$accountId, $month]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get transaction summary: total deposits, withdrawals, transfers
     */
    public function transactionSummary($accountId) {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            SELECT type, SUM(amount) as total
            FROM transactions
            WHERE account_id = ?
            GROUP BY type
        ");
        $stmt->execute([$accountId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get balance by month for last X months
     */
    public function balanceTrend($accountId, $months = 6) {
        $db = Database::getInstance();
        $stmt = $db->prepare("
            SELECT DATE_FORMAT(created_at, '%Y-%m') as month, SUM(amount) as total
            FROM transactions
            WHERE account_id = ?
            GROUP BY month
            ORDER BY month DESC
            LIMIT ?
        ");
        $stmt->execute([$accountId, $months]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>