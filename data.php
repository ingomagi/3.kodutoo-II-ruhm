<?php 
	
	require("functions.php");
	
	$commentError = "";
	$comment= "";
	//kui ei ole kasutaja id'd
	if (!isset($_SESSION["userId"])){
		
		//suunan sisselogimise lehele
		header("Location: login.php");
		exit();
	}
	
	//kui on ?logout aadressireal siis login välja
	if (isset($_GET["logout"])) {
		
		session_destroy();
		header("Location: login.php");
		exit();
	}
	$msg="";
	if(isset($session["message"])){
		$msg = $_SESSION["message"];
		unset($_SESSION["message"]);
	}

	
	
	
	if 	(isset($_POST["plate"])&&
		isset($_POST["color"])&&
		isset($_POST["masinatyyp"])&&
		isset($_POST["comment"])&&
		!empty($_POST["plate"])&&
		!empty($_POST["masinatyyp"])&&
		!empty($_POST["comment"])&&
		!empty($_POST["color"])&&
		strlen ($_POST["comment"])<255
		) {
		saveCar(cleanInput($_POST["plate"]), $_POST["color"], $_POST["masinatyyp"], $_POST["comment"]);
	}
	$masinainfo = geteveryCars();	
	if (isset($_POST["comment"])&&
		(strlen ($_POST["comment"])>255))
		{
				$commentError = "tekst on suurem kui lubatud";
			}
			
			
	if(isset($_GET["sort"]) && isset($_GET["direction"])){
		$sort = $_GET["sort"];
		$direction = $_GET["direction"];
	} else{
		$sort = "id";
		$direction = "ascending";
		
	}
	//echo $sort." ".$direction;
	if(isset($_GET["q"])){
	$q=(CleanInput($_GET["q"]));
	$carData = getAllCars($q, $sort, $direction);
	
	} else {
		$q = "";
		$carData = getAllCars($q, $sort, $direction);
		
	}
			
?>


<h1>Data</h1>
<?=$msg;?>
<body bgcolor="#e6ffe6">
<p>
	Tere tulemast <?=$_SESSION["userEmail"];?>!</a>
	<a href="?logout=1">Logi välja</a>
</p>


 <br> <br> 

  
  <form>
			<input type="search" name="q">
			<input type="submit" value="Otsi">
		</form>
		<?php 
			
			$direction = "ascending";
			if(isset($_GET["direction"])){
				if($_GET["direction"] == "ascending"){
					$direction = "decending";
				}
			}
			$html = "<table  style=' border: 1px solid #dddddd; text-align: left; padding: 8px;';>";
			
			$html .= "<tr>";
				$html .= "<th><a href='?q=".$q."&sort=id&direction=".$direction."'>id</th>";
				$html .= "<th><a href='?q=".$q."&sort=plate&direction=".$direction."'>plate</th>";
				$html .= "<th><a href='?q=".$q."&sort=color&direction=".$direction."'>color</th>";
				$html .= "<th><a href='?q=".$q."&sort=masinatyyp&direction=".$direction."'>masinatyyp</th>";
				$html .= "<th><a href='?q=".$q."&sort=comment&direction=".$direction."'>comment</th>";
			$html .= "</tr>";
			
			//iga liikme kohta massiivis
			foreach($carData as $c){
				// iga auto on $c
				//echo $c->plate."<br>";
				
				$html .= "<tr>";
					$html .= "<td>".$c->id."</td>";
					$html .= "<td>".$c->plate."</td>";
					$html .= "<td style='background-color:".$c->Color."'>".$c->Color."</td>";
					$html .= "<td>".$c->masinatyyp."</td>";
					$html .= "<td>".$c->comment."</td>";
					$html .= "<td><a class='btn btn-default' href='edit.php?id=".$c->id."'><span class='glyphicon glyphicon-wrench'>Muuda</span></a></td>";
					
				$html .= "</tr>";
			}
			
			$html .= "</table>";
			
			echo $html;
			
			$listHtml = "<br><br>";
			
			echo $listHtml;
	?>
	<br>
	<form method="POST">
	Sisestage kommentaar oma pakutava masina kohta (255 char pikkus): <br>
	<textarea name="comment" rows="5" cols="55" value="<?php echo $comment;?>" ></textarea><?php echo $commentError;?>  <br><br>
	<select name="masinatyyp" type="masinatyyp">
	<option value="">...</option>
	<option value="klassikaline">Klassikaline</option>
	<option value="Bike">Bike</option>
	<option value="mopeed">mopeed</option>
	<option value="Cruiser">Cruiser</option>
	<option value="Enduro">Enduro</option>
	<option value="Touring">Touring</option> 
	</select> <br><br> 
	<input name="plate" placeholder = "numbri märk" type ="text" value=""><br><br>
	Valige passis olev masina värv: <br>
   <input type="color" name="color"><br><br>
   <input type="submit" value="Sisesta">
  </form>