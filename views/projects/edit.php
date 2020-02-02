<?php

require_once("../../controllers/includes.php");

if( !empty($_GET["id"])){
    $p_model = new Project; // Start Project Model
    $project = $p_model->get_by_id($_GET["id"]);

    if( !empty($_POST)){
        $p_model->edit($_GET["id"]);
        header("Location: /");
        exit();
    }

    
} else {
    header("Location: /");
    exit;
}

require_once("../elements/header.php");
require_once("../elements/nav.php");

?>

<div id="background" class="container-fluid py-5">
    <div class="row">
        <div id="editProjectCard" class="col-md-8 offset-md-2 rounded-lg py-3">
            
                <div class="col-12 text-center py-3">
                    <h4>Edit Project</h4>
                </div>
                <div class="col-12">
                    <form method="post" enctype="multipart/form-data">
                        <img id="img-preview" class="w-100 rounded-lg mb-3" src="<?=$project["file_url"]?>">
                        <div class="custom-file mb-3">
                            <input id="file-with-preview" class="custom-file-input" type="file" name="fileToUpload"  >
                            <label class="custom-file-label" for=""></label>
                        </div>
                        <div class="form-group mb-3">
                            <label for="title">Title</label>
                            <input class="form-control" type="text" name="title" placeholder="Project Title" required value="<?=$project["title"]?>">
                        </div>
                        <div class="form-group mb-3">
                            <label for="description">Directions</label>
                            <textarea name="description" class="form-control" placeholder="Project Description" required><?=$project["description"]?></textarea>
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-primary">Update Recipe</button>
                        </div>
                    </form>
                </div>
            
        </div>
    </div>
</div>








<?php

require_once("../elements/footer.php");
?>