<?php
    class Result
    {
        private $conn;
        private $table_name;

        //object properties
        public $user_id;
        public $name;
        public $roll_no;
        public $score;
        public $submitted;

        //constructor
        public function __construct($db, $tcode)
        {
            $this->conn = $db;
            $this->table_name = $tcode;
        }

        public function createTable()
        {
            $this->table_name = htmlspecialchars(strip_tags($this->table_name));

            $query = "CREATE TABLE " . $this->table_name . " (
                        user_id VARCHAR(32) NOT NULL PRIMARY KEY, 
                        roll_no INT NOT NULL, 
                        name VARCHAR(50) NOT NULL, 
                        score FLOAT NOT NULL DEFAULT 0,
                        submitted TIMESTAMP NULL DEFAULT NULL,
                        CONSTRAINT fk_" . $this->table_name . "
                            FOREIGN KEY(user_id) 
                            REFERENCES users(user_id) ON DELETE CASCADE ON UPDATE CASCADE
                    )";

            $stmt = $this->conn->prepare($query);

            if($stmt->execute()) return true;

            return false;

        }

        public function recordPresent()
        {
            //query to check if user ID exists
            $query = "SELECT roll_no FROM " . $this->table_name . " WHERE user_id = :user_id LIMIT 0,1";

            $stmt = $this->conn->prepare($query);

            //sanitize
            $this->user_id = htmlspecialchars(strip_tags($this->user_id));

            //bind tcode value
            $stmt->bindParam(':user_id', $this->user_id);

            //execute query
            $stmt->execute();

            //get number of rows
            $num = $stmt->rowCount();

            //tcode exists
            if($num>0)
            {
                return true;    //since access is found in DB
            }

            return false; //as tcode is not found
        }

        //create new test record
        public function create()
        {
            try
            {
                // insert query
                $query = "INSERT INTO " . $this->table_name . " SET
                            name = :name,
                            user_id = :user_id,
                            roll_no = :roll_no,
                            score = 0";
            
                // prepare the query
                $stmt = $this->conn->prepare($query);
            
                // sanitize
                $this->name=htmlspecialchars(strip_tags($this->name));
                $this->user_id=htmlspecialchars(strip_tags($this->user_id));
                $this->roll_no=htmlspecialchars(strip_tags($this->roll_no));
            
                // bind the values
                $stmt->bindParam(':name', $this->name);
                $stmt->bindParam(':user_id', $this->user_id);
                $stmt->bindParam(':roll_no', $this->roll_no);
                        
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
            catch(Exception $e)
            {
                return false;
            }
        }
        
        //check if tcode is present and assign the properties
        public function recordExists()
        {
            //query to check if user ID exists
            $query = "SELECT name, roll_no, score, submitted FROM " . $this->table_name . " WHERE user_id = ? LIMIT 0,1";

            $stmt = $this->conn->prepare($query);

            //sanitize
            $this->user_id = htmlspecialchars(strip_tags($this->user_id));

            //bind tcode value
            $stmt->bindParam(1, $this->user_id);

            //execute query
            $stmt->execute();

            //get number of rows
            $num = $stmt->rowCount();

            //user_id exists
            if($num>0)
            {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $this->name=$row['name'];
                $this->roll_no=$row['roll_no'];
                $this->score=$row['score'];
                $this->submitted=$row['submitted'];
                return true;    //since user_id is found in DB
            }

            return false; //as tcode is not found
        }

        public function getAll()
        {
            // query to read all test records, with limit clause for pagination
            $query = "SELECT
                        user_id,
                        name,
                        roll_no,
                        score,
                        submitted
                    FROM " . $this->table_name . "
                    ORDER BY roll_no";
        
            // prepare query statement
            $stmt = $this->conn->prepare( $query );
        
            // execute query
            $stmt->execute();
        
            // return values
            return $stmt;
        }

        public function generateFile()
        {
            $fpath = "../../tests/{$this->table_name}/result_{$this->table_name}_{$_SESSION['id']}.csv";
            $res = @fopen($fpath,"w");

            $stmt = $this->getAll();

            fwrite($res,"Roll No.,Registration No.,Name,Score,Submission Time\n");

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                extract($row);
                $submitted = $submitted ? (string)date("d-m-Y h:m:s a", strtotime($submitted)) : "N/A";
                fwrite($res,"{$roll_no},{$user_id},{$name},{$score},{$submitted}\n");
            }

            fclose($res);
        }

        // read all test records
        public function readAll($from_record_num, $records_per_page)
        {
        
            // query to read all test records, with limit clause for pagination
            $query = "SELECT
                        user_id,
                        name,
                        roll_no,
                        score,
                        submitted
                    FROM " . $this->table_name . "
                    ORDER BY roll_no
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

        // used for paging tests
        public function countAll()
        {
        
            // query to select all test records
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

        public function deleteRecord()
        {
            $query = "DELETE FROM ". $this->table_name. " WHERE user_id = :user_id";

            $stmt = $this->conn->prepare($query);

            $this->user_id = htmlspecialchars(strip_tags($this->user_id));

            $stmt->bindParam(':user_id',$this->user_id);

            if($stmt->execute()) return true;
            return false;
        }

        public function deleteTable()
        {
            try
            {
                $query = "DROP TABLE ". $this->table_name;

                $stmt = $this->conn->prepare($query);

                if($stmt->execute()) return true;

                return false;
            }
            catch(Exception $e) 
            {
                return false;
            }
        }

        public function reset()
        {
            $query = "UPDATE ". $this->table_name. " SET score = 0, submitted = NULL";

            $stmt = $this->conn->prepare($query);

            if($stmt->execute()) return true;
            return false;
        }

        public function submitCount()
        {
            $query = "SELECT user_id from ". $this->table_name . " WHERE submitted IS NOT NULL";

            $stmt = $this->conn->prepare($query);

            if($stmt->execute()) return $stmt->rowCount();

            return false;
        }

        public function updateScore()
        {
            try
            {
                // update query
                $query = "UPDATE " . $this->table_name . " SET
                            score = :score,
                            submitted = :submitted
                            WHERE user_id = :user_id";
            
                // prepare the query
                $stmt = $this->conn->prepare($query);
            
                // sanitize
                $this->score=htmlspecialchars(strip_tags($this->score));
                $this->user_id=htmlspecialchars(strip_tags($this->user_id));
                $this->submitted=htmlspecialchars(strip_tags($this->submitted));
            
                // bind the values
                $stmt->bindParam(':score', $this->score);
                $stmt->bindParam(':user_id', $this->user_id);
                $stmt->bindParam(':submitted', $this->submitted);
                        
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
            catch(Exception $e)
            {
                return false;
            }
        }

        //create new test record
        public function updateRecord()
        {
            try
            {
                // update query
                $query = "UPDATE " . $this->table_name . " SET
                            name = :name,
                            roll_no = :roll_no
                            WHERE user_id = :user_id";
            
                // prepare the query
                $stmt = $this->conn->prepare($query);
            
                // sanitize
                $this->name=htmlspecialchars(strip_tags($this->name));
                $this->user_id=htmlspecialchars(strip_tags($this->user_id));
                $this->roll_no=htmlspecialchars(strip_tags($this->roll_no));
            
                // bind the values
                $stmt->bindParam(':name', $this->name);
                $stmt->bindParam(':user_id', $this->user_id);
                $stmt->bindParam(':roll_no', $this->roll_no);
                        
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
            catch(Exception $e)
            {
                return false;
            }
        }
    }
?>