<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $fullname = filter_var($_POST["fullname"], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
    $subject = filter_var($_POST["subject"], FILTER_SANITIZE_STRING);
    $message = filter_var($_POST["message"], FILTER_SANITIZE_STRING);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email address');  window.location.href='Contact.html';</script>";
        exit();
    }

    // Check for empty fields
    if (empty($fullname) || empty($email) || empty($message)) {
        echo "<script>alert('Please fill in all required fields');</script>";
        exit();
    }

    // Connect to Database

    // The SYNTHAX is mysqli('host','username','password',dbname')
    $conn = new mysqli('localhost', 'root', '', 'fweshmusic');
    if ($conn->connect_error) {
        die('Connection Failed: ' . $conn->connect_error);
    }

    // Prepare and execute the SQL statement
    $stmt = $conn->prepare("INSERT INTO users_messages (fullname, email, subject, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $fullname, $email, $subject, $message);
    $stmt->execute();
    
    // Close database connection and the prepared statement
    $stmt->close();
    $conn->close();

    echo "<script>alert('Message sent!'); window.location.href='Contact.html';</script>";
    exit();
} else {
    // Redirect if the form is not submitted
    header("Location: Contact.html");
    exit();
}
