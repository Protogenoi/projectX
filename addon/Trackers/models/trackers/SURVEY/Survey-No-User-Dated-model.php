<?php

class SURVEY_NOUSER_DATED_MODAL
{

    protected $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getSurveyNoUserDatedData($DATES)
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
    DATE(survey_tracker_updated_date) = :DATE
        OR
        DATE(survey_tracker_added_date) = :DATES");
        $stmt->bindParam(':DATE', $DATES, PDO::PARAM_STR);
        $stmt->bindParam(':DATES', $DATES, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}

?>
