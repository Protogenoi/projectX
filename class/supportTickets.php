<?php
/**
 * ------------------------------------------------------------------------
 *                               ADL CRM
 * ------------------------------------------------------------------------
 *
 * Copyright © 2019 ADL CRM All rights reserved.
 *
 * Unauthorised copying of this file, via any medium is strictly prohibited.
 * Unauthorised distribution of this file, via any medium is strictly prohibited.
 * Unauthorised modification of this code is strictly prohibited.
 *
 * Proprietary and confidential
 *
 * Written by michael <michael@adl-crm.uk>, 20/08/2019 15:26
 *
 * ADL CRM makes use of the following third party open sourced software/tools:
 *  Composer - https://getcomposer.org/doc/
 *  DataTables - https://github.com/DataTables/DataTables
 *  EasyAutocomplete - https://github.com/pawelczak/EasyAutocomplete
 *  PHPMailer - https://github.com/PHPMailer/PHPMailer
 *  ClockPicker - https://github.com/weareoutman/clockpicker
 *  fpdf17 - http://www.fpdf.org
 *  summernote - https://github.com/summernote/summernote
 *  Font Awesome - https://github.com/FortAwesome/Font-Awesome
 *  Bootstrap - https://github.com/twbs/bootstrap
 *  jQuery UI - https://github.com/jquery/jquery-ui
 *  Google Dev Tools - https://developers.google.com
 *  Twitter API - https://developer.twitter.com
 *  Webshim - https://github.com/aFarkas/webshim/releases/latest
 *  toastr - https://github.com/CodeSeven/toastr
 *  Twilio - https://github.com/twilio
 *  SendGrid - https://github.com/sendgrid
 *  Ideal Postcodes - https://ideal-postcodes.co.uk/documentation
 *  Chart.js - https://github.com/chartjs/Chart.js
 */

namespace ADL;

use ArrayObject;
use PDO;

require_once filter_input(INPUT_SERVER, 'DOCUMENT_ROOT', FILTER_SANITIZE_SPECIAL_CHARS) . '/app/core/doc_root.php';


class supportTickets
{

    protected $pdo;
    private $id;
    private $addedDate;
    private $updatedDate;
    private $addedBy;
    private $updatedBy;
    private $task;
    private $content;
    private $ticketStatus;
    private $category;
    private $fileName;
    private $fileType;
    private $fileLocation;
    private $assigned;

    private $site;
    private $user;
    private $pass;
    private $company;
    private $ipAddress;
    private $email;

    private $encryptionKey;

    public function __construct(PDO $pdo)
    {

        $this->pdo = $pdo;

    }

    public function createTicket()
    {

        $query = $this->pdo->prepare("INSERT INTO supportTickets SET addedBy=:hello, task=:task, content=:content, category=:category, assigned=:assigned");
        $query->bindParam(':hello', $this->addedBy, PDO::PARAM_STR);
        $query->bindParam(':task', $this->task, PDO::PARAM_STR);
        $query->bindParam(':content', $this->content, PDO::PARAM_STR);
        $query->bindParam(':category', $this->category, PDO::PARAM_STR);
        $query->bindParam(':assigned', $this->assigned[0], PDO::PARAM_STR);
        $query->execute();
        if ($query->rowCount() > 0) {

            $ticketID = $this->pdo->lastInsertId();

            $userNameCheck = ['Matt', 'Nick', 'Leigh', 'Paul', 'Charles', 'Andrew', 'Michael'];

            foreach ($this->assigned as $userNames):
                if (in_array($userNames, $userNameCheck, true)) {

                    $query = $this->pdo->prepare("INSERT INTO supportTeams SET username=:username, id_fk=:TID");
                    $query->bindParam(':username', $userNames, PDO::PARAM_STR);
                    $query->bindParam(':TID', $ticketID, PDO::PARAM_INT);
                    $query->execute();

                }

            endforeach;

            $query = $this->pdo->prepare("INSERT INTO ticketComments SET addedBy=:hello, content=:content, ticketID=:TID");
            $query->bindParam(':hello', $this->addedBy, PDO::PARAM_STR);
            $query->bindParam(':TID', $ticketID, PDO::PARAM_INT);
            $query->bindParam(':content', $this->content, PDO::PARAM_STR);
            $query->execute();

            return "success";

        } else {

            return "error";
        }
    }

