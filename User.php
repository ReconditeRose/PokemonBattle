<!DOCTYPE html>

<?php
// Open a connection to the database
// (display an error if the connection fails)
session_start();
echo "Hey ".$_SESSION['userName'];
echo '<li><a href="/Logout.php">Logout</a></li>';

$host="whale.csse.rose-hulman.edu"; // Host name 
$username="olsonmc"; // Mysql username 
$password="33upCosh"; // Mysql password 
$db_name="pokemonBattle"; 

// Connect to server and select databse.
$conn = mssql_connect("$host", "$username", "$password")or die("cannot connect"); 
mssql_select_db("$db_name",$conn)or die("cannot select DB");
?>

<H1>Teams</H2>
<body background = "/images/pw_bg.png">

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
$result = mssql_query("Exec CreateParty @userName='". $_SESSION['userName'] ."'",$conn);
}

$posts = mssql_query("SELECT PartyID FROM UserParty where userName = '" . $_SESSION['userName'] . "'",$conn);

while ($row = mssql_fetch_array($posts)) {
	$nameID = htmlspecialchars($row[0], ENT_QUOTES);
    echo '<form method="get" action="ViewTeam.php"><p><strong>Team ID:' . $nameID . '</strong>' .
    '<input type="submit" value="Edit Team"> ' .
	'<span id ="main">' .
	'<input type="hidden" name="TeamID" value=' . $row[0] . ' /> </form></span>';
	
	$pokemon = mssql_query('SELECT PokemonName,PokemonID from UserPokemon where PartyNo ='. $row[0],$conn);
	$x = 1;
	while ($poke = mssql_fetch_array($pokemon)){
		echo '<li>Pokemon #' . $x .': ' . $poke[0] . ', Pokemon ID = ' . $poke[1] . '</li>';
		$x = $x+1;
	}
	
}
?>

<form method="post" actoin =""><p><input type="submit" value ="Create New Team">
