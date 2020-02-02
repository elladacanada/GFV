<?php



class Comment extends DB {
    
    
    /*
    * add()
    * add comment adds button tot he database and returns full list of comments
    * @param $comment_data
    * @return array
    */
    public function add($comment_data){

        $comment = $this->data["comment"];
        $project_id = $this->data["project_id"];
        $user_id = (int)$_SESSION["user_logged_in"];
        $posted_time = date("Y-m-d H:i:s", time() );

        $sql = "INSERT INTO comments (comment, posted_time, project_id, user_id) VALUE ('$comment', '$posted_time', $project_id, $user_id)";

        $comment_id = (int)$this->execute_return_id($sql);

        if( !empty($comment_id) && is_numeric($comment_id)){ //is_numeric checks if it is a number and can even use decimal values.  is_int would also work but not for decimal values.

            //get comment count for the current project
            $comment_count = $this->get_count($project_id);

            // Get all comments for the project
            $all_project_comments = $this->get_all_by_project_id($project_id);

            //pass it back to our script.js in our $comment data
            $comment_data["error"] = false;
            $comment_data["comment_count"] = $comment_count;
            $comment_data["comments"] = $all_project_comments;

            //the value returned by the function
            return $comment_data;



        }

    }

    /*
    * delete()
    * delete comment from a project
    * @param 
    * @return array
    */
    public function delete(){

       
        $comment_id = (int)$_GET["id"];

        $sql = "DELETE FROM comments WHERE id =  $comment_id";

        $this->execute($sql);



    }
    
    /*
    * get_all_by_project_id()
    * get all from a project by its id
    * @param 
    * @return array
    */
    public function get_all_by_project_id($project_id){

        $project_id = (int)$project_id;
        $user_id = (int)$_SESSION["user_logged_in"];

        $sql = " SELECT comments.*, users.username, 
                IF(comments.user_id = $user_id, 'true', 'false') AS user_owns
                FROM comments
                LEFT JOIN users
                ON comments.user_id = users.id
                WHERE comments.project_id = $project_id
                ORDER BY comments.posted_time ASC
                LIMIT 100";

        $project_comments = $this->select($sql);

        return $project_comments; 
    }
    
    /*
    * get_count()
    * get all from a project by its id
    * @param 
    * @return array
    */
    public function get_count($project_id){

        $sql = "    SELECT COUNT(id) AS comment_count 
                    FROM comments
                    WHERE project_id = $project_id";
        
        $returned_count = $this->select($sql)[0];

        return $returned_count["comment_count"];
    }
}

?>