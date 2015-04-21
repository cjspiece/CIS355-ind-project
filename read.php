<?php
    require 'database.php';
    $id = null;
    if ( !empty($_GET['id'])) {
        $id = $_REQUEST['id'];
    }
	
    if ( null==$id ) {
        header("Location: index.php");
    } else {
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM jquizzes where id = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($id));
        $data = $q->fetch(PDO::FETCH_ASSOC);
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
                        <h3>Read a Quiz</h3>
                    </div>
                     
                    <div class="form-horizontal" >
                      <div class="control-group">
                        <label class="control-label">Quiz Number</label>
                        <div class="controls">
                            <label class="checkbox">
                                <?php echo $data['id'];?>
                            </label>
                        </div>
                      </div>
                      <div class="control-group">
                        <label class="control-label">Lesson Number</label>
                        <div class="controls">
                            <label class="checkbox">
                                <?php echo $data['j_lessons_num'];?>
                            </label>
                        </div>
                      </div>
                      <div class="control-group">
                        <label class="control-label">Description</label>
                        <div class="controls">
                            <label class="checkbox">
                                <?php echo $data['description'];?>
                            </label>
                        </div>
                      </div>
                    </div>
                </div>                 
		</div> <!-- /container -->
		
		<div class="container"> 
			<div class="span10 offset1">
				<div class="row">
					<h3>Questions for this quiz</h3>
				</div>
		
		<div class="row">              
			<table class="table table-striped table-bordered">
				<thead>
				<tr>
					<th>Quiz ID</th>
					<th>Question</th>
					<th>Answer</th>
				</tr>
				</thead>
					<tbody>
					<?php
						$pdo = Database::connect();
						$sql = 'SELECT * FROM jquestions WHERE quizzes_id =' . $id;
						foreach ($pdo->query($sql) as $row) 
						{
							echo '<tr>';
							echo '<td>'. $row['quizzes_id'] . '</td>';
							echo '<td>'. $row['question'] . '</td>';
							echo '<td>'. $row['answer'] . '</td>';
							echo '<td width=250>';								                               
							echo '<a class="btn btn-success" href="update_question.php?id='.$row['id'].'&quizzes_id='.$row['quizzes_id'].'">Update</a>';
							echo ' ';
							echo '<a class="btn btn-danger" href="delete_question.php?id='.$row['id'].'">Delete</a>';
							echo '</td>';
							echo '</tr>';
						}
						Database::disconnect();
					?>
					</tbody>
				</table>        
		</div>
	
	
			<div class="form-actions">
				<a class="btn btn-info" href="create_question.php">Add a Question</a>								
				<a class="btn" href="index.php">Back</a>
			</div>	
		</div>
	</div><!-- /container -->
  </body>
</html>