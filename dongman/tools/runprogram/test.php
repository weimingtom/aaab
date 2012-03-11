<?php

foreach ($urls as $payName=>$value) {
	$field['vod_play'] .= $payName.'$$$';
	foreach ($value as $k=>$v) {
		if (isset($arr[$k])) {
			$arr[$k] .= '$$$'.$v;
		} else {
			if ($p == 1) {
				$arr[$k] = $v;
			} else {
				$arr[$k] = str_repeat(' $$$', $p).$v;
			}
		}
	}
	$p++;
}
print_r($arr);