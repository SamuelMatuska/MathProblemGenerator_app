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
                    $_SESSION["username"] = $row['username'];
                    $_SESSION["firstname"] = $row['first_name'];
                    $_SESSION["lastname"] = $row['last_name'];
                    $_SESSION["studentID"] = $row['studentID'];

                    unset($_SESSION['error']);
                    if ($row['studentID'] == 187) {
                      $_SESSION['admin'] = true;
                      header("Location: ../admin.php");
                    } else {
                      $_SESSION['loggedin'] = true;
                      header("Location: ../student.php");
                    }
            } else {
              $errmsg .= "<p class='error-message'>Wrong password! Try again! </p>";
              $_SESSION['error'] = $errmsg;
              header("Location: ../index.php?error=true");
            }
        } else {
          $errmsg .= "<p class='error-message'>Username not found! Try again or create account!</p>";
          $_SESSION['error'] = $errmsg;
          header("Location: ../index.php?error=true");
        }
    } else {
      $errmsg .= "<p class='error-message'>Ups something went wrong! Try again! </p>";
      $_SESSION['error'] = $errmsg;
      header("Location: ../index.php?error=true");
    }
    unset($stmt);
    unset($db);
}
?>