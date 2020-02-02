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

          
          <div class="card p-3 rounded-lg">
          <div class="text-center">
            <h2><?=$selected_user["username"]?></h2>
          </div>
          <div class="profile_card_pic_holder">
            <img id="img-preview" class="w-100 profile_card_picture" src="<?=$selected_user["profile_pic"]?>">
          </div>
            <p><strong>Name: </strong><?=$selected_user["firstname"] . " " . $selected_user["lastname"]?></p>
            <p><strong>Email: </strong><?=$selected_user["email"]?></p>
            <p><strong>Member since:</strong> <?=date("d M Y",strtotime($selected_user["date_created"]))?></p>
            <p><strong>Bio: </strong><?=$selected_user["bio"]?></p>
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
        </div>  

        <div id="projectFeed" class="col-md-8">
          <div  class="text-center">
            <!-- <h2>Posts</h2> -->

          </div>
          <?php
            //Get all projects by this user
            $p_model = new Project;
            $user_projects = $p_model->get_by_user_id($selected_user["id"]);
            
            $c_model = new Comment; //get an instance of the comment model
            

            
            foreach($user_projects as $user_project){
              //  echo"<pre>";
              //  print_r($user_project);
          ?>

          <div class="card project-post mb-3 rounded-lg">
            <figure class="figure mb-0 figure-image">
              <img class="img-fluid w-100 rounded-lg" src="<?=$user_project["file_url"];?>" alt="">
            </figure>
            <div class="card-body">
                    <?php
                      if($user_project["user_id"] == $_SESSION["user_logged_in"]){
                        ?>
                        <span class="float-right">
                          <a href="/projects/edit.php?id=<?=$user_project["id"];?>"><i class="fas fa-edit"></i></a>
                          <a href="/projects/delete.php?id=<?=$user_project["id"];?>"><i class="fas fa-trash-alt text-danger"></i></a>
                        </span>
                        <?php
                      }
                    ?>
                    
                  
                    <h5><?=$user_project["title"];?></h5>
                    <p><?=$user_project["description"];?></p>
                    <p><small class="text-muted">Posted: <?=date("M, d, Y", strtotime($user_project["date_uploaded"]));?></small></p>
                    <p class="text-muted"><small>User: <a href="/users/index.php?id=<?=$user_project["user_id"]?>"><?=$user_project["firstname"]. " " . $user_project["lastname"];?></small></a></p>
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
                        <div class="user-comment <?=$my_comment?>">
                          <p>
                            
                              <span class="font-weight-bold comment-username"><?=$user_comment["username"]?></span>
                              <?=$user_comment["comment"]?>
                              <a class="trash-btn" href="/comments/delete.php?id=<?=$user_comment["id"];?>"><?=$my_comment_trash?></a>
                          
                            
                            
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
          <?php
            }
          ?>
        
        </div>
        
    </div>
</div>









<?php
require_once("../elements/footer.php");
?>