<?php 
/*
 * 2011 Jul 09
 * CSC309 - Carpool Reputation System
 *
 * Make the slide module as a single file so that we can include this file
 * directly when we need it
 *
 * @author Kobe Sun
 *
 */
?>


<style type="text/css">
#slideshow-holder {
	width: 682px;
	height: 160px;
	background: url(images/slides-spinner.gif) center center no-repeat #fff;
	position: relative;
	border: none;
	margin-bottom: 20px;
} 

#progress {
	position: absolute;
	width: 100%;
	text-align: center;
	color: #999;
}
</style>

<script type="text/javascript">
window.addEvent('domready', function() {
	/* preloading */
	var imagesDir = 'images/slides/';
	var images = [ '2.jpg', '3.jpg', '1.png', '4.jpg', '5.jpg' ];
	var holder = $('slideshow-holder');
	images.each(function(img, i) {
		images[i] = imagesDir + '' + img;
	}); // add dir to images
	var progressTemplate = 'Loading image {x} of ' + images.length;
	var updateProgress = function(num) {
		progress.set('text', progressTemplate.replace('{x}', num));
	};
	var progress = $('progress');
	updateProgress('0');
	var loader = new Asset.images(images, {
		onProgress : function(c, index) {
			updateProgress(index + 1);
		},
		onComplete : function() {
			var slides = [];
			/* put images into page */
			images.each(function(im) {
				slides.push(new Element('img', {
					src : im,
					width : 682,
					height : 160,
					styles : {
						opacity : 0,
						top : 0,
						left : 0,
						position : 'absolute',
						'z-index' : 10
					}
				}).inject(holder));
				holder.setStyle('background',
						'url(images/logo.jpg) center 30px no-repeat');
			});
			var showInterval = 5000;
			var index = 0;
			progress.set('text', 'Images loaded.  Made by MooTools.');
			(function() {
				slides[index].tween('opacity', 1);
			}).delay(1000);
			var start = function() {
				(function() {
					holder.setStyle('background', '');
					slides[index].fade(0);
					++index;
					index = (slides[index] ? index : 0);
					slides[index].fade(1);
				}).periodical(showInterval);
			};

			/* start the show */
			start();
		}
	});
});

</script>

<div id="slideshow-holder">
	<div id="progress"></div>
</div>
