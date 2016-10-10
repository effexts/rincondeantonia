<?php global $ocart; ?>

<script type="text/javascript">
function getProductAjaxRequest(){
	$.ajax({
		url: '<?php echo get_template_directory_uri(); ?>/ajax/product.php',
		type: 'get',
		data: {product_id : product_id},
		beforeSend: function(){
			$('#details').css({opacity: 1, left: 0});
			$('#details').html('<div class="product product-load"></div>');
			deviceWidth = $(window).width();
			if (deviceWidth <= 977) {
				$('#banner, #details').css({'height':'800px'});
			}
			$.scrollTo('#header', 800);
		},
		error: function(x, textStatus, m) {
			if (textStatus=="timeout") {
				getProductAjaxRequest();
			}
		},
		success: function(data){
			$('.iosSlider').fadeOut('fast');
			$('.prods li[id=item-' + product_id + ']').addClass('current');
			$('#details').html(data);
			$('.navi li:last').remove();
			$('input[type="text"]').not('#min_price, #max_price').clearOnFocus();
			if (deviceWidth > 800) {
				$('.btn-quantity').tipsy({
					trigger: 'focus',
					gravity: 'w',
					offset: 18
				});
			}
			if (deviceWidth > 800) {
				$('.tip').tipsy({
					delayIn: 200,
					gravity: 'n',
					offset: 8
				});
			}
			$('.optionprice').tipsy({
				trigger: 'hover',
				gravity: 'w',
				offset: 4
			});
			// reinstate carousel
			clearTimeout(resizeTimer);
			resizeTimer = setTimeout(reinitCarousel, 100);
			$('.main-image .zoom:first').fadeIn(800, function(){
				if (deviceWidth > 766) {
				$(this).jqzoom({ preloadText: '<?php _e('Loading...','ocart'); ?>' });
				}
			});zz
			$('.thumbs a, .thumbs2 a').click(function(){
				var rel = $(this).attr('rel');
				var currentID = $('.main-image .zoom:visible').attr('id');
				if (rel !== currentID && rel != 'video') {
					$('.main-image .zoom').fadeOut(800);
					$(".main-image .zoom[id='" + rel + "']").fadeIn(800, function(){
						if (deviceWidth > 766) {
						$(this).jqzoom({ preloadText: '<?php _e('Loading...','ocart'); ?>' });
						}
					});
				}
			});
		}
	});
	return false;
}

// function to re-init carousel
function reinitCarousel() {

	deviceWidth = $(window).width();
	if (deviceWidth <= 977) {
		$('.product-img').css({'width': $('#details').width() });
	} else {
		catalogver = '<?php echo ocart_catalog_version() ?>';
		force_lightbox = '<?php echo ocart_get_option('force_lightbox'); ?>';
		if ($('#lightbox').length == 0) { // if we are not using lightbox ver
		$('.product-img').css({'width': '490px'});
		}
		$('.thumbs').css({'position': 'absolute', 'top': 0, 'right': '36px'});
	}
	
    $('.thumbs').trigger('destroy');
	if (deviceWidth > 480) {
	$('.thumbs').show().carouFredSel({
				width: 'auto',
				height: 406,
				scroll: 1,
				items: 3,
				auto: false,
				direction: "down",
				prev: ".upImage",
				next: ".dnImage"
	});
	}
	
	// thumbs v2
	$('.thumbs2').show().carouFredSel({
				width: '100%',
				height: 'auto',
				align: 'left',
				scroll: 1,
				items: 5,
				auto: false,
				direction: "right",
				prev: ".prevImage2",
				next: ".nextImage2"
	});
	
	$('.prods').carouFredSel({
			width: 977,
			height: <?php echo ocart_get_option('catalog_image_height') + 100; ?>,
			scroll: 1,
			align: "left",
			auto: false,
			direction: "right",
			prev: {
				button: '.prevItem',
				onBefore: function(){
						$('.prods li').removeClass('viewport');
						$('.prevproduct').stop().animate({left: 0});
						$('.prods').trigger("currentVisible", function( items ) {
							items.addClass( 'viewport' );
							var next_item_id = $('.prods li.viewport:last').next().attr('id').replace(/[^0-9]/g, '');
							$('.nextproduct').load('<?php echo get_template_directory_uri(); ?>/ajax/getimage.php?id=' + next_item_id, function(){
								$('.prevproduct').hide().stop().animate({left: '-200px'});
							});
						});
				},
				onAfter: function(){
						$('.prods li').removeClass('viewport');
						$('.prods').trigger("currentVisible", function( items ) {
							items.addClass( 'viewport' );
							var $img = $('.prods li.viewport:first').last(),
								$prev = $img.prev();
							if (0==$prev.length) {
								$prev = $img.siblings().last();
							}
							var prev_item_id = $prev.attr('id').replace(/[^0-9]/g, '');
							$('.prevproduct').load('<?php echo get_template_directory_uri(); ?>/ajax/getimage.php?id=' + prev_item_id, function(){
								$('.prevproduct').show();
							});
						});
				}
			},
			next: {
				button: '.nextItem',
				onBefore: function(){
						$('.prods li').removeClass('viewport');
						$('.nextproduct').stop().animate({right: 0});
						$('.prods').trigger("currentVisible", function( items ) {
							items.addClass( 'viewport' );
							var prev_item_id = $('.prods li.viewport:first').attr('id').replace(/[^0-9]/g, '');
							$('.prevproduct').load('<?php echo get_template_directory_uri(); ?>/ajax/getimage.php?id=' + prev_item_id, function(){
								$('.nextproduct').hide().stop().animate({right: '-200px'});
							});
						});
				},
				onAfter: function(){
						$('.prods li').removeClass('viewport');
						$('.prods').trigger("currentVisible", function( items ) {
							items.addClass( 'viewport' );
							var next_item_id = $('.prods li.viewport:last').next().attr('id').replace(/[^0-9]/g, '');
							$('.nextproduct').load('<?php echo get_template_directory_uri(); ?>/ajax/getimage.php?id=' + next_item_id, function(){
								$('.nextproduct').show();
							});
						});
				}
			}
	});

};

var resizeTimer;

// window resizing
$(window).resize(function(){
	
	// detect banner/details height
	deviceWidth = $(window).width();
	if (deviceWidth <= 977) {
		if ($('.iosSlider').is(':hidden')) { // only when product is active
			$('#details, #banner').css({'height':'800px'});
		}
	} else {
		if ($('#details .product').is(':visible')) {
			$('#details, #banner').css({'height':'406px'});
		}
	}
	
	// reinstate carousel
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(reinitCarousel, 100);
	
});

