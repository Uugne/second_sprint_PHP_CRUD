<?php

// Empty string to be used later
$firstname='';
$id='';

// Button click to update using POST method
if(!empty($_POST['update']) && !empty($_POST['id']) )  {
  $id=$_POST['id'];
  // Fetch record with ID and populate it in the form
  $query2 = "SELECT * FROM employees WHERE id='".$_POST['id']."' ";
  $result = $conn->query($query2);
  if ($result->num_rows > 0) {
    // Output data for each row
    while($row = $result->fetch_assoc()) {
      $firstname=$row["firstname"];
    }
    echo "Current Details: " ."<b> - Name:</b> " . $firstname. "<br>";
  } else {
    echo "Error updating";
  }
}


// Check that the input fields are not empty and process the data
if(!empty($_POST['firstname']) && !empty($_POST['id']) ){
    // Insert into the database
  $query = "UPDATE user SET name='".$_POST['firstname']."' WHERE id='".$_POST['id']."' ";
  if (mysqli_query($conn, $query)) {
      echo "Record updated successfully!<br/>";
      echo '<a href="form-get.php">Get Form</a><br/>
            <a href="form-post.php">Post Form</a>';
      die(0);
  } else {
      // Display an error message if unable to update the record
       echo "Error updating record: " . $conn->error;
       die(0);
  }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>FORM</title>
</head>
<body>
    <h1>Form</h1>
    <p>Edit the record</p>
    <form method="POST" action="update.php">
        ID: <input type="text" name="id" value="<?php echo($id); ?>" required><br><br/>
        Name: <input type="text" name="name" value="<?php echo($firstname); ?>" required><br><br/>
        <br/>
        <input type="submit" value="update">
    </form>
</body>
</html>