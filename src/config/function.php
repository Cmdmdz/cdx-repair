<?php
include('env.config.php');

class DB_con
{
    /**
     * @return false|mysqli
     */
    private $databaseConnect;

    function __construct()
    {
        $conn = mysqli_connect($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD'], $_ENV['DB_DATABASE']);
        $this->databaseConnect = $conn;

        if (mysqli_connect_errno()) {
            echo "Failed to connect to MySQL" . mysqli_connect_error();
        }

    }

    public function register($name, $email, $password, $token, $id_rank)
    {
        return mysqli_query($this->databaseConnect, "INSERT INTO `repairman`(`name`, `email`, `password`, `token`,`id_rank`, `contact_id` ) VALUES ('$name','$email','$password','$token','$id_rank','F')");
    }

    public function login($email, $password)
    {
        return mysqli_query($this->databaseConnect, "select * from `repairman` where email = '$email' and password = '$password'");
    }

    public function existEmail($email)
    {
        return mysqli_query($this->databaseConnect, "SELECT email FROM repairman WHERE email = '$email' ");
    }

    public function findAllCase()
    {
        return mysqli_query($this->databaseConnect, "select * from case_repair inner join listrank  on case_repair.rank_case_id = listrank.list_rank_id ORDER BY lv ASC, case_date DESC ");
    }

    public function findCaseById($id)
    {
        return mysqli_query($this->databaseConnect, "select * from case_repair inner join repairman r on case_repair.repairman_id = r.repairman_id where case_id = '$id'");

    }

    public function updateStatusCase($id, $status, $repairmanId)
    {
        return mysqli_query($this->databaseConnect, "UPDATE `case_repair` SET `status`='$status', repairman_id='$repairmanId' WHERE case_id = '$id'");

    }

    public function updateRepairman($id, $name, $email)
    {
        return mysqli_query($this->databaseConnect, "UPDATE `repairman` SET `name`='$name', `email`='$email' WHERE repairman_id = '$id'");

    }

    public function deleteStatusCaseByCaseId($id)
    {
        mysqli_query($this->databaseConnect, "DELETE FROM `notification` WHERE case_repair_id = '$id'");

        return mysqli_query($this->databaseConnect, "DELETE FROM `case_repair` WHERE case_id = '$id'");

    }

    public function deleteRepairmanById($repairman_id)
    {
        $query = mysqli_query($this->databaseConnect, "select * from `case_repair` where repairman_id = '$repairman_id'");
        if (mysqli_num_rows($query) > 0) {

            mysqli_query($this->databaseConnect, "UPDATE `case_repair` SET `repairman_id`='17' WHERE repairman_id = '$repairman_id'");

        }
        return mysqli_query($this->databaseConnect, "DELETE FROM `repairman` WHERE repairman_id = '$repairman_id'");
    }

    public function findAllRepairman($rankId)
    {
        if ($rankId == 3) {
            return mysqli_query($this->databaseConnect, "select * from repairman inner join `rank` rc on repairman.id_rank = rc.id where rc.id = 1 or rc.id = 2 ORDER BY id_rank ASC");
        } else {
            return mysqli_query($this->databaseConnect, "select * from repairman inner join `rank` rc on repairman.id_rank = rc.id where rc.id = 2 ORDER BY id_rank ASC");
        }
    }

    public function findRepairmanById($repairmanId)
    {
        return mysqli_query($this->databaseConnect, "select * from repairman inner join `rank` rc on repairman.id_rank = rc.id where repairman.repairman_id = '$repairmanId'");

    }

    public function findRepairmanByEmail()
    {
        return mysqli_query($this->databaseConnect, "select * from repairman  where email = 'admin@gmail.com'");

    }

    public function findAllRank()
    {
        $array = array();
        $result = mysqli_query($this->databaseConnect, "select * from `rank`");

        while ($row = mysqli_fetch_assoc($result)) {
            $array[] = $row;
        }

        return $array;
    }

    public function findAllRankCase()
    {
        $array = array();
        $result = mysqli_query($this->databaseConnect, "select * from `rank_case`");

        while ($row = mysqli_fetch_assoc($result)) {
            $array[] = $row;
        }

        return $array;
    }

    public function createCase($firstName, $lastName, $mobileNumber, $detail, $status, $rank_case_id, $contact_id, $repairman_id)
    {


        mysqli_query($this->databaseConnect, "INSERT INTO `case_repair`(`firstName`, `lastName`, `mobileNumber`, `detail_case`, `status`, `rank_case_id`,`contact_id`,`case_date`, `repairman_id` ) VALUES ('$firstName','$lastName','$mobileNumber','$detail','$status','$rank_case_id','$contact_id', now(), '$repairman_id')");

        $result = mysqli_query($this->databaseConnect, "select * from case_repair where contact_id = '$contact_id'");

        if (mysqli_num_rows($result) > 0) {
            foreach ($result as $row) {
                $caseId = $row['case_id'];
                mysqli_query($this->databaseConnect, "insert into notification(noti_status, case_repair_id) VALUES (0,'$caseId')");

            }
        }

        return mysqli_query($this->databaseConnect, "INSERT INTO `history_case`(`firstName`, `lastName`, `mobileNumber`, `detail_case`, `status`, `rank_case_id`,`contact_id`,`case_date`  ) VALUES ('$firstName','$lastName','$mobileNumber','$detail','$status','$rank_case_id','$contact_id', now())");

    }

    public function findAllHistory()
    {
        return mysqli_query($this->databaseConnect, "select * from history_case inner join rank_case  on history_case.rank_case_id = rank_case.id  ");
    }

    public function findCaseByRepairmanId($repairmanId)
    {
        $array = array();
        $result = mysqli_query($this->databaseConnect, "select * from case_repair where repairman_id = '$repairmanId'");

        while ($row = mysqli_fetch_assoc($result)) {
            $array[] = $row;
        }

        return $array;

    }

    public function countNoti()
    {
        return mysqli_query($this->databaseConnect, "select * from notification inner join case_repair cr on notification.case_repair_id = cr.case_id");
    }


    public function generateRandomString($length)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function findAllListRank()
    {
        return mysqli_query($this->databaseConnect, "select * from listrank");

    }

}


