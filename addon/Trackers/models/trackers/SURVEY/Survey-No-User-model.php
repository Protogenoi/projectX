<?php

class SURVEY_NoUSER_MODAL
{

    protected $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getSURVEY_NoUSER_MODAL()
    {
        $stmt = $this->pdo->prepare("SELECT 
    survey_tracker_id,
    survey_tracker_agent,
    survey_tracker_number,
    survey_tracker_notes,
    survey_tracker_status,
    survey_tracker_call_count,
    DATE(survey_tracker_updated_date) AS DATE
FROM
    survey_tracker
WHERE
DATE(survey_tracker_updated_date) >= CURDATE()
        OR
        DATE(survey_tracker_added_date) >= CURDATE()");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}

?>
