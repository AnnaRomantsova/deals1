/* BACK 
   функции для интерфейса "потомков" BTr,BCt
*/
function red(id,type,root) {
  location.href='?edit='+id+'&type='+type+(root ? '&root=1' : '');
}

function cut(id,type) {
  location.href='?sct_back='+T_id+'&cut='+id+'&type='+type;
}

function past() {
  if (confirm("Вы уверены, что хотите вставить вырезанные записи в этот раздел?"))
    location.href='?sct_back='+T_id+'&past='+T_id;
}

function undoPast() {
  if (confirm("Вы уверены, что хотите отменить вставку вырезанных записей?"))
    location.href='?sct_back='+T_id+'&undoPast=1';
}

function del(id,type) {
  if (confirm("Вы уверены, что хотите удалить эту запись?"))
    location.href='?sct_back='+T_id+'&delete='+id+'&type='+type;
}

function clear_s(message,id,type,isitem) {
  if (confirm(message))
    location.href='?clear_s='+id+'&clear_type='+type+(isitem ? '&isitem=1' : '');
}

function up(id,type) {
  location.href='?sct_back='+T_id+'&up='+id+'&type='+type;
}

function down(id,type) {
  location.href='?sct_back='+T_id+'&down='+id+'&type='+type;
}

function pabl(id,type) {
  location.href='?sct_back='+T_id+'&pabl='+id+'&type='+type;
}

function pabl1(id,type) {
  location.href='?sct_back='+T_id+'&pabl1='+id+'&type='+type;
}