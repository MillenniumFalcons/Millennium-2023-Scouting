<html>
<?php
include("header.php");
include("navBar.php");
?>
<?php
$blueEstimate = 0;
$redEstimate = 0;
function filter($str)
{
	return filter_var($str, FILTER_SANITIZE_STRING);
}
if (
	isset($_POST['team1Blue']) && isset($_POST['team2Blue']) && isset($_POST['team3Blue'])
	&& isset($_POST['team1Red']) && isset($_POST['team2Red']) && isset($_POST['team3Red'])
) {
	include("databaseLibrary.php");
	$team1Blue = filter($_POST['team1Blue']);
	$team2Blue = filter($_POST['team2Blue']);
	$team3Blue = filter($_POST['team3Blue']);
	$team1Red = filter($_POST['team1Red']);
	$team2Red = filter($_POST['team2Red']);
	$team3Red = filter($_POST['team3Red']);
	$blue1Estimate = getAvgscore($team1Blue);
	$blue2Estimate = getAvgscore($team2Blue);
	$blue3Estimate = getAvgscore($team3Blue);
	$red1Estimate = getAvgscore($team1Red);
	$red2Estimate = getAvgscore($team2Red);
	$red3Estimate = getAvgscore($team3Red);
	$blueEstimate = $blue1Estimate + $blue2Estimate + $blue3Estimate;
	$redEstimate = $red1Estimate + $red2Estimate + $red3Estimate;
	$blueEstimate += getAvgPenalties($team1Red) + getAvgPenalties($team2Red) + getAvgPenalties($team3Red);
	$redEstimate += getAvgPenalties($team1Blue) + getAvgPenalties($team2Blue) + getAvgPenalties($team3Blue);
}
?>

<body>

	<div class="container row-offcanvas row-offcanvas-left">
		<a>
			<h3><b><u>Red Alliance:</u></b></h3>
		</a>
		<div class="well column  col-lg-12  col-sm-12 col-xs-12" id="content">
			<div class="row" style="text-align: center;">
				<div class="col-md-2">
					Team 1:
					<input type="text" name="team1Red" id="team1Red" size="8" class="form-control">
				</div>
				<div class="col-md-2">
					Team 2:
					<input type="text" name="team2Red" id="team2Red" size="8" class="form-control">
				</div>
				<div class="col-md-2">
					Team 3:
					<input type="text" name="team3Red" id="team3Red" size="8" class="form-control">
				</div>
			</div>
		</div>
	</div>

	<div class="container row-offcanvas row-offcanvas-left">
		<a>
			<h3><b><u>Blue Alliance:</u></b></h3>
		</a>
		<div class="well column  col-lg-12  col-sm-12 col-xs-12" id="content">
			<div class="row" style="text-align: center;">
				<div class="col-md-2">
					Team 1:
					<input type="text" name="team1Blue" id="team1Blue" size="8" class="form-control">
				</div>
				<div class="col-md-2">
					Team 2:
					<input type="text" name="team2Blue" id="team2Blue" size="8" class="form-control">
				</div>
				<div class="col-md-2">
					Team 3:
					<input type="text" name="team3Blue" id="team3Blue" size="8" class="form-control">
				</div>
			</div>
		</div>
		<div class="col-md-4">
			<br />
			<button id="submit" class="btn btn-primary" onclick="postwith('');">Submit Data</button>
			<br />
			<h1 style="margin-left:5px;"> Red Score:<spam style="color:red"><?php echo ($redEstimate); ?></spam>
			</h1>
			<h1 style="margin-left:5px;">Blue Score:<spam style="color:blue"><?php echo ($blueEstimate); ?></spam>
			</h1>
		</div>
	</div>

</body>


<script>
	function postwith(to) {

		var myForm = document.createElement("form");
		myForm.method = "post";
		myForm.action = to;

		var names = [
			'team1Blue',
			'team2Blue',
			'team3Blue',
			'team1Red',
			'team2Red',
			'team3Red'
		];

		var nums = [
			document.getElementById('team1Blue').value,
			document.getElementById('team2Blue').value,
			document.getElementById('team3Blue').value,
			document.getElementById('team1Red').value,
			document.getElementById('team2Red').value,
			document.getElementById('team3Red').value
		];


		for (var i = 0; i != names.length; i++) {
			var myInput = document.createElement("input");
			myInput.setAttribute("name", names[i]);
			myInput.setAttribute("value", nums[i]);
			myForm.appendChild(myInput);
		}

		document.body.appendChild(myForm);
		myForm.submit();
		document.body.removeChild(myForm);
	}
</script>

<?php include("footer.php"); ?>

</html>