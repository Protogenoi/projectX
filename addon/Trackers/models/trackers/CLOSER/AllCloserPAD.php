<?php

class AllCLOSERPadModal
{

    protected $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function AllgetCLOSERPad($datefrom, $dateTo)
    {
        if (isset($datefrom)) {
            $stmt = $this->pdo->prepare("SELECT date_updated AS updated_date, lead_up, mtg, closer, tracker_id, agent, client, phone, current_premium, our_premium, comments, sale, insurer, timeDeadline, dayDeadline
FROM closer_trackers WHERE DATE(date_added) BETWEEN :datefrom AND :dateTo ORDER BY date_added DESC");
            $stmt->bindParam(':datefrom', $datefrom, PDO::PARAM_STR);
            $stmt->bindParam(':dateTo', $dateTo, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

    }
}
