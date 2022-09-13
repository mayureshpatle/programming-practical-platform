<?php
    class User
    {
        private $conn;
        private $table_name = "users";

        //object properties
        public $id;
        public $name;
        public $password;
        public $email;
        public $type;
        public $status;
        public $access_code;
        public $mail_status;

        //constructor
        public function __construct($db)
        {
            $this->conn = $db;
        }

        public function idExists()
        {
            //query to check if user ID exists
            $query = "SELECT user_id, name, password, email, type, status, access_code, mail_status  FROM " . $this->table_name . " WHERE user_id = ? LIMIT 0,1";

            $stmt = $this->conn->prepare($query);

            //sanitize
            $this->id = htmlspecialchars(strip_tags($this->id));

            //bind id value
            $stmt->bindParam(1, $this->id);

            //execute query
            $stmt->execute();

            //get number of rows
            $num = $stmt->rowCount();

            //if id exists, assign values to object properties for easy access and use for php sessions
            if($num>0)
            {
                //get record details / values
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                //assign values to object properties
                $this->id = $row['user_id'];
                $this->name = $row['name'];
                $this->email = $row['email'];
                $this->password = $row['password'];
                $this->type = $row['type'];
                $this->status = $row['status'];
                $this->access_code = $row['access_code'];
                $this->mail_status = $row['mail_status'];

                return true;    //since user id is found in DB
            }

            return false; //as user ID is not found;
        }

        public function idPresent()
        {
            //query to check if user ID exists
            $query = "SELECT user_id FROM " . $this->table_name . " WHERE user_id = ? LIMIT 0,1";

            $stmt = $this->conn->prepare($query);

            //sanitize
            $this->id = htmlspecialchars(strip_tags($this->id));

            //bind id value
            $stmt->bindParam(1, $this->id);

            //execute query
            $stmt->execute();

            //get number of rows
            $num = $stmt->rowCount();

            //if id exists
            if($num>0)
            {
                return true;    //since user id is found in DB
            }

            return false; //as user ID is not found;
        }

        // read all user records
        public function readAll($from_record_num, $records_per_page)
        {
        
            // query to read all user records, with limit clause for pagination
            $query = "SELECT
                        user_id,
                        name,
                        email,
                        type,
                        status,
                        mail_status
                    FROM " . $this->table_name . "
                    ORDER BY type DESC, user_id ASC
                    LIMIT ?, ?";
        
            // prepare query statement
            $stmt = $this->conn->prepare( $query );
        
            // bind limit clause variables
            $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
            $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);
        
            // execute query
            $stmt->execute();
        
            // return values
            return $stmt;
        }

        // used for paging users
        public function countAll()
        {
        
            // query to select all user records
            $query = "SELECT user_id FROM " . $this->table_name . "";
        
            // prepare query statement
            $stmt = $this->conn->prepare($query);
        
            // execute query
            $stmt->execute();
        
            // get number of rows
            $num = $stmt->rowCount();
        
            // return row count
            return $num;
        }

        //showError method, used in create() function
        public function showError($stmt)
        {
            echo "<pre>";
                print_r($stmt->errorInfo());
            echo "</pre>";
        }

        //create new user record
        public function create()
        {
            // insert query
            $query = "INSERT INTO " . $this->table_name . " SET
                        user_id = :id,
                        name = :name,
                        email = :email,
                        status = :status,
                        access_code = :access_code,
                        type = :type,
                        mail_status = 1,
                        password = :password";
        
            // prepare the query
            $stmt = $this->conn->prepare($query);
        
            // sanitize
            $this->id=htmlspecialchars(strip_tags($this->id));
            $this->name=htmlspecialchars(strip_tags($this->name));
            $this->email=htmlspecialchars(strip_tags($this->email));
            $this->status=htmlspecialchars(strip_tags($this->status));
            $this->access_code=htmlspecialchars(strip_tags($this->access_code));
            $this->type=htmlspecialchars(strip_tags($this->type));
            $this->password=htmlspecialchars(strip_tags($this->password));
        
            // bind the values
            $stmt->bindParam(':id', $this->id);
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':status', $this->status,PDO::PARAM_INT);
            $stmt->bindParam(':access_code', $this->access_code);
            $stmt->bindParam(':type', $this->type,PDO::PARAM_INT);

            //hash password before saving to database
            $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $password_hash);
        
        
            // execute the query, also check if query was successful
            if($stmt->execute())
            {
                return true;
            }
            else
            {
                $this->showError($stmt);
                return false;
            }
        }

        public function mail_failed()
        {
            // update query
            $query = "UPDATE " . $this->table_name . " SET mail_status = 0 WHERE user_id = :id";

            // prepare the query
            $stmt = $this->conn->prepare($query);
        
            // sanitize
            $this->id=htmlspecialchars(strip_tags($this->id));

            // bind the values
            $stmt->bindParam(':id', $this->id);

            $stmt->execute();

        }

        // check if given access_code exist in the database & corresponds to the user
        public function verifyAccessCode()
        {
            // query to check if access_code exists
            $query = "SELECT user_id, access_code, status
                    FROM " . $this->table_name . "
                    WHERE user_id = ?
                    LIMIT 0,1";
        
            // prepare the query
            $stmt = $this->conn->prepare( $query );
        
            // sanitize
            $this->id=htmlspecialchars(strip_tags($this->id));
        
            // bind given access_code value
            $stmt->bindParam(1, $this->id);
        
            // execute the query
            $stmt->execute();
        
            // get number of rows
            $num = $stmt->rowCount();

            if($num == 0)
            {
                return false;   //id does not exist
            }

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            //access code matched
            if($row['access_code'] == $this->access_code)
            {
                //already verified
                if($row['status']==1)
                {
                    return false;
                }

                // insert query
                $query = "UPDATE " . $this->table_name . " SET status = 1 WHERE user_id = :id";

                // prepare the query
                $stmt = $this->conn->prepare($query);
            
                // sanitize
                $this->id=htmlspecialchars(strip_tags($this->id));

                // bind the values
                $stmt->bindParam(':id', $this->id);

                if($stmt->execute()) return true;

                return false;
            }

            return false;   //access code did not match
        }

        function delete($utils, $access, $db)
        {

            //delete test records, submissions & access
            $access->user_id = $this->id;
            $access->utils = $utils;
            $status = $access->deleteUser($db);

            $query = "DELETE FROM " . $this->table_name . " WHERE user_id = :user_id";

            $stmt = $this->conn->prepare($query);

            $this->id = htmlspecialchars(strip_tags($this->id));

            $stmt->bindParam(':user_id', $this->id);

            if($stmt->execute()) return $status && true;

            return false;
        }

        // check if given access_code exist in the database & corresponds to the user
        public function validateAccessCode()
        {
            // query to check if access_code exists
            $query = "SELECT user_id, access_code
                    FROM " . $this->table_name . "
                    WHERE user_id = ?
                    LIMIT 0,1";
        
            // prepare the query
            $stmt = $this->conn->prepare( $query );
        
            // sanitize
            $this->id=htmlspecialchars(strip_tags($this->id));
        
            // bind given access_code value
            $stmt->bindParam(1, $this->id);
        
            // execute the query
            $stmt->execute();
        
            // get number of rows
            $num = $stmt->rowCount();

            if($num == 0)
            {
                return false;   //id does not exist
            }

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            //access code matched
            if($row['access_code'] == $this->access_code)
            {
                return true;
            }

            return false;   //access code did not match
        }

        // used in forgot password feature
        public function updateAccessCode(){
        
            // update query
            $query = "UPDATE
                        " . $this->table_name . "
                    SET
                        access_code = :access_code
                    WHERE
                        user_id = :id";
        
            // prepare the query
            $stmt = $this->conn->prepare($query);
        
            // sanitize
            $this->access_code=htmlspecialchars(strip_tags($this->access_code));
            $this->id=htmlspecialchars(strip_tags($this->id));
        
            // bind the values from the form
            $stmt->bindParam(':access_code', $this->access_code);
            $stmt->bindParam(':id', $this->id);
        
            // execute the query
            if($stmt->execute()){
                return true;
            }
        
            return false;
        }

        // used in forgot password feature
        public function updatePassword(){
        
            // update query
            $query = "UPDATE " . $this->table_name . "
                    SET password = :password,
                    status = 1
                    WHERE user_id = :user_id";
        
            // prepare the query
            $stmt = $this->conn->prepare($query);
        
            // sanitize
            $this->password=htmlspecialchars(strip_tags($this->password));
            $this->id=htmlspecialchars(strip_tags($this->id));
        
            // bind the values from the form
            $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
            $stmt->bindParam(':password', $password_hash);
            $stmt->bindParam(':user_id', $this->id);
        
            // execute the query
            if($stmt->execute()){
                return true;
            }
        
            return false;
        }

        // used in forgot password feature
        public function updateName(){
        
            // update query
            $query = "UPDATE " . $this->table_name . "
                    SET name = :name
                    WHERE user_id = :user_id";
        
            // prepare the query
            $stmt = $this->conn->prepare($query);
        
            // sanitize
            $this->name=htmlspecialchars(strip_tags($this->name));
            $this->id=htmlspecialchars(strip_tags($this->id));
        
            // bind the values from the form
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':user_id', $this->id);
        
            // execute the query
            if($stmt->execute()){
                return true;
            }
        
            return false;
        }

        // used in forgot password feature
        public function updateEmail(){
        
            // update query
            $query = "UPDATE " . $this->table_name . "
                    SET email = :email,
                    status = 0
                    WHERE user_id = :user_id";
        
            // prepare the query
            $stmt = $this->conn->prepare($query);
        
            // sanitize
            $this->email=htmlspecialchars(strip_tags($this->email));
            $this->id=htmlspecialchars(strip_tags($this->id));
        
            // bind the values from the form
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':user_id', $this->id);
        
            // execute the query
            if($stmt->execute()){
                return true;
            }
        
            return false;
        }

        public function changeType(){
        
            // update query
            $query = "UPDATE " . $this->table_name . "
                    SET type = :type
                    WHERE user_id = :user_id";
        
            // prepare the query
            $stmt = $this->conn->prepare($query);
        
            // sanitize
            $this->type=htmlspecialchars(strip_tags($this->type));
            $this->id=htmlspecialchars(strip_tags($this->id));
        
            // bind the values from the form
            $stmt->bindParam(':type', $this->type, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $this->id);
        
            // execute the query
            if($stmt->execute()){
                return true;
            }
        
            return false;
        }
    }
?>