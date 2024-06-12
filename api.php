<?php
$servername = "localhost";
$username = "root";
$password = "password";
$dbname = "user_management";

$conn = new mysqli($servername, $username, $password, $dbname);
if($conn -> connect_error){ //This is a property of the $conn object. It holds the error message if there is a connection error.
    die("Connection failed: " . $conn->connect_error); //die is a function in PHP that terminates the script. It also outputs the message before termination.
}
header('Content-Type: application/json'); //This is a PHP function that sends a raw HTTP header to the client.
switch($_SERVER['REQUEST_METHOD']){ //$_SERVER is a superglobal array in PHP that contains information about headers, paths, and script locations.
    case 'GET':
        $sql = "SELECT * FROM users";
        $result = $conn->query($sql);
        $users = []; //users will hold an array of user data.
        while($row = $result->fetch_assoc()){ //This fetches the next row of the result set as an associative array.
            $users[] = $row;
        }
        echo json_encode($users);
        break;
    case 'POST':
        $data=json_decode(file_get_contents("php://input"),true);
        $sql = "INSERT INTO users (name, position, office, age, startDate, salary) VALUES ('{$data['name']}', '{$data['position']}', '{$data['office']}') , '{$data['age']}', '{$data['startDate']}', '{$data['salary']}')";
        if($conn->query($sql)===TRUE){
            echo json_encode(["id" => $conn->insert_id]);
        }
        else{
            echo json_encode(["error"=> $conn->error]);
        }
        break;
    case 'DELETE':
        $id = $_GET['id'];
        $sql = "DELETE FROM users WHERE id=$id";
        if ($conn->query($sql)===TRUE){
            echo json_encode(["message"=>"User deleted successfully"]);
        }
        else{
            echo json_encode(["error" => $conn->error]);
        }
        break;
}

$conn->close();
?>
