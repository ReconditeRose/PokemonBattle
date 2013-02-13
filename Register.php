<?php
session_start();
$host="whale.csse.rose-hulman.edu"; // Host name 
$username="olsonmc"; // Mysql username 
$password="33upCosh"; // Mysql password 
$db_name="pokemonBattle"; // Database name 

// Connect to server and select databse.
$conn = mssql_connect("$host", "$username", "$password")or die("cannot connect"); 
mssql_select_db("$db_name",$conn)or die("cannot select DB");

?>
<body background = "/images/pw_bg.jpg">
<!DOCTYPE html>

<html>
<head>
<a href = "index.php">Home</a>
<title>Register</title>
</head>
<body>

<h1>Register</h1>
<?php
// Only execute if we're receiving a post
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // This will be the string we collect errors in
    $errors = '';

    // Make sure the name field is filled
    $email =  $_POST['email'];
    if (empty($email)) $errors .= '<li>Email is required</li>';
	if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9+-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i", $email)) {
		$errors .= '<li>Email address is <u>not</u> valid.</li>';
	}
    // Make sure the username field is filled
    $username = $_POST['username'];
    if (empty($username)) $errors .= '<li>Username is required</li>';
	
	if (!preg_match("/^[a-zA-Z0-9]{6,}/", $username)) {
		$errors .= '<li>Username is <u>not</u> at least 6 alphanumeric characters.</li>';
	}

    // Make sure the password field is filled
    $password = $_POST['password'];
    if (empty($password)) $errors .= '<li>Password is required</li>';
	if (!preg_match("/^[a-zA-Z0-9]{6,}^/", $password)) {
		$errors .= '<li>Password is <u>not</u> at least 6 alphanumeric characters. May not contain special characters</li>';
	}
    // Make sure the passwords match
    $confirm = $_POST['confirmpassword'];
    if (strcmp($password, $confirm) != 0) $errors .= '<li>Passwords do not match</li>';

    // If we have any errors at this point, stop here and show them
    if (!empty($errors)) {
        echo '<ul>' . $errors . '</ul>';

    // Otherwise, begin the user creation process
    } else {
            // First, check for that username already being taken
    $user_results = mssql_query( "exec getUser '$username'",$conn);
    // We don't care what the result is
    // If there is one, that means the username is taken
    if ($user_results) {
    	if (mssql_fetch_array($user_results)) {
        	echo '<ul><li>Username already taken</li></ul>';
	} else{
    // If no duplicates are found, go ahead and create the new user
echo "workingb";
$command = "Exec RegisterParty '$username','$password','$email'";
mssql_query($command,$conn);
echo $command;
header("location:index.php");

        // Show a success message
        echo '<ul><li>Registration successful!</li></ul>';

        // Set the name and username fields to empty strings so they don't
        // get automatically repopulated
        $name = '';
        $username = '';
    }
}
    }
}
?>


<form action="" method="post">
    <label for="name">Email</label><br/>
    <input type="text" name="email"/><br/>

    <label for="username">Username</label><br/>
    <input type="text" name="username"/><br/>

    <label for="password">Password</label><br/>
    <input type="password" name="password"/><br/>

    <label for="confirmpassword">Confirm Password</label><br/>
    <input type="password" name="confirmpassword"/><br/>

    <input type="submit" value="Register"/>
</form>

</body>
</html>

 