    public function createCredentials()
    {
        $query = $this->pdo->prepare("INSERT INTO supportCredentials SET site=:site, user=:user, pass=AES_ENCRYPT(:pass, UNHEX(:key)), company=:company, ipAddress=INET_ATON(:ipAddress), info=:info, cred_id_fk=:cred_id_fk, email=:email");
        $query->bindParam(':site', $this->site, PDO::PARAM_STR);
        $query->bindParam(':email', $this->email, PDO::PARAM_STR);
        $query->bindParam(':user', $this->user, PDO::PARAM_STR);
        $query->bindParam(':pass', $this->pass, PDO::PARAM_STR);
        $query->bindParam(':company', $this->company, PDO::PARAM_STR);
        $query->bindParam(':ipAddress', $this->ipAddress, PDO::PARAM_STR);
        $query->bindParam(':info', $this->content, PDO::PARAM_STR);
        $query->bindParam(':cred_id_fk', $this->id, PDO::PARAM_STR);
        $query->bindParam(':key', $this->encryptionKey, PDO::PARAM_STR);
        $query->execute();
        if ($query->rowCount() > 0) {

            return "success";

        } else {

            return "error";
        }
    }

    public function getSupportCredentials()
    {

        $query = $this->pdo->prepare("SELECT site, user, pass, company, INET_ATON(ipAddress), info, cred_id_fk, email FROM supportCredentials WHERE cred_id_fk=:cred_id_fk");
        $query->bindParam(':cred_id_fk', $this->id, PDO::PARAM_INT);
        $query->execute();
        if ($query->rowCount() > 0) {

            /** @var ArrayObject[] $query */
            return $query->fetchAll(PDO::FETCH_ASSOC);

        } else {
            return 'error';
        }

    }

    public function getTicketStatusCounts()
    {

        if (isset($this->assigned)) {
            $query = $this->pdo->prepare("SELECT status, count(id) as counts FROM supportTickets WHERE assigned=:assigned group by status");
            $query->bindParam(':assigned', $this->assigned, PDO::PARAM_STR);
        } else {
            $query = $this->pdo->prepare("SELECT status, count(id) as counts FROM supportTickets group by status");
        }

        $query->execute();
        if ($query->rowCount() > 0) {

            return $query->fetchAll(PDO::FETCH_ASSOC);

        } else {

            return "error";
        }
    }

    public function getTicketCategoryCounts()
    {

        if (isset($this->assigned)) {
            $query = $this->pdo->prepare("SELECT category, count(id) as counts FROM supportTickets WHERE assigned=:assigned group by category");
            $query->bindParam(':assigned', $this->assigned, PDO::PARAM_STR);
        } else {
            $query = $this->pdo->prepare("SELECT category, count(id) as counts FROM supportTickets group by category");
        }

        $query->execute();
        if ($query->rowCount() > 0) {

            return $query->fetchAll(PDO::FETCH_ASSOC);

        } else {

            return "error";
        }
    }

    public function updateTicket()
    {

        if (isset($this->assigned)) {
            $query = $this->pdo->prepare("UPDATE supportTickets SET updatedBy=:hello, content=:content, status=:ticketStatus, category=:category, assigned=:assigned WHERE id=:TID");
            $query->bindParam(':assigned', $this->assigned, PDO::PARAM_STR);
        } else {
            $query = $this->pdo->prepare("UPDATE supportTickets SET updatedBy=:hello, content=:content, status=:ticketStatus, category=:category WHERE id=:TID");
        }

        $query->bindParam(':hello', $this->addedBy, PDO::PARAM_STR);
        $query->bindParam(':TID', $this->id, PDO::PARAM_STR);
        $query->bindParam(':content', $this->content, PDO::PARAM_STR);
        $query->bindParam(':ticketStatus', $this->ticketStatus, PDO::PARAM_STR);
        $query->bindParam(':category', $this->category, PDO::PARAM_STR);
        $query->execute();
        if ($query->rowCount() > 0) {

            $ticketID = $this->pdo->lastInsertId();

            $query = $this->pdo->prepare("INSERT INTO ticketComments SET addedBy=:hello, content=:content, ticketID=:TID");
            $query->bindParam(':hello', $this->addedBy, PDO::PARAM_STR);
            $query->bindParam(':TID', $this->id, PDO::PARAM_INT);
            $query->bindParam(':content', $this->content, PDO::PARAM_STR);
            $query->execute();

            return "success";

        } else {

            return "error";
        }
    }

