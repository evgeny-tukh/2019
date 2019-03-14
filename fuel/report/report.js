var monthCtl, yearCtl;
var changed = false;
var edit = null;
var monthNames2 = ['января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'];

function onChangePeriod ()
{
    if (changed)
    {
        if (!confirm ('Несохраненные данные пропадут. Продолжить?'))
        {
            monthCtl.selectedIndex = monthCtl.curSel;
            yearCtl.selectedIndex  = yearCtl.curSel;

            return;
        }
    }

    month = monthCtl.selectedIndex + 1;
    year  = parseInt (yearCtl.value);

    adjustMonthYearFields ();

alert ('month '+month+' of '+year);
}

function onSelectMonthYear ()
{
    monthCtl.curSel = monthCtl.selectedIndex;
    yearCtl.curSel  = yearCtl.selectedIndex;
}

function adjustMonthYearFields ()
{
    document.getElementById ('year2').innerText     = year.toString () + ' г.';
    document.getElementById ('profMonth').innerText = monthNames2 [month-1];
}

function init ()
{
    var fields = document.getElementsByClassName ('field');

    document.getElementById ('save').onclick = function ()
                                               {
                                                   changed = false;
                                               };

    monthCtl = document.getElementById ('month');
    yearCtl  = document.getElementById ('year');

    monthCtl.onchange = onChangePeriod;
    yearCtl.onchange  = onChangePeriod;
    monthCtl.onfocus  = onSelectMonthYear;
    yearCtl.onfocus   = onSelectMonthYear;

    adjustMonthYearFields ();

    for (var i = 0; i < fields.length; ++ i)
    {
        fields [i].onclick = function (event) 
                             {
                                 if (edit)
                                 {
                                     var value  = edit.savedValue;
                                     var parent = edit.parentElement;

                                     if (parent === this)
                                         return;

                                     parent.removeChild (edit);

                                     edit = null;

                                     parent.innerText = value;
                                 }

                                 edit = document.createElement ('input');

                                 edit.id                    = 'fldEdit';
                                 edit.parentElement         = event.target;
                                 edit.type                  = 'text';
                                 edit.className             = 'f14';
                                 edit.style.top             = '0px';
                                 edit.style.left            = '0px';
                                 edit.style.width           = '100%';
                                 edit.style.height          = '100%';
                                 edit.style.textAlign       = 'center';
                                 edit.style.borderStyle     = 'single';
                                 edit.style.borderWidth     = '1px';
                                 edit.style.borderColor     = 'black';
                                 edit.style.margin          = '0px';
                                 //edit.style.paddingLeft     = '2px';
                                 //edit.style.paddingRight    = '2px';
                                 edit.style.backgroundColor = 'yellow';
                                 edit.value                 = event.target.innerText;
                                 edit.savedValue            = event.target.innerText;
                                 edit.onkeydown             = function (event)
                                                              {
                                                                  var isEnter = event.key === 'Enter',
                                                                      isEsc   = event.key === 'Escape';

                                                                  if (isEnter || isEsc)
                                                                  {
                                                                      var value  = isEnter ? this.value : this.savedValue;
                                                                      var parent = edit.parentElement;

                                                                      parent.removeChild (edit);

                                                                      edit = null;

                                                                      parent.innerText = value;

                                                                      if (isEnter)
                                                                          changed = true;
                                                                  }
                                                              };

                                 event.target.innerText = null;

                                 event.target.appendChild (edit);

                                 edit.focus ();
                             };
    }
}
