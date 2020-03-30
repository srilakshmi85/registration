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
        $query = 'SELECT * FROM MyGuests where email_id = ?';
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
       echo $result = $this->isMemberExists($_POST["email"]);
        if ($result < 1) {
            if (! empty($_POST["signup-password"])) {
                $hashedPassword = password_hash($_POST["signup-password"], PASSWORD_DEFAULT);
            }
           echo $query = 'INSERT INTO MyGuests (firstname, email_id, user_password) VALUES (?, ?, ?)';
            $paramType = 'sss';
            $paramValue = array(
                $_POST["email"],
                $_POST["signup-password"],
                $_POST["email"]
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
        $query = 'SELECT * FROM MyGuests where email_id = ?';
        $paramType = 's';
        $paramValue = array(
            $username
        );
        $loginUser = $this->ds->select($query, $paramType, $paramValue);
        return $loginUser;
    }

    public function loginMember()
    {
        $loginUserResult = $this->getMember($_POST["email"]);
        if (! empty($_POST["signup-password"])) {
            $password = $_POST["signup-password"];
        }
        $hashedPassword = $loginUserResult[0]["user_password"];
        $loginPassword = 0;
        if (password_verify($password, $hashedPassword)) {
            $loginPassword = 1;
        }
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
