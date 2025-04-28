<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the email from the form
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }

    // Set up email parameters
    $subject = "Test Email";
    $message = "This is a test email.";
    $headers = "From: info@suncraft.com.vn\r\n";

    // Send the email
    if (mail($email, $subject, $message, $headers)) {
        echo "Email sent successfully to $email!";
    } else {
        echo "Failed to send email.";
    }
} else {
    // Redirect back to form if accessed directly
    header("Location: index.php");
    exit();
}
?>