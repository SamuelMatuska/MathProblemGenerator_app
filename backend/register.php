<?php
session_start();
//connectiong to database
require_once 'connection.php';

if (isset($_POST['register'])) {  
    // ------- Pomocne funkcie -------
    function checkEmpty($field) {
        // Funkcia pre kontrolu, ci je premenna po orezani bielych znakov prazdna.
        // Metoda trim() oreze a odstrani medzery, tabulatory a ine "whitespaces".
        if (empty(trim($field))) {
            return true;
        }
        return false;
    }

    function checkLength($field, $min, $max) {
        // Funkcia, ktora skontroluje, ci je dlzka retazca v ramci "min" a "max".
        // Pouzitie napr. pre "login" alebo "password" aby mali pozadovany pocet znakov.
        $string = trim($field);     // Odstranenie whitespaces.
        $length = strlen($string);      // Zistenie dlzky retazca.
        if ($length < $min || $length > $max) {
            return false;
        }
        return true;
    }

    function checkUsername($username) {
        // Funkcia pre kontrolu, ci username obsahuje iba velke, male pismena, cisla a podtrznik.
        if (!preg_match('/^[a-zA-Z0-9_]+$/', trim($username))) {
            return false;
        }
        return true;
    }

    function userExist($db, $username, $studentID) {
        // Funkcia pre kontrolu, ci pouzivatel s "login" alebo "email" existuje.
        $exist = false;

        $param_username = trim($username);
        $param_studentID = trim($studentID);

        $sql = "SELECT id FROM users WHERE username = :username OR studentID = :studentID";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
        $stmt->bindParam(":studentID", $param_studentID, PDO::PARAM_STR);
  
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $exist = true;
        }

        unset($stmt);

        return $exist;
    }

    // ------- ------- ------- -------
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $username = $_POST['username'];
        $studentID = $_POST['studentID'];
        $password = $_POST['password'];

        $errmsg = "";

        // Validacia username
        if (checkEmpty($_POST['username']) === true) {
            $errmsg .= "<p class='error-message'>Enter username!</p>";
        } elseif (checkLength($_POST['username'], 6,32) === false) {
            $errmsg .= "<p class='error-message'>Username must be long atleat 6 characters and maximum 32 characters! </p>";
        } elseif (checkUsername($_POST['username']) === false) {
            $errmsg .= "<p class='error-message'>Username have to be only capital letters, small letters, number or _ !!</p>";
        }

        if (userExist($db, $_POST['username'], $_POST['studentID']) === true) {
            $errmsg .= "<p class='error-message' >User with same studentID/username already exists!</p>";
        }

        if (checkEmpty($_POST['password']) === true) {
            $errmsg .= "<p class='error-message' >Enter a password!</p>";
        } elseif (checkLength($_POST['password'], 8, 32) === false) {
            $errmsg .= "<p class='error-message'>Password must be long 8 to 32 characters!</p>";
        } elseif (!preg_match('/\d/', $_POST['password'])) {
            $errmsg .= "<p class='error-message'>Password needs to contain at least one number!</p>";
        } elseif (!preg_match('/[a-zA-Z]/', $_POST['password'])) {
            $errmsg .= "<p class='error-message'>Password needs to contain at least one letter!</p>";
        }
        if (checkEmpty($_POST['firstname']) === true) {
            $errmsg .= "<p class='error-message'>Enter your first name.</p>";
        } elseif (!preg_match('/^[A-Z][a-z]{0,30}$/', $_POST['firstname'])) {
            $errmsg .= "<p class='error-message'>Name needs to begin with capital letter and maximum lenght is 30 characters!</p>";
        }

        // Validacia lastname
        if (checkEmpty($_POST['lastname']) === true) {
            $errmsg .= "<p class='error-message'>Enter your last name.</p>";
        } elseif (!preg_match('/^\p{Lu}\p{Ll}{0,30}$/u', $_POST['lastname'])) {
            $errmsg .= "<p class='error-message'>Last name needs to begin with capital letter and maximum lenght is 30 characters!</p>";
        }        

        echo $errmsg;

        if (empty($errmsg)) {
            $sql = "INSERT INTO users (first_name, last_name, username, studentID, password) VALUES (:firstname, :lastname, :username, :studentID, :password)";
            // Bind parametrov do SQL
            $hashed_password = password_hash($_POST['password'], PASSWORD_ARGON2ID);
            $stmt = $db->prepare($sql);
            $stmt->bindParam(":firstname", $firstname, PDO::PARAM_STR);
            $stmt->bindParam(":lastname", $lastname, PDO::PARAM_STR);
            $stmt->bindParam(":username", $username, PDO::PARAM_STR);
            $stmt->bindParam(":studentID", $studentID, PDO::PARAM_STR);
            $stmt->bindParam(":password", $hashed_password, PDO::PARAM_STR);

            $stmt->execute();

            unset($stmt);
            unset($_SESSION['error']);
            $_SESSION['success'] = "Registration successful!";
            header("Location: ../index.php");
            exit;
        }else {
            $_SESSION['error'] = $errmsg;
            header("Location: ../index.php?error=true");
            exit;
        }
        unset($pdo);
    }  
}
?>