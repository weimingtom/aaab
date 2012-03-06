<?php
class Category
{
    protected $tree = null;
    
	public $_id = 0; //指定要获取的分类ID,将只会获取它本身和它下面的子分类
	
	protected $store_id = 0; //指定要显示哪个版块的分类信息
	
	function __construct(&$base) {
		$this->base = $base;
		$this->db = $base->mydb ();
	}
	
	public function get_categorys($level=0){
		$arr = $this->list_child($level);
	        $options = array();
	        foreach ($arr as $val) {
	             $options[$val['cate_id']] = $val;
	        }
	        return $options; 
	}
	
	/* 获取分类排序，默认是二级，等级暂未实现 */
	public function get_categorys_sort($level=2) {
		$data = $this->get_categorys($level);
		foreach($data as $k=>&$v) {
			if(empty($v['parent_id'])) {
				$v['level'] = 1;
				$categorys[$v['cate_id']][] = $v;
			} else {
				$v['level'] = 2;
				$categorys[$v['parent_id']][] = $v;
			}
		}
		return $data;
	}
	
	public function get_nav_categorys() {
		return $this->db->fetch_all("SELECT * FROM mh_categories WHERE ismenu=1");
	}
	
	public function get_category($categoryId) {
		return $this->db->fetch_first("SELECT * FROM mh_categories WHERE id='$categoryId'");
	}
	
	/* 获取子类数据 */
	public function get_sub_categorys($categoryId, $categorys) {
		$arr = array();
		foreach($categorys as $category) {
			if($categoryId == $category['parent_id']) {
				$arr[] = $category;
			}
		}
		return $arr;
	}
	
    /**
     * 获取指定分类子分类Array(cate_id=>array(cate_name, level, ……)
     * 
	 * 用户显示下拉列表分灰的值
	 *
	 * @param unknown_type $level =>　显示至几级分类
	 * @return unknown
	 */
    function get_options ($level = 0)
    {    	
        $arr = $this->list_child($level);
        
        $options = array();
        
        foreach ($arr as $val)
        {

             $options[] = array('cate_id' => $val['cate_id'], 'cate_name' => str_repeat('&nbsp;&nbsp;', $val['level']-1) . $val['cate_name']);
             
        }
                
        return $options;        
    }
	
	function get_list ($level = 0)
	{		
        $arr = $this->list_child($level);
        
        $data = array();
              
        foreach ($arr as $val)
        {
             $data[] = array(
             	'cate_id' => $val['cate_id'], 
             	'parent_id' => $val['parent_id'],
             	'child'		=> $val['child'],
             	'cate_name' => $val['cate_name'],
             	'menulink' => $val['menulink'],
             	'catestat' => $this->db->result_first("SELECT COUNT(*) FROM mh_product WHERE cateid = '". $val['cate_id'] ."' "),
             	'level'	=> $val['level'],
             	'sort_order' => $val['sort_order'],
             	'time' => date('Y-m-d', strtotime($val['time']))
             );
        }
        
        return $data;        
    }
    
    /**
     * 获取所有子分类
     *
     * @param unknown_type $level => 显示至几级分类
     * @return unknown
     */
    function list_child ($level = 0)
    {    	
        $list = $this->_get_child($level);

        return $list;        
    }
    
    /**
     * @param unknown_type $level => 0　是显示所有分类的
     * @param unknown_type $this->_id => 指定只显示某一个分类与其下面的子分类
     * @return unknown
     */
	function _get_child ($level)
	{		
	    if ($this->tree === null)
	    {
	    	$this->_init_tree();
	    }

	    if ($level == 0 && empty($this->_id))
	    {
	        return $this->tree;
	    }
	
	    if ($this->_id == 0)
	    {
	        $param = array('cate_id' => 0, 'level' => 0, 'dir' => '0');
	    }
	    else
	    {	    	
	        $param = $this->tree[$this->_id];
	    }
	
	    $param['add_level'] = $level;
	    
	    //按指定的条件过滤掉不要显示的分类
	    category_filter(array(), $param);
	    
	    return  array_filter($this->tree, "category_filter");	    
	}

   /**
    * 初始化分类数据
    */
	function _init_tree ()
	{	
		$arr = $this->db->fetch_all("SELECT id AS cate_id,store_id,cate_name,menulink,parent_id,sort_order,`time` FROM `mh_categories` WHERE store_id = 0 ");
		
		$count = count($arr);
		$data = array();
		
		for($i=0; $i < $count; $i++)
		{			
			$data[$arr[$i]['cate_id']] = $arr[$i];			
		}
		
		//循环统计出每个分类是属于第几级分类
		foreach ($data as $val)
		{			
			$this->_build($val['cate_id'], $data);			
		}
		
		//按照定义的category_sort函数时行自定义排序
		category_sort(array(), array(), $data);
		
		uasort($data, "category_sort");
		
		$this->tree = $data;	
	}

