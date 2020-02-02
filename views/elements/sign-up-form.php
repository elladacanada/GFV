<div id="header-bg"> 
  <div class="col-md-8 offset-md-2 py-5">
    <h1 class="text-center">A social platform to share & source delicious, gluten free & vegetarian recipes.</h1>
  </div>
</div>
<div class="container-fluid">
  <div class="row">
   
    <div class="col-md-8 offset-md-2">
    <!-- ACCORDIAN START -->
      <div id="signupAccordian" class="accordian mt-4 mx-auto">
      <!-- SIGN IN START -->
      <div class="card">
          <div id="loginCard" class="card-header text-center" data-toggle="collapse" data-target="#loginCardBody">
            <h2 class="login_title">Login</h2>
            <p class="sign_up_open ">Already a user? <u><strong>Login</strong></u><p>
          </div>
          <div id="loginCardBody" class="card-body collapse show" data-parent="#signupAccordian">
            <form action="/users/login.php" method="post">
              <input type="text" name="username" class="form-control mb-3" placeholder="Username or Email" required>
              <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
              <div class="custom-control custom-checkbox">
                <input class="custom-control-input" id="remember" type="checkbox" name="remember" value="true">
                <label class="custom-control-label" for="remember">Remember Me</label>
              </div>
              <div class="text-right">
                <button type="submit" class="btn btn-primary">Login</button>
              </div>
            </form>
          </div>
        </div>
        <!-- SIGN IN END -->
      <!-- SIGN UP START -->
        <div class="card">
          <div id="signupCard" class="card-header text-center collapsed" data-toggle="collapse" data-target="#signupCardBody">
          <h2 class="sign_up_title" >Sign Up</h2>
            <p  class="login_open">Are you a new user? <u><strong>Sign up for <?=APP_NAME?></strong></u><p>
          </div>
          <div id="signupCardBody" class="card-body collapse" data-parent="#signupAccordian">
            <?php echo (!empty($_SESSION["create_account_msg"])) ? $_SESSION["create_account_msg"] : "";?>
            <form action="/users/add.php" method="post">
              <input type="text" class="form-control mb-3" name="username" required placeholder="Username">
              <input type="email" class="form-control mb-3" name="email" required placeholder="Email Address">
              <input type="password" class="form-control mb-3" name="password" required placeholder="Password">
              <input type="password" class="form-control mb-3" name="password2" required placeholder="Confirm Password">
              <hr>
              <h5>Profile Info</h5>
              <input type="text" class="form-control mb-3" name="firstname" required placeholder="First Name">
              <input type="text" class="form-control mb-3" name="lastname" required placeholder="Last Name">
              <textarea class="form-control mb-3" name="bio" placeholder="Bio" required></textarea>
              <div class="text-right">
                <button type="submit" class="btn btn-primary">Create Account</button>
              </div>
            </form>
          </div>
        </div>
        <!-- SIGN UP END-->
        
      </div>
      <!-- ACCORDIAN END -->
    </div>
  </div>
</div>

<?php
unset($_SESSION["login_attempt_msg"]);
unset($_SESSION["create_account_msg"]);

?>