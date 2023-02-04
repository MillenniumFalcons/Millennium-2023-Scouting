<?php
include("databaseName.php");
//Input- runQuery, establishes connection with server, runs query, closes connection.
//Output- queryOutput, data to/from the tables in phpMyAdmin databases.

#function getThreePointNumber()
#{
#	$command = escapeshellcmd('/Documents/FRC/Strategy/frcstrat/oprcalcufinal.py');
#	$output = shell_exec($command);
#	echo $output;
#}

function runQuery($queryString)
{
	global $servername;
	global $username;
	global $password;
	global $dbname;
	global $pitScoutTable;
	global $matchScoutTable;
	//Establish Connection
	try {
		$conn = connectToDB();
	} catch (Exception $e) {
		error_log("CREATING DB");
		createDB();
		$conn = connectToDB();
	}
	//new mysqli($servername, $username, $password, $dbname);
	//error_log($queryString);
	try {
		$statement = $conn->prepare($queryString);
	} catch (PDOException $e) {
		error_log($e->getMessage());
		error_log($e->getCode());
		if ($e->getCode() == "42S02") {
			error_log("CREATING TABLES");
			createTables();
		}
		$statement = $conn->prepare($queryString);
	}
	if (!$statement->execute()) {
		die("Failed!");
	}
	try {
		//error_log("".$statement->fetchAll());
		return $statement->fetchAll();
	} catch (Exception $e) {
		return;
	}
}
function createDB()
{
	global $dbname;
	$connection = connectToServer();
	$statement = $connection->prepare('CREATE DATABASE IF NOT EXISTS ' . $dbname);
	if (!$statement->execute()) {
		throw new Exception("constructDatabase Error: CREATE DATABASE query failed.");
	}
}
function connectToServer()
{
	global $servername;
	global $username;
	global $password;
	global $dbname;
	global $charset;
	$dsn = "mysql:host=" . $servername . ";charset=" . $charset;
	$opt = [
		PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		PDO::ATTR_EMULATE_PREPARES   => false
	];
	return (new PDO($dsn, $username, $password, $opt));
}
function connectToDB()
{
	global $servername;
	global $username;
	global $password;
	global $dbname;
	global $charset;
	$dsn = "mysql:host=" . $servername . ";dbname=" . $dbname . ";charset=" . $charset;
	$opt = [
		PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		PDO::ATTR_EMULATE_PREPARES   => false
	];
	return (new PDO($dsn, $username, $password, $opt));
}
function createTables()
{
	global $servername;
	global $username;
	global $password;
	global $dbname;
	global $pitScoutTable;
	global $matchScoutTable;
	$conn = connectToDB();
	$query = "CREATE TABLE " . $dbname . "." . $pitScoutTable . " (
			teamNumber VARCHAR(50) NOT NULL PRIMARY KEY,
			teamName VARCHAR(60) NOT NULL,
			numBatteries VARCHAR(20) NOT NULL,
			chargedBatteries VARCHAR(20) NOT NULL,
			codeLanguage VARCHAR(10) NOT NULL,
			pitComments LONGTEXT NOT NULL,
			climbHelp LONGTEXT NOT NULL,
			LoctiteFalcons VARCHAR(10) NOT NULL
		)";
	$statemennt = $conn->prepare($query);
	if (!$statemennt->execute()) {
		throw new Exception("constructDatabase Error: CREATE TABLE pitScoutTable query failed.");
	}
	$query = "CREATE TABLE " . $dbname . "." . $matchScoutTable . " (
			user VARCHAR(20) NOT NULL,
			ID VARCHAR(8) NOT NULL PRIMARY KEY,
			matchNum INT(11) NOT NULL,
			teamNum INT(11) NOT NULL,
			allianceColor TEXT NOT NULL,
			crossLineA INT(11) NOT NULL,
			aCubeL INT(11) NOT NULL,
			aCubeM INT(11) NOT NULL,
			aCubeH INT(11) NOT NULL,
			aConeL INT(11) NOT NULL,
			aConeM INT(11) NOT NULL,
			aConeH INT(11) NOT NULL,
			aDocked INT(11) NOT NULL,
			aEngaged INT(11) NOT NULL,
			tCubeL INT(11) NOT NULL,
			tCubeM INT(11) NOT NULL,
			tCubeH INT(11) NOT NULL,
			tConeL INT(11) NOT NULL,
			tConeM INT(11) NOT NULL,
			tConeH INT(11) NOT NULL,
			docked INT(11) NOT NULL,
			engaged INT(11) NOT NULL,
			parked INT(11) NOT NULL,
			issues LONGTEXT NOT NULL,
			defenseBot INT(11) NOT NULL,
			matchComments LONGTEXT NOT NULL
		)";
	$statement = $conn->prepare($query);
	if (!$statement->execute()) {
		throw new Exception("constructDatabase Error: CREATE TABLE matchScoutTable query failed.");
	}
}

