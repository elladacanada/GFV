<?php

require_once("../../controllers/includes.php");

$comment_data = array(
    "error" => true
);

// if the post data comment form submitted and prject id is set
if( !empty($_POST["project_id"])){
    
    // add new comment to database
    $c_model = new Comment;
    $comment_data = $c_model->add($comment_data);
}

echo json_encode($comment_data); // json formats things in a way that javascript can read.. ex; {key:value, key2: value}

die();