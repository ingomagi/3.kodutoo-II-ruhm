<?php
	//edit.php
	require("functions.php");
	require("editFunctions.php");
	
	//kas kasutaja uuendab andmeid
	//if(isset($_POST["delete"])){

		
		
	if(isset($_POST["update"])){
		
		updateCar(cleanInput($_POST["id"]), cleanInput($_POST["plate"]), cleanInput($_POST["color"]), cleanInput($_POST["masinatyyp"]), cleanInput($_POST["comment"]));
		
		//header("Location: edit.php?id=".$_POST["id"]."&success=true");
        //exit();	
		
	} elseif(isset($_POST["deleted"])){
	
		deleteCar ($_POST["id"]);
		
	}
	
	if(!isset($_GET["id"])){
		header("Location: data.php");
		exit();
	}
	
	//saadan kaasa id
	$c = getSingleCarData($_GET["id"]);
	

	
?>
<br><br>
<a href="data.php"> tagasi </a>

<h2>Muuda kirjet</h2>
  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" >
	<input type="hidden" name="id" value="<?=$_GET["id"];?>" > 
	<textarea name="comment" rows="5" cols="55" value="<?php echo $comment;?>" ></textarea>  <br><br>
	<select name="masinatyyp" type="masinatyyp">
	<option value="">...</option>
	<option value="klassikaline">Klassikaline</option>
	<option value="Bike">Bike</option>
	<option value="mopeed">mopeed</option>
	<option value="Cruiser">Cruiser</option>
	<option value="Enduro">Enduro</option>
	<option value="Touring">Touring</option> 
	</select> <br><br> 
  	<label for="number_plate" >auto nr</label><br>
	<input id="number_plate" name="plate" type="text" value="<?php echo $c->plate;?>" ><br><br>
  	<label for="color" >värv</label><br>
	<input id="color" name="color" type="color" value="<?=$c->color;?>"><br><br>
  	<input type="submit" name="update" value="Salvesta">
	<br><br><br><br>
	<input type="submit" name="deleted" value="Kustuta">
  </form>
  
  