<?php
// ne lehessen kívülről hívni, de a tesztelést megakasztja
if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST") {   

// lehetséges szerver oldali hibaüzenetek
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


$data = json_decode($_POST["data"], true);
extract($data);
$header = "From: ".$email;

$mailTo = $email; // publikus kódban saját magának küldi

// AJAX response json formátumban
// ha a validálás sikeres, megpróbál emailt küldeni, megfelelő visszajelzést küld vissza, amit a UI kezel

if (validateEmail($email) && validateText($subject) && validateText($message)) {
    sleep(2); // túl gyors a szerver, nem látszik a loading indicator
    if (mail($mailTo, $subject, $message, $header)) {
        $alert = array("text" => $alertComponents["success"], "class" => $alertClasses["success"]);
    }

    else {
        $alert = array("text" => $alertComponents["failed"], "class" => $alertClasses["danger"]);
    }
}

// ha a szerver oldali validálás hibát talál, megfelelő hibaüzenetet küld vissza, amit a UI kezel
else
{
    $alertText = $alertComponents["validation"];
    if (!validateEmail($email))
        $alertText = $alert.$alertComponents["email"];

    if (!validateText($subject))
        $alertText = $alert.$alertComponents["subject"];

    if (!validateText($message))
        $alertText = $alert.$alertComponents["message"];

    $alert = array("text" => $alertText, "class" => $alertClasses["danger"]);
}

echo json_encode($alert);
}

function validateEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validateText($text)
{
    return (trim($text) != "");
}
?>

