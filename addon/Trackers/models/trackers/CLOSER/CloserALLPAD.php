<?php

class CLOSERAllPadModal
{

    protected $pdo;

    private $hello;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getCLOSERALLPad()
    {

        if ($this->hello == 'Richard') {

            $stmt = $this->pdo->prepare("SELECT date_updated AS updated_date, lead_up, mtg, closer, tracker_id, agent, client, phone, current_premium, our_premium, comments, sale, insurer, timeDeadline, dayDeadline
FROM closer_trackers WHERE date_added >= CURDATE() AND agent IN ('Ricky Derrick', 'George Matthews', 'Rachel Jones', ' Tom Jones', 'Stephen Howard' , 'Lauren Ace') ORDER BY date_added DESC");

        } else {

            $stmt = $this->pdo->prepare("SELECT date_updated AS updated_date, lead_up, mtg, closer, tracker_id, agent, client, phone, current_premium, our_premium, comments, sale, insurer, timeDeadline, dayDeadline
FROM closer_trackers WHERE date_added >= CURDATE() ORDER BY date_added DESC");

        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param string $hello
     */
    public function setHello($hello)
    {
        $this->hello = $hello;
    }

}
