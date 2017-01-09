<?php

	require_once("../../config.php");
	
	function getSingleCarData($edit_id){
    
        $database = "if16_ingomagi";

		//echo "id on ".$edit_id;
		
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
		
		$stmt = $mysqli->prepare("SELECT plate, color, masinatyyp, comment FROM motikad WHERE id=? AND deleted IS NULL ");

		$stmt->bind_param("i", $edit_id);
		$stmt->bind_result($plate, $color, $masinatyyp, $comment);
		$stmt->execute();
		
		//tekitan objekti
		$car = new Stdclass();
		
		//saime ühe rea andmeid
		if($stmt->fetch()){
			// saan siin alles kasutada bind_result muutujaid
			$car->plate = $plate;
			$car->color = $color;
			$car->color = $masinatyyp;
			$car->color = $comment;
			
			
		}else{
			// ei saanud rida andmeid kätte
			// sellist id'd ei ole olemas
			// see rida võib olla kustutatud
			header("Location: data.php");
			exit();
		}
		
		$stmt->close();
		$mysqli->close();
		
		return $car;
		
	}


	function updateCar($id, $plate, $color, $masinatyyp, $comment){
    	
        $database = "if16_ingomagi";

		
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
		
		$stmt = $mysqli->prepare("UPDATE motikad SET plate=?, color=?, masinatyyp=?, comment=? WHERE id=? AND deleted IS NULL");
		$stmt->bind_param("ssssi",$plate, $color, $masinatyyp, $comment, $id);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "salvestus õnnestus!";
		}
		
		$stmt->close();
		$mysqli->close();
		
	}
	
	function deleteCar($id){
    	
        $database = "if16_ingomagi";

		
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
		
		$stmt = $mysqli->prepare("UPDATE motikad SET deleted=NOW() WHERE id=?");
		$stmt->bind_param("i",$id);
		
		// kas õnnestus salvestada
		if($stmt->execute()){
			// õnnestus
			echo "salvestus õnnestus!";
		}
		
		$stmt->close();
		$mysqli->close();
		
	}
	
	
?>