<?php
	//variables to hold information from the form
	 @$username = $_POST["username"];
	 @$password = $_POST["password"];
	 @$password2 = $_POST["password2"];
	 @$email = $_POST["email"];
	 @$name = $_POST["name"];
	
	?>

	<html>
	<!--Default to make users register-->
		<h1> Register for TextChange! </h1>
		<p> It's simple and free! </p>
		
		<!--actual register form-->
		<form method = "post" action = "register.php">
		<p> First Name: <input type = "text" name = "name"> </p>
		<p> Username: <input type = "text" name = "username"> </p>
		<p> Email: <input type = "text" name = "email"> </p>
		<p> Password: <input type = "password" name = "password"> </p>
		<p> Confirm Password: <input type = "password" name = "password2"> </p>
		<p> <input type = "submit" name = "submit" value = "Register"> </p>
		</form>
		
	</html>

	<?php 
	//checking to see that no field is left blank
	if ( (empty($username)) || (empty($password)) || (empty($password2)) || (empty($email)) || (empty($name)) )
	{
		echo "You must fill out all fields!";
		exit;
	 
	} else {
		//parsing the email at the "@" symbol
		$email_array = explode("@", $email);
		
		if ((@$email_array[1] != "wpunj.edu") || (@!isset($email_array[1])))
		{
			//if it is not WPUNJ.edu or it doesnt exist
			//meaning there was no @ symbol for explode fct to parse
			//the @ in the IF conditions suppress the error message
			//for no element in email_array[1] ...
			echo "You must have a valid WPUNJ email address!";
			exit;
		}
		
		if ($password != $password2)
		{
			//making sure passwords are the same
			echo "Passwords must be the same!";
			exit;
		}
		
		$password = (sha1($password));
	
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
		
		//query database to see if username exists there
		$query_username = "select count(*) username from users where username = '".$username."';";
		
		
		
		//boolean result if query worked
		$result_queryname = mysqli_query($mysql, $query_username);
		
		
		if (!$result_queryname)
		{
			//if query didn't run
			echo "Cannot run username check query.";
			exit;
		
		}
		
		
		$row = mysqli_fetch_row($result_queryname); //getting the row and putting it into a row array
		$count = $row[0]; //count will be stored in first spot of array
	
		
		
		if ($count > 0)
		{
			//username has to exist
			echo "Username already taken.";
			exit;
		}
		
		//query database to see if the email exists there
		$query_email = "select count(*) email from users where email = '".$email."';";
		
		//boolean result if query worked
		$result_queryemail = mysqli_query($mysql, $query_email);
		
		if(!$result_queryemail)
		{
			//if query didn't run
			echo "Cannot run email check query.";
			exit;
		}
		
		$row = mysqli_fetch_row($result_queryemail); 	//getting the row and putting it into a row array
		$count = $row[0];	//count will be stored in first spot of array
		
		if ($count > 0)
		{
			//email has to exist
			echo "Email already taken.";
			exit;
		}
		
		
		$query = "insert into users (username, password, email, fname) values ('".$username."', '".$password."', '".$email."', '".$name."');";
		
						
		
		$result = mysqli_query($mysql, $query);
		
		if (!$result)
		{
			echo "Cannot run query.";
			exit;
		}
		else
		{
			echo "<h1> Registration successful </h1>";
		}
		}		
		?>	
