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
<H2> Battle Requests</H2>
Sent Battle Request:

Sent Battle Requests:

Oustanding Requests:
<form name= "accept request" action="" method ="POST">
<input type="submit" name="request" value="ignore">
<input type="submit" name="request" value="accept with Team:">
<select>
<?php




?>
</select>
</form>


<H2>Active Teams</H2>
<body background = "/images/pw_bg.png">

<?php
$person = $_SESSION['userName'];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
$result = mssql_query("Exec CreateParty @userName='$person'",$conn);
}

$posts = mssql_query("Exec BattleParties '$person'",$conn);
while ($row = mssql_fetch_array($posts)) {
	$PartyID = htmlspecialchars($row[0], ENT_QUOTES);
	$nameID = htmlspecialchars($row[1], ENT_QUOTES);
    echo '<strong>User Team#:' . $PartyID . ' vs User:' . $nameID . '</strong>';	
	$active = mssql_query("Exec getActivePokemon $PartyID",$conn);
	$active = mssql_fetch_array($active);

	$fallback = mssql_query("Exec SelectFallback $PartyID",$conn);
	$fallback = mssql_fetch_Array($fallback);
	
	$pokemon = mssql_query("Exec SelectPokemon '$row[0]'",$conn);
	$x = 1;
	echo '<table>
	<tr><td>Status</td>
	<td>Pending</td>
	<td>Switch</td>
	</tr>';
	
	$hp1 = ($active[2]+90)*2;
	$hp2 = ($active[5]+90)*2;
	$battle = $row[2];
	
	while ($x<=6){
		if($poke = mssql_fetch_array($pokemon)){
			echo'works';

		}else{
			$poke[0]="";
			$poke[1]="";
		}
		echo '<tr>';
		switch($x){
		case 1:
			echo '<td>'."$active[3] HP:$active[4]/$hp2". '</td><td rowspan="2"></td>';
			break;
		case 2:
			if($active[3]==""){
			echo'<td>Selecting Pokemon</td>';
			}else{
			$ratio = intval(100*$active[4]/$hp2);
			echo '<td><div style="width :'. $ratio.'%" class="HealthBar"></div></td>'; 
			echo $ratio;
			}
			break;
		case 3: 
			echo '<td rowspan="2"></td><td>'."$active[0] HP:$active[1]/$hp1". '</td>';
			break;
		case 4:
			$ratio = intval(100*$active[1]/$hp1);
			echo '<td><div style="width :'.$ratio .'%" class="HealthBar"></div></td>'; 
			echo $ratio;
			break;
		case 5:
			echo '<form method="post" action="MakeMove.php"><input type="hidden" name ="type" value="Attack">
				<input type="hidden" name ="BID" value ='. $battle.'>
				<input type="hidden" name="poke1" value='. $active[10] .'>
				<input type="hidden" name="poke2" value='. $active[11] .'>
			';
			if($active[6]==""){
				echo '<td><input type="submit" style="width:100px" name ="move" value ="Struggle"></td>';
			}else{
				echo '<td><input type="submit" style="width:100px" name ="move" value ="'. $active[6] .'"></td>';
			}
			if($active[7]==""){
				echo '<td><input type="submit" style="width:100px" name ="move" value ="Struggle"></td>';
			}else{
				echo '<td><input type="submit" style="width:100px" name ="move" value ="'. $active[7] .'"></td>';
			}
			echo '</form>';
			break;
		case 6:
			echo '<form method="post" action="MakeMove.php"><input type="hidden" name ="type" value="Attack">';
			if($active[8]==""){
				echo '<td><input type="submit" style="width:100px" name ="move" value ="Struggle"></td>';
			}else{
			echo '<td><input type="submit" style="width:100px" name ="move" value ="'. $active[8] .'"></td>';
			}
			if($active[9]==""){
				echo '<td><input type="submit" style="width:100px" name ="move" value ="Struggle"></td>';
			}else{
				echo '<td><input type="submit" style="width:100px" name ="move" value ="'. $active[9] .'"></td>';
			}
			echo '</form>';
			break;
		default:
			break;
		}
		if($poke[1]!=""){
		if(!$active[0]==""){
			echo '<td><form method="POST" action="MakeMove.php"><input type="hidden" name ="type" value="Switch">
				<input type="hidden" name ="BID" value ='. $battle.'>
				<input type="hidden" name="poke1" value='. $active[10] .'>
				<input type="hidden" name="poke2" value='. $active[11] .'>
				<input type="hidden" name="Switch" value='.$poke[1].'>
				<input type="submit" style="width:100px" value ='.$poke[0].'>
				</form></td></tr>';
				}else{
			echo '<td><form method="POST" action="MakeMove.php"><input type="hidden" name ="type" value="Switch">
				<input type="hidden" name ="BID" value ='. $battle.'>
				<input type="hidden" name="poke1" value='. $fallback[1] .'>
				<input type="hidden" name="poke2" value=0>
				<input type="hidden" name="Switch" value='.$poke[1].'>
				<input type="submit" style="width:100px" value ='.$poke[0].'>
				</form></td></tr>';
				
				
				}
		}else{
			echo '<td></td>';
		}
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
