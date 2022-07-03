<?php
	require "../db_connect.php";
	require "../message_display.php";
	require "verify_librarian.php";
	require "header_librarian.php";
?>

<html>
	<head>
		<title>Edit book</title>
		<link rel="stylesheet" type="text/css" href="../css/global_styles.css" />
		<link rel="stylesheet" type="text/css" href="../css/form_styles.css" />
		<link rel="stylesheet" href="css/insert_book_style.css">
	</head>
	<body>
		<?php
			$query = $con->prepare("SELECT * FROM book WHERE isbn=?;");
			$id = trim($_GET['id'], "'");
			$query->bind_param("s", $id);
			$query->execute();
			$result = $query->get_result();
			$row = mysqli_fetch_array($result);
		?>
		<form class="cd-form" method="POST" action="#">
			<legend>Enter book details</legend>
			
				<div class="error-message" id="error-message">
					<p id="error"></p>
				</div>
				
				<div class="icon">
					<input class="b-title" type="text" name="b_title" placeholder="Title" required value= "<?php echo $row[1]; ?>"/>
				</div>
				
				<div class="icon">
					<input class="b-author" type="text" name="b_author" placeholder="Author" required value= "<?php echo $row[2]; ?>"/>
				</div>
				
				<div>
				<h4>Category</h4>
				
					<p class="cd-select icon">
						<select class="b-category" name="b_category" >
							<option <?php if($row[3] == 'Fiction') echo "selected = 'selected'";?>>Fiction</option>
							<option <?php if($row[3] == 'Non-fiction') echo "selected = 'selected'";?>>Non-fiction</option>
							<option <?php if($row[3] == 'Education') echo "selected = 'selected'";?>>Education</option>
						</select>
					</p>
				</div>
				
				<div class="icon">
					<input class="b-price" type="number" name="b_price" placeholder="Price" required value= "<?php echo $row[4]?>"/>
				</div>
				
				<br />
				<input class="b-isbn" type="submit" name="b_add" value="Edit book" />
		</form>
	<body>
	
	<?php
		if(isset($_POST['b_add']))
		{
			$query = $con->prepare("UPDATE book SET title=?, author=?, category=?, price=? WHERE isbn=?;");
			$query->bind_param("sssds", $_POST['b_title'], $_POST['b_author'], $_POST['b_category'], $_POST['b_price'], $id);
				
			if(!$query->execute())
				die(error_without_field("ERROR: Couldn't Edit book"));
			echo success("Successfully edited book");
			header("Refresh:3");
		}
	?>
</html>