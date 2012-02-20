<?php
class UserAction extends BaseAction{	
	// 用户管理
    public function show(){
	    $rs=D("Admin.User");
		$list=$rs->order('user_groupid desc')->select();
		$this->assign('list',$list);
		$this->assign('page',$show);
        $this->display(APP_PATH.'/Public/admin/user.html');
    }

	// 用户添加
    public function add(){
        $this->display(APP_PATH.'/Public/admin/user.html');
    }
	
	// 处理表单数据
	public function insert() {
		$Form=D("Admin.User");
		if($Form->create()) {
			if($Form->add()) {
    			$this->success('数据添加成功！');
            }else{
                $this->error('数据写入错误');
            }
		}else{
			exit($Form->getError().' [ <A HREF="javascript:history.back()">返 回</A> ]');
		}
	}			
}
?>