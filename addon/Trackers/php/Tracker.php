<?php

use ADL\clientNote;

require_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_SPECIAL_CHARS) . '/app/core/doc_root.php';

require_once(BASE_URL . '/classes/access_user/access_user_class.php');
$page_protect = new Access_user;
$page_protect->access_page(filter_input(INPUT_SERVER, 'PHP_SELF', FILTER_SANITIZE_SPECIAL_CHARS), "", 1);
$hello_name = ($page_protect->user_full_name != "") ? $page_protect->user_full_name : $page_protect->user;

$USER_TRACKING = 0;

require_once(BASE_URL . '/includes/user_tracking.php');
require_once(BASE_URL . '/includes/time.php');

if (isset($FORCE_LOGOUT) && $FORCE_LOGOUT == 1) {
    $page_protect->log_out();
}

require_once(BASE_URL . '/includes/adl_features.php');
require_once(BASE_URL . '/includes/Access_Levels.php');
require_once(BASE_URL . '/includes/adlfunctions.php');
require_once(BASE_URL . '/includes/ADL_PDO_CON.php');

if (isset($ffanalytics) && $ffanalytics == 1) {
    require_once(BASE_URL . '/app/analyticstracking.php');
}

if (isset($fferror) && $fferror == 1) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

require_once(BASE_URL . '/classes/database_class.php');
require_once(BASE_URL . '/class/login/login.php');

$CHECK_USER_LOGIN = new UserActions($hello_name, "NoToken");

$CHECK_USER_LOGIN->SelectToken();
$CHECK_USER_LOGIN->CheckAccessLevel();

$OUT = $CHECK_USER_LOGIN->SelectToken();

if (isset($OUT['TOKEN_SELECT']) && $OUT['TOKEN_SELECT'] != 'NoToken') {

    $TOKEN = $OUT['TOKEN_SELECT'];

}

$USER_ACCESS_LEVEL = $CHECK_USER_LOGIN->CheckAccessLevel();

$ACCESS_LEVEL = $USER_ACCESS_LEVEL['ACCESS_LEVEL'];

if ($ACCESS_LEVEL < 1) {

    header('Location: /../../../../index.php?AccessDenied&USER=' . $hello_name . '&COMPANY=' . $COMPANY_ENTITY);
    die;

}

