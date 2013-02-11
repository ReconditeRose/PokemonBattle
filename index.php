<!DOCTYPE html>

<?php session_start(); ?>

<?php
$host="whale.csse.rose-hulman.edu"; // Host name 
$username="olsonmc"; // Mysql username 
$password="33upCosh"; // Mysql password 
$db_name="pokemonBattle"; // Database name 

// Connect to server and select databse.
$conn = mssql_connect("$host", "$username", "$password")or die("cannot connect"); 
mssql_select_db("$db_name",$conn)or die("cannot select DB");

?>



<html>
<body background = "/images/pw_bg.png">
<head>
<title>Welcome!</title>
</head>
<body>
<li><a href="/Register.php">Register</a></li>
<h1>Welcome to Pokemon Battle!</h1>
<href = 
<h2> Login </h2>


<form name="form1" method="post" action="login.php">
<tr>
<td width="78">Username:</td>
<td width="294"><input name="myusername" type="text" id="myusername"></td>
<p>
<td>Password:</td>
<td><input name="mypassword" type="password" id="mypassword"></td>
<p>
<td><input type="submit" name="Submit" value="Login"></td>
</tr>
</form>


<h2>Displaying Some pokemon Users</h2>
<h3> and there passwords since we don't value security </h3>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    $id = $_POST['postID'];
	
		$id = mysqli_real_escape_string($conn,$id);

		$result = 'Select * from User';
		echo "Post Deleted";
		mssql_query($conn,"$result") or die(‘error’);

		$_SERVER['REQUEST_URI'];

	

}
?>

<?php
// Select the 30 most recent posts from our friendly posts view
$posts = mssql_query('SELECT * FROM [User]',$conn);
					 
					 // Display each post



while ($row = mssql_fetch_array($posts)) {
	$name = htmlspecialchars($row[0], ENT_QUOTES);
	$body = htmlspecialchars($row[2], ENT_QUOTES);
    echo '' . $name .' '. $body . '<p>';
}



?>


<!DOCTYPE html>
