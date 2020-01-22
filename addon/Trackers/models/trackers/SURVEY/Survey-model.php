<?php

class SURVEY_MODAL
{

    protected $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getSurveyData($hello_name)
    {
        $stmt = $this->pdo->prepare("SELECT 
    survey_tracker_id,
    survey_tracker_number,
    survey_tracker_notes,
    survey_tracker_status,
    survey_tracker_call_count,
    DATE(survey_tracker_updated_date) AS DATE
FROM
    survey_tracker
WHERE
    survey_tracker_agent = :HELLO
        AND DATE(survey_tracker_updated_date) >= CURDATE()");
        $stmt->bindParam(':HELLO', $hello_name, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}

?>
