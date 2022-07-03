<?php

require"../db_connect.php";
if (isset($_POST['search_param'])) {
    $search_param = mysqli_real_escape_string($con, $_POST['search_param']);
    $query = mysqli_query($con, "SELECT * FROM book where title like '%$search_param%' or author like '%$search_param%' or category like '%$search_param%' ORDER BY title;");
    $output = "<tr>
						<th></th>
						<th>ISBN<hr></th>
						<th>Title<hr></th>
						<th>Author<hr></th>
						<th>Category<hr></th>
						<th>Price<hr></th>
						<th>Copies available<hr></th>
					</tr>";
    if ($query->num_rows > 0) {
        while ($row = mysqli_fetch_array($query)) {
            $output .= "<tr><td>
								<label class='control control--radio'>
									<input type='radio' name='rd_book' value=".$row[0]." />
								<div class='control__indicator'></div>
							</td>";
					for($j=0; $j<6; $j++)
						if($j == 4)
							$output .= "<td>$".$row[$j]."</td>";
						else
							$output .= "<td>".$row[$j]."</td>";
					$output .="</tr>";
        }
    } else {
        $output = '
    <td colspan="4"> No result found. </td>';
    }
    echo $output;
}
?>