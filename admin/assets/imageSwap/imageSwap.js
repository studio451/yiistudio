$(function(){
 
	$('.imageSwap').hover(function() {
		var _this = this,
			images = _this.getAttribute('data-src').split(','),
			counter = 0;
 
		this.setAttribute('data-src', this.src);
		_this.timer = setInterval(function(){
			if(counter > images.length) {
				counter = 0;
			}
			if (images[counter] != undefined) {
				_this.src = images[counter];
			} else {
				_this.src = _this.getAttribute('data-src');
			}
 
			counter++;
		}, 750);
 
	}, function() {
		this.src = this.getAttribute('data-src');
		clearInterval(this.timer);
	});
 
});