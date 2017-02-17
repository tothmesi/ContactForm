<?php
 if ($_SERVER["REQUEST_METHOD"] != "POST"){
    die("invalid REQUEST_METHOD");
 }

$alert = "";
$alertComponents = array(
    "success" => "Your message was successfully sent.",
    "failed" => "Your message was not sent.",
    "validation" => "There were errors in your form:<br/>",
    "email" => "E-mail address is missing or invalid.<br/>",
    "subject" => "Subject is missing.<br/>",
    "message" => "Message is missing.<br/>");

$alertClasses = array(
    "success" => "alert alert-success",
    "danger" => "alert alert-danger"
    );

$mailTo = "toth.emese888@gmail.com";

$data = json_decode($_POST["data"], true);
extract($data);
$header = "From: ".$email;

// AJAX response json formátumban
if (validateEmail($email) && validateText($subject) && validateText($message)) {
    sleep(2); // server is too fast, loading indicator disappears immediately without sleep :)
    if (mail($mailTo, $subject, $message, $header)) {
        $alert = array("text" => $alertComponents["success"], "class" => $alertClasses["success"]);
    }

    else {
        $alert = array("text" => $alertComponents["failed"], "class" => $alertClasses["danger"]);
    }
}
else
{
    $alertText = $alertComponents["validation"];
    if (!validateEmail($_POST["email"]))
        $alertText = $alert.$alertComponents["email"];

    if (!validateText($_POST["subject"]))
        $alertText = $alert.$alertComponents["subject"];

    if (!validateText($_POST["message"]))
        $alertText = $alert.$alertComponents["message"];

    $alert = array("text" => $alertText, "class" => $alertClasses["danger"]);
}

echo json_encode($alert);


function validateEmail($email)
{
    $matches = array();
    $pattern = "/(.+)@(.)/";

    preg_match($pattern, $email, $matches);

    return ($matches) ? true : false;
}

function validateText($text)
{
    return (trim($text) != "");
}
?>

