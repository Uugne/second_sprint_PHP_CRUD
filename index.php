<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body class = "container">
<div>
<?php

    print '<h1>Personal management system (CRUD)</h1>';

    $employees = './' . $_GET['file'] . 'index.php';
    $project = './' . $_GET['file'] . 'project.php';

    print '<button id="button">' . 
                '<a class="button" href=' . $employees . '>'."EMPLOYEES".'</a>' .
        '</button>';
    print '<button id="button">' . 
            '<a class="button" href=' . $project . '>'."PROJECTS".'</a>' .
        '</button>';

    $servername = "localhost";
    $username = "root";
    $password = "mysql";
    $dbname = "php2021";

    
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // DELETE LOGIC

    if(isset($_GET['action']) and $_GET['action'] == 'delete'){
        $sql = 'DELETE FROM employees WHERE id = ?';
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $_GET['id']);
        $res = $stmt->execute();
        
        $stmt->close();
        mysqli_close($conn);
    
        header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
        die();
    }
    
    // UPDATE LOGIC START

    $firstname='';
    $id='';

    if(!empty($_POST['update']) && !empty($_POST['id']) ) {

        $id = $_POST['id'];
    
        $query2 = "SELECT * FROM employees WHERE id='".$_POST['id']."' ";
        $result = $conn->query($query2);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $firstname = $row["firstname"];
            }
        }
    }

    if(!empty($_POST['firstname']) && !empty($_POST['id']) ){
        
    $query = "UPDATE employees SET firstname='".$_POST['firstname']."' WHERE id='".$_POST['id']."' ";
        if (mysqli_query($conn, $query)) {
            print "<br>Employee name updated successfully!";
        }
    }

    // CREATE LOGIC

    include_once("index.php");

        if(isset($_POST['firstname']) & !empty($_POST['firstname'])){
            $fname = $_POST['firstname'];
            $projectID = $_POST['project_id'];

            $createSql = "INSERT INTO `employees` (firstname, project_id) VALUES (?, ?) ";

            $stmt=$conn->prepare($createSql);
            $stmt->bind_param("s, i", $fname, $projectID);
            $stmt->execute();

            $stmt->close();
            mysqli_close($conn);

            header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
            $res = mysqli_query($conn, $createSql) or die(mysqli_error($conn));
            
        }
       
    // CREATE LOGIC FINISH

    $sql = "SELECT employees.id, employees.firstname, project.name
            FROM employees
            LEFT JOIN project
            ON project.id = employees.project_id;";
    
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        print("<table class='table'><thead>");
        print("<tr><th>Id</th><th>Name</th><th>Project</th><th>Action</th></tr>");
        print("</thead>");
            while ($row = mysqli_fetch_assoc($result)) {
                print('<tr>' 
                    . '<td>' . $row['id'] . '</td>' 
                    . '<td>' . $row['firstname'] . '</td>' 
                    . '<td>' . $row['name'] . '</td>'
                    . '<td style="width:200px">' . '<a href="?action=delete&id='  . $row['id'] . '"><button>DELETE EMPLOYEE</button></a>' . '</td>'
                    . '<td>' . 
                        '<form action="" method="post">' .
                        '<input name="id" value=' . $row["id"] . ' hidden>' .
                        '<button type="submit" name="update" value="update">UPDATE EMPLOYEE</button>' .
                        '</form>' . 
                     '</td>'
                    . '</tr>');
            }
    } else {
            echo "0 results";
            }
        print("</table>");

        mysqli_close($conn);

    if ($_POST['update']) {
          print '<form method="POST" action="">
                    ID: <input type="text" name="id" value=' . $id .' required><br><br/>
                    Name: <input type="text" name="firstname" value=' . $firstname . ' required><br><br/>
                    <input type="submit" value="Update">
                </form>';
    }    
    ?>
    <br>

    <form action="" method="post" name="form1">
		<table>
            <tr>
                <select name="project_id" id="category">
                    <option value=''>Select a project</option>
                        <?php $sql = mysqli_query(mysqli_connect($servername, $username, $password, $dbname), "SELECT id, name FROM project");
                            while ($row = mysqli_fetch_array($sql)) {
                                    $id = $row['id'];
                                    $name = $row['name'];
                        ?>
                    <option value='<?php echo $id; ?>'><?php echo $name; ?></option>
                        <?php } ?> 
                </select>
            </tr>
			<tr>
				<td><input type="text" name="firstname" placeholder="First name"></td>
			</tr>
			<tr>
				<td><input type="submit" name="Submit" value="Add a first name and attach to a project"></td>
			</tr>
		</table>
	</form>

</body>
</html>