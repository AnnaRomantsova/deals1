/* ALL
   проверяет корректность полей форм типа select,text,email(если в имени input есть такая подстрочка)
*/
function e(s)
{
 rex=true;
 if (window.RegExp)
  {st="a";ex=new RegExp(st);
   if (st.match(ex))
    {
     r1=new RegExp("(@.*@)|(\\.\\.)|(@\\.)|(^\\.)");
     r2=new RegExp("^.+\\@(\\[?)[a-zA-Z0-9\\-\\.]+\\.([a-zA-Z]{2,4}|[0-9]{1,4})(\\]?)$");
     b=(!r1.test(s)&&r2.test(s));
    }
   else
        {rex=false;}
  }
 else
 {rex=false;}
 if(!rex) b=(s.indexOf("@")>0&&s.indexOf(".")>0&&s!=""&&s!="введите e-mail");
 return (b);
}

function val(form,fields) {
  for (var i=0; i < fields.length; i++) {
    eval('var v = form.'+fields[i]);
    if ('SELECT' == v.tagName) {
       if (!v.selectedIndex) {
                alert("Пожалуйста, сделайте выбор в выпадающих списках,\n отмеченных * !");
                v.focus();
                return false;
       }
       continue;
    }
    if ('' == v.value) {
            alert ('Пожалуйста, заполните все поля, отмеченные * !');
               v.focus();
            return false;
    }
    if ( (-1 < fields[i].indexOf('email')) && !e(v.value) ) {
        alert ('Пожалуйста, введите корректный e-mail!');
               v.focus();
        return false;
    }
  }

  return true;
}

function fieldOR(form,fields) {
  for (var i=0; i < fields.length; i++) {
          eval('var v = form.'+fields[i]);
          if ('' != v.value) return true;
  }
  return false;
}
