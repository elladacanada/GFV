<?php

require_once("../../controllers/includes.php");

$c_model = new Comment;
$c_model->delete();

header("Location: /");

?>