function getTeamList()
{
	global $matchScoutTable;
	$queryString = "SELECT `teamNum` FROM `" . $matchScoutTable . "`";
	$result = runQuery($queryString);
	$teams = array();
	foreach ($result as $row_key => $row) {
		if (!in_array($row["teamNum"], $teams)) {
			array_push($teams, $row["teamNum"]);
		}
	}
	return ($teams);
}

function pitScoutInput($teamNumber, $teamName, $numBatteries, $chargedBatteries, $codeLanguage, $pitComments, $climbHelp, $LoctiteFalcons)
{
	global $pitScoutTable;
	$queryString = "REPLACE INTO `" . $pitScoutTable . "` (`teamNumber`, `teamName`, `numBatteries`,`chargedBatteries`, `codeLanguage`, `pitComments`, `climbHelp`, `LoctiteFalcons`)";
	$queryString = $queryString . ' VALUES ("' . $teamNumber . '", "' . $teamName . '", "' . $numBatteries . '", "' . $chargedBatteries . '", "' . $codeLanguage . '", "' . $pitComments . '", "' . $climbHelp . '", "' . $LoctiteFalcons . '")';
	$queryOutput = runQuery($queryString);
}

function matchInput(
	$user,
	$ID,
	$matchNum,
	$teamNum,
	$allianceColor,
	$autoPath,
	$crossLineA,
	$aCubeL,
	$aCubeM,
	$aCubeH,
	$aConeL,
	$aConeM,
	$aConeH,
	$aDocked,
	$aEngaged,
	$tCubeL,
	$tCubeM,
	$tCubeH,
	$tConeL,
	$tConeM,
	$tConeH,
	$docked,
	$engaged,
	$parked,
	$issues,
	$defenseBot,
	$matchComments
) {

	global $servername;
	global $username;
	global $password;
	global $dbname;
	global $matchScoutTable;
	$queryString = "REPLACE INTO `" . $matchScoutTable . '`(  `user`,
															 `ID`,
															 `matchNum`,
															 `teamNum`,
															 `allianceColor`,
															 `autoPath`,
															 `crossLineA`,
															 `aCubeL`,
															 `aCubeM`,
															 `aCubeH`,
															 `aConeL`,
															 `aConeM`,
															 `aConeH`,
															 `aDocked`,
															 `aEngaged`,
															 `tCubeL`,
															 `tCubeM`,
															 `tCubeH`,
															 `tConeL`,
															 `tConeM`,
															 `tConeH`,
															 `docked`,
															 `engaged`,
															 `parked`,
															 `issues`,
															 `defenseBot`,
															 `matchComments`)
													VALUES ( "' . $user . '",
															 "' . $ID . '",
															 "' . $matchNum . '",
															 "' . $teamNum . '",
															 "' . $allianceColor . '",
															 "' . $autoPath . '",
															 "' . $crossLineA . '",
															 "' . $aCubeL . '",
															 "' . $aCubeM . '",
															 "' . $aCubeH . '",
															 "' . $aConeL . '",
															 "' . $aConeM . '",
															 "' . $aConeH . '",
															 "' . $aDocked . '",
															 "' . $aEngaged . '",
															 "' . $tCubeL . '",
															 "' . $tCubeM . '",
															 "' . $tCubeH . '",
															 "' . $tConeL . '",
															 "' . $tConeM . '",
															 "' . $tConeH . '",
															 "' . $docked . '",
															 "' . $engaged . '",
															 "' . $parked . '",
															 "' . $issues . '",
															 "' . $defenseBot . '",
															 "' . $matchComments . '")';
	$queryOutput = runQuery($queryString);
}

