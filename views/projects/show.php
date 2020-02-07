<?php
require_once("../../controllers/includes.php");



$p_model = new Project;
$project = $p_model->get_by_id($_GET["id"]);





?>



<div class="modal-content p-3 ">
      <figure class="figure">
          <img src="<?=$project["file_url"]?>">
      </figure>

     <div>
         <h5><?=ucwords($project["title"])?></h5>
     </div>

      <p><small class='text-muted'>Posted: <?=$project["date_uploaded"]?></small></p>

      <span><strong>Directions: </strong></span>
      
      <p class="white-space"><?=ucfirst($project["description"])?></p>
</div>
 














