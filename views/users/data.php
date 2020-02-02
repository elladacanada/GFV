<?php

require_once("../../controllers/includes.php");

$data = array();
$data[] = array(
"first_name" => "Allan",
"last_name" => "Parish",
"age" => "31",
"hometown" => "Maine",
);
$data[] = array(
"first_name" => "Susan",
"last_name" => "Harris",
"age" => "42",
"hometown" => "Merritt",
);
echo json_encode($data);
?>


