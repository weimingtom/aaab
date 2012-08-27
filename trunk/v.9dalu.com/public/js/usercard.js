var _widget_user_delay    = 0;
var _widget_user_shown_id = 0;

$(document).ready(function(){
    $("a[rel='_widget_user']").hover(
        function(event){
			var w=event.x ? event.x : event.pageX;
			var h=event.y ? event.y : event.pageY;

        	var user_id = $(this).attr('user_id');
			
			$.post("index.php?app=home&mod=User&act=usercard",{user_id:user_id},function(res){
																   
						$('body').append(res);	
						position = $('#user_'+user_id).offset();
		                $('#me_'+user_id).css({"top":(h-180)+"px","left":(w-5)+"px","display":"block"});
						 });
				clearTimeout(_widget_user_delay);								 
				_widget_user_delay = setTimeout(function(){						 
        		   _widget_user_show(user_id);
        	}, 100);
        },
        function(){
        	clearTimeout(_widget_user_delay);
        	_widget_user_delay = setTimeout(function(){
        		_widget_user_hide();
        	}, 100);
        }
    );
    
    $("table[rel='_widget_user']").hover(
        function(){
        	clearTimeout(_widget_user_delay);
        },
        function(){
        	_widget_user_delay = setTimeout(function(){
                _widget_user_hide();
            }, 100);
        }
    );
});

function _widget_user_show(user_id) {
	if (user_id != _widget_user_shown_id) {
        _widget_user_hide();
		_widget_user_shown_id = user_id;
	    $("#_widget_user_popbox_"+user_id).fadeIn();
		
		
	}
}

function _widget_user_hide() {
    _widget_user_shown_id = 0;
	$("table[rel='_widget_user']:visible").fadeOut();
}