function getTeamData($teamNumber)
{
	global $servername;
	global $username;
	global $password;
	global $dbname;
	global $pitScoutTable;
	global $matchScoutTable;
	$qs1 = "SELECT * FROM `" . $pitScoutTable . "` WHERE teamNumber = " . $teamNumber . "";
	$qs2 = "SELECT * FROM `" . $matchScoutTable . "`  WHERE teamNum = " . $teamNumber . "";
	$result = runQuery($qs1);
	$result2 = runQuery($qs2);
	$teamData = array();
	$pitExists = False;
	if ($result != FALSE) {
		// output data of each row
		foreach ($result as $row_key => $row) {
			array_push($teamData, $row["teamNumber"], $row["teamName"], $row["numBatteries"], $row["chargedBatteries"], $row["codeLanguage"], $row["pitComments"], $row["climbHelp"], $row["LoctiteFalcons"], array());
			$pitExists = True;
		}
	}
	if (!$pitExists) {
		array_push($teamData, $teamNumber, 'NA', 'NA', 'NA', 'NA', 'NA', 'NA', 'NA', array());
	}
	if ($result2 != FALSE) {
		foreach ($result2 as $row_key => $row) {
			array_push($teamData[8], array(
				$row["user"], $row["ID"], $row["matchNum"],
				$row["teamNum"], $row["allianceColor"], $row["autoPath"],
				$row["crossLineA"], $row["aCubeL"], $row["aCubeM"],
				$row["aCubeH"], $row["aConeL"], $row["aConeM"],
				$row["aConeH"],  $row["aDocked"], $row["aEngaged"],
				$row["tCubeL"], $row["tCubeM"], $row["tCubeH"],
				$row["tConeL"], $row["tConeM"], $row["tConeH"],
				$row["docked"], $row["engaged"], $row["parked"],
				$row["issues"], $row["defenseBot"], $row["matchComments"]
			));
		}
	}
	return ($teamData);
}

function getAutoUpperGoal($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$matchN = matchNum($teamNumber);
	$cubeGraphT = array();
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$cubeGraphT[$teamData[8][$i][2]] = $teamData[8][$i][7];
	}
	$out = array();
	for ($i = 0; $i != sizeof($matchN); $i++) {
		array_push($out, $cubeGraphT[$matchN[$i]]);
	}
	return ($out);
}

function getAutoLowerGoal($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$matchN = matchNum($teamNumber);
	$cubeGraphT = array();
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$cubeGraphT[$teamData[8][$i][2]] = $teamData[8][$i][9];
	}
	$out = array();
	for ($i = 0; $i != sizeof($matchN); $i++) {
		array_push($out, $cubeGraphT[$matchN[$i]]);
	}
	return ($out);
}

function getAutoUpperGoalMiss($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$matchN = matchNum($teamNumber);
	$cubeGraphT = array();
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$cubeGraphT[$teamData[8][$i][2]] = $teamData[8][$i][8];
	}
	$out = array();
	for ($i = 0; $i != sizeof($matchN); $i++) {
		array_push($out, $cubeGraphT[$matchN[$i]]);
	}
	return ($out);
}

function getAutoLowerGoalMiss($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$matchN = matchNum($teamNumber);
	$cubeGraphT = array();
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$cubeGraphT[$teamData[8][$i][2]] = $teamData[8][$i][10];
	}
	$out = array();
	for ($i = 0; $i != sizeof($matchN); $i++) {
		array_push($out, $cubeGraphT[$matchN[$i]]);
	}
	return ($out);
}

function getTeleopUpperGoal($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$matchN = matchNum($teamNumber);
	$cubeGraphT = array();
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$cubeGraphT[$teamData[8][$i][2]] = $teamData[8][$i][11];
	}
	$out = array();
	for ($i = 0; $i != sizeof($matchN); $i++) {
		array_push($out, $cubeGraphT[$matchN[$i]]);
	}
	return ($out);
}

function getTeleopLowerGoal($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$matchN = matchNum($teamNumber);
	$cubeGraphT = array();
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$cubeGraphT[$teamData[8][$i][2]] = $teamData[8][$i][13];
	}
	$out = array();
	for ($i = 0; $i != sizeof($matchN); $i++) {
		array_push($out, $cubeGraphT[$matchN[$i]]);
	}
	return ($out);
}

function getTeleopUpperGoalMiss($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$matchN = matchNum($teamNumber);
	$cubeGraphT = array();
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$cubeGraphT[$teamData[8][$i][2]] = $teamData[8][$i][12];
	}
	$out = array();
	for ($i = 0; $i != sizeof($matchN); $i++) {
		array_push($out, $cubeGraphT[$matchN[$i]]);
	}
	return ($out);
}

