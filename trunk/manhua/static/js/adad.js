
var lastScrollY = 0;

function heartBeat()
{

	var diffY;
	if (document.documentElement && parent.document.documentElement.scrollTop)
	{
		diffY = document.documentElement.scrollTop;
	}
	else if (document.body)
	{
		diffY = document.body.scrollTop;
	}

	percent =.1 * (diffY - lastScrollY);
	
	if(percent > 0)
	{
		percent = Math.ceil(percent);
	}
	else percent = Math.floor(percent);
	
	document.getElementById("LeftAd").style.top = parseInt(document.getElementById("LeftAd").style.top) + percent + "px";
	document.getElementById("RightAd").style.top = parseInt(document.getElementById("LeftAd").style.top) + percent + "px";
	lastScrollY = lastScrollY + percent;

}


window.setInterval("heartBeat()", 1);

function ClosedivLeft()
{
	document.getElementById('LeftAd').style.visibility = "hidden";
}

function ClosedivRight()
{
	document.getElementById('RightAd').style.visibility = "hidden";
}

