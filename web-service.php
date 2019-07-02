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

	// get the user id from the cookie 
	if (!isset($_COOKIE['user'])) {
		header('location: login.php');
	}
	$userid = $_COOKIE['user'];

	$userid = "'".$userid."'";

	if($_GET['submit'] === "delete"){// if the user wants to delete a task
		$id = $_GET['id'];
		$query = "DELETE FROM auis_tasks WHERE id = $id ";
		// send the query to the databse to delete the data
		if(mysqli_query($db,$query)){
			echo "{";
			echo '"task":"deleted"';
			echo "}"; 
		}

	}
	if ($_GET['submit'] === "create") { // if the user wants to create a task
		
	// getting all the data from the user
 	$title = mysqli_real_escape_string($db, strip_tags($_GET['title'])) ;
	$title = "'".$title."'";
	$description = nl2br(mysqli_real_escape_string($db,strip_tags($_GET['discription'])));
	$description = "'".$description ."'";
	$due = "'".$_GET['duedate']."'";
	$schedule_date = "'".$_GET['schedule_date']."'";
	$priority = "'".$_GET['priority']."'";
	

	// add data to the table
	$query = "INSERT INTO auis_tasks (id, title, description, due_date, schedule_date, priority, userid) VALUES (NULL, $title, $description, $due, $schedule_date, $priority, $userid)";
	
	if (mysqli_query($db,$query)) {
		// print this if the query worked
		echo "{";
		echo '"task":"saved"';
		echo "}"; 
	}elseif (mysqli_errno($db) == 1146) { // if table does not exist, we create one
		// query to create a table in itw403 database named auis_tasks
		$q = "CREATE TABLE `itw403`.`auis_tasks` (
			  `id` INT NOT NULL AUTO_INCREMENT,
			  `title` VARCHAR(45) NOT NULL,
			  `description` VARCHAR(100) NOT NULL,
			  `due_date` DATE NOT NULL,
			  `schedule_date` DATE NOT NULL,
			  `priority` VARCHAR(45) NOT NULL,
			  userid INT NOT NULL,
			  PRIMARY KEY (`id`),
			  FOREIGN KEY (userid) REFERENCES users(userid));";
			 
		mysqli_query($db,$q);
		mysqli_error($db);
		// inserting the data into the table after creating it
		if (mysqli_query($db,$query)) {
		 	echo "{";
			echo '"task":"saved"';
			echo "}"; 
		 }  
	}else{
		echo "{";
		$msg = mysqli_error($db);
			echo '"task":"'.$msg.'"';
			echo "}"; 
		
	}
	
	}// end if the user wants to create a task
	if ($_GET['submit'] == "change") {// if the user updates a task
		$id = $_GET['id'];
		$query = "UPDATE auis_tasks set id= $id";
			// add where statements based on what entered
			if ($_GET['title'] != NULL) {
				$title = "'".trim($_GET['title'])."'";
				$query = $query.", title = $title ";
			}
			if (trim($_GET['discription']) != NULL) {
				$description = "'".$_GET['discription']."'";
				$query = $query.", description = $description ";
			}
			if (trim($_GET['duedate']) != NULL) {
				$duedate = "'".$_GET['duedate']."'";
				$query = $query.", due_date = $duedate ";
			}
			if (trim($_GET['priority']) != NULL) {
				$priority = "'".$_GET['priority']."'";
				$query = $query." , priority = $priority";
			}
		$query = $query." WHERE id = \"".$id."\"";
		// print("query");
		// print $query;
		if (mysqli_query($db,$query)) {
		 	echo "{";
			echo '"task":"updated"';
			echo "}"; 
		 
	}else{
		
		$msg = mysqli_error($db);
		echo "{";
		echo '"task":"'.$msg.'"';
		echo "}"; 
		
	}
		
	}
	if ($_GET['submit'] == "view") {
		$query = "SELECT * FROM auis_tasks WHERE userid = $userid";
		$q ="SELECT COUNT(*) FROM auis_tasks WHERE userid = $userid";

		// send t he query to the databse
			$result = mysqli_query($db,$query);			 
			$num_of_rows = mysqli_query($db,$q);
			$number = mysqli_fetch_array($num_of_rows);
			print('{"lenght":"');
			print $number['COUNT(*)'];
			print('",');
			$counter = 0;
			print('"data":[');
			while ($row = @mysqli_fetch_array($result)) {
					print("{");
					print('"id":"');
					print $row['id'];
					print('","title":"');
					print $row['title'];
					print('","description":"');
					print $row['description'];
					print('","duedate":"');
					print $row['due_date'];
					print('","priority":"');
					print $row['priority'];
					print('"}');
					if ($counter === ($number['COUNT(*)'] - 1)) {
						
					}else{
						print(",");
					}
				$counter++;
			 } 
			print("]}");

	}
	if ($_GET['submit'] == "search") { // if the user wants to search for tasks
		// getting the data
		$result;
		$query = "SELECT * FROM auis_tasks WHERE 1=1";
		$q ="SELECT COUNT(*) FROM auis_tasks WHERE 1=1";
			// add where statements based on what entered
			if ($_GET['title'] != NULL) {
				$title = "'%".trim($_GET['title'])."%'";
				$query = $query."  and title LIKE $title ";
				$q = $q."  and title LIKE $title ";
			}
			if (trim($_GET['discription']) != NULL) {
				$description = "'%".$_GET['discription']."%'";
				$query = $query."and description LIKE $description ";
				$q = $q."and description LIKE $description ";
			}
			if (trim($_GET['duedate']) != NULL) {
				$duedate = "'".$_GET['duedate']."'";
				$query = $query."and due_date <= $duedate ";
				$q = $q."and due_date <= $duedate ";
			}
			if (trim($_GET['priority']) != NULL) {
				$priority = "'".$_GET['priority']."'";
				$query = $query." and priority = $priority";
				$q = $q." and priority = $priority";
			}
			// add the userid to the query
			$query = $query." and userid = $userid";
			$q = $q." and userid = $userid";
			
			// send t he query to the databse
			$result = mysqli_query($db,$query);			 
			$num_of_rows = mysqli_query($db,$q);
			$number = mysqli_fetch_array($num_of_rows);
			print('{"lenght":"');
			print $number['COUNT(*)'];
			print('",');
			$counter = 0;
			print('"data":[');
			while ($row = @mysqli_fetch_array($result)) {
					print("{");
					print('"id":"');
					print $row['id'];
					print('","title":"');
					print $row['title'];
					print('","description":"');
					print $row['description'];
					print('","duedate":"');
					print $row['due_date'];
					print('","priority":"');
					print $row['priority'];
					print('"}');
					if ($counter === ($number['COUNT(*)'] - 1)) {
						
					}else{
						print(",");
					}
				$counter++;
			 } 
			print("]}");
	}// end of search
	if (isset($_GET['reset'])) {
		$email = $_GET['email'];
		$pass = rand();
		mail($email, "reset password", "Your newpassword is: ".$pass);
		$email = "'".$_GET['email']."'";
		$q = "UPDATE users set password= $pass WHERE email=$email";
		mysqli_query($db,$q);
		
		echo "{";
		echo '"password":"'.$pass.'"';
		echo "}"; 
	}

?>