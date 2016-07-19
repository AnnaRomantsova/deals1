(function(jQuery) {
            jQuery(function() {
                // alert(jQuery('#city').val());
                jQuery('#city').change(function () {
                   // alert(jQuery('#city').val());
                    var city_id = jQuery(this).val();
                    /*(jQuery)('#street').attr('disabled', 'disabled');
                    jQuery("#street").selectBox("refresh");
                    (jQuery)("#street").empty();
                    (jQuery)("#street").append( (jQuery)('<option value="1" selected disabled>Идет загрузка</option>'));
                    //(jQuery)('#street').delay(2000);
                    jQuery("#street").selectBox("refresh");
                    */
                    jQuery.post(
                      "/ajax/house.php",
                      {
                        id_city: city_id
                      },
                      onAjaxSuccess
                    );

                    function popup() {

                    };

                    function onAjaxSuccess(data, textStatus)
                    {
                       // alert(data);
                        if (textStatus.type == 'error') { alert('error'); return(false); }
                        else {
                               (jQuery)("#street").empty();
                               var str= data.split(';');
                               for (var i = 0; i < str.length; i++) {
                                  if (str[i] !== '') {
                                     var str1= str[i].split(':');
                                     var id=str1[0];
                                     var name=str1[1];
                                     if (i==0)
                                        (jQuery)("#street").append( (jQuery)('<option value="'+id+'"  selected disabled>'+name+'</option>'));
                                     else (jQuery)("#street").append( (jQuery)('<option value="'+id+'">'+name+'</option>'));
                                   };
                                   jQuery("#street").selectBox("refresh");
                               };
                        }
                    };


                });
                jQuery('#street').change(function () {

                    var street_id = jQuery(this).val();
                   /*(jQuery)('#house').attr('disabled', 'disabled');
                   jQuery("#house").selectBox("refresh");
                    (jQuery)("#house").empty();
                    (jQuery)("#house").append( (jQuery)('<option value="1" selected disabled>Идет загрузка</option>'));
                    //(jQuery)('#house').delay(2000);
                    jQuery("#house").selectBox("refresh");
                    */
                    jQuery.post(
                      "/ajax/house.php",
                      {
                        id_street: street_id
                      },
                      onAjaxSuccess
                    );

                    function onAjaxSuccess(data, textStatus)
                    {
                       // alert(data);
                        if (textStatus.type == 'error') { alert('error'); return(false); }
                        else {
                               (jQuery)("#house").empty();
                               var str= data.split(';');
                               for (var i = 0; i < str.length; i++) {
                                  if (str[i] !== '') {
                                     var str1= str[i].split(':');
                                     var id=str1[0];
                                     var name=str1[1];
                                     if (i==0)
                                        (jQuery)("#house").append( (jQuery)('<option value="'+id+'"  selected disabled>'+name+'</option>'));
                                     else (jQuery)("#house").append( (jQuery)('<option value="'+id+'">'+name+'</option>'));
                                   };
                                   jQuery("#house").selectBox("refresh");
                                   //jQuery("#house").selectBox("showMenu");
                               };
                        }
                    };


                });
            })
 })(jQuery)