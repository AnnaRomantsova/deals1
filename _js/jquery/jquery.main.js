jQuery(document).ready(function(){

       jQuery('#js-enter').click(function(){
               jQuery('html, body').animate( {scrollTop: 0}, 'slow');
                jQuery('.overlay').show();
                jQuery('.popup-block').show();
                return false;
        })
        jQuery('#js-enter-close, .overlay').click(function(){
                jQuery('.overlay').hide();
                jQuery('.popup-block').hide();
                return false;
        })

        jQuery('.js-enter').click(function(){
                jQuery('.popup-block .lenter').show();
                jQuery('.popup-block .lregister').hide();
                jQuery('.popup-block .lforgot').hide();
                return false;
        })

        jQuery('.js-forgot').click(function(){
                jQuery('.popup-block .lenter').hide();
                jQuery('.popup-block .lregister').hide();
                jQuery('.popup-block .lforgot').show();
                return false;
        })

        jQuery('.js-register').click(function(){
                jQuery('.popup-block .lenter').hide();
                jQuery('.popup-block .lregister').show();
                jQuery('.popup-block .lforgot').hide();
                return false;
        })


        jQuery('.js-hover').live('mouseover',function(){
                jQuery(this).addClass('hover');
        });
        jQuery('.js-hover').live('mouseout',function(){
                jQuery(this).removeClass('hover');
        });

})