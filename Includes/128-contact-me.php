<?php

/*
 * 128 Contact form
 * @author Stefan George
 * @date 07/04/2020
 */
//include_once 'Includes/connect.php';
//$db = connect();

date_default_timezone_set('America/New_York');
$date = date("Y-m-d");
$time = strftime("%A. %X");
$returnStatus = array('status' => '0', 'message' => 'Error');

//$loggedIn = filter_input(INPUT_POST, 'loggedIn');
$formType = filter_input(INPUT_POST, 'formType', FILTER_SANITIZE_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_MAGIC_QUOTES);
$feedback = filter_input(INPUT_POST, 'feedback', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$loggedIn = '1';

if (!$email) {
    $returnStatus['message'] = "Invalid email format, please check email format.";
    echo json_encode($returnStatus);
    exit();
}
if (strlen($feedback) > 500) {
    $returnStatus['status'] = '-3';
    $returnStatus['message'] = "Maximum characters cannot exceed 500 characters.";
    echo json_encode($returnStatus);
    exit();
}

if ($loggedIn) {
    $to = "stefan.george@weeweefree.com";
    $subjectToStefan = "Feedback: " . $formType;
    $message = "Name: " . $name . "\n";
    $message .= "Category: " . $formType . "\n";
    $message .= "Email: " . $email . "\n";
    $message .= "Feedback: " . $feedback . "\n";
    $headers = "From: WeeWeeFree <support@weeweefree.com>" . "\r\n" .
            "Reply-to: support@weeweefree.com" . "\r\n" .
            "X-Mailer: PHP/" . phpversion();
    if (!mail($to, $subjectToStefan, $message, $headers)) {
        $fh = fopen('C:\128-feedback.txt', 'a');
        fwrite($fh, "\nDate: " . $date . "\nTime: " . $time . "\nan Cannot receive email from sender");
        fwrite($fh, "uid: " . $uid . "Feedback: " . $feedback . "\nEmail: " . $email . "\nName: " . $name . "\n\n");
        fclose($fh);
        $returnStatus['status'] = '-2';
        $returnStatus['message'] = "Cannot connect to server now, please try again later";
        echo json_encode($returnStatus);
        exit();
    } else {
//        $sender_subject = "Thank you for getting in touch";
//        $sender_message = "<html><body>We appreciate you contacting us. One ";
//        $sender_message .= "of our colleagues will get back to you shortly.";
//        $sender_message .= "<br><br>Have a great day!</body></html>";
//        $sender_header = "From: WeeWeeFree <support@weeweefree.com>" . "\r\n" .
//                "Reply-to: support@weeweefree.com" . "\r\n" .
//                "X-Mailer: PHP/" . phpversion()."\r\n".
//                'MIME-Version: 1.0'."\r\n".
//                'Content-Type: text/html; charset=ISO-8859-1'."\r\n";
//        if (!mail($email, $sender_subject, $sender_message, $sender_header)) {
//            $fh = fopen('C:\web-user-feedback.txt', 'a');
//            fwrite($fh, "\nDate: " . $date . "\nTime: " . $time . "\nan Cannot send email to sender");
//            fwrite($fh, "uid: " . $uid . "Feedback: " . $feedback . "\nEmail: " . $email . "\nName: " . $name . "\n\n");
//            fclose($fh);
//            $returnStatus['status'] = '-3';
//            $returnStatus['data'] = $recaptcha_json;
//            $returnStatus['message'] = "Cannot connect to server now, please try again later";
//            echo json_encode($returnStatus);
//            exit();
//        }
        $returnStatus['status'] = '1';
        $returnStatus['message'] = 'success';
        echo json_encode($returnStatus);
        exit();
    }
    $returnStatus['status'] = '1';
    $returnStatus['message'] = 'success';
    echo json_encode($returnStatus);
    exit();
} else {    //user not validated
    $returnStatus['status'] = '3';
    $returnStatus['message'] = "Cannot connect to server right now, please try again later.";
    echo json_encode($returnStatus);
    exit();
}
