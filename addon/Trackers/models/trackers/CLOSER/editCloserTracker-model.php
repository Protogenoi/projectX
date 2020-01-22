<?php

class editTrackerModel
{

    protected $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getEditTrackerModel($TID)
    {

        $stmt = $this->pdo->prepare("SELECT date_updated AS updated_date, lead_up, mtg, closer, tracker_id, agent, client, phone, current_premium, our_premium, comments, sale, insurer, timeDeadline, dayDeadline
FROM closer_trackers WHERE tracker_id=:TID");
        $stmt->bindParam(':TID', $TID, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