function getTeleopLowerGoalMiss($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$matchN = matchNum($teamNumber);
	$cubeGraphT = array();
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$cubeGraphT[$teamData[8][$i][2]] = $teamData[8][$i][14];
	}
	$out = array();
	for ($i = 0; $i != sizeof($matchN); $i++) {
		array_push($out, $cubeGraphT[$matchN[$i]]);
	}
	return ($out);
}

function getClimb($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$matchN = matchNum($teamNumber);
	$cubeGraphT = array();
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$cubeGraphT[$teamData[8][$i][2]] = $teamData[8][$i][17] + $teamData[8][$i][18] + $teamData[8][$i][19];
	}
	$out = array();
	for ($i = 0; $i != sizeof($matchN); $i++) {
		array_push($out, $cubeGraphT[$matchN[$i]]);
	}
	return ($out);
}







function getUpperShotPercentage($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$matchN = matchNum($teamNumber);
	$cubeGraphT = array();
	$x = 0;
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$cubeGraphT[$teamData[8][$i][2]] = (100 * ((($teamData[8][$i][11]) + ($teamData[8][$i][7])) / (($teamData[8][$i][12]) + (($teamData[8][$i][11]) + ($teamData[8][$i][7]) + ($teamData[8][$i][8])))));
	}
	$out = array();
	for ($i = 0; $i != sizeof($matchN); $i++) {
		array_push($out, $cubeGraphT[$matchN[$i]]);
	}
	return ($out);
}

function getLowerShotPercentage($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$matchN = matchNum($teamNumber);
	$cubeGraphT = array();
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$cubeGraphT[$teamData[8][$i][2]] = (100 * (($teamData[8][$i][9]) + ($teamData[8][$i][13])) / (($teamData[8][$i][9]) + ($teamData[8][$i][10]) + ($teamData[8][$i][14]) + ($teamData[8][$i][13])));
	}
	$out = array();
	for ($i = 0; $i != sizeof($matchN); $i++) {
		array_push($out, $cubeGraphT[$matchN[$i]]);
	}
	return ($out);
}


function getTeleopLowerShotPercentage($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$matchN = matchNum($teamNumber);
	$cubeGraphT = array();
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$cubeGraphT[$teamData[8][$i][2]] = ($teamData[8][$i][13]) / ($teamData[8][$i][14]);
	}
	$out = array();
	for ($i = 0; $i != sizeof($matchN); $i++) {
		array_push($out, $cubeGraphT[$matchN[$i]]);
	}
	return ($out);
}


function getAutoUpperShotPercentage($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$matchN = matchNum($teamNumber);
	$cubeGraphT = array();
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$cubeGraphT[$teamData[8][$i][2]] = ($teamData[8][$i][7]) / ($teamData[8][$i][8]);
	}
	$out = array();
	for ($i = 0; $i != sizeof($matchN); $i++) {
		array_push($out, $cubeGraphT[$matchN[$i]]);
	}
	return ($out);
}

function getAutoLowerShotPercentage($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$matchN = matchNum($teamNumber);
	$cubeGraphT = array();
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$cubeGraphT[$teamData[8][$i][2]] = ($teamData[8][$i][9]) / ($teamData[8][$i][10]);
	}
	$out = array();
	for ($i = 0; $i != sizeof($matchN); $i++) {
		array_push($out, $cubeGraphT[$matchN[$i]]);
	}
	return ($out);
}


function getAvgUpperShotPercentage($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$upperGoalCount = 0;
	$upperGoalMissCount = 0;

	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$upperGoalCount = $upperGoalCount + $teamData[8][$i][11] + $teamData[8][$i][7];
	}
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$upperGoalMissCount = $upperGoalMissCount + $teamData[8][$i][8] + $teamData[8][$i][12];
	}
	if (($upperGoalCount + $upperGoalMissCount) == 0) {
		return (0);
	}
	return (round((100 * ($upperGoalCount / ($upperGoalCount + $upperGoalMissCount))), 3));
}

function getAvgLowerShotPercentage($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$lowerGoalCount = 0;
	$lowerGoalMissCount = 0;

	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$lowerGoalCount = $lowerGoalCount + $teamData[8][$i][9] + $teamData[8][$i][13];
	}
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$lowerGoalMissCount = $lowerGoalMissCount + $teamData[8][$i][10] + $teamData[8][$i][14];
	}
	if (($lowerGoalCount + $lowerGoalMissCount) == 0) {
		return (0);
	}

	return (round((100 * ($lowerGoalCount / ($lowerGoalCount + $lowerGoalMissCount))), 3));
}



