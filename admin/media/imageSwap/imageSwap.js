$(function(){
 	$('.imageSwap').hover(function() {           
		var image = this.src;
                var imageAlt = this.getAttribute('data-src');
                if(imageAlt !== 'none')
                {
                    this.src = imageAlt;
                    this.setAttribute('data-src', image); 
                }                
	}, function() {                
                var image = this.getAttribute('data-src');
                var imageAlt = this.src;   
                if(image !== 'none')
                {
                    this.src = image;                
                    this.setAttribute('data-src', imageAlt);
                }
	}); 
});