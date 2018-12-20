<?php
namespace common\common;

use Yii;
/**
 * 微信类
 * @author He
 *
 */  
Class Wxinutil{
	/**
	 * 获取access_token
	 * @return unknown|Ambigous <number, unk nown>
	 */ 
	public static function getAccesstoken($appid,$appsecret=''){
		$key = 'accesstoken'.$appid;
		if(\yii::$app->cache->get($key)&&\yii::$app->cache->get($key)!=null){
			//若存在缓存，则调取并返回
			return \yii::$app->cache->get($key);
		}else{    
			if($appid){
				if(!isset($_SESSION['rev'])){
		            yii::$app->session['rev'] = yii::$app->params['replyVideo'];
		        }
		        $wxinfo = yii::$app->params['wxinfo'];
		        if($wxinfo){
					$wxinutil = new Wxinutil();
					$data = $wxinutil->getAccesstokenHttp($appid, $wxinfo['secret']);
					\yii::$app->cache->set($key,$data['accesstoken'],7000);		//设置缓存以及缓存过期时间
					return $data['accesstoken'];
		        }
			}
		 }  
	}
	
	public static function getAccesstoken2($appid,$appsecret=''){
		$key = 'accesstoken'.$appid;
		echo $key.'----';
		if(\yii::$app->cache->get($key)&&\yii::$app->cache->get($key)!=null){
			yii::trace('---------------------------------------222222=------');
			echo \yii::$app->cache->get($key);exit;
			//若存在缓存，则调取并返回
			return \yii::$app->cache->get($key);
		}else{ 
			if($appid){
				if(!isset($_SESSION['rev'])){
					yii::$app->session['rev'] = yii::$app->params['replyVideo'];
				}
				$wxinfo = yii::$app->params['wxinfo'];
				if($wxinfo){
					$wxinutil = new Wxinutil();
					$data = $wxinutil->getAccesstokenHttp($appid, $wxinfo['secret']);
					\yii::$app->cache->set($key,$data['accesstoken'],7000);		//设置缓存以及缓存过期时间
					yii::trace('---------------------------------------accesstoken=------'.$data['accesstoken']);
					return $data['accesstoken'];
				}
			}
		 } 
	}
	
	/**
	 * 获取access_token
	 * @param unknown $appid
	 * @param unknown $appsecret
	 * @return boolean|multitype:number unknown
	 */
	private function getAccesstokenHttp($appid,$appsecret){
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
		$output = $this->http_get($url);

		if($output==null){
			exit();
			// return false;
		}else{
			$opobj = json_decode($output);
			$access_token = $opobj->access_token;
			$expire_in = $opobj->expires_in-200;		//凭证有效时间
			$expire_time = time()+$expire_in;	//过期时间
			//	S('access_token',$access_token,$expire_in);		//设置缓存以及缓存过期时间
			return array('accesstoken'=>$access_token,'expire_time'=>$expire_time);
		}
	}
	
	/**
	 * curl方式获取数据
	 * @param unknown $url
	 * @param string $data
	 * @return mixed
	 */
	public static function https_request($url,$data=null){
		$curl = curl_init();
		curl_setopt($curl,CURLOPT_URL,$url);//用PHP取回的URL地址
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);//禁用后cURL将终止从服务端进行验证
		if (defined('CURLOPT_SAFE_UPLOAD')) {
			curl_setopt($curl, CURLOPT_SAFE_UPLOAD, false);
		}
		if(!empty($data)){
			curl_setopt($curl,CURLOPT_POST,1);//如果你想PHP去做一个正规的HTTP POST，设置这个选项为一个非零值。这个POST是普通的 application/x-www-from-urlencoded 类型，多数被HTML表单使用
			curl_setopt($curl,CURLOPT_POSTFIELDS,$data);//传递一个作为HTTP “POST”操作的所有数据的字符串
		}
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);//设定是否显示头信息
		$output = curl_exec($curl);
		curl_close($curl);
		return $output;
	}

	/**
	 * curl get方式 获取数据
	 * @param unknown $url
	 * @return mixed
	 */
	public static function http_get($url){
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($curl);
		curl_close($curl);
		return $output;
	}

	/**
	 *
	 * @param unknown $data  消息内容
	 * @param unknown $msgtype 消息类型
	 * @return unknown
	 */
	public function respMsg($data,$object=null){
		$resultStr = null;
		switch ($data['KeyWord']){
			case 'text': $resultStr=$this->respText($data);break;
			case 'test2': $resultStr=$this->respNews($data,$object);break;
			case '关注': $resultStr = $this->respScribe($data);break; 
			case '-1': $resultStr = $this->respText($data);break;
		};
		return $resultStr;
	}
	
	/**
	 * 关注订阅号自动回复
	 * @param unknown $data
	 * @return unknown|string
	 */
	public function respScribe($data){
		if(isset($data['ToUserName'])&&isset($data['FromUserName'])&&isset($data['Content'])){
			$textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Event><![CDATA[%s]]></Event>
						</xml>";
			$msgType = "subscribe";
		    $resultStr = sprintf($textTpl, $data['FromUserName'], $data['ToUserName'], time(),'text', $msgType);
			return $resultStr;
		}else{
			return '';
		}
	}
	
	/**
	 * 回复图片消息
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	public function respImages($data,$media_id){
		$textTpl = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[image]]></MsgType>
						<Image>
						<MediaId><![CDATA[%s]]></MediaId>
						</Image>
						</xml>";
		$resultStr = sprintf($textTpl,$data['FromUserName'], $data['ToUserName'], $data['Time'],$media_id);
		return $resultStr;
	}
	
	/**
	 * 文本类型
	 * @param array $data
	 * @param string $msgtype
	 * @return string|NULL
	 */
	public function respText($data){
		$textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[text]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							</xml>";
		$resultStr = sprintf($textTpl,  $data['FromUserName'], $data['ToUserName'], $data['Time'],$data['content']);
		return $resultStr;
	}

		/**
	 * 回复视频消息
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	public function respVideo($data,$info){
		$textTpl = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[video]]></MsgType>
						<Video>
						<MediaId><![CDATA[%s]]></MediaId>
						<Title><![CDATA[%s]]></Title>
						<Description><![CDATA[%s]]></Description>
						</Video> 
					</xml>";
		$resultStr = sprintf($textTpl,$data['FromUserName'], $data['ToUserName'], $data['Time'],$info->media_id,$info->vtitle,$info->vintroduction);
		return $resultStr;
	}
	
	
	/**
	 * 图文消息
	 * @param unknown $data
	 * @return unknown
	 */
	public function respNews($data,$artarr){
		if(count($artarr)>10){
			$count = 10;
		}elseif (count($artarr)<0){
			$count = 0;
		}else {
			$count = count($artarr);
		}
		$template = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[%s]]></MsgType>
					<ArticleCount>".$count."</ArticleCount>
					<Articles>";
		foreach($artarr as $k=>$v){
			$template .="<item>
						<Title><![CDATA[".$v->title."]]></Title>
						<Description><![CDATA[".$v->description."]]></Description>
						<PicUrl><![CDATA[".$v->image."]]></PicUrl>
						<Url><![CDATA[".$v->url."]]></Url>
						</item>";
		}
		$template .="</Articles>
					</xml> ";
		$resultStr = sprintf($template, $data['FromUserName'], $data['ToUserName'],$data['Time'], 'news');
		return $resultStr;
	}
	
	/**
	 * 自定义菜单
	 * @return Ambigous <\Manageadmin\Util\mixed, mixed>
	 */
	public function createMenu($menu,$appid){
		$count_p = count($menu);
		$i=0;
		$data = '{"button":[';
		foreach ($menu as $val) {
			$i++;
			$data.='{"name":"'.$val['name'].'",';//菜单头
			$count = isset($val['sub_button'])?count($val['sub_button']) :'0';
			//判断是否由子类
			if($count){//存在子菜单
				$data.='"sub_button":[';
				$k = 0;
				foreach ($val['sub_button'] as $v) {
					$k++;
					$data.='{"name":"'.$v['name'].'",';
					$data.='"type":"'.$v['type'].'",';
					if($v['type']=='click')
					{
						$data.='"key":"'.$v['key'].'"';
					}else{
						$data.='"url":"'.$v['content'].'"';
					}
		
					if($k == $count)
					{
						$data.= '}';
					}else{
						$data.= '},';
					}
				}
				if($i == $count_p)
				{
					$data.=']}';
				}else{
					$data.=']},';
				}
			}else{//不存在子菜单
				$data.='"type":"'.$val['type'].'",';
				if($val['type']=='click')
				{
					$data.='"key":"'.$val['key'].'"';
				}else{
					$data.='"url":"'.$val['content'].'"';
				}
		
				if($i == $count_p)
				{
					$data.= '}';
				}else{
					$data.= '},';
				}
			}
		}
		$data.=']}';
		$access_token =$this->getAccesstoken($appid) ;
		$url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=$access_token";
		$output = $this->https_request($url,$data);
		return $output;
	}
	
	/**
	 * 删除自定义菜单
	 * @param unknown $appid
	 * @return \common\wxinutil\mixed
	 */
	public function delMenu($appid){
		$access_token = $this->getAccesstoken($appid) ;
		$url = "https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=".$access_token;
		$output = $this->https_request($url);
		return $output;
	}
	/**
	 * 获取微信粉丝信息
	 * @param openid
	 * @param appid:微信appid
	 */
	public static function getUserinfo($openid,$appid){
		$accesstoken = self::getAccesstoken($appid);
		$url ="https://api.weixin.qq.com/cgi-bin/user/info?access_token=$accesstoken&openid=$openid&lang=zh_CN";
		$output = self::http_get($url);
		$user = json_decode($output);
		if(isset($user->subscribe)){		//存在则代表关注，并返回用户信息
			return $user;
		}else{
			return 'false';
		}
	}

	/**
	 * 获取二维码图片
	 * @param [type] appid 	   [description]
	 * @param [type] appsecret [description]
	 * @param [type] scene_str [参数]
	 * @param [type] imgdir    [二维码保存的]
	 */
	public function toTocket($appid,$scene_str,$imgdir){
		$accesstoken = $this->getAccesstoken($appid);
		$qrcode = '{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_str": $scene_str}}}';//永久二维码请求说明
		$url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$accesstoken";
		$result = $this->https_request($url,$qrcode);
		$jsoninfo = json_decode($result,true);
		$ticket = $jsoninfo['ticket'];//创建二维码ticket

		//下载二维码
		$url_ticket = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=$ticket";
		$imageInfo = $this->http_get($url_ticket);
		if(!is_dir($imgdir)){
			mkdir($imgdir,0777,true);
		}
		$filename = uniqid().rand(00,99)."qrcode.jpg";
		$savepath = $imgdir.$filename;
		$local_file = fopen($savepath,'w');
		if(false !== $local_file){
			if(false !== fwrite($local_file, $imageInfo)){
				fclose($local_file);
			}
		}
		return $filename;
	}

		/**
	 * 临时二维码
	 * @param unknown $appid
	 * @param unknown $scene_id  开发者自行设定的参数
	 */
	public function gettempcode($appid,$scene_id,$imgdir){
		$accesstoken = $this->getAccesstoken($appid);
		$qrcode = '{"expire_seconds": 1320, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": '.$scene_id.'}}}';
		$url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$accesstoken;
		$result = $this->https_request($url,$qrcode);
		$resultinfo = json_decode($result,true);
		if(isset($resultinfo['ticket'])){
			//下载二维码
			$ticket = $resultinfo['ticket'];
			$url_ticket = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=$ticket";
			$imageInfo = $this->http_get($url_ticket);
			if(!is_dir($imgdir)){
				mkdir($imgdir,0777,true);
			}
			$filename = uniqid().rand(00,99)."qrcode.jpg";
			$savepath = $imgdir.$filename;
			$local_file = fopen($savepath,'w');
			if(false !== $local_file){
				if(false !== fwrite($local_file, $imageInfo)){
					fclose($local_file);
				} 
			}
			return array('filename'=>$filename,'ticket'=>$ticket);
		}else {
			return false;
		}
	}
	
	/**
	 * 临时二维码
	 * @param unknown $appid
	 * @param unknown $scene_id  开发者自行设定的参数
	 */
	public function gettempcode2($appid,$scene_id,$imgdir){
		$accesstoken = $this->getAccesstoken($appid);
		$qrcode = '{"expire_seconds": 1320, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": '.$scene_id.'}}}';
		$url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$accesstoken;
		$result = $this->https_request($url,$qrcode);
		$resultinfo = json_decode($result,true);
		if(isset($resultinfo['ticket'])){
			//下载二维码
			$ticket = $resultinfo['ticket'];
			$url_ticket = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=$ticket";
			$imageInfo = $this->http_get($url_ticket);
			if(!is_dir($imgdir)){
				mkdir($imgdir,0777,true);
			}
			$filename = uniqid().rand(00,99)."qrcode.jpg";
			$savepath = $imgdir.$filename;
			$local_file = fopen($savepath,'w');
			if(false !== $local_file){
				if(false !== fwrite($local_file, $imageInfo)){
					fclose($local_file);
				}
			}
			return array('filename'=>$filename,'ticket'=>$ticket);
		}else {
			return false; 
		}
	}
	

	/**
	 * 上传图片获取media_id
	 * @param  [type] $data     [description]
	 * @param  [type] $appid    [description]
	 * @param  [type] $imageurl [description]
	 * @return [type]           [description]
	 */
	public function toImages($appid,$imageurl){
		$access_token = Wxinutil::getAccesstoken($appid);
		$url = "https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=$access_token&type=image";
		$file_info = array(
				'filename'=>$imageurl,
				'content-type'=>'image/jpg',
				'filelength'=>'11011',
		); 
		$real_path = $_SERVER['DOCUMENT_ROOT'].$file_info['filename'];
		$josn2 = array("media"=>"@$real_path");
		$result = Wxinutil::https_request($url,$josn2);
		$res = json_decode($result,true);
		if(!isset($res['media_id'])){
			exit();
		}
		return $res['media_id'];
	}

	/**
	 * 上传视频获取media_id
	 * @param  [type] $data     [description]
	 * @param  [type] $appid    [description]
	 * @param  [type] $imageurl [description]
	 * @return [type]           [description]
	 */
	public function toVideo($appid,$videourl,$title,$introduction){
		$access_token = Wxinutil::getAccesstoken($appid);
		$descr = array('title'=>$title,'introduction'=>$introduction);
		$description = json_encode($descr);
		$url = "https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=$access_token&type=video&description=$description";
		$real_path = $_SERVER['DOCUMENT_ROOT'].$videourl;
		$josn2 = array("media"=>"@$real_path");
		$result = Wxinutil::https_request($url,$josn2);
		$res = json_decode($result,true);
		if(!isset($res['media_id'])){
			exit();
		}
		return $res['media_id'];
	}

	/**
	 * 客服文本消息
	 */
	public function serviceMsgtext($data,$wxinfo){
		$accesstoken = $this->getAccesstoken($wxinfo['appid'],$wxinfo['secret']);
		$url ="https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=$accesstoken";
		$tempdata = '{
				    	"touser":"'.$data['FromUserName'].'",
				    	"msgtype":"text",
				    	"text":
				    	{
				         	"content":"'.$data['content'].'"
				    	}
					}';
		$output = json_decode($this->https_request($url,$tempdata),true);
		if($output['errcode']==0){
			return $output;
		}
	}

	/**
	 * 客服图文消息
	 */
	public function serviceMsgnews($data,$wxinfo,$artarr){
		$accesstoken = $this->getAccesstoken($wxinfo['appid'],$wxinfo['secret']);
		$url ="https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=$accesstoken";
		$tempdata = '{
				    "touser":"'.$data['FromUserName'].'",
				    "msgtype":"news",
				    "news":
				    {	"articles": [';
        foreach ($artarr as $k => $v) {
        	$tempdata .= '{
		            "title":"'.$v->title.'",
		            "description":"'.$v->description.'",
		            "url":"'.$v->url.'",
		            "picurl":"'.$v->image.'"
		        	},';
        }     
		$tempdata .=']}}';
		$output = json_decode($this->https_request($url,$tempdata),true);
		if($output['errcode']==0){
			return $output;
		}
	}

	/**
	 * 客服图片消息
	 */
	public function serviceMsgimages($data,$wxinfo){
		$accesstoken = $this->getAccesstoken($wxinfo['appid'],$wxinfo['secret']);
		$url ="https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=$accesstoken";
		$tempdata = '{
					    "touser":"'.$data['FromUserName'].'",
					    "msgtype":"image",
					    "image":
					    {
					      "media_id":"'.$data['content'].'"
					    }
					}';
		$output = json_decode($this->https_request($url,$tempdata),true);
		if($output['errcode']==0){
			return $output;
		}
	}

	/**
	 * 客服视频消息
	 */
	public function serviceMsgvideo($data,$wxinfo,$info){
		$accesstoken = $this->getAccesstoken($wxinfo['appid'],$wxinfo['secret']);
		$url ="https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=$accesstoken";
		$tempdata = '{
						"touser":"'.$data['FromUserName'].'",
					    "msgtype":"video",
					    "video":
					    {
						    "media_id":"'.$info->media_id.'",
					        "thumb_media_id":"'.$info->media_id.'",
					        "title":"'.$info->vtitle.'",
					        "description":"'.$info->vintroduction.'"
					    }
					}';
		$output = json_decode($this->https_request($url,$tempdata),true);
		if($output['errcode']==0){
			return $output;
		}
	}
	
	
	/**
	 * 发送模板
	 * @param unknown $appid
	 * @param unknown $openid
	 * @param unknown $template_id  模板id 必填
	 * @param string $url    模板跳转链接
	 * @param unknown $data 模板数据   必填
	 * $dataarr = array(
	 "name"=>array("value"=>"iphone7","color"=>"#173177"),
	 "price"=>array("value"=>"9000","color"=>"#173177"),
	 "time"=>array("value"=>"2016-9-21","color"=>"#173177"),
	 "remark"=>array("value"=>urlencode('感谢购买'),"color"=>"#173177"),
	 );
	 */
	public function sendTmpMessage($openid,$template_id,$tmpurl,$dataarr){
		if($openid!='' && $template_id!='' && is_array($dataarr)){
			$wxinfo = yii::$app->params['wxinfo'];
			$accesstoken = $this->getAccesstoken($wxinfo['appid'],$wxinfo['secret']);
			$url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=$accesstoken";
			$template = array(
					"touser"=>"$openid",
					"template_id"=>"$template_id",
					"url"=>urlencode("$tmpurl"),
			);
			$template['data'] = $dataarr;
			$temdata = urldecode(json_encode($template));
			$res = json_decode($this->https_request($url,$temdata),true);
			if($res['errcode']==0){
				return true;
			}else{
				return false;
			}
			 
		}
	}
	
}