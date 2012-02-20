function pagego($url,$total){
	$page=document.getElementById('page').value;
	if($page>0&&($page<=$total)){
		$url=$url.replace('{!page!}',$page);
		$url=$url.replace('-1/','/');
		location.href=$url;
	}
	return false;
}