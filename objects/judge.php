<?php

class Response
{
    public $score;
    public $error;
    public $compile_status;
    public $format_error;
    public $invalid_score;
    public $lang;
    public $status;

    function __construct($lang)
    {
        $this->score = 0;
        $this->error = "";
        $this->compile_status = "";
        $this->format_error = false;
        $this->invalid_score = false;
        $this->lang = $lang;
        $this->status = "";
    }

}

class Judge
{
    private $api_url;
    private $client_secret;
    function __construct($api_url, $client_secret)
    {
        $this->api_url = $api_url;
        $this->client_secret = $client_secret;
    }

    function submit_problem($source_code, $lang, $problem)
    {
        if(!$problem->pcodeExists()) return false;

        $time_limit = $lang=="C" ? $problem->c_time : (  $lang=="CPP14" ? $problem->cpp14_time : ( $lang=="PYTHON3" ? $problem->py3_time : $problem->java_time ));

        $time_limit = (float) $time_limit;

        //Building data for API call
        $data_array = array(
            "client-secret"=> $this->client_secret,
            "lang"=> $lang,
            "source"=> $source_code,
            "input"=> "",
            "memory_limit"=> 243232,
            "time_limit"=> $time_limit,
            "context"=> "",
            "callback"=> ""
        );
        $data_json = json_encode($data_array);

        //cURL api call to run the code

        $curl_call = curl_init();
        curl_setopt($curl_call, CURLOPT_URL, $this->api_url);
        curl_setopt($curl_call, CURLOPT_POST, true);
        curl_setopt($curl_call, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($curl_call, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_call, CURLOPT_HTTPHEADER, array('client-secret: '.$this->client_secret, 'content-type: application/json'));
        $response_json = curl_exec($curl_call);
        $response_object = json_decode($response_json);

        $response = new Response($lang);

        if($err = curl_error($curl_call))
        {
            $response->error .= "\n" . $err;
        }
        else
        {
            $response->status .= $response_object->result->compile_status . "<br />";
        }

        curl_close($curl_call);

        $queued =0;
        $compiled =0;

        //cURL Call
        $curl_call = curl_init();


        curl_setopt($curl_call, CURLOPT_URL, $response_object->status_update_url);
        curl_setopt($curl_call, CURLOPT_POST, true);
        curl_setopt($curl_call, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_call, CURLOPT_HTTPHEADER, array('client-secret: '.$this->client_secret));

        label:
        $response_json = curl_exec($curl_call);
        $response_object = json_decode($response_json);

        if($err = curl_error($curl_call)) $response->error .= "\n\n" . $err;
        else{
            
            if($response_object->request_status->code == "REQUEST_QUEUED")
            {
                if($queued == 0)
                {
                    $response->status .= "REQUEST QUEUED<br />";
                    $queued=1;
                }
                goto label;
            }
            elseif($response_object->request_status->code == "CODE_COMPILED" && $response_object->result->compile_status == "OK")
            {
                if($compiled == 0)
                {
                    $response->status .= "CODE COMPILED<br />";
                    $compiled=1;
                }
                goto label;
            }
            if($response_object->request_status->code == "REQUEST_COMPLETED")
            {
                $output_url = $response_object->result->run_status->output;
                $result = $response_object->result->run_status->status;
                $output = file_get_contents($output_url);

                $key = $problem->judge_key . ":";
                $len = strlen($key);

                $output = trim($output);

                $last = false;
                
                foreach(preg_split('~[\r\n]+~', $output) as $line)
                {
                    if($last)
                    {
                        $response->score = 0;
                        $response->format_error = true;
                        break;
                    }

                    $line = rtrim($line);
                    $judge_key=substr($line, 0, $len);
                    if($judge_key != $key)
                    {
                        $response->score = 0;
                        $response->format_error = true;
                        break;
                    }
                    $test_score = substr($line, $len);
                    if(is_numeric($test_score)) $response->score += (int)$test_score;
                    else if($trim($test_score)=="")
                    {
                        $last = 1;
                    }
                    else
                    {
                        $response->score = 0;
                        $response->format_error = true;
                        break;
                    }

                    if($response->score>100)
                    {
                        $response->invalid_score = true;
                        $response->score = 0;
                        break;
                    }
                }
            }
            else
            {
                $response->score = 0;
                $response->compile_status .= nl2br($response_object->result->compile_status);
            }
        }

        //End the curl call
        curl_close($curl_call);

        return $response;
    }

}

?>