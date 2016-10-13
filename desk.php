<?php

    session_start();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<title>Books Desk</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta name="description" content="" />
		<meta name="keywords" content=""/>
		<link rel="shortcut icon" href="../favicon.ico" type="image/x-icon"/>
		<link rel="stylesheet" href="css/style.css" type="text/css" media="screen"/>

	</head>
	<body background="./images/bg.jpg">

		<div>
			<a id="pd_loading" href="#" class="pd_loading"></a>
		</div>
		<?php 
		$kyara=array(12,22,32,42,52,62,72,82,92);
		$kyarara=$kyara[rand(0,8)];			
		?>
		<script type="text/javascript">
	<!--
		function ffOver() {   
	
		document.getElementById("shuffle").src="images/<?=$kyarara?>.png";
		}
		function ffOut() {
		
		document.getElementById("shuffle").src="images/<?=$kyarara-1 ?>.png";
		}

	-->
	</script>
	<div id="pd_options_bar" class="pd_options_bar">
			
			<a href="#" class="shuffle"><img src="images/<?=$kyarara-1 ?>.png" id="shuffle" height="150px"/ onmouseover="ffOver();"onmouseout="ffOut();"></a>	
			<a href="#" class="backdesk" style="display:none;"><img src="images/put.jpg" height="100px" style="border-radius:40px;"/></a>
			<a href="#" class="viewall"><img src="images/get.jpg" height="100px" style="border-radius:40px;" /></a>
			<a href="#" class="change"><img src="images/00.png"  height="150px" onclick="window.location.reload()"/></a>
			<a href="index.php" class="out"><img src="images/out.png"  height="100px" /></a>

		</div>
		<?php
	if (isset($_POST['item'])) {$item = $_POST['item'];} else{ $item = 3;};
	$_SESSION['item']=$item;     
		if($_SESSION['item']==0)
		   $mulu="books/manga/";
	    if($_SESSION['item']==1)
		   $mulu="books/ranobe/";
	    if($_SESSION['item']==2)
		   $mulu="books/photos/";
		if($_SESSION['item']==3)
		$mulu="books/ranobe/";
		?>

		<?php
		$i=0;
		$page=rand(1,7);
		$handle = opendir('./'.$mulu.$page.'/'); //イメージカタログ
		while (false !== ($file = readdir($handle))) {   	//とラバーサル　ファイル
		list($filesname,$kzm)=explode(".",$file);	//拡張子ゲット
        if($kzm=="gif" or $kzm=="jpg" or $kzm=="JPG") { 
          if (!is_dir('./'.$file)) { 
            $array[]=$file;			
			$picname[]=$filesname; 
            $i++;//イメージの個数
           }
          }
    }
	?>
		<div id="pd_container" class="pd_container">
			<?php                            /*LOOP イメージを輸出 */
			for($j=0;$j<$i;$j++){              	
			echo("<div class='pd_photo'>
				<div class='pd_hold'>
				<img src='./$mulu$page/$array[$j]'>
				<h3>$picname[$j]</h3>
				</div>
				<span class='delete'></span>
			</div>");
			}
			?>
		</div>

		<!--  JavaScript -->
		<script src="./js/jquery.min.js"></script>
		<script src="./js/jquery-ui.min.js"></script>
		<script src="./js/jquery.transform-0.6.2.min.js"></script>
		<script src="./js/jquery.animate-shadow-min.js"></script>
		<script type="text/javascript">
			jQuery(function() {
				/**
				 * idx:
				 * index of photo that is currently hold
				 * idxLarge:
				 * index of photo that is currently on full mode
				 * mouseup:
				 * flag to use on the mouseup and mousedown events,
				 * to help preventing the browser default selection of elements
				 */
				var idx,idxLarge	= -1;
				var mouseup 		= false;
				
				/**
				 * for now we hardcode the values of our thumb containers
				 */
				var photoW			= 184;
				var photoH			= 205;
				
				/**
				 * the photos and options container
				 */
				var jQuerycontainer 		= jQuery('#pd_container');
				
				var jQueryoptions		= jQuery('#pd_options_bar');
				
				var photosSize 		= jQuerycontainer.find('.pd_photo').length;
				
				/**
				 * navigation current step
				 */
				var navPage			= 0;
				/**
				 * spreads the photos on the table..
				 */
				
				var ie 				= false;
				if (jQuery.browser.msie) {
					ie = true;
				}
				
				start();
				
				function start(){
					jQuery('#pd_loading').show();
					
					var tableW 			= jQuerycontainer.width();
					var tableH 			= jQuerycontainer.height();
					
					var horizontalMax	= tableW - photoW;
					var verticalMax		= tableH - photoH;
					
					jQuery('<img />').attr('src','images/paperball.png');
					/**
					* display all the photos on the desk, with a random rotation,
					 * and also make them draggable.
					 * on mouse down, we want the photo to enlarge in a few pixels,
					 * and to rotate 0 degrees
					 */
					var cntPhotos = 0;
					jQuerycontainer.find('.pd_photo').each(function(i){
						var jQueryphoto 	= jQuery(this);
						jQuery('<img />').load(function(){
							++cntPhotos;
							var jQueryimage 	= jQuery(this);
							
						var r		= Math.floor(Math.random()*201)-100;//*41
						var maxzidx = parseInt(findHighestZIndex()) + 1;
						var param	= {
							'top' 		: Math.floor(Math.random()*verticalMax) +'px',       
							'left'		: Math.floor(Math.random()*horizontalMax) +'px',
								'z-index'	: maxzidx
						};
							
							jQueryphoto.css(param);
							if(!ie)
								jQueryphoto.transform({'rotate'	: r + 'deg'});
							jQueryphoto.show();	
							if(cntPhotos == photosSize){
					bindEvents();
								jQuery('#pd_loading').hide();
							}
						}).attr('src',jQueryphoto.find('img').attr('src'));	
					});	
				}
				
				/**
				 * grab a photo
				 */
				function mouseDown(jQueryphoto){
					mouseup 	= true;
					idx			= jQueryphoto.index() + 1;
					var maxzidx = parseInt(findHighestZIndex()) + 1;
					jQueryphoto.css('z-index',maxzidx);
					if(ie)
					var param = {
						'width'		: '+=40px',
						'height'	: '+=40px'
					};
					else
					var param = {
						'width'		: '+=40px',
						'height'	: '+=40px',
						'rotate'	: '0deg',
						'shadow'	: '5px 5px 15px #222'
					};
					jQueryphoto.stop(true,true).animate(param,100).find('img').stop(true,true).animate({
						'width'		: '+=40px',
						'height'	: '+=40px'
					},100);
				}
				
				/**
				 * we do the mouseup on the document to prevent the
				 * case when we release the mouse outside of a photo.
				 * also, we want the photo to get smaller again,
				 * rotate some random degrees, and also move it some pixels
				 */
				jQuery(document).bind('mouseup',function(e){
					if(mouseup){
						mouseup 	= false;
						var jQueryphoto 	= jQuerycontainer.find('.pd_photo:nth-child('+idx+')');
						var r		= Math.floor(Math.random()*101)-50;
						var jQueryphotoT	= parseFloat(jQueryphoto.css('top'),10);
						var jQueryphotoL	= parseFloat(jQueryphoto.css('left'),10);
						var newTop	= jQueryphotoT + r;
						var newLeft	= jQueryphotoL + r;
						if(ie)
						var param = {
							'width'		: '-=40px',
							'height'	: '-=40px',
							'top'		: newTop + 'px', 
							'left'		: newLeft + 'px'
						};
						else
						var param = {
							'width'		: '-=40px',
							'height'	: '-=40px',
							'top'		: newTop + 'px',
							'left'		: newLeft + 'px',
							'rotate'	: r+'deg',
							'shadow'	: '0 0 5px #000'
						};
						jQueryphoto.stop(true,true).animate(param,200).find('img').stop(true,true).animate({
							'width'	: '-=40px',
							'height': '-=40px'
						},200);
					}
					e.preventDefault();
				});
				
				/**
				 * removes the photo element from the DOM,
				 * after showing the paper image..
				 */
				jQuerycontainer.find('.delete').bind('click',function(){
					var jQueryphoto 			= jQuery(this).parent();
					var jQueryphotoT			= parseFloat(jQueryphoto.css('top'),10);
					var jQueryphotoL			= parseFloat(jQueryphoto.css('left'),10);
					var jQueryphotoZIndex	= jQueryphoto.css('z-index');
					var jQuerytrash = jQuery('<div />',{
						'className'	: 'pd_paperball',
						'style'		: 'top:' + parseInt(jQueryphotoT + photoH/2) + 'px;left:' + parseInt(jQueryphotoL + photoW/2) +'px;width:0px;height:0px;z-index:' + jQueryphotoZIndex
					}).appendTo(jQuerycontainer);
					
					jQuerytrash.animate({
						'width'	: photoW + 'px',
						'height': photoH + 'px',
						'top'	: jQueryphotoT + 'px',
						'left'	: jQueryphotoL + 'px'
					},100,function(){
						var jQuerythis = jQuery(this);
						setTimeout(function(){
							jQuerythis.remove();
						},800);
					});
					jQueryphoto.animate({
						'width'	: '0px',
						'height': '0px',
						'top'	: jQueryphotoT + photoH/2 + 'px',
						'left'	: jQueryphotoL + photoW/2 +'px'
					},200,function(){
						--photosSize;
						jQuery(this).remove();
					});
				});
				
				function stack(){
					navPage 		= 0;
					var cnt_photos 	= 0;
					var windowsW 		= jQuery(window).width();
					var windowsH 		= jQuery(window).height();
					jQuerycontainer.find('.pd_photo').each(function(i){
						var jQueryphoto 	= jQuery(this);
						jQueryphoto.css('z-index',parseInt(findHighestZIndex()) + 1000 + i)
						.stop(true)
						.animate({
							'top'	: parseInt((windowsH-200)/2 - photoH/2) + 'px',
							'left'	: parseInt((windowsW-100)/2 - photoW/2) + 'px'
						},800,function(){
							jQueryoptions.find('.backdesk').show();
							var jQueryphoto = jQuery(this);
							++cnt_photos;
							var jQuerynav 	= jQuery('<a class="pd_next_photo" style="display:none;"></a>');
							jQuerynav.bind('click',function(){
								navigate();
								jQuery(this).remove();
							});
							jQueryphoto.prepend(jQuerynav);
							jQueryphoto.draggable('destroy')
							.find('.delete')
							.hide()
							.andSelf()
							.find('.pd_hold')
							.unbind('mousedown')
							.bind('mousedown',function(){return false;});
								  
							jQueryphoto.unbind('mouseenter')
							.bind('mouseenter',function(){
								jQuerynav.show();
							})
							.unbind('mouseleave')
							.bind('mouseleave',function(){
								jQuerynav.hide();
							});
							jQueryoptions.find('.shuffle,.viewall').unbind('click');
							if(cnt_photos == photosSize)
								enlarge(findElementHighestZIndex());
						});
					});
				}
				
				function enlarge(jQueryphoto){
					var windowsW 		= jQuery(window).width();
					var windowsH 		= jQuery(window).height();
					if(ie)
					var param = {
						'width'	: '+=199px',
						'height': '+=300px',
						'top'	: parseInt((windowsH-330)/2 - (photoH+200)/2) + 'px', 
						'left'	: parseInt((windowsW-100)/2 - (photoW+200)/2) + 'px'
					};
					else
					var param = {
						'width'	: '+=199px', //框体大小
						'height': '+=300px',
						'top'	: parseInt((windowsH-330)/2 - (photoH+200)/2) + 'px',
						'left'	: parseInt((windowsW-100)/2 - (photoW+200)/2) + 'px',
						'rotate': '0deg',
						'shadow': '5px 5px 15px #222'
					};
					jQueryphoto.animate(param,500,function(){
						idxLarge = jQuery(this).index();
					}).find('img').animate({
						'width'	: '+=199px',  //图片大小变化
						'height': '+=300px'
					},500);
				}
				
				/**
				 * back to desk
				 */
				function disperse(){
					var windowsW 		= jQuery(window).width();
					var windowsH 		= jQuery(window).height();
					
					jQuerycontainer.find('.pd_photo').each(function(i){
						var jQueryphoto 		= jQuery(this);
						//if it is the current large photo:点击查看后的大小变化
						if(jQueryphoto.index() == idxLarge){
							if(ie)
							var param = {
								'top'		: parseInt((windowsH-330)/2 - photoH/2) + 'px', 
								'left'		: parseInt((windowsW-100)/2 - photoW/2) + 'px',
								'width'		: '160px',
								'height'	: '240px'
							};
							else
							var param = {
								'top'		: parseInt((windowsH-330)/2 - photoH/2) + 'px', 
								'left'		: parseInt((windowsW-100)/2 - photoW/2) + 'px',
								'width'		: '160px',
								'height'	: '240px',   //back后的框大小
								'shadow'	: '1px 1px 5px #555'
							};
							jQueryphoto.stop(true).animate(param,500, function(){
								shuffle();
								jQueryoptions.find('.viewall').show();
							}).find('img').animate({
								'width'		: '160px',   //back后的图片大小
								'height'	: '240px'
							},500);
						}
					});
					jQuerycontainer.find('.pd_next_photo').remove();
					bindEvents();
				}
				
				function bindEvents(){
					jQueryoptions.find('.shuffle').unbind('click').bind('click',function(e){
						if(photosSize == 0) return;
						shuffle();
						e.preventDefault();
					}).andSelf().find('.viewall').unbind('click').bind('click',function(e){
						var jQuerythis = jQuery(this);
						if(photosSize == 0) return;
						stack();
						jQuerythis.hide();
						e.preventDefault();
					}).andSelf().find('.backdesk').unbind('click').bind('click',function(e){
						var jQuerythis = jQuery(this);
						if(photosSize == 0) return;
						disperse();
						jQuerythis.hide();
						e.preventDefault();
					});
					
					jQuerycontainer.find('.pd_photo').each(function(i){
						var jQueryphoto = jQuery(this);
						jQueryphoto.draggable({
							containment	: '#pd_container'
						}).find('.delete')
						.show()
					}).find('.pd_hold').unbind('mousedown').bind('mousedown',function(e){
						var jQueryphoto 	= jQuery(this).parent();
						mouseDown(jQueryphoto);
						e.preventDefault();
					});
				}
				
				function navigate(){
					if(photosSize == 0) return;
					
					var tableW 			= jQuerycontainer.width();
					var tableH 			= jQuerycontainer.height();
					
					var horizontalMax	= tableW - photoW;
					var verticalMax		= tableH - photoH;
					
					var jQueryphoto 			= jQuerycontainer.find('.pd_photo:nth-child('+parseInt(idxLarge+1)+')');
					var r				= Math.floor(Math.random()*201)-100;//*41
					if(ie)
					var param = {
						'top' 		: Math.floor(Math.random()*verticalMax) +'px',       
						'left'		: Math.floor(Math.random()*horizontalMax) +'px',
						'width'		: '160px',
						'height'	: '240px'
					};
					else
					var param = {
						'top' 		: Math.floor(Math.random()*verticalMax) +'px',
						'left'		: Math.floor(Math.random()*horizontalMax) +'px',
						'width'		: '160px', //点击后的框大小
						'height'	: '240px',
						'rotate'	: r+'deg',
						'shadow'	: '1px 1px 5px #555'
					};
					jQueryphoto.stop(true).animate(param,500,function(){
						++navPage;
						var jQueryphoto = jQuery(this);
						
						jQuerycontainer.append(jQueryphoto.css('z-index',1));
						if(navPage < photosSize)
							enlarge(findElementHighestZIndex());
						else{ //last one
							jQueryoptions.find('.backdesk').hide();
							jQueryoptions.find('.viewall').show();
							bindEvents();
						}
					}).find('img').animate({
						'width'		: '160px',   //变小后的图片大小
						'height'	: '240px'
					},500);
				}
				
				function shuffle(){
					var tableW 			= jQuerycontainer.width();
					var tableH 			= jQuerycontainer.height();
					
					var horizontalMax	= tableW - photoW;
					var verticalMax		= tableH - photoH;
					jQuerycontainer.find('.pd_photo').each(function(i){
						var jQueryphoto = jQuery(this);
						var r		= Math.floor(Math.random()*401)-100;//*41
						if(ie)
						var param = {
							'top' 		: Math.floor(Math.random()*verticalMax) +'px',       
							'left'		: Math.floor(Math.random()*horizontalMax) +'px'
						};
						else
						var param = {
							'top' 		: Math.floor(Math.random()*verticalMax) +'px',
							'left'		: Math.floor(Math.random()*horizontalMax) +'px',
							'rotate'	: r+'deg'
						};
						jQueryphoto.animate(param,800);	
					});
				}
				
				function findHighestZIndex(){
					var photos = jQuerycontainer.find('.pd_photo');
					var highest = 0;
					photos.each(function(){
						var jQueryphoto = jQuery(this);
						var zindex = jQueryphoto.css('z-index');
						if (parseInt(zindex) > highest) {
							highest = zindex;
						}
					});
					return highest;
				}
				
				function findElementHighestZIndex(){
					var photos = jQuerycontainer.find('.pd_photo');
					var highest = 0;
					var jQueryelem;
					photos.each(function(){
						var jQueryphoto = jQuery(this);
						var zindex = jQueryphoto.css('z-index');
						if (parseInt(zindex) > highest) {
							highest = zindex;
							jQueryelem	= jQueryphoto;
						}
					});
					return jQueryelem;
				}
				
				
				Array.prototype.remove = function(from, to) {
					var rest = this.slice((to || from) + 1 || this.length);
					this.length = from < 0 ? this.length + from : from;
					return this.push.apply(this, rest);
				};
			});
		</script>
	</body>
</html>