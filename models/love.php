<?php

class Love extends DB {
    

    /*
    * add()
    * add love to a project
    * @param $love_data
    * @return array
    */
    public function add( $love_data ) {

        $project_id = $this->data["project_id"];
        $user_id = (int)$_SESSION["user_logged_in"];

        // Check if already loved by user.

        $sql = "SELECT * FROM loves WHERE project_id = $project_id AND user_id = $user_id";

        $love = $this->select($sql);

        if (!empty($love)){
            $love = $love[0];
        }

        // Delete the love if already been clicked before and is clicked again.

        if( !empty($love["id"])){
            //We will delete love if true that one already existed
            $sql = "DELETE FROM loves WHERE project_id = $project_id AND user_id = $user_id";
            $this->execute($sql);
            $love_data["loved"] = "unloved";
            $love_data["error"] = false;
        } else {
            // else, show some love
            $sql = "INSERT INTO loves (user_id, project_id) VALUES ($user_id, $project_id)";

            $love_id = (int)$this->execute_return_id($sql); //int doesnt have to be here but makes sure it returns an int for security purposes

            if( !empty($love_id) && $love_id != 0 ){
                $love_data["loved"] = "loved";
                $love_data["error"] = false;
            }
        }
        
        // Get the new loves clount
        $sql = "SELECT COUNT(loves.id) AS love_count FROM loves WHERE loves.project_id = $project_id";

        $love_count = $this->select($sql)[0];
        $love_data["love_count"] = $love_count["love_count"];

        return $love_data;
    }
}


?>