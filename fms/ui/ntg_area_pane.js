function NotToGoAreaPane (callbacks, options)
{
    var parent, height;
    
    if ('parent' in options)
    {
        parent = options.parent;
        height = parent.clientHeight - 20;
    }
    else
    {
        parent = document.getElementsByTagName ('body') [0];
        height = window.innerHeight - 20;
    }
    
    this.paneHeight = height;
    
    if (Cary.tools.isNothing (callbacks))
        callbacks = {};
    
    if (Cary.tools.isNothing (options))
        options = {};
    
    this.options   = Cary.tools.isNothing (options) ? {} : options;
    this.callbacks = Cary.tools.isNothing (callbacks) ? {} : callbacks;
    
    Cary.ui.Window.apply (this, [{ position: { top: 0, right: 0, width: '400px', height: Cary.tools.int2pix (height), absolute: true }, 
                                 title: stringTable.ntgAreas, parent: parent }]);
}

NotToGoAreaPane.prototype = Object.create (Cary.ui.Window.prototype);

NotToGoAreaPane.prototype.onInitialize = function ()
{
    var columns    = [{ title: stringTable.name, width: 330 }, { title: stringTable.activeShort, width: 50, onItemClick: onSwitchActive }];
    var listHeight = this.embedMode ? this.paneHeight - 70 : 270;
    var areaList   = new Cary.ui.ListView ({ parent: this.client, columns: columns, visible: true, onItemClick: onSelectArea },
                                            { position: 'absolute', top: 10, left: 5, width: 400, height: listHeight });

    loadAllAreas ();
    
    function loadAllAreas ()
    {
        notToGoAreas.objects.forEach (function (area)
                              {
                                  areaList.addItem ([area.name, area.properties.enabled ? Cary.symbols.checked : Cary.symbols.unchecked], area);
                              });
    }
    
    function onSwitchActive ()
    {
        var selection = areaList.getSelectedItem ();
        
        if (selection >= 0)
        {
            var active = areaList.getItemText (selection, 1) === Cary.symbols.checked;
            var area   = areaList.getItemData (selection);

            active = !active;

            areaList.setItemText (selection, 1, active ? Cary.symbols.checked : Cary.symbols.unchecked);

            if (active)
            {
                if (!('drawer' in area))
                    area.drawer = area.createDrawer ();
                
                area.drawer.draw (map.map);
            }
            else
            {
                area.drawer.undraw ();
            }
            
            area.properties.enabled = active;
            
            var url = 'http://localhost:8080/2019/fms/requests/ntga_enable.php?a=' + area.id.toString () + '&e=' + (active ? '1' : '0');
            
            Cary.tools.sendRequest ({ method: Cary.tools.methods.get, content: Cary.tools.contentTypes.plainText, resType: Cary.tools.resTypes.plain, url: url });
        }
    }

    function addAreaItem (area)
    {
        areaList.addItem ([area.name, area.active ? Cary.symbols.checked : Cary.symbols.unchecked], area);
    }
    
    function onSelectArea (row, column, item)
    {
        var vessel = areaList.getItemData (row);
        
        //map.setCenter (vessel.lat, vessel.lon);
        //
        //onSwitchVesselTrack (row, column, item);
    }
};

NotToGoAreaPane.prototype.queryClose = function ()
{
    this.hide ();
    
    if ('onClose' in this.callbacks)
        this.callbacks.onClose ();
    
    return false;
};

