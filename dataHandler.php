<?php
include("matchInput.php");

function filter($str)
{
	return filter_var($str, FILTER_SANITIZE_STRING);
}
?>
<script>
	console.log("No way9");
</script>
<?php
if (isset($_POST['matchNum'])) {

	include("databaseLibrary.php");
	$user = filter($_POST['userName']);
	$matchNum = filter($_POST['matchNum']);
	$teamNum = filter($_POST['teamNum']);
	$ID = $matchNum . "-" . $teamNum;
	$allianceColor = filter($_POST['allianceColor']);
	$crossLineA = filter($_POST['crossLineA']);

	$aCubeL = filter($_POST['aCubeL']);
	$aCubeM = filter($_POST['aCubeM']);
	$aCubeH = filter($_POST['aCubeH']);
	$aConeL = filter($_POST['aConeL']);
	$aConeM = filter($_POST['aConeM']);
	$aConeH = filter($_POST['aConeH']);

	$aDocked = filter($_POST['aDocked']);
	$aEngaged = filter($_POST['aEngaged']);

	$tCubeL = filter($_POST['tCubeL']);
	$tCubeM = filter($_POST['tCubeM']);
	$tCubeH = filter($_POST['tCubeH']);
	$tConeL = filter($_POST['tConeL']);
	$tConeM = filter($_POST['tConeM']);
	$tConeH = filter($_POST['tConeH']);

	$docked = filter($_POST['docked']);
	$engaged = filter($_POST['engaged']);
	$parked = filter($_POST['parked']);

	$issues = filter($_POST['issues']);
	$defenseBot = filter($_POST['defenseBot']);
	$matchComments = filter($_POST['matchComments']);

	matchInput(
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
	);
}


?>