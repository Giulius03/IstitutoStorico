$(document).ready(function(){

  wow = new WOW({
    animateClass: 'animated',
      offset:       100
  });
  wow.init();

  $('.carousel').carousel({ interval: 5000});

  $('a.fancybox').fancybox({
		helpers : {
			title : {type : 'inside'}
		}
	});
});
