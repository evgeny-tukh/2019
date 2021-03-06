userObj.createVirginUserObject = function (userType)
{
    var obj;

    switch (userType)
    {
        case userObj.types.DEPTH_CONTOUR:
           obj = new userObj.DepthContour; break;

        case userObj.types.NAV_CONTOUR:
           obj = new userObj.NavContour; break;

        case userObj.types.BRIDGE_CONTOUR:
           obj = new userObj.BridgeContour; break;

        case userObj.types.WATERLEVEL_CONTOUR:
           obj = new userObj.WaterLevelContour; break;

        case userObj.types.WL_MARKER:
           obj = new userObj.WaterLevelMarker; break;

        default:
            obj = null;
    }

    return obj;
};

userObj.addObjectToCollectionRaw = function (collection, text)
{    
    collection.addFromJSON (text, userObj.createVirginUserObject);
};


userObj.AlertableObjectCollection = function (map)
{
    this.map = map;
    
    Cary.userObjects.ObjectCollection.apply (this);
};

userObj.AlertableObjectCollection.prototype = Object.create (Cary.userObjects.ObjectCollection.prototype);

userObj.AlertableObjectCollection.prototype.drawAllAlerted = function (objectCallbacks)
{
    var mapObj = Cary.checkMap (this.map);
    
    this.enumerate (function (object)
                    {
                        object.alert (mapObj, objectCallbacks);
                        /*if (!('drawer' in object))
                            object.drawer = object.createDrawer ();
                        else
                            object.drawer.undraw ();
                        
                        object.drawer.drawAlerted (mapObj, objectCallbacks);*/
                    });
};
