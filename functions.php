<?php
	// functions.php
	//var_dump($GLOBALS);
	require("../../config.php");
	// see fail, peab olema kıigil lehtedel kus 
	// tahan kasutada SESSION muutujat
	session_start();
	
	//***************
	//**** SIGNUP ***
	//***************
	
	function signUp ($email, $password, $gender, $age, $langlang) {
		
		$database = "if16_ingomagi";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
		$stmt = $mysqli->prepare("INSERT INTO user_sample (email, password, gender, age, language) VALUES (?, ?, ?, ?, ?)");
	
		echo $mysqli->error;
		
		$stmt->bind_param("sssss", $email, $password, $gender, $age, $langlang);
		echo $mysqli->error;
		
		if($stmt->execute()) {
			echo "salvestamine ınnestus";
		} else {
		 	echo "ERROR ".$stmt->error;
		}
		
		$stmt->close();
		$mysqli->close();
		
	}
	function login ($email, $password) {
		
		$error = "";
		
		$database = "if16_ingomagi";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
		$stmt = $mysqli->prepare("
		SELECT id, email, password, created 
		FROM user_sample
		WHERE email = ?");
	
		echo $mysqli->error;
		
		//asendan k¸sim‰rgi
		$stmt->bind_param("s", $email);
		
		//m‰‰ran v‰‰rtused muutujatesse
		$stmt->bind_result($id, $emailFromDb, $passwordFromDb, $created);
		$stmt->execute();
		
		//andmed tulid andmebaasist vıi mitte
		// on tıene kui on v‰hemalt ¸ks vaste
		if($stmt->fetch()){
			
			//oli sellise meiliga kasutaja
			//password millega kasutaja tahab sisse logida
			$hash = hash("sha512", $password);
			if ($hash == $passwordFromDb) {
				
				echo "Kasutaja logis sisse ".$id;
				
				//m‰‰ran sessiooni muutujad, millele saan ligi
				// teistelt lehtedelt
				$_SESSION["userId"] = $id;
				$_SESSION["userEmail"] = $emailFromDb;
				$_SESSION["message"]= "<h1>Tere tulemast!</h1>";
				header("Location: data.php");
				exit();
			}else {
				$error = "vale parool";
			}
			
			
		} else {
			
			// ei leidnud kasutajat selle meiliga
			$error = "ei ole sellist emaili";
		}
		
		return $error;
		
	}
	
	function saveCar ($plate, $color, $masinatyyp, $comment) {
		
		$database = "if16_ingomagi";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
		$stmt = $mysqli->prepare("INSERT INTO motikad (plate, color, masinatyyp, comment) VALUES (?, ?, ?, ?)");
	
		echo $mysqli->error;
		
		$stmt->bind_param("ssss", $plate, $color, $masinatyyp, $comment);
		
		if($stmt->execute()) {
			echo "salvestamine ınnestus";
		} else {
		 	echo "ERROR ".$stmt->error;
		}
		
		$stmt->close();
		$mysqli->close();
		
	}
	function getAllCars($q, $sort, $direction) {
		$database = "if16_ingomagi";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
		
		$allowedsortoptions = ["id", "plate", "color", "masinatyyp", "comment"];
		if (!in_array($sort, $allowedsortoptions)){
			$sort= "id";
		}
		$orderby = "ASC";
		if($direction == "decending"){
			$orderby= "DESC";
		}
		
		//echo $sort." ".$orderby;
		
		if ($q== ""){
			//echo "ei otsi";
			
		$stmt = $mysqli->prepare("
			SELECT id, plate, color, masinatyyp, comment
			FROM motikad
			WHERE deleted IS NULL
			ORDER BY $sort $orderby
		");
		} else {
		//echo "otsib:  " .$q;
		$searchword = "%".$q."%";
		
		$stmt = $mysqli->prepare("
			SELECT id, plate, color, masinatyyp, comment
			FROM motikad
			WHERE deleted IS NULL AND
			(plate LIKE ? OR color LIKE ? OR masinatyyp LIKE ? OR comment LIKE ?)
			ORDER BY $sort $orderby
		");
		$stmt->bind_param("ssss",$searchword, $searchword, $searchword, $searchword);
		}
	
		echo $mysqli->error;
		
		$stmt->bind_result($id, $plate, $color, $masinatyyp, $comment);
		$stmt->execute();
		
		
		//tekitan massiivi
		$result = array();
		
		// tee seda seni, kuni on rida andmeid
		// mis vastab select lausele
		while ($stmt->fetch()) {
			
			//tekitan objekti
			$car = new StdClass();
			
			$car->id = $id;
			$car->plate = $plate;
			$car->Color = $color;
			$car->masinatyyp = $masinatyyp;
			$car->comment = $comment;
			//echo $plate."<br>";
			// iga kord massiivi lisan juurde nr m‰rgi
			array_push($result, $car);
		}
		
		$stmt->close();
		
		
		return $result;
	}
	
	
	function getEveryCars() {
		 $database = "if16_ingomagi";
		 $mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
	 $stmt = $mysqli->prepare("
	
		 SELECT id, plate, color, comment, masinatyyp
		 FROM motikad
		 ");
	
	
	 $stmt->bind_result($id, $plate, $color, $comment, $masinatyyp);
	 $stmt->execute();
	 $result = array();
	 while($stmt->fetch()) {
		//echo $plate."<br>";	
		 $car = new stdClass();
		 $car->id = $id;
		 $car->plate = $plate;
		 $car->color = $color;
		 $car->masinatyyp = $masinatyyp;
		 $car->comment = $comment;
		
	 array_push($result, $car);
	 }  
	 $stmt->close();
	 $mysqli->close();
	 return $result;
	 }
	
	
	function cleanInput($input){
		
		$input = trim($input);
		$input = htmlspecialchars($input);
		$input = stripslashes($input);
		
		return $input;
		
		
		}
?>