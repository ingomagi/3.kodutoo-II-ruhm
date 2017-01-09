<?php

	
	require("functions.php");
	if (isset($_SESSION["userId"])){
		

		header("Location: data.php");
		exit();
	}

	$signupPasswordError = "";
	$signupEmailError = "";
	$genderError = "";
	$ageError = "";
	$langError = "";
	$signupEmail="";
	$signupAge="";

	//on üldse olemas
	if(isset($_POST["signupEmail"]))
	{
		//jah on olemas
		//kas on tühi
		if(empty($_POST["signupEmail"]))
		{
			$signupEmailError = "see väli on kohustuslik";
		}else
			{ 
			$signupEmail=$_POST["signupEmail"];
			}
	}
	if(isset($_POST["signupPassword"]))
	{
				if(empty($_POST["signupPassword"]))
				{
			$signupPasswordError = "see väli on kohustuslik";
		} else{ 
			if (strlen ($_POST["signupPassword"])<8)
			{
			
				$signupPasswordError = "parool on lühem kui 8 märki";
			}
		}
	}	
	
	if(isset($_POST['signupGender'])) 
	{
		if(empty($_POST["signupGender"]))
		{
		$genderError = " Unustasite valida oma soo";
		}
	}
	if(isset($_POST['signupAge'])) 
	{
		if(empty($_POST["signupAge"]))
		{
		$ageError = " Unustasite sisestada oma sünnipäeva";
		}
	}
	
	// peab olema email ja parool
	//ja ühtegi errorit ei olema
	if (  $signupEmailError == "" 
			&&
			$signupPasswordError == ""
			&&
			isset($_POST["signupEmail"])
			&&
			isset($_POST["signupPassword"])
			&&
			isset($_POST["signupGender"])
			&&
			isset($_POST["signupAge"])
			
			
		)
		{
			//salvestame andmebaasi
			//echo "email: ".$signupEmail. "<br>";
			$age=$_POST["signupAge"];
			//echo "vanus: ".$age. "<br>";
			
			//echo "password: ".$_POST["signupPassword"]."<br>";
			$password = hash("sha512", $_POST["signupPassword"]);
			$gender=$_POST["signupGender"];
			//echo "sugu: ".$gender. "<br>";
			$language=$_POST["Language"];
			//echo "sugu: ".$language. "<br>";
			//echo "password hashed: ".$password."<br>";
			
			signUp($signupEmail, $password, $gender, $age, $language);
		}
		$error ="";
	if ( isset($_POST["loginEmail"]) && isset($_POST["loginPassword"]) && 
		!empty($_POST["loginEmail"]) && !empty($_POST["loginPassword"])
	  ) {
		  
		$error = login($_POST["loginEmail"], $_POST["loginPassword"]);
		
	}
  ?>
 

 
 
 
<!DOCTYPE html>
<html>
<head>
<title>Logi sisse või loo kasutaja</title>
</head>
<body>
<body bgcolor="pink">
<h1>Logi sisse</h1>

<form method="POST">
  <input name="loginEmail" placeholder = "e-mail" type ="e-mail"> <br><br>
  <input name="loginPassword" placeholder = "parool" type ="password"> <br><br>
    <input type="submit" value="logi sisse">
  
</form>

<h1>Loo kasutaja</h1>

<form method="POST">

  <input name="signupEmail" placeholder = "e-mail" type ="e-mail" value="<?php echo $signupEmail;?>"> <?php echo $signupEmailError;?> <br><br>
  <input name="signupPassword" placeholder = "parool" type ="password"> <?php echo $signupPasswordError;?>  <br><br>

  valige oma sugu
  <select name="signupGender" type="signupGender">
	<option value="">...</option>
	<option value="mees">Mees</option>
	<option value="naine">Naine</option>
	<option value="meesnaine">muu</option>
  </select><?php echo $genderError;?>  <br><br>
  Sisestage oma sünnipäev<br> 
  <input name="signupAge" placeholder = "päev/kuu/aasta" type ="age"value="<?php echo $signupAge;?>">
  <?php echo $ageError;?>  <br><br>
	Valige oma eelistatud suhtlus keel: <br>
	<input type="radio" name="Language" value="EST" checked />EST
	<input type="radio" name="Language" value="ENG"/>ENG
	<?php echo $langError;?>
  <br><br><input type="submit" value="loo kasutaja">
  
</form> 




</body>
</html>