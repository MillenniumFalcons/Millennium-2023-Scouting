<html>
<?php
include("navBar.php"); ?>

<body>
	<script src="js/Chart.js"></script>
	<style>
		body {
			padding: 0;
			margin: 0;
		}

		#canvas-holder {
			width: 50%;
		}

		#canvas-holder2 {
			width: 50%;
		}

		#canvas-holder3 {
			width: 50%;
		}

		.rotate090 {

			-webkit-transform: rotate(90deg);
			-moz-transform: rotate(90deg);
			-o-transform: rotate(90deg);
			-ms-transform: rotate(90deg);
			transform: rotate(90deg);
		}
	</style>
	<script>
		var $ = jQuery.noConflict();
	</script>
	<div class="container row-offcanvas row-offcanvas-left">
		<div class="well column  col-lg-112  col-sm-12 col-xs-12" id="content">
			<?php
			if ($_GET["team"]) {
				$teamNumber = $_GET["team"];
				include("databaseName.php");
				include("databaseLibrary.php");
				//getTeamData($_GET["team"]);
				$teamData = getTeamData($teamNumber);
			}
			?>
			<form action="" method="get">
				Enter Team Number: <input class="control-label" type="number" name="team" id="team" size="10" height="10" width="40">
				<button id="submit" class="btn btn-primary" onclick="">Display</button>
				<div class="row">
					<div class="col-md-4">
						<h1> Team <?php echo ($_GET["team"]); ?> - <?php echo ($teamData[1]); ?></h1>
						<div class="box">
							<div id="myCarousel" class="carousel slide" data-interval="false">
								<ol class="carousel-indicators">
									<?php
									$index = 0;
									while (file_exists("uploads/" . $_GET["team"] . "-" . $index . ".jpg") == 1) {
										if ($index == 0) {
											echo ('<li data-target="#myCarousel" data-slide-to="' . $index . '" class="active"></li>');
										} else {
											echo ('<li data-target="#myCarousel" data-slide-to="' . $index . '"></li>');
										}
										$index++;
									}

									$index = 0;
									while (file_exists("uploads/" . $_GET["team"] . "-" . $index . ".png") == 1) {
										if ($index == 0) {
											echo ('<li data-target="#myCarousel" data-slide-to="' . $index . '" class="active"></li>');
										} else {
											echo ('<li data-target="#myCarousel" data-slide-to="' . $index . '"></li>');
										}
										$index++;
									}

									$index = 0;
									while (file_exists("uploads/" . $_GET["team"] . "-" . $index . ".jpeg") == 1) {
										if ($index == 0) {
											echo ('<li data-target="#myCarousel" data-slide-to="' . $index . '" class="active"></li>');
										} else {
											echo ('<li data-target="#myCarousel" data-slide-to="' . $index . '"></li>');
										}
										$index++;
									}
									?>
								</ol>
								<div class="carousel-inner" role="listbox">
									<?php
									$index = 0;
									while (file_exists("uploads/" . $_GET["team"] . "-" . $index . ".jpg") == 1) {
										if ($index == 0) {
											echo ('<div class="item active" >
											<img   id="' . $_GET["team"] . '-' . $index . '" src="uploads/' . $_GET["team"] . '-' . $index . '.jpg" >
										 </div>');
										} else {
											echo ('<div class="item" >
											<img   id="' . $_GET["team"] . '-' . $index . '" src="uploads/' . $_GET["team"] . '-' . $index . '.jpg" >
										 </div>');
										}
										$index++;
									}

									$index = 0;
									while (file_exists("uploads/" . $_GET["team"] . "-" . $index . ".png") == 1) {
										if ($index == 0) {
											echo ('<div class="item active" >
											<img   id="' . $_GET["team"] . '-' . $index . '" src="uploads/' . $_GET["team"] . '-' . $index . '.png" >
										 </div>');
										} else {
											echo ('<div class="item" >
											<img   id="' . $_GET["team"] . '-' . $index . '" src="uploads/' . $_GET["team"] . '-' . $index . '.png" >
										 </div>');
										}
										$index++;
									}
									?>
								</div>
								<a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
									<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
									<span class="sr-only">Previous</span>
								</a>
								<a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
									<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
									<span class="sr-only">Next</span>
								</a>
							</div>
						</div>
						<button class=" btn btn-material-purple">Auto Upper</button>
						<button class=" btn btn-material-green">Teleop Upper</button>
						<button class=" btn btn-material-blue">Upper Shot Percentage</button>
						<button class=" btn btn-material-red">Total Lower</button>
						<button class=" btn btn-material-orange">Climb</button>
						<button class=" btn btn-material-brown"> Predicted Team Score Contribution</button>




						<canvas id="dataChart" width="300" height="250"></canvas>

						<script>
							var randomScalingFactor = function() {
								return Math.round(Math.random() * 100)
							};
							var lineChartData = {
								labels: <?php echo (json_encode(matchNum($teamNumber))); ?>,
								datasets: [

									{
										label: "Auto Upper Goal",
										fillColor: "rgba(220,220,220,0.1)",
										strokeColor: "purple",
										pointColor: "rgba(220,220,220,1)",
										pointStrokeColor: "#ffff00",
										pointHighlightFill: "#fff",
										pointHighlightStroke: "rgba(220,220,220,1)",
										data: <?php echo (json_encode(getAutoUpperGoal($teamNumber))); ?>
									},

									{
										label: "Teleop Upper Goal",
										fillColor: "rgba(220,220,220,0.1)",
										strokeColor: "green",
										pointColor: "rgba(220,220,220,1)",
										pointStrokeColor: "#ffff00",
										pointHighlightFill: "#fff",
										pointHighlightStroke: "rgba(220,220,220,1)",
										data: <?php echo (json_encode(getTeleopUpperGoal($teamNumber))); ?>
									},

									{
										label: "Upper Shot Percentage",
										fillColor: "rgba(220,220,220,0.1)",
										strokeColor: "blue",
										pointColor: "rgba(220,220,220,1)",
										pointStrokeColor: "#ffff00",
										pointHighlightFill: "#fff",
										pointHighlightStroke: "rgba(220,220,220,1)",
										data: <?php echo (json_encode(getUpperShotPercentage($teamNumber))); ?>
									},


									{
										label: "Climb",
										fillColor: "rgba(220,220,220,0.1)",
										strokeColor: "orange",
										pointColor: "rgba(220,220,220,1)",
										pointStrokeColor: "#ffff00",
										pointHighlightFill: "#fff",
										pointHighlightStroke: "rgba(220,220,220,1)",
										data: <?php echo (json_encode(getClimb($teamNumber))); ?>
									},

									{
										label: "Total Lower Goal",
										fillColor: "rgba(220,220,220,0.1)",
										strokeColor: "red",
										pointColor: "rgba(220,220,220,1)",
										pointStrokeColor: "#ffff00",
										pointHighlightFill: "#fff",
										pointHighlightStroke: "rgba(220,220,220,1)",
										data: <?php echo (json_encode(getTotalLowerGoal($teamNumber))); ?>
									},

									{
										label: "Predicted Team Score Contribution",
										fillColor: "rgba(220,220,220,0.1)",
										strokeColor: "brown",
										pointColor: "rgba(220,220,220,1)",
										pointStrokeColor: "#ffff00",
										pointHighlightFill: "#fff",
										pointHighlightStroke: "rgba(220,220,220,1)",
										data: <?php echo (json_encode(getScore($teamNumber))); ?>
									},

								]
							}
						</script>


					</div>
					<div class="col-md-4">
						<a>
							<h3><b><u>Upper and Lower Goal Statistics:</u></b></h3>
						</a>
						<div class="table-responsive">
							<table class="table">
								<tbody>
									<tr class="info">
										<td>Average Auto Upper Goal</td>
										<td><?php echo (getAvgUpperGoal($teamNumber)); ?></td>
									</tr>
									<tr class="success">
										<td>Average Auto Lower Goal</td>
										<td><?php echo (getAvgLowerGoal($teamNumber)); ?></td>
									</tr>
									<tr class="danger">
										<td>Average Teleop Upper Goal</td>
										<td><?php echo (getAvgUpperGoalT($teamNumber)); ?></td>
									</tr>
									<tr class="info">
										<td>Average Teleop Lower Goal</td>
										<td><?php echo (getAvgLowerGoalT($teamNumber)); ?></td>
									</tr>
									<tr class="success">
										<td>Max Teleop Upper</td>
										<td><?php echo (getMaxUpperGoalT($teamNumber)); ?></td>
									</tr>
									<tr class="danger">
										<td>Max Teleop Lower</td>
										<td><?php echo (getMaxLowerGoalT($teamNumber)); ?></td>
									</tr>
									<tr class="info">
										<td>Max Auto Upper</td>
										<td><?php echo (getMaxUpperGoal($teamNumber)); ?></td>
									</tr>
									<tr class="success">
										<td>Max Auto Lower</td>
										<td><?php echo (getMaxLowerGoal($teamNumber)); ?></td>
									</tr>
									<tr class="danger">
										<td>Avg of Predicted Score</td>
										<td><?php echo (getAvgscore($teamNumber)); ?></td>
									</tr>
									<tr class="info">
										<td>Average Cycle Count</td>
										<td><?php echo (getAvgCycleCount($teamNumber)); ?></td>
									</tr>


								</tbody>
							</table>
						</div>
						<a>
							<h3><b><u>Comments:</u></b></h3>
						</a>
						<div class="table-responsive">
							<table class="table">
								<tbody>
									<tr class="success">
										<td>Match Strategy Comments</td>
										<td><?php $mc = matchComments($teamNumber);
											for ($i = 0; $i != sizeof($mc); $i++) {
												echo ("$mc[$i].") . PHP_EOL;
											} ?></td>
									</tr>
									<tr class="info">
										<td>Defense Comments</td>
										<td><?php $dc = defenseComments($teamNumber);
											for ($i = 0; $i != sizeof($dc); $i++) {
												echo ("$dc[$i].") . PHP_EOL;
											} ?></td>
									</tr>

									<tr class="success">
										<td>Average Penalties</td>
										<td><?php echo (getAvgPenalties($teamNumber)); ?></td>
									</tr>

									<tr class="info">
										<td>Avg Drive Ranking</td>
										<td><?php echo (getAvgDriveRank($teamNumber)); ?></td>
									</tr>

									<tr class="danger">
										<td>Total Defense</td>
										<td><?php echo (getTotalDefense($teamNumber)); ?></td>
									</tr>

								</tbody>
							</table>

						</div>
					</div>
					<div class="col-md-4">
						<a>
							<h3><b><u>Pit Statistics:</u></b></h3>
						</a>
						<div class="table-responsive">
							<table class="table">
								<tbody>
									<tr class="danger">
										<td>No. of Batteries</td>
										<td><?php echo ($teamData[2]); ?></td>
									</tr>
									<tr class="info">
										<td>Batteries Charged Simultaneously</td>
										<td><?php echo ($teamData[3]); ?></td>
									</tr>

									<tr class="success">
										<td>Code Language</td>
										<td><?php echo ($teamData[4]); ?></td>
									</tr>

									<tr class="danger">
										<td>Pit Comments</td>
										<td><?php echo ($teamData[5]); ?></td>
									</tr>
									<tr class="info">
										<td>Have a Climber</td>
										<td><?php echo ($teamData[6]); ?></td>
									</tr>

								</tbody>
							</table>
						</div>
						<div>
							<canvas id="myCanvas" width="300" height="230" style="border:1px solid #d3d3d3;"></canvas>
							<script type="text/javascript">
								var canvas = document.getElementById('myCanvas');
								var context = canvas.getContext('2d');
								var drawLine = false;
								var oldCoor = {};
								var i = 1;
								var t;
								var coordinateList = [];
								var lastCoordinate = {};
								var imageObj = new Image();
								var matchToPoints = [];
								<?php
								for ($i = 0; $i != sizeof($teamData[8]); $i++) {
									echo ("matchToPoints[" . $teamData[8][$i][2] . "] = " . $teamData[8][$i][5] . ";");
								}
								?>
								imageObj.onload = function() {
									makeCanvasReady();
									var ctx = document.getElementById("dataChart").getContext("2d");
									window.myLine = new Chart(ctx).Line(lineChartData, {
										responsive: true
									});
								};
								imageObj.src = 'images/RedField.png';

								function makeCanvasReady() {
									context.clearRect(0, 0, 300, 231);
									context.drawImage(imageObj, 0, 0, 300, 231);
								}

								function adjustCanvas() {
									$("#canvasHolder").css('height', $(window).height() - 25);
									$("#canvasHolder").css('height', $(window).height() - 25);
									$("#main").attr('width', $("#canvasHolder").width());
									$("#main").attr('height', $("#canvasHolder").height());
								}

								function drawPoint(context, x, y) {
									context.fillRect(x, y, 1, 1);
								}

								function drawPointLines() {
									makeCanvasReady();
									var matchNumber = document.getElementById("matchNum").value;
									var a = matchToPoints[matchNumber];
									var color = "#FFFFFF";
									context.beginPath();
									context.strokeStyle = color;

									for (var i = 0; i != a.length; i++) {
										if (i == 0) {
											context.moveTo(a[i][0], a[i][1]);
										} else {
											context.lineTo(a[i][0], a[i][1]);
										}
									}
									context.stroke();
								}
							</script>
							<h4><b>Match Number -</b></h4>
							<select onclick="drawPointLines()" id="matchNum" class="form-control">
								<?php for ($i = 0; $i != sizeof($teamData[8]); $i++) {
									echo ("<option value='" . $teamData[8][$i][2] . "'>" . $teamData[8][$i][2] . "</option>");
								} ?>
							</select>
						</div>


						<a>
							<h3><b><u>Climb Statistics:</u></b></h3>
						</a>
						<div class="table-responsive">
							<table class="table">
								<tbody>
									<tr class="info">
										<td>Average Climbs</td>
										<td><?php echo (getAvgClimb($teamNumber)); ?></td>
									</tr>
									<tr class="success">
										<td>Total Climbs</td>
										<td><?php echo (getTotalClimb($teamNumber)); ?></td>
									</tr>
									<tr class="danger">
										<td>Total Single Climbs</td>
										<td><?php echo (getTotalSingleClimb($teamNumber)); ?></td>
									</tr>
									<tr class="info">
										<td>Total Double Climbs</td>
										<td><?php echo (getTotalDoubleClimb($teamNumber)); ?></td>
									</tr>
									<tr class="success">
										<td>Total Triple Climbs</td>
										<td><?php echo (getTotalTripleClimb($teamNumber)); ?></td>
									</tr>
									<tr class="danger">
										<td>Total Side Climbs</td>
										<td><?php echo (getTotalClimbSide($teamNumber)); ?></td>
									</tr>
									<tr class="info">
										<td>Total Center Climbs</td>
										<td><?php echo (getTotalClimbCenter($teamNumber)); ?></td>
									</tr>
									<tr class="success">
										<td>Total Control Panel Number</td>
										<td><?php echo (getTotalControlNumber($teamNumber)); ?></td>
									</tr>
									<tr class="danger">
										<td>Total Control Panel Position</td>
										<td><?php echo (getTotalControlPosition($teamNumber)); ?></td>
									</tr>


								</tbody>
							</table>
						</div>
						<a>
							<h3><b><u>D Statistics:</u></b></h3>
						</a>
						<div class="table-responsive">
							<table class="table">
								<tbody>
									<tr class="danger">
										<td>Total Times Defense Played</td>
										<td><?php echo (getTotalDefense($teamNumber)); ?></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
		</div>
	</div>
</body>

</html>
<?php include("footer.php"); ?>