<?php
    require_once 'session/session_mgr.php';
    require_once 'requests/redirect.php';
    
    $sessionMgr = new SessionManager ();

    $curTime    = time ();
    $link       = $_REQUEST ['l'];
    $bottomPane = array_key_exists ('b', $_REQUEST) ? intval ($_REQUEST ['b']) : 1;
    $dockerCls  = $bottomPane ? 'docker2' : 'docker3';
    $absPath    = strtolower (substr ($link, 0, 4)) === 'http';
    $site       = 'jecat.ru';
    
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
                <script src="page.js"></script>
                <style>
                    body
                    {
                        background-color: cyan;
                        padding:          0px;
                    }
                </style>

                <script>
                    function initPage ()
                    {
                        //document.getElementById ('shipLocation').onclick = function () { redirectTo ('<?php /*echo redirectTo ('jecat.ru/FmosDemo');*/ ?>'); };

                        commonPageInit ();
                        redirectTo ('<?php echo $absPath ? $link : redirectTo ("$site/$link", "$site/new"); ?>');
                    }
                </script>
            </head>
            <body onload="initPage ();">
                <div class="<?php echo $dockerCls; ?>">
                    <iframe id="docker" width="100%" height="100%" frameborder="no" marginheight="0" marginwidth="0" vspace="0" hspace="0"></iframe>
                </div>
                <?php if ($bottomPane) { ?>
                <div class="bottomButArea">
                    <a href="under_dev.php?b=fms.php">
                        <div class="bottomBut">
                            <img class="bottomButIcon" src="res/oil32.png"/>Топливо
                        </div>
                    </a>                    
                    <a href="under_dev.php?b=fms.php">
                        <div class="bottomBut">
                            <img class="bottomButIcon" src="res/maint32.png"/>Ремонт
                        </div>
                    </a>                    
                    <a href="under_dev.php?b=fms.php">
                        <div class="bottomBut">
                            <img class="bottomButIcon" src="res/crew32.png"/>Экипажи
                        </div>
                    </a>                    
                    <a href="under_dev.php?b=fms.php">
                        <div class="bottomBut">
                            <img class="bottomButIcon" src="res/bigthumb32.png"/>Эффективность
                        </div>
                    </a>                    
                    <a href="main.php">
                        <div class="bottomBut rightBox">
                            <img class="bottomButIcon" src="res/exit26.png" style="top: 3pt;"/>Выход
                        </div>
                    </a>                    
                </div>
                <?php } ?>
                <img id="loading" src="res/loader.gif" style="position: absolute; display: none; left: 40%; top: 40%;"/>
            </body>
        </html>
        <?php
    }
?>