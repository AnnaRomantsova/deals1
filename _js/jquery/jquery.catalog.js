$j(document).ready(function(){

	// add
	var allCash = new Array();

	var timeout = null;

	var hiddenAll = parseInt($j('#count-items').val());
	var priceAll = parseFloat($j('#count-price').val());

	var hiddenOther = hiddenAll;
	var priceOther = priceAll;

	$j('.cash').click(function(){

		if($j(this).attr('i') != undefined){
			var x = allCash[parseInt($j(this).attr('i'))];
			x.show();
			x.popup();
			return false;
		}


		var i = allCash.length;
		allCash[i] = new Object();
		var item = allCash[i];
		item.min = 1;
		item.max = parseInt($j(this).attr('max'));
		item.start = parseInt($j(this).attr('start'));
		item.visual = parseInt($j(this).attr('visual'));

		item.price = parseFloat($j(this).attr('price'));

		if (item.visual > 0) {
			item.start = item.visual;
			hiddenOther -= item.visual;
			priceOther -= item.visual*item.price;
		}
		item.pid = $j(this).attr('product');
		item.current = item.start;
		item.link = $j(this);

		var text = '<div><b class="Iminus js-hover"></b> ';
		text += '<input class="inter" value="' + item.current + '" />';
		//text += ' <b class="Iplus js-hover">+<span><a href="#">+10</a><a href="#">+5</a><a href="#">+1</a></span></b></div>';

		item.div = $j(this).after(text).next();
		item.input = item.div.find('input');
		item.link.attr('i',i).hide();
		item.div.attr('i',i);

		var hideTimer = null;
		item.input.keydown(function(e){
			if(e.keyCode > 95 && e.keyCode< 106)
				return true;
			if(e.keyCode > 58)
				return false;

		});
		item.input.keyup(function(){
			clearTimeout(hideTimer);

			var z = $j(this).val();
			if(z.length == 0)
				z = '0';
			item.current = parseInt(z);
			item.change(0,1500);
		})
		item.popup = function(setcoords){
			var price = $j('.popup-price');


			var summ = 0;
			var size = 0;
			var c = allCash.length;
			for(var z =0; z<c ; z++){
				size += allCash[z].current;

			}



	var txt = '';
	if(size%10 == 1 && size != 11 || (size > 20 && size%10 == 1))
		txt = size + ' книга ' ;
	else if(size < 20 && size > 4)
		txt = size + ' книг ';
	else if(size<5 ||( size%10 < 5 && size%10 != 0))
		txt = size + ' книги';
	else
		txt = size + ' книг ' ;

			price.find('a.change').html(txt);
			if(size > 0){
				$j('.korzina').show();
				$j('.korzina a.cash').html(txt);
			} else {
				$j('.korzina').hide();
			}

			var offset = item.input.offset();
			offset.top -= price.height()+5;
			offset.left -= 155;

			if(setcoords)
				price.css(offset);
			price.show();
			clearTimeout(timeout);
			timeout = setTimeout(function(){
				price.hide();
			},2000)
		}
		item.change=function(count,time){

			var value = item.current;
			var result = 0;
			if(count+value < item.min){
				result = item.min-1;
				if( time ){
					clearTimeout(timeout);
					hideTimer = setTimeout(function(){
						item.hide();
					},time);
				} else {
					clearTimeout(timeout);
					item.hide();
				}
			} else if(count+value > item.max){
				result = item.max;
			} else {
				result = count+value;
			}
			item.current = result;
			if(result > 0 )
				item.input.val(result);

			Set_Cookie('cash_item['+item.pid+']',item.current, 30, '/');
			item.popup(true);
		}
		item.show = function(){
			item.current = item.min;
			item.input.val(item.current);

			item.div.show();
			item.link.hide();
		}
		item.hide = function(){
			item.div.hide();
			item.link.show();
		}
		item.div.find('a').attr('i',i).click(function(){
			var count = parseInt($j(this).html());
			item.change(count);
			return false;
		})
		item.change(0);
		var mouseover = false;

		$j('.popup-price').mouseover(function(){
			clearTimeout(timeout);
			mouseover = true;
		})
		$j('.popup-price').mouseout(function(){
			if (mouseover) {
				mouseover = false;
				clearTimeout(timeout);
				timeout = setTimeout(function(){
					$j('.popup-price').hide();
				}, 2000)
			}
		})

		if(item.visual > 0){
			item.div.show();
			item.link.hide();
			//item.popup(true);
			clearTimeout(timeout);
			$j('.popup-price').hide();


		}

		return false;
	});

	$j('.popup-price').click(function(){
		return false;
	});

	$j('.popup-price a.close').click(function(){
		$j('.popup-price').hide();
		return false;
	})

	$j('.popup-price input, .korzina input, .popup-price a.change, .korzina a.cash').click(function(){
		location.href = $j(this).attr('href')
		return false;
	})

	var size = hiddenOther;
	var summ = priceOther;



	var txt = '';
	if(size%10 == 1 && size != 11 || (size > 20 && size%10 == 1))
		txt = size + ' книга ' ;
	else if(size < 20 && size > 4)
		txt = size + ' книг ';
	else if(size<5 ||( size%10 < 5 && size%10 != 0))
		txt = size + ' книги';
	else
		txt = size + ' книг ' ;


	if(size > 0){
		$j('.korzina').show();
		$j('.korzina a.cash').html(txt);
	} else {
		$j('.korzina').hide();
	}

	$j('.cash[visual]').trigger('click');

});