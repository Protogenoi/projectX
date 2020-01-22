<?php

class STATSALLPadModal
{

    protected $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getSTATSALLPad()
    {

        $stmt = $this->pdo->prepare("SELECT 
    COUNT(mtg) AS mtg
FROM
    closer_trackers
WHERE
    mtg = 'Yes'
        AND DATE(date_added) = CURDATE()");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