function getAvgUpperGoalT($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$upperGoalCountT = 0;
	$matchCount  = 0;

	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$upperGoalCountT = $upperGoalCountT + $teamData[8][$i][11];
		$matchCount++;
	}
	return (round(($upperGoalCountT / $matchCount), 3));
}

function getAvgLowerGoalT($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$lowerGoalCountT = 0;
	$matchCount  = 0;
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$lowerGoalCountT = $lowerGoalCountT + $teamData[8][$i][13];
		$matchCount++;
	}
	return ($lowerGoalCountT / $matchCount);
}
function getAvgLowerGoalMissT($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$lowerGoalMissCountT = 0;
	$matchCount  = 0;
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$lowerGoalMissCountT = $lowerGoalMissCountT + $teamData[8][$i][14];
		$matchCount++;
	}
	return ($lowerGoalMissCountT / $matchCount);
}
function getAvgUpperGoalMissT($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$upperGoalMissCountT = 0;
	$matchCount  = 0;

	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$upperGoalMissCountT = $upperGoalMissCountT + $teamData[8][$i][12];
		$matchCount++;
	}
	return (round(($upperGoalMissCountT / $matchCount), 3));
}



//Auto Upper and Lower statistics



function getAvgUpperGoal($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$upperGoalCount = 0;
	$matchCount  = 0;
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$upperGoalCount = $upperGoalCount + $teamData[8][$i][7];
		$matchCount++;
	}
	return (round(($upperGoalCount / $matchCount), 3));
}

function getAvgLowerGoal($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$lowerGoalCount = 0;
	$matchCount  = 0;
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$lowerGoalCount = $lowerGoalCount + $teamData[8][$i][9];
		$matchCount++;
	}
	return ($lowerGoalCount / $matchCount);
}



//Teleop


function getMaxUpperGoalT($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$maxUpperGoalT = 0;
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		if ($maxUpperGoalT < $teamData[8][$i][11]) {
			$maxUpperGoalT = $teamData[8][$i][11];
		}
	}
	return ($maxUpperGoalT);
}

function getMaxLowerGoalT($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$maxLowerGoalT = 0;
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		if ($maxLowerGoalT < $teamData[8][$i][13]) {
			$maxLowerGoalT = $teamData[8][$i][13];
		}
	}
	return ($maxLowerGoalT);
}

//Auto


function getMaxUpperGoal($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$maxUpperGoal = 0;
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		if ($maxUpperGoal < $teamData[8][$i][7]) {
			$maxUpperGoal = $teamData[8][$i][7];
		}
	}
	return ($maxUpperGoal);
}

function getMaxLowerGoal($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$maxLowerGoal = 0;
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		if ($maxLowerGoal < $teamData[8][$i][9]) {
			$maxLowerGoal = $teamData[8][$i][9];
		}
	}
	return ($maxLowerGoal);
}

function getAvgClimb($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$climbSum = 0;
	$matchCount = 0;

	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$climbSum += $teamData[8][$i][20];
		$climbSum += $teamData[8][$i][21];
		$matchCount++;
	}

	return ($climbSum / $matchCount);
}

function getAllMatchData()
{
	global $matchScoutTable;
	$qs1 = "SELECT * FROM `" . $matchScoutTable . "`";
	return runQuery($qs1);
}

function getTotalClimb($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$climbCount = 0;
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$climbCount = $climbCount + $teamData[8][$i][17];
		$climbCount = $climbCount + $teamData[8][$i][18];
		$climbCount = $climbCount + $teamData[8][$i][19];
	}
	return ($climbCount);
}

function getTotalUpperGoal($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$upperGoal = 0;
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$upperGoal = $upperGoal + $teamData[8][$i][7];
		$upperGoal = $upperGoal + $teamData[8][$i][11];
	}
	return ($upperGoal);
}


function getTotalLowerGoal($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$matchN = matchNum($teamNumber);
	$cubeGraphT = array();
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$cubeGraphT[$teamData[8][$i][2]] = $teamData[8][$i][9] + $teamData[8][$i][13];
	}
	$out = array();
	for ($i = 0; $i != sizeof($matchN); $i++) {
		array_push($out, $cubeGraphT[$matchN[$i]]);
	}
	return ($out);
}


function getTotalSingleClimb($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$climbCount = 0;
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$climbCount = $climbCount + $teamData[8][$i][17];
	}
	return ($climbCount);
}

function getTotalDoubleClimb($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$climbCount = 0;
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$climbCount = $climbCount + $teamData[8][$i][18];
	}
	return ($climbCount);
}

