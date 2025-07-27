
<?php
public function transfer($fromAccount, $toAccount, $amount, $currency) {
    $db = Database::getInstance();
    $db->beginTransaction();
    try {
        // Debit
        $db->prepare("UPDATE accounts SET balance = balance - ? WHERE id = ?")
           ->execute([$amount, $fromAccount]);

        // Credit
        $db->prepare("UPDATE accounts SET balance = balance + ? WHERE id = ?")
           ->execute([$amount, $toAccount]);

        // Journal Entry
        $db->prepare("INSERT INTO journal_entries (debit_account_id, credit_account_id, amount, currency) VALUES (?,?,?,?)")
           ->execute([$fromAccount, $toAccount, $amount, $currency]);

        $db->commit();
    } catch (Exception $e) {
        $db->rollBack();
        throw $e;
    }
}

?>
