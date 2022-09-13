<?php
    class Problem
    {
        private $conn;
        private $table_name = "problems";

        //object properties
        public $pcode;
        public $pname;
        public $owner;
        public $created;
        public $last_editor;
        public $last_edit;
        public $owner_name;
        public $editor_name;
        public $ready;
        public $judge_key;
        public $c;
        public $cpp14;
        public $py3;
        public $java;
        public $c_time;
        public $cpp14_time;
        public $py3_time;
        public $java_time;
        public $c_ready;
        public $cpp14_ready;
        public $py3_ready;
        public $java_ready;
        public $flag;           //to update time on file change
        public $utils;

        //constructor
        public function __construct($db)
        {
            $this->conn = $db;
        }

        // read all problem records
        function readAll($from_record_num, $records_per_page)
        {
        
            // query to read all problem records, with limit clause for pagination
            $query = "SELECT
                        pcode,
                        pname,
                        owner,
                        last_editor,
                        last_edit,
                        owner_name,
                        editor_name,
                        ready
                    FROM " . $this->table_name . "
                    ORDER BY pcode
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

        // used for paging problems
        public function countAll()
        {
        
            // query to select all user records
            $query = "SELECT pcode FROM " . $this->table_name . "";
        
            // prepare query statement
            $stmt = $this->conn->prepare($query);
        
            // execute query
            $stmt->execute();
        
            // get number of rows
            $num = $stmt->rowCount();
        
            // return row count
            return $num;
        }

        public function pcodePresent()
        {
            //query to check if user ID exists
            $query = "SELECT pcode FROM " . $this->table_name . " WHERE pcode = ? LIMIT 0,1";

            $stmt = $this->conn->prepare($query);

            //sanitize
            $this->pcode = htmlspecialchars(strip_tags($this->pcode));

            //bind tcode value
            $stmt->bindParam(1, $this->pcode);

            //execute query
            $stmt->execute();

            //get number of rows
            $num = $stmt->rowCount();

            //pcode exists
            if($num>0)
            {
                return true;    //since pcode is found in DB
            }

            return false; //as pcode is not found
        }

        public function pcodeExists()
        {
            //query to check if user ID exists
            $query = "SELECT pname, owner, owner_name, created,
                    last_edit, last_editor, editor_name, 
                    ready, judge_key, c, cpp14, py3, java, 
                    c_time, cpp14_time, py3_time, java_time,
                    c_ready, cpp14_ready, py3_ready, java_ready, flag
                    FROM " . $this->table_name . " WHERE pcode = ? LIMIT 0,1";

            $stmt = $this->conn->prepare($query);

            //sanitize
            $this->pcode = htmlspecialchars(strip_tags($this->pcode));

            //bind tcode value
            $stmt->bindParam(1, $this->pcode);

            //execute query
            $stmt->execute();

            //get number of rows
            $num = $stmt->rowCount();

            //pcode exists
            if($num>0)
            {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                extract($row);
                $this->pname = $pname;
                $this->owner = $owner;
                $this->created = $created;
                $this->last_editor = $last_editor;
                $this->last_edit = $last_edit;
                $this->owner_name = $owner_name;
                $this->editor_name = $editor_name;
                $this->ready = $ready;
                $this->judge_key = $judge_key;
                $this->c = $c;
                $this->cpp14 = $cpp14;
                $this->py3 = $py3;
                $this->java = $java;
                $this->c_time = $c_time;
                $this->cpp14_time = $cpp14_time;
                $this->py3_time = $py3_time;
                $this->java_time = $java_time;
                $this->c_ready = $c_ready;
                $this->cpp14_ready = $cpp14_ready;
                $this->py3_ready = $py3_ready;
                $this->java_ready = $java_ready;
                $this->flag = $flag;
                return true;    //since pcode is found in DB
            }

            return false; //as pcode is not found
        }

        //create new problem record and directory
        public function create()
        {
            $this->judge_key = $this->utils->getToken(16);
            $this->owner = $_SESSION['id'];
            $this->owner_name = $_SESSION['name'];
            $this->last_editor = $_SESSION['id'];
            $this->editor_name = $_SESSION['name'];

            // insert query
            $query = "INSERT INTO " . $this->table_name . " SET
                        pcode = :pcode,
                        pname = :pname,
                        owner = :owner,
                        last_editor = :last_editor,
                        owner_name = :owner_name,
                        editor_name = :editor_name,
                        judge_key = :judge_key";
        
            // prepare the query
            $stmt = $this->conn->prepare($query);
        
            // sanitize
            $this->pcode=htmlspecialchars(strip_tags($this->pcode));
            $this->pname=htmlspecialchars(strip_tags($this->pname));
            $this->owner=htmlspecialchars(strip_tags($this->owner));
            $this->last_editor=htmlspecialchars(strip_tags($this->last_editor));
            $this->owner_name=htmlspecialchars(strip_tags($this->owner_name));
            $this->editor_name=htmlspecialchars(strip_tags($this->editor_name));
            $this->judge_key=htmlspecialchars(strip_tags($this->judge_key));
        
            // bind the values
            $stmt->bindParam(':pcode', $this->pcode);
            $stmt->bindParam(':pname', $this->pname);
            $stmt->bindParam(':owner', $this->owner);
            $stmt->bindParam(':last_editor', $this->last_editor);
            $stmt->bindParam(':owner_name', $this->owner_name);
            $stmt->bindParam(':editor_name', $this->editor_name);
            $stmt->bindParam(':judge_key', $this->judge_key);
        
            // execute the query, also check if query was successful
            if($stmt->execute())
            {
                try
                {
                    $home_dir = "../problems/{$this->pcode}";
                    @mkdir($home_dir, 0777, true);

                    $dir = $home_dir . "/drivers";
                    @mkdir($dir, 0777, true);

                    $fpath = $home_dir . "/description.txt";
                    $file = @fopen($fpath, "w");
                    @fclose($file);

                    $home_dir .= "/drivers";

                    //c language drivers
                    $dir = $home_dir . "/c";
                    @mkdir($dir, 0777, true);

                    @mkdir($dir . "/testing", 0777, true);

                    $fpath = $dir . "/head.txt";
                    $file = @fopen($fpath, "w");
                    @fclose($file);

                    $fpath = $dir . "/tail.txt";
                    $file = @fopen($fpath, "w");
                    @fclose($file);

                    $fpath = $dir . "/locked_head.txt";
                    $file = @fopen($fpath, "w");
                    @fclose($file);

                    $fpath = $dir . "/locked_tail.txt";
                    $file = @fopen($fpath, "w");
                    @fclose($file);

                    $fpath = $dir . "/description.txt";
                    $file = @fopen($fpath, "w");
                    @fclose($file);

                    //cpp14 drivers
                    $dir = $home_dir . "/cpp14";
                    @mkdir($dir, 0777, true);

                    @mkdir($dir . "/testing", 0777, true);

                    $fpath = $dir . "head.txt";
                    $file = @fopen($fpath, "w");
                    @fclose($file);

                    $fpath = $dir . "/tail.txt";
                    $file = @fopen($fpath, "w");
                    @fclose($file);

                    $fpath = $dir . "/locked_head.txt";
                    $file = @fopen($fpath, "w");
                    @fclose($file);

                    $fpath = $dir . "/locked_tail.txt";
                    $file = @fopen($fpath, "w");
                    @fclose($file);

                    $fpath = $dir . "/description.txt";
                    $file = @fopen($fpath, "w");
                    @fclose($file);

                    //python 3 drivers
                    $dir = $home_dir . "/py3";
                    @mkdir($dir, 0777, true);

                    @mkdir($dir . "/testing", 0777, true);

                    $fpath = $dir . "head.txt";
                    $file = @fopen($fpath, "w");
                    @fclose($file);

                    $fpath = $dir . "/tail.txt";
                    $file = @fopen($fpath, "w");
                    @fclose($file);

                    $fpath = $dir . "/locked_head.txt";
                    $file = @fopen($fpath, "w");
                    @fclose($file);

                    $fpath = $dir . "/locked_tail.txt";
                    $file = @fopen($fpath, "w");
                    @fclose($file);

                    $fpath = $dir . "/description.txt";
                    $file = @fopen($fpath, "w");
                    @fclose($file);

                    //java drivers
                    $dir = $home_dir . "/java";
                    @mkdir($dir, 0777, true);

                    @mkdir($dir . "/testing", 0777, true);

                    $fpath = $dir . "head.txt";
                    $file = @fopen($fpath, "w");
                    @fclose($file);

                    $fpath = $dir . "/tail.txt";
                    $file = @fopen($fpath, "w");
                    @fclose($file);

                    $fpath = $dir . "/locked_head.txt";
                    $file = @fopen($fpath, "w");
                    @fclose($file);

                    $fpath = $dir . "/locked_tail.txt";
                    $file = @fopen($fpath, "w");
                    @fclose($file);

                    $fpath = $dir . "/description.txt";
                    $file = @fopen($fpath, "w");
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

        public function delete()
        {
            $query = "DELETE FROM ". $this->table_name. " WHERE pcode = :pcode";

            $stmt = $this->conn->prepare($query);

            $this->pcode = htmlspecialchars(strip_tags($this->pcode));

            $stmt->bindParam(':pcode',$this->pcode);

            $this->utils->deleteDirectory(realpath("../problems/{$this->pcode}")."/");

            if($stmt->execute()) return true;
            return false;
        }

        public function setReady()
        {
            $status = true;
            $used = $this->c || $this->cpp14 || $this->py3 || $this->java;

            $status = (!$this->c || $this->c_ready) && $status;
            $status = (!$this->cpp14 || $this->cpp14_ready) && $status;
            $status = (!$this->py3 || $this->py3_ready) && $status;
            $status = (!$this->java || $this->java_ready) && $status;

            $this->ready = $status && $used;
        }

        public function update()
        {
            $this->setReady();
            $this->last_editor = $_SESSION['id'];
            $this->editor_name = $_SESSION['name'];
            
            $query = "UPDATE " . $this->table_name . " SET 
                    pname = :pname, last_editor = :last_editor, editor_name = :editor_name,
                    judge_key = :judge_key, ready = :ready, c = :c, cpp14 = :cpp14, py3 = :py3, java = :java,
                    c_ready = :c_ready, cpp14_ready = :cpp14_ready, py3_ready = :py3_ready, java_ready = :java_ready,
                    c_time = :c_time, cpp14_time = :cpp14_time, py3_time = :py3_time, java_time = :java_time, flag=:flag
                    WHERE pcode = :pcode";

            $stmt = $this->conn->prepare($query);

            $this->pcode=htmlspecialchars(strip_tags($this->pcode));
            $this->pname=htmlspecialchars(strip_tags($this->pname));
            $this->last_editor=htmlspecialchars(strip_tags($this->last_editor));
            $this->editor_name=htmlspecialchars(strip_tags($this->editor_name));
            $this->judge_key=htmlspecialchars(strip_tags($this->judge_key));
            $this->ready=htmlspecialchars(strip_tags($this->ready));
            $this->c=htmlspecialchars(strip_tags($this->c));
            $this->cpp14=htmlspecialchars(strip_tags($this->cpp14));
            $this->py3=htmlspecialchars(strip_tags($this->py3));
            $this->java=htmlspecialchars(strip_tags($this->java));
            $this->c_ready=htmlspecialchars(strip_tags($this->c_ready));
            $this->cpp14_ready=htmlspecialchars(strip_tags($this->cpp14_ready));
            $this->py3_ready=htmlspecialchars(strip_tags($this->py3_ready));
            $this->java_ready=htmlspecialchars(strip_tags($this->java_ready));
            $this->c_time=htmlspecialchars(strip_tags($this->c_time));
            $this->cpp14_time=htmlspecialchars(strip_tags($this->cpp14_time));
            $this->py3_time=htmlspecialchars(strip_tags($this->py3_time));
            $this->java_time=htmlspecialchars(strip_tags($this->java_time));
            $this->flag=htmlspecialchars(strip_tags($this->flag));

            $stmt->bindParam(':pcode', $this->pcode);
            $stmt->bindParam(':pname', $this->pname);
            $stmt->bindParam(':last_editor', $this->last_editor);
            $stmt->bindParam(':editor_name', $this->editor_name);
            $stmt->bindParam(':judge_key', $this->judge_key);
            $stmt->bindParam(':ready', $this->ready, PDO::PARAM_INT);
            $stmt->bindParam(':c', $this->c, PDO::PARAM_INT);
            $stmt->bindParam(':cpp14', $this->cpp14, PDO::PARAM_INT);
            $stmt->bindParam(':py3', $this->py3, PDO::PARAM_INT);
            $stmt->bindParam(':java', $this->java, PDO::PARAM_INT);
            $stmt->bindParam(':c_ready', $this->c_ready, PDO::PARAM_INT);
            $stmt->bindParam(':cpp14_ready', $this->cpp14_ready, PDO::PARAM_INT);
            $stmt->bindParam(':py3_ready', $this->py3_ready, PDO::PARAM_INT);
            $stmt->bindParam(':java_ready', $this->java_ready, PDO::PARAM_INT);
            $stmt->bindParam(':c_time', $this->c_time);
            $stmt->bindParam(':cpp14_time', $this->cpp14_time);
            $stmt->bindParam(':py3_time', $this->py3_time);
            $stmt->bindParam(':java_time', $this->java_time);
            $stmt->bindParam(':flag', $this->flag, PDO::PARAM_INT);

            if($stmt->execute()) return true;

            return false;
        }

        function submitted($response)
        {
            $error = $response->error!="" || $response->invalid_score || $response->format_error;
            $alert = $error ? "alert-danger" : "alert-success";
            echo "<div class='col-md-12'>
                    <div class='alert {$alert}'>
                        <h3></b>Your Submission Got {$response->score} points </b></h3>";
            
            if($error)
            {
                if($response->error!="") echo "ERROR DETAILS: {$response->error} <br />";
                if($response->format_error) echo "VERDICT: Invalid Output Format <br />";
                if($response->invalid_score) echo "VERDICT: Invalid Score <br />";
            }

            if($response->score == 100)
            {
                $flag = false;
                if($response->lang == "C" && $this->c_ready != 1)
                {
                    $this->c_ready = 1;
                    $flag = true; 
                }
                else if($response->lang == "CPP14" && $this->cpp14_ready != 1)
                {
                    $this->cpp14_ready = 1;
                    $flag = true; 
                }
                else if($response->lang == "PYTHON3" && $this->py3_ready != 1) 
                {
                    $this->py3_ready = 1;
                    $flag = true; 
                }
                else if($this->java_ready != 1) 
                {
                    $this->java_ready = 1;
                    $flag = true;
                }
                if($flag)
                {
                    $this->update();
                    echo "Status of this language is now set to ready. <br />";
                }
            }

            echo "</div></div>";

        }
    }
?>