<?php
function get_connection()
{
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $database = "xkcd";

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}

function get_all_verified_users()
{
    $conn = get_connection();
    $emails = array();

    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        return 501;
    }

    $query = "SELECT email FROM user WHERE verified = 1";
    if ($stmt = $conn->prepare($query)) {
        $stmt->execute();
        $result = $stmt->get_result();

        while ($info = $result->fetch_row()) {
            array_push($emails, $info[0]);
        }
        $stmt->close();
    }

    $conn->close();
    return $emails;
}

function get_random_comic()
{
    $url = sprintf("http://xkcd.com/%d/info.0.json", rand(1, 1000));
    $json = file_get_contents($url);
    $data = json_decode($json);
    return $data->img;
}

// here email is a list of all recipients
function send_mail_with_comic($email, $comic)
{
    $header = "From: atulpatare99@gmail.com\r\n";
    $header .= "MIME-Version: 1.0" . "\r\n";
    $header .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $header .= "X-Priority: 1\r\n";

    $to = $email;
    $subject = "Xkcd Newletter: Minutely Updates";
    $message = sprintf('
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>

        <style>
            .text-center {
                text-align: center;
            }
            
            .mt-3 {
                margin-top: 3rem;
            }
        
            .center {
                display: block;
                margin-left: auto;
                margin-right: auto;
                width: 50rem;
            }
        </style>
    </head>

    <body>
        <div class="text-center">
            <p>Here is your todays comic from
                <h2>Xkcd Newsletter</h2>
            </p>
        </div>
        <br>
        <img class="center" width="auto" src="%s"> </br>
       

        <div class="text-center mt-3">
            <small>
            To make sure you keep getting these emails,
             please add xkcd.newsletter.com to your address book or whitelist us.
             </br> Want out of the loop? <a href="/unsubscribe.php" >Unsubscribe </a>
            </small>
        </div>
    </body>

    </html>
    ', $comic);

    mail($to, $subject, $message, $header);
}


function run_service()
{
    $emails = get_all_verified_users();
    $comic = get_random_comic();
    send_mail_with_comic(implode(",", $emails), $comic);
}

run_service();
