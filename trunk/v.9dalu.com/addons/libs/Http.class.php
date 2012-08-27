<?php
// +----------------------------------------------------------------------
// | ThinkPHP
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$

/**
 +------------------------------------------------------------------------------
 * Http 工具类
 * 提供一系列的Http方法
 +------------------------------------------------------------------------------
 * @category   ORG
 * @package  ORG
 * @subpackage  Net
 * @author    liu21st <liu21st@gmail.com>
 * @version   $Id$
 +------------------------------------------------------------------------------
 */
class Http extends Think
{//类定义开始

    /**
     +----------------------------------------------------------
     * 采集远程文件
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param string $remote 远程文件名
     * @param string $local 本地保存文件名
     +----------------------------------------------------------
     * @return mixed
     +----------------------------------------------------------
     */
    static public function curl_download($remote,$local) {
        $cp = curl_init($remote);
        $fp = fopen($local,"w");
        curl_setopt($cp, CURLOPT_FILE, $fp);
        curl_setopt($cp, CURLOPT_HEADER, 0);
        curl_exec($cp);
        curl_close($cp);
        fclose($fp);
    }

    /**
     +----------------------------------------------------------
     * 下载文件
     * 可以指定下载显示的文件名，并自动发送相应的Header信息
     * 如果指定了content参数，则下载该参数的内容
     +----------------------------------------------------------
     * @static
     * @access public
     +----------------------------------------------------------
     * @param string $filename 下载文件名
     * @param string $showname 下载显示的文件名
     * @param string $content  下载的内容
     * @param integer $expire  下载内容浏览器缓存时间
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     * @throws ThinkExecption
     +----------------------------------------------------------
     */
    static public function download ($filename, $showname='',$content='',$expire=180) {
        if(is_file($filename)) {
            $length = filesize($filename);
        }elseif(is_file(UPLOAD_PATH.$filename)) {
            $filename = UPLOAD_PATH.$filename;
            $length = filesize($filename);
        }elseif($content != '') {
            $length = strlen($content);
        }else {
            throw_exception($filename.L('下载文件不存在！'));
        }
        if(empty($showname)) {
            $showname = $filename;
        }
		//造成部分中文文件的乱码问题
        //$showname = basename($showname);
		if(!empty($filename)) {
	        $type = mime_content_type($filename);
		}else{
			$type	 =	 "application/octet-stream";
		}
        //发送Http Header信息 开始下载
        header("Pragma: public");
        header("Cache-control: max-age=".$expire);
        //header('Cache-Control: no-store, no-cache, must-revalidate');
        header("Expires: " . gmdate("D, d M Y H:i:s",time()+$expire) . "GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s",time()) . "GMT");
        header("Content-Length: ".$length);
        header("Content-type: ".$type);
        header("Content-Disposition: attachment; filename= ".$showname." ");
        header('Content-Encoding: none');
        header("Content-Transfer-Encoding: binary" );
        if($content == '' ) {
            readfile($filename);
        }else {
        	echo($content);
        }
        exit();
    }

    /**
     +----------------------------------------------------------
     * 显示HTTP Header 信息
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    static function get_header_info($header='',$echo=true)
    {
        ob_start();
        $headers   = getallheaders();
        if(!empty($header)) {
            $info = $headers[$header];
            echo($header.':'.$info."\n"); ;
        }else {
            foreach($headers as $key=>$val) {
                echo("$key:$val\n");
            }
        }
        $output = ob_get_clean();
        if ($echo) {
            echo (nl2br($output));
        }else {
            return $output;
        }

    }

    /**
     * HTTP Protocol defined status codes
     * @param int $num
     */
	static function send_http_status($code) {
		static $_status = array(
			// Informational 1xx
			100 => 'Continue',
			101 => 'Switching Protocols',

			// Success 2xx
			200 => 'OK',
			201 => 'Created',
			202 => 'Accepted',
			203 => 'Non-Authoritative Information',
			204 => 'No Content',
			205 => 'Reset Content',
			206 => 'Partial Content',

			// Redirection 3xx
			300 => 'Multiple Choices',
			301 => 'Moved Permanently',
			302 => 'Found',  // 1.1
			303 => 'See Other',
			304 => 'Not Modified',
			305 => 'Use Proxy',
			// 306 is deprecated but reserved
			307 => 'Temporary Redirect',

			// Client Error 4xx
			400 => 'Bad Request',
			401 => 'Unauthorized',
			402 => 'Payment Required',
			403 => 'Forbidden',
			404 => 'Not Found',
			405 => 'Method Not Allowed',
			406 => 'Not Acceptable',
			407 => 'Proxy Authentication Required',
			408 => 'Request Timeout',
			409 => 'Conflict',
			410 => 'Gone',
			411 => 'Length Required',
			412 => 'Precondition Failed',
			413 => 'Request Entity Too Large',
			414 => 'Request-URI Too Long',
			415 => 'Unsupported Media Type',
			416 => 'Requested Range Not Satisfiable',
			417 => 'Expectation Failed',

			// Server Error 5xx
			500 => 'Internal Server Error',
			501 => 'Not Implemented',
			502 => 'Bad Gateway',
			503 => 'Service Unavailable',
			504 => 'Gateway Timeout',
			505 => 'HTTP Version Not Supported',
			509 => 'Bandwidth Limit Exceeded'
		);
		if(array_key_exists($code,$_status)) {
			header('HTTP/1.1 '.$code.' '.$_status[$code]);
		}
	}
}//类定义结束
?>