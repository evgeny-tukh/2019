<?php

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
                    <title>Водомерные посты</title>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <link rel="stylesheet" href="main.css"/>
                    <link rel="stylesheet" href="cary/styles.css"/>
                    <link rel="stylesheet" href="cary/classic.css"/>
                    <link rel="stylesheet" href="cary/ui/generic/calendar.css"/>
                    <script>
                        var authKey = '<?php echo $authKey; ?>';
                        var logoutLink = '<?php echo $logoutLink; ?>';
                    </script>
                    <style type="text/css">
                        /* Chart.js */
                        @-webkit-keyframes chartjs-render-animation{from{opacity:0.99}to{opacity:1}}@keyframes chartjs-render-animation{from{opacity:0.99}to{opacity:1}}.chartjs-render-monitor{-webkit-animation:chartjs-render-animation 0.001s;animation:chartjs-render-animation 0.001s;}
                    </style>
                    <style>
                        body
                        {
                            overflow: hidden;
                        }
                    </style>
                    <script>
                        var authKey = '<?php echo $authKey; ?>';
                    </script>
                    <script src="https://maps.googleapis.com/maps/api/js?libraries=geometry&key=AIzaSyCsZWmFuiHNNNIh5GSgkz6bhJuWhbtk21g"></script>
                    <script src="chart.js/Chart.bundle.js"></script>
                    <script src="cary/cary.js"></script>
                    <script src="cary/tools.js"></script>
                    <script src="cary/service.js"></script>
                    <script src="cary/layout.js"></script>
                    <script src="cary/geo.js"></script>
                    <script src="cary/geo_util.js"></script>
                    <script src="cary/gm/maps.js"></script>
                    <script src="cary/gm/map_controls.js"></script>
                    <script src="cary/gm/mf_balloon.js"></script>
                    <script src="cary/gm/map_locker.js"></script>
                    <script src="cary/gm/pos_indicator.js"></script>
                    <script src="cary/gm/img_button.js"></script>
                    <script src="cary/gm/brg_rgn_tag.js"></script>
                    <script src="cary/gm/gm_panel.js"></script>
                    <script src="cary/gm/map_menu.js"></script>
                    <script src="cary/gm/drawers/gen_drawer.js"></script>
                    <script src="cary/gm/drawers/polyline_drawer.js"></script>
                    <script src="cary/gm/drawers/polygon_drawer.js"></script>
                    <script src="cary/gm/drawers/icon_drawer.js"></script>
                    <script src="cary/gm/drawers/circle_drawer.js"></script>
                    <script src="cary/gm/drawers/icon_grp_drawer.js"></script>
                    <script src="cary/usr_obj/gen_obj.js"></script>
                    <script src="cary/usr_obj/multi_pt_obj.js"></script>
                    <script src="cary/usr_obj/usr_pln.js"></script>
                    <script src="cary/usr_obj/usr_plg.js"></script>
                    <script src="cary/usr_obj/usr_icn.js"></script>
                    <script src="cary/usr_obj/usr_icn_grp.js"></script>
                    <script src="cary/usr_obj/usr_circle.js"></script>
                    <script src="cary/ui/generic/wnd.js"></script>
                    <script src="cary/ui/generic/gen_ctl.js"></script>
                    <script src="cary/ui/generic/buttons.js"></script>
                    <script src="cary/ui/generic/editbox.js"></script>
                    <script src="cary/ui/generic/slider.js"></script>
                    <script src="cary/ui/generic/treeview.js"></script>
                    <script src="cary/ui/generic/listview.js"></script>
                    <script src="cary/ui/generic/listbox.js"></script>
                    <script src="cary/ui/generic/browser.js"></script>
                    <script src="cary/ui/generic/browsebox.js"></script>
                    <script src="cary/ui/generic/checkbox.js"></script>
                    <script src="cary/ui/generic/details.js"></script>
                    <script src="cary/ui/generic/calendar.js"></script>
                    <script src="cary/ui/generic/datehourbox2.js"></script>
                    <script src="cary/ui/generic/flashing_img.js"></script>
                    <script src="cary/ui/generic/tabctrl.js"></script>
                    <script src="cary/ui/dlg/coord_edit.js"></script>
                    <script src="cary/ui/dlg/pos_edit.js"></script>
                    <script src="cary/ui/dlg/usr_pln_props.js"></script>
                    <script src="cary/ui/dlg/usr_plg_props.js"></script>
                    <script src="cary/ui/dlg/msg_box.js"></script>
                    <script src="cary/ui/dlg/browser_wnd.js"></script>
                    <script src="main.js "></script>
                    <script src="custom.js "></script>
                    <script src="custom_obj/custom_obj.js"></script>
                    <script src="custom_obj/depth_cnt.js"></script>
                    <script src="custom_obj/bridge.js"></script>
                    <script src="custom_obj/generic.js"></script>
                    <!-- <script src="custom_obj/berth.js"></script> -->
                    <script src="custom_obj/depth_cnt.js"></script>
                    <script src="custom_obj/drawers/alrtbl_cnt_drawer.js"></script>
                    <script src="custom_obj/drawers/dpt_cnt_drawer.js"></script>
                    <script src="custom_obj/drawers/bridge_cnt_drawer.js"></script>
                    <script src="custom_obj/drawers/wl_marker_drawer.js"></script>
                    <script src="custom_obj/drawers/nav_cnt_drawer.js"></script>
                    <script src="util/oe_util.js"></script>
                    <script src="util/wl_util.js"></script>
                    <script src="util/ser_util.js"></script>
                    <script src="util/watchdog.js"></script>
                    <script src="strings.js"></script>
                    <script src="ui/wl_pane.js?aaa=111"></script>
                    <script src="ui/graph_wnd.js"></script>
                </head>
                <body onload="init();">
                </body>
            </html>
        <?php
    }
?>