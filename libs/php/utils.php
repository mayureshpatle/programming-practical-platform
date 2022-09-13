<?php
    class Utils
    {
        //generate token
        function getToken($length=32)
        {
            $token = "";
            $chars = "qwer2tyuiopm1n7bvcxz0asdlk3jhgfQA4ZWS6XEDCRFV5TGBYHNUJMIK8OLP9";
            $range = strlen($chars);
            for($i=0; $i<$length; ++$i)
            {
                $token .= $chars[$this->crypto_random($range)];
            }
            return $token;
        }

        //return a random number in [0,range)
        function crypto_random($range)
        {
            if($range < 0) return 0;
            $log = log($range,2);
            $bits = (int)log($range, 2) + 1;
            $bytes = (int)(log($range, 2)/8) + 1; 
            $mask = (int) (1<<$bits) - 1;
            do
            {
                $random = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
                $random = $random & $mask;
                if($random >= $range)
                {
                    $rand2 = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
                    $rand2 = $rand2 & $mask;
                    $random = $random ^ $rand2;
                }
            } while($random >= $range);
            return $random;
        }

        function tableExists($name)
        {
            $tables = array('USERS','TESTS','ACCESS', 'PROBLEMS','TESTING');

            return in_array($name, $tables);
        }

        function deleteDirectory($dir)
        {
            if($handle = opendir($dir))
            {
                while(false !== ($file = readdir($handle))) 
                {
                    if($file != "." && $file != "..") 
                    {
                        if(is_dir($dir.$file))
                        {
                            if(!@rmdir($dir.$file)) // Empty directory? Remove it
                            {
                                $this->deleteDirectory($dir.$file.'/'); // Not empty? Delete the files inside it
                            }
                        }
                        else
                        {
                            @unlink($dir.$file);
                        }
                    }
                }
                closedir($handle);
                @rmdir($dir);
            }
        }

        function prompt($prompt_msg)
        {
            echo("<script type='text/javascript'> var answer = prompt('".$prompt_msg."'); </script>");
    
            $answer = "<script type='text/javascript'> document.write(answer); </script>";
            return $answer;
        }
    }
?>