    public function getSupportTickets()
    {

        if (isset($this->assigned)) {
            $query = $this->pdo->prepare("SELECT id, updatedDate, addedDate, addedBy, updatedBy, task, status, content, category, assigned FROM supportTickets WHERE assigned=:assigned ORDER BY updatedDate DESC");
            $query->bindParam(':assigned', $this->assigned, PDO::PARAM_STR);
        } else {
            $query = $this->pdo->prepare("SELECT id, updatedDate, addedDate, addedBy, updatedBy, task, status, content, category, assigned FROM supportTickets ORDER BY updatedDate DESC");
        }

        $query->execute();
        if ($query->rowCount() > 0) {

            /** @var ArrayObject[] $query */
            return $query->fetchAll(PDO::FETCH_ASSOC);

        } else {
            return 'error';
        }

    }

    public function getSupportTicketsByStatus()
    {

        if (isset($this->assigned)) {
            $query = $this->pdo->prepare("SELECT id, updatedDate, addedDate, addedBy, updatedBy, task, status, content, category, assigned FROM supportTickets WHERE status=:ticketStatus AND assigned=:assigned ORDER BY updatedDate DESC");
            $query->bindParam(':assigned', $this->assigned, PDO::PARAM_STR);

        } else {
            $query = $this->pdo->prepare("SELECT id, updatedDate, addedDate, addedBy, updatedBy, task, status, content, category, assigned FROM supportTickets WHERE status=:ticketStatus ORDER BY updatedDate DESC");

        }
        $query->bindParam(':ticketStatus', $this->ticketStatus, PDO::PARAM_STR);
        $query->execute();
        if ($query->rowCount() > 0) {

            /** @var ArrayObject[] $query */
            return $query->fetchAll(PDO::FETCH_ASSOC);

        } else {
            return 'error';
        }
    }

    public function getSupportTicketsByCategory()
    {

        if (isset($this->assigned)) {
            $query = $this->pdo->prepare("SELECT id, updatedDate, addedDate, addedBy, updatedBy, task, status, content, category, assigned FROM supportTickets WHERE category=:category AND assigned=:assigned ORDER BY updatedDate DESC");
            $query->bindParam(':assigned', $this->assigned, PDO::PARAM_STR);

        } else {
            $query = $this->pdo->prepare("SELECT id, updatedDate, addedDate, addedBy, updatedBy, task, status, content, category, assigned FROM supportTickets WHERE category=:category ORDER BY updatedDate DESC");

        }
        $query->bindParam(':category', $this->category, PDO::PARAM_STR);
        $query->execute();
        if ($query->rowCount() > 0) {

            /** @var ArrayObject[] $query */
            return $query->fetchAll(PDO::FETCH_ASSOC);

        } else {
            return 'error';
        }
    }

    public function getSupportTicketsByCategoryAndStatus()
    {

        if (isset($this->assigned)) {
            $query = $this->pdo->prepare("SELECT id, updatedDate, addedDate, addedBy, updatedBy, task, status, content, category, assigned FROM supportTickets WHERE category=:category AND status=:status AND assigned=:assigned ORDER BY updatedDate DESC");
            $query->bindParam(':assigned', $this->assigned, PDO::PARAM_STR);

        } else {
            $query = $this->pdo->prepare("SELECT id, updatedDate, addedDate, addedBy, updatedBy, task, status, content, category, assigned FROM supportTickets WHERE category=:category AND status=:status ORDER BY updatedDate DESC");

        }
        $query->bindParam(':category', $this->category, PDO::PARAM_STR);
        $query->bindParam(':status', $this->ticketStatus, PDO::PARAM_STR);
        $query->execute();
        if ($query->rowCount() > 0) {

            /** @var ArrayObject[] $query */
            return $query->fetchAll(PDO::FETCH_ASSOC);

        } else {
            return 'error';
        }
    }


