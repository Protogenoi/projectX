<?php

class view_closer_tracker_model
{

    protected $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function get_view_closer_tracker_model()
    {

        $stmt = $this->pdo->prepare("SELECT 
    date_updated AS updated_date,
    closer,
    agent,
    client,
    phone,
    comments,
    sale
FROM
    closer_trackers
WHERE
    date_added >= CURDATE()
ORDER BY date_added DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}

?>
