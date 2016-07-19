(jQuery)(document).ready(function(){

        (jQuery)('#js-enter').click(function(){
                (jQuery)('.overlay').show();
                (jQuery)('.popup-block').show();
                alert('f');
                return false;
        })

        (jQuery)('.js-enter').click(function(){
                $('.popup-block .lenter').show();
                $('.popup-block .lregister').hide();
                $('.popup-block .lforgot').hide();
                return false;
        })


})
