<?php
session_start();
//connectiong to database
require_once 'connection.php';

if (isset($_POST['login'])) {
    $sql = "SELECT first_name, last_name, username, studentID, password FROM users WHERE username = :username";

    $stmt = $db->prepare($sql);

    $stmt->bindParam(":username", $_POST["username"], PDO::PARAM_STR);

    if ($stmt->execute()) {
        if ($stmt->rowCount() == 1) {
            // Uzivatel existuje, skontroluj heslo.
            $row = $stmt->fetch();
            $hashed_password = $row["password"];

            if (password_verify($_POST['password'], $hashed_password)) {
                    // Uloz data pouzivatela do session.
                    $_SESSION["loggedin"] = true;
                    $_SESSION["username"] = $row['username'];
                    $_SESSION["firstname"] = $row['first_name'];
                    $_SESSION["lastname"] = $row['last_name'];
                    $_SESSION["studentID"] = $row['studentID'];

                    // Presmeruj pouzivatela na zabezpecenu stranku.
                    header("location: ../student.html");
            } else {
                echo "Erro 2";
            }
        } else {
            echo "Erro 1";
        }
    } else {
        echo "Ups. Nieco sa pokazilo!";
    }
    unset($stmt);
    unset($db);
}
?>