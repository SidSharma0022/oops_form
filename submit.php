<?php
include "conection.php";

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $message = $_POST['message'];


    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_name = $_FILES['file']['name'];
        $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);


        $base_dir = "filemanager/";
        $user_dir = $base_dir . $name . "/";
        $date_dir = $user_dir . date("Y-m-d") . "/";


        if (!is_dir($user_dir)) {
            mkdir($user_dir, 0777, true);
        }
        if (!is_dir($date_dir)) {
            mkdir($date_dir, 0777, true);
        }


        $file_path = $date_dir . uniqid("file_", true) . '.' . $file_ext;


        if (move_uploaded_file($file_tmp, $file_path)) {
            
            $sql = mysqli_query($con, "INSERT INTO form_oops(name, email, mobile, message, file) VALUES('$name', '$email', '$mobile', '$message', '$file_path')");

            if ($sql) {
                echo "Form submitted successfully, and file uploaded!";
            } else {
                echo "Database error: " . mysqli_error($con);
            }
        } else {
            echo "Failed to move the file to the directory.";
        }
    } else {
        echo "No file uploaded or file upload error.";
    }
header("Location: index.php");
}

?>
