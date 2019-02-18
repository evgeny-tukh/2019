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
        $back = array_key_exists ('b', $_REQUEST) ? $_REQUEST ['b'] : 'main.php';
        
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
                <style>
                    .bigLabel
                    {
                        position:       absolute;
                        width:          100%;
                        font-size:      40pt;
                        top:            40%;
                        text-align:     center;
                        font-weight:    bold;
                        color:          black;
                    }
                    
                    img
                    {
                        position:       absolute;
                        left:           10px;
                        top:            -40pt;
                    }
                </style>
            </head>
            <body>
                <div class="tabCtlContainer">
                    <a href="<?php echo $back;?>"<div class="tabBut rightTabBut">Выход</div></a>
                </div>
                
                <div class="bigLabel">Страница в разработке
                    <img src="res/developer.png">
                </div>
            </body>
        </html>
        <?php
    }
?>