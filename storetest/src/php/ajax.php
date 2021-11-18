<?php 
header("Access-Control-Allow-Origin: *");
session_start();

$connection = DBConnection("localhost", "root", "", "vrp");

$isLoggedIn = false;
$authObj = array();
$_SESSION["loggedIn"] = $isLoggedIn;
$_SESSION["authObj"] = $authObj;

if(isset($_POST["method"])) {
    $method = mysqli_real_escape_string($connection, $_POST["method"]);
    switch($method) {
        case "auth":
            if(!$_SESSION["loggedIn"]) {
                echo json_encode(array("result" => $_SESSION["loggedIn"]));
            } else {
                echo json_encode(array("result" => $_SESSION["loggedIn"]) + array("authObj" => $_SESSION["authObj"]));
            }
            break;
        case "register":
            if(isset($_POST["userName"]) && isset($_POST["password"])) {
                $username = mysqli_real_escape_string($connection, $_POST["userName"]);
                $password = mysqli_real_escape_string($connection, $_POST["password"]);

                $hashPassword = password_hash($password, PASSWORD_BCRYPT);

                $query = mysqli_query($connection, "INSERT INTO accounts(username, password, admin) VALUES('$username', '$hashPassword', '0');");

                if($query) {
                    echo json_encode(array("result" => true));
                }
                
            }
            break;
        case "login":
            if (!isset($_POST['userName'], $_POST['password'])) {
                echo json_encode(array("result" => false));
            }else {
                if ($stmt = $connection->prepare('SELECT username, password, admin FROM accounts WHERE username = ?')) {
                    
                    $stmt->bind_param('s', $_POST['userName']);
                    $stmt->execute();
                    $stmt->store_result();
                    
                    if ($stmt->num_rows > 0) {
                        $stmt->bind_result($username, $password, $admin);
                        $stmt->fetch();

                        if (password_verify($_POST['password'], $password)) {
                            session_regenerate_id();
                            $isLoggedIn = true;
                            $isAdmin = ($admin == 1) ? true : false;
                            $authObj = array("userName" => $username, "isAdmin" => $isAdmin);

                            echo json_encode(array("result" => true) + array("authObj" => $authObj));
                            
                        } else{
                            echo json_encode(array("result" => false));
                        }
                    } else {                        
                        echo json_encode(array("result" => false));
                    }
        
                    $stmt->close();
                }
            }
            break;
    }
}


function DBConnection($host, $DBUser, $DBPassword, $DBName) {
    $connection = @mysqli_connect($host,$DBUser,$DBPassword,$DBName);

    if(!$connection){
        echo "Error en la conexión";
    }
    return $connection;
    exit();
}