$query = filter_input(INPUT_GET, 'query', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$EXECUTE = filter_input(INPUT_GET, 'EXECUTE', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

if (isset($query)) {

    require_once(BASE_URL . '/class/clientNote.php');

    $tracker_id = filter_input(INPUT_POST, 'tracker_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $agent = filter_input(INPUT_POST, 'agent_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $closer = filter_input(INPUT_POST, 'closer', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $client = filter_input(INPUT_POST, 'client', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $curprem = filter_input(INPUT_POST, 'current_premium', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $ourprem = filter_input(INPUT_POST, 'our_premium', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $comments = filter_input(INPUT_POST, 'comments', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $sale = filter_input(INPUT_POST, 'sale', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $dec = filter_input(INPUT_POST, 'dec', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $INSURER = filter_input(INPUT_POST, 'INSURER', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $cbDate = filter_input(INPUT_POST, 'cbDate', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $cbTime = filter_input(INPUT_POST, 'cbTime', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $sendNotification = filter_input(INPUT_POST, 'sendNotification', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $MTG = filter_input(INPUT_POST, 'MTG', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $LEAD_UP = filter_input(INPUT_POST, 'LEAD_UP', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $YEAR = date("Y");
    $DAY = date("D");
    $MONTH = date("M");
    $DATE = date("D d-m-y");

    if ($query == 'add') {

        //GET EMPLOYEE_ID TO ADD TO RAG

        $GET_EID = $pdo->prepare("SELECT 
    employee_id
FROM
    employee_details
WHERE
    CONCAT(firstname, ' ', lastname) = :NAME");
        $GET_EID->bindParam(':NAME', $agent, PDO::PARAM_STR);
        $GET_EID->execute();
        $EID_RESULT = $GET_EID->fetch(PDO::FETCH_ASSOC);

        $EID = $EID_RESULT['employee_id'];

        //INSERT INTO TRACKERS

        $INSERT = $pdo->prepare("INSERT INTO closer_trackers set insurer=:INSURER, mtg=:mtg, lead_up=:up, agent=:agent, closer=:closer, client=:client, phone=:phone, current_premium=:curprem, our_premium=:ourprem, comments=:comments, sale=:sale");
        $INSERT->bindParam(':agent', $agent, PDO::PARAM_STR);
        $INSERT->bindParam(':INSURER', $INSURER, PDO::PARAM_STR);
        $INSERT->bindParam(':closer', $closer, PDO::PARAM_STR);
        $INSERT->bindParam(':client', $client, PDO::PARAM_STR);
        $INSERT->bindParam(':phone', $phone, PDO::PARAM_STR);
        $INSERT->bindParam(':curprem', $curprem, PDO::PARAM_STR);
        $INSERT->bindParam(':mtg', $MTG, PDO::PARAM_STR);
        $INSERT->bindParam(':ourprem', $ourprem, PDO::PARAM_STR);
        $INSERT->bindParam(':comments', $comments, PDO::PARAM_STR);
        $INSERT->bindParam(':sale', $sale, PDO::PARAM_STR);
        $INSERT->bindParam(':up', $LEAD_UP, PDO::PARAM_STR);
        $INSERT->execute();
        $TID = $pdo->lastInsertId();

        if (isset($sendNotification) && $sendNotification == 'Yes' && isset($TID) && $TID > 0) {

            $INSERT = $pdo->prepare("UPDATE closer_trackers SET timeDeadLine=:timeDeadLine, dayDeadline=:dayDeadline WHERE tracker_id=:TID");
            $INSERT->bindParam(':timeDeadLine', $cbTime, PDO::PARAM_STR);
            $INSERT->bindParam(':dayDeadline', $cbDate, PDO::PARAM_STR);
            $INSERT->bindParam(':TID', $TID, PDO::PARAM_INT);
            $INSERT->execute();

            $INSERT = $pdo->prepare("INSERT INTO potential_clients SET status=:status, clientName=:clientName, owner=:owner, phoneNumber=:phoneNumber, addedBy=:addedBy, company=:company");
            $INSERT->bindParam(':company', $INSURER, PDO::PARAM_STR);
            $INSERT->bindParam(':addedBy', $closer, PDO::PARAM_STR);
            $INSERT->bindParam(':clientName', $client, PDO::PARAM_STR);
            $INSERT->bindParam(':phoneNumber', $phone, PDO::PARAM_STR);
            $INSERT->bindParam(':owner', $COMPANY_ENTITY, PDO::PARAM_STR);
            $INSERT->bindParam(':status', $sale, PDO::PARAM_STR);
            $INSERT->execute();
            $CID = $pdo->lastInsertId();

            $clientNoteMessage = "Potential Client Uploaded";
            $clientNoteNoteType = "Potential Client Added";
            $clientNoteAddedBy = $hello_name;
            $clientNoteReference = $client;

            $addTimelineNotes = new ADL\clientNote($CID, $pdo);
            $result = $addTimelineNotes->addPotentialClientNote($clientNoteMessage,
                $clientNoteNoteType,
                $clientNoteAddedBy,
                $clientNoteReference);

            $clientNoteMessage = "$sale - Deadline: $cbDate at $cbTime: $comments";
            $clientNoteNoteType = "Tracker Alert";
            $clientNoteAddedBy = $hello_name;
            $clientNoteReference = 'ADL Alert';

            $addTimelineNotes = new ADL\clientNote($CID, $pdo);
            $result = $addTimelineNotes->addPotentialClientNote($clientNoteMessage,
                $clientNoteNoteType,
                $clientNoteAddedBy,
                $clientNoteReference);


        } elseif (isset($sale) && $sale == 'SALE') {

        }

        //CHECK IF AGENT IS ON EMPLOYEE DATABASE FIRST OTHERWISE IGNORE BELOW
        if ($EID > '0') {

            //GET LEADS AND SALES

            $GET_LS = $pdo->prepare("SELECT 
agent,
    COUNT(IF(sale = 'SALE',
        1,
        NULL)) AS Sales,
 COUNT(IF(sale IN ('SALE' , 'NoCard',
            'QDE',
            'DEC',
            'QUN',
            'DIDNO',
            'QCBK',
            'QQQ',
            'QML',
                  'QNQ','NoCard','Hangup on XFER', 'Thought we were an insurer'),
        1,
        NULL)) AS Leads
FROM
    closer_trackers

WHERE
date_added > DATE(NOW()) AND agent=:agent");
            $GET_LS->bindParam(':agent', $agent, PDO::PARAM_STR);
            $GET_LS->execute();
            $GL_RESULT = $GET_LS->fetch(PDO::FETCH_ASSOC);

            $SALES = $GL_RESULT['Sales'];
            $LEADS = $GL_RESULT['Leads'];

            //CHECK IF AGENT IS ALREADY ON RAG

            $CHK_RAG = $pdo->prepare("SELECT 
    employee_id, id
FROM
    lead_rag
WHERE
    employee_id =:EID AND date=:date AND year=:year AND month=:month ");
            $CHK_RAG->bindParam(':EID', $EID, PDO::PARAM_STR);
            $CHK_RAG->bindParam(':date', $DATE, PDO::PARAM_STR);
            $CHK_RAG->bindParam(':year', $YEAR, PDO::PARAM_STR);
            $CHK_RAG->bindParam(':month', $MONTH, PDO::PARAM_STR);
            $CHK_RAG->execute();
            $CHK_RAGRESULT = $CHK_RAG->fetch(PDO::FETCH_ASSOC);
            if ($CHK_RAG->rowCount() >= 1) {

                $RAG_ID = $CHK_RAGRESULT['id'];

                //IF YES UPDATE

                $database = new Database();
                $database->beginTransaction();

                $database->query("UPDATE lead_rag set leads=:leads, sales=:sales, updated_by=:hello WHERE id=:RID");
                $database->bind(':RID', $RAG_ID);
                $database->bind(':leads', $LEADS);
                $database->bind(':sales', $SALES);
                $database->bind(':hello', $hello_name);
                $database->execute();

                $database->endTransaction();


            } else {

                //IF NO INSERT

                $database = new Database();
                $database->beginTransaction();

                $database->query("INSERT into lead_rag set sales=:sales, leads=:leads, employee_id=:REF, month=:month, year=:year, date=:date, updated_by=:hello");
                $database->bind(':REF', $EID);
                $database->bind(':leads', $LEADS);
                $database->bind(':sales', $SALES);
                $database->bind(':date', $DATE);
                $database->bind(':year', $YEAR);
                $database->bind(':month', $MONTH);
                $database->bind(':hello', $hello_name);
                $database->execute();
                $lastid = $database->lastInsertId();

                $database->query("INSERT INTO employee_register set employee_id=:REF, lead_rag_id=:lead_id");
                $database->bind(':REF', $EID);
                $database->bind(':lead_id', $lastid);
                $database->execute();
                $database->endTransaction();

            }

        }
        /*
          $year="2017";
          $group="NOT SET";
          $col="NOT SET";

          $pad_group="NOT SET";

          $PAD_INSERT = $pdo->prepare("INSERT INTO pad_statistics SET pad_statistics_group=:group, pad_statistics_lead=:lead, pad_statistics_closer=:closer, pad_statistics_notes=:notes, pad_statistics_status='White', pad_statistics_year=:year, pad_statistics_col=:col, pad_statistics_added_by=:hello");
          $PAD_INSERT->bindParam(':group', $pad_group, PDO::PARAM_STR);
          $PAD_INSERT->bindParam(':lead', $lead, PDO::PARAM_STR);
          $PAD_INSERT->bindParam(':closer', $closer, PDO::PARAM_STR);
          $PAD_INSERT->bindParam(':notes', $comments, PDO::PARAM_STR);
          $PAD_INSERT->bindParam(':year', $year, PDO::PARAM_STR);
          $PAD_INSERT->bindParam(':col', $col, PDO::PARAM_STR);
          $PAD_INSERT->bindParam(':hello', $hello_name, PDO::PARAM_STR);
          $PAD_INSERT->execute();
         */
        header('Location: ../Tracker.php?query=CloserTrackers&result=ADDED');
        die;
    }

    if ($query == 'edit') {

        //GET EMPLOYEE_ID TO ADD TO RAG

        $GET_EID = $pdo->prepare("SELECT 
    employee_id
FROM
    employee_details
WHERE
    CONCAT(firstname, ' ', lastname) = :NAME");
        $GET_EID->bindParam(':NAME', $agent, PDO::PARAM_STR);
        $GET_EID->execute();
        $EID_RESULT = $GET_EID->fetch(PDO::FETCH_ASSOC);

        $EID = $EID_RESULT['employee_id'];

        //UPDATE TRACKERS

        $UPDATE = $pdo->prepare("UPDATE closer_trackers set insurer=:INSURER, mtg=:mtg, lead_up=:up, agent=:agent, client=:client, phone=:phone, current_premium=:curprem, our_premium=:ourprem, comments=:comments, sale=:sale WHERE tracker_id=:id AND closer=:closer");
        $UPDATE->bindParam(':id', $tracker_id, PDO::PARAM_INT);
        $UPDATE->bindParam(':INSURER', $INSURER, PDO::PARAM_STR);
        $UPDATE->bindParam(':closer', $closer, PDO::PARAM_STR);
        $UPDATE->bindParam(':agent', $agent, PDO::PARAM_STR);
        $UPDATE->bindParam(':client', $client, PDO::PARAM_STR);
        $UPDATE->bindParam(':phone', $phone, PDO::PARAM_STR);
        $UPDATE->bindParam(':curprem', $curprem, PDO::PARAM_STR);
        $UPDATE->bindParam(':up', $LEAD_UP, PDO::PARAM_STR);
        $UPDATE->bindParam(':ourprem', $ourprem, PDO::PARAM_STR);
        $UPDATE->bindParam(':comments', $comments, PDO::PARAM_STR);
        $UPDATE->bindParam(':sale', $sale, PDO::PARAM_STR);
        $UPDATE->bindParam(':mtg', $MTG, PDO::PARAM_STR);
        $UPDATE->execute();

        if (isset($sendNotification)) {

            $GET_EID = $pdo->prepare("SELECT client_id FROM potential_clients WHERE phoneNumber=:phone");
            $GET_EID->bindParam(':phone', $phone, PDO::PARAM_INT);
            $GET_EID->execute();
            $EID_RESULT = $GET_EID->fetch(PDO::FETCH_ASSOC);

            $CID = $EID_RESULT['client_id'];

            if ($sendNotification == 'Yes') {

                $INSERT = $pdo->prepare("UPDATE closer_trackers SET timeDeadLine=:timeDeadLine, dayDeadline=:dayDeadline WHERE tracker_id=:TID");
                $INSERT->bindParam(':timeDeadLine', $cbTime, PDO::PARAM_STR);
                $INSERT->bindParam(':dayDeadline', $cbDate, PDO::PARAM_STR);
                $INSERT->bindParam(':TID', $tracker_id, PDO::PARAM_INT);
                $INSERT->execute();

                if (isset($CID)) {

                    $INSERT = $pdo->prepare("UPDATE potential_clients SET status=:status, phoneNumber=:phone, clientName=:clientName, updatedBy=:hello WHERE client_id=:CID");
                    $INSERT->bindParam(':phone', $phone, PDO::PARAM_STR);
                    $INSERT->bindParam(':hello', $hello_name, PDO::PARAM_STR);
                    $INSERT->bindParam(':clientName', $client, PDO::PARAM_STR);
                    $INSERT->bindParam(':status', $sale, PDO::PARAM_STR);
                    $INSERT->bindParam(':CID', $CID, PDO::PARAM_INT);
                    $INSERT->execute();

                    $clientNoteMessage = "$sale - Deadline: $cbDate at $cbTime: $comments";
                    $clientNoteNoteType = "Closer Tracker alert set";
                    $clientNoteAddedBy = $hello_name;
                    $clientNoteReference = 'ADL Alert';

                    $addTimelineNotes = new ADL\clientNote($CID, $pdo);
                    $result = $addTimelineNotes->addPotentialClientNote($clientNoteMessage,
                        $clientNoteNoteType,
                        $clientNoteAddedBy,
                        $clientNoteReference);

                } else {
                    $INSERT = $pdo->prepare("INSERT INTO potential_clients SET status=:status, clientName=:clientName, owner=:owner, phoneNumber=:phoneNumber, addedBy=:addedBy, company=:company");
                    $INSERT->bindParam(':company', $INSURER, PDO::PARAM_STR);
                    $INSERT->bindParam(':addedBy', $closer, PDO::PARAM_STR);
                    $INSERT->bindParam(':clientName', $client, PDO::PARAM_STR);
                    $INSERT->bindParam(':phoneNumber', $phone, PDO::PARAM_STR);
                    $INSERT->bindParam(':owner', $COMPANY_ENTITY, PDO::PARAM_STR);
                    $INSERT->bindParam(':status', $sale, PDO::PARAM_STR);
                    $INSERT->execute();
                    $CID = $pdo->lastInsertId();

                    $clientNoteMessage = "Potential Client Uploaded";
                    $clientNoteNoteType = "Potential Client Added";
                    $clientNoteAddedBy = $hello_name;
                    $clientNoteReference = $client;

                    $addTimelineNotes = new ADL\clientNote($CID, $pdo);
                    $result = $addTimelineNotes->addPotentialClientNote($clientNoteMessage,
                        $clientNoteNoteType,
                        $clientNoteAddedBy,
                        $clientNoteReference);

                    $clientNoteMessage = "$sale - Deadline: $cbDate at $cbTime: $comments";
                    $clientNoteNoteType = "Closer Tracker alert set";
                    $clientNoteAddedBy = $hello_name;
                    $clientNoteReference = 'ADL Alert';

                    $addTimelineNotes = new ADL\clientNote($CID, $pdo);
                    $result = $addTimelineNotes->addPotentialClientNote($clientNoteMessage,
                        $clientNoteNoteType,
                        $clientNoteAddedBy,
                        $clientNoteReference);
                }


            } elseif ($sendNotification == 'Complete') {

                $INSERT = $pdo->prepare("UPDATE closer_trackers SET timeDeadLine=NULL, dayDeadline=NULL WHERE tracker_id=:TID");
                $INSERT->bindParam(':TID', $tracker_id, PDO::PARAM_INT);
                $INSERT->execute();


                if (isset($CID)) {

                    $clientNoteMessage = "$sale - Deadline: $cbDate at $cbTime";
                    $clientNoteNoteType = "Closer Tracker alert complete";
                    $clientNoteAddedBy = $hello_name;
                    $clientNoteReference = 'ADL Alert';

                    $addTimelineNotes = new ADL\clientNote($CID, $pdo);
                    $result = $addTimelineNotes->addPotentialClientNote($clientNoteMessage,
                        $clientNoteNoteType,
                        $clientNoteAddedBy,
                        $clientNoteReference);

                }

            } elseif ($sendNotification == 'No') {

                $INSERT = $pdo->prepare("UPDATE closer_trackers SET timeDeadLine=NULL, dayDeadline=NULL WHERE tracker_id=:TID");
                $INSERT->bindParam(':TID', $tracker_id, PDO::PARAM_INT);
                $INSERT->execute();


                if (isset($CID)) {

                    $clientNoteMessage = "$sale - Deadline: $cbDate at $cbTime";
                    $clientNoteNoteType = "Closer Tracker alert dismissed";
                    $clientNoteAddedBy = $hello_name;
                    $clientNoteReference = 'ADL Alert';

                    $addTimelineNotes = new ADL\clientNote($CID, $pdo);
                    $result = $addTimelineNotes->addPotentialClientNote($clientNoteMessage,
                        $clientNoteNoteType,
                        $clientNoteAddedBy,
                        $clientNoteReference);

                }

            }

        }


        //CHECK IF AGENT IS ON EMPLOYEE DATABASE FIRST OTHERWISE IGNORE BELOW
        if ($EID > '0') {

            //GET LEADS AND SALES

            $GET_LS = $pdo->prepare("SELECT 
agent,
    COUNT(IF(sale = 'SALE',
        1,
        NULL)) AS Sales,
 COUNT(IF(sale IN ('SALE' , 'NoCard',
            'QDE',
            'DEC',
            'QUN',
            'DIDNO',
            'QCBK',
            'QQQ',
            'QML',
                  'QNQ','NoCard','Hangup on XFER', 'Thought we were an insurer'),
        1,
        NULL)) AS Leads
FROM
    closer_trackers

WHERE
date_added > DATE(NOW()) AND agent=:agent");
            $GET_LS->bindParam(':agent', $agent, PDO::PARAM_STR);
            $GET_LS->execute();
            $GL_RESULT = $GET_LS->fetch(PDO::FETCH_ASSOC);

            $SALES = $GL_RESULT['Sales'];
            $LEADS = $GL_RESULT['Leads'];

            //CHECK IF AGENT IS ALREADY ON RAG

            $CHK_RAG = $pdo->prepare("SELECT 
    employee_id, id
FROM
    lead_rag
WHERE
    employee_id =:EID AND date=:date AND year=:year AND month=:month ");
            $CHK_RAG->bindParam(':EID', $EID, PDO::PARAM_STR);
            $CHK_RAG->bindParam(':date', $DATE, PDO::PARAM_STR);
            $CHK_RAG->bindParam(':year', $YEAR, PDO::PARAM_STR);
            $CHK_RAG->bindParam(':month', $MONTH, PDO::PARAM_STR);
            $CHK_RAG->execute();
            $CHK_RAGRESULT = $CHK_RAG->fetch(PDO::FETCH_ASSOC);
            if ($CHK_RAG->rowCount() >= 1) {

                $RAG_ID = $CHK_RAGRESULT['id'];
                //IF YES UPDATE

                $database = new Database();
                $database->beginTransaction();

                $database->query("UPDATE lead_rag set sales=:sales, leads=:leads, updated_by=:hello WHERE id=:RID AND month=:month AND year=:year AND date=:date");
                $database->bind(':RID', $RAG_ID);
                $database->bind(':leads', $LEADS);
                $database->bind(':sales', $SALES);
                $database->bind(':date', $DATE);
                $database->bind(':year', $YEAR);
                $database->bind(':month', $MONTH);
                $database->bind(':hello', $hello_name);
                $database->execute();

                $database->endTransaction();


            } else {

                //IF NO INSERT

                $database = new Database();
                $database->beginTransaction();

                $database->query("INSERT INTO lead_rag set sales=:sales, leads=:leads, updated_by=:hello, employee_id=:REF, month=:month, year=:year, date=:date");
                $database->bind(':REF', $EID);
                $database->bind(':leads', $LEADS);
                $database->bind(':sales', $SALES);
                $database->bind(':date', $DATE);
                $database->bind(':year', $YEAR);
                $database->bind(':month', $MONTH);
                $database->bind(':hello', $hello_name);
                $database->execute();

            }

        }


        header('Location: ../Tracker.php?query=CloserTrackers&result=UPDATED');
        die;
    }

    if ($query == 'Alledit') {

        //GET EMPLOYEE_ID TO ADD TO RAG

        $GET_EID = $pdo->prepare("SELECT 
    employee_id
FROM
    employee_details
WHERE
    CONCAT(firstname, ' ', lastname) = :NAME");
        $GET_EID->bindParam(':NAME', $agent, PDO::PARAM_STR);
        $GET_EID->execute();
        $EID_RESULT = $GET_EID->fetch(PDO::FETCH_ASSOC);

        $EID = $EID_RESULT['employee_id'];

        //UPDATE TRACKERS

        $UPDATE = $pdo->prepare("UPDATE closer_trackers set insurer=:INSURER mtg=:mtg, lead_up=:up,  closer=:closer, agent=:agent, client=:client, phone=:phone, current_premium=:curprem, our_premium=:ourprem, comments=:comments, sale=:sale WHERE tracker_id=:id");
        $UPDATE->bindParam(':id', $tracker_id, PDO::PARAM_INT);
        $UPDATE->bindParam(':INSURER', $INSURER, PDO::PARAM_STR);
        $UPDATE->bindParam(':closer', $closer, PDO::PARAM_STR);
        $UPDATE->bindParam(':agent', $agent, PDO::PARAM_STR);
        $UPDATE->bindParam(':client', $client, PDO::PARAM_STR);
        $UPDATE->bindParam(':phone', $phone, PDO::PARAM_STR);
        $UPDATE->bindParam(':curprem', $curprem, PDO::PARAM_STR);
        $UPDATE->bindParam(':up', $LEAD_UP, PDO::PARAM_STR);
        $UPDATE->bindParam(':ourprem', $ourprem, PDO::PARAM_STR);
        $UPDATE->bindParam(':comments', $comments, PDO::PARAM_STR);
        $UPDATE->bindParam(':sale', $sale, PDO::PARAM_STR);
        $UPDATE->bindParam(':mtg', $MTG, PDO::PARAM_STR);
        $UPDATE->execute();

        //CHECK IF AGENT IS ON EMPLOYEE DATABASE FIRST OTHERWISE IGNORE BELOW
        if ($EID > '0') {

            //GET LEADS AND SALES

            $GET_LS = $pdo->prepare("SELECT 
agent,
    COUNT(IF(sale = 'SALE',
        1,
        NULL)) AS Sales,
 COUNT(IF(sale IN ('SALE' , 'NoCard',
            'QDE',
            'DEC',
            'QUN',
            'DIDNO',
            'QCBK',
            'QQQ',
            'QML',
                  'QNQ','NoCard','Hangup on XFER', 'Thought we were an insurer'),
        1,
        NULL)) AS Leads
FROM
    closer_trackers

WHERE
date_added > DATE(NOW()) AND agent=:agent");
            $GET_LS->bindParam(':agent', $agent, PDO::PARAM_STR);
            $GET_LS->execute();
            $GL_RESULT = $GET_LS->fetch(PDO::FETCH_ASSOC);

            $SALES = $GL_RESULT['Sales'];
            $LEADS = $GL_RESULT['Leads'];

            //CHECK IF AGENT IS ALREADY ON RAG

            $CHK_RAG = $pdo->prepare("SELECT 
    employee_id, id
FROM
    lead_rag
WHERE
    employee_id =:EID AND date=:date AND year=:year AND month=:month ");
            $CHK_RAG->bindParam(':EID', $EID, PDO::PARAM_STR);
            $CHK_RAG->bindParam(':date', $DATE, PDO::PARAM_STR);
            $CHK_RAG->bindParam(':year', $YEAR, PDO::PARAM_STR);
            $CHK_RAG->bindParam(':month', $MONTH, PDO::PARAM_STR);
            $CHK_RAG->execute();
            $CHK_RAGRESULT = $CHK_RAG->fetch(PDO::FETCH_ASSOC);
            if ($CHK_RAG->rowCount() >= 1) {

                $RAG_ID = $CHK_RAGRESULT['id'];
                //IF YES UPDATE

                $database = new Database();
                $database->beginTransaction();

                $database->query("UPDATE lead_rag set sales=:sales, leads=:leads, updated_by=:hello WHERE id=:RID AND month=:month AND year=:year AND date=:date");
                $database->bind(':RID', $RAG_ID);
                $database->bind(':leads', $LEADS);
                $database->bind(':sales', $SALES);
                $database->bind(':date', $DATE);
                $database->bind(':year', $YEAR);
                $database->bind(':month', $MONTH);
                $database->bind(':hello', $hello_name);
                $database->execute();

                $database->endTransaction();


            } else {

                //IF NO INSERT

                $database = new Database();
                $database->beginTransaction();

                $database->query("INSERT INTO lead_rag set sales=:sales, leads=:leads, updated_by=:hello, employee_id=:REF, month=:month, year=:year, date=:date");
                $database->bind(':REF', $EID);
                $database->bind(':leads', $LEADS);
                $database->bind(':sales', $SALES);
                $database->bind(':date', $DATE);
                $database->bind(':year', $YEAR);
                $database->bind(':month', $MONTH);
                $database->bind(':hello', $hello_name);
                $database->execute();

            }

        }

        header('Location: ../Tracker.php?query=AllCloserTrackers&result=UPDATED');
        die;
    }
}

header('Location: /../../../../CRMmain.php');
die;
