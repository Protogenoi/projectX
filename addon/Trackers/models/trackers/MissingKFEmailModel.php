<?php

class MissingKFEmailModal
{

    protected $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getMissingKFEmail($hello_name)
    {

        switch ($hello_name):
            case "James":
                $CLOSER_NAME = "James Adams";
                break;
            case "Mike":
                $CLOSER_NAME = "Michael Lloyd";
                break;
            case "Richard";
                $CLOSER_NAME = "Richard Michaels";
                break;
            case "Hayley":
                $CLOSER_NAME = "Hayley Hutchinson";
                break;
            case "Kyle";
                $CLOSER_NAME = "Kyle Barnett";
                break;
            case "carys";
                $CLOSER_NAME = "Carys Riley";
                break;
            default:
                $CLOSER_NAME = $hello_name;
        endswitch;

        $stmt = $this->pdo->prepare("SELECT 
    client_details.email,
    client_details.submitted_date,
    client_policy.closer,
    CONCAT(title, ' ', first_name, ' ', last_name) AS NAME
FROM
    client_details
    LEFT JOIN client_policy ON client_details.client_id=client_policy.client_id
WHERE
    client_details.email NOT IN (SELECT 
            keyfactsemail_email
        FROM
            keyfactsemail)
            AND client_policy.closer =:CLOSER
    GROUP BY client_details.email ORDER BY client_details.submitted_date DESC");
        $stmt->bindParam(':CLOSER', $CLOSER_NAME, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
