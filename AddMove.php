<!DOCTYPE html>
<head>
   <body background = "/images/pw_bg.jpg">
<link rel="stylesheet" type="text/css" href="Style.css" media="screen" />
</head>

<?php
session_start();
$host="whale.csse.rose-hulman.edu"; // Host name 
$username="olsonmc"; // Mysql username 
$password="33upCosh"; // Mysql password 
$db_name="pokemonBattle"; // Database name 

// Connect to server and select databse.
$conn = mssql_connect("$host", "$username", "$password")or die("cannot connect"); 
mssql_select_db("$db_name",$conn)or die("cannot select DB");

echo '<li><a href="/User.php">Home</a></li>'
?>

<html>
<head>
<title>Welcome!</title>
</head>
<body>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST'){

if($_POST['Type'] == "Add"){
$pokemonID = $_GET['PokeID'];
$learnMove = $_POST['Move'];
mssql_query("exec LearnMove '$pokemonID','$learnMove'");


}else if($_POST['Type'] == 'Drop'){
$var1 = $_POST['var1'];
$var2 = $_GET['PokeID'];
mssql_query("exec dropMove $var2,$var1");


}

if ($_SERVER['REQUEST_METHOD'] == 'GET' || $_SERVER['REQUEST_METHOD']=='POST') {

/*    echo '<form method="get" action="ViewTeam.php"><p><strong>Team ID:' . $nameID . '</strong>' .
    '<input type="submit" value="Edit Team"> ' .
	'<span id ="main">' .
	'<input type="hidden" name="TeamID" value=' . $row[0] . ' /> </form></span>';
*/	
	$add = $_GET['PokeID'];
	$result = mssql_query("select pokemonName,Move1,Move2,Move3,Move4 from UserPokemon where PokemonID = $add");
	$row = mssql_fetch_array($result);
	$x=1;

	
	while($x<5){
		if($row[$x] != ""){
			echo '<form method="post" action="">
			'. $row[$x] . '
			<input type="submit" value = "Delete">
			<input type="hidden" name="var1" value ="'.$x.'">
			<input type="hidden" name ="Type" value="Drop">'."
			</form>";
		}
		$x= $x+1;
	}

	$pokemon = mssql_query('exec getMoves ' . $add);
	echo '<table><tr>
	<th>Move Name</th>
	<th>Add</th>
	<th>Type</th>
	<th>Power</th>
	<th>Accuracy</th>
	<th>PP</th>
	</tr>';
	while ($poke = mssql_fetch_array($pokemon)){
		echo "<tr>
		<td>$poke[0]</td>
		".'<td><form name = "add" method="post" action="">
		<input type="submit" value = "add">
		<input type="hidden" name="Move" value ="'.$poke[0].'">
		<input type="hidden" name ="Type" value="Add">'."
		</form></td>
		<td>$poke[1]</td>
		<td>$poke[2]</td>
		<td>$poke[3]</td>
		<td>$poke[4]</td>
		</tr>";
	}
	

}
?> 

