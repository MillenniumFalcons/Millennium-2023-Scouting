<html>
<?php
	  include("header.php");
	  include("navBar.php");?>
<body>

<script src="Chart.js"></script>
<script src="jquery.js"></script>

<div id="content">
<div class="container row-offcanvas row-offcanvas-left">
<div class="well column  col-lg-112  col-sm-12 col-xs-12" id="content">


<h1>Head Scout Raw Data</h1>

<?php
       include("databaseLibrary.php");
       $result = getAllHeadScoutData();
        $i=0;

       echo('<div style="border:1px solid black;overflow-y:hidden;overflow-x:scroll;"><table  class="sortable table table-hover" id="RawData" border="1">');
       foreach ($result as $row_key => $row){
               if($i==0){
                       echo("<tr>");
                       foreach ($row as $key => $value){
                                    if(!is_numeric($key)){
                                       echo("<td>".$key."</td>");
                               }
                       }
                       $i++;
                       echo("</tr>");
               }
               echo("<tr>");
                    foreach ($row as $key => $value){
                            if(!is_numeric($key)){
								if($key == "matchNum"){
                                            $value= '<a href="matchData.php?match=".$value."">'.$value.'</a>';
									}
                                    if($key == "team1"){
                                            $value= '<a href="teamData.php?team='.$value.'">'.$value.'</a>';
									}
									if($key == "team2"){
                                            $value= '<a href="teamData.php?team='.$value.'">'.$value.'</a>';
									}
									if($key == "team3"){
                                            $value= '<a href="teamData.php?team='.$value.'">'.$value.'</a>';
									}
									if($key == "team4"){
                                            $value= '<a href="teamData.php?team='.$value.'">'.$value.'</a>';
									}
									if($key == "team5"){
                                            $value= '<a href="teamData.php?team='.$value.'">'.$value.'</a>';
									}
									if($key == "team6"){
                                            $value= '<a href="teamData.php?team='.$value.'">'.$value.'</a>';
									}
                               echo("<td align='center'>".$value."</td>");
                       }
               }
               echo("</tr>");
            }
            echo("</table>");
?>

</div>
</div>
</div>

</body>
</html>
<?php include("footer.php"); ?>
