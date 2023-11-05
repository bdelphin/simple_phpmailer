<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// check if argv contains --help or -h
if (count($argv) === 1 || in_array("--help", $argv) || in_array("-h", $argv)) {
    echo "Simple PHP Mailer" . PHP_EOL;
    echo "Usage :" . PHP_EOL;
    echo 'mailer.php --subject "Mail subject" --message "Mail body, which can contain <b>HTML Content</b> !" --recipient john.doe@example.com' . PHP_EOL;
    
    die();
} else {
    if (in_array("--subject", $argv)) {
        $key = array_search("--subject", $argv);
        try {
            $subject = $argv[$key+1];
        } catch(Exception) {
            echo "Error, missing subject. Display help with mailer.php --help" . PHP_EOL;
            exit(1);
        }
    } else if (in_array("-s", $argv)) {
        $key = array_search("-s", $argv);
        try {
            $subject = $argv[$key+1];
        } catch(Exception) {
            echo "Error, missing subject. Display help with mailer.php --help" . PHP_EOL;
            exit(1);
        }
    } else {
        echo "Error, missing subject. Display help with mailer.php --help" . PHP_EOL;
        exit(1);
    }

    if (in_array("--message", $argv)) {
        $key = array_search("--message", $argv);
        try {
            $message = $argv[$key+1];
        } catch(Exception) {
            echo "Error, missing message body. Display help with mailer.php --help" . PHP_EOL;
            exit(1);
        }
    } else if (in_array("-m", $argv)) {
        $key = array_search("-m", $argv);
        try {
            $message = $argv[$key+1];
        } catch(Exception) {
            echo "Error, missing message body. Display help with mailer.php --help" . PHP_EOL;
            exit(1);
        }
    } else {
        echo "Error, missing message body. Display help with mailer.php --help" . PHP_EOL;
        exit(1);
    }

    // TODO : add multiple recipients

    if (in_array("--recipient", $argv)) {
        $key = array_search("--recipient", $argv);
        try {
            $recipient = $argv[$key+1];
        } catch(Exception) {
            echo "Error, missing recipient. Display help with mailer.php --help" . PHP_EOL;
            exit(1);
        }
    } else if (in_array("-r", $argv)) {
        $key = array_search("-r", $argv);
        try {
            $recipient = $argv[$key+1];
        } catch(Exception) {
            echo "Error, missing recipient. Display help with mailer.php --help" . PHP_EOL;
            exit(1);
        }
    } else {
        echo "Error, missing recipient. Display help with mailer.php --help" . PHP_EOL;
        exit(1);
    }
}

$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->isSMTP();
    $mail->Host = $_ENV['SMTP_HOST'];
    $mail->SMTPAuth = true;
    $mail->Username = $_ENV['SMTP_USERNAME'];
    $mail->Password = $_ENV['SMTP_PASSWORD'];
    if ($_ENV['SMTP_ENCRYPTION'] === "ENCRYPTION_STARTTLS") {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    } else if ($_ENV['SMTP_ENCRYPTION'] === "ENCRYPTION_STARTTLS") {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    } else {
        echo "Error : unknown SMTP_ENCRYPTION value specified in .env file." . PHP_EOL;
        exit(1);
    }
    $mail->Port = $_ENV['SMTP_PORT'];                                   

    $mail->setFrom($_ENV['FROM_ADDRESS'], $_ENV['FROM_NAME']);
    
    if ($_ENV['MAIL_FORMAT'] === "HTML") {
        $mail->isHTML(true);  
    } else if ($_ENV['MAIL_FORMAT'] === "PLAIN_TEXT") {
        $mail->isHTML(false);
    } else {
        echo "Error : unknown MAIL_FORMAT value specified in .env file." . PHP_EOL;
        exit(1);
    }

    $mail->addAddress($recipient);
    $mail->Subject = $subject;
    $mail->Body    = $message;
    $mail->AltBody = strip_tags($message);

    $mail->send();

    echo PHP_EOL . 'Message has been sent 🎉' . PHP_EOL;
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo} . PHP_EOL";
}