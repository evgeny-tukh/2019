var map;
var posInd;
var globals         = {};
var waterLevelAreas = null;
var settings        = { enableAreaActivation: false };

function init ()
{
    var settingsButton;
    var waterLevelButton;
    var zoomInButton;
    var zoomOutButton;
    var logoutButton;
    var settingsPane;
    var mapDiv;
    var waterLevelPane = null;
    var activeSubMenu  = null;
    
    globals.layourMgr = new Cary.LayoutManager ([{ type: Cary.paneTypes.FULL_SCREEN, id: 'map', right: 350, hideable: false, moreStyles: { backgroundColor: 'yellow'} },
                                                 { type: Cary.paneTypes.RIGHT_ANCHORED, id: 'wl', width: 350, hideable: true, moreStyles: { backgroundColor: 'blue'} }]);
                                             
    mapDiv = globals.layourMgr.getDiv ('map');

    stringTable = new strings.StringTable ('russian.st', 2000);
    
    map = new Cary.Map ();

    initSettings ();
    initLayers (map);
    //initAbris (map);

    Cary.settings.activeItemClass   = 'activeItem';
    Cary.settings.selectedItemClass = 'selectedItem';
    
    Cary.tools.createCssClass (Cary.settings.activeItemClass, { color: 'black' });
    Cary.tools.createCssClass (Cary.settings.activeItemClass + ':hover', { color: 'blue' });
    Cary.tools.createCssClass (Cary.settings.selectedItemClass, { color: 'red', 'font-weight': 'bold' });
    
    map.attach (mapDiv);
    map.createMap ();
    map.setupPredefinedBaseMaps ();

    map.addEventListener ('mousemove', onMouseMove);
    map.addEventListener ('mouseover', function ()
                                       {
                                           posInd.show (true);
                                       });
    map.addEventListener ('mouseout', function () { posInd.show (false); });
    
    settingsButton   = map.createImgButton (google.maps.ControlPosition.TOP_LEFT, 'res/settings26.png', { onClick: showSettingsPane });
    zoomInButton     = map.createImgButton (google.maps.ControlPosition.LEFT_BOTTOM, 'res/zoom-in-20.png', { onClick: function () { map.zoomIn (); } });
    zoomOutButton    = map.createImgButton (google.maps.ControlPosition.LEFT_BOTTOM, 'res/zoom-out-20.png', { onClick: function () { map.zoomOut (); } });
    logoutButton     = map.createImgButton (google.maps.ControlPosition.LEFT_CENTER, 'res/exit26.png', { onClick: logout });
    settingsPane     = map.createGMPanel (google.maps.ControlPosition.TOP_LEFT, { onInit: onInitSettingsPane });
    waterLevelButton = map.createImgButton (google.maps.ControlPosition.LEFT_CENTER, 'res/watermeter26.png', { onClick: showWaterLevelPane });

    posInd = map.createPosIndicator (google.maps.ControlPosition.TOP_CENTER);
    
    posInd.setText ('hehehe');
    posInd.setValue (10, 20);
    
    settingsPane.setSlidingMode (Cary.controls.GMPanel.slidingMode.LEFT);
    settingsButton.show ();
    zoomInButton.show ();
    zoomOutButton.show ();

    if (!authKey)
        logoutButton.show ();

    waterLevelButton.show (false);
    posInd.show ();
    showWaterLevelPane ();
    
    function logout ()
    {
        Cary.tools.sendRequest ({ url: 'logout.php', method: Cary.tools.methods.get, content: Cary.tools.contentTypes.plainText });

        if (authKey)
        {
            if (logoutLink)
                window.location = logoutLink;
            else
                window.history.back ();
        }
        else
        {
            window.location = 'login.html';
        }
    }
    
    function onMouseMove (event)
    {
        posInd.onMouseEvent (event);
    }

    function showWaterLevelPane ()
    {
        if (waterLevelPane === null)
            waterLevelPane = new WaterLevelPane (globals.layourMgr.getDiv ('wl'),
                                                 {
                                                     onClose: function ()
                                                              {
                                                                  globals.layourMgr.showLayout ('wl', false);
                                                                  waterLevelButton.show (true);
                                                                  /*waterLevelPane = null;*/
                                                              } 
                                                 });
        
        globals.layourMgr.showLayout ('wl', true);
        waterLevelButton.show (false);
    }
    
    function showSettingsPane ()
    {
        map.lock (closeAllMenus);
        
        settingsButton.show (false);
        settingsPane.slideIn ();
        
        function closeAllMenus ()
        {
            if (activeSubMenu !== null)
                activeSubMenu.show (false);
            
            settingsPane.unlock ();
            settingsPane.slideOut ();
            
            map.unlock ();
            settingsButton.show (true);
        }
    }
        
    function onInitSettingsPane (panel)
    {
        var baseMapMenu;
        //var overlaysMenu;
        
        baseMapMenu  = map.createGMPanel (google.maps.ControlPosition.TOP_LEFT, { onInit: onInitBaseMapMenu, height: 'fit-content', onOpen: setActiveSubMenu, onClose: resetActiveSubMenu });
        //overlaysMenu = map.createGMPanel (google.maps.ControlPosition.TOP_LEFT, { onInit: onInitOverlaysMenu, height: 'fit-content', onOpen: setActiveSubMenu, onClose: resetActiveSubMenu });
        
        baseMapMenu.container.style.marginLeft  = '330px';//'280px';
        //overlaysMenu.container.style.marginLeft = '330px';// '280px';
        
        map.addEventListener ('zoom_changed', 
                              function ()
                              {
                                  // Some magic here
                                  baseMapMenu.container.style.marginLeft  = '280px';
                                  //overlaysMenu.container.style.marginLeft = '280px';
                              });
        
        map.addEventListener ('maptypeid_changed', 
                              function ()
                              {
                                  // Some magic here
                                  baseMapMenu.container.style.marginLeft  = '330px';
                                  //overlaysMenu.container.style.marginLeft = '330px';
                                  
                                  map.addEventListener ('tilesloaded', function () { userObj.realertAreas (); });
                              });
        
        panel.addTitle (stringTable.settings, null, function () { settingsButton.show (true); map.unlock (); });
        panel.addSubMenu ({ text: stringTable.baseMap, className: 'settingsPaneSubMenu', onClick: function () { baseMapMenu.show (); } });
        //panel.addSubMenu ({ text: stringTable.overlays, className: 'settingsPaneSubMenu', onClick: function () { overlaysMenu.show (); } });
        
        function setActiveSubMenu (subMenu)
        {
            activeSubMenu = subMenu;
        }
        
        function resetActiveSubMenu (subMenu)
        {
            activeSubMenu = null;
        }
        
        function onInitBaseMapMenu (menu)
        {
            var items = [];
            
            menu.addTitle (stringTable.baseMap, null, function () { panel.unlock (); });
            
            addItem ('Roadmap', Cary.maps.baseMaps.RoadMap);
            addItem ('Terrain', Cary.maps.baseMaps.Terrain);
            addItem ('Satellite', Cary.maps.baseMaps.Satellite);
            addItem ('Hybrid', Cary.maps.baseMaps.Hybrid);
            //addItem ('Navionics', Cary.maps.baseMaps.Navionics);
            //addItem ('Abris', Cary.maps.baseMaps.CustomMap);
            addItem ('OpenStreet', Cary.maps.baseMaps.OpenStreet);
            //addItem ('Sentinel-2', Cary.maps.baseMaps.Sentinel2);
            //addItem ('Landsat 8', Cary.maps.baseMaps.Landsat8);
            //addItem ('ScanEx (demo)', Cary.maps.baseMaps.ScanEx);
            
            function addItem (itemName, mapFlag)
            {
                items.push (menu.addItem (itemName, {}, function (item) { selectBaseMap (item); }, { checked: itemName === 'Roadmap', data: map.getBaseMapIndex (mapFlag) }));
            }
            
            function selectBaseMap (activeItem)
            {
                baseMapMenu.show (false);
                //hideSettingsPane ();
                panel.unlock ();
                
                items.forEach (function (item)
                               {
                                   menu.checkItem (item, item === activeItem);
                               });
                
                selectMapType (activeItem.data);
            }
        }
        
        function onInitOverlaysMenu (menu)
        {
            var items = [];
            
            menu.addTitle (stringTable.overlays, null, function () { panel.unlock (); });
            
            addItem ('OpenSea', Cary.maps.overlayMaps.Layers.OpenSea);
            addItem ('OpenWeather (Temperature)', Cary.maps.overlayMaps.Layers.OpenWeatherTemp);
            addItem ('OpenWeather (Precipitation)', Cary.maps.overlayMaps.Layers.OpenWeatherPrecipitation);
            addItem ('OpenWeather (Wind)', Cary.maps.overlayMaps.Layers.OpenWeatherWind);
            addItem ('OpenWeather (Pressure)', Cary.maps.overlayMaps.Layers.OpenWeatherPressure);
            addItem ('OpenWeather (Clouds)', Cary.maps.overlayMaps.Layers.OpenWeatherClouds);
            addItem ('ScanEx/Sentinel', Cary.maps.overlayMaps.Layers.ScanExSentinel);
            addItemCB (stringTable.aisTargets, toggleAISTargets);
            addItem (stringTable.aisTargetsMT, Cary.maps.overlayMaps.Layers.AISTargetsMT);
            
            function toggleAISTargets ()
            {
                if (aisTargetTable.started ())
                    aisTargetTable.stop ();
                else
                    aisTargetTable.start (true);
            }
            
            function addItemCB (itemName, callback)
            {
                items.push (menu.addItem (itemName, { textWidth: '240px', backgroundColor: 'yellow' },
                            function (item)
                            {
                                menu.checkItem (item, !menu.isItemChecked (item));
                                
                                if (callback)
                                    callback ();
                            }, 
                            { checked: false, data: null, textWidth: 240 }));
            }
            
            function addItem (itemName, mapFlag)
            {
                items.push (menu.addItem (itemName, { textWidth: '240px', backgroundColor: 'yellow' },
                            function (item)
                            {
                                var show = !menu.isItemChecked (item);
                                
                                map.showOverlayLayer (map.getOverlayIndex (item.data), show);
                                
                                menu.checkItem (item, show);
                            }, 
                            { checked: false, data: mapFlag, textWidth: 240 }));
            }
        }
    }    
}

