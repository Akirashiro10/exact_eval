<?php
include('../connection.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $academic_year_id = $_POST['academic_year_id'];

  $update_query = "UPDATE academic_list SET status = 0 WHERE id = $academic_year_id";

  if (mysqli_query($connection, $update_query)) {
    echo "Academic year marked as finished.";
  } else {
    echo "Error: " . mysqli_error($connection);
  }

  mysqli_close($connection);
}
?>
