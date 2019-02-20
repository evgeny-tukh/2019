<?php

    require_once 'db/database.php';
    require_once 'session/session_mgr.php';
    require_once 'util/util.php';
    require_once 'util/auth.php';
    
    $sessionMgr = new SessionManager ();
    $authorized = FALSE;
    $message    = "Invalid user name.";
    $userID     = NULL;
    $isAdmin    = FALSE;
    $features   = 0;

    $database = new Database ();
    
    if ($database)
    {
        $authKey    = getAuthKey ();
        $logoutLink = getLogoutKey ();
        $data       = $authKey ? json_decode (openssl_decrypt (base64_decode ($authKey), 'AES-128-ECB', getEncriptionKey ()), TRUE) : $_POST;
        $userInfo   = $database->checkCredentials ($data ["un"], $data ["p"]);

        if (!$userInfo)
        {
            $message = "Authorization failed";
        }
        else
        {
            $message    = "Authorization passed";
            $authorized = TRUE;
            $userID     = $userInfo ['id'];
            $isAdmin    = $database->isAdmin ($userID);
            $features   = $database->getFeatures ($userID);
        }
        
        $database->close ();
    }

    if ($authorized)
    {
        $sessionMgr->setAuthenticationStatus (TRUE);
        $sessionMgr->setUserID ($userID);
        $sessionMgr->setUserFeatures ($features);
        $sessionMgr->setAccessTime ();

        if ($authKey)
            header ("Location: index.php?k=$authKey");
        else
            header ('Location: index.php');
    }
    else
    {
        $sessionMgr->setAuthenticationStatus (FALSE);
        
        header ('Location: login.html');
    }
?>
