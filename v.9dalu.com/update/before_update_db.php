<?php
// 清理weibo_follow中的垃圾数据
$user_model = M('user');
$min_uid = $user_model->order('uid ASC')->getField('uid');
$max_uid = $user_model->order('uid DESC')->getField('uid');
if ($min_uid > 0 && $max_uid > 0 && $max_uid >= $min_uid) {
	$db_prefix = C('DB_PREFIX');
	$sql = "DELETE FROM {$db_prefix}weibo_follow WHERE type = 0 AND (uid < '{$min_uid}' OR uid > '{$max_uid}' OR fid < '{$min_uid}' OR fid > '{$max_uid}')";
	M('')->query($sql);
}
