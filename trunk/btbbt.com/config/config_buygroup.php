<?php

/*
	[Stone PHP] (C)2008-2010 REDSTONE.
	This is NOT a freeware, use is subject to license terms
*/

//note stonephp 以下是一些配置信息，不明白的不要乱动
$groupid = 21;//note 要销售的用户组
$default_buy = 1;//note 默认选中那种类型的购买方式
$price = array();
$prices[0]['name'] = '一年授权';//note 第一种收费模式的名称
$prices[0]['days'] = 365;//note 第一种收费模式，购买天数 
$prices[0]['price'] = 800;//note 第一种收费模式购买价格 单位人民币

$prices[1]['name'] = '三年授权';//note 第二种收费模式的名称
$prices[1]['days'] = 365*3;//note 第二种收费模式，购买天数 
$prices[1]['price'] = 2000;//note 第二种收费模式购买价格单位人民币

$prices[2]['name'] = '终身授权';//note 第三种收费模式的名称
$prices[2]['days'] = 365*20;//note 第三种收费模式，购买天数 
$prices[2]['price'] = 5000;//note 第三种收费模式购买价格单位人民币
//note 配置结束
