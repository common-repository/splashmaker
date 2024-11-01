(function( $ ) {
	'use strict';
	// updated by kuldeep
	$( document ).ready(function() {
    var website_base_url = frontend_ajax_object.plugindir; //admin folder
    $( "#toplevel_page_splashmaker .wp-submenu li:nth-child(5)" ).addClass( "pi" );
    $( "#toplevel_page_splashmaker .wp-submenu li:nth-child(8)" ).addClass( "help" );
    $(".help a").attr('target','_blank');
    $('.toplevel_page_splashmaker.menu-top-first.opensub ').hover(
        function(){ $('#toplevel_page_splashmaker .wp-menu-image.dashicons-before img').addClass('smhover') },
        function(){ $('#toplevel_page_splashmaker .wp-menu-image.dashicons-before img').removeClass('smhover') }
    )
    $(".post-type-dynamic_content .wrap .wp-heading-inline").before('<div id="icon-users" class="icon"><img src="'+website_base_url+'img/logo_sm_icon.svg"></div>');
    $('.post-type-dynamic_content #icon-users, .post-type-dynamic_content .wp-heading-inline, .post-type-dynamic_content .page-title-action').wrapAll('<div class="splash_header">');            
    $('#toplevel_page_splashmaker').mouseover(function () {
        $('#toplevel_page_splashmaker .wp-menu-image.dashicons-before img').attr("src", website_base_url+"/img/logo_sm_icon_blue_wp.svg");
    })
    $('#toplevel_page_splashmaker').mouseout(function () {
        $('#toplevel_page_splashmaker .wp-menu-image.dashicons-before img').attr("src", website_base_url+"/img/logo_sm_icon_white.svg");
    });
    $( ".wp-has-current-submenu.wp-menu-open.menu-top.toplevel_page_splashmaker" ).mouseover(function() {
        $('#toplevel_page_splashmaker .wp-menu-image.dashicons-before img').attr("src", website_base_url+"/img/logo_sm_icon_white.svg");
    });
    
    $('.optionBox').on('click','.remove',function() {
        $(this).parent().remove();
    });
    //for splash add_new field
    $( "#splash_hide" ).hide();
    $('.splash_add_custom_field').click(function() {
        $( "#splash_hide" ).show();
        $("#splash_hide").eq(0).clone().appendTo( $( ".add_xtra_field" ) );
        $("#splash_hide:first").hide();
    
    });
    $(document).on("click",".splash_remove_btn",function(e){ 
    var temp=$(this).parents(".splash_panel");
    
            console.log(temp);
            temp.closest( ".splash_panel_100").remove();
    });

    // kuldeep comments
    var boxes = $('.clickToCopy');
    boxes.click(function(){
      boxes.removeClass('active');
      $(this).addClass('active');  
        $(this).animate({
          // backgroundColor: '#a2d6f7',
          backgroundColor: '#a0cefa',          
          color: '#2c3338'
        }, 500, 'linear', function() 
        { 
            $(this).animate({
              backgroundColor: '#cce7f8',
              color: '#2c3338'
            });
        }
        );
    });
    // kuldeep comments

    $('.clickToCopy').on('click',function(){
        var id = $(this).attr('data-id');
        var elem = $('#clickToCopy_'+id);
        var elem1 = 'clickToCopy_'+id;
        var copyText = document.getElementById(elem1);
        var is_safari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
                // alert(elem);
                
                
        if (navigator.userAgent.match(/ipad|ipod|iphone/i) || is_safari) {
          var el = elem.get(0);
          var editable = el.contentEditable;
          var readOnly = el.readOnly;
          el.contentEditable = true;
          el.readOnly = true;
          var range = document.createRange();
          range.selectNodeContents(el);
          var sel = window.getSelection();
          sel.removeAllRanges();
          sel.addRange(range);
          el.setSelectionRange(0, 999999);
          el.contentEditable = editable;
          el.readOnly = readOnly;
        }else{
            copyText.select();
        }
        // document.execCommand('styleWithCSS', false, true);
        // document.execCommand('foreColor', false, 'hotpink');
        document.execCommand("copy");

        $(this).next('.shareDropdown').stop().slideToggle(500);
        return false;

    });


	 jQuery('li#toplevel_page_splashmaker  ul  li:last-child a').attr('target','_blank');
     jQuery('li#toplevel_page_splashmaker  ul  li').eq(4).find('a').attr('target','_blank');
    });


})( jQuery );