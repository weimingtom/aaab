<?php
class AdsAction extends BaseAction{	
	// 显示分类
    public function show(){
	    $rs=D("Admin.Ads");
		$array=$rs->select();
		$this->assign('listads',$array);
		$this->display(APP_PATH.'/Public/admin/ads.html');
    }
	
	// 更新数据
	public function update(){
	    $array=$_POST;
		$rs=D("Admin.Ads");	
		if(!empty($array['ads_name_sub'])){
		    if($rs->where('ads_name="'.trim($_POST['ads_name_sub']).'"')->find()){$this->error('该广告标识已经存在,请重新填写一个广告标识！');};
			$data['ads_name'] = trim($array['ads_name_sub']);
			$data['ads_content'] = stripslashes(trim($array['ads_content_sub']));
			$rs->add($data);
			write_file(APP_PATH.'/Public/ads/'.$data['ads_name'].'.js',t2js($data['ads_content']));
		}			
		foreach($array['ads_id'] as $value){
		    $data['ads_id'] = $array['ads_id'][$value];
			$data['ads_name'] = trim($array['ads_name'][$value]);
			$data['ads_content'] = stripslashes(trim($array['ads_content'][$value]));
			if(empty($data['ads_name'])){
			    $rs->where('ads_id='.$data['ads_id'])->delete();
			}else{
			    write_file(APP_PATH.'/Public/ads/'.$data['ads_name'].'.js',t2js($data['ads_content']));
			    $rs->save($data);
			}
		}				
		$this->success('数据更新成功！');
	}					
}
?>