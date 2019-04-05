<?php
    require_once 'session/session_mgr.php';
    require_once 'requests/redirect.php';
    require_once 'sitecfg.php';
    require_once 'guicfg.php';

    $sessionMgr = new SessionManager ();

    $curTime      = time ();
    $link         = $_REQUEST ['l'];
    $bottomPane   = array_key_exists ('b', $_REQUEST) ? intval ($_REQUEST ['b']) : 1;
    $guiCfgName   = array_key_exists ('c', $_REQUEST) ? $_REQUEST ['c'] : 'fms';
    $dockerCls    = $bottomPane ? 'docker2' : 'docker3';
    $absPath      = strtolower (substr ($link, 0, 4)) === 'http';
    $links        = [];
    $linkNames    = [];
    $linksActive  = [];
    $linkIcons    = [];
    $defLinks     = ['fms.php?l=2019/fuel', 'under_dev.php', 'under_dev.php', 'under_dev.php'];
    $defLnkNames  = ['Топливо', 'Ремонт', 'Экипажи', 'Эффективность'];
    $defLnkIcons  = ['oil32', 'maint32', 'crew32', 'bigthumb32'];
    $defLnkActive = [1, 1, 1, 1];

    for ($i = 1; $i < 5; ++ $i)
    {
        $index = $i - 1;

        $buttonCfg = $guiCfg [$guiCfgName]['buttons'][$index];

        $links [$index]       = $buttonCfg ['link']; //array_key_exists ("l$i", $_REQUEST) ? $_REQUEST ["l$i"] : $defLinks [$index];
        $linkNames [$index]   = $buttonCfg ['name']; //array_key_exists ("ln$i", $_REQUEST) ? $_REQUEST ["ln$i"] : $defLnkNames [$index];
        $linksActive [$index] = $buttonCfg ['active']; //array_key_exists ("la$i", $_REQUEST) ? intval ($_REQUEST ["la$i"]) : $defLnkActive [$index];
        $linkIcons [$index]   = $buttonCfg ['icon']; //array_key_exists ("li$i", $_REQUEST) ? $_REQUEST ["li$i"] : $defLnkIcons [$index];

        if ($links [$index][0] == '.')
            $links [$index] = 'fms.php?l=2019/'.substr ($links [$index], 1) ;
    }
    /*$link2      = array_key_exists ('l2', $_REQUEST) ? intval ($_REQUEST ['l2']) : "under_dev.php";
    $link3      = array_key_exists ('l3', $_REQUEST) ? intval ($_REQUEST ['l3']) : "under_dev.php";
    $link4      = array_key_exists ('l4', $_REQUEST) ? intval ($_REQUEST ['l4']) : "under_dev.php";
    $linkName1  = array_key_exists ('ln1', $_REQUEST) ? intval ($_REQUEST ['ln1']) : 'Топливо';
    $linkName2  = array_key_exists ('ln2', $_REQUEST) ? intval ($_REQUEST ['ln2']) : 'Ремонт';
    $linkName3  = array_key_exists ('ln3', $_REQUEST) ? intval ($_REQUEST ['ln3']) : 'Экипажи';
    $linkName4  = array_key_exists ('ln4', $_REQUEST) ? intval ($_REQUEST ['ln4']) : 'Эффективность';*/


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
                    <?php for ($j = 0; $j < 4; ++ $j) { ?>

                    <!--<a href="<?php echo $links [$j]; ?>?b=fms.php">-->
                    <a href="<?php echo $links [$j]; ?>">
                        <div class="<?php echo $linksActive [$j] ? 'bottomBut' : 'bottomButDisabled'; ?>">
                            <img class="bottomButIcon" src="res/<?php echo $linkIcons [$j].'.png'; ?>"/><?php echo $linkNames [$j]; ?>
                        </div>
                    </a>
                    <?php } ?>
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