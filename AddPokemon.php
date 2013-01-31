<!DOCTYPE html>
<body background = "/images/pw_bg.png">
<head>
<link rel="stylesheet" type="text/css" href="Style.css" media="screen" />
</head>

<?php
session_start();
echo '<li><a href="/User.php">Home</a></li>';
$host="whale.csse.rose-hulman.edu"; // Host name 
$username="olsonmc"; // Mysql username 
$password="33upCosh"; // Mysql password 
$db_name="pokemonBattle"; // Database name 

// Connect to server and select databse.
$conn = mssql_connect("$host", "$username", "$password")or die("cannot connect"); 
mssql_select_db("$db_name",$conn)or die("cannot select DB");

echo 'Current Team';
$pokemon = mssql_query('SELECT PokemonName,PokemonID from UserPokemon where PartyNo =' . $_GET['TeamID'],$conn);
$x = 1;
while ($poke = mssql_fetch_array($pokemon)){
	echo '<li>Pokemon #' . $x .': ' . $poke[0] . ', Pokemon ID = ' . $poke[1] . '</li>';
	$x = $x+1;
	}
echo '<p>';


echo '<form name="add" method="post" action="SubmitAdd.php">
<td width="78">PokemonName:</td>
<input name="poke" type="text">
<input type = "hidden" name ="PartyID" value = "' . $_GET['TeamID']. '">
<td><input type="submit" name="Submit" value="Add Pokemon"></td>
<p>
</form>';



$posts = mssql_query("Select Name from Pokemon",$conn);

while($poke = mssql_fetch_array($posts)){
	echo '<form name ="add" method = "post" action="SubmitAdd.php">' . $poke[0] . '<input type="submit" value ="Add">'.
	'<input type="hidden" name ="poke" value ="' . $poke[0] . '">' .
	'<input type = "hidden" name ="PartyID" value = "' . $_GET['TeamID']. '"></form>';
}

?>


<html>
<head>
<title>Welcome!</title>
</head>
<body>


<!DOCTYPE html>
