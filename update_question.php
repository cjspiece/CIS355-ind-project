<?php
	// If there is no session data then redirect the user to the login page
	session_start();
	if ($_SESSION["id"] != 'ilovejapanese')
	{
		header('Location: login.php');
	}
	
    require 'database.php';
	 
    $id = null;
	$quizId = null;
	
    if ( !empty($_GET['id'])) {
        $id = $_REQUEST['id'];
    }
     
    if ( null==$id ) {
        header("Location: index.php");
    }
    
	// Used to navigate back to the former read page
	if ( !empty($_GET['quizzes_id'])) 
	{
        $quizId = $_REQUEST['quizzes_id'];	
    }	
	
    if ( !empty($_POST)) 
	{
		
		if ( !empty($_GET['quizzes_id'])) 
	{
        $quizId = $_REQUEST['quizzes_id'];
    }	
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
        
        // update data
        if ($valid) {
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE jquestions  set quizzes_id = ?, question = ?, answer = ? WHERE id = ?";
            $q = $pdo->prepare($sql);
            $q->execute(array($quizzes_id,$question,$answer,$id));
            Database::disconnect();
            // When the update is finished redirect them to the associated quiz page
			header("Location: read.php?id=".$_REQUEST['quizzes_id']);
        }
    } 
	else 
	{
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM jquestions where id = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        $data = $q->fetch(PDO::FETCH_ASSOC);
        $question = $data['question'];
        $answer = $data['answer'];
		$quizzes_id = $data['quizzes_id'];
        Database::disconnect();
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
                        <h3>Update a Question</h3>
                    </div>
             
                    <form class="form-horizontal" action="update_question.php?id=<?php echo $id?>" method="post">
                      <div class="control-group <?php echo !empty($lessonNumError)?'error':'';?>">
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
                        <label class="control-label">Answer</label>
                        <div class="controls">
                            <input name="answer" type="text" placeholder="Enter your answer here" value="<?php echo !empty($answer)?$answer:'';?>">
                            <?php if (!empty($answerError)): ?>
                                <span class="help-inline"><?php echo $answerError;?></span>
                            <?php endif;?>
                        </div>
                      </div>
                      <div class="form-actions">
                          <button type="submit" class="btn btn-success">Update</button>
                          <a class="btn" href="read.php?id=<?php echo $quizId?>">Back</a>
                        </div>
                    </form>
                </div>
                 
    </div> <!-- /container -->
  </body>
</html>