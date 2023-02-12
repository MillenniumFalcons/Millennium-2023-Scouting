<html>
<?php
include("navBar.php");
?>
<script src="matchInputDynamic.js"></script>
<script>
	function postwith(to) {

		if (document.getElementById('matchNum').value == "" || document.getElementById('teamNum').value == "") {
			return;
		}
		if(document.getElementById('issues').value == ""){
			document.getElementById('issues').value = "None";
		}

		var nums = {
			'userName': document.getElementById('userName').value,
			'matchNum': document.getElementById('matchNum').value,
			'teamNum': document.getElementById('teamNum').value,
			'allianceColor': document.getElementById('allianceColor').value,
			'crossLineA': document.getElementById('crossLineA').checked ? 1 : 0,

			'aCubeL': aCubeL,
			'aCubeM': aCubeM,
			'aCubeH': aCubeH,
			'aConeL': aConeL,
			'aConeM': aConeM,
			'aConeH': aConeH,

			'aDocked': aDocked,
			'aEngaged': aEngaged,

			'tCubeL': tCubeL,
			'tCubeM': tCubeM,
			'tCubeH': tCubeH,
			'tConeL': tConeL,
			'tConeM': tConeM,
			'tConeH': tConeH,

			'docked': docked,
			'engaged': engaged,
			'parked': parked,

			'issues': document.getElementById('issues').value,
			'defenseBot': document.getElementById('defenseBot').checked ? 1 : 0,
			'matchComments': document.getElementById('matchComments').value
		};

		var id = document.getElementById('matchNum').value + "-" + document.getElementById('teamNum').value;
		console.log(JSON.stringify(nums));

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
					<input type="text" name="userName" id="userName" size="8" class="form-control">
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
					<button id="Switch" onclick="autotele();" class="clearColor">Teleop</button>
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
							<h4><b>Exited Auto Line:</b></h4>
							<label>
								<input id="crossLineA" type="checkbox">
							</label>
						</div>
					</div>
				</div>

				<div class="togglebutton" id="reach">
					<h4 id="updateModeToggle"><b><u>Adding</u></b></h4>
					<label>
						<input id="updateMode" type="checkbox" onclick="changeMode()" checked>
					</label>
				</div>	

				<div class = "row" >
					<div class="col-md-5">
						<div class="buttonOnPicture">
							<img src="images/coneScore.jpeg" alt="Snow">
							<button type="button" onClick="updateConesLow()" class="btn4" id="bigFont"><a id="aConeLButton" >0</a></button>
							<button type="button" onClick="updateConesMid()" class="btn5" id="bigFont"><a id="aConeMButton" >0</a></button>
							<button type="button" onClick="updateConesHigh()" class="btn6" id="bigFont"><a id="aConeHButton" >0</a></button>
						</div>
					</div>

					<div class="col-md-5">
						<div class="buttonOnPicture">
							<img src="images/cubeScore.jpeg" alt="Snow">
							<button type="button" onClick="updateCubesLow()" class="btn1" id="bigFont"><a id="aCubeLButton" >0</a></button>
							<button type="button" onClick="updateCubesMid()" class="btn2" id="bigFont"><a id="aCubeMButton" >0</a></button>
							<button type="button" onClick="updateCubesHigh()" class="btn3" id="bigFont"><a id="aCubeHButton" >0</a></button>
						</div>
					</div>
				</div>

				<a>
				<h3><b><u>Auto Climb:</u></b></h3>
				</a>
				<input type="radio" onClick="climbLoc(0)" name="Climb" value="None"> None </button>
				<input type="radio" onClick="climbLoc(1)" name="Climb" value="Side"> Docked </button>
				<input type="radio" onClick="climbLoc(2)" name="Climb" value="Center"> Engaged </button>
			</div>


			<!--Tepeop scouting section-->
			<div id="teleopscouting">
				<a>
					<h2><b><u>Teleop Scouting:</u></b></h2>
				</a>
				<div>
				</div>

				<script>

					aCubeL = 0;
					aCubeM = 0;
					aCubeH = 0;
					aConeL = 0;
					aConeM = 0;
					aConeH = 0;
					updateMode = true;
					increment = 1;
					aDocked = 0;
					aEngaged = 0;

					docked = 0;
					engaged = 0;
					parked = 0;


					function climbLoc(loc){
						if(loc == 0){
							aDocked = 0;
							aEngaged = 0;
						}else if(loc == 1){
							aDocked = 1;
							aEngaged = 0;
						}else{
							aDocked = 0;
							aEngaged = 1;
						}
					}

					function climbLocT(loc){
						if(loc == 0){
							docked = 0;
							engaged = 0;
							parked = 0;
							console.log(parked + "" + docked + "" + engaged);
						}else if(loc == 1){
							docked = 0;
							engaged = 0;
							parked = 1;
							console.log(parked + "" + docked + "" + engaged);
						}else if(loc == 2){
							docked = 1;
							engaged = 0;
							parked = 0;
							console.log(parked + "" + docked + "" + engaged);
						}else{
							docked = 0;
							engaged = 1;
							parked = 0;
							console.log(parked + "" + docked + "" + engaged);
						}
					}

					function updateCubesLow() {
						aCubeL += increment;
						if(aCubeL == -1){
							aCubeL = 0;
						}
						document.getElementById("aCubeLButton").innerHTML = aCubeL;
					}

					function updateCubesMid() {
						aCubeM += increment;
						if(aCubeM == -1){
							aCubeM = 0;
						}
						document.getElementById("aCubeMButton").innerHTML = aCubeM;
					}

					function updateCubesHigh() {
						aCubeH += increment;
						if(aCubeH == -1){
							aCubeH = 0;
						}
						document.getElementById("aCubeHButton").innerHTML = aCubeH;
					}

					function updateConesLow() {
						aConeL += increment;
						if(aConeL == -1){
							aConeL = 0;
						}
						document.getElementById("aConeLButton").innerHTML = aConeL;
					}

					function updateConesMid() {
						aConeM += increment;
						if(aConeM == -1){
							aConeM = 0;
						}
						document.getElementById("aConeMButton").innerHTML = aConeM;
					}

					function updateConesHigh() {
						aConeH += increment;
						if(aConeH == -1){
							aConeH = 0;
						}
						document.getElementById("aConeHButton").innerHTML = aConeH;
					}

					function changeMode(){
							var updateModeToggle = document.getElementById('updateModeToggle');

							if(updateMode){
								increment=-1;
								updateMode = false;
								updateModeToggle.innerHTML="<b><u>Subtracting</u></b>";
							} else{
								increment=1;
								updateMode = true;
								updateModeToggle.innerHTML="<b><u>Adding</u></b>";
							}
							console.log("updateMode: "+updateMode);
						}





					tCubeL = 0;
					tCubeM = 0;
					tCubeH = 0;
					tConeL = 0;
					tConeM = 0;
					tConeH = 0;
					updateModeT = true;
					incrementT = 1;

					function TupdateCubesLow() {
						tCubeL += incrementT;
						if(tCubeL == -1){
							tCubeL = 0;
						}
						document.getElementById("tCubeLButton").innerHTML = tCubeL;
					}

					function TupdateCubesMid() {
						tCubeM += incrementT;
						if(tCubeM == -1){
							tCubeM = 0;
						}
						document.getElementById("tCubeMButton").innerHTML = tCubeM;
					}

					function TupdateCubesHigh() {
						tCubeH += incrementT;
						if(tCubeH == -1){
							tCubeH = 0;
						}
						document.getElementById("tCubeHButton").innerHTML = tCubeH;
					}

					function TupdateConesLow() {
						tConeL += incrementT;
						if(tConeL == -1){
							tConeL = 0;
						}
						document.getElementById("tConeLButton").innerHTML = tConeL;
					}

					function TupdateConesMid() {
						tConeM += incrementT;
						if(tConeM == -1){
							tConeM = 0;
						}
						document.getElementById("tConeMButton").innerHTML = tConeM;
					}

					function TupdateConesHigh() {
						tConeH += incrementT;
						if(tConeH == -1){
							tConeH = 0;
						}
						document.getElementById("tConeHButton").innerHTML = tConeH;
					}

					function changeMode2(){
							var updateModeToggle = document.getElementById('updateModeToggle2');

							if(updateModeT){
								incrementT=-1;
								updateModeT = false;
								updateModeToggle.innerHTML="<b><u>Subtracting</u></b>";
							} else{
								incrementT=1;
								updateModeT = true;
								updateModeToggle.innerHTML="<b><u>Adding</u></b>";
							}
							console.log("updateModeT: "+updateModeT);
						}

				</script>

				<div class="togglebutton" id="reach">
					<h4 id="updateModeToggle2"><b><u>Adding</u></b></h4>
					<label>
						<input id="updateModeT" type="checkbox" onclick="changeMode2()" checked>
					</label>
				</div>	

				<div class = "row" >
					<div class="col-md-5">
						<div class="buttonOnPicture">
							<img src="images/coneScore.jpeg" alt="Snow">
							<button type="button" onClick="TupdateConesLow()" class="btn4" id="bigFont"><a id="tConeLButton" >0</a></button>
							<button type="button" onClick="TupdateConesMid()" class="btn5" id="bigFont"><a id="tConeMButton" >0</a></button>
							<button type="button" onClick="TupdateConesHigh()" class="btn6" id="bigFont"><a id="tConeHButton" >0</a></button>
						</div>
					</div>

					<div class="col-md-5">
						<div class="buttonOnPicture">
							<img src="images/cubeScore.jpeg" alt="Snow">
							<button type="button" onClick="TupdateCubesLow()" class="btn1" id="bigFont"><a id="tCubeLButton" >0</a></button>
							<button type="button" onClick="TupdateCubesMid()" class="btn2" id="bigFont"><a id="tCubeMButton" >0</a></button>
							<button type="button" onClick="TupdateCubesHigh()" class="btn3" id="bigFont"><a id="tCubeHButton" >0</a></button>
						</div>
					</div>
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

				<!--Climb-->
				<a>
				<h3><b><u>Climb:</u></b></h3>
				</a>
				<input type="radio" onClick="climbLocT(0)" name="Climb" value="None"> None </button>
				<input type="radio" onClick="climbLocT(1)" name="Climb" value="Parked"> Parked </button>
				<input type="radio" onClick="climbLocT(2)" name="Climb" value="Docked"> Docked </button>
				<input type="radio" onClick="climbLocT(3)" name="Climb" value="Engaged"> Engaged </button>


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
					<input type="button" value="Submit Data" id="submitButton" class="clearColor" onclick="postwith('')" />


				</div>
			</div>
		</div>
	</div>
	</div>
	</div>
	</div>

	<style>

		.clearColor {
			background-color: rgb(255, 0, 0);
			color: white;
			border-radius: 2px;
			width: 100px;
     		height: 50px;
			font-family: Helvetica;
			font-weight: bold;
			/*To get rid of weird 3D affect in some browsers*/
			border: solid rgb(255, 0, 0);
		}

		/* Container needed to position the button. Adjust the width as needed */
		.buttonOnPicture {
		position: relative;
		width: 50%;
		}

		/* Make the image responsive */
		.buttonOnPicture img {
		width: 100%;
		height: auto;
		}

		/* Style the button and place it in the middle of the container/image */
		.buttonOnPicture .btn1 {
			position: absolute;
			top: 80%;
			left: 70%;
			transform: translate(-50%, -50%);
			-ms-transform: translate(-50%, -50%);
			background-color: rgb(185, 139, 204);
			color: white;
			font-size: 20px;
			padding: 12px 24px;
			border: Purple;
			cursor: pointer;
			border-radius: 5px;
		}

		.buttonOnPicture .btn1:hover {
			background-color: Purple;
			border-color: Purple;
		}

		.buttonOnPicture .btn2 {
			position: absolute;
			top: 48%;
			left: 58%;
			transform: translate(-50%, -50%);
			-ms-transform: translate(-50%, -50%);
			background-color: rgb(185, 139, 204);
			color: white;
			font-size: 16px;
			padding: 12px 24px;
			border: Purple;
			cursor: pointer;
			border-radius: 5px;
		}

		.buttonOnPicture .btn2:hover {
			background-color: Purple;
			border-color: Purple;
		}

		.buttonOnPicture .btn3 {
			position: absolute;
			top: 15%;
			left: 35%;
			transform: translate(-50%, -50%);
			-ms-transform: translate(-50%, -50%);
			background-color: rgb(185, 139, 204);
			color: white;
			font-size: 16px;
			padding: 12px 24px;
			border: Purple;
			cursor: pointer;
			border-radius: 5px;
		}

		.buttonOnPicture .btn3:hover {
			background-color: Purple;
			border-color: Purple;
		}


		.buttonOnPicture .btn4 {
			position: absolute;
			top: 80%;
			left: 70%;
			transform: translate(-50%, -50%);
			-ms-transform: translate(-50%, -50%);
			background-color: rgb(184, 178, 6);
			color: white;
			font-size: 16px;
			padding: 12px 24px;
			border: Yellow;
			cursor: pointer;
			border-radius: 5px;
		}

		.buttonOnPicture .btn4:hover {
			background-color: rgb(230, 180, 14);
			border-color: rgb(230, 180, 14);
		}

		.buttonOnPicture .btn5 {
			position: absolute;
			top: 45%;
			left: 55%;
			transform: translate(-50%, -50%);
			-ms-transform: translate(-50%, -50%);
			background-color: rgb(184, 178, 6);
			color: white;
			font-size: 16px;
			padding: 12px 24px;
			border: Yellow;
			cursor: pointer;
			border-radius: 5px;
		}

		.buttonOnPicture .btn5:hover {
			background-color: rgb(230, 180, 14);
			border-color: rgb(230, 180, 14);
		}

		.buttonOnPicture .btn6 {
			position: absolute;
			top: 15%;
			left: 35%;
			transform: translate(-50%, -50%);
			-ms-transform: translate(-50%, -50%);
			background-color: rgb(184, 178, 6);
			color: white;
			font-size: 16px;
			padding: 12px 24px;
			border: Yellow;
			cursor: pointer;
			border-radius: 5px;
		}

		.buttonOnPicture .btn6:hover {
			background-color: rgb(230, 180, 14);
			border-color: rgb(230, 180, 14);
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
			background-color: Red;
		}
	</style>
</body>

</html>
<?php include("footer.php"); ?>