	/**
	 * 计算出当前分类是属于几级分类
	 *
	 * @param unknown_type $cate_id => 分类ID
	 * @param unknown_type $data => 引用同一个 $data 数据做比较
	 */
	function _build ($cate_id, &$data)
	{		
		if (!isset($data[$cate_id]['child']))
		{			
			$data[$cate_id]['child'] = 0;			
		}
		
		$parents = array();
		$parent_id = $data[$cate_id]['parent_id'];

		$level = 1;
		
		while (isset($data[$parent_id]) && (!in_array($parent_id, $parents)))
		{			
		    if (isset($data[$parent_id]['child']))
		    {		    	
		        $data[$parent_id]['child'] ++;		        
		    }
		    else
		    {		    	
		        $data[$parent_id]['child'] = 1;		        
		    }
		
		    $parents[] = $parent_id;
		    $level ++;
		    $parent_id = $data[$parent_id]['parent_id'];		    
		}
		
		$parents[] = 0;
		$parents = array_reverse($parents);
		$data[$cate_id]['dir'] = implode('/', $parents);
		$data[$cate_id]['level'] = $level;
	}
}


/**
 * 分类排序函数
 */
function category_sort ($a, $b, $add_data=null)
{	
	static $data = null;
	
	if (isset($add_data))
	{		
	    $data = $add_data;
	    return true;	    
	}
	
	if ($a['dir'] == $b['dir'])
	{	
	    if ($a['sort_order'] == $b['sort_order'])
	    {	    	
	        return $a['cate_id'] < $b['cate_id'] ? -1 : 1;	        
	    }
	    
	    return $a['sort_order'] < $b['sort_order'] ? -1 : 1;	    
	}
	else
	{	
		//level => 当前分类处在几级分类
	    $count = min($a['level'], $b['level']);
	
	    $tmp_a = explode('/', $a['dir']);
	    $tmp_b = explode('/', $b['dir']);
	
	    for ($i=0; $i< $count; $i++)
	    {	    	
	        if ($tmp_a[$i] != $tmp_b[$i])
	        {	        	
	            if ($data[$tmp_a[$i]]['sort_order'] == $data[$tmp_b[$i]]['sort_order'])
	            {	            	
	                return $data[$tmp_a[$i]]['cate_id'] < $data[$tmp_b[$i]]['cate_id'] ? -1 : 1;
	            }
	            
	            return $data[$tmp_a[$i]]['sort_order'] < $data[$tmp_b[$i]]['sort_order'] ? -1 : 1;
	        }
	    }
	    
	    if ($a['level'] < $b['level'])
	    {	    	
	        if ($tmp_b[$count] == $a['cate_id'])
	        {	        	
	            return -1;
	        }
	        
	        if ($data[$a['cate_id']]['sort_order'] == $data[$tmp_b[$count]]['sort_order'])
	        {
	            return  $a['cate_id'] < $tmp_b[$count] ? -1 : 1;
	        }
	        
	        return $data[$a['cate_id']]['sort_order'] < $data[$tmp_b[$count]]['sort_order'] ? -1 : 1;
	    }
	    else
	    {	    	
	        if ($tmp_a[$count] == $b['cate_id'])
	        {	        	
	            return 1;
	        }
	        
	        if ($data[$b['cate_id']]['sort_order'] == $data[$tmp_a[$count]]['sort_order'])
	        {
	            return  $tmp_a[$count] < $b['cate_id'] ? -1 : 1;
	        }
	        
	        return $data[$tmp_a[$count]]['sort_order'] < $data[$b['cate_id']]['sort_order'] ? -1 : 1;
	    }
	}
}

/**
 * 分类过滤函数
 */
function category_filter ($val, $add_param = null)
{
    static $param = 0;
    
    if (isset($add_param))
    {    	
        $param = $add_param;

        if ($param['cate_id'] > 0)
        {        	
           $param['parent_dir'] = $param['dir'] . '/' . $param['cate_id'];
           $param['parent_dir_ext'] = $param['dir'] . '/' . $param['cate_id'] . '/';
        }
        else
        {        	
            $param['parent_dir'] = '0';
            $param['parent_dir_ext'] = '0/';
        }

        //取至几层分类
        $param['last_level'] =  $param['level'] + $param['add_level'];
        $param['parent_dir_ext_length'] = strlen($param['parent_dir_ext']);

        return true;
    }
    
    if ($val['cate_id'] == $param['cate_id'])
    {
        return true;
    }

    if ($val['level'] > $param['level'])
    {    	
    	//所处分类层次大于指定获取层次的将会返回false
        if ($param['add_level'] > 0 && ($val['level'] > $param['last_level']))
        {
            return false;
        }
        
        if ($val['dir'] == $param['parent_dir'] ||  strncmp($val['dir'], $param['parent_dir_ext'], $param['parent_dir_ext_length']) == 0)
        {
            return true;
        }
        
        return false;
    }
}

?>