<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class Member
{

    private $ds;

    function __construct()
    {
        require_once  './DataSource.php';
        $this->ds = new DataSource();
    }

    public function isMemberExists($email)
    {
        $query = 'SELECT * FROM MyGuests1 where email_id = ?';
        $paramType = 's';
        $paramValue = array(
            $email
        );
        $insertRecord = $this->ds->select($query, $paramType, $paramValue);
        $count = 0;
        if (is_array($insertRecord)) {
            $count = count($insertRecord);
        }
        return $count;
    }

    public function registerMember()
    {
       $response=0;
        $result = $this->isMemberExists($_POST["email"]);
        if ($result < 1) {
            if (! empty($_POST["signup-password"])) {
                $hashedPassword = password_hash($_POST["signup-password"], PASSWORD_DEFAULT);
            }
            $query = 'INSERT INTO MyGuests1 (firstname, email_id, user_password) VALUES (?, ?, ?)';
            $paramType = 'sss';
            $paramValue = array(
                $_POST["username"],
                $_POST["email"],
                $_POST["signup-password"]
            );

            $memberId = $this->ds->insert($query, $paramType, $paramValue);
            if(!empty($memberId)) {
                $response = array("status" => "success", "message" => "You have registered successfully.");
            }
        } else if ($result == 1) {
            $response = array("status" => "error", "message" => "Email already exists.");
        }
        return $response;
    }

    public function getMember($username)
    {
        $query = 'SELECT * FROM MyGuests1 where email_id = ?';
        $paramType = 's';
        $paramValue = array(
            $username
        );
        $loginUser = $this->ds->select($query, $paramType, $paramValue);
        return $loginUser;
    }

    public function loginMember()
    {
        $loginUserResult = $this->getMember($_POST["username"]);
        if (! empty($_POST["signup-password"])) {
            $password = $_POST["signup-password"];
        }
        $hashedPassword = $loginUserResult[0]["user_password"];
        $loginPassword = 1;
        /*if (password_verify($password, $hashedPassword)) {
            $loginPassword = 1;
        }*/
        if ($loginPassword == 1) {
            $_SESSION["firstname"] = $loginUserResult[0]["email_id"];
            $url = "./home.php";
            header("Location: $url");
        } else if ($loginPassword == 0) {
            $loginStatus = "Invalid username or password.";
            return $loginStatus;
        }
    }
}
?>