    public function getSupportTicketsByID()
    {

        if (isset($this->assigned)) {

            $query = $this->pdo->prepare("SELECT category, ticketComments.id, ticketID, ticketComments.addedDate, ticketComments.addedBy, supportTickets.task, ticketComments.content, supportTickets.status, assigned FROM supportTickets JOIN ticketComments ON ticketComments.ticketID = supportTickets.id WHERE assigned=:assigned AND supportTickets.id=:TID ORDER BY ticketComments.id DESC");
            $query->bindParam(':assigned', $this->assigned, PDO::PARAM_INT);
        } else {

            $query = $this->pdo->prepare("SELECT category, ticketComments.id, ticketID, ticketComments.addedDate, ticketComments.addedBy, supportTickets.task, ticketComments.content, supportTickets.status, assigned FROM supportTickets JOIN ticketComments ON ticketComments.ticketID = supportTickets.id WHERE supportTickets.id=:TID ORDER BY ticketComments.id DESC");
        }

        $query->bindParam(':TID', $this->id, PDO::PARAM_INT);
        $query->execute();
        if ($query->rowCount() > 0) {

            /** @var ArrayObject[] $query */
            return $query->fetchAll(PDO::FETCH_ASSOC);

        } else {
            return 'error';
        }

    }

    public function uploadSupportTicketFiles()
    {

        $query = $this->pdo->prepare("INSERT INTO supportUploads set addedBy=:addedBy, fileName=:fileName, filetype=:fileType, fileLocation=:fileLocation, fileCategory=:category, id_fk=:ticketID");
        $query->bindParam(':ticketID', $this->id, PDO::PARAM_INT);
        $query->bindParam(':addedBy', $this->addedBy, PDO::PARAM_STR);
        $query->bindParam(':fileName', $this->fileName, PDO::PARAM_STR);
        $query->bindParam(':fileType', $this->fileType, PDO::PARAM_STR);
        $query->bindParam(':fileLocation', $this->fileLocation, PDO::PARAM_STR);
        $query->bindParam(':category', $this->category, PDO::PARAM_STR);
        $query->execute();
        if ($query->rowCount() > 0) {

            return 'success';

        } else {
            return 'error';
        }

    }

    public function getUploadSupportTicketFilesByTicketID()
    {

        $query = $this->pdo->prepare("SELECT addedBy, fileName, filetype, fileLocation, fileCategory FROM supportUploads WHERE id_fk=:ticketID");
        $query->bindParam(':ticketID', $this->id, PDO::PARAM_INT);
        $query->execute();
        if ($query->rowCount() > 0) {

            /** @var ArrayObject[] $query */
            return $query->fetchAll(PDO::FETCH_ASSOC);

        } else {
            return 'error';
        }

    }

    public function getStatusColour()
    {

        switch ($this->ticketStatus):
            case 'Open';
                $statusColour = 'danger';
                break;
            case 'Closed';
                $statusColour = 'primary';
                break;
            case 'In progess';
                $statusColour = 'success';
                break;
            case 'Waiting Reply';
                $statusColour = 'warning';
                break;
            default:
                $statusColour = 'default';
        endswitch;

        return $statusColour;

    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param mixed $addedDate
     */
    public function setAddedDate($addedDate)
    {
        $this->addedDate = $addedDate;
    }

    /**
     * @param mixed $updatedDate
     */
    public function setUpdatedDate($updatedDate)
    {
        $this->updatedDate = $updatedDate;
    }

    /**
     * @param mixed $addedBy
     */
    public function setAddedBy($addedBy)
    {
        $this->addedBy = $addedBy;
    }

    /**
     * @param mixed $updatedBy
     */
    public function setUpdatedBy($updatedBy)
    {
        $this->updatedBy = $updatedBy;
    }

    /**
     * @param mixed $task
     */
    public function setTask($task)
    {
        $this->task = $task;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @param mixed $ticketStatus
     */
    public function setTicketStatus($ticketStatus)
    {
        $this->ticketStatus = $ticketStatus;
    }

    /**
     * @param string $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @param string $fileName
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * @param string $fileType
     */
    public function setFileType($fileType)
    {
        $this->fileType = $fileType;
    }

    /**
     * @param mixed $fileLocation
     */
    public function setFileLocation($fileLocation)
    {
        $this->fileLocation = $fileLocation;
    }

    /**
     * @param array $assigned
     */
    public function setAssigned($assigned)
    {
        $this->assigned = $assigned;
    }

    /**
     * @param mixed $site
     */
    public function setSite($site)
    {
        $this->site = $site;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @param mixed $company
     */
    public function setCompany($company)
    {
        $this->company = $company;
    }

    /**
     * @param mixed $ipAddress
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;
    }

    /**
     * @param mixed $encryptionKey
     */
    public function setEncryptionKey($encryptionKey)
    {
        $this->encryptionKey = $encryptionKey;
    }

    /**
     * @param mixed $pass
     */
    public function setPass($pass)
    {
        $this->pass = $pass;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

}
