<?php
	// If there is no session data then redirect the user to the login page
	session_start();
	if ($_SESSION["id"] != 'ilovejapanese')
	{
		header('Location: login.php');
	}
	$uname = $_SESSION["uname"];
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
            <div class="row">
                <h3>Welcome! <?php  echo "$uname"; ?> <a href="logout.php" class="btn btn-danger">Logout</a></h3>
				<p>Here you can create, modify, and update quizzes for this website. </p>
            </div>
            <div class="row">              
                <table class="table table-striped table-bordered">
                      <thead>
                        <tr>
							<th>Quiz ID</th>
							<th>Lesson Number</th>
							<th>Description</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php
                       include 'database.php';
                       $pdo = Database::connect();
                       $sql = 'SELECT * FROM jquizzes ORDER BY id ASC';
                       foreach ($pdo->query($sql) as $row) {
                                echo '<tr>';
                                echo '<td>'. $row['id'] . '</td>';
                                echo '<td>'. $row['j_lessons_num'] . '</td>';
                                echo '<td>'. $row['description'] . '</td>';
                                echo '<td width=250>';								                               
                                echo '<a class="btn" href="read.php?id='.$row['id'].'">Read</a>';
                                echo ' ';
                                echo '<a class="btn btn-success" href="update.php?id='.$row['id'].'">Update</a>';
                                echo ' ';
                                echo '<a class="btn btn-danger" href="delete.php?id='.$row['id'].'">Delete</a>';
                                echo '</td>';
                                echo '</tr>';
                       }
                       Database::disconnect();
                      ?>
                      </tbody>
                </table>
				<p>
                    <a href="create.php" class="btn btn-success">Create a New Quiz</a>								
                </p>                 
        </div>
    </div> <!-- /container -->
  </body>
</html>
