<?php
	require "../db_connect.php";
	require "../message_display.php";
	require "verify_librarian.php";
	require "header_librarian.php";
?>

<html>
	<head>
		<title>Issued books</title>
		<link rel="stylesheet" type="text/css" href="../css/global_styles.css">
		<link rel="stylesheet" type="text/css" href="css/issued_books_style.css">
	</head>
	<body>
	
		<?php
			$query = $con->prepare("SELECT book_isbn, member FROM book_issue_log;");
			$query->execute();
			$result = $query->get_result();
			$rows = mysqli_num_rows($result);
			if($rows == 0)
				echo "<h2 align='center'>No books currently issued</h2>";
			else
			{
				echo "<form class='cd-form'>";
				echo "<legend>Issued books</legend>";
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
							<th>Copies<hr></th>
							<th>Member<hr></th>
							<th>Due Date<hr></th>
						</tr>";
				while($row = mysqli_fetch_array($result)){
					$isbn = $row[0];
					$member = $row[1];
					$query = $con->prepare("SELECT isbn, title, author, category, copies FROM book WHERE isbn = ?;");
					$query->bind_param("s", $isbn);
					$query->execute();
					$innerRow = mysqli_fetch_array($query->get_result());
					echo "<tr>";
					for($j=0; $j<5; $j++)
						echo "<td>".$innerRow[$j]."</td>";
					$query = $con->prepare("SELECT due_date FROM book_issue_log WHERE member = ? AND book_isbn = ?;");
					$query->bind_param("ss", $member, $isbn);
					$query->execute();
					echo "<td>".$member."</td>";
					echo "<td>".mysqli_fetch_array($query->get_result())[0]."</td>";
					echo "</tr>";
				}
				echo "</table><br />";
				echo "</form>";
			}
		?>
		
	</body>
</html>