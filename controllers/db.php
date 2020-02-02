<?php

class DB {

    public $data = array();
    public $params = array();

    function __construct(){
        //run as soon as db class is called

        // Store all of our $_POST data in the $data variable
        if( !empty($_POST)){
           
            $conn = $this->conn();
            $escPost = array();
            foreach($_POST as $key => $value){
                $escPost[$key] = trim( mysqli_real_escape_string($conn, $value) );
            }
            $conn->close();
            $this->data = $escPost;
        }
        // Store all of our $_GET data in the $params variable
        if( !empty($_GET)){
           
            $conn = $this->conn();
            $escGet = array();
            foreach($_GET as $key => $value){
                $escGet[$key] = trim( mysqli_real_escape_string($conn, $value) );
            }
            $conn->close();
            $this->params = $escGet;
        }
    }

    /*
    * conn()
    * Connect to databse
    * 
    */
    protected function conn() {

        if( $_SERVER["SERVER_NAME"] == "dev.oraia.design"){
            $servername = 'localhost';
            $username = 'projectshare_db';
            $password = '04S7~bqy';
            $dbname = 'projectshare';

        } else {

            $servername = 'localhost';
            $username = 'root';
            $password = 'root';
            $dbname = 'projectshare';
        }

        $conn = new mysqli($servername, $username, $password, $dbname);

        if( $conn->connect_error){
            die("Connection Failed: " . $conn->connect_error);
        }

        return $conn;
    }

    /*
    * select
    * run mysql select statement and return the results
    */
    public function select($sql) {
        if(APP_DEBUG) echo 'select()<br>';
        $conn = $this->conn();
        $result = $conn->query($sql);

        // Store the XSS cleaned data
        // XSS = Cross-Site Scripting Attack
        $xssArr = array();
        if($result->num_rows > 0){
            $x = 0;
            while($row = $result->fetch_assoc() ) {
                // Loop through each column of the current row
                foreach($row as $column => $value){
                    $xssArr[$x][$column] = htmlspecialchars($value, ENT_QUOTES);
                }
                $x++;
            }
        } else {
            if(APP_DEBUG) $_SESSION["errors"][] = "error selecting from database: $sql";
        }

        $conn->close();
        return $xssArr;

    }
    /*
    * execute
    * @params $sql
    * Executes sql query (update sql)
    * @returns null
    */
    public function execute($sql){
        $conn = $this->conn();
        if($conn->query($sql) !== true) {
            echo "Your Statement: ". $sql . "<br> Error: ".$conn->error;
            die("Error with the sql statement");
        }
        $conn->close();
    }

    /*
    * execute_return_id
    * Executes sql query and returns last inserted ID
    */
    public function execute_return_id($sql) {
        if(APP_DEBUG) echo 'execute_return_id()<br>';
        $conn = $this->conn();

        if( $conn->query($sql) !== TRUE ) {
            echo "Your Statement: ". $sql . "<br> Error: ".$conn->error;
            die("Error with the sql statement");
        }

        $last_id = $conn->insert_id;

        $conn->close();

        return $last_id;
    }
}

?>