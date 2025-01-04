<?php

// This script is the form processing script for the contact form.
// It checks that all the required fields have been filled in and
// sends an email to the specified email address.

// The error message that will be displayed to the user if some
// of the required fields have not been filled in.
$errorMSG = "";

// NAME
if (empty($_POST["name"])) {
    // If the name field has not been filled in, add an error message
    $errorMSG = "Name is required ";
} else {
    // If the name field has been filled in, get the value from the post
    $name = $_POST["name"];
}

// EMAIL
if (empty($_POST["email"])) {
    // If the email field has not been filled in, add an error message
    $errorMSG .= "Email is required ";
} else {
    // If the email field has been filled in, get the value from the post
    $email = $_POST["email"];
}

// MSG Guest
if (empty($_POST["guest"])) {
    // If the guest field has not been filled in, add an error message
    $errorMSG .= "Subject is required ";
} else {
    // If the guest field has been filled in, get the value from the post
    $guest = $_POST["guest"];
}

// MSG Event
if (empty($_POST["event"])) {
    // If the event field has not been filled in, add an error message
    $errorMSG .= "Subject is required ";
} else {
    // If the event field has been filled in, get the value from the post
    $event = $_POST["event"];
}


// MESSAGE
if (empty($_POST["message"])) {
    // If the message field has not been filled in, add an error message
    $errorMSG .= "Message is required ";
} else {
    // If the message field has been filled in, get the value from the post
    $message = $_POST["message"];
}

// The email address that the form will send to
$EmailTo = "armanmia7@gmail.com";
// The subject of the email
$Subject = "New Message Received";

// Prepare the body of the email
$Body = "";
$Body .= "Name: ";
$Body .= $name;
$Body .= "\n";
$Body .= "Email: ";
$Body .= $email;
$Body .= "\n";
$Body .= "guest: ";
$Body .= $guest;
$Body .= "\n";
$Body .= "event: ";
$Body .= $event;
$Body .= "\n";
$Body .= "Message: ";
$Body .= $message;
$Body .= "\n";

// Send the email using the mail() function
$success = mail($EmailTo, $Subject, $Body, "From:".$email);

// Redirect to the success page if the email has been sent
if ($success && $errorMSG == ""){
   echo "success";
}else{
    if($errorMSG == ""){
        echo "Something went wrong :(";
    } else {
        echo $errorMSG;
    }
}

?>