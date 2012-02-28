<?php    
class RSS    
{    
    /**   
     +----------------------------------------------------------   
     * RSSƵ����   
     +----------------------------------------------------------   
     */   
    protected $channel_title = '';
    /**   
     +----------------------------------------------------------   
     * RSSƵ������   
     +----------------------------------------------------------   
     */   
    protected $channel_link = '';
    /**   
     +----------------------------------------------------------   
     * RSSƵ������   
     +----------------------------------------------------------   
     */   
    protected $channel_description = '';
    /**   
     +----------------------------------------------------------   
     * RSSƵ��ʹ�õ�Сͼ���URL   
     +----------------------------------------------------------   
     */   
    protected $channel_imgurl = '';
    /**   
     +----------------------------------------------------------   
     * RSSƵ����ʹ�õ�����   
     +----------------------------------------------------------   
     */   
    protected $language = 'zh_CN';
    /**   
     +----------------------------------------------------------   
     * RSS�ĵ��������ڣ�Ĭ��Ϊ����   
     +----------------------------------------------------------   
     */   
    protected $pubDate = '';
    protected $lastBuildDate = '';
     
    protected $generator = 'YBlog RSS Generator';
     
    /**   
     +----------------------------------------------------------   
     * RSS������Ϣ������   
     +----------------------------------------------------------   
     */   
    protected $items = array();
     
    /**   
     +----------------------------------------------------------   
     * ���캯��   
     +----------------------------------------------------------   
     * @access public    
     +----------------------------------------------------------   
     * @param string $title  RSSƵ����   
     * @param string $link  RSSƵ������   
     * @param string $description  RSSƵ������   
     * @param string $imgurl  RSSƵ��ͼ��   
     +----------------------------------------------------------   
     */   
    public function __construct($title, $link, $description, $generator, $imgurl = '')    
    {    
        $this->channel_title = $title;
        $this->channel_link = $link;
        $this->channel_description = $description; 
		$this->generator = $generator;
        $this->channel_imgurl = $imgurl;
        $this->pubDate = Date('Y-m-d H:i:s', time());
        $this->lastBuildDate = Date('Y-m-d H:i:s', time());
    }
     
    /**   
     +----------------------------------------------------------   
     * ����˽�б���   
     +----------------------------------------------------------   
     * @access public    
     +----------------------------------------------------------   
     * @param string $key  ������   
     * @param string $value  ������ֵ   
     +----------------------------------------------------------   
     */   
     public function Config($key,$value)    
     {    
        $this->{$key} = $value;
     }
     
    /**   
     +----------------------------------------------------------   
     * ���RSS��   
     +----------------------------------------------------------   
     * @access public    
     +----------------------------------------------------------   
     * @param string $title  ��־�ı���   
     * @param string $link  ��־������   
     * @param string $description  ��־��ժҪ   
     * @param string $pubDate  ��־�ķ�������   
     +----------------------------------------------------------   
     */   
     function AddItem($title, $link, $description, $pubDate)    
     {    
        $this->items[] = array('title' => $title, 'link' => $link, 'description' => ($this-> HtmlReplace($description)), 'pubDate' => $pubDate);
     }
     
     /**   
     +----------------------------------------------------------   
     * ���RSS��XMLΪ�ַ���   
     +----------------------------------------------------------   
     * @access public    
     +----------------------------------------------------------   
     * @return string   
     +----------------------------------------------------------   
     */   
    public function Fetch()    
    {    
        $rss = '<?xml version="1.0" encoding="gb2312"?>'."\r\n";
        $rss .= "<rss version=\"2.0\">\r\n";
        $rss .= "<channel>\r\n";
        $rss .= "<title><![CDATA[{$this->channel_title}]]></title>\r\n";
        $rss .= "<description><![CDATA[{$this->channel_description}]]></description>\r\n";
        $rss .= "<link>{$this->channel_link}</link>\r\n";
        $rss .= "<language>{$this->language}</language>\r\n";
     
        if (!empty($this->pubDate))    
            $rss .= "<pubDate>{$this->pubDate}</pubDate>\r\n";
        if (!empty($this->lastBuildDate))    
            $rss .= "<lastBuildDate>{$this->lastBuildDate}</lastBuildDate>\r\n";
        if (!empty($this->generator))    
            $rss .= "<generator>{$this->generator}</generator>\r\n";
     
        //$rss .= "<ttl>5</ttl>\r\n";
     
        if (!empty($this->channel_imgurl)) {    
            $rss .= "<image>\r\n";
            $rss .= "<title><![CDATA[{$this->channel_title}]]></title>\r\n";
            $rss .= "<link>{$this->channel_link}</link>\r\n";
            $rss .= "<url>{$this->channel_imgurl}</url>\r\n";
            $rss .= "</image>\r\n";
        }
     
        for ($i = 0; $i < count($this->items); $i++) {    
            $rss .= "<item>\r\n";
            $rss .= "<title><![CDATA[{$this->items[$i]['title']}]]></title>\r\n";
            $rss .= "<link>{$this->items[$i]['link']}</link>\r\n";
            $rss .= "<description><![CDATA[{$this->items[$i]['description']}]]></description>\r\n";
            $rss .= "<pubDate>{$this->items[$i]['pubDate']}</pubDate>\r\n";
            $rss .= "</item>\r\n";
        }
     
        $rss .= "</channel>\r\n</rss>";
        return $rss;
    }
	
	protected function HtmlReplace($str){
		//$str = strip_tags($str);
		return str_replace("&","&amp;",$str);
	}  
     
    /**   
     +----------------------------------------------------------   
     * ���RSS��XML�������   
     +----------------------------------------------------------   
     * @access public    
     +----------------------------------------------------------   
     * @return void   
     +----------------------------------------------------------   
     */   
    public function Display()    
    {    
echo $this->Fetch();
        exit;
    }
}
?>