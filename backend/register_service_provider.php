<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $business_name = $_POST['businessName'];
    $business_phone = $_POST['phone'];
    $experience = $_POST['experience'];
    $service_radius = 10; // Default
    $services_offered = $_POST['serviceType'];

    $sql = "INSERT INTO service_providers 
            (name, email, password, business_name, business_phone, experience, service_radius, services_offered) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Database error: " . $conn->error);
    }

    $stmt->bind_param(
        "sssssiss",
        $name,
        $email,
        $password,
        $business_name,
        $business_phone,
        $experience,
        $service_radius,
        $services_offered
    );

    if ($stmt->execute()) {

        header("Location: ../frontend/dashboard-service-provider.html");
        exit();

    } else {

        echo "Error: " . $stmt->error;

    }

    $stmt->close();
}

$conn->close();
?>