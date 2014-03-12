<?php
	//variables to hold the username and password from the login form
	@$name = $_POST["name"];
	@$password = $_POST["password"];
	
	//requiring user to enter password and username
	if ((!isset($name)) || (!isset($password)))
	{
	?> <!--end of PHP for now, starting HTML-->
		<h1> Please Log In </h1>
		<p> Browsing books is only a step away! </p>
		
		<!--actual login form-->
		<form method = "post" action = "index.php">
		<p> Username: <input type = "text" name = "name"> </p>
		<p> Password: <input type = "password" name = "password"> </p>
		<p> <input type = "submit" name = "submit" value = "Log In"> </p>
		</form>
	
	<?php 
	} else {
		//values were provided, now trying to connect to mySQL
		//mysqli = improved function over mysql for new features
		//localhost is database, jordan and password will have to change to--
		//an acct and password that is just used for web authorization..
		$mysql = mysqli_connect("localhost", "jordan", "password"); 
		
		if (!$mysql)
		{
			echo "Cannot connect to database.";
			exit;
		}
		
		//if we got here, then select the users table
		//1st parameter is link to connection, 2nd is the actual table
		$selected = mysqli_select_db($mysql, "users");
		
		if (!$selected)
		{
			echo "Cannot select database.";
			exit;
		}
		
		$password = (sha1($password));
		//now we will query the database for resulting match
		$query = "select count(*) from users where
				username = '".$name."' and password = '".$password."'";	
				
		$result = mysqli_query($mysql, $query);
		
		if (!$result)
		{
			echo "Cannot run query.";
			exit;
		}
		
		$row = mysqli_fetch_row($result); //getting the row and putting it into a row array
		$count = $row[0]; //count will be stored in first spot of array
		
		
		if ($count > 0)
		{
			//authorized user, successful
			echo "<h1> Welcome! </h1>
				<p> Thanks for logging in, you will be redirected soon </p>";
		} else {
			//unauthorized user, not successful
			echo "<h1> Username/Password combination incorrect </h1>
				<p> Please try again. </p>";
				}
		}
		?>

		<a href="register.php">Click here to register!</a>
		
			
		
			
