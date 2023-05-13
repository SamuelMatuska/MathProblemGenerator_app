<?php
header("Location: ../index.html");
session_start();

if (isset($_POST['register'])) {  
    //connectiong to database
    require_once 'connection.php';

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

    function checkGmail($email) {
        // Funkcia pre kontrolu, ci zadany email je gmail.
        if (!preg_match('/^[\w.+\-]+@gmail\.com$/', trim($email))) {
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
        $errmsg = "";

        // Validacia username
        if (checkEmpty($_POST['username']) === true) {
            $errmsg .= "<p class='error-message'>Zadajte username.</p>";
        } elseif (checkLength($_POST['username'], 6,32) === false) {
            $errmsg .= "<p class='error-message'>Username musi mat min. 6 a max. 32 znakov.</p>";
        } elseif (checkUsername($_POST['username']) === false) {
            $errmsg .= "<p class='error-message'>Username moze obsahovat iba velke, male pismena, cislice a podtrznik.</p>";
        }

        if (userExist($db, $_POST['username'], $_POST['studentID']) === true) {
            $errmsg .= "<p class='error-message' >Pouzivatel s tymto studentID / username uz existuje.</p>";
        }

        if (checkEmpty($_POST['password']) === true) {
            $errmsg .= "<p class='error-message' >Enter a password.</p>";
        } elseif (checkLength($_POST['password'], 8, 32) === false) {
            $errmsg .= "<p class='error-message'>Heslo musí mať 8 až 32 znakov.</p>";
        } elseif (!preg_match('/\d/', $_POST['password'])) {
            $errmsg .= "<p class='error-message'>Heslo musí obsahovať aspoň jednu číslicu.</p>";
        } elseif (!preg_match('/[a-zA-Z]/', $_POST['password'])) {
            $errmsg .= "<p class='error-message'>Heslo musí obsahovať aspoň jedno písmeno.</p>";
        }
        if (checkEmpty($_POST['first_name']) === true) {
            $errmsg .= "<p class='error-message'>Enter your first name.</p>";
        } elseif (!preg_match('/^[A-Z][a-z]{0,30}$/', $_POST['first_name'])) {
            $errmsg .= "<p class='error-message'>Meno musí začínať veľkým písmenom a byť dlhé najviac 30 znakov.</p>";
        }

        // Validacia lastname
        if (checkEmpty($_POST['last_name']) === true) {
            $errmsg .= "<p class='error-message'>Enter your last name.</p>";
        } elseif (!preg_match('/^[A-Z][a-z]{0,30}$/', $_POST['last_name'])) {
            $errmsg .= "<p class='error-message'>Priezvisko musí začínať veľkým písmenom a byť dlhé najviac 30 znakov.</p>";
        }

        if (empty($errmsg)) {
            $sql = "INSERT INTO users (first_name, last_name, username, studentID, password) VALUES (:first_name, :last_name, :username, :studentID, :password)";

            $first_name = $_POST['firstname'];
            $last_name = $_POST['lastname'];
            $username = $_POST['username'];
            $studentID = $_POST['studentID'];
            $hashed_password = password_hash($_POST['password'], PASSWORD_ARGON2ID);
            // Bind parametrov do SQL
            $stmt = $db->prepare($sql);

            $stmt->bindParam(":first_name", $first_name, PDO::PARAM_STR);
            $stmt->bindParam(":last_name", $last_name, PDO::PARAM_STR);
            $stmt->bindParam(":username", $username, PDO::PARAM_STR);
            $stmt->bindParam(":studentID", $studentID, PDO::PARAM_STR);
            $stmt->bindParam(":password", $hashed_password, PDO::PARAM_STR);

            $stmt->execute();

            unset($stmt);
        }
        unset($pdo);
        header("Location: ../index.html");
        exit;
    }  
}
?>