<?php
session_start();
header("Access-Control-Allow-Origin: *");

include "../../config.php";

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
];

try {
    $connection = new PDO("mysql:host=$host;dbname=$dbname", $dbuser, $dbpassword, $options);
} catch (PDOException $e) {
    echo json_encode(array("result" => false, "resultcode" => $e->getMessage()));
    die();
}

if (isset($_POST["method"])) {
    $method = $_POST["method"];
    switch ($method) {
        case "auth":
            if (isset($_SESSION["userData"]) && !empty($_SESSION["userData"])) {
                $stmt = $connection->prepare("SELECT id, username, isAdmin, FROM users WHERE username = ?;");
                $stmt->execute([$_SESSION["username"]]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result) {
                    $isAdmin = ($result["isAdmin"] == 1) ? true : false;
                    $authObj = array("userID" => $result["id"], "userName" => $result["username"], "isAdmin" => $isAdmin);
                    echo json_encode(array("result" => true) + array("authObj" => $authObj));
                } else {
                    echo resultCode(404);
                }
            } else {
                echo resultCode(403);
            }
            break;
        case "register":
            if (isset($_POST["userName"]) && isset($_POST["password"]) && isset($_POST["repeatPassword"])) {
                if (!empty($_POST["userName"]) && !empty($_POST["password"]) && !empty($_POST["repeatPassword"])) {
                    if ($_POST["userName"] != " " && $_POST["password"] != " ") {
                        $username = stripslashes($_POST["userName"]);
                        $password = stripslashes($_POST["password"]);
                        $repeatPassword = stripslashes($_POST["repeatPassword"]);

                        $uppercase = preg_match('@[A-Z]@', $password);
                        $lowercase = preg_match('@[a-z]@', $password);
                        $number    = preg_match('@[0-9]@', $password);
                        $specialChars = preg_match('@[^\w]@', $password);

                        if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
                            echo errorCode("Password should be at least 8 characters in length and should include at least one upper case letter, one lower case letter, one number, and one special character");
                        } else if ($password !== $repeatPassword) {
                            echo errorCode("Passwords don't match");
                        } else {
                            try {
                                $queryUser = $connection->prepare("SELECT username FROM users WHERE username = ?;");
                                $queryUser->execute([$username]);
                                $userNameExists = $queryUser->fetch(PDO::FETCH_ASSOC);

                                if ($userNameExists) {
                                    echo errorCode("Username alrready registered");
                                } else {
                                    if ($repeatPassword === $password) {
                                        $options = [
                                            'cost' => 12,
                                        ];
                                        $hashPassword = password_hash($password, PASSWORD_DEFAULT, $options);

                                        $query = $connection->prepare("INSERT INTO users(username, password) VALUES(?, ?);");
                                        $query->execute([$username, $hashPassword]);

                                        if ($query) {
                                            echo json_encode(array("result" => true));
                                        } else {
                                            echo errorCode("Something went wrong");
                                        }
                                    } else {
                                        echo errorCode("Passwords don't match");
                                    }
                                }
                            } catch (PDOException $e) {
                                echo json_encode(array("result" => false, "resultcode" => $e->getMessage(), "errorCode" => "Something went wrong"));
                            }
                        }
                    } else {
                        echo errorCode("Inputs can't be just spaces");
                    }
                } else {
                    echo errorCode("Inputs can't be empty");
                }
            } else {
                echo resultCode(400);
            }
            break;
        case "login":
            if (isset($_POST["userName"]) && isset($_POST["password"])) {
                if (!empty($_POST["userName"] && !empty($_POST["password"]))) {
                    $username = stripslashes($_POST["userName"]);
                    $password = stripslashes($_POST["password"]);

                    $stmt = $connection->prepare("SELECT id, username, password, isAdmin FROM users WHERE username = ?;");
                    try {
                        $stmt->execute([$username]);
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);

                        if ($row) {
                            $hashPassword = $row["password"];

                            if (password_verify($password, $hashPassword)) {

                                $_SESSION["username"] = $row["username"];
                                $_SESSION["userData"] = $row["id"] . $row["username"];
                                $isAdmin = ($row["isAdmin"] == 1) ? true : false;
                                $authObj = array("userID" => $row["id"], "userName" => $row["username"], "isAdmin" => $isAdmin);
                                echo json_encode(array("result" => true) + array("authObj" => $authObj));
                            } else {
                                echo errorCode("Wrong username or password");
                            }
                        } else {
                            echo errorCode("Wrong username or password");
                        }
                    } catch (PDOException $e) {
                        echo json_encode(array("result" => false, "resultcode" => $e->getMessage(), "errorCode" => "Something went wrong"));
                    }
                } else {
                    echo errorCode("Inputs can't be empty");
                }
            } else {
                echo errorCode("Something went wrong");
            }
            break;
    }
}

function resultCode(int $error)
{
    return json_encode(array("result" => false, "resultcode" => $error));
}

function errorCode(string $error)
{
    return json_encode(array("result" => false, "errorCode" => $error));
}

function resultErrorCode(string $errorCode, int $resultCode)
{
    return json_encode(array("result" => false, "resultCode" => $resultCode, "errorCode" => $errorCode));
}
