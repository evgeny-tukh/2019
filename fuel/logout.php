<?php
    require_once 'session/session_mgr.php';
    require_once 'util/auth.php';
    
    $manager = new SessionManager ();
    
    $manager->setAuthenticationStatus (FALSE);

    header ("Location: index.php");
?>