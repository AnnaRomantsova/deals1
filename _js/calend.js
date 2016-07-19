/* ALL
   календарик
*/
isDOM = document.getElementById;
isOpera = ( -1 < window.navigator.userAgent.indexOf('Opera'));
m = new Array(); y = new Array(); d = new Array;
monthdays = new Array(31,28,31,30,31,30,31,31,30,31,30,31);
var globalMinY =  1900;

function correctDate(field,miny,maxy) {
   var tmp = field.value.split(".");
   dt = parseInt(tmp[0],10); mt = parseInt(tmp[1],10)-1; yt = parseInt(tmp[2],10);
   date = new Date(yt,mt,dt);
   ycorrect = ( (1900 == (yt-date.getYear())) || (yt == date.getYear()) );

   if ( !miny || ( miny < globalMinY ) )
    { miny =  globalMinY;
    }

   if ( yt < miny ) { ycorrect = false; }
   if ( maxy && ( maxy < yt ) ) { ycorrect = false; }
   return ( (dt == date.getDate() ) && (mt == date.getMonth()) && ycorrect );
}

onload = initCloseAll;
function initCloseAll() {
  document.onclick = closeAll;
}

function closeAll() {
  if (isDOM)        {
    var list = document.getElementsByTagName('div');
    for (var i = 0; i < list.length; i++)
       if ( -1 < list[i].id.indexOf('calend') )
           list[i].style.display = 'none';
  }
}

function lookC(n) {
   if (isDOM)        {
        var elm=document.getElementById('calend'+n);
        if ( elm.style && elm.style.display ) {
                 elm.style.display=(elm.style.display=='block')?'none':'block';
                 if ( 'block' == elm.style.display ) {
                        if ( correctDate(document.getElementById('date'+n)) )
                         { d[n] = dt; m[n] = mt; y[n] = yt; }
                        else
                         { d[n] = dc; m[n] = mc; y[n] = yc; }
                           writeValue(d[n],n);
                           writeDate(n);
                 }
          }
  }

}

function writeValue(j,n) {
   document.getElementById('date'+n).value=''+( 10 > (d[n]=j) ? '0'+d[n] : d[n] )+'.'+( 9 > m[n] ? '0'+(m[n]+1) : m[n]+1 ) +'.'+ y[n];
}

function sel(j,n) {
   writeValue(j,n)
   lookC(n);
   change = true;
}

function changeM(n) {
 if (isDOM) {
   m[n] = document.getElementById('month'+n).selectedIndex;
   writeDate(n);
 }
}

function back(n) {
 if (isDOM) {
   var oldm = m[n];
   m[n] = (12+m[n]-1)%12;
   if ( ((m[n] - oldm) > 1) && (y[n] > globalMinY) ) y[n]--;
   writeDate(n)

 }
}

function forward(n) {
 if (isDOM) {
   var oldm = m[n];
   m[n] = (m[n]+1)%12;
   if ( (oldm - m[n]) > 1 ) y[n]++;
   writeDate(n)
 }
}

function ydown(n) {
 if (isDOM) {
   if (y[n] > globalMinY)
    { y[n]--;
      writeDate(n);
    }
 }
}

function yup(n) {
 if (isDOM) {
   y[n]++;
   writeDate(n);
 }
}

function writeDate(n) {
   monthdays[1] = ( (y[n]%4) ? 28 : 29 );
   if (  monthdays[m[n]] < d[n]  ) d[n] = monthdays[m[n]];

   document.getElementById('month'+n).selectedIndex = m[n];
   document.getElementById('year'+n).firstChild.nodeValue = y[n];
   writeDays(n);

}

function newE(span, j, n) {
      var e = document.createElement('div');
      var a = document.createElement('a');
      a.appendChild ( document.createTextNode( j ) )
      e.appendChild( a );
      var _class = '';
      if ( (dc == j) && (mc == m[n]) &&  (yc == y[n]) ) _class = "calendcurrent";
      if ( d[n] == j ) _class = "calendselect";
      if ( _class )  e.setAttribute('class',_class);
      span.appendChild(e);
      if ( e.outerHTML ) e.outerHTML = '<div class="'+_class+'"><a href="#" onclick="sel(\''+j+'\',\''+n+'\');return false;">'+j+'</a></div>';
      a.setAttribute('onclick','sel(\''+j+'\',\''+n+'\');return false;');
      a.setAttribute('href','#');
}

function newBr(span) {
      var br = document.createElement('br');
      br.setAttribute('clear','all');
      span.appendChild(br);
}

function writeDays(n) {
   var dayWeek = new Date(y[n],m[n],1);
   var day = (7+dayWeek.getDay()-1)%7;
   var node = document.getElementById('days'+n);
   var span = document.createElement('span');

   var i = -1; var j = 0; var k = 0;
   while ( 7 > ++i )
      newE(span, (i >= day ? ++j : ' '), n );

   newBr(span);

   while ( monthdays[m[n]] >= ++j ) {
      ++k;
      newE(span, j, n);
      if (  0 == (k%7) )
         newBr(span);
   }

   if (isOpera)
    { var e = document.createElement('div');
      span.appendChild(e);
      e.outerHTML = '<div class="calendopera"></div>';
      e.setAttribute( 'class','calendopera' );

      newBr(span);
      var e = document.createElement('div');
      span.appendChild(e);
      e.outerHTML = '<div class="calendopera"></div>';
      e.setAttribute( 'class','calendopera' );

    }



   node.replaceChild( span, node.firstChild );


}


