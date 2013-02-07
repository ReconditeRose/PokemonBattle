<!DOCTYPE html>
<body background = "/images/pw_bg.png">
<head>
<link rel="stylesheet" type="text/css" href="Style.css" media="screen" />
</head>

<?php
session_start();
echo '<li><a href="/User.php">Home</a></li>';
$host="whale.csse.rose-hulman.edu";
$username="333PokemonBattle";
$password="333PokemonBattle";
$db_name="pokemonBattle"; 

$conn = mssql_connect("$host", "$username", "$password")or die("cannot connect"); 
mssql_select_db("$db_name",$conn)or die("cannot select DB");

echo 'Current Team';
$pokemon = mssql_query('exec SelectPokemon ' . $_GET['TeamID'],$conn);
$x = 1;
while ($poke = mssql_fetch_array($pokemon)){
	echo '<li>Pokemon #' . $x .': ' . $poke[0] . ', Pokemon ID = ' . $poke[1] . '</li>';
	$x = $x+1;
	}
echo '<p>';


echo '<form name="add" method="post" action="SubmitAdd.php">
<td width="78">PokemonName:</td>
<input name="poke[0]" type="text">
<input type = "hidden" name ="PartyID" value = "' . $_GET['TeamID']. '">
<td><input type="submit" name="Submit" value="Add Pokemon"></td>
<p>
</form>';



$posts = mssql_query("exec getPokeAddData",$conn);
echo '<form name = "add Pokemon" action="SubmitAdd.php" method="post">
	<input type="submit" value ="Add">
	<input type = "hidden" name ="PartyID" value = "' . $_GET['TeamID']. '">
	<table><tr>
	<td>Pokemon Name</td>
	<td>Add</td>
	<td>Type 1</td>
	<td>Type 2</td>
	<td>Base HP</td>
	<td>Base Attack</td>
	<td>Base Defense</td>
	<td>Base Speed</td>
	<td>Base Sp. Attack</td>
	<td>Base Sp. Defense</td>
	</tr>
	';

while($poke = mssql_fetch_array($posts)){
	echo '<tr>
	<td>'. $poke[0] . '</td>
	<td><input type="checkbox" name ="poke[]" value='.$poke[0] .'></td>';
	$x=1;
	while($x<9){
	echo "<td>$poke[$x]</td>";
	$x=$x+1;
	}
	echo '</tr>';
}
echo '</form></table>';
?>


<html>
<head>
<title>Welcome!</title>
</head>
<body>


<!DOCTYPE html>
