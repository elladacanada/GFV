<?php
require_once("../../controllers/includes.php");

// If the form was submitted
if( !empty($_POST)){
    $user->edit();
    header("Location: /users/");
    exit;
}

$title = "Editing " . $current_user["username"]; 

require_once("../elements/header.php");
require_once("../elements/nav.php");
// echo "<pre>";
// print_r($current_user);
?>

<div id="background-edit-page" class="container-fluid py-5">
    <div class="row">
        <div id="editCard" class="col-md-8 offset-md-2 rounded-lg py-3" mx-auto>
        <div class="row">
        <div class="col-12 text-center py-3">
            <h2>Edit Profile</h2>
        </div>
            
                <div class="col-6">
                    <form method="post" enctype="multipart/form-data">
                            <img id="img-preview" class="w-100 mb-3" src="<?=$current_user["profile_pic"]?>">
                                <div class="custom-file mb-3">
                                    <input id="file-with-preview" class="custom-file-input" type="file" name="fileToUpload"  >
                                    <label class="custom-file-label" for=""></label>
                                </div>
                        </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input id="username" 
                                type="text" 
                                name="username" class="form-control" 
                                value="<?=$current_user["username"]?>">
                    </div>
                        
                    <div class="form-group">
                        <label for="firstname">First Name</label>
                        <input id="firstname" 
                                type="text" 
                                name="firstname" class="form-control" 
                                value="<?=$current_user["firstname"]?>">
                    </div>
                    <div class="form-group">
                        <label for="lastname">Last Name</label>
                        <input id="lastname" 
                                type="text" 
                                name="lastname" class="form-control" 
                                value="<?=$current_user["lastname"]?>">
                    </div>
                    <div class="form-group">
                        <label for="bio">Bio</label>
                        <textarea id="bio" 
                                type="text" 
                                name="bio" class="form-control" 
                                ><?=$current_user["bio"]?></textarea>
                    </div>

                    <?php
                        // check for Alerts
                        if(!empty($_SESSION["errors"]) && is_array($_SESSION["errors"])){
                        foreach( $_SESSION["errors"] as $error) {
                            echo "<div class='alert alert-danger'>$error</div>";
                        }

                        unset($_SESSION["errors"]); // this erases the session which makes the errors dissapear upon refresh of the page.
                        }
                    ?>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input id="password" 
                                type="text" 
                                name="password" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="password2">Confirm Password</label>
                        <input id="password2" 
                                type="text" 
                                name="password2" class="form-control">
                    </div>
                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>   
    </div>
</div>








<?php
require_once("../elements/footer.php");
?>