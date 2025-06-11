<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
         <title>Jakach Server Manager Installer</title>
</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Please connect you Jakach account by clicking on the button below</h4>
                </div>
                <div class="card-body">
			<a href="https://auth.jakach.ch/?send_to=https://manager.jakach.ch/install/connect_account.php" class="btn btn-secondary">Log in using Jakach login</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

<?php
session_start();

if (isset($_GET["auth"])) {
    $auth_token = $_GET["auth"];

    // Check the auth token against Jakach login API
    $check_url = "https://auth.jakach.ch/api/auth/check_auth_key.php?auth_token=" . urlencode($auth_token);

    // Initialize cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $check_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute cURL and get the response
    $response = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
        die("cURL Error: " . curl_error($ch));
    }

    // Close cURL
    curl_close($ch);

    // Decode the JSON response
    $data = json_decode($response, true);

    // Check if the response contains a valid status
    if (isset($data['status']) && $data['status'] == "success") {
        // Successful authentication: login the user
        $_SESSION["username"] = $data["username"];
        $_SESSION["id"] = $data["id"];
        $_SESSION["email"] = $data["email"];
        $_SESSION["telegram_id"] = $data["telegram_id"];
        $_SESSION["user_token"] = $data["user_token"];

        echo '<br><div class="alert alert-success" role="alert">
                All done, you can now start using cyberhex! <a href="/login.html">Go to login page</a>
              </div>';

	include "../config/config.php";
        $conn = new mysqli($DB_SERVERNAME, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "INSERT INTO users (user_token, permissions) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $sql);

        $token = $_SESSION["user_token"];
        $perms = "11111111111111";

        mysqli_stmt_bind_param($stmt, "ss", $token, $perms);
        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);
        mysqli_close($conn);

    } else {
        echo("auth failed");
    }
}
?>





