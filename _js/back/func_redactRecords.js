/* BACK
   функции для интерфейса "потомков" B
*/

function red(id) {
   location.href='?edit='+id;
}

function del(id) {
  if (confirm("Вы уверены, что хотите удалить эту запись?"))
    location.href='?delete='+id+'&cp='+T_cp;
}

function clear_(message) {
  if (confirm(message))
    location.href='?clear=1';
}

function up(id) {
  location.href='?id_back='+id+'&up='+id;
}

function down(id) {
  location.href='?id_back='+id+'&down='+id;
}

function pabl(id) {
  location.href='?id_back='+id+'&pabl='+id;
}

function change_city() {
  var sel = document.getElementById("city"); // Получаем наш список
  var val = sel.options[sel.selectedIndex].value; // Получаем значение выде
  location.href='?id_city='+val;
}

function change_street() {
  var sel = document.getElementById("street"); // Получаем наш список
  var street = sel.options[sel.selectedIndex].value; // Получаем значение выде

  var sel = document.getElementById("city"); // Получаем наш список
  var city = sel.options[sel.selectedIndex].value; // Получаем значение выде
 // alert('?id_street='+street+'&id_city='+city);

  location.href='?id_street='+street+'&id_city='+city;
}

function submit_forms(formid)
  {
     var form = document.getElementById(formid);
     form.submit();
  }

