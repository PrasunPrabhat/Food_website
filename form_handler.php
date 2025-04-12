<?php
// Include PHPMailer
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data and sanitize inputs
    $name = htmlspecialchars($_POST['name']);
    $visitor_email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $subject = htmlspecialchars($_POST['subject']);
    $message = nl2br(htmlspecialchars($_POST['message']));

    // Validate email format
    if (!filter_var($visitor_email, FILTER_VALIDATE_EMAIL)) {
        header("Location: index.html?status=error&message=Invalid email format");
        exit();
    }

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'rajkumar2000kir@gmail.com'; // Your Gmail
        $mail->Password   = 'doza tpzt kslk sypm'; // Use App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Email sender & recipient
        $mail->setFrom($visitor_email, $name); // Sender
        $mail->addAddress('rajkumar2000kir@gmail.com'); // Receiver
        $mail->addReplyTo($visitor_email, $name); // Reply to sender

        // Email content
        $mail->isHTML(true);
        $mail->Subject = "New Contact Form Submission: " . $subject;
        $mail->Body    = "
            <h3>New Contact Form Submission</h3>
            <p><strong>Name:</strong> $name</p>
            <p><strong>Email:</strong> $visitor_email</p>
            <p><strong>Subject:</strong> $subject</p>
            <p><strong>Message:</strong><br>$message</p>
        ";

        // Send the email
        if ($mail->send()) {
            header("Location: index.html?status=success&message=Your message has been sent successfully!");
        } else {
            header("Location: index.html?status=error&message=Message could not be sent. Please try again later.");
        }
        exit();
        
    } catch (Exception $e) {
        header("Location: index.html?status=error&message=Mailer Error: " . urlencode($mail->ErrorInfo));
        exit();
    }
} else {
    header("Location: index.html?status=error&message=Invalid request.");
    exit();
}
?>
