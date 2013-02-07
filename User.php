<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">
<HTML>
  <HEAD>
    <LINK href="style.css" rel="stylesheet" type="text/css">
  </HEAD>
  <BODY>
    <P class="special">This paragraph should have special green text.
  </BODY>
</HTML>


<?php
// Open a connection to the database
// (display an error if the connection fails)
session_start();
echo "Hey ".$_SESSION['userName'];
echo '<li><a href="/Logout.php">Logout</a></li>';

$host="whale.csse.rose-hulman.edu";
$username="333PokemonBattle";
$password="333PokemonBattle";
$db_name="pokemonBattle"; 

$conn = mssql_connect("$host", "$username", "$password")or die("cannot connect"); 
mssql_select_db("$db_name",$conn)or die("cannot select DB");
?>

<H2>Active Teams</H2>
<body background = "/images/pw_bg.png">

<?php
$person = $_SESSION['userName'];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
$result = mssql_query("Exec CreateParty @userName='$person'",$conn);
}

$posts = mssql_query("Exec SelectParties '$person'",$conn);
while ($row = mssql_fetch_array($posts)) {
	$nameID = htmlspecialchars($row[0], ENT_QUOTES);
    echo '<strong>Team ID:' . $nameID . '</strong>';	
	
	$pokemon = mssql_query("Exec SelectPokemon '$row[0]'",$conn);
	$x = 1;
	echo '<table>
	<tr><td>Status</td>
	<td>Pending</td>
	<td>Switch</td>
	</tr>';
	while ($x<=6){
		if($poke = mssql_fetch_array($pokemon))
			echo'';
		else
			$poke[0]="";
		echo '<tr>';
		switch($x){
		case 1:
			echo '<td>Opposing Pokemon</td><td rowspan="2"></td>';
			break;
		case 2:
			echo '<td><div style="width :50%" class="HealthBar"></div></td>'; 
			break;
		case 3: 
			echo '<td rowspan="2"></td><td>Your Pokemon</td>';
			break;
		case 4:
			echo '<td><div style="width :50%" class="HealthBar"></div></td>'; 
			break;
		case 6:
		case 5:
			echo '<td><form type="post"><input type="submit" style="width:100px" value ="Attack"></form></td>';
			echo '<td><form type="post"><input type="submit" style="width:100px" value ="Attack"></form></td>';
			break;
		default:
			break;
		}
		echo '
		

		<td><form type="post"><input type="submit" style="width:100px" value ='.$poke[0].'></form></td>
		</tr>';
		$x = $x+1;
	}
	echo '</table>';
	
}

echo '<H2>Idle Teams</H2>';
$posts = mssql_query("Exec SelectParties '$person'",$conn);
while ($row = mssql_fetch_array($posts)) {
	$nameID = htmlspecialchars($row[0], ENT_QUOTES);
    echo '<form method="get" action="ViewTeam.php"><p><strong>Team ID:' . $nameID . '</strong>' .
    '<input type="submit" value="Edit Team"> ' .
	'<span id ="main">' .
	'<input type="hidden" name="TeamID" value=' . $row[0] . ' /> </form></span>';
	
	
	$pokemon = mssql_query("Exec SelectPokemon '$row[0]'",$conn);
	$x = 1;
	echo '<table>
	<tr><td>Record</td>
	<td>0:0</td>
	</tr>';
	while ($poke = mssql_fetch_array($pokemon)){
		echo '<tr>
		<td>Pokemon #' . $x .': ' . $poke[0] . '</td>
		<td>Stuff</td>
		</tr>';
		$x = $x+1;
	}
	echo '</table>';
}

?>

<form method="post" actoin =""><p><input type="submit" value ="Create New Team">
