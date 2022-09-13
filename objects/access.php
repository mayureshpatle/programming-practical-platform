<?php
    class Access
    {
        private $conn;
        private $table_name = "access";

        //object properties
        public $tcode;
        public $user_id;
        public $utils;

        //constructor
        public function __construct($db)
        {
            $this->conn = $db;
        }

        public function accessPresent()
        {
            //query to check if user ID exists
            $query = "SELECT tcode FROM " . $this->table_name . " WHERE tcode = :tcode AND user_id = :user_id LIMIT 0,1";

            $stmt = $this->conn->prepare($query);

            //sanitize
            $this->tcode = htmlspecialchars(strip_tags($this->tcode));
            $this->user_id = htmlspecialchars(strip_tags($this->user_id));

            //bind values
            $stmt->bindParam(':tcode', $this->tcode);
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
            // insert query
            $query = "INSERT INTO " . $this->table_name . " SET
                        tcode = :tcode,
                        user_id = :user_id";
        
            // prepare the query
            $stmt = $this->conn->prepare($query);
        
            // sanitize
            $this->tcode=htmlspecialchars(strip_tags($this->tcode));
            $this->user_id=htmlspecialchars(strip_tags($this->user_id));
        
            // bind the values
            $stmt->bindParam(':tcode', $this->tcode);
            $stmt->bindParam(':user_id', $this->user_id);
                    
            // execute the query, also check if query was successful
            if($stmt->execute())
            {
                try
                {
                    $dir = "tests/{$this->tcode}/{$this->user_id}";
                    @mkdir($dir, 0777, false);

                    $fpath = $dir . "/c.txt";
                    $file = @fopen($fpath,"w");
                    @fclose($file);

                    $fpath = $dir . "/cpp14.txt";
                    $file = @fopen($fpath,"w");
                    @fclose($file);

                    $fpath = $dir . "/py3.txt";
                    $file = @fopen($fpath,"w");
                    @fclose($file);

                    $fpath = $dir . "/java.txt";
                    $file = @fopen($fpath,"w");
                    @fclose($file);

                    $fpath = $dir . "/best.txt";
                    $file = @fopen($fpath,"w");
                    @fclose($file);

                    return true;
                }

                catch(Exception $e)
                {
                    $this->delete();
                    return false;
                }
            }
            else
            {
                $this->showError($stmt);
                return false;
            }
        }
        
        //check if tcode is present and assign the properties
        public function accessExists()
        {
            //query to check access exists
            $query = "SELECT tcode  FROM " . $this->table_name . " WHERE tcode = :tcode AND user_id = :user_id LIMIT 0,1";

            $stmt = $this->conn->prepare($query);

            //sanitize
            $this->tcode = htmlspecialchars(strip_tags($this->tcode));
            $this->user_id = htmlspecialchars(strip_tags($this->user_id));

            //bind values
            $stmt->bindParam(':tcode', $this->tcode);
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

            return false; //as access is not found
        }

        public function delete()
        {
            $query = "DELETE FROM ". $this->table_name. " WHERE tcode = :tcode AND user_id = :user_id";

            $stmt = $this->conn->prepare($query);

            $this->tcode = htmlspecialchars(strip_tags($this->tcode));
            $this->user_id = htmlspecialchars(strip_tags($this->user_id));

            $stmt->bindParam(':tcode',$this->tcode);
            $stmt->bindParam(':user_id',$this->user_id);

            $dir = "tests/{$this->tcode}/{$this->user_id}";

            $this->utils->deleteDirectory(realpath($dir)."/");

            if($stmt->execute()) return true;
            return false;
        }

        public function deregister()
        {
            $query = "DELETE FROM ". $this->table_name. " WHERE tcode = :tcode AND user_id = :user_id";

            $stmt = $this->conn->prepare($query);

            $this->tcode = htmlspecialchars(strip_tags($this->tcode));
            $this->user_id = htmlspecialchars(strip_tags($this->user_id));

            $stmt->bindParam(':tcode',$this->tcode);
            $stmt->bindParam(':user_id',$this->user_id);

            $dir = "../tests/{$this->tcode}/{$this->user_id}";

            $this->utils->deleteDirectory(realpath($dir)."/");

            if($stmt->execute()) return true;
            return false;
        }

        public function deleteUser($db)
        {
            include_once "../../objects/result.php";

            //fetch all registered tests
            $query = "SELECT tcode FROM " . $this->table_name . " WHERE user_id = :user_id";

            $stmt = $this->conn->prepare($query);

            $this->user_id = htmlspecialchars(strip_tags($this->user_id));

            $stmt->bindParam(":user_id", $this->user_id);

            $stmt->execute();

            //delete submissions & result records
            while($row = $stmt->fetch(PDO::FETCH_ASSOC))
            {
                extract($row);

                $dir = "../../tests/{$tcode}/{$this->user_id}";

                $this->utils->deleteDirectory(realpath($dir)."/");

                //delete result records
                $result = new Result($db, $tcode);
                $result->user_id = $this->user_id;
                $result->deleteRecord();
            }

            //deregister
            $query = "DELETE FROM ". $this->table_name. " WHERE user_id = :user_id";

            $stmt = $this->conn->prepare($query);

            $this->user_id = htmlspecialchars(strip_tags($this->user_id));

            $stmt->bindParam(':user_id',$this->user_id);

            if($stmt->execute()) return true;
            return false;
        }
    }
?>