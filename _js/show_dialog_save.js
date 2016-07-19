/* BACK 
   устаревшая проверка изменений при закрытии окна
   используются только в модуле structure
*/
MSG_WIN_CLOSE = 'ВЫ НЕ СОХРАНИЛИ ИЗМЕНЕНИЯ. Действительно хотите уйти отсюда?';
change = false; submited = false;

flag_parent = parent && parent.IS_CHECK_LANG;

onbeforeunload = ( flag_parent ? null : beforeunload_);

change_ = new Function("change = true;");
function changeAll() {
    var d = document;
    var list = d.getElementsByTagName("input");
    for (var i = 0; i < list.length; i++)
        if ( 'file' != list[i].type ) list[i].onchange = change_;
    var list = d.getElementsByTagName("textarea");
    for (var i = 0; i < list.length; i++)
        list[i].onchange = change_;
    var list = d.getElementsByTagName("select");
    for (var i = 0; i < list.length; i++)
        list[i].onchange = change_;

    var list = d.getElementsByTagName("iframe");
      for (var i = 0; i < list.length; i++)
       {   doc = list[i].contentWindow.document;
           //doc.getElementById('xSourceField').onchange = change_;
           doc.getElementById('xEditingArea').onfocus = change_;
       }

}

onload = changeAll;

function beforeunload_()
 {  document.getElementsByTagName('input')[0].blur();
    if ( change && !submited )
          return MSG_WIN_CLOSE;

 }

function close_()
 {
    ( flag_parent ? parent.close() : close() );

 }

function go_href(href_)
 {
    ( flag_parent ? (parent.location.href = href_) : (location.href = href_) );

 }


function submit_(sender)
 {
    if (flag_parent)
     { parent.submited = true;
       parent.submitAll();
     }
    else
     { submited = true;
       sender.form.submit();
     }

 }