function getTotalTripleClimb($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$climbCount = 0;
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$climbCount = $climbCount + $teamData[8][$i][19];
	}
	return ($climbCount);
}

function getTotalClimbSide($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$climbCount = 0;
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$climbCount = $climbCount + $teamData[8][$i][21];
	}
	return ($climbCount);
}

function getTotalClimbCenter($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$climbCount = 0;
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$climbCount = $climbCount + $teamData[8][$i][20];
	}
	return ($climbCount);
}

function matchNum($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$matchNum = array();
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		array_push($matchNum, $teamData[8][$i][2]);
	}
	sort($matchNum);
	return ($matchNum);
}

function defenseComments($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$defenseComments = array();
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		array_push($defenseComments, $teamData[8][$i][24]);
	}
	return ($defenseComments);
}

function matchComments($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$matchComments = array();
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		array_push($matchComments, $teamData[8][$i][25]);
	}
	return ($matchComments);
}


function getScore($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$matchN = matchNum($teamNumber);
	$cubeGraphT = array();
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$cubeGraphT[$teamData[8][$i][2]] = ((4 * ($teamData[8][$i][7])) + (2 * ($teamData[8][$i][9])) + (2 * ($teamData[8][$i][11])) + ($teamData[8][$i][13]) + (25 * ($teamData[8][$i][20])) + (25 * ($teamData[8][$i][21])) + (20 * ($teamData[8][$i][15])) + (10 * ($teamData[8][$i][16])) + (5 * ($teamData[8][$i][6])));
	}
	$out = array();
	for ($i = 0; $i != sizeof($matchN); $i++) {
		array_push($out, $cubeGraphT[$matchN[$i]]);
	}
	return ($out);
}







function getPickList($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$pointCal = 0;
	$matchCount = 0;

	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$pointCal = ($pointCal + (2 * ($teamData[8][$i][21])));
		$pointCal = ($pointCal + (2 * ($teamData[8][$i][20])));
		$pointCal = ($pointCal + (2 * ($teamData[8][$i][7])));
		$pointCal = ($pointCal + (1 * ($teamData[8][$i][9])));
		$pointCal = ($pointCal + (1 * ($teamData[8][$i][11])));
		$pointCal = ($pointCal + (0.5 * ($teamData[8][$i][13])));
		$pointCal = ($pointCal + (2 * ($teamData[8][$i][15])));
		$pointCal = ($pointCal + (1 * ($teamData[8][$i][16])));
		$pointCal = ($pointCal - (0.5 * ($teamData[8][$i][14])));
		$pointCal = ($pointCal - (1 * ($teamData[8][$i][12])));
		$pointCal = ($pointCal - (0.5 * ($teamData[8][$i][10])));
		$pointCal = ($pointCal - (1 * ($teamData[8][$i][8])));
		$matchCount++;
	}
	return (round(($pointCal / $matchCount), 3));
}


function getAvgPenalties($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$penalCount = 0;
	$matchCount  = 0;
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$penalCount = $penalCount + $teamData[8][$i][26];
		$matchCount++;
	}
	return ($penalCount / $matchCount);
}

function getTotalControlNumber($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$numberCount = 0;
	$matchCount  = 0;
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$numberCount = $numberCount + $teamData[8][$i][16];
		$matchCount++;
	}
	return ($numberCount);
}

function getTotalControlPosition($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$positionCount = 0;
	$matchCount  = 0;
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$positionCount = $positionCount + $teamData[8][$i][15];
		$matchCount++;
	}
	return ($positionCount);
}

function getAvgCycleCount($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$cycleCount = 0;
	$matchCount  = 0;
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$cycleCount = $cycleCount + $teamData[8][$i][27];
		$matchCount++;
	}
	return ($cycleCount / $matchCount);
}

function getAvgscore($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$matchCount  = 0;
	$Score = 0;
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$Score = $Score + ((4 * ($teamData[8][$i][7])) + (2 * ($teamData[8][$i][9])) + (2 * ($teamData[8][$i][11])) + ($teamData[8][$i][13]) + (25 * ($teamData[8][$i][20])) + (25 * ($teamData[8][$i][21])) + (20 * ($teamData[8][$i][15])) + (10 * ($teamData[8][$i][16])) + (5 * ($teamData[8][$i][6])));
		$matchCount++;
	}
	return ($Score / $matchCount);
}

function getTotalDefense($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$defenseCount = 0;
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$defenseCount = $defenseCount + $teamData[8][$i][23];
	}
	return ($defenseCount);
}
