/* BACK
   функции проверки на стороне клиента типов файлов, загруженных в <input type="file" ... />
*/
function fileIsImage(field) {
  if ( !/.*\.jpg$|.*\.png$|.*\.gif$/i.test(field.value) ) {
          field.outerHTML =  field.outerHTML;
          alert('Не допустимый тип файла!\n (Разрешенные типы png,jpg,gif)');
  }
}

function fileIsPrice(field) {
  if ( !/.*\.doc$|.*\.xls$|.*\.rar$|.*\.zip|.*\.7z$/i.test(field.value) ) {
          field.outerHTML =  field.outerHTML;
          alert('Не допустимый тип файла!\n (Разрешенные типы doc,xls,rar,zip,7z)');
  }
}

function fileIsArchive(field) {
  if ( !/.*\.zip$|.*\.rar|.*\.7z$/i.test(field.value) ) {
          field.outerHTML =  field.outerHTML;
          alert('Не допустимый тип файла!\n (Разрешенные типы rar,zip,7z)');
  }
}

function fileIsDoc(field) {
  if ( !/.*\.zip$|.*\.doc$|.*\.pdf$|.*\.rar$/i.test(field.value) ) {
          field.outerHTML =  field.outerHTML;
          alert('Не допустимый тип файла!\n (Разрешенные типы rar,zip,doc,pdf)');
  }
}
