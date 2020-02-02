<?php

class Util extends DB{

    public function file_upload($target_dir = APP_ROOT."/views/uploads/", $inputNameAttr = "fileToUpload"){
        // Create an array to store any errors, or the file name on success

        $file_upload = array(
            "file_upload_error_status" => 0,
            "errors" => array(),
            "filename" => ""
        );

        // Check if the $_FILES input exists
        if( !empty($_FILES[$inputNameAttr]["name"])){

            //Check if user folder exists 
            if( !file_exists( $target_dir . $_SESSION["user_logged_in"])){
                //make directory
                mkdir($target_dir . $_SESSION["user_logged_in"]);
            }

            $filename = time() . basename($_FILES[$inputNameAttr]["name"]);
            $target_file = $target_dir . $_SESSION["user_logged_in"] . "/" . $filename;

            //Checks the image size but if not an image, will return an error.  
            // tmp_name is the servers temporary location when image uploaded.
            $check = getimagesize($_FILES[$inputNameAttr]["tmp_name"]);

            if( $check !== false) {
                $file_upload["file_upload_error_status"] = 0;
            } else {
                $file_upload["file_upload_error_status"] = 1;
                $file_upload["errors"][] = "File is not an image. ";
            }
            // if file exists (not really needed when we give each file a time stamp)
            if( file_exists($target_file)) {
                $file_upload["file_upload_error_status"] = 1;
                $file_upload["errors"][] = "File already exists. ";
            }

            //check the file size
            $allowedSize = 10000000;
            if($_FILES[$inputNameAttr]["size"] > $allowedSize){ //1mb upload size
                $file_upload["file_upload_error_status"] = 1;
                $file_upload["errors"][] = "File size is too big. Limit is  " . ($allowedSize / 10000000) . "MB";
            }

            //Check the file type
            $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $valid_file_types = array("jpg", "png", "jpeg", "gif");
            //check if file type is a valid file type
            if( !in_array($file_type, $valid_file_types)) {
                $file_upload["file_upload_error_status"] = 1;
                $file_upload["errors"][] = "Only JPG, JPEG, PNG, or GIF file formats allowed.";
            }

            // If no errors, upload Image
            if( $file_upload["file_upload_error_status"] == 0) {
                
                if( move_uploaded_file($_FILES[$inputNameAttr]["tmp_name"], $target_file)){
                     // this checks the databse to determine how to sanitize the variable we passed to it.
                     //we are passing the entire target file but will strip the APP_ROOT from it.
                    $file_upload["filename"] = mysqli_real_escape_string($this->conn(), str_replace(APP_ROOT."/views", "", $target_file));

                    

                    return $file_upload;
                }
            } else {
                $_SESSION["errors"] = $file_upload["errors"];
            }
            //this will return all error statements unless the last if statement runs "if( $file_upload)". then the file would upload
            return $file_upload;
        }
    }
}

?>