<?php


class handler
{
    public $email;
    public $password;
    public $conn;
    public $password_corr;
    public $email_corr;

    function __construct($mail,$pass)
    {
      $this->email=$mail;
      $this->password=$pass;
      $this->conn=new mysqli("localhost","root","","signupdb");
      if($this->conn->connect_error===TRUE)
        {
            die ("sorry unable to take you data due to ".$this->conn->connect_error);
        }  
    }

    function final_handel()
    {
        $sql=$this->conn->prepare("SELECT username,email,log_password FROM signin WHERE email=?");
        $sql->bind_param("s",$this->email);
        if(mysqli_stmt_execute($sql))
        {
            mysqli_stmt_store_result($sql);
            if(mysqli_stmt_num_rows($sql) == 1)
            {
                mysqli_stmt_bind_result($sql,$username,$this->email_corr, $this->password_corr);
                if(mysqli_stmt_fetch($sql))
                {
                    if($this->password===$this->password_corr)
                    {
                        echo "<br> <h1>you have succesfully logged in thanks ".$username."</h1> <br>";
                    }
                    else{
                        echo "<br> <h1> sorry password mismatch</h1><br>";
                    }
                }
            }
            else
            {
                echo "user is not registered with this email";
            }

        }

        else
        {
            echo "something went wrong plz resubmit the form";
        }

        $sql->close();
        $this->conn->close();

    }
}

$email = $password = "";
$emailErr = "";

function clean_input($data) 
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    $email=clean_input($_POST["email"]);
    $password=clean_input($_POST["password"]);

    if(empty($email) ||empty($password))
    {
        echo "<br>THE INPUT CANNOT BE LEFT BLANK<br>";
    }
    else
    {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $emailErr = "Invalid email format";
        }
        else
        {
            $request=new handler($email,$password);
            $request->final_handel();

        }
    }
}
else
{
    echo "SOMETHING WENT WRONG plz resubmit the form ";
} 

echo $emailErr;
?>