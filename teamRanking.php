<html>
<?php
include("header.php") ?>

<body>
	<?php include("navBar.php") ?>

		<div class="well column  col-lg-12  col-sm-12 col-xs-12" id="content">
			<h2>Team Ranking</h2>
			<table class="sortable table table-hover" id="RawData" border="1">
				<tr>
					<th>Team Number</th>
					<th>Avg Game Pieces</th>
					<th>Max Game Pieces</th>
					<th bgcolor="MediumPurple">Avg Auto Cube Low</th>				
					<th bgcolor="MediumPurple">Avg Auto Cube Mid</th>
					<th bgcolor="MediumPurple">Avg Auto Cube High</th>
					<th bgcolor="yellow">Avg Auto Cone Low</th>
					<th bgcolor="yellow">Avg Auto Cone Mid</th>
					<th bgcolor="yellow">Avg Auto Cone High</th>	
					<th>Avg Auto Game Pieces</th>
					<th>Max Auto Game Pieces</th>
					<th>Avg Auto Dock</th>
					<th>Avg Auto Engage</th>
					<th bgcolor="PaleTurquoise">Avg Teleop Cube Low</th>				
					<th bgcolor="PaleTurquoise">Avg Teleop Cube Mid</th>
					<th bgcolor="PaleTurquoise">Avg Teleop Cube High</th>
					<th bgcolor="LightCoral">Avg Teleop Cone Low</th>
					<th bgcolor="LightCoral">Avg Teleop Cone Mid</th>
					<th bgcolor="LightCoral">Avg Teleop Cone High</th>	
					<th>Avg Teleop Game Pieces</th>
					<th>Max Teleop Game Pieces</th>
					<th>Avg Teleop Dock</th>
					<th>Avg Teleop Engage</th>
					<th bgcolor="PaleGreen">Total Defense</th>
				</tr>
				<?php
				include("databaseLibrary.php");
				$teamList = getTeamList();

				foreach ($teamList as $teamNumber) {

					$i = 0;
					$GamePieceAvg = getAvgGamePieceCount($teamNumber);
					$GamePieceMax = getMaxGamePieces($teamNumber);
					$autoCubeHigh = getAvgAutoCubeHigh($teamNumber);
					$autoCubeMid = getAvgAutoCubeMid($teamNumber);
					$autoCubeLow = getAvgAutoCubeLow($teamNumber);
					$autoConeHigh = getAvgAutoConeHigh($teamNumber);
					$autoConeMid = getAvgAutoConeMid($teamNumber);
					$autoConelow = getAvgAutoConeLow($teamNumber);
					$autoGamePieceAvg = getAvgAutoGamePieceCount($teamNumber);
					$autoGamePieceMax = getMaxAutoGamePieces($teamNumber);
					$autoDock = getAvgAutoDock($teamNumber);
					$autoEngage = getAvgAutoEngage($teamNumber);
					$teleopCubeHigh = getAvgTeleopCubeHigh($teamNumber);
					$teleopCubeMid = getAvgTeleopCubeMid($teamNumber);
					$teleopCubeLow = getAvgTeleopCubeLow($teamNumber);
					$teleopConeHigh = getAvgTeleopConeHigh($teamNumber);
					$teleopConeMid = getAvgTeleopConeMid($teamNumber);
					$teleopConelow = getAvgTeleopConeLow($teamNumber);
					$teleopGamePieceAvg = getAvgTeleopGamePieceCount($teamNumber);
					$teleopGamePieceMax = getMaxTeleopGamePieces($teamNumber);
					$teleopDock = getAvgTeleopDock($teamNumber);
					$teleopEngage = getAvgTeleopEngage($teamNumber);
					$totalDefense = getTotalDefense($teamNumber);




					echo ("<tr>
					<td><a href='teamData.php?team=" . $teamNumber . "'>" . $teamNumber . "</a></td>
					<th>" . $GamePieceAvg . "</th>
					<th>" . $GamePieceMax . "</th>
					<th>" . $autoCubeLow . "</th>
					<th>" . $autoCubeMid . "</th>
					<th>" . $autoCubeHigh . "</th>
					<th>" . $autoConelow . "</th>
					<th>" . $autoConeMid . "</th>
					<th>" . $autoConeHigh . "</th>
					<th>" . $autoGamePieceAvg . "</th>
					<th>" . $autoGamePieceMax . "</th>
					<th>" . $autoDock . "</th>
					<th>" . $autoEngage . "</th>
					<th>" . $teleopCubeLow . "</th>
					<th>" . $teleopCubeMid . "</th>
					<th>" . $teleopCubeHigh . "</th>
					<th>" . $teleopConelow . "</th>
					<th>" . $teleopConeMid . "</th>
					<th>" . $teleopConeHigh . "</th>
					<th>" . $teleopGamePieceAvg . "</th>
					<th>" . $teleopGamePieceMax . "</th>
					<th>" . $teleopDock . "</th>
					<th>" . $teleopEngage . "</th>
					<th>" . $totalDefense . "</th>

					</tr>");
				}

				?>
			</table>
		</div>
</body>
<?php include("footer.php") ?>