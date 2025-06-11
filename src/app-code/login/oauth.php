<?php
session_start();
include "../config/config.php";

$auth_token = $_GET["auth"] ?? '';

if (!$auth_token) {
    echo json_encode(['status' => 'failure', 'msg' => 'Missing auth token']);
    exit;
}

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

curl_close($ch);

// Decode the JSON response
$data = json_decode($response, true);

if (isset($data['status']) && $data['status'] === "success") {
    // Set session data
    $_SESSION["username"] = $data["username"];
    $_SESSION["id"] = $data["id"];
    $_SESSION["email"] = $data["email"];
    $_SESSION["telegram_id"] = $data["telegram_id"];
    $_SESSION["user_token"] = $data["user_token"];
    $_SESSION["logged_in"]="true";
    $_SESSION["ssh_key_pw"]="";
    $user_token = $data["user_token"];

    // Connect to DB
    $conn = mysqli_connect($DB_SERVERNAME, $DB_USERNAME, $DB_PASSWORD, $DB_DATABASE);
    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    // Prepare and run query
    $sql = "SELECT id FROM users WHERE user_token = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $user_token);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt); // Needed for num_rows

        if (mysqli_stmt_num_rows($stmt) > 0) {
            // User exists
            mysqli_stmt_close($stmt);
            mysqli_close($conn);
            header("Location: /app/");
        } else {
            echo "This user does not exist! Log in <a href='/login.html'>here</a>";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "SQL prepare failed: " . mysqli_error($conn);
    }

    mysqli_close($conn);
} else {
    echo json_encode(['status' => 'failure', 'msg' => $data["msg"] ?? 'Invalid response']);
}
?>
