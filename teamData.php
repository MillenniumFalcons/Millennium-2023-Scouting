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
					</div>
					<div class="col-md-4">

						<button class=" btn btn-material-yellow">Auto Cones</button>
						<button class=" btn btn-material-purple">Auto Cubes</button>
						<button class=" btn btn-material-red">Teleop Cones</button>
						<button class=" btn btn-material-blue">Teleop Cubes</button>
						<button class=" btn btn-material-green">Defense</button>

						<canvas id="myCanvas" width="10" height="10" style="border:1px solid #d3d3d3;"></canvas>
							<script type="text/javascript">
								var canvas = document.getElementById('myCanvas');
								var context = canvas.getContext('2d');
								var oldCoor = {};
					
								var imageObj = new Image();
								
								imageObj.onload = function() {
									var ctx = document.getElementById("dataChart").getContext("2d");
									window.myLine = new Chart(ctx).Line(lineChartData, {
										responsive: true
									});
								};
								imageObj.src = 'images/RedField.png';

							</script>
						<canvas id="dataChart" width="300" height="250"></canvas>

						<script>
							var randomScalingFactor = function() {
								return Math.round(Math.random() * 100)
							};
							var lineChartData = {
								labels: <?php echo (json_encode(matchNum($teamNumber))); ?>,
								datasets: [

									{
										label: "Auto Cones",
										fillColor: "rgba(220,220,220,0.1)",
										strokeColor: "yellow",
										pointColor: "yellow",
										pointStrokeColor: "yellow",
										pointHighlightFill: "#fff",
										pointHighlightStroke: "rgba(220,220,220,1)",
										data: <?php echo (json_encode(getAutoCones($teamNumber))); ?>
									},

									{
										label: "Auto Cubes",
										fillColor: "rgba(220,220,220,0.1)",
										strokeColor: "purple",
										pointColor: "purple",
										pointStrokeColor: "purple",
										pointHighlightFill: "#fff",
										pointHighlightStroke: "rgba(220,220,220,1)",
										data: <?php echo (json_encode(getAutoCubes($teamNumber))); ?>
									},

									{
										label: "Teleop Cones",
										fillColor: "rgba(220,220,220,0.1)",
										strokeColor: "red",
										pointColor: "red",
										pointStrokeColor: "red",
										pointHighlightFill: "#fff",
										pointHighlightStroke: "rgba(220,220,220,1)",
										data: <?php echo (json_encode(getTeleopCones($teamNumber))); ?>
									},


									{
										label: "Teleop Cubes",
										fillColor: "rgba(220,220,220,0.1)",
										strokeColor: "blue",
										pointColor: "blue",
										pointStrokeColor: "blue",
										pointHighlightFill: "#fff",
										pointHighlightStroke: "rgba(220,220,220,1)",
										data: <?php echo (json_encode(getTeleopCubes($teamNumber))); ?>
									},

									{
										label: "Played Defense",
										fillColor: "rgba(220,220,220,0.1)",
										strokeColor: "green",
										pointColor: "green",
										pointStrokeColor: "green",
										pointHighlightFill: "#fff",
										pointHighlightStroke: "rgba(220,220,220,1)",
										data: <?php echo (json_encode(getDefense($teamNumber))); ?>
									},

								]
							};
						</script>


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
												echo ("$mc[$i] &nbsp -- &nbsp") . PHP_EOL;
											} ?></td>
									</tr>
									<tr class="info">
										<td>Issues</td>
										<td>
											<?php $mc = getIssues($teamNumber);
											for ($i = 0; $i != sizeof($mc); $i++) {
												echo ("$mc[$i] &nbsp &nbsp ") . PHP_EOL;
											} ?>
										</td>
									</tr>

								</tbody>
							</table>

						</div>
	
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
										<td>What Autos</td>
										<td><?php echo ($teamData[6]); ?></td>
									</tr>
									<tr class="info">
										<td>Pit Comments</td>
										<td><?php echo ($teamData[5]); ?></td>
									</tr>
									

								</tbody>
							</table>
						</div>


						<a>
							<h3><b><u>Climb Statistics:</u></b></h3>
						</a>
						<div class="table-responsive">
							<table class="table">
								<tbody>
									<tr class="danger">
										<td>Average Auto Dock</td>
										<td><?php echo (getAvgAutoDock($teamNumber)); ?></td>
									</tr>
									<tr class="info">
										<td>Average Auto Engage</td>
										<td><?php echo (getAvgAutoEngage($teamNumber)); ?></td>
									</tr>

									<tr class="success">
										<td>Average Teleop Dock</td>
										<td><?php echo (getAvgTeleopDock($teamNumber)); ?></td>
									</tr>

									<tr class="danger">
										<td>Average Teleop Engage</td>
										<td><?php echo (getAvgTeleopEngage($teamNumber)); ?></td>
									</tr>
									<tr class="info">
										<td>Average Teleop Park</td>
										<td><?php echo (getAvgTeleopPark($teamNumber)); ?></td>
									</tr>
									

								</tbody>
							</table>
						</div>
		</div>
	</div>
</body>

</html>
