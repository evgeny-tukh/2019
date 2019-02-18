<?php
    require_once 'session/session_mgr.php';
    require_once 'requests/redirect.php';
    
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
                <script src="page.js"></script>
                <script>
                    function initPage ()
                    {
                        document.getElementById ('shipLocation').onclick = function () { redirectTo ('<?php echo redirectTo ('jecat.ru/FmosDemo'); ?>'); };

                        commonPageInit ();
                    }
                </script>
            </head>
            <body onload="initPage ();">
                <div class="tabCtlContainer">
                    <!--<a href="http://jecat.ru/FmosDemo"><div class="tabBut">Дислокация судов</div></a>-->
                    <!--<a href=""><div class="tabBut">Дислокация судов!</div></a>-->
                    <div id="shipLocation" class="tabBut">Дислокация судов</div>
                    <a href="under_dev.php?b=fmon.php"><div class="tabBut">Операционная деятельность</div></a>
                    <a href="under_dev.php?b=fmon.php"><div class="tabBut">Навигационная безопасность</div></a>
                    <a href="main.php"><div class="tabBut rightTabBut">Выход</div></a></a>
                </div>
                <div class="docker">
                    <iframe id="docker" width="100%" height="100%" frameborder="no" marginheight="0" marginwidth="0" vspace="0" hspace="0"></iframe>
                </div>
                <img id="loading" src="res/loading.gif" style="position: absolute; display: none; left: 40%; top: 40%;"/>
            </body>
        </html>
        <?php
    }
?>