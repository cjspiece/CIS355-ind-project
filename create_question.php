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
		$questionError = null;
		$answerError = null;
                         
        // keep track post values
		$question = $_POST['question'];
		$answer = $_POST['answer'];
		$quizzes_id = $_POST['quizzes_id'];
                         
        // validate input
        $valid = true;
        if (empty($question)) 
		{
            $questionError = 'Please enter a question';
            $valid = false;
        }
         
        if (empty($answer)) 
		{
            $answerError = 'Please enter an answer';
            $valid = false;
        }
                  
        // insert data
        if ($valid) 
		{
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
            $sql = "INSERT INTO jquestions (quizzes_id,question,answer) values(?, ?, ?)";
			$q = $pdo->prepare($sql);
			$q->execute(array($quizzes_id, $question, $answer));
			
			// Insert a conditional that prompts the user to add more questions if they want
            Database::disconnect();
            header("Location: read.php?id=".$_REQUEST['quizzes_id']);
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
	<script src="js/bootstrap.min.js"></script>
	
</head>
 
<body>

    <div class="container">
                <div class="span10 offset1">
                    <div class="row">
                        <h3>Create a New Question</h3>
                    </div>
             
                    <form class="form-horizontal" action="create_question.php" method="post">
                      <div class="control-group">
                        <label class="control-label">Quiz Number</label>
                        <div class="controls">
							<select name="quizzes_id">
								<?php
									$pdo2 = Database::connect();
									$pdo2->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
									$sql = "SELECT * FROM jquizzes";
									foreach ($pdo2->query($sql) as $row)
									{
										echo "<option value=$row[0]>".$row[0]."</option>";
									}
									Database::disconnect();
								?>
							</select>
                        </div>
                      </div>
                      <div class="control-group <?php echo !empty($questionError)?'error':'';?>">
                        <label class="control-label">Question</label>
                        <div class="controls">
                            <input name="question" type="text" placeholder="Enter your question here" value="<?php echo !empty($question)?$question:'';?>">
                            <?php if (!empty($questionError)): ?>
                                <span class="help-inline"><?php echo $questionError;?></span>
                            <?php endif;?>
                        </div>
                      </div>
					  <div class="control-group <?php echo !empty($answerError)?'error':'';?>">
                        <label class="control-label">Correct answer</label>
                        <div class="controls">
                            <input name="answer" type="text" placeholder="Enter the correct answer here" value="<?php echo !empty($answer)?$answer:'';?>">
                            <?php if (!empty($answerError)): ?>
                                <span class="help-inline"><?php echo $answerError;?></span>
                            <?php endif;?>
                        </div>
                      </div>
                      <div class="form-actions">
                          <button type="submit" class="btn btn-success">Create</button>
                          <a class="btn" href="read.php">Back</a>
                        </div>
                    </form>
                </div>       
    </div> <!-- /container -->
  </body>
</html>