// custom scripting starts here
$(function() {

	// get device width
	deviceWidth = $(window).width();
	
	// reinstate carousel
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(reinitCarousel, 100);

	// sticky footer code
	function positionFooter(){
		if($(document).height() == $(window).height()){
			$("#footer").css({position: "absolute",top:($(window).scrollTop()+$(window).height()-$("#footer").height())-1+"px"});
		}
	}
	positionFooter(); 
	$(window).scroll(positionFooter).resize(positionFooter);
	
	// sticky sidebar
	if (!!$('#checkout_summary').offset()) { // make sure ".sticky" element exists
 
    var stickyTop = $('#checkout_summary').offset().top; // returns number
 
    $(window).scroll(function(){ // scroll event
 
      var windowTop = $(window).scrollTop(); // returns number
 
      if (stickyTop < windowTop){
        $('#checkout_summary').css({ position: 'fixed', top: 0 });
      }
      else {
        $('#checkout_summary').css('position','static');
      }
 
    });
	
	}

	// close filter automatically
	$(document).click(function () {
		if ($('#filter-by').is(':visible')) {
			$('#filter-by').fadeOut();
			$('#filter').css({'background-position' : '0 0'});
		}
		$('.cartpopup').fadeOut('fast', function(){
		});
		if ($('.ajax-search-results').is(':visible')) {
			$('.ajax-search-results').hide();
			$('#productSearch').stop().animate({'width':'100px'}, 600);
		} else {
			$('#productSearch').stop().animate({'width':'100px'}, 600);
		}
	});
	$('#filter-by, #filter, .cart, .ajax-search').live('click',function (e) {
        e.stopPropagation();
	});

	// clear field on focus
	$('input[type="text"]').not('#min_price, #max_price').clearOnFocus();

	// animate top links
    $('#toplinks a').mouseenter(function() {
		$(this).stop(true, true).animate({backgroundColor:'<?php ocart_skin_data('active_color') ?>'},300);
	}).mouseleave(function() {
		$(this).stop(true, true).animate({backgroundColor:'#fff'}, 1);
    });
	
	// animate list choices
    $('.list a').live('mouseenter',function() {
		if ($(this).hasClass('current') == false) {
		$(this).stop().animate({color:'<?php ocart_skin_data('nav_hover_color') ?>'},600);
		}
	}).live('mouseleave',function() {
		if ($(this).hasClass('current') == false) {
		$(this).stop().animate({color:'<?php ocart_skin_data('nav_color') ?>'},100);
		}
    });
	
	// animate blog categories
    $('.blog_nav a').live('mouseenter',function() {
		if ($(this).parent().hasClass('current-cat') == false) {
		$(this).stop().animate({color:'<?php ocart_skin_data('nav_hover_color') ?>'},600);
		}
	}).live('mouseleave',function() {
		if ($(this).parent().hasClass('current-cat') == false) {
		$(this).stop().animate({color:'<?php ocart_skin_data('nav_color') ?>'},100);
		}
    });
	$('.current-cat a').append('<span />');

	// toggle current choice
    $('.list a').live('click',function(e) {
	
		e.preventDefault();
		
		// animation
		$('.list a').css({'color':'<?php ocart_skin_data('nav_color') ?>'});
		$('.list a').removeClass('current');
		$('.list a').children('span').remove();
		$(this).addClass('current');
		$(this).append('<span></span>');
		$(this).stop().animate({color:'<?php ocart_skin_data('active_color') ?>'}, {duration:1, complete: function(){
			$(this).effect("bounce", { direction: 'left', distance:10, times:3 }, 400);
		}
		});
		
		catalogver = '<?php echo ocart_catalog_version() ?>';
		
		// v1
		if (catalogver == 1) {
		var taxonomy = $(this).attr('id');
		$.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/catalog.php',
			type: 'get',
			data: {taxonomy: taxonomy},
			success: function(data){
				$('.catalogWrapper').html(data);
				$('#filter-by').load('<?php echo get_template_directory_uri(); ?>/ajax/filters.php');
			}
		});
		}
		
		// v2
		if (catalogver == 2) {
		// reset filters
		$('.filter ul a').removeClass('selected');
		$('.filter ul a#' + $(this).attr('id')).addClass('selected');
		var taxonomies = $(this).attr('id');
			pricemin = $('.text_min ins').html();
			pricemax = $('.text_max ins').html();
		$('.catalog_title').load('<?php echo get_template_directory_uri(); ?>/ajax/catalog_title.php?pricemin=' + pricemin + '&pricemax=' + pricemax + '&taxonomies=' + taxonomies);
		$('.catalog').css({opacity: 0.5});
		$.ajax({
			type: 'post',
			url: '<?php echo get_template_directory_uri(); ?>/ajax/showmore.php',
			data: {pricemin: pricemin, pricemax: pricemax, taxonomies: taxonomies, offset: 0},
			success: function(res) {
				$(".catalog_list").html(res);
				$('.catalog').css({opacity: 1});
			}
		});
		}
		
    });
	
	// animate next/prev
	$('.next').live('mouseenter',function(){ 
		$(this).stop().animate({backgroundPosition:"(-21px -20px)"}, 300)
	}).live('mouseleave',function(){
		$(this).stop().animate({backgroundPosition:"(-21px 0)"},300) });
	$('.prev').live('mouseenter',function(){
		$(this).stop().animate({backgroundPosition:"(0 -20px)"}, 300)
	}).live('mouseleave',function(){
		$(this).stop().animate({backgroundPosition:"(0 0)"}, 300)
	});
	
	// animate option links
	$('.options a').live('mouseenter',function(){
		$(this).stop().animate({backgroundColor:'#fbfbfb','padding-left':'30px'}, 200);
	}).live('mouseleave',function(){
		$(this).stop().animate({backgroundColor:'#fff','padding-left':'20px'}, 100);
	});
	
	// toggle sorting options
	$('.options').css({'display': 'none'});
	$('.options a:last').css({'border-bottom': 'none'});
	$('.sort').live('mouseenter',function(){
		$('.options').stop(true, true).slideDown('slow');
		$(this).children('.sort-link').stop().animate({backgroundPosition: "right bottom"},500);
		$(this).children('.sort-link').addClass('current');
	}).live('mouseleave',function(){
		$('.options').fadeOut('fast');
		$(this).children('.sort-link').stop().animate({backgroundPosition: "right top"},300);
		$(this).children('.sort-link').removeClass('current');
	});
	
	// toggle languages
	$('.switchbar-inner ul ul').css({'display': 'none'});
	$('.switchbar-inner li').live('mouseenter',function(){
		$(this).find('ul').stop(true, true).slideDown();
	});
	$('.switchbar-inner').live('mouseleave',function(){
		$('.switchbar-inner ul ul').slideUp();
	});
	
	// switch parent categories
	$('.options a').live('click',function(){
		var taxonomy = $(this).attr('id');
		$.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/categories.php',
			type: 'get',
			data: {taxonomy : taxonomy},
			beforeSend: function(){
				$('.list').empty();
			},
			success: function(data){
				$('#browser').html(data);
			}
		});
		$.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/catalog.php',
			type: 'get',
			data: {taxonomy: taxonomy},
			success: function(data){
				$('.catalogWrapper').html(data);
				$('#filter-by').load('<?php echo get_template_directory_uri(); ?>/ajax/filters.php');
			}
		});
	});
	
	// switch "select"
	$('#nav select').live('change',function(){
		var select = $(this);
		var taxonomy = select.val();
		$.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/catalog.php',
			type: 'get',
			data: {taxonomy: taxonomy},
			success: function(data){
				$('.catalogWrapper').html(data);
				$('#filter-by').load('<?php echo get_template_directory_uri(); ?>/ajax/filters.php');
			}
		});
	});

	/* main slideshow */
	<?php if (ocart_get_option('enable_slideshow')) { ?>
	$('.iosSlider').iosSlider({
		autoSlide: true,
		desktopClickDrag: true,
		snapToChildren: true,
		infiniteSlider: true,
		navSlideSelector: $('.iosSlider_buttons .button'),
		navPrevSelector: $('.prevButton'),
		navNextSelector: $('.nextButton'),
		onSlideChange: slideContentChange,
		onSlideComplete: slideContentComplete,
		onSliderLoaded: slideContentLoaded
	});
	<?php } ?>
	
	function slideContentChange(args) {
		/* indicator */
		$(args.sliderObject).parent().find('.iosSlider_buttons .button').removeClass('selected');
		$(args.sliderObject).parent().find('.iosSlider_buttons .button:eq(' + args.currentSlideNumber + ')').addClass('selected');
	}

	function slideContentComplete(args) {

		/* animation */
		$(args.sliderObject).find('.text1, .text2, .text4, .text6').attr('style', '');
		$(args.currentSlideObject).children('.text1').animate({
			left: '500px',
			opacity: '1'
		}, 600, 'easeOutQuint');
		$(args.currentSlideObject).children('.text2').delay(600).animate({
			top: '140px',
			opacity: '1'
		}, 600, 'easeOutQuint');
		$(args.currentSlideObject).children('.text4').delay(300).animate({
			left: '60px',
			opacity: '1'
		}, 300, 'easeOutQuint');
		$(args.currentSlideObject).children('.text6').delay(300).animate({
			right: '60px',
			opacity: '1'
		}, 300, 'easeOutQuint');
	}

	function slideContentLoaded(args) {
	
		/* animation */
		$(args.sliderObject).find('.text1, .text2, .text4, .text6').attr('style', '');
		$(args.currentSlideObject).children('.text1').animate({
			left: '500px',
			opacity: '1'
		}, 600, 'easeOutQuint');
		$(args.currentSlideObject).children('.text2').delay(600).animate({
			top: '140px',
			opacity: '1'
		}, 600, 'easeOutQuint');
		$(args.currentSlideObject).children('.text4').delay(300).animate({
			left: '60px',
			opacity: '1'
		}, 300, 'easeOutQuint');
		$(args.currentSlideObject).children('.text6').delay(300).animate({
			right: '60px',
			opacity: '1'
		}, 300, 'easeOutQuint');

		/* indicator */
		$(args.sliderObject).parent().find('.iosSlider_buttons .button').removeClass('selected');
		$(args.sliderObject).parent().find('.iosSlider_buttons .button:eq(' + args.currentSlideNumber + ')').addClass('selected');

	}
	
	// animate button [1]
    $('.button1').mouseover(function() {
		$(this).stop().animate({opacity: 1,backgroundColor:'#fff'},300);
	}).mouseout(function() {
		$(this).stop().animate({opacity: 0.90,backgroundColor:'<?php ocart_skin_data('active_color') ?>'},300);
    });
	
	// animate product in catalog
    $('.prods li').live('mouseenter',function() {
		$(this).children('.label').stop(true,true).animate({top: '80%',opacity: 1}, 800, 'easeOutQuint');
		if ($(this).children('.producthover').length) {
			$(this).children('.producthover').stop(true,true).fadeIn();
		}
	}).live('mouseleave',function() {
		$(this).children('.label').stop().animate({top: '50%',opacity: 0}, 800, 'easeOutQuint');
		if ($(this).children('.producthover').length) {
			$(this).children('.producthover').stop(true,true).hide();
		}
    });
	
	// animate small cart
	$('.items li').live('mouseenter',function(){
		$(this).children('.remove').show();
		$(this).stop().animate({backgroundColor:'#f9f9f9'}, 500);
	}).live('mouseleave',function(){
		$(this).children('.remove').hide();
		$(this).stop().animate({backgroundColor:'#fff'}, 1);
	});
	
	// tooltips
	if (deviceWidth > 800) {
		$('.items li div.remove').tipsy({
			fade: true,
			fallback: '<?php _e('Remove from cart','ocart'); ?>',
			gravity: 'w',
			offset: 4
		});
		$('.tagcloud a').tipsy({
			fade: true,
			gravity: 'n',
			offset: 4
		});
		$('.checkout_form_text').tipsy({
			gravity: 'w',
			trigger: 'focus',
			offset: 8
		});
	}
	
	// remove icon effect
	$('.remove').live('mouseenter',function(){ $(this).css({'background-position':'bottom'}); }).live('mouseout',function(){ $(this).css({'background-position':'top'}); });
	
	// remove item from smallcart
	$('.items li div.remove').live('click',function(e){
		e.preventDefault();
		e.stopPropagation();
		var what_to_remove = $(this).parent();
		var session_id = $(this).attr('id').replace(/[^0-9]/g, '');
		var item_id = $(this).parent().attr('id').replace(/[^0-9]/g, '');
		$.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/remove_item.php',
			type: 'get',
			data: {session_id: session_id},
			beforeSend: function(){
				what_to_remove.after('<li></li>');
				what_to_remove.fadeOut();
			},
			success: function(data){
				$('.cartpopup-inner').html(data);
				$('.ajax_items_count').load('<?php echo get_template_directory_uri(); ?>/ajax/cart_quantity.php');
				$('.tipsy').hide();
				
				$.ajax({
					url: '<?php echo get_template_directory_uri(); ?>/ajax/update_rt_quantity.php?item_id=' + item_id,
					dataType: 'json',
					success: function(data){
						$('.product').attr('name', 'qty-' + data.new_product_quantity);
						$.each(data, function(key, val) {
							$('.product-tax').find('a[data-termID=' + val.term + ']').attr('name', 'qty-' + val.qty);
						});
					}
				});

			}
		});
	});
	
	// toggle the small cart view
	$('.cartpopup').css({'display':'none'});
	$('.cart-link').bind('mouseenter',function(){
		$('.cartpopup').stop(true, true).show().animate({top: 100}, 200).animate({top: 44}, 500);
	});
	
	// open product details
	$('.items li, #similar ul li a, .ajax-search-results a, .t-productname a, .td-item a').live('click',function(e){
		e.preventDefault();
		closeLightbox(id='');
		$('.tipsy').hide();
		$('#similar .wrap').stop().animate({'height': 0}, {duration: 600, complete: function(){
			$('#similar').hide();
			$('#similar .similarWrap').empty();
		}});
		product_id = $(this).attr('id').replace(/[^0-9]/g, '');
		$('.iosSlider').fadeOut('fast');
		getProductAjaxRequest();
	});
	
	force_lightbox = '<?php echo ocart_get_option('force_lightbox'); ?>';
	if (force_lightbox == 0) {
	// open product inline
	$('.prods li').live('click',function(e){
		e.preventDefault();
		closeLightbox(id='');
		$('.tipsy').hide();
		$('#similar .wrap').stop().animate({'height': 0}, {duration: 600, complete: function(){
			$('#similar').hide();
			$('#similar .similarWrap').empty();
		}});
		product_id = $(this).attr('id').replace(/[^0-9]/g, '');
		$('.iosSlider').fadeOut('fast');
		getProductAjaxRequest();
	});
	} else {
	// opening product in lightbox
	$('.prods li').live('click',function(e){
		e.preventDefault();
		product_id = $(this).attr('id').replace(/[^0-9]/g, '');
		lightbox(null, '<?php echo get_template_directory_uri(); ?>/ajax/product_lightbox.php', '', product_id);
	});
	}
	
	// change link by opening product
	$('.prods li').live('click',function(e){
		e.preventDefault();
		link = $(this).attr('rel');
		window.history.pushState(null,null, link);
	});
	
	// change location
	$('.items li').live('click',function(e){
		if ($('#blog').length > 0) {
			href = $(this).attr('rel');
			document.location.href=href;
		}
	});
	
	// close product detail
	$('#closeProductdetail').live('click',function(e){
	
		$('#similar .wrap').stop().animate({'height': 0}, {duration: 600, complete: function(){
			$('#similar').hide();
			$('#similar .similarWrap').empty();
		}});

		$('#details').stop().animate({left: '-200%', opacity: 0}, {duration: 1200, complete:function(){
			$('.iosSlider').fadeIn('slow');
			$('#banner').css({'height': $('.iosSlider').height() });
		}});
		
	});
	
	// back to home
	$('.navi-home, #logo a').live('click',function(e){
	
	$('#similar .wrap').stop().animate({'height': 0}, {duration: 600, complete: function(){
		$('#similar').hide();
		$('#similar .similarWrap').empty();
	}});
	
	if ($('#blog').length == 0) {
	
		e.preventDefault();
		if ($('.iosSlider').is(':visible')) {
			$('.iosSlider').effect("bounce", { times:3, distance: 10 }, 200);
		} else {
			$('#details').stop().animate({left: '-200%', opacity: 0}, {duration: 1200, complete:function(){
				$('.iosSlider').fadeIn('slow');
				$('#banner, #details').css({'height': $('.iosSlider').height() });
			}});
		}
		
		// default product list
		$('#filter').css({'background-position' : '0 0'});
		$('#filter-by').hide();
		$('#browser').load('<?php echo get_template_directory_uri(); ?>/ajax/categories.php');
		$('#filter-by').load('<?php echo get_template_directory_uri(); ?>/ajax/filters.php');
		$('.catalogWrapper').load('<?php echo get_template_directory_uri(); ?>/ajax/catalog.php');
		
	}
		
	});
	
	// product term selection
	$('.product-color a').live('click',function(e){
		e.preventDefault();
		$('.product-color li').removeClass('current');
		$(this).parent().addClass('current');
	});
	$('.product-tax a').live('click',function(e){
		e.preventDefault();
		$(this).parent().parent().parent().find('a').removeClass('current');
		$(this).addClass('current');
	});
	
	// validate quantity change
	$('.btn-quantity').live('change keydown keyup', function(e) {
		var re = /^[1-9]\d*$/;
		var str = $(this).val();
		if (!re.test(str)){ $(this).val(''); }
	});
	
	// add to cart button
	$('.addtocart').live('submit',function(e){
	
		// remove alerts
		e.preventDefault();
		$('.no-options-selected').remove();
		
		// quantity chosen
		var re = /^[1-9]\d*$/;
		var str = $('.btn-quantity').val();
		if (re.test(str)){
			var quantity = parseInt(str);
		} else {
			var quantity = 1;
		}
		
		// check stock quantity levels
		var prod_stock = $('.product').attr('name').replace(/[^0-9]/g, '');
		if (quantity > prod_stock) {
		
			$('.product-var').effect("shake", { times:3, distance: 5 }, 100);
			$('.addtocart').append('<span class="no-options-selected">' + prod_stock + ' <?php _e('item(s) are left only.','ocart'); ?></span>');
			$('.no-options-selected').stop().animate({opacity: 1});
			setTimeout(function(){
				$('.no-options-selected').fadeOut('slow');
			}, 2000);
			
		} else {
		
		// make sure that customer checked product options
		var attr_select = '<?php echo ocart_get_option('attr_select'); ?>';
		
		if (attr_select == 0) {
		
		if($('.product-tax li.current > a, .product-tax li > a.current').size() != $('.product-tax').not('.do-not-count').size()) {
			
			$('.product-var').effect("shake", { times:3, distance: 5 }, 100);
			$('.addtocart').append('<span class="no-options-selected"><?php _e('Please select product options first.','ocart'); ?></span>');
			$('.no-options-selected').stop().animate({opacity: 1});
			setTimeout(function(){
				$('.no-options-selected').fadeOut('slow');
			}, 2000);
		
		} else {
		
		// check now for custom stock levels if found
		canAddglobally = true;
		canAddtoCart = true;
		$('.product-tax li.current > a, .product-tax li > a.current').each(function(i, val){
			var opt_qty = $(this).attr('name').replace(/[^0-9]/g, '');
			if (quantity > opt_qty) {
				$(this).parent().effect("shake", { times:3, distance: 5 }, 100);
				$('.addtocart').append('<span class="no-options-selected">' + opt_qty + ' <?php _e('item(s) are left only.','ocart'); ?></span>');
				$('.no-options-selected').stop().animate({opacity: 1});
				setTimeout(function(){
					$('.no-options-selected').fadeOut('slow');
				}, 2000);
				canAddtoCart = false;
				canAddglobally = false;
			} else {
				canAddtoCart = true;
			}
		});
		
		if (canAddtoCart === true && canAddglobally !== false) {
		
		$.scrollTo('#header', 800);
		
		// variables
		var item_id = $('.product').attr('id').replace(/[^0-9]/g, '');
		var item_name = $('.product-name').html();
		var terms = '';
		$('.product-tax li.current > a, .product-tax li > a.current').each(function(){
			terms = terms + $(this).attr("id") + ":";
		});

		// now user can add item to cart!
		$.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/add_item.php',
			type: 'get',
			data: {item_id: item_id, quantity: quantity, item_name: item_name, terms: terms},
			beforeSend: function(){
				$('.btn-add').attr('disabled', 'disabled');
				$('.cartpopup').hide();
			},
			success: function(data){
				$('.cartpopup-inner').html(data);
				$('.cartpopup').fadeIn(500, function(){
					$('.ajax_items_count').load('<?php echo get_template_directory_uri(); ?>/ajax/cart_quantity.php');
					$('.items li:first').effect("shake", { times:2, distance: 5 }, 200);
				});
				$('.btn-add').removeAttr('disabled');
				$('.btn-quantity').val('Qty');
				
				$.ajax({
					url: '<?php echo get_template_directory_uri(); ?>/ajax/update_rt_quantity.php?item_id=' + item_id,
					dataType: 'json',
					success: function(data){
						$('.product').attr('name', 'qty-' + data.new_product_quantity);
						$.each(data, function(key, val) {
							$('.product-tax').find('a[data-termID=' + val.term + ']').attr('name', 'qty-' + val.qty);
						});
					}
				});
				
			}
		});
		
		// animation
		$('.main-image img:first').clone().attr('class','clonedproduct').appendTo('.main-image').stop(true, true).animate({
			top: 0 - $(this).offset().top + 320,
			left: $(this).offset().left + 30,
			opacity: 0,
			width: 0,
			height: 0
		},800);
		
		} // canAddtoCart
		
		} // did not choose any product options
		
		} else {

		err = false;
		$('.product-tax select').each(function(){
			if ($(this).val() == 0) {
				$('.product-var').effect("shake", { times:3, distance: 5 }, 100);
				$('.addtocart').append('<span class="no-options-selected"><?php _e('Please select product options first.','ocart'); ?></span>');
				$('.no-options-selected').stop().animate({opacity: 1});
				setTimeout(function(){
					$('.no-options-selected').fadeOut('slow');
				}, 2000);
				err = true;
				return false;
			}
		});
		
		// check now for custom stock levels if found
		canAddglobally = true;
		canAddtoCart = true;
		
		if (canAddtoCart === true && canAddglobally !== false && err == false) {
		
		$.scrollTo('#header', 800);
		
		// variables
		var item_id = $('.product').attr('id').replace(/[^0-9]/g, '');
		var item_name = $('.product-name').html();
		var terms = '';
		$('.product-tax select').each(function(){
			terms = terms + $(this).val() + ":"
		});

		// now user can add item to cart!
		$.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/add_item.php',
			type: 'get',
			data: {item_id: item_id, quantity: quantity, item_name: item_name, terms: terms},
			beforeSend: function(){
				$('.btn-add').attr('disabled', 'disabled');
				$('.cartpopup').hide();
			},
			success: function(data){
				$('.cartpopup-inner').html(data);
				$('.cartpopup').fadeIn(500, function(){
					$('.ajax_items_count').load('<?php echo get_template_directory_uri(); ?>/ajax/cart_quantity.php');
					$('.items li:first').effect("shake", { times:2, distance: 5 }, 200);
				});
				$('.btn-add').removeAttr('disabled');
				$('.btn-quantity').val('Qty');
			}
		});
		
		// animation
		$('.main-image img:first').clone().attr('class','clonedproduct').appendTo('.main-image').stop(true, true).animate({
			top: 0 - $(this).offset().top + 320,
			left: $(this).offset().left + 30,
			opacity: 0,
			width: 0,
			height: 0
		},800);
		
		}
		
		} // attr select 1
		
		} // global stock check
		
	});
	
	// add to basket (v2)
	$('.catalog_quickadd span').live('click',function(e){
		e.preventDefault();
		var item_id = $(this).parent().parent().attr('id').replace(/[^0-9]/g, '');
		lightbox(null, '<?php echo get_template_directory_uri(); ?>/ajax/product_lightbox.php', '', item_id);
	});
	
	// submitting login form (in checkout step)
	$('#checkout_form_login').live('submit',function(e){
		e.preventDefault();
		$(this).css({opacity: 0.5});
		$('.checkout_form_submit').attr('disabled','disabled');
		$.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/user_checkout_login.php',
			type: 'post',
			data: $(this).serialize(),
			dataType: 'json',
			success: function(data){
				$('#checkout_form_login .message, #checkout_form_login .status').remove();
				$('#checkout_form_login').css({opacity: 1});
				if (data.error) {
					$('#' + data.validID).focus().parent().append('<span class="status status-success"></span>');
					$('#' + data.errorID).focus().parent().append('<span class="status status-error"></span>');
					$('#checkout_form_login .checkout_form_submit').effect("bounce", { times:2, distance:10 }, 300);
					$('#checkout_form_login').append('<p class="message message-error">' + data.error + '</p>');
					$('#checkout_form_login .message').fadeIn('slow', function(){
						$('.checkout_form_submit').removeAttr('disabled');
					});
				}
				if (data.ok) {
					location.reload();
				}
			}
		});
		return false;
	});
	
	// submitting login form
	$('#form_login').live('submit',function(e){
		e.preventDefault();
		$(this).css({opacity: 0.5});
		$('.form_submit').attr('disabled','disabled');
		$.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/user_login.php',
			type: 'post',
			data: $(this).serialize(),
			dataType: 'json',
			success: function(data){
				$('#form_login .message, #form_login .status').remove();
				$('#form_login').css({opacity: 1});
				if (data.error) {
					$('#' + data.validID).focus().parent().append('<span class="status status-success"></span>');
					$('#' + data.errorID).focus().parent().append('<span class="status status-error"></span>');
					if (deviceWidth > 977) { $('#form_login .form_submit').effect("bounce", { times:2, distance:10 }, 300); }
					$('#form_login').append('<p class="message message-error">' + data.error + '</p>');
					$('#form_login .message').fadeIn('slow', function(){
						$('.form_submit').removeAttr('disabled');
					});
				}
				if (data.ok) {
					location.reload();
				}
			}
		});
		return false;
	});
	
	// submitting register form
	$('#form_register').live('submit',function(e){
		e.preventDefault();
		$(this).css({opacity: 0.5});
		$('.form_submit').attr('disabled','disabled');
		$.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/user_register.php',
			type: 'post',
			data: $(this).serialize(),
			dataType: 'json',
			success: function(data){
				$('#form_register .message, #form_register .status').remove();
				$('#form_register').css({opacity: 1});
				if (data.error) {
					$('#' + data.validID).focus().parent().append('<span class="status status-success"></span>');
					if (deviceWidth > 977) { $('#form_register .form_submit').effect("bounce", { times:2, distance:10 }, 300); }
					$('#form_register').append('<p class="message message-error">' + data.error + '</p>');
					$('#form_register .message').fadeIn('slow', function(){
						$('.form_submit').removeAttr('disabled');
					});
				}
				if (data.ok) {
					$('#form_register').append('<p class="message message-success"><?php _e('Your password will be emailed to you.','ocart'); ?></p>');
					$('#form_register .message').fadeIn('slow', function(){
						$('.form_submit').removeAttr('disabled');
						location.reload();
					});
				}
			}
		});
		return false;
	});
	
	// submitting reset password form
	$('#form_resetpw').live('submit',function(e){
		e.preventDefault();
		$(this).css({opacity: 0.5});
		$('.form_submit').attr('disabled','disabled');
		$.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/user_recover_password.php',
			type: 'post',
			data: $(this).serialize(),
			dataType: 'json',
			success: function(data){
				$('#form_resetpw .message, #form_resetpw .status').remove();
				$('#form_resetpw').css({opacity: 1});
				if (data.error) {
					$('#' + data.errorID).focus().parent().append('<span class="status status-error"></span>');
					$('#form_resetpw .form_submit').effect("bounce", { times:2, distance:10 }, 300);
					$('#form_resetpw').append('<p class="message message-error">' + data.error + '</p>');
					$('#form_resetpw .message').fadeIn('slow', function(){
						$('.form_submit').removeAttr('disabled');
					});
				}
				if (data.ok) {
					$('#form_resetpw').append('<p class="message message-success"><?php _e('Check your e-mail for the confirmation link.','ocart'); ?></p>');
					$('#form_resetpw .message').fadeIn('slow', function(){
						$('.form_submit').removeAttr('disabled');
					});
				}
			}
		});
		return false;
	});

	// animate button style 2
    $('.btnstyle2').live('mouseenter',function() {
		$(this).stop().animate({backgroundColor:'#333'},300);
	}).live('mouseleave',function() {
		$(this).stop().animate({backgroundColor:'#ddd'},100);
    });
	
	// animate button style 1
    $('.btnstyle1').live('mouseenter',function() {
		$(this).stop().animate({backgroundColor:'<?php ocart_skin_data('button_hover_1') ?>'},500);
	}).live('mouseleave',function() {
		$(this).stop().animate({backgroundColor:'<?php ocart_skin_data('active_color') ?>'},300);
    });
	
	// animate button style 3
    $('.btnstyle3').live('mouseenter',function() {
		$(this).stop().animate({backgroundColor:'<?php ocart_skin_data('button_style2_hover') ?>'},500);
	}).live('mouseleave',function() {
		$(this).stop().animate({backgroundColor:'<?php ocart_skin_data('button_style2_color') ?>'},300);
    });
	
	// switching login/register
	$('.registerlink').live('click',function(){
		$(this).parent().parent().slideToggle(400, function(){
			$('.tipsy').hide();
		});
		$('.div-register').slideToggle(400, function(){
			$('.tipsy').hide();
			$('#form_register input:first').focus();
		});
	});
	$('.loginlink').live('click',function(){
		$(this).parent().parent().slideToggle(400, function(){
			$('.tipsy').hide();
		});
		$('.div-login').slideToggle(400, function(){
			$('.tipsy').hide();
			$('#form_login input:first').focus();
		});
	});
	$('.forgot-pw').live('click',function(){
		$(this).parent().parent().parent().parent().slideToggle(400, function(){
			$('.tipsy').hide();
		});
		$('.div-resetpw').slideToggle(400, function(){
			$('.tipsy').hide();
			$('#form_resetpw input:first').focus();
		});
	});
	
	// carousel for store categories
	<?php
	if (ocart_get_option('show_nav_all')) {
		$width = 770;
	} else {
		$width = 900;
	}
	?>
	$('ul.list').carouFredSel({
		width: <?php echo $width; ?>,
		height: 30,
		scroll: 1,
		align: "left",
		auto: false,
		direction: "right",
		prev: "#browser .prev",
		next: "#browser .next"
	});
	
	// effect on product thumbnails
	$('.thumbs a').live('mouseenter',function(){
		if ($(this).hasClass('video')) {
		$(this).children('img').stop().animate({opacity: 0.2});
		} else {
		$(this).stop().animate({opacity: 0.5}, 900);
		}
	}).live('mouseleave',function(){
		if ($(this).hasClass('video')) {
		$(this).children('img').stop().animate({opacity: 1});
		} else {
		$(this).stop().animate({opacity: 1}, 900);
		}
	});
	
	// switch main product images [next]
	$('.nextImage').live('click',function(){
		var $img = $('.main-image .zoom:visible').last(),
			$next = $img.next();
		if (0==$next.length) {
		   $next = $img.siblings().first();
		}
		$img.fadeOut(800);
		$next.fadeIn(800, function(){
				if (deviceWidth > 800) {
				$(this).jqzoom({ preloadText: '<?php _e('Loading...','ocart'); ?>' });
				}
		});
		return false;
	});
	
	// switch main product images [previous]
	$('.prevImage').live('click',function(){
		var $img = $('.main-image .zoom:visible').last(),
			$prev = $img.prev();
		if (0==$prev.length) {
		   $prev = $img.siblings().last();
		}
		$img.fadeOut(800);
		$prev.fadeIn(800, function(){
				if (deviceWidth > 800) {
				$(this).jqzoom({ preloadText: '<?php _e('Loading...','ocart'); ?>' });
				}
		});
		return false;
	});

	// load products in catalog
	if ($('#catalog-noajax').length == 0) {
		$.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/catalog.php',
			type: 'get',
			success: function(data){
				$('.catalogWrapper').html(data);
				$('#filter-by').load('<?php echo get_template_directory_uri(); ?>/ajax/filters.php');
			}
		});
	} else {
		$('#filter-by').load('<?php echo get_template_directory_uri(); ?>/ajax/filters.php');
	}
	
	// breadcrumb jump
	$('.navi-tax').live('click',function(e){
		e.preventDefault();
		$(this).effect("bounce", { direction: 'right', distance:5, times:3 }, 200);
		var taxonomy = $(this).attr('id');
		$.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/catalog.php',
			type: 'get',
			data: {taxonomy: taxonomy},
			success: function(data){
				$('.catalogWrapper').html(data);
				$('#filter-by').load('<?php echo get_template_directory_uri(); ?>/ajax/filters.php');
			}
		});
	});
	
	// close small cart popup
	$('#gotostore').live('click',function(e){
		if ($('#blog').length == 0) {
		e.preventDefault();
		$('.cartpopup').fadeOut();
		}
	});
	
	// hover on cart product
	$('.thecart tr').live('mouseenter',function(){
		$(this).css({'background': '#fbfbfb'});
	}).live('mouseleave',function(){
		$(this).css({'background': '#fff'});
	});

	// change quantity by buttons
	$('.update_q').live('click',function(e){
		
		e.preventDefault();
		var item = $(this).parent().children('input');
		var currentStock = parseInt(item.val());
		if ($(this).hasClass('plus') == true) {
		var new_quantity = parseInt(item.val()) + 1;
		} else {
		var new_quantity = parseInt(item.val()) - 1;
		}
		if (new_quantity < 0) {
			var new_quantity = 0;
		}
		var session_id = $(this).parent().children('input').attr('id').replace(/[^0-9]/g, '');

		$.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/check_stock.php',
			type: 'get',
			dataType: 'json',
			data: {id: $(this).parent().parent().parent().attr('id').replace(/[^0-9]/g, ''), new_quantity: new_quantity},
			success: function(data){
				if (!data.stock) {
					console.log("Error");
					console.log(data.stock);
					item.val(currentStock);
		
				} else {
					console.log("No Error");
					console.log(data.stock);
					item.val(new_quantity).change();
					$.ajax({
						url: '<?php echo get_template_directory_uri(); ?>/ajax/update_item.php',
						type: 'get',
						data: {session_id: session_id, new_quantity: new_quantity},
						success: function(data){
							$('.cartpopup-inner').html(data);
							$('.ajax_items_count').load('<?php echo get_template_directory_uri(); ?>/ajax/cart_quantity.php');
							$('#added_coupons').load('<?php echo get_template_directory_uri(); ?>/ajax/get_coupons.php');
							$('.calc-tax span').load('<?php echo get_template_directory_uri(); ?>/ajax/recalculate_tax.php');
							$('.calc-shipping span').load('<?php echo get_template_directory_uri(); ?>/ajax/recalculate_shipping.php');
							$('.calc-total span').load('<?php echo get_template_directory_uri(); ?>/ajax/recalculate_total.php');
							
							<?php do_action('ocart_ajax_calls_cart_calc'); ?>
							
						}
					});
		
				}
			}
		});
		
	});
	
	// change quantity in manual mode
	$('.item_quantity').live('blur', function(e) {
	
		var this_quantity = $(this);
		
		var new_quantity = $(this).val();
		var session_id = $(this).attr('id').replace(/[^0-9]/g, '');
		
		$.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/check_stock.php',
			type: 'get',
			dataType: 'json',
			data: {id: $(this).parent().parent().parent().attr('id').replace(/[^0-9]/g, ''), new_quantity: new_quantity},
			success: function(data){
				if (data.error) {
					this_quantity.val(data.max);
				} else {
					$.ajax({
						url: '<?php echo get_template_directory_uri(); ?>/ajax/update_item.php',
						type: 'get',
						data: {session_id: session_id, new_quantity: new_quantity},
						success: function(data){
							$('.cartpopup-inner').html(data);
							$('.ajax_items_count').load('<?php echo get_template_directory_uri(); ?>/ajax/cart_quantity.php');
							$('#added_coupons').load('<?php echo get_template_directory_uri(); ?>/ajax/get_coupons.php');
							$('.calc-tax span').load('<?php echo get_template_directory_uri(); ?>/ajax/recalculate_tax.php');
							$('.calc-shipping span').load('<?php echo get_template_directory_uri(); ?>/ajax/recalculate_shipping.php');
							$('.calc-total span').load('<?php echo get_template_directory_uri(); ?>/ajax/recalculate_total.php');
							
								<?php do_action('ocart_ajax_calls_cart_calc'); ?>
								
						}
					});
				}
			}
		});
		
	});
	
	// change prices dynamically
	$('.item_quantity').live('change keyup blur', function(e) {
		var re = /^[0-9]\d*$/;
		var str = $(this).val();
		$(this).val(parseFloat(str));
		if (re.test(str)){
			var price = $(this).parent().parent().prev('td').html();
			var realprice = price.replace(/[^0-9]/g,'');
			$(this).parent().parent().next('td').load('<?php echo get_template_directory_uri(); ?>/ajax/print_value.php?value=' + realprice + '&quantity=' + str);
		} else {
			$(this).parent().parent().next('td').html('<?php echo ocart_format_currency( formatocurrency(0,0,',','.') ); ?>');
			$(this).val(0);
		}
		// update grand total
		$('.calc-total span').load('<?php echo get_template_directory_uri(); ?>/ajax/recalculate_total.php');
	});
	
	// liststyle1 animation
	$('.liststyle1 li').mouseenter(function(){
		$(this).children('.thumb').children('a').stop(true, true).animate({opacity: 0.7}, 600);
	}).mouseleave(function(){
		$(this).children('.thumb').children('a').stop(true, true).animate({opacity: 1}, 600);
	});
	
	// animate featured image in archive
	$('.post').mouseenter(function(){
		$(this).find('img').not('.collection_front_image, .collection_hover_image').stop(true, true).animate({opacity: 0.5}, 600);
	}).mouseleave(function(){
		$(this).find('img').not('.collection_front_image, .collection_hover_image').stop(true, true).animate({opacity: 1}, 600);
	});
	
	// blog store button
	$('.blog_store').mouseenter(function(){
		$(this).stop(true, true).animate({backgroundColor: '#fff'}, 300);
	}).mouseleave(function(){
		$(this).stop(true, true).animate({backgroundColor: '<?php ocart_skin_data('active_color') ?>'}, 800);
	});
	
	// list animation in tab content
	$('.tabcontent ul li').mouseenter(function(){
		$(this).css({'background': '#f9f9f9 url(<?php echo get_template_directory_uri(); ?><?php if (isset($ocart['skin']) && $ocart['skin'] != 'default') { echo '/skins/'.$ocart['skin'].'/'; } else { echo '/skins/default/'; } ?>bullet-hover.png) no-repeat 20px 20px'});
	}).mouseleave(function(){
		$(this).css({'background': '#fff url(<?php echo get_template_directory_uri(); ?><?php if (isset($ocart['skin']) && $ocart['skin'] != 'default') { echo '/skins/'.$ocart['skin'].'/'; } else { echo '/skins/default/'; } ?>bullet.png) no-repeat 20px 20px'});
	});
	
	// list animation in normal list
	$('.widget:not(.oc_tabs,.oc_latestblogs,.oc_twitter) ul li').mouseenter(function(){
		$(this).css({'background': 'url(<?php echo get_template_directory_uri(); ?><?php if (isset($ocart['skin']) && $ocart['skin'] != 'default') { echo '/skins/'.$ocart['skin'].'/'; } else { echo '/skins/default/'; } ?>bullet-hover.png) no-repeat left 20px'});
	}).mouseleave(function(){
		$(this).css({'background': 'url(<?php echo get_template_directory_uri(); ?><?php if (isset($ocart['skin']) && $ocart['skin'] != 'default') { echo '/skins/'.$ocart['skin'].'/'; } else { echo '/skins/default/'; } ?>bullet.png) no-repeat left 20px'});
	});
	
	// jquery tabs
	$('.tabs').fptabs('.tabcontent');
	
	// open the filters
	$('#filter').mouseenter(function(){ if ($('#filter-by').is(':hidden')) { $(this).css({'background-position' : '0 -36px'}); } });
	$('#filter').mouseleave(function(){ if ($('#filter-by').is(':hidden')) { $(this).css({'background-position' : '0 0'}); } });
	$('#filter').live('click',function(){
		if ($('#filter-by').is(':hidden')) {
			$(this).css({'background-position' : '0 -72px'});
			$('#filter-by').fadeIn();
		} else {
			$(this).css({'background-position' : '0 -36px'});
			$('#filter-by').fadeOut();
		}
	});
	
	// filter / sort
	$('.tax-parent').live('click',function(e){
		e.preventDefault();
		var dropd = $(this).next('ul');
		if (dropd.is(':visible')) {
			dropd.fadeOut();
			$(this).css({'background-position':'right top'});
		} else {
			dropd.fadeIn();
			$(this).css({'background-position':'right bottom'});
		}
	});
	$('.tax > ul').live('mouseleave',function(){
		$(this).children('li').children('ul').fadeOut();
		$(this).find('.tax-parent').css({'background-position':'right top'});
	});
	
	// reset filters
	$('#resetfilters').live('click',function(e){
		$('#filter-by').load('<?php echo get_template_directory_uri(); ?>/ajax/filters.php');
		$('.catalogWrapper').load('<?php echo get_template_directory_uri(); ?>/ajax/catalog.php');
	});
	
	// update catalog by filters
	$('#filter-by li ul a').live('click',function(e){
		e.preventDefault();

		// toggle current class
		if ($(this).hasClass('current')) {
			$(this).removeClass('current');
		} else {
			$(this).addClass('current');
		}
		if (!$(this).attr('rel')) { // regular link
			$(this).closest('.tax-parent-li').find('ul li:first a').removeClass('current');
		} else {
			$(this).closest('.tax-parent-li').find('a').removeClass('current');
			$(this).closest('.tax-parent-li').find('ul li:first a').addClass('current');
		}
		
		// custom field parameter
		if ($(this).parent().parent().attr('rel') == 'custom_fields' || $(this).parent().parent().attr('rel') == 'sort_by') {
			$(this).closest('.tax-parent-li').find('a').removeClass('current');
			$(this).addClass('current');
		}
		
		// selected items
		var sortfield = '';
		var cfield = '';
		var terms_ids = '';
		var taxonomies = '';
		$('#filter-by li ul a.current').each( function() {
			if ($(this).parent().attr('class')) {
			terms_ids = terms_ids + $(this).parent().attr('class').replace(/[^0-9]/g, '') + ',';
			taxonomies = taxonomies + $(this).closest('.tax-parent-li').find('ul li:first a').attr('rel').replace(/default_/, '') + ',';
			}
			if ($(this).parent().parent().attr('rel') == 'custom_fields') {
			cfield = $(this).attr('id');
			}
			if ($(this).parent().parent().attr('rel') == 'sort_by') {
			sortfield = $(this).attr('id');
			}
		});
		
		// ajax request
		$.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/catalog.php',
			type: 'get',
			data: {taxonomies: taxonomies, terms_ids: terms_ids, cfield: cfield, sortfield: sortfield, min_price: $('#min_price').val(), max_price: $('#max_price').val(), use_saved_query: true},
			beforeSend: function() {
				$('#filter-by h2').append('<span class="loading"><?php _e('Updating...','ocart'); ?><img src="<?php echo get_template_directory_uri(); ?>/img/loading.gif" alt="" /></span>');
			},
			success: function(data){
				$('#filter-by h2 span.loading').remove();
				$('.catalogWrapper').html(data);
			}
		});
		
	});
	
	// auto update on price range changes
	$('#min_price, #max_price').live('change',function(){
		$('#filter-by li ul a:first').trigger('click');
	});
	
	// billing/address same check
	$('#cform_ub').click(function(){
		if ($('#cform_ub').is(':checked')) {
			// visual: mark fields as disabled
			$(this).parent().parent().parent().children('p').not('.chkbox').fadeTo(1, 0.5);
			$(this).parent().parent().parent().children('p').not('.chkbox').children('input, select').attr('disabled','disabled');
			// remove any error msgs
			$('.cform fieldset:eq(2) span.errorfield').remove();
			// copy values from billing
			$('.cform fieldset:eq(1) input[type=text]').each(function(){
				var fieldvalue = $(this).val();
				var thisfield = $(this).attr('id');
				$('.cform fieldset:eq(2) input[type=text]#' + thisfield + '2').val(fieldvalue);
			});
			$('.cform fieldset:eq(1) select').each(function(){
				var fieldvalue = $(this).val();
				var thisfield = $(this).attr('id');
				$('.cform fieldset:eq(2) select#' + thisfield + '2').val(fieldvalue);
				if ($('.cform fieldset:eq(2) select#' + thisfield + '2').val() != fieldvalue) {
					$('span.errorfield').remove();
					$('.cform fieldset:eq(2) select#' + thisfield + '2').parent().fadeTo(1, 1);
					$('.cform fieldset:eq(2) select#' + thisfield + '2').removeAttr('disabled');
					$('.cform fieldset:eq(2) select#' + thisfield + '2').after('<span class="errorfield"><?php _e('We do not ship to this destination yet.','ocart'); ?></span>').focus();
				}
			});
		} else {
			// visual: mark fields as not disabled
			$(this).parent().parent().parent().children('p').not('.chkbox').fadeTo(1, 1);
			$(this).parent().parent().parent().children('p').not('.chkbox').children('input, select').removeAttr('disabled');
		}
	});
	
	// detect billing fields change when cform_ub is checked
	$('.cform fieldset:eq(1) input[type=text]').change(function(){
		if ($('#cform_ub').is(':checked')) {
			var fieldvalue = $(this).val();
			var thisfield = $(this).attr('id');
			$('.cform fieldset:eq(2) input[type=text]#' + thisfield + '2').val(fieldvalue);
		}
	});
	
	// detect billing fields change when cform_ub is checked
	$('.cform fieldset:eq(1) select').change(function(){
		if ($('#cform_ub').is(':checked')) {
			var fieldvalue = $(this).val();
			var thisfield = $(this).attr('id');
			$('.cform fieldset:eq(2) select#' + thisfield + '2').val(fieldvalue);
		}
	});
	
	// update user pic on saving email on checkout
	$('#cform_email').change(function(){
		$.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/validate_email.php',
			type: 'post',
			data: {email: $('#cform_email').val()},
			dataType: 'json',
			success: function(data){
				$('.cform span.email').remove();
				$('#'+ data.field).focus().after('<span class="email">' + data.msg + '</span>');
				$('.cform_email label').html(data.gravatar);
			}
		});
	});
	
	// capture data-fee
	function get_additional_charges() {
		add_charge = 0;
		$('input[type=radio]:checked').each(function(){
			add_charge += parseFloat($(this).attr('data-fee'));
		});
		return add_charge;
	}
	
	// capture data-fee
	function get_additional_charges_shipping() {
		add_charge = 0;
		$('#radio_shipping_options input[type=radio]:checked').each(function(){
			add_charge += parseFloat($(this).attr('data-fee'));
		});
		return add_charge;
	}
	
	// add shipping label price
	$('#radio_shipping_options input[type=radio]').click(function(){
		$('#shipping_fee').load('<?php echo get_template_directory_uri(); ?>/ajax/recalculate_shipping.php?add=' + get_additional_charges_shipping());
		$('#order_total').load('<?php echo get_template_directory_uri(); ?>/ajax/recalculate_total.php?add=' + get_additional_charges(), function(){
			$('.checkout_total span').effect("bounce", { times:2 }, 400);
		});
	});
	
	// add payment option price
	$('#radio_payment_options input[type=radio]').click(function(){
		$('#order_total').load('<?php echo get_template_directory_uri(); ?>/ajax/recalculate_total.php?add=' + get_additional_charges(), function(){
			$('.checkout_total span').effect("bounce", { times:2 }, 400);
		});
	});
	
	// submitting order form details
	$('#cform').submit(function(e){
		e.preventDefault();
		$('input#cform_gross_total').val($('#order_total').html());
		
		// check for agreement
		if ($(this).find('.chkbox_terms').length) {
			if (!$('.chkbox_terms').find('input[type=checkbox]').is(':checked')) {
				$('#terms_must_check').remove();
				$('#cform_agreement').parent().parent().after('<span class="errorfield3" id="terms_must_check"><?php _e('Please accept terms and conditions before placing your order.','ocart'); ?></span>');
				return false;
			}
		}
		
		$.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/submit_order.php',
			type: 'post',
			data: $(this).serialize(),
			dataType: 'json',
			beforeSend: function(){
				$('.submit input').css({opacity: 0.5});
				$('.submit input').attr('disabled','disabled');
				$('.preorder').html('<img src="<?php echo get_template_directory_uri(); ?>/img/loading.gif" alt="" />');
			},
			success: function(data){
				$('.cform span.email, .cform span.errorfield, .cform span.errorfield2, .cform span.errorfield3').remove();
				var count = 0;
				if (data.fields) {
					$('.submit input').css({opacity: 1});
					$('.submit input').removeAttr('disabled');
					$('.preorder').html('<?php _e('Please confirm your billing and shipping details before placing your order.','ocart'); ?>');
				$.each(data.fields, function(i, msg) {
					count++;
					if (count == 1) { $('#'+ i).focus(); }
					if (i == 'cform_email') {
					$('#'+ i).after('<span class="email">' + msg + '</span>');
					} else {
					$('#'+ i).after('<span class="errorfield">' + msg + '</span>');
					}
				});
				} else if (data.custom_error) {
					$('.submit input').css({opacity: 1});
					$('.submit input').removeAttr('disabled');
					$('.preorder').html('<?php _e('Please confirm your billing and shipping details before placing your order.','ocart'); ?>');
					$('#' + data.custom_error_field).after('<span class="errorfield2">' + data.custom_error + '</span>');
					$.scrollTo('#' + data.custom_error_field, 800);
				} else {
					$('#cform').slideToggle();
					$('.checkout_process').load('<?php echo get_template_directory_uri(); ?>/ajax/paymentredirection.php?orderID=' + data.order_id + '&paymentgateway=' + data.order_pay);
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(xhr.status);
				alert(thrownError);
			}
		});
	});
	
	// updating account settings
	$('#updateinfo').submit(function(e){
		e.preventDefault();
		$.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/account_change.php',
			type: 'post',
			data: $(this).serialize(),
			dataType: 'json',
			beforeSend: function(){
			},
			success: function(data){
				$('#updateinfo span').not('.help').remove();
				if (data.fields) {
				var count = 0;
				$.each(data.fields, function(i, msg) {
					count++;
					if (count == 1) { $('#'+ i).focus(); }
					$('#'+ i).after('<span class="errorfield">' + msg + '</span>');
				});
				} else if (data.success) {
					$('#updateinfo input[type=password]').val('');
					$('#updateinfo input[type=submit]').before('<span class="successfield">' + data.success + '</span>');
				} else {
					$('#updateinfo input[type=password]').val('');
					$('#updateinfo input[type=submit]').before('<span class="emptyfield">' + data.empty + '</span>');
				}
				$('#footer').css({position: 'relative',top:'auto'});
			}
		});
	});
	
	// footer css fix
	$('#footer .footer_menu a:last').css({'background': 'none'});
	
	// footer widgets fix
	if (deviceWidth > 977) {
		$('.section:last').css({'width':'197px','margin-right':0});
	} else {
		$('.section:last').css({'margin': 0});
	}
	
	// submitting contact form
	$('#contactform').live('submit',function(e){
		e.preventDefault();
		$('#contactform span.contact_result').remove();
		$('#contactform input[type=submit]').after('<img src="<?php echo get_template_directory_uri(); ?>/img/loading.gif" alt="" />');
		$.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/contactform.php',
			type: 'post',
			data: $(this).serialize(),
			dataType: 'json',
			beforeSend: function(){
			},
			success: function(data){
				$('#contactform img').remove();
				if (data.fields) {
					var count = 0;
					$.each(data.fields, function(i, msg) {
						count++;
						if (count == 1) { $('#'+ i).focus(); }
					});
				} else if (data.thankyou) {
					$('#contactform').append('<span class="contact_result">' + data.thankyou + '</span>');
				}
			}
		});
	});
	
	// apply coupon code
	$('.coupon').live('submit',function(e){
		e.preventDefault();
		$('.coupon-error, .coupon-discount').remove();
		$('.coupon img').show();
		$.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/apply_coupon.php',
			type: 'post',
			data: $(this).serialize(),
			dataType: 'json',
			success: function(data){
				$('.coupon img').hide();
				if (data.err) {
					$('.coupon').append('<div class="coupon-error">' + data.err + '</div><div class="clear"></div>');
				} else {
					$('#coupon_code').val('');
				}
				if (data.discount) {
					$('.coupon').append('<div class="coupon-discount">' + data.discount + '</div><div class="clear"></div>');
				}
				if (data.new_total) {
					$('.calc-total span').load('<?php echo get_template_directory_uri(); ?>/ajax/recalculate_total.php');
					$('.calc-shipping').children('span').html('<?php _e('FREE!','ocart'); ?>');
				}
				if (data.new_subtotal) {
					$('.calc-total span').load('<?php echo get_template_directory_uri(); ?>/ajax/recalculate_total.php');
					$('#added_coupons').load('<?php echo get_template_directory_uri(); ?>/ajax/get_coupons.php');
				}
			}
		});
	});
	
	// product tabs (new feature)
	$('.infotabs li a:first').css({'border-radius' : '5px 0 0 0'});
	
	// when tab is clicked
	$('.infotabs a').live('click',function(e){
		
		if ($(this).attr('rel') != 'tab_video') {
		e.preventDefault();
		}
		if ($(this).attr('rel') == 'tab_video' && $(this).hasClass('current')) {
			e.preventDefault();
		}
		
		if ($(this).hasClass('current') && $(this).attr('rel') != 'tab_content') {
		
			$('.infotabs a').removeClass('current');
			$('.infotabs a:first').addClass('current');
			$('.infotab').html('<div class="infotab_div_default"></div>');
			var productID = $('.product').attr('id').replace(/[^0-9]/g, '');
			$.ajax({
				url: '<?php echo get_template_directory_uri(); ?>/ajax/short_excerpt.php?id=' + productID,
				type: 'get',
				success: function(data){
					$('.infotab_div_default').html(data);
					$('.infotab').append('<a href="#readmore" class="togglemore"><?php _e('Read More','ocart'); ?></a>');
				}
			});
			
		} else if ($(this).hasClass('current') && $(this).attr('rel') == 'tab_content') {

			if ($('.infotab_div_default').is(':visible')) {
			
				$('.infotabs a').removeClass('current');
				var rel = $(this).attr('rel');
				var productID = $('.product').attr('id').replace(/[^0-9]/g, '');
				var activetab = $(this);
				$('.infotab').html('<div class="infotab_div" />');
				$('.infotab_div').stop().animate({'height' : '200px'}, function() {
					$('.infotab_div').addClass('loader');
					$('.infotab_div').load('<?php echo get_template_directory_uri(); ?>/ajax/inline_content.php?id=' + productID + '&tab=' + rel, function(){
						$('.infotab_div').removeClass('loader');
						$('.infotab').append('<a href="#closetab" class="closetab"><?php _e('Back','ocart'); ?></a>').fadeIn();
						activetab.addClass('current');
						$('.infotab_div').mCustomScrollbar();
					});
				});

			} else {
			
				$('.infotabs a').removeClass('current');
				$('.infotabs a:first').addClass('current');
				$('.infotab').html('<div class="infotab_div_default"></div>');
				var productID = $('.product').attr('id').replace(/[^0-9]/g, '');
				$.ajax({
					url: '<?php echo get_template_directory_uri(); ?>/ajax/short_excerpt.php?id=' + productID,
					type: 'get',
					success: function(data){
						$('.infotab_div_default').html(data);
						$('.infotab').append('<a href="#readmore" class="togglemore"><?php _e('Read More','ocart'); ?></a>');
					}
				});
			
			}
		
		} else {
		
		$('.infotabs a').removeClass('current');
		var rel = $(this).attr('rel');
		var productID = $('.product').attr('id').replace(/[^0-9]/g, '');
		var activetab = $(this);
		$('.infotab').html('<div class="infotab_div" />');
		$('.infotab_div').stop().animate({'height' : '200px'}, function() {
			$('.infotab_div').addClass('loader');
			$('.infotab_div').load('<?php echo get_template_directory_uri(); ?>/ajax/inline_content.php?id=' + productID + '&tab=' + rel, function(){
				$('.infotab_div').removeClass('loader');
				$('.infotab').append('<a href="#closetab" class="closetab"><?php _e('Back','ocart'); ?></a>').fadeIn();
				activetab.addClass('current');
				$('.infotab_div').mCustomScrollbar();
			});
		});
		
		}
		
	});
	
	// when rate is clicked
	$('#rate_product').live('click',function(e){
		var productID = $('.product').attr('id').replace(/[^0-9]/g, '');
		$('.infotabs a').removeClass('current');
		$('.infotab').html('<div class="infotab_div" />');
		$('.infotab_div').stop().animate({'height' : '200px'}, function() {
			$('.infotab_div').addClass('loader');
			$('.infotab_div').load('<?php echo get_template_directory_uri(); ?>/ajax/inline_content.php?id=' + productID + '&tab=tab_reviews', function(){
				$('.infotab_div').removeClass('loader');
				$('.infotab').append('<a href="#closetab" class="closetab"><?php _e('Back','ocart'); ?></a>').fadeIn();
				$('.infotabs a:last').addClass('current');
				$('.infotab_div').mCustomScrollbar();
			});
		});
	});
	
	// when read more is clicked
	$('.togglemore').live('click',function(e){
			var rel = $('.infotabs a:first').attr('rel');
			var productID = $('.product').attr('id').replace(/[^0-9]/g, '');
			var activetab = $(this);
			$('.infotab').html('<div class="infotab_div" />');
			$('.infotab_div').stop().animate({'height' : '200px'}, function() {
				$('.infotab_div').addClass('loader');
				$('.infotab_div').load('<?php echo get_template_directory_uri(); ?>/ajax/inline_content.php?id=' + productID + '&tab=' + rel, function(){
					$('.infotab_div').removeClass('loader');
					$('.infotab').append('<a href="#closetab" class="closetab"><?php _e('Back','ocart'); ?></a>').fadeIn();
					$('.infotab_div').mCustomScrollbar();
				});
			});
	});
	
	// when close tab is clicked
	$('.closetab').live('click',function(e){
		$('.infotabs a').removeClass('current');
		$('.infotabs a:first').addClass('current');
		$('.infotab').html('<div class="infotab_div_default"></div>');
		var productID = $('.product').attr('id').replace(/[^0-9]/g, '');
		$.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/short_excerpt.php?id=' + productID,
			type: 'get',
			success: function(data){
				$('.infotab_div_default').html(data);
				$('.infotab').append('<a href="#readmore" class="togglemore"><?php _e('Read More','ocart'); ?></a>');
			}
		});
	});
	
	// submit a review
	$('#reviewform').live('submit',function(e){
		e.preventDefault();
		$.ajax({
				type: 'post',
				dataType: 'json',
				url: '<?php echo get_template_directory_uri(); ?>/ajax/submit_review.php',
				data: $(this).serialize(),
				beforeSend: function(){
					$('#reviewform').css({opacity: 0.5});
					$('#reviewform input[type=submit]').attr('disabled','disabled');
				},
				success: function(data){
					$('#reviewform').stop().animate({'opacity':1});
					$('#reviewform input[type=submit]').removeAttr('disabled');
					$('#reviewform input, #reviewform textarea').not('#review_submit').css({'border-color':'#e5e5e5'});
					if (data.error) {
						$('#'+data.error).css({'border-color':'red'});
						$('#'+data.error).focus();
						if (data.error == 'comment') {
						$('.infotab_div').mCustomScrollbar("scrollTo", '#comment');
						} else {
						$('.infotab_div').mCustomScrollbar("scrollTo", 'first');
						}
					} else if (data.error_rate) {
						$('.rating-title span').remove();
						$('.rating-title').append('<span>' + data.error_rate + '</span>');
						$('.infotab_div').mCustomScrollbar("scrollTo", '.rating-title');
					} else {
						// update reviews count
						$('.ajax_reviews_count').html(data.count_of_reviews);
						// on success: reload reviews
						var productID = $('.product').attr('id').replace(/[^0-9]/g, '');
						$('.infotab_div').empty().addClass('loader');
						$('.infotab_div').load('<?php echo get_template_directory_uri(); ?>/ajax/inline_content.php?id=' + productID + '&tab=tab_reviews', function(){
								$('.infotab_div').removeClass('loader');
								$('.infotab').append('<a href="#closetab" class="closetab"><?php _e('Back','ocart'); ?></a>').fadeIn();
								$('.infotab_div').mCustomScrollbar();
						});
					}
				}
		});
	});
	
	// rating UI
    $('.ratings_stars').live('mouseenter', function() {
		$('.rating').removeClass('rated');
		$(this).prevAll().andSelf().addClass('ratings_over');
		$(this).nextAll().removeClass('ratings_over');
	});
	$('.ratings_stars').live('mouseout', function() {
		if ($('.rating').hasClass('rated') == false) {
		$(this).prevAll().andSelf().removeClass('ratings_over');
		}
    });
	
    $('.ratings_stars').live('click', function() {
		$(this).prevAll().andSelf().addClass('ratings_over');
		$(this).nextAll().removeClass('ratings_over');
		$('.rating').addClass('rated');
		// save the vote
		$('#rating').val($(this).attr('id').replace(/[^0-9]/g, ''));
	});
	
	// toggle review form
	$('#toggle_review a').live('click',function(){
		$('.toggled_review_form').slideToggle('slow', function(){
			$('.infotab_div').mCustomScrollbar("update");
		});
	});
	
	// load initial results
	if ($('.catalog').length > 0) { // Run the ajax request only when .catalog exists!
		canScroll = false;
		$("body").prepend("<div id='loading-results' style='display:none;'></div>");
		$('#loading-results').center().show();
		$('.catalog').css({opacity: 0.2});

		$.ajax({
			type: 'post',
			url: '<?php echo get_template_directory_uri(); ?>/ajax/showmore.php',
			data: {offset: 0},
			success: function(res) {
				// add initial products
				$(".catalog_list").append(res);
				// enable scroll again
				canScroll = true;
				// remove loader
				$('#loading-results').remove();
				$('.catalog').css({opacity: 1});
			},
			error: function(xhr, ajaxOptions, thrownError) {
				 alert(thrownError);
			}
		});
	}
	
	// back to top button
	<?php if (ocart_get_option('show_backtotop')) { ?>
	$(window).scroll(function() {
		if($(this).scrollTop() != 0) {
			$('#toTop').fadeIn();	
		} else {
			$('#toTop').fadeOut();
		}
	});
	$('#toTop').click(function() {
		$('body,html').animate({scrollTop:0},800);
	});
	<?php } ?>
	
	// scroll bar to limit long lists
	<?php
	if (isset($ocart['scroll_attr']) && is_array($ocart['scroll_attr'])) {
		foreach ($ocart['scroll_attr'] as $key) {
	?>
	$('ul.root-<?php echo $key; ?>').mCustomScrollbar();
	<?php
		}
	}
	?>
	
	// add toggle filters to parents
	$('.filter ul li').each(function(){
		if ($(this).children('ul').length) {
			$(this).children('a').after('<span class="filter_e">[+]</span>');
		}
	});
	
	// expand filter
	$('.filter_e').live('click',function(){
		if ($(this).parent().find('ul.children').is(':hidden')) {
		$(this).parent().find('ul.children').slideToggle();
		$(this).html('[–]');
		} else {
		$(this).parent().find('ul.children').slideToggle();
		$(this).html('[+]');
		}
	});
	
	// load items based on left filters
	$('.filter ul a').live('click',function(e){
		
		e.preventDefault();

		// mark 'selected' or 'unselected'
		if ($(this).hasClass('selected')) {
		$(this).removeClass('selected');
		} else {
		$(this).addClass('selected');
		}
		
		// mark unselected from main menu
		$('#supermenu li#' + $(this).attr('id')).removeClass('current-menu-item');
		
		// mark parent unselected if this is child
		if ($(this).parent().parent().hasClass('children')) {
			$(this).parent().parent().parent().children('a').removeClass('selected');
		}
		
		// get active taxonomies
		var taxonomies = '';
		$('.filter ul a.selected').each( function() {
			taxonomies = taxonomies + $(this).attr('id') + ',';
		});
		
			pricemin = $('.text_min ins').html();
			pricemax = $('.text_max ins').html();
		
		// load results and change title
		canScroll = false;
		$("body").prepend("<div id='loading-results' style='display:none;'></div>");
		$('#loading-results').center().show();
		$('.catalog').css({opacity: 0.2});
		$('.catalog_title').load('<?php echo get_template_directory_uri(); ?>/ajax/catalog_title.php?pricemin=' + pricemin + '&pricemax=' + pricemax + '&taxonomies=' + taxonomies);
		
		// show more
		$.ajax({
				type: 'post',
				url: '<?php echo get_template_directory_uri(); ?>/ajax/showmore.php',
				data: {pricemin: pricemin, pricemax: pricemax, taxonomies: taxonomies, offset: 0},
				success: function(res) {
					// add results
					$(".catalog_list").html(res);
					// enable scroll again
					canScroll = true;
					// remove loader
					$('#loading-results').remove();
					$('.catalog').css({opacity: 1});
				}
		});
		
	});
	
	// clear filters
	$('.header a').live('click',function(e){
		
		e.preventDefault();
		
		// clear selection of box
		$(this).parent().next('ul').find('a').removeClass('selected');
		
		// is it price reset
		if ($(this).parent().attr('id') == 'price_range_slider' ) {
			$( ".text_min ins" ).html( 0 );
			$( ".text_max ins" ).html( '<?php echo ocart_show_price_plain( ocart_max_price() ); ?>' );
			var pslider = $("#slider-range");
			pslider.slider("values", 0, 0);
			pslider.slider("values", 1, '<?php echo ocart_show_price_plain( ocart_max_price() ); ?>');
		} else {
		
		// get active taxonomies
		var taxonomies = '';
		$('.filter ul a.selected').each( function() {
			taxonomies = taxonomies + $(this).attr('id') + ',';
		});
		
			pricemin = $('.text_min ins').html();
			pricemax = $('.text_max ins').html();
		
		// load results and change title
		canScroll = false;
		$("body").prepend("<div id='loading-results' style='display:none;'></div>");
		$('#loading-results').center().show();
		$('.catalog').css({opacity: 0.2});
		$('.catalog_title').load('<?php echo get_template_directory_uri(); ?>/ajax/catalog_title.php?pricemin=' + pricemin + '&pricemax=' + pricemax + '&taxonomies=' + taxonomies);
		
		// show more
		$.ajax({
				type: 'post',
				url: '<?php echo get_template_directory_uri(); ?>/ajax/showmore.php',
				data: {pricemin: pricemin, pricemax: pricemax, taxonomies: taxonomies, offset: 0},
				success: function(res) {
					// add results
					$(".catalog_list").html(res);
					// enable scroll again
					canScroll = true;
					// remove loader
					$('#loading-results').remove();
					$('.catalog').css({opacity: 1});
				}
		});
		
		} // run this only if user is not resetting price
		
	});
	
	// remove active filter
	$('.active_filter').live('click',function(e){
		term_id = $(this).attr('rel');
		$(this).fadeOut('slow');
		$('.filter ul a#' + term_id).removeClass('selected');
		
		// get active taxonomies
		var taxonomies = '';
		$('.filter ul a.selected').each( function() {
			taxonomies = taxonomies + $(this).attr('id') + ',';
		});
		
			pricemin = $('.text_min ins').html();
			pricemax = $('.text_max ins').html();
		
		// load results and change title
		canScroll = false;
		$("body").prepend("<div id='loading-results' style='display:none;'></div>");
		$('#loading-results').center().show();
		$('.catalog').css({opacity: 0.2});
		$('.catalog_title').load('<?php echo get_template_directory_uri(); ?>/ajax/catalog_title.php?pricemin=' + pricemin + '&pricemax=' + pricemax + '&taxonomies=' + taxonomies);
		
		// show more
		$.ajax({
				type: 'post',
				url: '<?php echo get_template_directory_uri(); ?>/ajax/showmore.php',
				data: {pricemin: pricemin, pricemax: pricemax, taxonomies: taxonomies, offset: 0},
				success: function(res) {
					// add results
					$(".catalog_list").html(res);
					// enable scroll again
					canScroll = true;
					// remove loader
					$('#loading-results').remove();
					$('.catalog').css({opacity: 1});
				}
		});

	});
	
	// list grid view
	$('#switchToGrid').live('click',function(e){
		jQuery.cookies.set('layout', 'grid');
		location.reload();
	});
	
	// list slider view
	$('#switchToSlider').live('click',function(e){
		jQuery.cookies.set('layout', 'slider');
		location.reload();
	});
	
	// product color / change image
	$('.product-color ul a').live('click',function(){
		var rel = $(this).attr('rel');
		var currentID = $('.main-image .zoom:visible').attr('id');
		if (rel && rel !== currentID && rel != 'video') {
			$('.main-image .zoom').fadeOut(800);
			$(".main-image .zoom[id='" + rel + "']").fadeIn(800, function(){
				if (deviceWidth > 766) {
					$(this).jqzoom({ preloadText: '<?php _e('Loading...','ocart'); ?>' });
				}
			});
		}
	});
	
	// flash price when option is selected
	$('.product-tax ul a').live('click',function(){
		var pricenow = $(this).parent().parent().parent().parent().parent().parent().find('.price-now');
		var changes = '';
		$('.product-tax li.current > a, .product-tax li > a.current').each(function(){
			changes = changes + $(this).attr("data-change") + ":"
		});
		pricenow.load('<?php echo get_template_directory_uri(); ?>/ajax/adjust_product_price.php?product_id=' + $('.product').attr('id').replace(/[^0-9]/g, '') + '&change=' + changes,function(){
			pricenow.effect("bounce", { times:2 }, 400);
		});
	});
	
	// flash price when option is selected (dropdown)
	$('.product-tax select').live('change',function(){
		var pricenow = $(this).parent().parent().parent().parent().parent().parent().find('.price-now');
		var changes = '';
		$('.product-tax select option:selected').each(function(){
			changes = changes + $(this).attr("data-change") + ":"
		});
		pricenow.load('<?php echo get_template_directory_uri(); ?>/ajax/adjust_product_price.php?product_id=' + $('.product').attr('id').replace(/[^0-9]/g, '') + '&change=' + changes,function(){
			pricenow.effect("bounce", { times:2 }, 400);
		});
	});
	
	// change URL dynamically
	$('.list a, .navi a, #similar ul li a, .ajax-search-results a, .filter ul a').live('click',function(e){
		e.preventDefault();
		link = $(this).attr('href');
		window.history.pushState(null,null, link);
	});
	$('#logo a').live('click',function(e){
		if ($('#blog').length == 0) {
		e.preventDefault();
		link = $(this).attr('href');
		window.history.pushState(null,null, link);
		}
	});
	
	// focus on product search
	$('#productSearch').live('focus',function(){
		$(this).stop().animate({'width':'200px'}, 600);
		var searchterm = encodeURIComponent($('#productSearch').val());
		if (searchterm != '') {
			$('.ajax-search-results').show();
			$('.ajax-search-results').load('<?php echo get_template_directory_uri(); ?>/ajax/search.php?type=product&s=' + searchterm);
		}
	});
	
	// auto complete / ajax results
	var timer;
	$(document).on('keyup', '#productSearch',function(){
		clearTimeout(timer);
		timer = setTimeout(function() {
			var searchterm = encodeURIComponent($('#productSearch').val());
			$('.ajax-search-results').show().load('<?php echo get_template_directory_uri(); ?>/ajax/search.php?type=product&s=' + searchterm);
		}, 300);
	});
	
	// init similar products
	$('.recommend-btn').live('click',function(){
		if ($('#similar').is(':hidden')) {
			$(this).addClass('clicked');
			$('#similar').show();
			$('#similar .wrap').animate({'height':'200px'}, {duration: 600, complete: function(){
				$('#similar .similarWrap').addClass('loadsimilar');
				$('#similar .similarWrap').load('<?php echo get_template_directory_uri(); ?>/ajax/similar.php?product=' + $('.product').attr('id').replace(/[^0-9]/g, ''), function(){
					$('#similar .similarWrap').removeClass('loadsimilar');
					$('.tooltip').tipsy({
						gravity: 'n',
						trigger: 'hover',
						fade:  true,
						offset: 8
					});
					// init carousel
					$('#similar ul').carouFredSel({
						width: '100%',
						height: 200,
						items: {
							visible: 4
						},
						scroll: 1,
						align: 'center',
						auto: false,
						direction: "right",
						prev: {
							button: '.sprevItem'
						},
						next: {
							button: '.snextItem'
						}
					});
				});
			}});
		} else {
			$('#similar .wrap').animate({'height':0}, {duration: 600, complete: function(){
				$('.recommend-btn').removeClass('clicked');
				$('#similar').hide();
				$('#similar .similarWrap').empty();
			}});
		}
	});

	// pick date in checkout
	$( "#cform_custom_delivery" ).datepicker({
		dateFormat: "dd/mm/yy",
		showOn: "button",
		buttonImage: "<?php echo get_template_directory_uri(); ?>/img/calendar.png",
		buttonImageOnly: true,
		minDate: 0
	});
	
	// supermenu css
	$('#supermenu li a:first').css({'border-radius': '2px 0 0 2px'});
	$('#supermenu li a:first').addClass('homelink');
	$("#supermenu li:has(ul)").find("a:first").append('<span>&#9660;</span>');
	$("#supermenu li li:has(ul)").find("a:first").find('span').html('&#8594;');
	
	// supermenu
	$("#supermenu ul").css({display: "none"}); // Opera Fix
	$("#supermenu li").hover(function(){
	$(this).find('ul:first').css({visibility: "visible",display: "none"}).fadeIn(500);
	},function(){
	$(this).find('ul:first').hide();
	});
	
	// supermenu link
	$("#supermenu a").click(function(e){
	
		// track ajaxified links only
		if ($(this).parent().attr('id') && ($('body').find('.catalog_list').length != 0 || $('body').find('.prods').length != 0) ) {
		
		e.preventDefault();
		link = $(this).attr('href');
		window.history.pushState(null,null, link);
		
		// animation
		$('#supermenu li').removeClass('current-menu-item');
		$('#supermenu li').removeClass('current-menu-ancestor');
		$(this).parent().addClass('current-menu-item');
		$(this).parent().parent().parent().addClass('current-menu-item');
		$(this).parent().parent().parent().parent().parent().addClass('current-menu-item');
		$(this).parent().parent().parent().parent().parent().parent().parent().addClass('current-menu-item');
		
		catalogver = '<?php echo ocart_catalog_version() ?>';
		
		// v1
		if (catalogver == 1) {
		
			var taxonomy = $(this).parent().attr('id');
			$.scrollTo('.catalogWrapper', 800);
			$.ajax({
					url: '<?php echo get_template_directory_uri(); ?>/ajax/catalog.php',
					type: 'get',
					data: {taxonomy: taxonomy},
					success: function(data){
						$('.catalogWrapper').html(data);
						$('#filter-by').load('<?php echo get_template_directory_uri(); ?>/ajax/filters.php');
					}
			});

		}
		
		// v2
		if (catalogver == 2) {
		$.scrollTo('#index', 800);
		// reset filters
		$('.filter ul a').removeClass('selected');
		$('.filter ul a#' + $(this).parent().attr('id')).addClass('selected');
		var taxonomies = $(this).parent().attr('id');
			pricemin = $('.text_min ins').html();
			pricemax = $('.text_max ins').html();
		$('.catalog_title').load('<?php echo get_template_directory_uri(); ?>/ajax/catalog_title.php?pricemin=' + pricemin + '&pricemax=' + pricemax + '&taxonomies=' + taxonomies);
		$('.catalog').css({opacity: 0.5});
		$.ajax({
			type: 'post',
			url: '<?php echo get_template_directory_uri(); ?>/ajax/showmore.php',
			data: {pricemin: pricemin, pricemax: pricemax, taxonomies: taxonomies, offset: 0},
			success: function(res) {
				$(".catalog_list").html(res);
				$('.catalog').css({opacity: 1});
			}
		});
		}
		
		}
		
	});
	
	// price slider in grid
	$( "#slider-range" ).slider({
			range: true,
            min: 0,
            max: '<?php echo ocart_show_price_plain( ocart_max_price() ); ?>', // maximum price
            step: 10, // Use this determine the amount of each interval
			values: [ 0, '<?php echo ocart_show_price_plain( ocart_max_price() ); ?>' ], // The default range
			slide: function( event, ui ) {
				$( ".text_min ins" ).html(ui.values[ 0 ]); // Display and selected the min Price
				$( ".text_max ins" ).html(ui.values[ 1 ]); // Display and selected the max Price
			},
			change: function(event, ui) {
				// ajax update
					
					// get active taxonomies
					var taxonomies = '';
					$('.filter ul a.selected').each( function() {
						taxonomies = taxonomies + $(this).attr('id') + ',';
					});
					
					pricemin = $('.text_min ins').html();
					pricemax = $('.text_max ins').html();
					
					// load results and change title
					canScroll = false;
					$("body").prepend("<div id='loading-results' style='display:none;'></div>");
					$('#loading-results').center().show();
					$('.catalog').css({opacity: 0.2});
					$('.catalog_title').load('<?php echo get_template_directory_uri(); ?>/ajax/catalog_title.php?pricemin=' + pricemin + '&pricemax=' + pricemax + '&taxonomies=' + taxonomies);
					
					// show more
					$.ajax({
							type: 'post',
							url: '<?php echo get_template_directory_uri(); ?>/ajax/showmore.php',
							data: {pricemin: pricemin, pricemax: pricemax, taxonomies: taxonomies, offset: 0},
							success: function(res) {
								// add results
								$(".catalog_list").html(res);
								// enable scroll again
								canScroll = true;
								// remove loader
								$('#loading-results').remove();
								$('.catalog').css({opacity: 1});
							}
					});
			}
	});
	
	// collections js
	$('.collection_front_image').live('mouseenter',function(){
		if ($(this).parent().children('.collection_hover_image').length) {
		$(this).fadeOut('slow');
		$(this).parent().find('.collection_hover_image').fadeIn('slow');
		}
	});
	$('.collection_hover_image').live('mouseleave',function(){
		$(this).fadeOut('slow');
		$(this).parent().find('.collection_front_image').fadeIn('slow');
	});
	
	// animate the tag
	$('.column li').live('mouseenter',function(){
		$(this).find('.catalog_item_status').stop().animate({top: '-30px'});
		$(this).css({'border': '2px solid #ccc'});
	}).live('mouseleave',function(){
		$(this).find('.catalog_item_status').stop().animate({top: '-20px'});
		$(this).css({'border': '2px solid transparent'});
	});
	
	// recalculate shipping/tax form
	$('.loc_fields').live('submit',function(e){
		e.preventDefault();
		$('.loc_notice').remove();
		if ($('#pre_country').val() == 0 && $('#pre_region').val() == 0 && $('#pre_zip').val() == 0) {
			$('.loc').append('<span class="loc_notice"><?php _e('Please select a region first.','ocart'); ?></span>');
		} else {
			$.ajax({
				url: '<?php echo get_template_directory_uri(); ?>/ajax/get_fee_by_location.php',
				type: 'post',
				data: $(this).serialize(),
				dataType: 'json',
				beforeSend: function() {
					$('.loc').append('<span class="loc_notice"><?php _e('Calculating fees, please wait...','ocart'); ?></span>');
				},
				success: function(data){
					
					$('.loc_notice').remove();
					$('.loc').append('<span class="loc_notice"><?php _e('Thank you. Your shipping and tax are calculated.','ocart'); ?></span>');
					
					// returned data
					$('.calc-shipping span').html(data.new_shipping);
					$('.calc-tax span').html(data.new_tax);
					$('.calc-total span').html(data.new_total);
					
					// shake new data
					$('.shake-total').effect("shake", { times:2, distance: 5 }, 200);

				}
			});
		}
	});
	
	// get change of region in checkout
	if ($('#cform_country').length) {
		$.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/fees_map.php?country=' + $('#cform_country').val() + '&city=' + $('#cform_city').val() + '&state=' + $('#cform_state').val() + '&zip=' + $('#cform_postcode').val() + '&shipping_charges=' + get_additional_charges_shipping() + '&payment_charges=' + get_additional_charges(),
			dataType: 'json',
			success: function(data){
				$('.checkout_est_tax span').html(data.new_tax);
				$('#shipping_fee').html(data.new_shipping);
				$('#order_total').html(data.new_total);
				$('.checkout_total span').effect("bounce", { times:2 }, 400);
			}
		});
	}
	
	// change fees on change
	$('#cform_country, #cform_state, #cform_city, #cform_postcode').live('change',function(){
		$.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/fees_map.php?country=' + $('#cform_country').val() + '&city=' + $('#cform_city').val() + '&state=' + $('#cform_state').val() + '&zip=' + $('#cform_postcode').val() + '&shipping_charges=' + get_additional_charges_shipping() + '&payment_charges=' + get_additional_charges(),
			dataType: 'json',
			success: function(data){
				$('.checkout_est_tax span').html(data.new_tax);
				$('#shipping_fee').html(data.new_shipping);
				$('#order_total').html(data.new_total);
				$('.checkout_total span').effect("bounce", { times:2 }, 400);
			}
		});
	});
	
	// change fees on country2 change
	$('#cform_country2').live('change',function(){
		$.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/fees_map.php?country=' + $('#cform_country2').val() + '&city=' + $('#cform_city').val() + '&state=' + $('#cform_state').val() + '&zip=' + $('#cform_postcode').val() + '&shipping_charges=' + get_additional_charges_shipping() + '&payment_charges=' + get_additional_charges(),
			dataType: 'json',
			success: function(data){
				$('.checkout_est_tax span').html(data.new_tax);
				$('#shipping_fee').html(data.new_shipping);
				$('#order_total').html(data.new_total);
				$('.checkout_total span').effect("bounce", { times:2 }, 400);
			}
		});
	});
	
	// subscribe to sold out product
	$('#subscribe_to_form').live('submit',function(e) {
		e.preventDefault();
		$('.subs-status').hide();
		$.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/product_subscription.php',
			type: 'post',
			dataType: 'json',
			data: $(this).serialize(),
			success: function(data){
				if (data.fail){
					$('.subs-status').fadeIn('slow').removeClass('pass').html(data.fail);
				} else {
					$('.subs-status').fadeIn('slow').html(data.pass).addClass('pass');
					$('#subscribe_to_product, #subscribe_to_button').remove();
				}
			}
		});
	});
	
	// open lightbox
	$('#wishlist a').click(function(e){
		e.preventDefault();
		<?php if (is_user_logged_in()) { // logged in ?>
			lightbox(null, '<?php echo get_template_directory_uri(); ?>/ajax/wishlist.php');
		<?php } else { ?>
			lightbox(null, '<?php echo get_template_directory_uri(); ?>/ajax/login.php');
		<?php } ?>
	});
	
	// remove item from wishlist
	$('.removeFromWishlist').live('click',function(){
	
		<?php if (!is_user_logged_in()) { // logged in ?>
			lightbox(null, '<?php echo get_template_directory_uri(); ?>/ajax/login.php');
		<?php } else { ?>
		
		var id = $(this).parent().parent().attr('data-ID');
		var list = $(this).parent().parent();
		$.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/remove_from_wishlist.php?id=' + id,
			success: function(data){
				list.fadeOut();
				$('#ajax_wishlist_count').html(data);
				$('#wishlist').fadeOut().fadeIn();
				if (data == 0) { // emtpy
					$('.wishlist').html("<p><?php _e('Your wishlist is empty. You still have not added any product to your wishlist.','ocart'); ?></p>");
				}
			}
		});
		
		<?php } ?>
		
	});
	
	// add item to wishlist
	$('.add_to_wishlist').live('click',function(){
	
		wishlistbutton = $(this);
	
		<?php if (!is_user_logged_in()) { // logged in ?>
			lightbox(null, '<?php echo get_template_directory_uri(); ?>/ajax/login.php');
		<?php } else { ?>
	
		var id = $(this).attr('data-ID');
		$.scrollTo('#topbar', 500);
		$.ajax({
			url: '<?php echo get_template_directory_uri(); ?>/ajax/add_to_wishlist.php?id=' + id,
			success: function(data){
				// auto update wishlist count
				if (data !== '') {
					$('#ajax_wishlist_count').html(data);
					$('#wishlist').fadeOut().fadeIn();
					wishlistbutton.html("<?php _e('Added to Wishlist','ocart'); ?>");
				} else {
					$('#wishlist').fadeOut().fadeIn();
				}
			}
		});
		
		<?php } ?>
		
	});
	
});
</script>