<?php
    class Test
    {
        private $conn;
        private $table_name = "tests";
        private $judge;
        private $result;
        private $problem;

        //object properties
        public $tcode;
        public $tname;
        public $pcode;
        public $status;
        public $reg_key;
        public $created;
        public $utils;
        public $lang;

        //constructor
        public function __construct($db)
        {
            $this->conn = $db;
        }

        // read all test records
        function readAll($from_record_num, $records_per_page)
        {
        
            // query to read all test records, with limit clause for pagination
            $query = "SELECT
                        tcode,
                        tname,
                        pcode,
                        status,
                        reg_key
                    FROM " . $this->table_name . "
                    ORDER BY status DESC, tcode
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
            $query = "SELECT tcode FROM " . $this->table_name . "";
        
            // prepare query statement
            $stmt = $this->conn->prepare($query);
        
            // execute query
            $stmt->execute();
        
            // get number of rows
            $num = $stmt->rowCount();
        
            // return row count
            return $num;
        }

        // read all test records for specific user
        function readMy($from_record_num, $records_per_page, $user_id)
        {
        
            // query to read all test records, with limit clause for pagination
            $query = "SELECT
                        tcode,
                        tname,
                        pcode,
                        status
                    FROM " . $this->table_name . " NATURAL JOIN access
                    WHERE user_id = ? 
                    ORDER BY status DESC, tcode
                    LIMIT ?, ?";
        
            // prepare query statement
            $stmt = $this->conn->prepare( $query );

            $user_id=htmlspecialchars(strip_tags($user_id));
        
            // bind limit clause variables
            $stmt->bindParam(1, $user_id);
            $stmt->bindParam(2, $from_record_num, PDO::PARAM_INT);
            $stmt->bindParam(3, $records_per_page, PDO::PARAM_INT);
            
        
            // execute query
            $stmt->execute();
        
            // return values
            return $stmt;
        }

        // used for paging mytests
        public function countMy($user_id)
        {
        
            // query to read all test records corresponding to user
            $query = "SELECT tcode FROM " . $this->table_name . " NATURAL JOIN access WHERE user_id = ?";
        
            // prepare query statement
            $stmt = $this->conn->prepare($query);

            $user_id=htmlspecialchars(strip_tags($user_id));
        
            // bind
            $stmt->bindParam(1, $user_id);
        
            // execute query
            $stmt->execute();
        
            // get number of rows
            $num = $stmt->rowCount();
        
            // return row count
            return $num;
        }

        //only returns true or false if tcode is present or not present respectively
        public function tcodePresent()
        {
            //query to check if user ID exists
            $query = "SELECT tcode FROM " . $this->table_name . " WHERE tcode = ? LIMIT 0,1";

            $stmt = $this->conn->prepare($query);

            //sanitize
            $this->tcode = htmlspecialchars(strip_tags($this->tcode));

            //bind tcode value
            $stmt->bindParam(1, $this->tcode);

            //execute query
            $stmt->execute();

            //get number of rows
            $num = $stmt->rowCount();

            //tcode exists
            if($num>0)
            {
                return true;    //since tcode is found in DB
            }

            return false; //as tcode is not found
        }

        //create new test record
        public function create()
        {
            $this->reg_key = $this->utils->getToken(16);

            // insert query
            $query = "INSERT INTO " . $this->table_name . " SET
                        tcode = :tcode,
                        tname = :tname,
                        pcode = :pcode,
                        status = :status,
                        reg_key = :reg_key";
        
            // prepare the query
            $stmt = $this->conn->prepare($query);
        
            // sanitize
            $this->tcode=htmlspecialchars(strip_tags($this->tcode));
            $this->pcode=htmlspecialchars(strip_tags($this->pcode));
            $this->tname=htmlspecialchars(strip_tags($this->tname));
            $this->status=htmlspecialchars(strip_tags($this->status));
            $this->reg_key=htmlspecialchars(strip_tags($this->reg_key));
        
            // bind the values
            $stmt->bindParam(':tcode', $this->tcode);
            $stmt->bindParam(':tname', $this->tname);
            $stmt->bindParam(':pcode', $this->pcode);
            $stmt->bindParam(':status', $this->status, PDO::PARAM_INT);
            $stmt->bindParam(':reg_key', $this->reg_key);
        
            // execute the query, also check if query was successful
            if($stmt->execute())
            {
                try
                {
                    $home_dir = "../tests/{$this->tcode}";
                    @mkdir($home_dir, 0777, true);

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
        public function tcodeExists()
        {
            //query to check if user ID exists
            $query = "SELECT tname, status, created FROM " . $this->table_name . " WHERE tcode = ? LIMIT 0,1";

            $stmt = $this->conn->prepare($query);

            //sanitize
            $this->tcode = htmlspecialchars(strip_tags($this->tcode));

            //bind tcode value
            $stmt->bindParam(1, $this->tcode);

            //execute query
            $stmt->execute();

            //get number of rows
            $num = $stmt->rowCount();

            //tcode exists
            if($num>0)
            {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $this->tname=$row['tname'];
                $this->status=$row['status'];
                $this->created=$row['created'];
                return true;    //since tcode is found in DB
            }

            return false; //as tcode is not found
        }

        //check if tcode is present and assign the properties
        public function tcodeExistsAdmin()
        {
            //query to check if user ID exists
            $query = "SELECT tname, pcode, reg_key, status, created FROM " . $this->table_name . " WHERE tcode = ? LIMIT 0,1";

            $stmt = $this->conn->prepare($query);

            //sanitize
            $this->tcode = htmlspecialchars(strip_tags($this->tcode));

            //bind tcode value
            $stmt->bindParam(1, $this->tcode);

            //execute query
            $stmt->execute();

            //get number of rows
            $num = $stmt->rowCount();

            //tcode exists
            if($num>0)
            {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $this->tname=$row['tname'];
                $this->pcode=$row['pcode'];
                $this->reg_key=$row['reg_key'];
                $this->status=$row['status'];
                $this->created=$row['created'];
                return true;    //since tcode is found in DB
            }

            return false; //as tcode is not found
        }

        public function setName()
        {
            // update query
            $query = "UPDATE " . $this->table_name . " SET tname = :tname WHERE tcode = :tcode";

            // prepare the query
            $stmt = $this->conn->prepare($query);
        
            // sanitize
            $this->tcode=htmlspecialchars(strip_tags($this->tcode));
            $this->tname=htmlspecialchars(strip_tags($this->tname));

            // bind the values
            $stmt->bindParam(':tcode', $this->tcode);
            $stmt->bindParam(':tname', $this->tname);

            if($stmt->execute()) return true;
            
            return false;
        }

        public function setStatus()
        {
            // update query
            $query = "UPDATE " . $this->table_name . " SET status = :status WHERE tcode = :tcode";

            // prepare the query
            $stmt = $this->conn->prepare($query);
        
            // sanitize
            $this->tcode=htmlspecialchars(strip_tags($this->tcode));
            $this->status=htmlspecialchars(strip_tags($this->status));

            // bind the values
            $stmt->bindParam(':tcode', $this->tcode);
            $stmt->bindParam(':status', $this->status, PDO::PARAM_INT);

            if($stmt->execute()) return true;
            
            return false;
        }

        public function getRegKey()
        {
            //select query
            $query = "SELECT reg_key from ". $this->table_name . " WHERE tcode = :tcode";

            $stmt = $this->conn->prepare($query);

            $this->tcode=htmlspecialchars(strip_tags($this->tcode));

            $stmt->bindParam(':tcode', $this->tcode);

            $stmt->execute();

            $num = $stmt->rowCount();

            if($num > 0)
            {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $this->reg_key = $row['reg_key'];
                return true;
            }

            return false;
        }

        public function resetRegKey()
        {
            $this->reg_key = $this->utils->getToken(8);

            // update query
            $query = "UPDATE " . $this->table_name . " SET reg_key = :reg_key WHERE tcode = :tcode";

            // prepare the query
            $stmt = $this->conn->prepare($query);
        
            // sanitize
            $this->tcode=htmlspecialchars(strip_tags($this->tcode));
            $this->reg_key=htmlspecialchars(strip_tags($this->reg_key));

            // bind the values
            $stmt->bindParam(':tcode', $this->tcode);
            $stmt->bindParam(':reg_key', $this->reg_key);

            if($stmt->execute()) return true;
            
            return false;
        }

        public function delete()
        {
            $query = "DELETE FROM ". $this->table_name. " WHERE tcode = :tcode";

            $stmt = $this->conn->prepare($query);

            $this->tcode = htmlspecialchars(strip_tags($this->tcode));

            $stmt->bindParam(':tcode',$this->tcode);

            $this->utils->deleteDirectory(realpath("../tests/{$this->tcode}")."/");

            if($stmt->execute()) return true;
            return false;
        }

        public function deleteTest()
        {
            $query = "DELETE FROM ". $this->table_name. " WHERE tcode = :tcode";

            $stmt = $this->conn->prepare($query);

            $this->tcode = htmlspecialchars(strip_tags($this->tcode));

            $stmt->bindParam(':tcode',$this->tcode);

            $this->utils->deleteDirectory(realpath("../../tests/{$this->tcode}")."/");

            if($stmt->execute()) return true;
            return false;
        }

        public function changeProblem()
        {
            // update query
            $query = "UPDATE " . $this->table_name . " SET pcode = :pcode WHERE tcode = :tcode";

            // prepare the query
            $stmt = $this->conn->prepare($query);
        
            // sanitize
            $this->tcode=htmlspecialchars(strip_tags($this->tcode));
            $this->pcode=htmlspecialchars(strip_tags($this->pcode));

            // bind the values
            $stmt->bindParam(':tcode', $this->tcode);
            $stmt->bindParam(':pcode', $this->pcode);

            if($stmt->execute()) 
            {
                return true;
            }
            return false;
        }

        public function getBest()
        {
            $fpath =  "../../tests/{$this->tcode}/{$this->result->user_id}/best.txt";
            if(is_file($fpath))
            {
                //soln
                $size = filesize($fpath);
                if($size)
                {
                    $soln = fopen($fpath,"r");
                    $code = @fread($soln,$size);
                    fclose($soln);
                }
                else $code = "";

                return $code;
            }
            
            return false;
        }

        public function getSaved()
        {
            $langs = $this->getLangs();

            $solns = array();
            
            foreach($langs as $lang)
            {
                $this->lang = $lang;
                $lang_lib = $this->langLib();

                $fpath =  "../../tests/{$this->tcode}/{$this->result->user_id}/{$lang_lib}.txt";

                $code = false;

                if(is_file($fpath))
                {
                    //soln
                    $size = filesize($fpath);
                    if($size)
                    {
                        $soln = fopen($fpath,"r");
                        $code = @fread($soln,$size);
                        fclose($soln);
                    }
                    else $code = "";
                }

                $solns[$lang] = $code;
            }
            
            return $solns;
        }

        public function rollNo()
        {
            return $this->result->roll_no;
        }

        public function fetchProblem()
        {
            //select query
            $query = "SELECT pcode from ". $this->table_name . " WHERE tcode = :tcode";

            $stmt = $this->conn->prepare($query);

            $this->tcode=htmlspecialchars(strip_tags($this->tcode));

            $stmt->bindParam(':tcode', $this->tcode);

            $stmt->execute();

            $num = $stmt->rowCount();

            if($num > 0)
            {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $this->pcode = $row['pcode'];
                $this->problem->pcode = $this->pcode;
                return $this->problem->pcodeExists();
            }

            return false;
        }

        public function initProblem($problem_obj)
        {
            $this->problem = $problem_obj;
            return $this->fetchProblem();
        }

        public function getPname()
        {
            return $this->problem->pname;
        }

        public function timeLimit()
        {
            if($this->lang == "C") return $this->problem->c_time;
            if($this->lang == "CPP14") return $this->problem->cpp14_time;
            if($this->lang == "JAVA") return $this->problem->java_time;
            return $this->problem->py3_time;
        }

        public function langLib()
        {
            if($this->lang == "C") return "c";
            if($this->lang == "CPP14") return "cpp14";
            if($this->lang == "JAVA") return "java";
            return "py3";
        }

        public function getDesc()
        {
            $lang_lib = $this->langLib();

            //description
            $fpath = "../problems/{$this->pcode}/description.txt";
            $size = filesize($fpath);
            if($size)
            {
                $desc = fopen($fpath,"r");
                $description = "";
                $description = @fread($desc,$size);
                fclose($desc);
            }
            else $description = "";
            $description .= "\n\n";

            //language specific description
            $fpath = "../problems/{$this->pcode}/drivers/{$lang_lib}/description.txt";
            $size = filesize($fpath);
            if($size)
            {
                $desc = fopen($fpath,"r");
                $description .= @fread($desc,$size);
                fclose($desc);
            }

            return $description;
        }

        public function getLockedHead()
        {
            $lang_lib = $this->langLib();

            //locked head code
            $fpath = "../problems/{$this->pcode}/drivers/{$lang_lib}/locked_head.txt";
            $size = filesize($fpath);
            if($size)
            {
                $desc = fopen($fpath,"r");
                $locked_head = "";
                $locked_head = @fread($desc,$size);
                $locked_head = "```{$lang_lib}\n" . $locked_head . "\n```";
                fclose($desc);
            }
            else $locked_head = "";

            return $locked_head;
        }

        public function getLockedTail()
        {
            $lang_lib = $this->langLib();

            //locked tail code
            $fpath = "../problems/{$this->pcode}/drivers/{$lang_lib}/locked_tail.txt";
            $size = filesize($fpath);
            if($size)
            {
                $desc = fopen($fpath,"r");
                $locked_tail = "";
                $locked_tail = @fread($desc,$size);
                $locked_tail = "```{$lang_lib}\n" . $locked_tail . "\n```";
                fclose($desc);
            }
            else $locked_tail = "";

            return $locked_tail;
        }

        public function prevCode()
        {
            $lang_lib = $this->langLib();

            //saved solution
            $fpath = "../tests/{$this->tcode}/{$_SESSION['id']}/{$lang_lib}.txt";
            $soln = @fopen($fpath,"a");
            @fclose($soln);
            $size = filesize($fpath);
            if($size)
            {
                $soln = fopen($fpath,"r");
                $old_code = @fread($soln, $size );
                fclose($soln);
            }
            else $old_code = "";

            return $old_code;
        }

        public function getLangs()
        {
            $langs = array();
            if($this->problem->c) array_push($langs, "C");
            if($this->problem->cpp14) array_push($langs, "CPP14");
            if($this->problem->py3) array_push($langs, "PYTHON3");
            if($this->problem->java) array_push($langs, "JAVA");
            return $langs;
        }

        public function saveCode($code)
        {
            try
            {
                $lang_lib = $this->langLib();

                //solution file
                $fpath = "../tests/{$this->tcode}/{$_SESSION['id']}/{$lang_lib}.txt";
                $soln = @fopen($fpath,"w");
                @fwrite($soln, $code);
                @fclose($soln);
                return true;
            }
            catch(Exception $e)
            {
                return false;
            }
        }

        public function saveBest($submission, $curr_time)
        {
            try
            {
                //save language in comment
                $sym_st = "/*\n";
                $sym_en = "\n*/";
                $sym = "\n//";
                if($this->lang == "PYTHON3")
                {
                    $sym_st = "'''\n";
                    $sym_en = "\n'''";
                    $sym = "\n#";
                }

                $code = $sym_st . "ROLL NO.: {$this->result->roll_no}\nREG NO. {$_SESSION['id']}\nLANGUAGE: {$this->lang}\n";

                $code .= "SUBMITTED ON: {$curr_time}\nSCORE: {$this->bestScore()}" . $sym_en;

                $code .= $sym . "-------------------------------------------\n\n";

                $code .= $submission;

                //best solution
                $fpath = "../tests/{$this->tcode}/{$_SESSION['id']}/best.txt";
                $soln = @fopen($fpath,"w");
                @fwrite($soln, $code);
                @fclose($soln);

                
                $this->result->submitted = date("Y-m-d H:i:s", strtotime($curr_time));
                return $this->result->updateScore();
            }
            catch(Exception $e)
            {
                return false;
            }
        }

        public function initResult($result_obj)
        {
            $this->result = $result_obj;
            $this->result->user_id = $_SESSION['id'];
            return $this->result->recordExists();
        }

        public function initResultAdmin($result_obj, $user_id)
        {
            $this->result = $result_obj;
            $this->result->user_id = $user_id;
            return $this->result->recordExists();
        }

        public function bestScore()
        {
            return $this->result->score;
        }

        public function getHead()
        {
            $lang_lib = $this->langLib();

            // head code
            $fpath = "../problems/{$this->pcode}/drivers/{$lang_lib}/head.txt";
            $size = filesize($fpath);
            if($size)
            {
                $desc = fopen($fpath,"r");
                $head = @fread($desc,$size);
                fclose($desc);
            }
            else $head = "";

            return $head;
        }

        public function getTail()
        {
            $lang_lib = $this->langLib();

            // tail code
            $fpath = "../problems/{$this->pcode}/drivers/{$lang_lib}/tail.txt";
            $size = filesize($fpath);
            if($size)
            {
                $desc = fopen($fpath,"r");
                $tail = "";
                $tail = @fread($desc,$size);
                fclose($desc);
            }
            else $tail = "";

            return $tail;
        }

        public function initJudge($judge_obj, $problem_obj)
        {
            $this->judge = $judge_obj;
            return $this->initProblem($problem_obj);
        }

        public function submit($code)
        {
            if(!$this->fetchProblem()) return false;


            //drivers
            $head = $this->getHead();
            $tail = $this->getTail();

            //source_code
            $source_code = $head . "\n" . $code . "\n" . $tail;

            $response = $this->judge->submit_problem($source_code, $this->lang, $this->problem);

            if(!$response) return false;


            $best = $this->bestScore();

            if($best==0 || $response->score > $best)
            {
                $this->result->score = $response->score;
                $curr_time = date("d-m-Y h:i:s a"); 
                $this->saveBest($code, $curr_time);
            }

            return $response;
        }


    }
?>