<?php
if(!isset($GLOBALS['_vars'])) $GLOBALS['_vars'] = array(); 
$fields = array();

require_once(DEDEINC."/common.func.php");

$atts = array();
$atts['tagname'] = 'datalist';
$blockValue = $this->refObj->GetArcList($atts,$this->refObj,$fields); 
if(is_array($blockValue)){
foreach( $blockValue as $key=>$fields )
{

  if($fields['userid']!='') $spaceurl = $cfg_basehost.'/member/index.php?uid='.$fields['userid'];
  else $spaceurl = "#";
  if($fields['username']=='匿名') $spaceurl = "#";
  $fields['bgimg'] = 'cmt-neu.gif';
  $fields['ftypetitle'] = '该用户表示中立';
  if($fields['ftype']=='bad') {
  	$fields['bgimg'] = 'cmt-bad.gif';
  	$fields['ftypetitle'] = '该用户表示差评';
  }
  else if($fields['ftype']=='good') {
  	$fields['bgimg'] = 'cmt-good.gif';
  	$fields['ftypetitle'] = '该用户表示好评';
  }
?>
document.write("<div class='decmt-box'>");
document.write("<div class='decmt-title'>");
document.write("<span class='moodico'><img src='<?php echo $GLOBALS['cfg_templeturl']; ?>/images/mood/ico-mood-<?php if($fields['face']>0) echo $fields['face'];?>.gif'/></span>");
document.write("<span class='username'><a href='<?php echo $spaceurl;?>'><?php echo $fields['username']; ?></a></span>");
document.write("<span class='date'><?php echo GetDateMk($fields['dtime']); ?></span>");
document.write("<span>发表</span>");
document.write("</div>");
document.write("<div class='decmt-act'>");
document.write("<span id='goodfb<?php echo $fields['id']; ?>'><a href=#goodfb<?php echo $fields['id']; ?> onclick=postBadGood('goodfb',<?php echo $fields['id']; ?>)>支持</a>[<?php echo $fields['good']; ?>]</span>");
document.write("<span id='badfb<?php echo $fields['id']; ?>'><a href=#badfb<?php echo $fields['id']; ?> onclick=postBadGood('badfb',<?php echo $fields['id']; ?>)>反对</a>[<?php echo $fields['bad']; ?>]</span>");
document.write("<span><a href='/plus/bookfeedback.php?fid=<?php echo $fields['id']; ?>&action=quote'>引用</a></span>");
document.write("</div>");
document.write("<div class='decmt-content'><?php echo jstrim($fields['msg'],100); ?><img src='<?php echo $GLOBALS['cfg_templeturl']; ?>/images/<?php echo $fields['bgimg']; ?>' alt='<?php echo $fields['ftypetitle']; ?>' />");
document.write("</div>");
document.write("</div>");
<?php
}
}?>