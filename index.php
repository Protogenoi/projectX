<?php
require_once(__DIR__ . '/classes/access_user/access_user_class.php');

$LOGIN_ACTIVATE = filter_input(INPUT_GET, 'activate', FILTER_SANITIZE_SPECIAL_CHARS);
$LOGIN_IDENT = filter_input(INPUT_GET, 'ident', FILTER_SANITIZE_SPECIAL_CHARS);
$LOGIN_VALIDATE = filter_input(INPUT_GET, 'validate', FILTER_SANITIZE_SPECIAL_CHARS);

$LOGIN_ID = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_SPECIAL_CHARS);

$LOGIN_REMEMBER = filter_input(INPUT_POST, 'remember', FILTER_SANITIZE_SPECIAL_CHARS);
$LOGIN_LOGIN = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_SPECIAL_CHARS);
$LOGIN_SUBMIT = filter_input(INPUT_POST, 'Submit', FILTER_SANITIZE_SPECIAL_CHARS);
$LOGIN_PASSWORD = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);

$my_access = new Access_user(false);
if (isset($LOGIN_ACTIVATE) && isset($LOGIN_IDENT)) {
    $my_access->auto_activation = true;
    $my_access->activate_account($LOGIN_ACTIVATE, $LOGIN_IDENT);
}
if (isset($LOGIN_VALIDATE) && isset($LOGIN_ID)) {
    $my_access->validate_email($LOGIN_VALIDATE, $LOGIN_ID);
}
if (isset($LOGIN_SUBMIT)) {
    $my_access->save_login = (isset($LOGIN_REMEMBER)) ? $LOGIN_REMEMBER : "no";
    $my_access->count_visit = false;
    $my_access->login_user($LOGIN_LOGIN, $LOGIN_PASSWORD);
}
$error = $my_access->the_msg;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ADL CRM | Login</title>
    <link rel="stylesheet" href="resources/templates/ADL/loginpage.css">
    <link rel="stylesheet" href="/resources/templates/fontawesome-free-5.10.2-web/css/all.min.css">
    <link rel="stylesheet" href="/resources/templates/MDB-Free_4.8.9/css/bootstrap.min.css">
    <link rel="stylesheet" href="/resources/templates/MDB-Free_4.8.9/css/mdb.min.css">
    <link rel="stylesheet" href="/resources/templates/MDB-Free_4.8.9/css/style.css">
    <script src="/resources/templates/MDB-Free_4.8.9/js/jquery-3.4.1.min.js"></script>
    <script src="/resources/templates/MDB-Free_4.8.9/js/popper.min.js"></script>
    <script src="/resources/templates/MDB-Free_4.8.9/js/bootstrap.min.js"></script>
    <script src="/resources/templates/MDB-Free_4.8.9/js/mdb.min.js"></script>
    <link href="/img/favicon.ico" rel="icon" type="image/x-icon"/>

</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">ADL</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText"
            aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

</nav>

<?php

$videoArray = [
    'Mt_Baker.mp4',
    'lines.mp4',
    'tropical.mp4'
];

$rand = rand(0, 2);

?>

<header>
    <div class="overlay"></div>
    <video playsinline="playsinline" autoplay="autoplay" muted="muted" loop="loop">
        <source src="/img/<?php echo $videoArray[$rand]; ?>" type="video/mp4">
    </video>
    <div class="container h-100">
        <div class="d-flex h-100 text-center align-items-center">
            <div class="w-100 text-white">

                <div class="container">
                    <div class="row">
                        <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">


                            <!-- Material form login -->
                            <div class="card card-cascade">
                                <h5 class="card-header blue-gradient white-text text-center py-4">
                                    <strong>Sign in</strong>
                                </h5>
                                <!--Card content-->
                                <div class="card-body px-lg-5 pt-0">

                                    <!-- Form -->
                                    <form class="text-center" style="color: #757575;"
                                          action="<?php echo filter_input(INPUT_SERVER, 'PHP_SELF',
                                              FILTER_SANITIZE_SPECIAL_CHARS); ?>" method="post" name="form1">

                                        <!-- Email -->
                                        <div class="md-form">

                                            <input type="text" id="login-username" name="login" class="form-control"
                                                   value="<?php echo (isset($LOGIN_LOGIN)) ? $LOGIN_LOGIN : $my_access->user; ?>">
                                            <label for="login-username">Username</label>
                                        </div>

                                        <!-- Password -->
                                        <div class="md-form">
                                            <input type="password" id="login-password" name="password"
                                                   class="form-control" value="<?php if (isset($LOGIN_PASSWORD)) {
                                                echo $LOGIN_PASSWORD;
                                            } ?>">
                                            <label for="login-password">Password</label>
                                        </div>


                                        <!-- Sign in button -->
                                        <button class="btn btn-outline-info btn-rounded btn-block my-4 waves-effect z-depth-0"
                                                type="submit" name="Submit">Sign in
                                        </button>

                                    </form>
                                    <!-- Form -->

                                </div>

                            </div>
                            <!-- Material form login -->

                        </div>
                    </div>
                </div>

            </div>
        </div>


    </div>
    </div>


</header>


<?php require_once(__DIR__ . '/app/Holidays.php'); ?>
</html>
