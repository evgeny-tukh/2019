<?php
    require_once 'session/session_mgr.php';
    
    $sessionMgr = new SessionManager ();

    $curTime = time ();
    
    if (!$sessionMgr->isAuthenticated () || $sessionMgr->isSessionExpired ())
    {
        include ('login.html');
    }
    else
    {
        $sessionMgr->setAccessTime ();
        
        $features = $sessionMgr->getUserFeatures ();
        ?>

        <!DOCTYPE html>
        <!--
        To change this license header, choose License Headers in Project Properties.
        To change this template file, choose Tools | Templates
        and open the template in the editor.
        -->
        <html>
            <head>
                <meta charset="UTF-8">
                <title>Новый проект (Демо)</title>
                <link rel="stylesheet" href="common.css"/>
                <link rel="stylesheet" href="page.css"/>
            </head>
            <body>
                <div class="tabCtlContainer">
                    <a href="under_dev.php?b=fumon.php"><div class="tabBut">Отчеты по расходу</div></a>
                    <a href="under_dev.php?b=fumon.php"><div class="tabBut">Отчеты по бункеру</div></a>
                    <a href="under_dev.php?b=fumon.php"><div class="tabBut">Судовая топливная система</div></a>
                    <a href="main.php"><div class="tabBut rightTabBut">Выход</div></a>
                </div>
            </body>
        </html>
        <?php
    }
?>