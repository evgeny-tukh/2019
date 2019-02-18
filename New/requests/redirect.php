<?php

    require_once 'session/session_mgr.php';
    require_once 'util/auth.php';
    
    function redirectTo ($url, $logoutLink = NULL)
    {
        if ($url)
        {
            $url     = "http://$url/login.php"; //urlencode ("$url/login.php");
            $data    = [ 'un' => 'jecat'/*SessionManager::getUserName ()*/, 'p' => 'karina'/*SessionManager::getUserPw ()*/ ];
            $payload = base64_encode (openssl_encrypt (json_encode ($data), 'AES-128-ECB', getEncriptionKey ()));
            $result  = "$url?k=$payload";
            
            if ($logoutLink)
                $result .= "&o=$logoutLink";
        }
        else
        {
            //header ('under_dev.php?b=main.php');
            $result = 'under_dev.php?b=main.php';
        }
        
        return $result;
    }
    