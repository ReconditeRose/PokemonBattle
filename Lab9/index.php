<!DOCTYPE html>

<?php
// Open a connection to the database
// (display an error if the connection fails)
$conn = mysqli_connect('localhost', 'root', '') or die(mysqli_error());
mysqli_select_db($conn, 'rhitter') or die(mysqli_error());
?>



<html>
<head>
<title>Welcome!</title>
</head>
<body>

<h1>Welcome to RHITter!</h1>

<ul>
    <li><a href="/register.php">Register</a></li>
    <li><a href="/post.php">Make a Post</a></li>
</ul>

<h2>Live Feed</h2>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {


    $id = $_POST['postID'];
	
		$id = mysqli_real_escape_string($conn,$id);

		$result = 'DELETE FROM posts WHERE posts.id = ' . $id;
		echo "Post Deleted";
		mysqli_query($conn,"$result") or die(‘error’);

		$_SERVER['REQUEST_URI'];

	

}
?>

<?php
// Select the 30 most recent posts from our friendly posts view
$posts = mysqli_query($conn, "SELECT name,post_body,posts.id ".
                     "FROM users,posts " .
					 "where users.id = posts.user_id " .
					 "ORDER BY created_at DESC " .
                     "LIMIT 30");
					 
					 // Display each post



while ($row = mysqli_fetch_array($posts)) {
	$name = htmlspecialchars($row[0], ENT_QUOTES);
	$body = htmlspecialchars($row[1], ENT_QUOTES);
    echo '<form action="" method="post"><p><strong>' . $name . '</strong>: ' . $body . '
    <input type="submit" value="Delete Post"> ' .
	'<span id ="main">' .
	'<input type="hidden" name="postID" value=' . $row[2] . ' /> </form></span>';
}



?>


<!DOCTYPE html>
