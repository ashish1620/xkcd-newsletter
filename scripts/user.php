<?php

include_once('./db/conn.php');


function create_user($email)
{
    $conn = get_connection();

    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        return 501;
    }

    $token = gen_token();
    $query = "INSERT INTO user(email, token) VALUES(?, ?)";
    if ($stmt = $conn->prepare($query)) {

        $stmt->bind_param("ss", $email, $token);
        $stmt->execute();

        // dupl key error for email [1062]
        if ($stmt->errno === 1062)
            return 409;

        // everything went correct send mail
        $res = send_verification_mail($email, $token);
        if (!$res)
            return 500;

        $stmt->close();
    } else {
        return 500;
    }

    $conn->close();
    return 200;
}

function gen_token()
{
    $TOKEN_MIN = 24564;
    $TOKEN_MAX =  98996;

    return random_int($TOKEN_MIN, $TOKEN_MAX);
}

function check_token($email, $token)
{
    $conn = get_connection();

    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        return 500;
    }

    $query = "SELECT * FROM user WHERE email = ? AND token = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("ss", $email, $token);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {

            // update the verified status
            $update = "UPDATE user set verified = true WHERE email = ?";
            if ($upt_stmt = $conn->prepare($update)) {

                $upt_stmt->bind_param("s", $email);
                $upt_stmt->execute();

                // dupl key error for email [1062]
                if ($upt_stmt->errno === 1062)
                    return "User with same email exists";

                $upt_stmt->close();
            }

            return 200;
        } else {
            echo ("did not matched");
        }

        $stmt->close();
    } else {
        return 501;
    }

    $conn->close();
    return 501;
}

function send_verification_mail($email, $token)
{
    $header = "From: atulpatare99@gmail.com\r\n";
    $header .= "MIME-Version: 1.0" . "\r\n";
    $header .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $header .= "X-Priority: 1\r\n";

    $to = $email;
    $subject = "Xkcd Newletter Subscription code";
    $message = sprintf('
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>

    <body>
        <p>Here is your one time code for registering your mail on</p>
        <h2>Xkcd Newsletter</h2> <br>
        <h1><mark>%s</mark></h1>
    </body>

    </html>
    ', $token);

    return mail($to, $subject, $message, $header);
}
