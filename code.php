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
        <h2 class="title text-center">Finish your registration!</h2>
        <h4 class="sub-title text-center">Enter the code we just mailed you. Make sure you check <mark>spam</mark>
    folder too, just to make sure 😊. </h4>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">

            <label for="code">Enter the code</label>
            <input class="input-box" type="phone" name="code" required>
            <?= "<input type='hidden' name='email' value='{$_REQUEST['email']}' > "?>
            <input class="button" type="submit">

        </form>
    </div>

    <?php
    include_once('./scripts/user.php');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $code = $_POST['code'];
        $email = $_REQUEST['email'];
        
        if (empty($code)) {
            echo "<p class='center mt-3 error'>Code cannot be empty 😡</p>";
        } else {
            $res = check_token($email, $code);

            if ($res === 200)
            echo ("<p class='center mt-3 error'> Congratulation! you have been registered for comic updates 😎 </p> ");

            else if ($res === 500)
                echo ("<p class='center mt-3 error'> Server error occurred 😬</p> ");

            else if ($res === 501)
                echo ("<p class='center mt-3 error'> The code did not matched 😟 </p> ");
        }
    }
    ?>
</body>

</html>