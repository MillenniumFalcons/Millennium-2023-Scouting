<html>
<?php
include("navBar.php");
?>
<script src="Orange-Rind/js/orangePersist.js"></script>
<script src="matchInputDynamic.js"></script>
<script>
	function postwith(to) {
		if (document.getElementById('penalties').value == "") {
			document.getElementById('penalties').value = 0;
		}

		if (document.getElementById('matchNum').value == "" || document.getElementById('teamNum').value == "") {
			return;
		}

		var nums = {
			'userName': document.getElementById('userName').value,
			'matchNum': document.getElementById('matchNum').value,
			'teamNum': document.getElementById('teamNum').value,
			'allianceColor': document.getElementById('allianceColor').value,
			'autoPath': JSON.stringify(coordinateList),
			'crossLineA': document.getElementById('crossLineA').checked ? 1 : 0,

			'upperGoal': upperGoal,
			'upperGoalMiss': upperGoalMiss,
			'lowerGoal': lowerGoal,
			'lowerGoalMiss': lowerGoalMiss,

			'upperGoalT': upperGoalT,
			'upperGoalMissT': upperGoalMissT,
			'lowerGoalT': lowerGoalT,
			'lowerGoalMissT': lowerGoalMissT,
			'controlPanelPosT': document.getElementById('controlPanelPosT').checked ? 1 : 0,
			'controlPanelNumT': document.getElementById('controlPanelNumT').checked ? 1 : 0,

			'climb': climb,
			'climbTwo': climbTwo,
			'climbThree': climbThree,
			'climbCenter': climbCenter,
			'climbSide': climbSide,

			'issues': document.getElementById('issues').value,
			'defenseBot': document.getElementById('defenseBot').checked ? 1 : 0,
			'defenseComments': document.getElementById('defenseComments').value,
			'matchComments': document.getElementById('matchComments').value,
			'penalties': document.getElementById('penalties').value,
			'cycleNumber': cycleNumber,
		};

		var id = document.getElementById('matchNum').value + "-" + document.getElementById('teamNum').value;
		console.log(JSON.stringify(nums));
		console.log("hello");
		/*
		orangePersist.collection("avr").doc(id).set(nums);
		*/
		$.post("dataHandler.php", nums).done(function(data) {}).done(function() {
			alert("Submission Succeeded! Form Reloading.");
			location.reload(true);
		}).fail(function() {
			alert("Submission Failed! Please alert your head scout!");
		});
	}
</script>

