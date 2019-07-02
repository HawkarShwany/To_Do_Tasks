<!DOCTYPE html>
<html>
<head>
	<title>login</title>
	<style type="text/css">
		body{
			background-color: #3e3838;
			color:#ae7c7c;
			text-height: 10px;
			font-family: sans-serif;
		}
		p{
			font-size: 0.7em;
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
<div id="register">
	<h2>Register here<h2>
	<input id="fullname" type="text" name="fullname" placeholder="fullname" required><br>
	<input id="username" type="text" name="username" placeholder="username" required><br>
	<input id="email" type="email" name="email" placeholder="email" required><br>
	<input id="password" type="password" name="password" placeholder="password" required><br>
	<button onclick="register()">register</button>
	<button onclick="change()">Reset password</button>
	<br><p>to reset your password, enter your email address above</p> 
</div>

<div id="login"><h2>or login if you have an account<h2>
	<input id="user" type="text" name="username" placeholder="username" required><br>
	<input id="pass" type="password" name="password" placeholder="password" required><br>
	<button onclick="login()">login</button>
	
</div>

<div id="msg">
	
</div>
</center>


<script type="text/javascript">
	function change(){
		var email = document.getElementById('email').value ;
		var link = "?email="+email+"reset=reset";
		var xhttp = new XMLHttpRequest();
		xhttp.open("GET", "./web-service.php" + link, true);
		xhttp.send();
		xhttp.onreadystatechange = function(){
			if (this.readyState == 4 && this.status == 200) {
				var json = JSON.parse(this.responseText);
				document.getElementById('msg').innerHTML = json.password;
			}
		}

	}
	function login(){
		var username = document.getElementById('user').value ;
		var password = document.getElementById('pass').value ;
		var link = "?user="+username+"&pass="+password +"&submit=login";
		var xhttp = new XMLHttpRequest();
		xhttp.open("GET", "./json-register.php" + link, true);
		xhttp.send();
		xhttp.onreadystatechange = function(){
				if (this.readyState == 4 && this.status == 200) {
					var json = JSON.parse(this.responseText);
					document.cookie = "user="+json.id+";path=/";
					// redirect to home
					window.location.href = "home.php";

				}else{
					document.getElementById('msg').innerHTML =  this.readyState + this.responseText;
				}
		}
	}

	function register(){
		var fullname = document.getElementById('fullname').value ;
		var username = document.getElementById('username').value ;
		var email = document.getElementById('email').value ;
		var password = document.getElementById('password').value ;
		var link = "?fullname="+fullname+"&username="+username+"&email="+email+"&password="+password+"&submit=register";

		var xhttp = new XMLHttpRequest();
		xhttp.open("GET", "./json-register.php" + link, true);
		xhttp.send();
		xhttp.onreadystatechange = function(){
				if (this.readyState == 4 || this.status == 200) {
					var json = JSON.parse(this.responseText);
					document.cookie = "user="+json.id+";path=/";
					// redirect to home 
					window.location.href = "home.php";

				}else{
					document.getElementById('msg').innerHTML =  "Something went wrong" + this.readyState + this.responseText;
				}
			}		
		}
	
</script>
</body>
</html>