<!DOCTYPE html>

<?php
// Open a connection to the database
// (display an error if the connection fails)
$conn = mysqli_connect('localhost', 'root', '') or die(mysqli_error());
mysqli_select_db($conn, 'rhitter') or die(mysqli_error());
?>


<html>
<head>
<title>New Post</title>
</head>
<body>

<h1>New Post</h1>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = $_POST['username'];
    $password = $_POST['password'];
    $post_body = $_POST['postbody'];
	
	$username = mysqli_real_escape_string($conn,$username);
	$password = mysqli_real_escape_string($conn,$password);
	$post_body = mysqli_real_escape_string($conn,$post_body);

    if (empty($post_body)) {
        echo '<ul><li>You must post something!</li></ul>';
    } else {
		$result = "CALL createPost('" . $username . "',
						 '" . $password . "', '" . $post_body . "')";
        $new = mysqli_query($conn, $result);
        $row = mysqli_fetch_array($new);
        $status = $row[0];

        echo '<ul><li>' . $status . '</li></ul>';
    }
}
?>



<form action="" method="post">
    <label for="username">Username</label><br/>
    <input type="text" name="username"/><br/>
    <label for="username">Password</label><br/>
    <input type="password" name="password"/><br/>
    <label for="postbody">Post</label><br/>
    <textarea name="postbody"></textarea><br/>
    <input type="submit" value="Post"/><br/>
</form>

</body>
</html>
