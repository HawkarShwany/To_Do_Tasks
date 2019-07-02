<?php
// connect to the database

	$server = '127.0.0.1';
	$username = 'root';
	$password = '0000';
	$dbname = 'itw403'; // itw403 was databse name in my system, yours may be different
	$db = @mysqli_connect($server,$username,$password,$dbname);
	
	if (!$db) {
		 	print("error: could not connect to the databse");
	} 

	if ($_GET['submit'] === "login") {

		$username = "'".$_GET['user']."'";
		$q = "SELECT * FROM users WHERE username = $username";
		$result = mysqli_query($db,$q);
		//print($result);
		
		print(mysqli_error($db));
		// check if the user exists
		if (@count($result) == 0  ) {
			// if username doesn't exists redirect to register
			print("{\"data\":");
			print("\"username does not exist. please register\"}");
			
		}else{
			// if user exists, check if password is correct
			$row = mysqli_fetch_array($result);
			if ($_GET['pass'] === $row['password']) {
				
				print("{\"data\":");
				print("\"correct\",\"id\":\"");
				print($row['userid']."\"}");
			}else{
				print("{\"data\":");
				print("\"incorrect password.".$row['password']." please inter a correct password\"}");
			}

		}

	}// end login

	if ($_GET['submit'] === "register") {
		
		$fullname = "'".$_GET['fullname']."'";
		$username = "'".$_GET['username']."'";
		$email = "'".$_GET['email']."'";
		$password = "'".$_GET['password']."'";

		$insert_query = "INSERT INTO users (username,password,email, fullname) VALUES ($username, $password, $email, $fullname)";
		$query = "CREATE TABLE `itw403`.`users` (
			userid INT NOT NULL AUTO_INCREMENT,
			username VARCHAR(45) UNIQUE NOT NULL,
			password VARCHAR(100) NOT NULL,
			email VARCHAR(45) UNIQUE NOT NULL,
			fullname VARCHAR(45) NOT NULL,
			PRIMARY KEY (userid)
			)";

		if (mysqli_query($db,$insert_query)) {
		// print this if the query worked
			print("{\"data\":");
			print("\"registered\"}");
			
		print(mysqli_error($db));
		print(mysqli_error($db));
		}elseif ( mysqli_errno($db) == 1146) { // if table does not exist, we create one
			mysqli_query($db,$query);
			print(mysqli_error($db));
			// inserting the data into the table after creating it
			mysqli_query($db,$insert_query);  
			print("{\"data\":");
			print("\"registered\"}");
			print(mysqli_error($db));
		}elseif (mysqli_errno($db) == 1062) {
			// if the email is used by another user
			print("{\"data\":");
			print("\"registered\"}");
			print(mysqli_error($db));
		}else{
			print(mysqli_error($db));
		}

	}// end register
?>