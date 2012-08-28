

$(document).ready(function() {

		$(window).scrollTop(0);
		$('.contents_slide').waypoint(function (event, direction) {
 
        //cache the variable of the data-slide attribute associated with each slide
        dataslide = $(this).attr('data-slide');
 
        //If the user scrolls up change the navigation link that has the same data-slide attribute as the slide to active and 
        //remove the active class from the previous navigation link 
        if (direction === 'down') {
            $('#nav_item'+dataslide).addClass('nav_itemActive').prev().removeClass('nav_itemActive');
        }
        // else If the user scrolls down change the navigation link that has the same data-slide attribute as the slide to active and 
        //remove the active class from the next navigation link 
        else {
            $('#nav_item'+dataslide).addClass('nav_itemActive').next().removeClass('nav_itemActive');
        }
 
    }, {offset:'10'});
    
    $('#modal-button-ok').click(function(){
			$('#login').submit();
	});
	
	$('#signup_button').click(function(){
		$('#mailing_list').submit();
	});
	
	$('#mailing_list').submit(function(){
		$.ajax({
				data: $(this).serialize(),
				type: $(this).attr('method'),
				url: $(this).attr('action'),
				success: function(data){
					alert(data);
				} 
		}); 
		return false;
	});

});

