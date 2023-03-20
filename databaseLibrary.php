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
	echo $queryString;
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
				$row["teamNum"], $row["allianceColor"],
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

//TEAM RANKING STATISTICS

// ------------------------- AUTO ------------------------------
function getAvgGamePieceCount($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$gamePieceCount = 0;
	$matchCount  = 0;

	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$gamePieceCount = $gamePieceCount + $teamData[8][$i][6] + $teamData[8][$i][7] + $teamData[8][$i][8] + $teamData[8][$i][9] + $teamData[8][$i][10] + $teamData[8][$i][11] + $teamData[8][$i][14] + $teamData[8][$i][15] + $teamData[8][$i][16] + $teamData[8][$i][17] + $teamData[8][$i][18] + $teamData[8][$i][19];
		$matchCount++;
	}
	return (round(($gamePieceCount / $matchCount), 3));
}

function getMaxGamePieces($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$maxGamePieces = 0;
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		if ($maxGamePieces < $teamData[8][$i][6] + $teamData[8][$i][7] + $teamData[8][$i][8] + $teamData[8][$i][9] + $teamData[8][$i][10] + $teamData[8][$i][11] + $teamData[8][$i][14] + $teamData[8][$i][15] + $teamData[8][$i][16] + $teamData[8][$i][17] + $teamData[8][$i][18] + $teamData[8][$i][19]) {
			$maxGamePieces = $teamData[8][$i][6] + $teamData[8][$i][7] + $teamData[8][$i][8] + $teamData[8][$i][9] + $teamData[8][$i][10] + $teamData[8][$i][11] + $teamData[8][$i][14] + $teamData[8][$i][15] + $teamData[8][$i][16] + $teamData[8][$i][17] + $teamData[8][$i][18] + $teamData[8][$i][19];
		}
	}
	return ($maxGamePieces);
}

function getAvgAutoConeHigh($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$avgAutoConeHigh = 0;
	$matchCount  = 0;
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$avgAutoConeHigh = $avgAutoConeHigh + $teamData[8][$i][11];
		$matchCount++;
	}
	return ($avgAutoConeHigh / $matchCount);
}

function getAvgAutoConeMid($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$avgAutoConeMid = 0;
	$matchCount  = 0;
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$avgAutoConeMid = $avgAutoConeMid + $teamData[8][$i][10];
		$matchCount++;
	}
	return ($avgAutoConeMid / $matchCount);
}

function getAvgAutoConeLow($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$avgAutoConeLow = 0;
	$matchCount  = 0;
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$avgAutoConeLow = $avgAutoConeLow + $teamData[8][$i][9];
		$matchCount++;
	}
	return ($avgAutoConeLow / $matchCount);
}

function getAvgAutoCubeHigh($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$avgAutoCubeHigh = 0;
	$matchCount  = 0;
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$avgAutoCubeHigh = $avgAutoCubeHigh + $teamData[8][$i][8];
		$matchCount++;
	}
	return ($avgAutoCubeHigh / $matchCount);
}

function getAvgAutoCubeMid($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$avgAutoCubeMid = 0;
	$matchCount  = 0;
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$avgAutoCubeMid = $avgAutoCubeMid + $teamData[8][$i][7];
		$matchCount++;
	}
	return ($avgAutoCubeMid / $matchCount);
}

function getAvgAutoCubeLow($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$avgAutoCubeLow = 0;
	$matchCount  = 0;
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$avgAutoCubeLow = $avgAutoCubeLow + $teamData[8][$i][6];
		$matchCount++;
	}
	return ($avgAutoCubeLow / $matchCount);
}

function getAvgAutoGamePieceCount($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$gamePieceCount = 0;
	$matchCount  = 0;

	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$gamePieceCount = $gamePieceCount + $teamData[8][$i][6] + $teamData[8][$i][7] + $teamData[8][$i][8] + $teamData[8][$i][9] + $teamData[8][$i][10] + $teamData[8][$i][11];
		$matchCount++;
	}
	return (round(($gamePieceCount / $matchCount), 3));
}

function getMaxAutoGamePieces($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$maxGamePieces = 0;
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		if ($maxGamePieces < $teamData[8][$i][6] + $teamData[8][$i][7] + $teamData[8][$i][8] + $teamData[8][$i][9] + $teamData[8][$i][10] + $teamData[8][$i][11]) {
			$maxGamePieces = $teamData[8][$i][6] + $teamData[8][$i][7] + $teamData[8][$i][8] + $teamData[8][$i][9] + $teamData[8][$i][10] + $teamData[8][$i][11];
		}
	}
	return ($maxGamePieces);
}

function getAvgAutoDock($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$dock = 0;
	$matchCount  = 0;

	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$dock = $dock + $teamData[8][$i][12];
		$matchCount++;
	}
	return (round(($dock / $matchCount), 3));
}

function getAvgAutoEngage($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$engage = 0;
	$matchCount  = 0;

	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$engage = $engage + $teamData[8][$i][13];
		$matchCount++;
	}
	return (round(($engage / $matchCount), 3));
}

//------------------------------ TELEOP -------------------------------

function getAvgTeleopConeHigh($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$avgTeleopConeHigh = 0;
	$matchCount  = 0;
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$avgTeleopConeHigh = $avgTeleopConeHigh + $teamData[8][$i][19];
		$matchCount++;
	}
	return ($avgTeleopConeHigh / $matchCount);
}

function getAvgTeleopConeMid($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$avgTeleopConeMid = 0;
	$matchCount  = 0;
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$avgTeleopConeMid = $avgTeleopConeMid + $teamData[8][$i][18];
		$matchCount++;
	}
	return ($avgTeleopConeMid / $matchCount);
}

