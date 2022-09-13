<?php

    //error_repoting(E_ALL);

    if(!isset($_SESSION))
    {
        session_start();
    }

    //timezone
    date_default_timezone_set('Asia/Calcutta');

    //home page url
    $home_url="http://localhost/a14project/";
    //$home_url="http://mayureshpatle-61466.portmap.io:61466/a14project/";

    //styling info
    $jquery = $home_url . 'libs/jquery.js';
    $bootstrap_js = $home_url . 'libs/bootstrap.js';
    $bootstrap = $home_url . 'libs/bootstrap.css';


    // Information from the XOAUTH2 configuration.
    $mailer_mail = 'ycce.codingclub@gmail.com';
    $oauth2_clientId = '269964978990-ffmgger1g29sssfvgnfknle8a7v8hgaj.apps.googleusercontent.com';
    $oauth2_clientSecret = 'xFJWpzB9FRgFlCMVCqYy9K0u';
    $oauth2_refreshToken = '1//040MupP_q4wHSCgYIARAAGAQSNwF-L9IrojR2jqWoLnJkEAtymGCDJe8iwIgBWHg9uI2aXuczeYqNDDFoUP3Bxg2Y1hPnGpCfUTY';

    // Information for online judge API
    $oj_url = "https://api.hackerearth.com/v4/partner/code-evaluation/submissions/";
    $oj_client_secret = "2120528c9183cba83828108bbfd520a1f213bdd8";

    //paging info
    $page = isset($_GET['page'])?$_GET['page']:1;

    $records_per_page = 10;

    $from_record_num = ($records_per_page * $page) - $records_per_page;
    
?>