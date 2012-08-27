<?php

class GoodsAction extends Action {

        function index()
        {
                
                if($_GET['id']){
                        
                
                //echo $_GET['id'];
                $a = D('Goods')->where('goods_url=' . '"' . $_GET['id'] . '"')->find() ;
                //echo D('Goods')->getLastSql();
                $b = unserialize($a['type_data']) ;
                //print_r($b) ;
                $aaaaa=$b['goodsurl'];
                
                echo "11111111111111";
                
                //redirect($aaaaa);
               // $url = "/index.php?app=home&mod=Test&act=url&id=10245949481" ;
               // $aaaa = "&ref=" . $url ;
               /// $abcd="http://s.click.taobao.com/t_js?tu=" . urlencode($aaaaa) . urlencode($aaaa) ;
                //echo "<a href='".$abcd."'>sdfasdf</a>";
                //redirect($abcd);
                //echo "Lacation:".$abcd."";
               /// header("Location:".$abcd."");
                }else{
                     //  $Model=new Model();
                    //   echo $DB_PREFIX;
                    //   $a=$Model->query("select * from ".C('DB_PREFIX')."weibo where goods_url='zhVrLsYX'");
                   //    print_r($a);
                       
                      //  echo U('home/user/index',array('id'=>'zhVrLsYX'));
                        
                       // echo "<a target='_blank' href='/index.php?app=weibo&mod=goods&act=index&id=zhVrLsYX'>测试淘宝客连接</a>";
                }
                
        }

}

?>
