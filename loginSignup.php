<?php

class Register
{
    public $username;
    public $email;
    public $password;
    public $conn;
    public $vkey;
    


    function __construct($name,$email,$password)
    {
        $this->username=$name;
        $this->email=$email;
        $this->password=$password;
        //$this->connect=$connect;
        $this->conn=new mysqli("localhost","root","","signupdb");
        if($this->conn->connect_error===TRUE)
        {
            die ("sorry unable to take you data due to ".$this->conn->connect_error);
        }
    }
    //this function checks and inserts users into the database
    function duplication_check()
    {   
        //creating sql query
        $sql=$this->conn->prepare("SELECT email FROM signin WHERE email=?");
        $sql->bind_param("s",$this->email);
        if(mysqli_stmt_execute($sql))
        {
            mysqli_stmt_store_result($sql);
            
            //checking email address wheather it exists already or not
            if(mysqli_stmt_num_rows($sql) > 0)
            {
                $sql->close();
                die("This email is already taken"); 
            }
            else
            {
                $this->vkey=md5(time().$this->email);//for email verification hash is generated
                $sql->close();
                //inserting into database
                $reg=$this->conn->prepare("INSERT INTO signin(username,email,log_password,v_key)VALUES(?,?,?,?)");
                $reg->bind_param("ssss",$this->username,$this->email,$this->password,$this->vkey);
                
                //checking wheather the insertion was succesfull or not
                if(mysqli_stmt_execute($reg))
                {
                    echo "SUCESSFULLY INSERTED AS USER";
                }
                else
                {
                    echo("something Went wrong");
                }

                $reg->close();
            }
        }
        else{
            echo "Something went wrong";
            $sql->close();
        }

        $this->conn->close();
    }

}

$Username = $Email = $Password = $usernameErr = $emailErr = $passwordErr ="";

function clean_input($data) 
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
/*$Password ="/@helo /";
$Username ="Nabin oli";
$Email="example@gmail.com";?*/
if ($_SERVER['REQUEST_METHOD'] == "POST")
{
    $Username=clean_input($_POST["username"]);
    $Password=clean_input($_POST["password"]);
    $Email = clean_input($_POST["email"]);
    $a=0;

    if(empty($Username))
    {
        $usernameErr = "Username cannot be left blank";
    }
    elseif(!preg_match("/^[a-zA-Z-' ]*$/",$Username)) 
    {
        $usernameErr = "Only letters and white space allowed";
    }
    else
    {
        $a=$a+1;
    }  
    
    
    if(empty($Email))
    {
        $emailErr = "Email cannot be left blank";
    }
    elseif(!filter_var($Email, FILTER_VALIDATE_EMAIL))
    {
        $emailErr = "Invalid email format";
    }
    else
    {
        $a=$a+1;
    }

    if(empty($Password))
    {
        $passwordErr="Password cannot be left blank";
    }
    elseif(strlen($Password)<6)
    {
        $passwordErr="Password length cannot be less than 6";
    }
    elseif($a<2)
    {
        echo "Plz enter the correct value again";
    }
    else
    {
        $sql_request=new Register($Username,$Email,$Password);
        $sql_request->duplication_check();
    }

    echo $passwordErr."<br>".$emailErr."<br>".$usernameErr;
}






?>



<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Login // Sign up</title>
	<link rel="stylesheet" href="login.css">
</head>
<body>
	<br>
<br>
<div class="p">You need to sign in first</div>
    <div class="cont">
        <div class="form sign-in">
            <form method="post" action="login.php">
            <h2>Welcome <br>
			 </h2>
            <label>
                <span>Email</span>
                <input type="text" name="email"  />
            </label>
            <label>
                <span>Password</span>
                <input type="password" name="password" />
            </label>
            <p class="forgot-pass"><a style="text-decoration: none; color: rgb(185, 175, 162);" href="forgotpassword.html">Forgot password?</a></p>
            <button type="submit" class="submit">Sign In</button>
            </form>
         
        </div>
        <div class="sub-cont">
            <div class="img">
                <div class="img__text m--up">
                 
                    <h3>Don't have an account? Please Sign up!<h3>
                </div>
                <div class="img__text m--in">
                
                    <h3>If you already has an account, just sign in.<h3>
                </div>
                <div class="img__btn">
                    <span class="m--up">Sign Up</span>
                    <span class="m--in">Sign In</span>
                </div>
            </div>
            <div class="form sign-up">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <h2>Create your Account</h2>
                <label>
                    <span>Name</span>
                    <input type="text" name="username" />
                </label>
                <label>
                    <span>Email</span>
                    <input type="email" name="email" />
                </label>
                <label>
                    <span>Password</span>
                    <input type="password" name="password"/>
                </label>
                <button type="submit" class="submit">Sign Up</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.querySelector('.img__btn').addEventListener('click', function() {
            document.querySelector('.cont').classList.toggle('s--signup');
        });
    </script>
</body>
</html>