function addBaseMap (baseMap)
{
    var select = document.getElementById ('baseMap');
    var option = document.createElement ('option');

    option.text = baseMap.getName ();

    select.add (option);
}

function selectMapType (index)
{
    map.selectBaseMap (index);
}

function showOverlay (index, show)
{
    map.showOverlayLayer (index, show);
}

function initSettings ()
{
    Cary.tools.sendRequest ({ mathod: Cary.tools.methods.get, content: Cary.tools.contentTypes.plainText, resType: Cary.tools.resTypes.json, url: 'requests/set_load.php', onLoad: onLoad });
    
    function onLoad (data)
    {
        data.forEach (function (dataItem)
                      {
                          settings [dataItem.name] = dataItem.value;
                      });

        ['depthAreas', 'waterLevels'].forEach (function (key)
                                               {
                                                    if (key in settings)
                                                        settings [key] = parseInt (settings [key]);
                                                    else
                                                        settings [key] = null;
                                               });
    }
}

function setSetting (name, value)
{
    settings [name] = value;
    
    Cary.tools.sendRequest ({ mathod: Cary.tools.methods.put, content: Cary.tools.contentTypes.json, resType: Cary.tools.resTypes.plain, url: 'requests/set_set.php',
                              param: { name: name, value: value } });
}