<body>

	<div class="container row-offcanvas row-offcanvas-left">
		<div class="well column  col-lg-12  col-sm-12 col-xs-12" id="content">
			<div class="row" style="text-align: center;">
				<div class="col-md-2">
					User:
					<input type="text" name="userName" onKeyUp="saveUserName()" id="userName" size="8" class="form-control">
				</div>
				<div class="col-md-2">
					Match Number:
					<input type="text" name="matchNum" id="matchNum" size="8" class="form-control">
				</div>
				<div class="col-md-2">
					Team Number:
					<input type="text" name="teamNum" id="teamNum" size="8" class="form-control">
				</div>
				<div class="col-md-3">
					Alliance Color:
					<select id="allianceColor" class="form-control">
						<option value='blue'>Blue</option>
						<option value='red'>Red</option>
					</select>
				</div>
				<div class="col-md-3">
					<button id="Switch" onclick="autotele();" class="btn btn-primary">Teleop</button>
				</div>
			</div>

			<!--Auto Scouting-->
			<div id="autoscouting">
				<a>
					<h2><b><u>Auto Scouting:</u></b></h2>
				</a>
				<div class="row">
					<div class="col-md-4">
						<div class="togglebutton" id="reach">
							<h4><b>Left Auto Line:</b></h4>
							<label>
								<input id="crossLineA" type="checkbox">
							</label>
						</div>
						<a href="javascript:void(0)" class="btn btn-raised btn-boulder btn-material-teal-600" onclick="clearPath()"><b>CLEAR PATH</b></a>
						<div class="row">
							<canvas id="myCanvas" width=300px height=231px style="border:0px solid #d3d3d3;">
								<script src="Drawing.js"></script>
							</canvas>
						</div>
					</div>
				</div>
				<div>
					<div class="row">
						<a>
							<h3><b><u> Upper Goal:</u></b></h3>
						</a>
						<button type="button" onClick="updateupperGoal()" class="enlargedtext stylishUpper" id="bigFont"><a id="upperGoal" class="enlargedtext">0</a> Upper Goal </button>
						<button type="button" onClick="updateupperGoalMiss()" class="enlargedtext stylishUpper" id="bigFont"> Upper Goal Miss <a id="upperGoalMiss" class="enlargedtext">0</a></button>
						<button type="button" onClick="upperGoalClear()" class="enlargedtext stylishUpperMiss" id="bigFont"> Clear <a class="enlargedtext"></a></button>
						<br>
						<br>
						<br>

						<a>
							<h3><b><u>Lower Goal:</u></b></h3>
						</a>
						<button type="button" onClick="updatelowerGoal()" class="enlargedtext stylishLower" id="bigFont"><a id="lowerGoal" class="enlargedtext">0</a> Lower Goal </button>
						<button type="button" onClick="updatelowerGoalMiss()" class="enlargedtext stylishLower" id="bigFont"> Lower Goal Miss <a id="lowerGoalMiss" class="enlargedtext">0</a></button>
						<button type="button" onClick="lowerGoalClear()" class="enlargedtext stylishUpperMiss" id="bigFont"> Clear <a class="enlargedtext"></a></button>
						<br>
						<br>
					</div>
				</div>
			</div>

			<!--Tepeop scouting section-->
			<div id="teleopscouting">
				<a>
					<h2><b><u>Teleop Scouting:</u></b></h2>
				</a>
				<div>
				</div>

				<script>
					function updatelowerGoalMiss() {
						lowerGoalMiss += increment;
						document.getElementById("lowerGoalMiss").innerHTML = lowerGoalMiss;

					}

					function updatelowerGoal() {
						lowerGoal += increment;

						document.getElementById("lowerGoal").innerHTML = lowerGoal;

					}

					function updateupperGoalMiss() {

						upperGoalMiss += increment;

						document.getElementById("upperGoalMiss").innerHTML = upperGoalMiss;

					}

					function updateupperGoal() {
						upperGoal += increment;

						document.getElementById("upperGoal").innerHTML = upperGoal;

					}

					function upperGoalClear() {
						upperGoal = 0;
						upperGoalMiss = 0;

						document.getElementById("upperGoal").innerHTML = upperGoalT;
						document.getElementById("upperGoalMiss").innerHTML = upperGoalT;

					}

					function lowerGoalClear() {
						lowerGoal = 0;
						lowerGoalMiss = 0;

						document.getElementById("lowerGoal").innerHTML = upperGoalT;
						document.getElementById("lowerGoalMiss").innerHTML = upperGoalT;

					}









					upperGoalTemp = 0;
					upperGoalMissTemp = 0;
					lowerGoalTemp = 0;
					lowerGoalMissTemp = 0;
					climb = 0;
					climbTwo = 0;
					climbThree = 0;
					climbCenter = 0;
					climbSide = 0;


					function updateupperGoalT() {
						upperGoalTemp += increment;

						document.getElementById("upperGoalTemp").innerHTML = upperGoalTemp;

					}

					function updateupperGoalMissT() {
						upperGoalMissTemp += increment;
						document.getElementById("upperGoalMissTemp").innerHTML = upperGoalMissTemp;

					}

					function updatelowerGoalT() {
						lowerGoalTemp += increment;
						document.getElementById("lowerGoalTemp").innerHTML = lowerGoalTemp;

					}

					function updatelowerGoalMissT() {
						lowerGoalMissTemp += increment;
						document.getElementById("lowerGoalMissTemp").innerHTML = lowerGoalMissTemp;

					}

					function upperGoalClearT() {
						upperGoalTemp = 0;
						upperGoalMissTemp = 0;

						document.getElementById("upperGoalTemp").innerHTML = upperGoalTemp;
						document.getElementById("upperGoalMissTemp").innerHTML = upperGoalMissTemp;

					}

					function lowerGoalClearT() {
						lowerGoalTemp = 0;
						lowerGoalMissTemp = 0;

						document.getElementById("lowerGoalTemp").innerHTML = lowerGoalTemp;
						document.getElementById("lowerGoalMissTemp").innerHTML = lowerGoalMissTemp;

					}

					function okButton() {
						lowerGoalT += lowerGoalTemp;
						upperGoalT += upperGoalTemp;
						lowerGoalMissT += lowerGoalMissTemp;
						upperGoalMissT += upperGoalMissTemp;

						if ((lowerGoalTemp + upperGoalTemp + lowerGoalMissTemp + upperGoalMissTemp) == 0) {
							cycleNumber = cycleNumber;
						} else {
							cycleNumber += 1;
						}

						lowerGoalTemp = 0;
						upperGoalTemp = 0;
						lowerGoalMissTemp = 0;
						upperGoalMissTemp = 0;

						document.getElementById("lowerGoalTemp").innerHTML = lowerGoalTemp;
						document.getElementById("lowerGoalMissTemp").innerHTML = lowerGoalMissTemp;
						document.getElementById("upperGoalTemp").innerHTML = lowerGoalTemp;
						document.getElementById("upperGoalMissTemp").innerHTML = lowerGoalMissTemp;

					}

					function climbLoc(climbLocation) {
						if (climbLocation == 1) {
							climbSide = 1;
							climbCenter = 0;
						} else {
							if (climbLocation == 2) {
								climbCenter = 1;
								climbSide = 0;
							}
						}

					}

					function climbTyp(climbType) {
						if (climbType == 1) {
							climb = 1;
							climbTwo = 0;
							climbThree = 0;
						} else {
							if (climbType == 2) {
								climbTwo = 1;
								climbThree = 0;
								climb = 0;
							} else {
								if (climbType == 3) {
									climbThree = 1;
									climb = 0;
									climbTwo = 0;

								}
							}
						}
					}
				</script>

				<script>
					var increment = 1;
					var upperGoal = 0;
					var upperGoalMiss = 0;
					var lowerGoal = 0;
					var lowerGoalMiss = 0;

					var upperGoalT = 0;
					var upperGoalMissT = 0;
					var lowerGoalT = 0;
					var lowerGoalMissT = 0;
					var cycleNumber = 0;
				</script>

				<a>
					<h3><b><u>Upper Goal:</u></b></h3>
				</a>
				<button type="button" onClick="updateupperGoalT()" class="enlargedtext stylishUpper" id="bigFont"><a id="upperGoalTemp" class="enlargedtext">0</a> Upper Goal </button>
				<button type="button" onClick="updateupperGoalMissT()" class="enlargedtext stylishUpper" id="bigFont"> Upper Goal Miss <a id="upperGoalMissTemp" class="enlargedtext">0</a></button>
				<button type="button" onClick="upperGoalClearT()" class="enlargedtext stylishUpperMiss" id="bigFont"> Clear <a class="enlargedtext"></a></button>
				<br>
				<br>
				<br>

				<a>
					<h3><b><u>Lower Goal:</u></b></h3>
				</a>
				<button type="button" onClick="updatelowerGoalT()" class="enlargedtext stylishLower" id="bigFont"><a id="lowerGoalTemp" class="enlargedtext">0</a> Lower Goal </button>
				<button type="button" onClick="updatelowerGoalMissT()" class="enlargedtext stylishLower" id="bigFont"> Lower Goal Miss <a id="lowerGoalMissTemp" class="enlargedtext">0</a></button>
				<button type="button" onClick="lowerGoalClearT()" class="enlargedtext stylishUpperMiss" id="bigFont"> Clear <a class="enlargedtext"></a></button>
				<br>
				<br>
				<br>

				<button type="button" onClick="okButton()" class="btn btn-primary" id="bigFont"> Save Cycle <a class="enlargedtext"></a></button>

				<div class="togglebutton" id="reach">
					<h4><b>Control Panel Rotation Control</b></h4>
					<label>
						<input id="controlPanelNumT" type="checkbox">
					</label>
				</div>

				<div class="togglebutton" id="reach">
					<h4><b>Control Panel Position Control</b></h4>
					<label>
						<input id="controlPanelPosT" type="checkbox">
					</label>
				</div>

				<!--Defense-->
				<a>
					<h3><b><u>Defense:</u></b></h3>
				</a>
				<div class="togglebutton" id="reach">
					<h4><b>Defense?</b></h4>
					<label>
						<input id="defenseBot" type="checkbox">
					</label>
				</div>

				<h4><b><u>Defense Comments: </u></b></h4>
				<textarea placeholder="Please write strategy of the robot or interesting observations of the robot" type="text" id="defenseComments" class="form-control md-textarea" rows="6"></textarea>
				<br>

				<!--Climb-->
				<a>
					<h3><b><u>Climb:</u></b></h3>
				</a>
				<input type="radio" onClick="climbTyp(0)" name="ClimbTyp" value="None"> None&nbsp&nbsp</button>
				<input type="radio" onClick="climbTyp(1)" name="ClimbTyp" value="Single"> Single&nbsp&nbsp</button>
				<input type="radio" onClick="climbTyp(2)" name="ClimbTyp" value="Double"> Double&nbsp&nbsp</button>
				<input type="radio" onClick="climbTyp(3)" name="ClimbTyp" value="Triple"> Triple&nbsp&nbsp</button>


				<a>
					<h3><b><u>Where did they climb:</u></b></h3>
				</a>
				<input type="radio" onClick="climbLoc(0)" name="Climb" value="None"> None&nbsp&nbsp</button>
				<input type="radio" onClick="climbLoc(1)" name="Climb" value="Side"> Side&nbsp&nbsp</button>
				<input type="radio" onClick="climbLoc(2)" name="Climb" value="Center"> Center&nbsp&nbsp</button>

				<h4><b><u>Penalties: </u></b></h4>
				<textarea placeholder="Number of Penalties" type="text" id="penalties" class="form-control md-textarea" rows="6">0</textarea>

				<a>
					<h3><b><u>Robot Issues:</u></b></h3>
				</a>
				<select id="issues" multiple="" class="form-control">
					<option value="N/A">None</option>
					<option value="dead">Dead</option>
					<option value="stopped working">Stopped Working</option>
					<option value="fell over">Fell Over</option>
				</select>


				<h4><b><u>Comments / Strategy: </u></b></h4>
				<textarea placeholder="Please write strategy of the robot or interesting observations of the robot" type="text" id="matchComments" class="form-control md-textarea" rows="6"></textarea>
				<br>

				<br> <br>
				<div style="padding: 5px; padding-bottom: 10;">
					<input type="button" value="Submit Data" id="submitButton" class="btn btn-primary" onclick="okButton(''); postwith('')" />


				</div>
			</div>
		</div>
	</div>
	</div>
	</div>
	</div>

	<style>
		.stylishLower {
			background-color: rgb(58, 133, 129);
			color: white;
			border-radius: 2px;
			font-family: Helvetica;
			font-weight: bold;
			/*To get rid of weird 3D affect in some browsers*/
			border: solid rgb(58, 133, 129);
		}

		.stylishLower:hover {
			background-color: Orange;
			border-color: Orange;
		}

		.stylishUpperMiss {
			background-color: rgb(255, 0, 0);
			color: white;
			border-radius: 2px;
			font-family: Helvetica;
			font-weight: bold;
			/*To get rid of weird 3D affect in some browsers*/
			border: solid rgb(255, 0, 0);
		}

		.stylishLowerMiss:hover {
			background-color: Orange;
			border-color: Orange;
		}


		.stylishUpper {
			background-color: rgb(255, 120, 50);
			color: white;
			border-radius: 2px;
			font-family: Helvetica;
			font-weight: bold;
			/*To get rid of weird 3D affect in some browsers*/
			border: solid rgb(255, 120, 50);
		}

		.stylishUpper:hover {
			background-color: Orange;
			border-color: Orange;
		}

		#bigFont {
			font-size: 20px
		}

		#mediumFont {
			font-size: 15px
		}

		#smallFont {
			font-size: 10px
		}

		.feedback:hover {
			background-color: Orange;
		}
	</style>
</body>

</html>
<?php include("footer.php"); ?>