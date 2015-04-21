<?php

	// If there is no session data then redirect the user to the login page
	session_start();
	if ($_SESSION["id"] != 'ilovejapanese')
	{
		header('Location: login.php');
	}
	     
    require 'database.php';
	
	// This php file will be used to create a new Quiz for a specific lesson.
	// Since the lesson is not tied to any other table there is no constraint for this.
	 
    if ( !empty($_POST)) {
        // keep track validation errors
        $lessonNumError = null;
        $descriptionError = null;
                 
        // keep track post values
        $lessonNumber = $_POST['j_lessons_num'];
        $description = $_POST['description'];
                 
        // validate input
        $valid = true;
        if (empty($lessonNumber)) 
		{
            $lessonNumError = 'Please enter a valid lesson number';
            $valid = false;
        }
         
        if (empty($description)) 
		{
            $descriptionError = 'Please enter a description for this quiz';
            $valid = false;
        }
                  
        // insert data
        if ($valid) {
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
            $sql = "INSERT INTO jquizzes (j_lessons_num,description) values(?, ?)";
			$q = $pdo->prepare($sql);
			$q->execute(array($lessonNumber, $description));
						
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
                        <h3>Create a New Quiz</h3>
                    </div>
             
                    <form class="form-horizontal" action="create.php" method="post">
                      <div class="control-group <?php echo !empty($lessonNumError)?'error':'';?>">
                        <label class="control-label">Lesson Number</label>
                        <div class="controls">
                            <input name="j_lessons_num" type="text"  placeholder="Lesson Number" value="<?php echo !empty($lessonNumber)?$lessonNumber:'';?>">
                            <?php if (!empty($lessonNumError)): ?>
                                <span class="help-inline"><?php echo $lessonNumError;?></span>
                            <?php endif; ?>
                        </div>
                      </div>
                      <div class="control-group <?php echo !empty($descriptionError)?'error':'';?>">
                        <label class="control-label">Description</label>
                        <div class="controls">
                            <input name="description" type="text" placeholder="Description" value="<?php echo !empty($description)?$description:'';?>">
                            <?php if (!empty($descriptionError)): ?>
                                <span class="help-inline"><?php echo $descriptionError;?></span>
                            <?php endif;?>
                        </div>
                      </div>
                      <div class="form-actions">
                          <button type="submit" class="btn btn-success">Create</button>
                          <a class="btn" href="index.php">Back</a>
                        </div>
                    </form>
                </div>       
    </div> <!-- /container -->
  </body>
</html>