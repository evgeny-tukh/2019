<?php

    require_once 'session/session_mgr.php';
    require_once 'util/util.php';
    require_once 'util/auth.php';
    
    $sessionMgr = new SessionManager ();

    $curTime    = time ();
    $authKey    = getAuthKey ();
    $logoutLink = getLogoutKey ();

    if ($authKey && !$sessionMgr->isAuthenticated ())
        checkAuthorizeByKey ($sessionMgr, $authKey);
    
    if (!$sessionMgr->isAuthenticated () || $sessionMgr->isSessionExpired ())
    {
die('sdsds');
//        include ('login.html');
    }
    else
    {
        $sessionMgr->setAccessTime ();
        
        $features = $sessionMgr->getUserFeatures ();
                
        if ($authKey)
            header ("Location: main.php?k=$authKey");
        else
            header ('Location: main.php');
    }
?>
