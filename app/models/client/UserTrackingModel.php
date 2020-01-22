<?php

class UserTrackingModal
{

    protected $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getUserTracking($search)
    {

        $SEARCH_URL = "%search=$search%";

        $stmt = $this->pdo->prepare("SELECT 
tracking_history_user
    tracking_history_user,
    tracking_history_url,
    INET6_NTOA(tracking_history_ip) AS tracking_history_ip,
    tracking_history_date
FROM
   tracking_history
WHERE
    tracking_history_url like :SEARCH
AND
    tracking_history_user !='Michael'
ORDER BY
    tracking_history_date DESC
    ");
        $stmt->bindParam(':SEARCH', $SEARCH_URL, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}

?>
