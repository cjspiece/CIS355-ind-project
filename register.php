<?php

require 'database.php';

// Error tracking variables
$usernameError = null;
$passwordError = null;
$firstNameError= null;
$lastNameError = null;
$emailError = null;


// Post variables
$username = $_POST['username'];
$password = $_POST['password'];
$firstName = $_POST['firstName'];
$lastName = $_POST['lastName'];
$email = $_POST['email'];

if ( !empty($_POST)) 
{
	// validate input
	$valid = true;
	
	if (empty($username)) 
	{
		$usernameError = 'Please enter a valid lesson number';
		$valid = false;
	}
	
	// Check to see if the username is already in the user table
	$pdo = Database::connect();
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = "SELECT username FROM users where username =:username";
	$q = $pdo->prepare($sql);
	$q->bindParam(':username', $username);
	$q->execute();
	$count = $q->rowCount();
	
	// If the username already exists then prompt the user that they must select a different one
	if($count > 0)
	{
		$usernameError = 'This username is already taken';
		$valid = false;
	}
	
	if (empty($password)) 
	{
		$passwordError = 'Please enter a password';
		$valid = false;
	}
	
	if (empty($firstName)) 
	{
		$firstNameError = 'Please enter your first name';
		$valid = false;
	}
	
	if (empty($lastName)) 
	{
		$lastNameError = 'Please enter your last name';
		$valid = false;
	}
	
	if (empty($email)) 
	{
		$emailError = 'Please enter your email address';
		$valid = false;
	}
	
	 // If all error checks are clean then proceed to insert data
	if ($valid) 
	{
		$pdo = Database::connect();
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		$sql = "INSERT INTO users (username, email, firstName, lastName, password_hash) values (?, ?, ?, ?, ?)";
		$q = $pdo->prepare($sql);
		$q->execute(array($username, $email, $firstName, $lastName, $password));
					
		Database::disconnect();
		header("Location: index.php");
	}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
</head>
 
<body>
    <div class="container">
     
		<div class="span10 offset1">
			<div class="row">
				<h3>Please enter the info below:</h3>
			</div>
			<form class="form-horizontal" action="register.php" method="post"> 
			<div class="form-horizontal" >
				<div class="control-group <?php echo !empty($usernameError)?'error':'';?>">
					<label class="control-label">Username</label>
					<div class="controls">
						<input name="username" type="text"  placeholder="" value="<?php echo !empty($username)?$username:'';?>">
						<?php if (!empty($usernameError)): ?>
						<span class="help-inline"><?php echo $usernameError;?></span>
						<?php endif; ?>
					</div>
				</div>
				
				<div class="control-group <?php echo !empty($passwordError)?'error':'';?>">
					<label class="control-label">Password</label>
					<div class="controls">
						<input name="password" type="text"  placeholder="" value="<?php echo !empty($password)?$password:'';?>">
						<?php if (!empty($passwordError)): ?>
						<span class="help-inline"><?php echo $passwordError;?></span>
						<?php endif; ?>
					</div>
				</div>
				
				<div class="control-group <?php echo !empty($firstNameError)?'error':'';?>">
					<label class="control-label">First name</label>
					<div class="controls">
						<input name="firstName" type="text"  placeholder="" value="<?php echo !empty($firstName)?$firstName:'';?>">
						<?php if (!empty($firstNameError)): ?>
						<span class="help-inline"><?php echo $firstNameError;?></span>
						<?php endif; ?>
					</div>
				</div>
				
				<div class="control-group <?php echo !empty($lastNameError)?'error':'';?>">
					<label class="control-label">Last name</label>
					<div class="controls">
						<input name="lastName" type="text"  placeholder="" value="<?php echo !empty($lastName)?$lastName:'';?>">
						<?php if (!empty($lastNameError)): ?>
						<span class="help-inline"><?php echo $lastNameError;?></span>
						<?php endif; ?>
					</div>
				</div>
				
				<div class="control-group <?php echo !empty($emailError)?'error':'';?>">
					<label class="control-label">Email address</label>
					<div class="controls">
						<input name="email" type="text"  placeholder="" value="<?php echo !empty($email)?$email:'';?>">
						<?php if (!empty($emailError)): ?>
						<span class="help-inline"><?php echo $emailError;?></span>
						<?php endif; ?>
					</div>
				</div>
			  
				<div class="form-actions">
					<button type="submit" class="btn btn-success">Create</button>
					<a class="btn" href="index.php">Back</a>
				</div>
				</form>
			</div>
		</div>
                 
    </div> <!-- /container -->
  </body>
</html>