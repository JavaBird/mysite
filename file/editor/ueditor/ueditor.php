<?php
date_default_timezone_set("Asia/chongqing");
error_reporting(E_ERROR);
header("Content-Type: text/html; charset=utf-8");
defined('IN_PHPCMS') or exit('No permission resources.'); 
pc_base::load_app_class('admin','admin',0);
include "uploader.class.php";



$CONFIG = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents("config.json")), true);

class ueditor extends admin
{
   private  $action;
   
        function __construct() {
        		parent::__construct();
        		$this->action = $_GET['action'];
        }
        
       public function ueditor(){
          
           $action = $this->action;
           
           switch ($action) {
               
               case 'config':
                   $result =  json_encode($CONFIG);
                   break;
           
                   /* 上传图片 */
               case 'uploadimage':
                   /* 上传涂鸦 */
               case 'uploadscrawl':
                   /* 上传视频 */
               case 'uploadvideo':
                   /* 上传文件 */
               case 'uploadfile':
                //   $result = include("action_upload.php");
                    
                   $result =  $this-> upload();
                   
                   break;
           
                   /* 列出图片 */
               case 'listimage':
               //    $result = include("action_list.php");
                   break;
                   /* 列出文件 */
               case 'listfile':
                //   $result = include("action_list.php");
                   break;
           
                   /* 抓取远程文件 */
               case 'catchimage':
                 //  $result = include("action_crawler.php");
                   break;
           
               default:
                   $result = json_encode(array(
                   'state'=> '请求地址出错'
                       ));
                   break;
           }
           
           /* 输出结果 */
           if (isset($_GET["callback"])) {
               if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
                   echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
               } else {
                   echo json_encode(array(
                       'state'=> 'callback参数不合法'
                   ));
               }
           } else {
               echo $result;
           }
           
       }
       
      public function upload(){
          
          /* 上传配置 */
          $base64 = "upload";
          switch (htmlspecialchars($_GET['action'])) {
              case 'uploadimage':
                  $config = array(
                  "pathFormat" => $CONFIG['imagePathFormat'],
                  "maxSize" => $CONFIG['imageMaxSize'],
                  "allowFiles" => $CONFIG['imageAllowFiles']
                  );
                  $fieldName = $CONFIG['imageFieldName'];
                  break;
              case 'uploadscrawl':
                  $config = array(
                  "pathFormat" => $CONFIG['scrawlPathFormat'],
                  "maxSize" => $CONFIG['scrawlMaxSize'],
                  "allowFiles" => $CONFIG['scrawlAllowFiles'],
                  "oriName" => "scrawl.png"
                      );
                  $fieldName = $CONFIG['scrawlFieldName'];
                  $base64 = "base64";
                  break;
              case 'uploadvideo':
                  $config = array(
                  "pathFormat" => $CONFIG['videoPathFormat'],
                  "maxSize" => $CONFIG['videoMaxSize'],
                  "allowFiles" => $CONFIG['videoAllowFiles']
                  );
                  $fieldName = $CONFIG['videoFieldName'];
                  break;
              case 'uploadfile':
              default:
                  $config = array(
                  "pathFormat" => $CONFIG['filePathFormat'],
                  "maxSize" => $CONFIG['fileMaxSize'],
                  "allowFiles" => $CONFIG['fileAllowFiles']
                  );
                  $fieldName = $CONFIG['fileFieldName'];
                  break;
          }
          
          /* 生成上传实例对象并完成上传 */
          $up = new uploader($fieldName, $config, $base64);
          
          /**
           * 得到上传文件所对应的各个参数,数组结构
           * array(
           *     "state" => "",          //上传状态，上传成功时必须返回"SUCCESS"
           *     "url" => "",            //返回的地址
           *     "title" => "",          //新文件名
           *     "original" => "",       //原始文件名
           *     "type" => ""            //文件类型
           *     "size" => "",           //文件大小
           * )
           */
          
          /* 返回数据 */
          return json_encode($up->getFileInfo());
          
          
          
      }
       
      
}

?>