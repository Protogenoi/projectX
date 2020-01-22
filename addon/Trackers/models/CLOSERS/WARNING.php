<?php

class TRACKER_WARNINGModal
{

    protected $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getTRACKER_WARNING()
    {

        $stmt = $this->pdo->prepare("SELECT 
    COUNT(sale) AS Total,
    COUNT(IF(sale = 'SALE', 1, NULL)) AS Sales,
    COUNT(IF(sale = 'NoCard', 1, NULL)) AS NoCard,
    COUNT(IF(sale = 'QDE', 1, NULL)) AS QDE,
    COUNT(IF(sale = 'DEC', 1, NULL)) AS DECLINE,
    COUNT(IF(sale = 'QUN', 1, NULL)) AS QUN,
    COUNT(IF(sale = 'QNQ', 1, NULL)) AS QNQ,
    COUNT(IF(sale = 'DIDNO', 1, NULL)) AS DIDNO,
    COUNT(IF(sale = 'QCBK', 1, NULL)) AS QCBK,
    COUNT(IF(sale = 'QQQ', 1, NULL)) AS QQQ,
    COUNT(IF(sale = 'Other', 1, NULL)) AS Other,
    COUNT(IF(sale = 'Hangup on XFER', 1, NULL)) AS Hangup,
    COUNT(IF(sale = 'Thought we were an insurer', 1, NULL)) AS insurer,
    COUNT(IF(sale = 'QML', 1, NULL)) AS QML
FROM
    closer_trackers
WHERE
    date_added > DATE(NOW())
");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}

?>
