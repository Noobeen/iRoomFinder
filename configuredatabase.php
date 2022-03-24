<?php

$server="localhost";
$user="root";
$password="";
$db_name="signupdb";

$conn=new mysqli($server,$user,$password);
if($conn->connect_error)
{
    die("error : ".$conn->connect_error);
}
$sql_comm="CREATE DATABASE ".$db_name;
if($conn->query($sql_comm)===TRUE)
{
    echo "The database with name ".$db_name." created succesfully";
}
else
{
    echo "something went wrong ".$conn->connect_error;
}

$conn->close();

$newconn=new mysqli($server,$user,$password,$db_name);
if($newconn->connect_error)
{
    die("error : ".$newconn->connect_error);
}

$query="CREATE TABLE signin (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    log_password VARCHAR(50) NOT NULL,
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";

if($newconn->query($query)===TRUE)
{
    echo "table also created succesfully now you can run other scripts";
}

else
{
    echo "some thing went worng ".$newconn->connect_error;
}

?>