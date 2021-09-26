<?php
require_once('login.php');
require_once(BASE_PATH.'/utils/auth/session.php');

$errors = [];

if($_POST) {
    $user = signin($_POST['username'], $_POST['password']);
    if($user) {
        if(!$user->isActive()){
            $errors['generic'] = "User is blocked";
        }
        else{
            initSession($user);
            header('Location: '.BASE_URL);
            die();
        }
    }
    else{
        $errors['generic'] = "Username or password maybe invalid";
    }
}
?>

<?php include_once(BASE_PATH.'/partials/header.php'); ?>
<section class="register">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h1>Login form</h1>
                <form method="POST" action="<?=$_SERVER['PHP_SELF']?>">
                    <div class="row">

                        <label class="col-md-4">Username:
                            <input name="username" class="col-md-8 form-control" required />
                        </label>

                    </div>
                    <div class="row">
                        <label class="col-md-4">Password:
                            <input name="password" type="password" required class="col-md-8 form-control" />
                        </label>
                        <span class="text text-danger"><?= $errors['generic'] ?? '' ?></span>
                        <button class="btn btn-success btn-block">Login</button>
                </form>
            </div>
        </div>
    </div>
</section>
<?php include_once(BASE_PATH.'/partials/footer.php') ?>