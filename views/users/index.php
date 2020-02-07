<?php
require_once("../../controllers/includes.php");

$title = "My Profile"; 

require_once("../elements/header.php");
require_once("../elements/nav.php");
// echo "<pre>";
// print_r($selected_user);

if( !empty($_GET["id"])){
  $user_id = $_GET["id"];
  $u_model = new User;
  $selected_user = $u_model->get_by_id($user_id);
} else {
    $selected_user = $current_user;
}

?>

<div id="background-user" class="container-fluid py-5">
    <div class="row">
        <div class="col-md-4 " mx-auto>

          <div id="searchCard" class="text-center p-3 mb-3 rounded-lg">
          
            <h4>Search</h4>
          
            
              <form id="search_form" class="form-inline">

                <input type="search" name="search" id="search" class="form-control w-100" placeholder="Search..." autocomplete="off">
                <div id="search_results">

                </div>
              </form>
            
          </div>

          
          <div class="card p-3 rounded-lg mb-3">
          <div class="text-center">
            <h2><?=ucwords($selected_user["username"])?></h2>
          </div>
          <div class="profile_card_pic_holder">
            <img class="w-100 profile_card_picture" src="<?=$selected_user["profile_pic"]?>">
          </div>
            <p><strong>Name: </strong><?=ucwords($selected_user["firstname"]) . " " . ucwords($selected_user["lastname"])?></p>
            <p><strong>Email: </strong><?=strtolower($selected_user["email"])?></p>
            <p><strong>Member since:</strong> <?=date("d M Y",strtotime($selected_user["date_created"]))?></p>
            <p><strong>Bio: </strong><?=ucfirst($selected_user["bio"])?></p>
            <?php
            if($selected_user["id"] == $_SESSION["user_logged_in"]){
            ?>
            <div class="text-right">
              <p>
                <a href="/users/edit.php" class="btn btn-primary">Edit Profile</a>
              </p>
            </div>
            <?php
            }
            ?>
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
                  <button id="post_button" type="submit" class="btn btn-primary">Post Project</button>
                </div>
              </form>
            </div>
          </div><!-- end of shareProjectCard -->





        </div>  

        <div id="projectFeed" class="col-md-8">
          <div class="row">
          <?php
            //Get all projects by this user
            $p_model = new Project;
            $user_projects = $p_model->get_by_user_id($selected_user["id"]);
            
            $c_model = new Comment; //get an instance of the comment model
            

            
            foreach($user_projects as $user_project){
              //  echo"<pre>";
              //  print_r($user_project);
          ?>
          <div class="col-4">
          <div  id="project-<?=$user_project["id"];?>" class="card user-cards project-post mb-3 rounded-lg ">
          
            <figure class="figure mb-0 figure-image">
              <img class=" rounded-lg" src="<?=$user_project["file_url"];?>" alt="">
            </figure>
            <div class="card-body">
                    <?php
                      if($user_project["user_id"] == $_SESSION["user_logged_in"]){
                        ?>
                        <span class="float-right">
                          <a href="/projects/edit.php?id=<?=$user_project["id"];?>"><i class="primary fas fa-edit"></i></a>
                          <a data-project="<?=$user_project["id"];?>" class="deleteProjectButton"><i class="fas fa-trash-alt text-danger"></i></a>
                        </span>
                        <?php
                      }
                    ?>
                    
                  
                    <h5><?=ucwords($user_project["title"]);?></h5>
                    
                    <p><small class="text-muted">Posted: <?=date("M, d, Y", strtotime($user_project["date_uploaded"]));?><br>User: <a href="/users/index.php?id=<?=$user_project["user_id"]?>"><?=ucwords($user_project["firstname"]). " " . ucwords($user_project["lastname"]);?></small></a></p>

                      <button type="button" data-project="<?=$user_project["id"]?>" class="modal-button btn btn-primary w-100" data-toggle="modal" data-target="#exampleModal">View Directions...</button>
                    
                  </div>

                  <!-- HEARTS AND COMMENTS -->
                  <!-- HEARTS AND COMMENTS -->
                  <!-- HEARTS AND COMMENTS -->
                  <div class="card-footer">
                  <?php
                    $love_class = "far";
                    if(!empty($user_project["love_id"])){
                      $love_class = "fas";
                    }
                  ?>
                    <div class="project-meta ">
                      <span class="love-btn float-right" data-project="<?=$user_project["id"]?>">
                        <i class="<?=$love_class?> fa-heart text-danger love-icon"></i>
                        <span class="love-count"><?=$user_project["love_count"]?></span>
                      </span>

                      <span class="float-left comment-btn "><small class="text-muted">comments...</small>
                        <i class="far fa-comment"></i>
                        <span class="comment-count">
                          <?php echo $c_model->get_count($user_project["id"]);?>
                        </span>
                      </span>
                    </div>
                      

                      <!-- COMMENTS START -->
                      <!-- COMMENTS START -->
                      <!-- COMMENTS START -->
                      <!-- COMMENTS START -->
                      <div class="comment-loop ">

                      <?php
                        $project_comments = $c_model->get_all_by_project_id($user_project["id"]);
                        foreach($project_comments as $user_comment){
                          $my_comment = ($user_comment["user_owns"] == "true")?"my_comment":"";
                          $my_comment_trash = ($user_comment["user_owns"] == "true") ? "<i class='fas fa-trash trash-icon'></i>":"";
                      ?>
                        <div id="comment-<?=$user_comment["id"]?>" class="user-comment <?=$my_comment?>">
                          <p>
                            <span class="font-weight-bold comment-username"><?=ucwords($user_comment["username"])?></span>
                                <?=ucfirst($user_comment["comment"])?>
                                <a class="trash-btn text-danger" data-target="<?=$user_comment["id"]?>"><?=$my_comment_trash?></a>
                          </p>
                        </div>
                      <?php
                        }
                      ?>

                      </div> <!-- end comment loop-->
                     
                      <form class="comment-form" data-project="<?=$user_project["id"]?>" action="">
                        <input class="form-control comment-box" type="text" name="comment" placeholder="Write a comment..." >
                      </form>
                  </div>

                  
            </div>
            </div>
          <?php
            }
          ?>
        </div> <!--row end -->
        </div>
        
    </div>
</div>


<!-- POP UP MODAL -->
<!-- POP UP MODAL -->
<!-- POP UP MODAL -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ...
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>





<?php
require_once("../elements/footer.php");
?>