﻿<?php

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
                    <title>Система управления флотом</title>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <link rel="stylesheet" href="main.css"/>
                    <link rel="stylesheet" href="../cary/styles.css"/>
                    <link rel="stylesheet" href="../cary/classic.css"/>
                    <link rel="stylesheet" href="../cary/ui/generic/calendar.css"/>
                    <script>
                        var authKey = '<?php echo $authKey; ?>';
                        var logoutLink = '<?php echo $logoutLink; ?>';
                    </script>
                    <script src="chart.js/Chart.bundle.js"></script>
                    <script src="https://maps.googleapis.com/maps/api/js?libraries=geometry&key=AIzaSyAdUFwRzzsTOL9FGaQ-WCmSMwxp64fqjEA"></script>
                    <script src="lz-string/lz-string.js"></script>
                    <script src="main.js"></script>
                    <script src="custom.js"></script>
                    <script src="util/ser_util.js"></script>
                    <script src="util/watchdog.js"></script>
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
                    <script src="ui/side_pane.js"></script>
                    <script src="ui/fleet_pane.js"></script>
                    <script src="ui/ntg_area_pane.js"></script>
                    <script src="ui/data_pane.js"></script>
                    <script src="ui/track_settings.js"></script>
                    <script src="ui/graph_wnd.js"></script>
                    <script src="ui/vi_dialogs/tank_edit.js"></script>
                    <script src="ui/vi_dialogs/fuel_oper_edit.js"></script>
                    <script src="ui/vessel_info.js"></script>
                    <script src="ui/vi_panes/vi_pane.js"></script>
                    <script src="ui/vi_panes/tanks_pane.js"></script>
                    <script src="ui/vi_panes/fuel_pane.js"></script>
                    <script src="ui/vi_panes/oper_pane.js"></script>
                    <script src="strings.js"></script>
                    <script src="ais.js"></script>
                    <script src="fleet/fleet.js"></script>
                    <script src="fleet/track.js"></script>
                </head>
                <body onload="init ();">
                    <?php
                        function childPane ($id, $indent)
                        {
                            echo "$indent<div id=\"$id\" class=\"childPane\"></div>\n";
                        }
                        
                        function switchButton ($id, $title, $indent)
                        {
                            echo "$indent    <div id=\"$id\" class=\"switchBut\">$title</div>\n";
                        }
                        
                        $indent = str_repeat (' ', 20);
                        
                        if ($authKey)
                        {
                            echo "$indent<div class=\"pageSwitch\">\n";
                            switchButton ('mapBut', 'Дислокация', $indent);
                            switchButton ('activityBut', 'Опер.деятельность', $indent);
                            switchButton ('securityBut', 'Безопасность', $indent);
                            echo "$indent<img src=\"res/exclamation40.png\" style=\"margin: 0px; position: absolute; top: 3px; right: 10px; display: none;\" id=\"warnIcn\" />";
                            echo "$indent</div>\n";
                            childPane ('map', $indent);
                            childPane ('activity', $indent);
                            childPane ('security', $indent);
                            echo "$indent<div id=\"vesselPane\" class=\"vesselPane\">\n";
                        }
                        else
                        {
                            echo "$indent<div id=\"map\" class=\"map\">Map pane</div>\n";
                        }
                    ?>
                </body>
            </html>
        <?php
    }
?>