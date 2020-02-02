<?php

//define a class for user
class User extends DB{


    /*
    * get_all()
    * Get all users data from database
    * @returns array
    */
    public function get_all(){

        $user_id = (int)$_SESSION["user_logged_in"];

        // IF SEARCH IS USED
        // IF SEARCH IS USED
        if( !empty($_GET["search"])){
            $search_query = $this->params["search"];
            $search_query = str_replace("@","", $search_query);
            $sql_where = "WHERE users.username LIKE '%$search_query%'
                            OR users.firstname LIKE '%$search_query%'
                            OR users.lastname LIKE '%$search_query%' ";
        } else {
            $sql_where = '';
        }

        $sql = "SELECT * FROM users $sql_where";

        $user_results = $this->select($sql);
        
        foreach($user_results as $key => $user) {
            $user_results[$key]["title"] = $user["firstname"] . "" . $user["lastname"];
        }

        return $user_results;
    }

    /*
    * get_by_id()
    * Get a users data from the database by ID
    * @params $user_id
    * @returns array
    */
    public function get_by_id( $user_id) {
        $sql = "SELECT * FROM users WHERE id = $user_id";
        $user = $this->select($sql)[0];

        return $user;
    }

    /*
    * exists()
    * Checks if user already exists in the database
    * @returns int (integer)
    */
    public function exists(){
        // Check the database to see if user exists
        $username = $this->data["username"];
        $email = $this->data["email"];

        $sql = " SELECT * FROM users WHERE username = '$username' OR email = '$email' LIMIT 1";

        $user = $this->select($sql);

        return $user;

    }

    /*
    * add()
    * Adds the new user to the database
    * @returns int (integer)
    */
    public function add(){
        if(APP_DEBUG) echo 'add()<br>';
        $username = $this->data["username"];
        $email = $this->data["email"];
        $firstname = $this->data["firstname"];
        $lastname = $this->data["lastname"];
        $bio = $this->data["bio"];
        $password = password_hash(trim($_POST["password"]), PASSWORD_DEFAULT );
        $current_time = date("Y-m-d H:i:s", time());

        $sql = "    INSERT INTO users (username, email, firstname, lastname, bio, password, date_created) 
                    VALUES ('$username', '$email','$firstname','$lastname','$bio', '$password', '$current_time')";

        $new_user_id = $this->execute_return_id($sql);

        return$new_user_id;
    }

    /*
    * edit()
    * Edit the current user
    * @returns null
    */
    public function edit(){
        $id = (int)$_SESSION["user_logged_in"];
        $username = $this->data["username"];
        $firstname = $this->data["firstname"];
        $lastname = $this->data["lastname"];
        $bio = $this->data["bio"];
        $password = password_hash(trim($_POST["password"]), PASSWORD_DEFAULT);
        $password2 = password_hash(trim($_POST["password2"]), PASSWORD_DEFAULT);

        if(!empty($_FILES["fileToUpload"]["name"])){ //check if new file was submitted
            $util = new Util;
            $file_upload = $util->file_upload(); // upload the new file
            $filename = $file_upload["filename"];

            if( $file_upload["file_upload_error_status"] == 0) {
                // get the old image
                $old_profile_image = $this->get_by_id($id)["profile_pic"];

                $sql = " UPDATE users SET profile_pic = '$filename' WHERE id = $id";
                $this->execute($sql);

                //delete the old image
                if(!empty($old_profile_image)){
                    if( file_exists(APP_ROOT . "/views" . $old_profile_image)){
                        unlink(APP_ROOT . "/views" . $old_profile_image);
                    }
                }
            }

        }

        // var_dump($password);
        // exit();

        $sql = "UPDATE users
                SET username = '$username', 
                    firstname = '$firstname',
                    lastname = '$lastname',
                    bio = '$bio'";
        if( $_POST["password"]) {

            if($_POST["password"] != "" && $_POST["password2"] != "" && $_POST["password"] == $_POST["password2"]){
                $sql .= ", password = '$password'";
            } else {// FIGURE OUT HOW TO MAKE ERROR CODE 
                
            $_SESSION["errors"][] = "your passwords do not match";
            }
        }
        $sql .= " WHERE id = $id";

        $this->execute($sql);
    }


    /*
    * login()
    * Logs in the user
    * @returns null
    */
    public function login(){

        $_SESSION = array(); // empty the session first to start fresh

        $username = $this->data["username"];
        $sql = "SELECT * FROM users WHERE username = '$username' OR email = '$username' LIMIT 1";

        $user = $this->select($sql)[0];

        //password verify hashes the posted password and checks it against the database password
        if( password_verify( $_POST["password"], $user["password"])){
            $_SESSION["user_logged_in"] = $user["id"];
            
            // if remember is set, set the cookie of user logged in.
            if (!empty($_POST["remember"])){
                setcookie("user_logged_in", $user["id"], time() + (2 * 24 * 60 * 60), "/"); //cookies set for 2 days, "/" means to set from domain root level.
            }
        } else {
            $_SESSION["login_attempt_msg"] = "<p class='text-danger'>Incorrect uswername or password</p>";
        }

        
    }

}

?>