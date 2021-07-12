<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body class="container">
<?php

    print '<h1>Personal management system (CRUD)</h1>';

    $employees = './' . $_GET['file'] . 'employees.php';
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

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    if(isset($_GET['action']) and $_GET['action'] == 'delete'){
        $sql = 'DELETE FROM project WHERE id = ?';
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $_GET['id']);
        $res = $stmt->execute();
        
        $stmt->close();
        mysqli_close($conn);
    
        header("Location: " . strtok($_SERVER["REQUEST_URI"], '?'));
        die();
    } 

    $sql = "SELECT project.id, project.name, group_concat(employees.firstname SEPARATOR ', ') as firstnames
                FROM project
                LEFT JOIN employees
                ON project.id = employees.project_id
                GROUP BY project.id;
            ";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        print("<table class='table'><thead>");
        print("<tr><th>Id</th><th>Project Name</th><th>Employees</th><th>Action</th></tr>");
        print("</thead>");
            while ($row = mysqli_fetch_assoc($result)) {
                print('<tr>' 
                    . '<td>' . $row['id'] . '</td>' 
                    . '<td>' . $row['name'] . '</td>' 
                    . '<td>' . $row['firstnames'] . '</td>'
                    . '<td>' . '<a href="?action=delete&id='  . $row['id'] . '"><button>DELETE PROJECT</button></a>' . '</td>'
                    . '</tr>');
            }
        } else {
            echo "0 results";
        }
        print("</table>");

        mysqli_close($conn);

?>

</body>
</html>