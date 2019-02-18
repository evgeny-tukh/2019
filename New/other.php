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
                        document.getElementById ('waterposts').onclick = function () { redirectTo ('<?php echo redirectTo ('jecat.ru/WaterLevel'); ?>'); };

                        commonPageInit ();
                    }
                </script>
            </head>
            <body onload="initPage ();">
                <div class="tabCtlContainer">
                    <a href="under_dev.php?b=other.php"><div class="tabBut">Мониторинг СНО</div></a>
                    <div class="tabBut" id="waterposts">Вод.посты</div>
                    <a href="under_dev.php?b=other.php"><div class="tabBut">Контроль за безопасной осадкой</div></a>
                    <a href="main.php"><div class="tabBut rightTabBut">Выход</div></a>
                </div>
                <div class="docker">
                    <iframe id="docker" width="100%" height="100%" frameborder="no" marginheight="0" marginwidth="0" vspace="0" hspace="0"></iframe>
                </div>
                <img id="loading" src="res/loading.gif" style="position: absolute; display: none; left: 40%; top: 40%;"/>
          </body>
        </html>
        <?php
    }
?>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            