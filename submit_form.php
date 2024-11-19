<?php
include "conection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $message = $_POST['message'];
    $file = $_FILES['file'];

    // Validate file size and type
    if ($file['size'] > 500000 || !in_array($file['type'], ['image/jpeg', 'image/jpg'])) {
        die("File must be a JPEG and less than 500KB.");
    }

    // Save file to the server
    $uploadDir = 'uploads/';
    $fileName = uniqid() . '-' . basename($file['name']);
    $filePath = $uploadDir . $fileName;

    if (!move_uploaded_file($file['tmp_name'], $filePath)) {
        die("Failed to upload file.");
    }

    // Save data to the database
    $stmt = $con->prepare("INSERT INTO form_oops (name, email, mobile, message, file) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $mobile, $message, $fileName);

    if ($stmt->execute()) {
    // Send email to the user
    $to = $email;
    $subject = "Form Submission Received";
    $boundary = md5(time());

    // Email headers
    $headers = "From: siddharthsharma0022@gmail.com\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";

   

    // Attachment
    $fileContent = file_get_contents($filePath);
    $fileEncoded = chunk_split(base64_encode($fileContent));

    // $body .= "--$boundary\r\n";
    // $body .= "Content-Type: image/jpeg; name=\"$fileName\"\r\n";
    // $body .= "Content-Disposition: attachment; filename=\"$fileName\"\r\n";
    // $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
    // $body .= $fileEncoded . "\r\n";
    // $body .= "--$boundary--";

    // Send the email
    if (mail($to, $subject, $headers)) {
        echo "Form submitted successfully and email sent!";
    } else {
        echo "Form submitted, but email sending failed.";
    }
}
else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $con->close();
}
?>
