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
                    <h4>We are creating the databases used in jakach server manager, please stand by</h4>
                </div>
                <div class="card-body">
					<p>If the creation fails, please wait a minute and try again. The database server might still be starting at the time.</p>
					<br>
                </div>
			<?php
			$success=1;
			include "../config/config.php";

			// Create connection
			$conn = new mysqli($DB_SERVERNAME, $DB_USERNAME, $DB_PASSWORD);

			// Check connection
			if ($conn->connect_error) {
				$success=0;
				die("Connection failed: " . $conn->connect_error);
			}

			// Create database
			$sql = "CREATE DATABASE IF NOT EXISTS $DB_DATABASE";
			if ($conn->query($sql) === TRUE) {
				echo '<br><div class="alert alert-success" role="alert">
					Database created successfully!
					</div>';
			} else {
				$success=0;
				echo '<br><div class="alert alert-danger" role="alert">
						Error creating database: ' . $conn->error .'
				</div>';
			}

			$conn->close();

			// Connect to the new database
			$conn = new mysqli($DB_SERVERNAME, $DB_USERNAME, $DB_PASSWORD,$DB_DATABASE);

			// Check connection
			if ($conn->connect_error) {
				$success=0;
				die("Connection failed: " . $conn->connect_error);
			}

			// Create user table
			$sql="CREATE TABLE IF NOT EXISTS users (
			    id INT AUTO_INCREMENT PRIMARY KEY,
			    user_token VARCHAR(300),
			    permissions VARCHAR(255)
			);";


			if ($conn->query($sql) === TRUE) {
				echo '<br><div class="alert alert-success" role="alert">
						Table users created successfully!
				</div>';
			} else {
				$success=0;
				echo '<br><div class="alert alert-danger" role="alert">
						Error creating table users: ' . $conn->error .'
				</div>';
			}

			$sql="CREATE TABLE IF NOT EXISTS ssh_keys (
                            id INT AUTO_INCREMENT PRIMARY KEY,
			    key_value VARCHAR(500)
                        );";


                        if ($conn->query($sql) === TRUE) {
                                echo '<br><div class="alert alert-success" role="alert">
                                                Table ssh_keys created successfully!
                                </div>';
                        } else {
                                $success=0;
                                echo '<br><div class="alert alert-danger" role="alert">
                                                Error creating table ssh_keys: ' . $conn->error .'
                                </div>';
                        }

			$sql="CREATE TABLE IF NOT EXISTS servers (
			    id INT AUTO_INCREMENT PRIMARY KEY,
			    server_ip VARCHAR(255)
			);";


			if ($conn->query($sql) === TRUE) {
				echo '<br><div class="alert alert-success" role="alert">
						Table servers created successfully!
				</div>';
			} else {
				$success=0;
				echo '<br><div class="alert alert-danger" role="alert">
						Error creating servers: ' . $conn->error .'
				</div>';
			}

			$sql="CREATE TABLE IF NOT EXISTS tasks (
                            id INT AUTO_INCREMENT PRIMARY KEY,
                            target_ip VARCHAR(300),
			    command VARCHAR(255),
			    status INT
                        );";


                        if ($conn->query($sql) === TRUE) {
                                echo '<br><div class="alert alert-success" role="alert">
                                                Table tasks created successfully!
                                </div>';
                        } else {
                                $success=0;
                                echo '<br><div class="alert alert-danger" role="alert">
                                                Error creating table tasks: ' . $conn->error .'
                                </div>';
                        }

			if($success!==1){
				echo '<br><div class="alert alert-danger" role="alert">
						There was an error creating the databases. Please try again or contact support at: <a href="mailto:info.jakach@gmail.com">info.jakach@gmail.com</a>
				</div>';
			}else{
				echo '<br><div class="alert alert-success" role="alert">
						Database created successfully!
						Continue <a href="connect_account.php">here</a>
				</div>';
			}

			$conn->close();
			?>
			</div>
		</div>
    </div>
</div>
</body>
</html>
