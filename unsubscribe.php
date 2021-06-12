<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="img/favicon.ico" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200&display=swap" rel="stylesheet">
    <title>Xkcd Newletter</title>
</head>

<body>

    <div class="center">
        <h2 class="title text-center">Welcome to xkcd newsletter service!</h2>
        <h4 class="sub-title text-center">Enter the email address to unsubscribe </h4>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">

            <label for="email">Enter your email</label>
            <input class="input-box" type="email" name="email" required>
            <input class="button" type="submit">

        </form>
    </div>

    <?php
    include_once('./scripts/user.php');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $email = $_POST['email'];
        if (empty($email)) {
            echo "<p class='center mt-3 error'>Email cannot be empty 😡</p>";
        } else {
            $res = delete_user($email);

            if ($res === 200)
                echo ("<p class='center mt-3 error'> Unsubscribed successfully. 😔</p>");

            else if ($res === 500)
                echo ("<p class='center mt-3 error'> Server error occurred or Email id not found😬 </p> ");
        }
    }
    ?>
</body>

</html>