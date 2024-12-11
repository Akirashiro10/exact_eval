<?php
include('../connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if (isset($_POST['edit_code'])) {
      
            $newdoccode = $_POST['code'];
            $stmt = $pdo->prepare("UPDATE doc_name SET name = :code");
            $stmt->bindParam(':code', $newdoccode);
            $stmt->execute();
        }

        if (isset($_POST['edit_docmonth'])) {

            $newMonth = $_POST['docmonth'];
            $stmt = $pdo->prepare("UPDATE doc_name SET month = :month");
            $stmt->bindParam(':month', $newMonth);
            $stmt->execute();
        }
        if (isset($_POST['edit_docyear'])) {

            $newYear = $_POST['docyear'];
            $stmt = $pdo->prepare("UPDATE doc_name SET name = :year");
            $stmt->bindParam(':year', $newYear);
            $stmt->execute();
        }

        header("Location: settings.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
