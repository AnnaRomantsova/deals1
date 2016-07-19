/**
 * @author Admin
 */

var isChanged = false;

/*window.onbeforeunload = function(evt){
        if(isChanged){
                var message = "—охраните пожайлуйста изменени€ до закрыти€ страницы, иначе они потер€ютс€!";
                if (typeof evt == 'undefined') {
                        evt = window.event;
                }
                if (evt) {
                        evt.returnValue = message;
                }
                return message;
        }
}*/

$j(document).ready(function(){
        var allCash = new Array();


        $j('input.inter').each(function(){

                var i = allCash.length;
                allCash[i] = new Object();
                var item = allCash[i];
                item.min = 1;
                item.max = parseInt($j(this).attr('max'));
                item.price = parseFloat($j(this).attr('price'));
                item.pid = $j(this).attr('product');
                item.current = parseInt($j(this).val());

                item.input = $j(this);
                item.tr = item.input.parent().parent();

                item.action = item.tr.find('a.action');
                item.hided = item.tr.find('b.hided');

                //item.input.before('<b class="Iminus js-hover">-<span><a href="#">-1</a><a href="#">-5</a><a href="#">-10</a></span></b> ');
                //item.input.after(' <b class="Iplus js-hover">+<span><a href="#">+10</a><a href="#">+5</a><a href="#">+1</a></span></b>');


                item.input.keydown(function(e){
                        if(e.keyCode > 95 && e.keyCode< 106)
                                return true;
                        if(e.keyCode > 58)
                                return false;

                });

                item.input.keyup(function(){
                        var z = $j(this).val();
                        if(z.length == 0)
                                z = '0';
                        item.current = parseInt(z);
                        item.change(0);
                        isChanged = true;
                        if(z == '0'){
                                $j(this).val('');
                        }
                })

                item.visualvalue = function(value){
                        item.current = value;
                        item.input.val(item.current);
                        Set_Cookie('cash_item['+item.pid+']',item.current, 30, '/');
                        var vff= item.current * item.price;
                        vff = Math.floor(vff*100)/100;
                        item.hided.html(vff +' <span class="rur">р<span>уб.</span></span>*');

                        var summ = 0;
                        var size = 0;
                        var c = allCash.length;
                        for(var z= 0 ; z<c; z++){
                                summ += allCash[z].current*allCash[z].price;
                                size += allCash[z].current;
                        }

                        summ = Math.floor(summ*100)/100;

                        $j('.result').html(summ+' <span class="rur">р<span>уб.</span></span>*');

                        if(summ > 0){
                                $j('.korzina').show();
                                var txt = '';
                                if(size%10 == 1 && size != 11 || (size > 20 && size%10 == 1))
                                        txt = size + ' товар на ' + summ + ' <span class="rur">р<span>уб.</span></span>*';
                                else if(size < 20 && size > 4)
                                        txt = size + ' товаров на ' + summ + ' <span class="rur">р<span>уб.</span></span>*';
                                else if(size<5 ||( size%10 < 5 && size%10 != 0))
                                        txt = size + ' товара на ' + summ + ' <span class="rur">р<span>уб.</span></span>*';
                                else
                                        txt = size + ' товаров на ' + summ + ' <span class="rur">р<span>уб.</span></span>*';

                                $j('.korzina a.cash').html(txt);
                        } else{
                                $j('.korzina').hide();
                        }
                }
                item.show = function(setmin){
                        if (setmin) {
                                item.visualvalue(item.min);
                                isChanged = true;
                        }

                        item.tr.removeClass('deleted');
                }
                item.hide = function(setmin){
                        if(setmin){
                                item.visualvalue(item.min-1);
                                isChanged = true;
                        }
                        item.tr.addClass('deleted');
                }
                item.change = function(count){
                        if(count != 0){
                                isChanged = true;
                        }
                        var value = item.current;
                        var result = 0;
                        if(count+value < item.min){
                                item.visualvalue(item.min-1);
                                item.hide(false);
                        } else if(count+value > item.max){
                                item.visualvalue(item.max);
                                item.show(false);
                        } else {
                                item.visualvalue(count+value);
                                item.show(false);
                        }
                }

                item.input.parent().find('a').attr('i',i).click(function(){
                        var count = parseInt($j(this).html());
                        item.change(count);
                        return false;
                })

                item.change(0);

                item.action.click(function(){
                        if(item.tr.hasClass('deleted')){
                                item.show(true);
                        } else {
                                item.hide(true);
                        }
                        return false;
                })


        })

        $j('.popup-price a.close').click(function(){
                $j('.popup-price').hide();
                return false;
        })

        $j('.popup-price a.change, .korzina a.cash').click(function(){
                location.href = $j(this).attr('href')
                return false;
        })
})