function getAvgTeleopConeLow($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$avgTeleopConeLow = 0;
	$matchCount  = 0;
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$avgTeleopConeLow = $avgTeleopConeLow + $teamData[8][$i][17];
		$matchCount++;
	}
	return ($avgTeleopConeLow / $matchCount);
}

function getAvgTeleopCubeHigh($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$avgTeleopCubeHigh = 0;
	$matchCount  = 0;
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$avgTeleopCubeHigh = $avgTeleopCubeHigh + $teamData[8][$i][16];
		$matchCount++;
	}
	return ($avgTeleopCubeHigh / $matchCount);
}

function getAvgTeleopCubeMid($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$avgTeleopCubeMid = 0;
	$matchCount  = 0;
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$avgTeleopCubeMid = $avgTeleopCubeMid + $teamData[8][$i][15];
		$matchCount++;
	}
	return ($avgTeleopCubeMid / $matchCount);
}

function getAvgTeleopCubeLow($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$avgTeleopCubeLow = 0;
	$matchCount  = 0;
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$avgTeleopCubeLow = $avgTeleopCubeLow + $teamData[8][$i][14];
		$matchCount++;
	}
	return ($avgTeleopCubeLow / $matchCount);
}

function getAvgTeleopGamePieceCount($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$gamePieceCount = 0;
	$matchCount  = 0;

	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$gamePieceCount = $gamePieceCount + $teamData[8][$i][14] + $teamData[8][$i][15] + $teamData[8][$i][16] + $teamData[8][$i][17] + $teamData[8][$i][18] + $teamData[8][$i][19];
		$matchCount++;
	}
	return (round(($gamePieceCount / $matchCount), 3));
}

function getMaxTeleopGamePieces($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$maxGamePieces = 0;
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		if ($maxGamePieces < $teamData[8][$i][14] + $teamData[8][$i][15] + $teamData[8][$i][16] + $teamData[8][$i][17] + $teamData[8][$i][18] + $teamData[8][$i][19]) {
			$maxGamePieces = $teamData[8][$i][14] + $teamData[8][$i][15] + $teamData[8][$i][16] + $teamData[8][$i][17] + $teamData[8][$i][18] + $teamData[8][$i][19];
		}
	}
	return ($maxGamePieces);
}

function getAvgTeleopDock($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$dock = 0;
	$matchCount  = 0;

	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$dock = $dock + $teamData[8][$i][20];
		$matchCount++;
	}
	return (round(($dock / $matchCount), 3));
}

function getAvgTeleopEngage($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$engage = 0;
	$matchCount  = 0;

	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$engage = $engage + $teamData[8][$i][21];
		$matchCount++;
	}
	return (round(($engage / $matchCount), 3));
}

function getAvgTeleopPark($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$park = 0;
	$matchCount  = 0;

	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$park = $park + $teamData[8][$i][22];
		$matchCount++;
	}
	return (round(($park / $matchCount), 3));
}

//----------------------------- Defense -------------------------------

function getTotalDefense($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$defenseCount = 0;
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$defenseCount = $defenseCount + $teamData[8][$i][24];
	}
	return ($defenseCount);
}

// --------------------------- TEAM DATA GRAPH -----------------------------------

function getAutoCones($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$matchN = matchNum($teamNumber);
	$cubeGraphT = array();
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$cubeGraphT[$teamData[8][$i][2]] = $teamData[8][$i][9] + $teamData[8][$i][10] + $teamData[8][$i][11];
	}
	$out = array();
	for ($i = 0; $i != sizeof($matchN); $i++) {
		array_push($out, $cubeGraphT[$matchN[$i]]);
	}
	return ($out);
}

function getAutoCubes($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$matchN = matchNum($teamNumber);
	$cubeGraphT = array();
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$cubeGraphT[$teamData[8][$i][2]] = $teamData[8][$i][6] + $teamData[8][$i][7] + $teamData[8][$i][8];
	}
	$out = array();
	for ($i = 0; $i != sizeof($matchN); $i++) {
		array_push($out, $cubeGraphT[$matchN[$i]]);
	}
	return ($out);
}


function getTeleopCones($teamNumber)
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

function getTeleopCubes($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$matchN = matchNum($teamNumber);
	$cubeGraphT = array();
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$cubeGraphT[$teamData[8][$i][2]] = $teamData[8][$i][14] + $teamData[8][$i][15] + $teamData[8][$i][16];
	}
	$out = array();
	for ($i = 0; $i != sizeof($matchN); $i++) {
		array_push($out, $cubeGraphT[$matchN[$i]]);
	}
	return ($out);
}


function getDefense($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$matchN = matchNum($teamNumber);
	$cubeGraphT = array();
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		$cubeGraphT[$teamData[8][$i][2]] = $teamData[8][$i][24];
	}
	$out = array();
	for ($i = 0; $i != sizeof($matchN); $i++) {
		array_push($out, $cubeGraphT[$matchN[$i]]);
	}
	return ($out);
}

// --------------------------- OTHER FUNCTIONS -------------------------------


function getAllMatchData()
{
	global $matchScoutTable;
	$qs1 = "SELECT * FROM `" . $matchScoutTable . "`";
	return runQuery($qs1);
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

function matchComments($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$matchComments = array();
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		array_push($matchComments, "Match " . $teamData[8][$i][2] . ": " . $teamData[8][$i][25]);
	}
	return ($matchComments);
}

function getIssues($teamNumber)
{
	$teamData = getTeamData($teamNumber);
	$matchComments = array();
	for ($i = 0; $i != sizeof($teamData[8]); $i++) {
		array_push($matchComments, "Match " . $teamData[8][$i][2] . ": " . $teamData[8][$i][23]);
	}
	return ($matchComments);
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
