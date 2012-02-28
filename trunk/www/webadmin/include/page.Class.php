<?php
/**
 *-------------------------��ҳ��----------------------*
 */
class PageClass
{
	private $myde_count;       //�ܼ�¼��
	var $myde_size;        //ÿҳ��¼��
	private $myde_page;        //��ǰҳ
	private $myde_page_count;  //��ҳ��
	private $page_url;         //ҳ��url
	private $page_i;           //��ʼҳ
	private $page_ub;          //����ҳ
	private $index_url;        //��ҳ(������;���������дĿ¼)
	var $page_limit;
	
	function __construct($myde_count=0, $myde_size=1,$myde_page=1,$page_url,$index_url="")//���캯��
	{	
		
		$this -> myde_count = $this -> numeric($myde_count);
		$this -> myde_size  = $this -> numeric($myde_size);
		$this -> myde_page  = $this -> numeric($myde_page);
		$this -> index_url  = $index_url;
		$this -> page_limit = ($this -> myde_page * $this -> myde_size) - $this -> myde_size; 
		
		$this -> page_url       = $page_url;
		
		if($this -> myde_page < 1) $this -> myde_page =1;
		
		if($this -> myde_count < 0) $this -> myde_page =0;
		
		$this -> myde_page_count  = ceil($this -> myde_count/$this -> myde_size);
		
		if($this -> myde_page_count < 1) $this -> myde_page_count = 1;
		
		if($this -> myde_page > $this -> myde_page_count) $this -> myde_page = $this -> myde_page_count;
		
		$this -> page_i = $this -> myde_page - 2;
		
        $this -> page_ub = $this -> myde_page + 2;
		
        if($this -> page_i < 1){
		
            $this -> page_ub = $this -> page_ub + (1 - $this -> page_i);
			
            $this -> page_i = 1;
        }
        
        if($this -> page_ub > $this -> myde_page_count){
		
            $this -> page_i = $this -> page_i - ($this -> page_ub - $this -> myde_page_count);
			
            $this -> page_ub = $this -> myde_page_count;
			
            if($this -> page_i < 1) $this -> page_i = 1;
        }
	}
	
	
	private function numeric($id) //�ж��Ƿ�Ϊ����
	{
		if (strlen($id)){
    		if (!ereg("^[0-9]+$",$id)){
				$id = 1;
    		}else{
				$id = substr($id,0,11);
 			}
		}else{
			$id = 1;
		}
		return $id;
	}
	
	private function page_replace($page) //��ַ�滻
	{
		return str_replace("{page}", $page, $this -> page_url);
	}
	
	
	private function myde_home() //��ҳ
	{
		if($this -> myde_page != 1){
		
			//return "    <li class=\"page_a\"><a href=\"".$this -> page_replace(1)."\"  title=\"��ҳ\" >��ҳ</a></li>\n";
			return "    <li class=\"page_a\"><a href=\"".($this -> index_url <>"" ? $this -> index_url : $this -> page_replace(1))."\"  title=\"��ҳ\" >��ҳ</a></li>\n";
			
		}else{
		
			return "    <li>��ҳ</li>\n";
			
		}
	}
	
	private function myde_prev() //��һҳ
	{
		if($this -> myde_page != 1){
		
			return "    <li class=\"page_a\"><a href=\"".(($this -> index_url <>"" && $this->myde_page-1 ==1) ? $this -> index_url : $this -> page_replace($this->myde_page-1)) ."\"  title=\"��һҳ\" >��һҳ</a></li>\n";
			
		}else{
		
			return "    <li>��һҳ</li>\n";
			
		}
	}
	
	private function myde_next() //��һҳ
	{
		if($this -> myde_page != $this -> myde_page_count){
		
				return "    <li class=\"page_a\"><a href=\"".$this -> page_replace($this->myde_page+1) ."\"  title=\"��һҳ\" >��һҳ</a></li>\n";
				
		}else{
		
			return "    <li>��һҳ</li>\n";
			
		}
	}
	
	private function myde_last() //βҳ
	{
		if($this -> myde_page != $this -> myde_page_count){
		
				return "    <li class=\"page_a\"><a href=\"".$this -> page_replace($this -> myde_page_count)."\"  title=\"βҳ\" >βҳ</a></li>\n";
				
		}else{
		
			return "    <li>βҳ</li>\n";
			
		}
	}
	
	function myde_write($id='page') //���
	{
		$str  = "<div id=\"".$id."\" class=\"pages\">\n  <ul>\n  ";
		
		$str .= "  <li>�ܼ�¼:<span>".$this -> myde_count."</span></li>\n";
		
		$str .= "    <li><span>".$this -> myde_page."</span>/<span>".$this -> myde_page_count."</span></li>\n";
		
		$str .= $this -> myde_home();
		
		$str .= $this -> myde_prev();
		
		for($page_for_i = $this -> page_i;$page_for_i <= $this -> page_ub; $page_for_i++){
				if($this -> myde_page == $page_for_i){
				
					$str .= "    <li class=\"on\">".$page_for_i."</li>\n";
					
				}else{
					if($page_for_i==1 && $this -> index_url <>""){
						$str .= "    <li class=\"page_a\"><a href=\"".$this -> index_url."\" title=\"��".$page_for_i."ҳ\">";
						
						$str .= $page_for_i . "</a></li>\n";
					}else{
						$str .= "    <li class=\"page_a\"><a href=\"".$this -> page_replace($page_for_i)."\" title=\"��".$page_for_i."ҳ\">";
						
						$str .= $page_for_i . "</a></li>\n";
					}
					
				}
        }
		$str .= $this -> myde_next();
		
		$str .= $this -> myde_last();
		
		$str .= "    <li class=\"pages_input\"><input type=\"text\" value=\"".$this -> myde_page."\"";
		
		$str .= " onkeydown=\"javascript: if(event.keyCode==13){ location='";
		
		$str .= $this -> page_replace("'+this.value+'")."';return false;}\"";
		
		$str .= " title=\"��������Ҫ�����ҳ��\" /></li>\n";
		
		$str .= "  </ul>\n  <div class=\"page_clear\"></div>\n</div>";
		
		return $str;
	}
}
/*-------------------------ʵ��--------------------------------*
$page = new PageClass(1000,5,$_GET['page'],'?page={page}');
$page = new PageClass(1000,5,$_GET['page'],'list-{page}.html');
$page -> myde_write();
*/
?>