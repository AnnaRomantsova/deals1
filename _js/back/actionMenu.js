/* BACK
   открытие - закрытие меню
*/
var tt=0;
var timer;
function actionMenu_apearance()
 { tt=tt+10;
   document.getElementById('actionMenu').style.filter='alpha(opacity='+tt+')';
   if (tt<100)
      setTimeout(actionMenu_apearance,30);
   else tt=0;
 }
function actionMenu()
 { if (timer) clearTimeout(timer);
   if (document.getElementById('actionMenu').style.display=='none')
    { document.getElementById('actionMenu').style.filter='alpha(opacity=0)';
      document.getElementById('actionMenu').style.display='block';
      setTimeout(actionMenu_apearance,10); }
 }
function closetimer()
 { document.getElementById('actionMenu').style.display='none';
 }
function actionMenu_close()
 { timer=setTimeout(closetimer,100);
 }
