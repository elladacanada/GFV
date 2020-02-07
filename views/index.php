<?php
require_once("../controllers/includes.php");

$title = "Home Page"; //must go above require of header

require_once("elements/header.php");
require_once("elements/nav.php");

// Check if the id is set
// If it is, get the user by id and pass data
// else load current user



?>



  <?php
  // if the user_logged_in session variable is not set with a user
    if( empty($_SESSION["user_logged_in"]) ) {
      // Show the login form
      require_once("elements/sign-up-form.php");
    } else {
      ?>
      <!-- <div class="text-center mb-5">
        <h1>Welcome to <?=APP_NAME?></h1>
        <button id="modalButton" type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Click this to open modal</button>

    </div> -->



      <?php
        // check for Alerts
        if(!empty($_SESSION["errors"]) && is_array($_SESSION["errors"])){
          foreach( $_SESSION["errors"] as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
          }

          unset($_SESSION["errors"]); // this erases the session which makes the errors dissapear upon refresh of the page.
        }
      ?>
  <div id="background" class="container-fluid py-5">
      <div class="row">

      <div class="col-md-4">
        <div class="sticky-top">
        <div id="searchCard" class="text-center p-3 mb-3 rounded-lg">
        
          <h4>Search</h4>
        
          
            <form id="search_form" class="form-inline">

              <input type="search" name="search" id="search" class="form-control w-100" placeholder="Search..." autocomplete="off">
              <div id="search_results">

              </div>
            </form>
          
        </div>

          <div id="shareProjectCard" class="card mb-3 rounded-lg">
            <div class="card-header text-center">
              <h4>Share New Recipe</h4>
            </div>
            <div class="card-body ">
            <!-- // enctype to be able to upload iamge files // -->
              <form method="post" action="/projects/add.php" enctype="multipart/form-data"> 
                
                  <img id="img-preview" class="w-100 mb-3 card_picture">
                
                <div class="custom-file mb-3">
                  <input id="file-with-preview" class="custom-file-input" type="file" name="fileToUpload" required multiple>
                  <label class="custom-file-label" for=""></label>
                </div>
                <div class="form-group mb-3">
                  <input class="form-control" type="text" name="title" placeholder="Title" required>
                </div>
                <div class="form-group mb-3">
                  <textarea name="description" class="form-control" placeholder="Directions" required></textarea>
                </div>
                <div class="form-group text-right">
                  <button type="submit" class="btn btn-primary">Post Project</button>
                </div>
              </form>
            </div>
          </div><!-- end of shareProjectCard -->
          </div>
      </div> 

        <div class="col-md-8 ">
          <div id="projectFeed" >
            <?php

              $p_model = new Project;
              $projects = $p_model->get_all();
              $c_model = new Comment; //get an instance of the comment model

              foreach($projects as $project){
                // echo"<pre>";
                // print_r($project);
                ?>

                <div id="project-<?=$project["id"];?>" class="card project-post mb-3 rounded-lg">
                  
                  <figure class="figure mb-0 figure-image">
                    <img class="img-fluid w-100 rounded-lg" src="<?=$project["file_url"];?>" alt="">
                    
                  </figure>
                  <div class="card-body">
                    <?php
                      if($project["user_id"] == $_SESSION["user_logged_in"]){
                        ?>
                        <span class="float-right">
                          <a href="/projects/edit.php?id=<?=$project["id"];?>"><i class="primary fas fa-edit"></i></a>
                          <a data-project="<?=$project["id"];?>" class="deleteProjectButton"><i class="fas fa-trash-alt text-danger"></i></a>
                        </span>
                        <?php
                      }
                    ?>
                    <h5><?=ucwords($project["title"]);?></h5>

                    <p><small class="text-muted">Posted: <?=date("M, d, Y", strtotime($project["date_uploaded"]));?><br>
                    User: <a href="/users/index.php?id=<?=$project["user_id"]?>"><?=ucwords($project["firstname"]). " " . ucwords($project["lastname"]);?></a></small></p>
                  
                    <span><strong>Directions:</strong></span>
                    <p class="white-space"><?=ucfirst($project["description"]);?></p>
                    
                  </div>

                  <!-- HEARTS AND COMMENTS -->
                  <!-- HEARTS AND COMMENTS -->
                  <!-- HEARTS AND COMMENTS -->
                  <div class="card-footer">
                  <?php
                    $love_class = "far";
                    if(!empty($project["love_id"])){
                      $love_class = "fas";
                    }
                  ?>
                    <div class="project-meta ">
                      <span class="love-btn float-right" data-project="<?=$project["id"]?>">
                        <i class="<?=$love_class?> fa-heart text-danger love-icon"></i>
                        <span class="love-count"><?=$project["love_count"]?></span>
                      </span>

                      <span class="float-left comment-btn "><small class="text-muted">comments...</small>
                        <i class="far fa-comment"></i>
                        <span class="comment-count">
                          <?php echo $c_model->get_count($project["id"]);?>
                        </span>
                      </span>
                    </div>
                      

                      <!-- COMMENTS START -->
                      <!-- COMMENTS START -->
                      <!-- COMMENTS START -->
                      <!-- COMMENTS START -->
                      <div class="comment-loop ">

                      <?php
                        $project_comments = $c_model->get_all_by_project_id($project["id"]);
                        foreach($project_comments as $user_comment){
                          $my_comment = ($user_comment["user_owns"] == "true")?"my_comment":"";
                          $my_comment_trash = ($user_comment["user_owns"] == "true") ? "<i class='fas fa-trash trash-icon'></i>":"";
                      ?>
                        <div id="comment-<?=$user_comment["id"]?>"  class="user-comment <?=$my_comment?>">
                          <p>
                            
                              <span class="font-weight-bold comment-username"><?=ucwords($user_comment["username"])?></span>
                              <?=ucfirst($user_comment["comment"])?>
                              <a class="trash-btn text-danger" data-target="<?=$user_comment["id"];?>"><?=$my_comment_trash?></a>
                          
                            
                            
                          </p>
                        </div>
                      <?php
                        }
                      ?>

                      </div> <!-- end comment loop-->
                     
                      <form class="comment-form" data-project="<?=$project["id"]?>" action="">
                        <input class="form-control comment-box" type="text" name="comment" placeholder="Write a comment..." >
                      </form>
                  </div>
                  
                  
                      
                  
                </div>

                <?php
              }
            ?>
          </div>
        </div>
      
        

          
        
      </div>

      <?php
    }
    
  ?>

  
</div>


























<?php
require_once("elements/footer.php");
?>

    