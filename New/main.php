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
                <link rel="stylesheet" href="main.css"/>
            </head>
            <body>
                <a href="fms.php?l=2019/fms">
                    <div class="wspShortcut flmon blink">Флот
                        <img class="wspIcon" src="res/tug32.png">
                        <div class="wsHint">Отсл.судов, опер.деят-ть, навиг.безопасность</div>
                    </div>
                </a>
                <a href="fumon.php">
                    <div class="wspShortcut fumon">Топливо
                        <img class="wspIcon" src="res/oil32.png">
                        <div class="wsHint">Отчеты по расходу, бункеровка, подр.информация</div>
                    </div>
                </a>
                <a href="maint.php">
                    <div class="wspShortcut maint">ТО/Ремонт
                        <img class="wspIcon" src="res/maint32.png">
                        <div class="wsHint">Информация по техобслуживанию и ремонту</div>
                    </div>
                </a>
                <a href="crew.php">
                    <div class="wspShortcut crew">Экипажи
                        <img class="wspIcon" src="res/crew32.png">
                        <div class="wsHint">....</div>
                    </div>
                </a>
                <a href="efficiency.php">
                    <div class="wspShortcut efficiency">Оценка эффективности
                        <img class="wspIcon" src="res/bigthumb32.png">
                        <div class="wsHint">....</div>
                    </div>
                </a>
                <a href="fms.php?l=2019/wl">
                    <div class="wspShortcut wposts">Водопосты
                        <img class="wspIcon" src="res/watermeter26.png">
                        <div class="wsHint">....</div>
                    </div>
                </a>
                <a href="safecnt.php">
                    <div class="wspShortcut safecnt">Безоп.осадка
                        <img class="wspIcon" src="res/echo32.png">
                        <div class="wsHint">....</div>
                    </div>
                </a>
                <a href="sno.php">
                    <div class="wspShortcut sno">СНО
                        <img class="wspIcon" src="res/engine32.png">
                        <div class="wsHint">....</div>
                    </div>
                </a>
                <a href="logout.php">
                    <div class="wspShortcut exit">Выход
                        <img class="wspIcon" src="res/exit26.png">
                        <div class="wsHint">Выход из учетной записи</div>
                    </div>
                </a>
            </body>
        </html>
        <?php
    }
?>