<?php
 session_start();
 $username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Admin | Settings</title>
    <link rel="icon" href="../images/system-logo.png">

    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .settings-button{
            background-color: darkblue;
            min-width: 120px; 
            margin-right: 0px;
            margin-left: -10px;
            padding-left: 15px;
            border-radius: 10px;
        }
        .settings-button2 {
           
            min-width: 120px;
            margin-right: 0px;
            margin-left: -10px;
            padding-left: 15px;
            border-radius: 10px;
        }
        .admin-container {
            margin-top: 20px;
            margin-left: 15px;
            width: 1250px;
            display: flex;
        }
        .admin-box {
            background-color: #f0f0f0;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            margin: 50px auto;
            text-align: center;
        }
        .admin-form {
            position: relative;
        }
        .admin-form input {
            margin-bottom: 10px;
            width: calc(100% - 40px); 
            padding: 8px;
            box-sizing: border-box;
        }
        .admin-form i {
            position: absolute;
            right: 0px;
            top: calc(50% - 8px); 
            cursor: pointer;
            color: black;
        }
        .password-feedback {
            color: green;
            height: 20px; 
            display: flex;
            align-items: center; 
        }
        .password-feedback.hidden {
            visibility: hidden;
        }
    </style>
</head>

<body>
    <?php include 'sidebar.php'; ?>
    <div class="content">
        <h1>SETTINGS</h1>
        <div class="admin-container">
            <div class="admin-box">
                <?php
                include('../connection.php');

                $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $stmt = $pdo->query("SELECT * FROM admin_list");
                $admin = $stmt->fetch(PDO::FETCH_ASSOC);

                $stmt2 = $pdo->query("SELECT * FROM doc_name");
                $document = $stmt2->fetch(PDO::FETCH_ASSOC);
                if ($admin) {
                    echo "<h2>Admin Log-In Credentials</h2>";
                    echo "<h3 style='margin-bottom: 34px'>Username: {$admin['admin_name']}</h3>";

                    echo "<form class='admin-form' method='post' action='edit_admin.php'>";
                    echo "<input type='text' name='username' placeholder='New Username' required><br>";
                    echo "<button type='submit' name='edit_username'>Edit Username</button>";
                    echo "</form>";

                    echo "<h3>Password: </h3>";
                    echo "<form class='admin-form' method='post' action='edit_admin.php' id='adminPasswordForm'>";
                    echo "<div style='position:relative;'>";
                    echo "<input type='password' id='current_admin_password' name='current_password' placeholder='Current Password' required>";
                    echo "<i class='fas fa-eye' id='toggleCurrentAdminPassword'></i>";
                    echo "</div>";
                    echo "<span id='current_password_feedback' class='password-feedback hidden'></span>";
                    echo "<div style='position:relative;'>";
                    echo "<input type='password' id='new_admin_password' name='new_password' placeholder='New Password' minlength='8' required>";
                    echo "<i class='fas fa-eye' id='toggleNewAdminPassword'></i>";
                    echo "</div>";
                    echo "<span id='new_password_feedback' class='password-feedback hidden'></span>";
                    echo "<div style='position:relative;'>";
                    echo "<input type='password' id='confirm_admin_password' name='confirm_password' placeholder='Re-Type New Password' required>";
                    echo "<i class='fas fa-eye' id='toggleConfirmAdminPassword'></i>";
                    echo "</div>";
                    echo "<span id='confirm_password_feedback' class='password-feedback hidden'></span>";
                    echo "<button type='submit' name='edit_password' class='settings-button2'>Edit Password</button>";
                    echo "</form>";
                } else {
                    echo "<p>No admin found.</p>";
                }
                ?>
                <?php if (isset($_GET['error'])): ?>
                    <h3><?php echo $_GET['error']; ?></h3>
                    <?php unset($_GET['error']); ?>
                <?php endif; ?>
            </div>

            <div class="admin-box">
                <?php
                if ($admin) {
                    echo "<h2>Admin Informations</h2>";

                    echo "<h3 style='margin-bottom: 34px'>Name: {$admin['name']}</h3>"; 

                    echo "<form class='admin-form' method='post' action='edit_admin.php'>";
                    echo "<input type='text' name='name' placeholder='New Name' required><br>";
                    echo "<button type='submit' name='edit_name'>Edit Name</button>";
                    echo "</form>";

                    echo "<h3 style='margin-top: 50px'>Role: {$admin['role']}</h3>";

                    echo "<form class='admin-form' method='post' action='edit_admin.php'>";
                    echo "<input type='text' name='role' placeholder='New Role' required><br>";
                    echo "<button type='submit' name='edit_role'>Edit Role</button>";
                    echo "</form>";
                } else {
                    echo "<p>No admin found.</p>";
                }
                ?>
            </div>

            <div class="admin-box">
                <?php
                if ($document) {
                    echo "<h2 style='font-size:20px'>Document Number Management <br>[".$document['name'] . $document['month'] . $document['year']."]</h2>";
                    echo "<h3>Code: {$document['name']}</h3>";

                    echo "<form class='admin-form' method='post' action='edit_doc_num.php'>";
                    echo "<input type='text' name='code' placeholder='New Code'><br>";
                    echo "<button type='submit' name='edit_code'>Edit Doc Name</button>";
                    echo "</form>";

                    echo "<h3>Month: {$document['month']}</h3>";

                    echo "<form class='admin-form' method='post' action='edit_doc_num.php'>";
                    echo "<input type='text' name='docmonth' placeholder='Month'><br>";
                    echo "<button type='submit' name='edit_docmonth'>Edit Month</button>";
                    
                    echo "<h3>Year: {$document['year']}</h3>";

                    echo "<form class='admin-form' method='post' action='edit_doc_num.php'>";
                    echo "<input type='text' name='docyear' placeholder='Year'><br>";
                    echo "<button type='submit' name='edit_docyear'>Edit Year</button>";
                    echo "</form>";
                } else {
                    echo "<p>No admin found.</p>";
                }
                ?>
            </div>
        </div>
    </div>
