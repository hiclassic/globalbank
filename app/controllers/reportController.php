<?php
require_once __DIR__ . '/../Models/Report.php';

class ReportController {

    private $report;

    public function __construct() {
        $this->report = new Report();
    }

    /**
     * Get monthly statement for an account
     * @param int $accountId
     * @param int $month (1-12)
     * @return array
     */
    public function getMonthlyStatement($accountId, $month) {
        return $this->report->monthlyStatement($accountId, $month);
    }

    /**
     * Get transaction summary by type (deposit, withdraw, transfer)
     * @param int $accountId
     * @return array
     */
    public function getTransactionSummary($accountId) {
        return $this->report->transactionSummary($accountId);
    }

    /**
     * Get balance trend by last X months
     * @param int $accountId
     * @param int $months
     * @return array
     */
    public function getBalanceTrend($accountId, $months = 6) {
        return $this->report->balanceTrend($accountId, $months);
    }
}
