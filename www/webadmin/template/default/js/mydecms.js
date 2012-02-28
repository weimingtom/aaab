function TongJiCount(url,id){
	$.post(url+"?id="+id, function(data) {
	  $(".TongJiCount").html(data);
	});
}