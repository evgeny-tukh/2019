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
                <style>
                    .ind
                    {
                        position: absolute;
                        right: 2px;
                        -webkit-animation: blinkingIcn 3s linear infinite;
                        animation: blinkingIcn 3s linear infinite;
                    }
                </style>
                <script src="https://maps.googleapis.com/maps/api/js?libraries=geometry&key=AIzaSyAdUFwRzzsTOL9FGaQ-WCmSMwxp64fqjEA"></script>
                <script src="../cary/cary.js"></script>
                <script src="../cary/tools.js"></script>
                <script src="../cary/service.js"></script>
                <script src="../cary/geo.js"></script>
                <script src="../cary/geo_util.js"></script>
                <script src="../cary/gm/maps.js"></script>
                <script src="../cary/gm/map_controls.js"></script>
                <script src="../cary/gm/mf_balloon.js"></script>
                <script src="../cary/gm/map_locker.js"></script>
                <script src="../cary/gm/pos_indicator.js"></script>
                <script src="../cary/gm/img_button.js"></script>
                <script src="../cary/gm/brg_rgn_tag.js"></script>
                <script src="../cary/gm/gm_panel.js"></script>
                <script src="../cary/gm/map_menu.js"></script>
                <script src="../cary/gm/drawers/gen_drawer.js"></script>
                <script src="../cary/gm/drawers/polyline_drawer.js"></script>
                <script src="../cary/gm/drawers/polygon_drawer.js"></script>
                <script src="../cary/gm/drawers/icon_drawer.js"></script>
                <script src="../cary/gm/drawers/circle_drawer.js"></script>
                <script src="../cary/gm/drawers/icon_grp_drawer.js"></script>
                <script src="../cary/usr_obj/gen_obj.js"></script>
                <script src="../cary/usr_obj/multi_pt_obj.js"></script>
                <script src="../cary/usr_obj/usr_pln.js"></script>
                <script src="../cary/usr_obj/usr_plg.js"></script>
                <script src="../cary/usr_obj/usr_icn.js"></script>
                <script src="../cary/usr_obj/usr_icn_grp.js"></script>
                <script src="../cary/usr_obj/usr_circle.js"></script>
                <script src="../cary/ui/generic/wnd.js"></script>
                <script src="../cary/ui/generic/gen_ctl.js"></script>
                <script src="../cary/ui/generic/buttons.js"></script>
                <script src="../cary/ui/generic/editbox.js"></script>
                <script src="../cary/ui/generic/slider.js"></script>
                <script src="../cary/ui/generic/treeview.js"></script>
                <script src="../cary/ui/generic/listview.js?a=b"></script>
                <script src="../cary/ui/generic/listbox.js"></script>
                <script src="../cary/ui/generic/browser.js"></script>
                <script src="../cary/ui/generic/browsebox.js"></script>
                <script src="../cary/ui/generic/checkbox.js"></script>
                <script src="../cary/ui/generic/details.js"></script>
                <script src="../cary/ui/generic/calendar.js"></script>
                <script src="../cary/ui/generic/datehourbox2.js"></script>
                <script src="../cary/ui/generic/flashing_img.js"></script>
                <script src="../cary/ui/generic/tabctrl.js"></script>
                <script src="../cary/ui/dlg/coord_edit.js"></script>
                <script src="../cary/ui/dlg/pos_edit.js"></script>
                <script src="../cary/ui/dlg/usr_pln_props.js"></script>
                <script src="../cary/ui/dlg/usr_plg_props.js"></script>
                <script src="../cary/ui/dlg/msg_box.js"></script>
                <script src="../cary/ui/dlg/browser_wnd.js"></script>
                <script src="util/ser_util.js"></script>
                <script src="fleet/fleet.js"></script>
                <script src="fleet/track.js"></script>
                <script src="main.js">
                </script>
            </head>
            <body onload="init ();">
                <div class="coverUpr">
                </div>
                <div class="coverLwr">
                </div>
                <a href="fms.php?l=2019/fms">
                    <div class="wspShortcut flmon blink">Флот
                        <img src="res/exclamation30.png" class="ind" id="fms_ind"/>
                        <img class="wspIcon" src="res/tug32.png">
                        <div class="wsHint">Отсл.судов, опер.деят-ть, навиг.безопасность</div>
                    </div>
                </a>
                <a href="fms.php?l=2019/fuel">
                    <div class="wspShortcut fumon">Топливо
                        <img src="res/exclamation30.png" class="ind" id="fuel_ind"/>
                        <img class="wspIcon" src="res/oil32.png">
                        <div class="wsHint">Отчеты по расходу, бункеровка, подр.информация</div>
                    </div>
                </a>
                <a href="maint.php">
                    <div class="wspShortcut maint">ТО/Ремонт
                        <img src="res/exclamation30.png" class="ind" id="maint_ind"/>
                        <img class="wspIcon" src="res/maint32.png">
                        <div class="wsHint">Информация по техобслуживанию и ремонту</div>
                    </div>
                </a>
                <a href="crew.php">
                    <div class="wspShortcut crew">Экипажи
                        <img src="res/exclamation30.png" class="ind" id="crew_ind"/>
                        <img class="wspIcon" src="res/crew32.png">
                        <div class="wsHint">....</div>
                    </div>
                </a>
                <a href="efficiency.php">
                    <div class="wspShortcut efficiency">Оценка эффективности
                        <img src="res/exclamation30.png" class="ind" id="eff_ind"/>
                        <img class="wspIcon" src="res/bigthumb32.png">
                        <div class="wsHint">....</div>
                    </div>
                </a>
                <a href="fms.php?l=2019/wl">
                    <div class="wspShortcut wposts">Водопосты
                        <img src="res/exclamation30.png" class="ind" id="wat_ind"/>
                        <img class="wspIcon" src="res/watermeter26.png">
                        <div class="wsHint">....</div>
                    </div>
                </a>
                <a href="fms.php?l=2019/port">
                    <div class="wspShortcut safecnt">Безоп.осадка
                        <img src="res/exclamation30.png" class="ind" id="dpt_ind"/>
                        <img class="wspIcon" src="res/echo32.png">
                        <div class="wsHint">....</div>
                    </div>
                </a>
                <a href="fms.php?l=https://gis-mt.ru/site/login">
                    <div class="wspShortcut sno">СНО
                        <img src="res/exclamation30.png" class="ind" id="sno_ind"/>
                        <img class="wspIcon" src="res/engine32.png">
                        <div class="wsHint">....</div>
                    </div>
                </a>
                <a href="fms.php?l=2019/icemon">
                    <div class="wspShortcut ice">Ледовая обстановка
                        <img src="res/exclamation30.png" class="ind" id="ice_ind"/>
                        <img class="wspIcon" src="res/snowflake32.png">
                        <div class="wsHint">Оптические и радарные ледовые снимки</div>
                    </div>
                </a>
            </body>
        </html>
        <?php
    }
?>