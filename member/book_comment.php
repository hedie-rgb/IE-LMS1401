<?php
	require "../db_connect.php";
	require "../message_display.php";
	require "verify_member.php";
	require "header_member.php";
?>

<html>
	<head>
		<title>Comments</title>
		<link rel="stylesheet" type="text/css" href="../css/global_styles.css">
		<link rel="stylesheet" type="text/css" href="../css/custom_checkbox_style.css">
		<link rel="stylesheet" type="text/css" href="css/book_comment_style.css">
	</head>
	<body>
	
		<?php
			$query = $con->prepare("SELECT body FROM comment WHERE isbn=?;");
			$id = trim($_GET['id'], "'");
			$query->bind_param("s", $id);
			$query->execute();
			$result = $query->get_result();
			$rows = mysqli_num_rows($result);
			if($rows == 0){
				echo "<h2 align='center'>No comments for this book</h2><br /><br /><br />";
				echo "<form class='cd-form' method='POST' action='#'>";
				echo "<input class='cm' type='text' name='cm'/>";
				echo "<br /><br /><br /><input type='submit' name='b_comment' value='Add comment' />";
				echo "</form>";
			}
			else
			{
				$query = $con->prepare("SELECT title, author, category FROM book WHERE isbn = ?;");
				$query->bind_param("s", $id);
				$query->execute();
				$innerRow = mysqli_fetch_array($query->get_result());
				echo "<form class='cd-form' method='POST' action='#'>";
				echo "<legend>Comments</legend>";
				echo "<div class='success-message' id='success-message'>
						<p id='success'></p>
					</div>";
				echo "<div class='error-message' id='error-message'>
						<p id='error'></p>
					</div>";
				echo"<table width='100%' cellpadding='10' cellspacing='10'>
						<tr>
							<th>ISBN<hr></th>
							<th>Title<hr></th>
							<th>Author<hr></th>
							<th>Category<hr></th>
							<th>Comment<hr></th>
						</tr>";
				while($row = mysqli_fetch_array($result))
				{
					if($id != NULL)
					{
						echo "<td>".$id."</td>";
						for($j=0; $j<3; $j++)
							echo "<td>".$innerRow[$j]."</td>";
						echo "<td>".$row[0]."</td>";
						echo "</tr>";
					}
				}
				echo "</table><br /><br />";
				echo "<input class='cm' type='text' name='cm'/>";
				echo "<br /><br /><br /><input type='submit' name='b_comment' value='Add comment' />";
				echo "</form>";
			}
			
			if(isset($_POST['b_comment']))
			{
				if(empty($_POST['cm']))
					echo error_without_field("Please write a comment first");
				else
				{
					$query = $con->prepare("SELECT * FROM comment WHERE member=? AND isbn=?;");
					$query->bind_param("ss", $_SESSION['username'], $id);
					$query->execute();
					$result = $query->get_result();
					$rows = mysqli_num_rows($result);
					if($rows == 0){
						$query = $con->prepare("INSERT INTO comment VALUES(?,?, ?);");
						$query->bind_param("sss", $_SESSION['username'], $id, $_POST['cm']);
						if(!$query->execute()){
							echo error_without_field("ERROR: Couldn\'t add your comment");
						}
						else{
							echo success("Comment successfully added.");
							header("Refresh:3");
						}
					}
					else
					{
						echo error_without_field("There is already a comment from you on this book");
					}
				}
			}
		?>
		
	</body>
</html>