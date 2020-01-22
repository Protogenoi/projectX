<?php
require_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_SPECIAL_CHARS) . '/app/core/doc_root.php';
require_once(BASE_URL . '/includes/ADL_PDO_CON.php');

class supportTickets
{


    private $assigned;

    public function __construct(\PDO $pdo)
    {

        $this->pdo = $pdo;

    }


    public function createTicket()
    {

        if (is_array($this->assigned)) {

            $userNameCheck = ['mike', 'bob', 'sally'];

            foreach ($this->assigned as $userNames):
                if (in_array($userNames, $userNameCheck, true)) {

                    $query = $this->pdo->prepare("INSERT INTO supportTeams SET username=:username, id_fk=20");
                    $query->bindParam(':username', $userNames, PDO::PARAM_STR);
                    $query->execute();

                }

            endforeach;

            return $this->assigned;

        }

        return "No match!";

    }


    /**
     * @param array $assigned
     */
    public function setAssigned($assigned)
    {
        $this->assigned = $assigned;
    }

}

if (isset($_POST['names'])) {

    $names = filter_input(INPUT_POST, 'names', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

    $new = new supportTickets($pdo);
    $new->setAssigned($names);
    $result = $new->createTicket();

    echo $result;
}

?>
<html>
<head>
<body>


<form method="post" action="/tests/1.php">

    <select multiple="multiple" name="names[]">
        <option value="mike">mike</option>
        <option value="bob">bob</option>
        <option value="sally">sally</option>
    </select>

    <button type="submit">Submit</button>

</form>


</body>
</head>
</html>
