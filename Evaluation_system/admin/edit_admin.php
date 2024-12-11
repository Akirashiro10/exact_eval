<?php
session_start(); 
include('../connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if (isset($_POST['edit_username'])) {
            $newUsername = $_POST['username'];

       
            if (!$newUsername) {
                echo "New username is not set.";
                exit();
            }
            if (!isset($_SESSION['username'])) {
                echo "Session username is not set.";
                exit();
            }
            $stmt = $pdo->prepare("UPDATE admin_list SET admin_name = :username");
            $stmt->bindParam(':username', $newUsername);
            if ($stmt->execute()) {
                echo "Username updated successfully.";

                $updateActiveSessionStmt = $pdo->prepare("UPDATE active_sessions SET user_id = :new_username WHERE user_id = :current_username");
                $updateActiveSessionStmt->bindParam(':new_username', $newUsername);
                $updateActiveSessionStmt->bindParam(':current_username', $_SESSION['username']);
                $updateActiveSessionStmt->execute();

                $_SESSION['username'] = $newUsername;
                session_write_close();
            } else {
                echo "Failed to update username.";
                exit();
            }
            $checkStmt = $pdo->prepare("SELECT * FROM admin_list WHERE admin_name = :current_username");
            $checkStmt->bindParam(':current_username', $_SESSION['username']);
            $checkStmt->execute();
            if ($checkStmt->rowCount() == 0) {
                echo "Admin not found with the current session username." .$_SESSION['username'];
                exit();
            }

       
            $stmt = $pdo->prepare("UPDATE admin_list SET admin_name = :username WHERE admin_name = :current_username");
            $stmt->bindParam(':username', $newUsername);
            $stmt->bindParam(':current_username', $_SESSION['username']);
            if ($stmt->execute()) {
                
                echo "Username updated successfully.";

                $_SESSION['username'] = $newUsername; 
                session_write_close(); 
            } else {
                echo "Failed to update username.";
                exit();
            }
        }

        if (isset($_POST['edit_password'])) {
            $currentPassword = $_POST['current_password'];
            $newPassword = $_POST['new_password'];
            $confirmPassword = $_POST['confirm_password'];

            $checkStmt = $pdo->prepare("SELECT admin_pass FROM admin_list WHERE admin_name = :username");
            $checkStmt->bindParam(':username', $_SESSION['username']);
            $checkStmt->execute();
            $admin = $checkStmt->fetch(PDO::FETCH_ASSOC);

            if (!$currentPassword === $admin['admin_pass']) {
                echo "Current password is incorrect.";
        header("Location: settings.php?error=Current password is incorrect.");

                
                exit();
            }

            if ($newPassword !== $confirmPassword) {
                echo "New passwords do not match.";
        header("Location: settings.php?error=New passwords do not match.");

                exit();
            }

            if (strlen($newPassword) < 8) {
                echo "New password must be at least 8 characters long.";
        header("Location: settings.php?error=New password must be at least 8 characters long.");
                
                exit();
            }

            $password = $_POST['new_password'];
            $updateStmt = $pdo->prepare("UPDATE admin_list SET admin_pass = :password WHERE admin_name = :username");
            $updateStmt->bindParam(':password', $password);
            $updateStmt->bindParam(':username', $_SESSION['username']);
            $updateStmt->execute();
            header("Location: settings.php?error=Password Updated Succesfully.");

            echo "Password updated successfully.";
            exit();
        }

        if (isset($_POST['edit_name'])) {
            $newName = $_POST['name'];
            $stmt = $pdo->prepare("UPDATE admin_list SET name = :name WHERE admin_name = :current_username");
            $stmt->bindParam(':name', $newName);
            $stmt->bindParam(':current_username', $_SESSION['username']);
            $stmt->execute();
        }

        if (isset($_POST['edit_role'])) {
            $newRole = $_POST['role'];
            $stmt = $pdo->prepare("UPDATE admin_list SET role = :role WHERE admin_name = :current_username");
            $stmt->bindParam(':role', $newRole);
            $stmt->bindParam(':current_username', $_SESSION['username']);
            $stmt->execute();
        }

        header("Location: settings.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
