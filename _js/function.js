(jQuery)(document).ready(function() {
        //echo  "hh";
       // jQuery("select:not(#month1,#month2)").selectBox();


        (jQuery)("#closePopup").click(function(){
                (jQuery)(".zalivka").hide(500);
                (jQuery)(".popupWindow").hide(500);
        })
})


function submit_forms(formid)
  {

     var form = document.getElementById(formid);
     //alert(form);
     form.submit();
  }

function license(obj)
  {
     var lic = document.getElementById('lic');
     var but = document.getElementById('regbut');
     if (obj.checked ) {
             lic.style.display='block';
             but.className = 'greenButtom';

      } else  {
            lic.style.display='none';
            but.className = 'noactiveButtom';
      };
  }

  function go_register()
  {
     var lic = document.getElementById('lic');

     if (lic.style.display=='block') submit_forms('reg');
      //else  lic.style.display='none';
  }