</body>

<script>
    const currentAdminPasswordInput = document.getElementById("current_admin_password");
    const newAdminPasswordInput = document.getElementById("new_admin_password");
    const confirmAdminPasswordInput = document.getElementById("confirm_admin_password");

    const currentAdminPasswordFeedback = document.getElementById("current_password_feedback");
    const newAdminPasswordFeedback = document.getElementById("new_password_feedback");
    const confirmAdminPasswordFeedback = document.getElementById("confirm_password_feedback");

    currentAdminPasswordInput.addEventListener("input", function() {
        checkAdminPassword();
    });

    newAdminPasswordInput.addEventListener("input", function() {
        checkAdminNewPassword();
        checkAdminConfirmPassword();
    });

    confirmAdminPasswordInput.addEventListener("input", function() {
        checkAdminConfirmPassword();
    });

    function checkAdminPassword() {
        const currentPassword = currentAdminPasswordInput.value;
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "check_admin_password.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    const response = xhr.responseText.trim();
                    if (response === "success") {
                        currentAdminPasswordFeedback.textContent = "Password is correct.";
                        currentAdminPasswordFeedback.style.color = "green";
                        currentAdminPasswordFeedback.classList.remove("hidden");
                    } else {
                        currentAdminPasswordFeedback.textContent = "Password is incorrect.";
                        currentAdminPasswordFeedback.style.color = "red";
                        currentAdminPasswordFeedback.classList.remove("hidden");
                    }
                } else {
                    // Handle error
                }
            }
        };
        xhr.send("current_password=" + encodeURIComponent(currentPassword));
    }

    function checkAdminNewPassword() {
        const newPassword = newAdminPasswordInput.value;
        if (newPassword.length >= 8) {
            newAdminPasswordFeedback.textContent = "Password is strong.";
            newAdminPasswordFeedback.style.color = "green";
            newAdminPasswordFeedback.classList.remove("hidden");
        } else {
            newAdminPasswordFeedback.textContent = "Password is too short.";
            newAdminPasswordFeedback.style.color = "red";
            newAdminPasswordFeedback.classList.remove("hidden");
        }
    }

    function checkAdminConfirmPassword() {
        const newPassword = newAdminPasswordInput.value;
        const confirmPassword = confirmAdminPasswordInput.value;

        if (confirmPassword.length > 0) {
            if (newPassword === confirmPassword && newPassword.length >= 8) {
                confirmAdminPasswordFeedback.textContent = "Passwords match.";
                confirmAdminPasswordFeedback.style.color = "green";
                confirmAdminPasswordFeedback.classList.remove("hidden");
            } else {
                confirmAdminPasswordFeedback.textContent = "Passwords do not match or length is less than 8 characters.";
                confirmAdminPasswordFeedback.style.color = "red";
                confirmAdminPasswordFeedback.classList.remove("hidden");
            }
        } else {
            confirmAdminPasswordFeedback.textContent = "";
            confirmAdminPasswordFeedback.classList.add("hidden");
        }
    }

    const toggleCurrentAdminPassword = document.getElementById("toggleCurrentAdminPassword");
    toggleCurrentAdminPassword.addEventListener("click", function() {
        if (currentAdminPasswordInput.type === "password") {
            currentAdminPasswordInput.type = "text";
            toggleCurrentAdminPassword.classList.remove("fa-eye");
            toggleCurrentAdminPassword.classList.add("fa-eye-slash");
        } else {
            currentAdminPasswordInput.type = "password";
            toggleCurrentAdminPassword.classList.remove("fa-eye-slash");
            toggleCurrentAdminPassword.classList.add("fa-eye");
        }
    });

    const toggleNewAdminPassword = document.getElementById("toggleNewAdminPassword");
    toggleNewAdminPassword.addEventListener("click", function() {
        if (newAdminPasswordInput.type === "password") {
            newAdminPasswordInput.type = "text";
            toggleNewAdminPassword.classList.remove("fa-eye");
            toggleNewAdminPassword.classList.add("fa-eye-slash");
        } else {
            newAdminPasswordInput.type = "password";
            toggleNewAdminPassword.classList.remove("fa-eye-slash");
            toggleNewAdminPassword.classList.add("fa-eye");
        }
    });

    const toggleConfirmAdminPassword = document.getElementById("toggleConfirmAdminPassword");
    toggleConfirmAdminPassword.addEventListener("click", function() {
        if (confirmAdminPasswordInput.type === "password") {
            confirmAdminPasswordInput.type = "text";
            toggleConfirmAdminPassword.classList.remove("fa-eye");
            toggleConfirmAdminPassword.classList.add("fa-eye-slash");
        } else {
            confirmAdminPasswordInput.type = "password";
            toggleConfirmAdminPassword.classList.remove("fa-eye-slash");
            toggleConfirmAdminPassword.classList.add("fa-eye");
        }
    });
</script>

</html>
