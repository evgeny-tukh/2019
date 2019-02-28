<?php

    require_once 'db/database.php';
    require_once 'session/session_mgr.php';
    require_once 'util/util.php';
    require_once 'util/auth.php';

    $sessionMgr = new SessionManager ();
    $creds      = [ "jecat" => "karina", "francesco" => "8nFMpLV4", "philippe" => "tatjana", "guest" => "" ];
    $authorized = FALSE;
    $message    = "Invalid user name.";
    $userID     = NULL;
    $isAdmin    = FALSE;
    $features   = 0;

    $database = new Database ();

    if ($database)
    {
        $authKey  = getAuthKey ();
        $data     = $authKey ? json_decode (openssl_decrypt (base64_decode ($authKey), 'AES-128-ECB', getEncriptionKey ()), TRUE) : $_POST;
        $userInfo = $database->checkCredentials ($data ["un"], $data ["p"]);
        $userID   = array_key_exists ('id', $userInfo) ? $userInfo ['id'] : NULL;

        if ($userID == NULL)
        {
            $message = "Authorization failed";
        }
        else
        {
            $message    = "Authorization passed";
            $authorized = TRUE;
            $isAdmin    = $database->isAdmin ($userID);
            $features   = $database->getFeatures ($userID);
            $initialPos = $database->getInitialPos ($userID);
        }

        $database->close ();
    }

    if ($authorized)
    {
        $sessionMgr->setAuthenticationStatus (TRUE);
        $sessionMgr->setUserID ($userID);
        $sessionMgr->setUserFeatures ($features);
        $sessionMgr->setAccessTime ();

        if ($initialPos)
        {
            SessionManager::setVariable ('initLat', $initialPos ['lat']);
            SessionManager::setVariable ('initLon', $initialPos ['lon']);
        }
        else
        {
            SessionManager::setVariable ('initLat', NULL);
            SessionManager::setVariable ('initLon', NULL);
        }

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