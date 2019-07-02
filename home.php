<?php
if (!isset($_COOKIE['user'])) {
		header('location: login.php');
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>home</title>
	<style type="text/css">
		body{
			background-color: #3e3838;
			color:#ae7c7c;
			text-height: 10px;
			font-family: sans-serif;
		}
		table,th,td{
			border-bottom: 1px solid;			
			padding: 10px;
			padding-bottom: 0;
			border-collapse: collapse;
		}
		button{
			width: 100px;
			margin: 10px;
			border: none; 
			padding: 3px;
		}
		input{
			padding:3px;
			margin: 10px;
		}
	</style>
</head>
<body>
	<center>
<div id="nav">
	<button onclick="createTask()">create tasks</button>
	<button onclick="search()">search tasks</button>
	<button onclick="view()">View tasks</button>
</div>
<div id="body">
		
</div>
</center>
<script type="text/javascript">
	function view(){ // this function shows all the tasks
		var xhttp = new XMLHttpRequest();
		xhttp.open("GET", "./web-service.php?submit=view", true);
		xhttp.send();
		xhttp.onreadystatechange = function(){
			var table = "<table><tr><th>Title</th> <th>Description</th> <th>Due Date</th> <th>Priority</th></tr><tr>";
			if (this.readyState == 4 && this.status == 200) {
				var json = JSON.parse(this.responseText);
				for(var i =0 ; i < json.lenght ; i++){
					var id = json.data[i].id ;
					table += "<tr><td>";
					table += json.data[i].title ;
					table += "</td><td>";
					table += json.data[i].description ;
					table += "</td><td>";
					table += json.data[i].duedate ;
					table += "</td><td>";
					table += json.data[i].priority ;
					table += "</td><td>";
					table += "<button value=\"";
					table += id;
					table += "\" onclick=\"update(this.value)\">update</button>";
					table += "</td><td>";
					table +=  "<button value=\""
					table += id;
					table += "\" onclick=\"del(this.value)\">delete</button>";
					table += "</td><td></tr>";
				}
				table += "</table>";
				document.getElementById("body").innerHTML = table;
			}else{
				document.getElementById("body").innerHTML = "could not find anything"+ this.readyState +" "+this.responseText;
			}
		}	
	}
	function search(){ // this function dispalys the search inputs so the user searchs for his tasks
		var x = "<h2>Search for a task</h2>";
		x += "<label>Title:</label><br>";
		x += "<input type=\"text\" id=\"title\" ><br>";
		x += "<label>Discription:</label><br>";
		x += "<textarea id=\"discription\" ></textarea><br>";
		x += "<label>Due Date: (within)</label><br>";
		x += "<input type=\"Date\" id=\"duedate\" ><br>";
		x += "<label>Priority:</label>";
		x += "<select id=\"priority\">";
		x += "<option></option>";
		x += "<option>A</option>";
		x += "<option >B</option>";
		x += "<option >C</option>";
		x += "</select><br>";
		x += "<button onclick=\"searchTasks()\">Search</button>";
		document.getElementById("body").innerHTML = x;


	}
	function searchTasks(){// this function gets the values inside the inputs and displays the search results
		var title = document.getElementById('title').value;
		var discription = document.getElementById('discription').value;
		var duedate = document.getElementById('duedate').value;
		var priority = document.getElementById('priority').value;
		var link = "?title="+title+"&discription="+discription+"&duedate="+duedate+"&priority="+priority+"&submit=search";

		var xhttp = new XMLHttpRequest();
		xhttp.open("GET", "./web-service.php" + link, true);
		xhttp.send();
		xhttp.onreadystatechange = function(){
			var table = "<table><tr><th>Title</th> <th>Description</th> <th>Due Date</th> <th>Priority</th></tr><tr>";
			if (this.readyState == 4 && this.status == 200) {
				var json = JSON.parse(this.responseText);
				for(var i =0 ; i < json.lenght ; i++){
					var id = json.data[i].id ;
					table += "<tr><td>";
					table += json.data[i].title ;
					table += "</td><td>";
					table += json.data[i].description ;
					table += "</td><td>";
					table += json.data[i].duedate ;
					table += "</td><td>";
					table += json.data[i].priority ;
					table += "</td><td>";
					table += "<button value=\"";
					table += id;
					table += "\" onclick=\"update(this.value)\">update</button>";
					table += "</td><td>";
					table +=  "<button value=\""
					table += id;
					table += "\" onclick=\"del(this.value)\">delete</button>";
					table += "</td><td></tr>";
				}
				table += "</table>";
				document.getElementById("body").innerHTML = table;
			}else{
				document.getElementById("body").innerHTML = "could not find anything"+ this.readyState +" "+this.responseText;
			}
		}	

	}
	function update(id){
		var x = "<h2>Update your task</h2>";
		x += "<label>Title:</label><br>";
		x += "<input id=\"title\" type=\"text\" ><br>";
		x += "<label>Discription:</label><br>";
		x += "<textarea id=\"discription\" ></textarea><br>";
		x += "<label>Due Date:</label><br>";
		x += "<input type=\"Date\" id=\"duedate\" ><br>";
		x += "<label>Schedule Date:</label><br>";
		x += "<input type=\"date\" id=\"schedule_date\" ><br>";
		x += "<label>Priority:</label>";
		x += "<select id=\"priority\" >";
		x += "<option></option>";
		x += "<option >A</option>";
		x += "<option >B</option>";
		x += "<option >C</option>";
		x += "</select><br>";
		x += "<button value=\"";
		x += id;
		x += "\" onclick=\"chang(this.value)\">Update</button>";
		document.getElementById("body").innerHTML = x;
	}
	function chang(id){
		var title = document.getElementById('title').value;
		var discription = document.getElementById('discription').value;
		var duedate = document.getElementById('duedate').value;
		var schedule_date = document.getElementById('schedule_date').value;
		var priority = document.getElementById('priority').value;
		var link = "?title="+title+"&discription="+discription+"&duedate="+duedate+"&schedule_date="+schedule_date+"&priority="+priority+"&id="+id+"&submit=change";

		var xhttp = new XMLHttpRequest();
		xhttp.open("GET", "./web-service.php" + link, true);
		xhttp.send();
		xhttp.onreadystatechange = function(){
				if (this.readyState == 4 && this.status == 200) {
					var json = JSON.parse(this.responseText);
					document.getElementById("body").innerHTML = json.task;
				}else{
					document.getElementById("body").innerHTML = this.readyState +" "+this.responseText;
				}
		}	
	}
	
	function del(id){
		var link = "?id="+id+"&submit=delete";
		var xhttp = new XMLHttpRequest();
		xhttp.open("GET", "./web-service.php" + link, true);
		xhttp.send();
		xhttp.onreadystatechange = function(){
			if (this.readyState == 4 && this.status == 200) {
				var json = JSON.parse(this.responseText);
				document.getElementById('body').innerHTML = json.task ;
				document.getElementById('body').innerHTML = searchTasks() ;

			}else{
				document.getElementById("body").innerHTML = "could not delete"+ this.readyState +" "+this.responseText;
			}
		}
	}
	function submit(){ // this function gets the values inside the inputs and sends them to the WS to be submitted to the database
		var title = document.getElementById('title').value;
		var discription = document.getElementById('discription').value;
		var duedate = document.getElementById('duedate').value;
		var schedule_date = document.getElementById('schedule_date').value;
		var priority = document.getElementById('priority').value;
		var link = "?title="+title+"&discription="+discription+"&duedate="+duedate+"&schedule_date="+schedule_date+"&priority="+priority+"&submit=create";

		var xhttp = new XMLHttpRequest();
		xhttp.open("GET", "./web-service.php" + link, true);
		xhttp.send();
		xhttp.onreadystatechange = function(){
				if (this.readyState == 4 && this.status == 200) {
					var json = JSON.parse(this.responseText);
					document.getElementById("body").innerHTML = json.task;
				}else{
					document.getElementById("body").innerHTML = this.readyState +" "+this.responseText;
				}
		}	
	}
	function createTask(){// this function displays the inputs to make a task
		var x = "<h2>Create a task</h2>";
		x += "<label>Title:</label><br>";
		x += "<input id=\"title\" type=\"text\" ><br>";
		x += "<label>Discription:</label><br>";
		x += "<textarea id=\"discription\" ></textarea><br>";
		x += "<label>Due Date:</label><br>";
		x += "<input type=\"Date\" id=\"duedate\" ><br>";
		x += "<label>Schedule Date:</label><br>";
		x += "<input type=\"date\" id=\"schedule_date\" ><br>";
		x += "<label>Priority:</label>";
		x += "<select id=\"priority\" >";
		x += "<option></option>";
		x += "<option >A</option>";
		x += "<option >B</option>";
		x += "<option >C</option>";
		x += "</select><br>";
		x += "<button onclick=\"submit()\">Submit</button>";
		document.getElementById("body").innerHTML = x;
	}
	
</script>
</body>
</html>