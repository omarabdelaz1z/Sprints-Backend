<?php
require_once('../../path.config.php');
require_once('register.php');
require_once(BASE_PATH.'/utils/auth/session.php');

$errors = [];

if($_POST) {
    if($_POST['password'] !== $_POST['confirm_password']){
        $errors['generic'] = "Password mismatch";
    }

    if(empty($errors)) {
        $user = signup($_POST);
        if(gettype($user) === 'array')
            $errors['generic'] = "Email is already used.";

        if(empty($errors)){
            initSession($user);
            header('Location: '.BASE_URL);
            die();
        }
    }
} ?>

<?php include_once(BASE_PATH.'/partials/header.php'); ?>
<section class="register">
    <div class="container">
      <div class="row">
        <div class="col-sm-12">
          <h1>Register form</h1>
          <form method="POST" action="<?=$_SERVER['PHP_SELF']?>">

            <div class="row">
              <label class="col-md-4">Name:
                  <input name="name" class="col-md-8 form-control" required value="<?= $data['name'] ?? '' ?>" />
              </label>
            </div>

            <div class="row">
              <label class="col-md-4">Username:
                  <input name="username" class="col-md-8 form-control" required value="<?= $data['username'] ?? '' ?>" />
              </label>
            </div>

            <div class="row">
              <label class="col-md-4">Password:
                  <input name="password" type="password" required class="col-md-8 form-control" />
              </label>
            </div>

            <div class="row">
              <label class="col-md-4">Confirm Password:
                  <input name="confirm_password" required type="password" class="col-md-8 form-control" />
              </label>
            </div>

            <div class="row">
                <label class="col-md-4">Email:
                    <input name="email" type="email" required class="col-md-8 form-control" value="<?= $data['email'] ?? '' ?>" />
                </label>
            </div>

            <span class="text text-danger"><?= $errors['generic'] ?? '' ?></span>
            <button class="btn btn-success btn-block">Register</button>
          </form>
        </div>
      </div>
    </div>
  </section>
<?php include_once(BASE_PATH.'/partials/footer.php'); ?>