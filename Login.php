<?php
session_start();
$host="whale.csse.rose-hulman.edu"; // Host name 
$username="olsonmc"; // Mysql username 
$password="33upCosh"; // Mysql password 
$db_name="pokemonBattle"; // Database name 

// Connect to server and select databse.
$conn = mssql_connect("$host", "$username", "$password")or die("cannot connect"); 
mssql_select_db("$db_name",$conn)or die("cannot select DB");

// username and password sent from form 
$myusername=$_POST['myusername']; 
$mypassword=$_POST['mypassword']; 

// To protect MySQL injection (more detail about MySQL injection)
$myusername = stripslashes($myusername);
$mypassword = stripslashes($mypassword);
$myusername = mysql_real_escape_string($myusername);
$mypassword = mysql_real_escape_string($mypassword);
$sql="Authenticate '$myusername', '$mypassword'";
$result=mssql_query($sql);

// Mysql_num_row is counting table row
$count=mssql_num_rows($result);

// If result matched $myusername and $mypassword, table row must be 1 row
if($count==1){
$_SESSION['userName'] = $myusername;
// Register $myusername, $mypassword and redirect to file "login_success.php"
header("location:User.php");
}
else {
echo "Wrong Username or Password";
}
?>