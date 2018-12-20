<?php
namespace common\common;
use yii;
use common\models\Condition;
use common\models\User;

/**
 * 全局基础公共类方法
 * @author HanksGump
 *
 */
class Helper{
	
	/**
	 * 通过ip获取城市地址
	 */
	public static function getAddressByIp($ip){
		//判断限制IP区域范围
		$ipinfo =file_get_contents("http://ip.taobao.com/service/getIpInfo.php?ip=".$ip);
		$ipinfo = json_decode($ipinfo);
		if($ipinfo->code){		//若code为1，即查询失败，则显示默认的城市，广州：020
			return $city = '广州';
		}
		$data = (array) $ipinfo->data;
		//判断省份
		return $data['city'];	
	}
	
	/**
	 * 排序
	 * @param unknown $arr	待排序的数组
	 * @param unknown $left	排序起始下标
	 * @param unknown $right  排序结束下标
	 * @param string $comp
	 * @return unknown
	 */
	public static function arr_sort($arr,$left,$right,$comp='>'){
		if($left<$right){
			$i = $left;
			$j = $right;
			$target = $arr[$left];
			while($i<$j){
				while($i<$j && $arr[$j]<$target){
					$j--;
				}
				if($i<$j){
					$arr[$i++] = $arr[$j];
				}
				while($i<$j && $arr[$i]>$target){
					$i++;
				}
				if($i<$j){
					$arr[$j--] = $arr[$i];
				}
			}
			$arr[$i] = $target;
			$arr = self::quick_sort($arr,$left,$i-1);		//对筛选出的较小一列数组进行 排序
			$arr = self::quick_sort($arr,$i+1,$right);		//对右边较大数组进行排序
		}
		return $arr;
	}
	
	/**
	 * 数据过滤,默认为STRING,若匹配不成功，则返回false,匹配成功，返回原值
	 *  已有快捷规则：整形，手机号，电子邮件，
	 * @param $data 需过滤的数据
	 * $param $type 数据类型
	 * $param $regex 正则规则:可自定义正则规则
	 */
	public static function filtdata($data,$type="STRING",$regex=null){
		//1.首先是去空去html标签
		$data = trim(htmlspecialchars($data));
		$res = true;
		switch ($type){
			case 'STRING':break;
			//整形表达式(不包含0)
			case 'INT':$res = preg_match("/^[1-9]\d*$/",$data);$res = $res?true:false;break;  
			//手机号规则
			case 'PHONE':$res = preg_match("/^[1][0-9]{10}$/",$data);$res = $res?true:false;break;
			//电子邮件
			case 'EMAIL':$res = preg_match("/^[A-Za-z0-9_]+(\.[_A-Za-z0-9_]+)*@[A-Za-z0-9]+(\.[A-Za-z0-9]+)*$/",$data);$res = $res?true:false;break;
			//正整数表达式（包括0）
			case 'INTEGER':$res = preg_match("/^([0]|([1-9][0-9]*))$/",$data);$res = $res?true:false;break;
			//固话规则
			case 'FIXPHONE':$res = preg_match("/^(0[0-9]{2,3})?([2-9][0-9]{6,7})+(\-[0-9]{1,4})?$/",$data);$res = $res?true:false;break;
			//金额规则：要求：整数位最多十位，小数为最多为两位，可以无小数位
			case 'MONEY': $res = preg_match("/^(([0-9]|([1-9][0-9]{0,9}))((\.[0-9]{1,2})?))$/",$data);$res = $res?true:false;break;
			//自定义
			case 'SELF':$res = preg_match("/$regex/",$data);break;
			default:$res = false;break;
		}
		if($res!==false){
			return $data;
		}else{
			return false;
		}
	}
	
	/**
	 * 从年月日计算出年龄
	 * @param  [type] $byear  出生的年份
	 * @param  [type] $bmonth 出生的月份
	 * @param  [type] $bday   出生的日期
	 * @return array
	 * author:he
	 */
	public static function calAge($byear,$bmonth,$bday) {
		if(!$byear||!$bmonth||!$bday){
			return false;
			exit;
		}
		list($year, $month, $day) = explode('-', date('Y-m-d',time()));//现在的时间
		$bmonth = intval($bmonth);//将变量转为整形类型
		$bday = intval($bday);
		if ($bmonth < 10) {//月份小于10的在前面加0
			$bmonth = '0' . $bmonth;
		}
		if ($bday < 10) {
			$bday = '0' . $bday;
		}
		$bi = intval($byear . $bmonth . $bday);//出生年月日
		$ni = intval($year . $month . $day);//现在的时间
		$years = 0;
		while (($bi + 10000) <= $ni) {//获取岁数
			$bi += 10000;
			$years++;
		}//得到岁数
		
		$data = yii::$app->params['data'];
		$age = $data['age'];
		$val = '';
		foreach ($age as $k=>$v){
			if($v==$years){
				$val = $k;
			}
		}
		$val = ($val)?$val:'-1';
		// 检查参数有效性
		if ($bmonth < 1 || $bmonth > 12 || $bday < 1 || $bday > 31)
			return (false);
		// 星座名称以及开始日期
		$signs = array(
				array( "20" => "10"),
				array( "19" => "11"),
				array( "21" => "0"),
				array( "20" => "1"),
				array( "21" => "2"),
				array( "22" => "3"),
				array( "23" => "4"),
				array( "23" => "5"),
				array( "23" => "6"),
				array( "24" => "7"),
				array( "22" => "8"),
				array( "22" => "9")
		);
		list($sign_start, $sign_name) = each($signs[(int)$bmonth-1]);
		if ($bday < $sign_start)
			list($sign_start, $sign_name) = each($signs[($bmonth -2 < 0) ? $bmonth = 11: $bmonth -= 2]);

		return ['age'=>$val,'constellation'=>$sign_name];
	}


	/**
	 * 异步上传图片
	 * @param [type] $imgb 图片文件流
	 * @param [type] $imgdir 图片保存的路劲 
	 * @return true or false。
	 * author:he
	 */
	public static function imgurl($imgb,$imgdir){
		if(!is_dir($imgdir)){
			mkdir($imgdir,0777,true);
		}
		$savename = uniqid () . '.jpeg';
		$savepath = $imgdir . $savename;
		$claerBase64 = explode ( ',', $imgb );
		//将base64码转为图片
		$ifp = fopen ( $savepath, "wb" );
		fwrite ( $ifp, base64_decode ( $imgb ) );
		fclose ( $ifp );
		return $savename;//true则上传成功，false则上传失败
	}
	
	/**
	 * 男女方条件
	 * @param unknown $condition  条件数组
	 * @param unknown $dpost  //$dpost = array_keys ( $_POST ); // 获取post提交的键名数组
	 */
	public static function condition($condition,$dpost){
		$arr = array ();
		$arr ['m'] = array ();
		$arr ['w'] = array ();
		foreach ( $condition as $con ) {
			foreach ( $dpost as $dp ) {
				if ($dp == 'm' . $con ['ename']||$dp == 'md' . $con ['ename'] ||$dp == 'mu' . $con ['ename']) {//男方条件
					$tmparr = array ();
					if($con['compare']=='between'){//区间选择条件
						if(!empty($_POST['m' . $con ['ename']])){
							$mvalue = explode("~",$_POST['m' . $con ['ename']]); //获取上下限值
							
							$mdvalue = Helper::filtdata($mvalue[0],$type='SELF',$regex=$con['rule']);    // 男方上限条件;
							$muvalue = Helper::filtdata($mvalue[1],$type='SELF',$regex=$con['rule']);    // 男方下限条件
							if($mdvalue===false || $muvalue===false){
								return [
										'info' => '您好,男方条件数据填写错误，请重写',
										'errorcode' => '1001'
										];
								exit ();
							}
							if($mdvalue>$muvalue){
								return [
										'info' => "您好,男方".$con['zname']."的下限不能超过上限,请重写",
										'errorcode' => '1001',
										];
								exit ();
							}
							if($mdvalue!=-1){//区间
								$tmparr ['d'] = $mdvalue;    // 男方上限条件
								$tmparr ['u'] = $muvalue;    // 男方下限条件
								$tmparr ['comp'] = 'between'; // 男方比较类型
								$arr ['m'] [$con ['ename']] = $tmparr;
							}
						}
					}else{//非区间选择条件
						$mcoditval = Helper::filtdata($_POST ['m' . $con ['ename']],$type='SELF',$regex=$con['rule']);    // 男方条件
						if($mcoditval===false){
							return [
									'info' => '您好,男方条件数据填写错误，请重写',
									'errorcode' => '1001'
									];
							exit ();
						}
						if($mcoditval!=-1){//如果为不限则不保存数据库)
							$tmparr ['v'] = $mcoditval;
							$tmparr ['comp'] = Helper::filtdata($_POST ['m' . $con ['ename'] . 'cp']); // 男方比较类型
							$arr ['m'] [$con ['ename']] = $tmparr;
						}
					}
		
				} else if ($dp == 'f' . $con ['ename'] ||$dp == 'fd' . $con ['ename'] ||$dp == 'fu' . $con ['ename']) {//女方条件
					$ftmparr = array ();
					if($con ['compare']=='between'){//区间选择条件
						if(!empty($_POST['f' . $con ['ename']])){
							$fvalue = explode("~",$_POST['f' . $con ['ename']]); //获取上下限值
							$fdvalue = Helper::filtdata($fvalue[0],$type='SELF',$regex=$con['rule'] );   // 女方上限条件 $_POST ['fd' . $con ['ename']]
							$fuvate = Helper::filtdata($fvalue[1],$type='SELF',$regex=$con['rule'] );   // 女方下限条件$_POST ['fu' . $con ['ename']]
							if($fdvalue===false || $fuvate===false){
								return [
										'info' => '您好,女方条件数据填写错误，请重写',
										'errorcode' => '1001'
										];
								exit ();
							}
							if($fdvalue>$fuvate){
								return [
										'info' => "您好,女方".$con['zname']."的下限不能超过上限,请重写",
										'errorcode' => '1001'
										];
								exit ();
							}
							if($fdvalue!=-1){//区间(如果为不限则不保存数据库)
								$ftmparr ['d'] = $fdvalue;
								$ftmparr ['u'] = $fuvate;
								$ftmparr ['comp'] = 'between';// 女方比较类型 Helper::filtdata($_POST ['f' . $con ['ename'] . 'cp'])
								$arr ['w'] [$con ['ename']] = $ftmparr;
							}
						}
					}else{//非区间选择条件
						$fcoditval = Helper::filtdata($_POST ['f' . $con ['ename']],$type='SELF',$regex=$con['rule'] );   // 女方条件;
						if($fcoditval===false){
							return [
									'info' => '您好,女方条件数据填写错误，请重写',
									'errorcode' => '1001'
									];
							exit ();
						}
						if($fcoditval!=-1){
							$ftmparr ['v'] = $fcoditval;
							$ftmparr ['comp'] = Helper::filtdata($_POST ['f' . $con ['ename'] . 'cp']);// 女方比较类型
							$arr ['w'] [$con ['ename']] = $ftmparr;
						}
					}
				}
			}
		}
		if (isset ( $_POST ['mcondition'] )) {//男方备注条件
			$arr ['m'] ['else'] = json_encode ( ($_POST ['mcondition']) );
		}
		if (isset ( $_POST ['fcondition'] )) {//女方备注条件
			$arr ['w'] ['else'] = json_encode ( ($_POST ['fcondition']) );
		}
	    return  [
				'info' => $arr,
				'errorcode' => '0'
					] ;
		exit ();
	}
	

	/**
     * 获取列表（分页）
     * @param unknown $query
     * @param number $curPage
     * @param number $pageSize
     * @param string $search
     * @return multitype:number multitype: |unknown
     */
    public static function getPages($query,$curPage = 1,$pageSize = 5 ,$search = null)
    {
        if (!empty($search)) {
            $query->andWhere($search);
        }
        if(is_array($query)){
        	$data['count'] = count($query);
        }else{
          $data['count'] = $query->count();
        }
        if(!$data['count'])
            return ['count'=>0,'curPage'=>$curPage,'pageSize'=>$pageSize,'start'=>0,'end'=>0,'data'=>[]];
        $curPage = (ceil($data['count']/$pageSize)<$curPage)?ceil($data['count']/$pageSize):$curPage;
   
        $data['curPage'] = $curPage;
        //每页显示条数
        $data['pageSize'] = $pageSize;
        //起始页
        $data['start'] = ($curPage-1)*$pageSize+1;
        //末页
        $data['end'] = (ceil($data['count']/$pageSize) == $curPage)?$data['count']:($curPage-1)*$pageSize+$pageSize;
        //数据
        $data['data'] = $query->offset(($curPage-1)*$pageSize)->limit($pageSize);
   
        return $data;
   
    }

    /**
     * [uploads description] 自定义的多文件上传方法
     * @param  string $name         [description] input表单中的name
     * @param  [type] $setsuffix    [description] 文件保存后缀
     * @param  string $save_dir     [description] 文件保存路径
     * @param  array  $allow_suffix [description] 允许上传的文件后缀
     * @return [type]               [description] 返回图片文件路径组成的数组
     */
    public static function uploads($name="showpic",$setsuffix="jpeg",$save_dir="",$allow_suffix=array('jpg','jpeg','gif','png')) {

        //拼接保存目录
        $save_dir = rtrim($save_dir,'/').'/';
          
        //判断保存目录是否存在
        if(!file_exists($save_dir)){ 
            mkdir($save_dir,0777,true);
        }

        $num = count($_FILES[$name]['tmp_name']);

        //循环处理上传
        for($i=0;$i <$num;$i++){
            //获取文件原后缀
            $suffix = trim(strrchr($_FILES[$name]['name'][$i],'.'),'.');

            //判断原后缀是否是允许上传的格式
            if(!in_array($suffix,$allow_suffix)){
                echo '错误提示：';
                echo $suffix.' 为不允许上传的文件类型';
               exit;
            }

            //判断是不是post上传
            if(!is_uploaded_file($_FILES[$name]['tmp_name'][$i])){
                echo '错误提示：';
                echo '非法上传，文件不是post获得的';
                exit;
            }

            //判断错误
            if($_FILES[$name]['error'][$i]>0){
                echo '错误提示：';
                echo '文件上传错误<br />';
                echo '下标error为 '.$_FILES[$name]['error'][$i];
                exit;
            }

            //如果保存后缀没定义就使用文件原后缀
            if(!$setsuffix){
                $setsuffix = $suffix;
            }

            //得到上传后文件名 
            $new_file_name =  rand(1,1000).substr(md5($_FILES[$name]['name'][$i]),20).'.'.$setsuffix;
            //拼接完整路径
            $new_path = $save_dir.$new_file_name;
            //上传文件 把tmp文件移动到保存目录中
            if(!move_uploaded_file($_FILES[$name]['tmp_name'][$i],$new_path)){
                echo '错误提示：';
                echo '把文件从临时文件夹移动到保存目录时发送错误';
                exit;
            }
            
            //返回由图片文件路径组成的数组
            $new_name[] = $new_file_name;
        }

        return $new_name;
    }

     public static function upload($name="showpic",$setsuffix="jpeg",$save_dir="",$allow_suffix=array('jpg','jpeg','gif','png')) {
     	//拼接保存目录
        $save_dir = rtrim($save_dir,'/').'/';
          
        //判断保存目录是否存在
        if(!file_exists($save_dir)){ 
            mkdir($save_dir,0777,true);
        }

        //处理上传
        //获取文件原后缀
        $suffix = trim(strrchr($_FILES[$name]['name'],'.'),'.');

        //判断原后缀是否是允许上传的格式
        if(!in_array($suffix,$allow_suffix)){
            echo '错误提示：';
            echo $suffix.' 为不允许上传的文件类型';
           exit;
        }

        //判断是不是post上传
        if(!is_uploaded_file($_FILES[$name]['tmp_name'])){
            echo '错误提示：';
            echo '非法上传，文件不是post获得的';
            exit;
        }

        //判断错误
        if($_FILES[$name]['error']>0){
            echo '错误提示：';
            echo '文件上传错误<br />';
            echo '下标error为 '.$_FILES[$name]['error'][$i];
            exit;
        }

        //如果保存后缀没定义就使用文件原后缀
        if(!$setsuffix){
            $setsuffix = $suffix;
        }

        //得到上传后文件名 
        $new_file_name = rand(1,1000).substr(md5($_FILES[$name]['name']),20).'.'.$setsuffix;
        //拼接完整路径
        $new_path = $save_dir.$new_file_name;
        //上传文件 把tmp文件移动到保存目录中
        if(!move_uploaded_file($_FILES[$name]['tmp_name'],$new_path)){
            echo '错误提示：';
            echo '把文件从临时文件夹移动到保存目录时发送错误';
            exit;
        }
            
        //返回由图片文件路径组成的数组
        return $new_file_name;
    }
    /** 
     * 对象数组转为普通数组 
     * 
     * AJAX提交到后台的JSON字串经decode解码后为一个对象数组， 
     * 为此必须转为普通数组后才能进行后续处理， 
     * 此函数支持多维数组处理。 
     * 
     * @param array 
     * @return array 
     */
    function object_array($array) {
        $arr = array(); 
        if(is_object($array)) {  
            $arr = (array)$array;  
         } if(is_array($array)) {  
             foreach($array as $key=>$value) {  
                 $arr[$key] = object_array($value);  
                 }  
         }  
         return $arr;  
    }  

    /**
     * 删除文件夹及其文件夹下所有文件
     * @param  [redi] $dir [要删除的文件夹]
     * @return [type]      [description]
     */
    function deldir($dir) {
      //先删除目录下的文件：
      $dh=opendir($dir);
      while ($file=readdir($dh)) {
        if($file!="." && $file!="..") {
          $fullpath=$dir."/".$file;
          if(!is_dir($fullpath)) {
              unlink($fullpath);
          } else {
              deldir($fullpath);
          }
        }
      }    
      closedir($dh);
      //删除当前文件夹：
      if(rmdir($dir)) {
        return true;
      } else {
        return false;
      }
    }
    
    /**
     * 删除文件夹下所有文件
     * @param  [redi] $dir [要删除的文件夹]
     * @return [type]      [description]
     */
    function deletedir($dir) {
    	//先删除目录下的文件：
    	$dh=opendir($dir);
    	while ($file=readdir($dh)) {
    		if($file!="." && $file!="..") {
    			$fullpath=$dir."/".$file;
    			if(!is_dir($fullpath)) {
    				unlink($fullpath);
    			} else {
    				deldir($fullpath);
    			}
    		}
    	}
    	closedir($dh);
    	//删除当前文件夹：
    	return true;
    }

    /**
     * 无限极分类生成树方法,巧在引用
     * @param  [type] $items [description]
     * @return [type]        [description]
     */
    function subtree($arr,$pid=0,$lev=1) {
    	if(!$arr)
    		return false;
    	
		static $subs = array(); //子孙数组
		foreach ($arr as $v) {
			if ($v['pid'] == $pid) {
				$v['lev'] = $lev;
				$subs[] = $v; //举例说array('id'=>1,'name'=>'安徽','parent'=>0),
				$this->subtree($arr,$v['id'],$lev+1);
			}
		}
		return $subs;
	}
	
	
	/**
	 * 检测数据
	 * $type=1 检测数据不为false
	 * $type =2 字符串类型不为空
	 * @param unknown $data
	 * @return multitype:number unknown |multitype:number
	 */
	public static function checkingdata($data,$type=1){
		if($type==1){
			foreach ($data as $k=>$d){
				if($d===false){
					return ['info'=>$k,'errorcode'=>1001];$break;
				}
			}
		}elseif($type==2){
			foreach ($data as $k=>$d){
				if($d==''){
					return ['info'=>$k,'errorcode'=>1001];$break;
				}
			}
		}
		return ['errorcode'=>0];
	}
	
	
	//获取sign
	/*
	
	* */
	public static function getSign($obj,$key=null) {
		foreach ($obj as $k=>$v){
			$Parameters[$k] = $v;
		}
		//签名步骤一：按字典序排序参数
		ksort($Parameters);
		$helper = new Helper();
		$String = $helper->formatBizQueryParaMap($Parameters,false);
		//签名步骤二：在string后面加入KEY
		if($key){
			$String = $String."&key=".$key;
		}
		//签名步骤三：sha1加密
		$String = sha1($String);
		$result = strtoupper($String);
		//签名步骤四：所有字符串转为大写
		return $result;
		
		
		/* //签名步骤一：按字典序排序参数 
		ksort($darr);
		$temp = '';
		//签名步骤二：拼接参数
		foreach ($darr as $k=>$d){
			$temp .= $k . "=" . $d . "&";
		}
		$string='';
		if (strlen($temp) > 0)
		{
			$string = substr($temp, 0, strlen($temp)-1);
		}
		$sign = sha1($string);
		return $sign; */
	}
	
	
	/*
	 * 格式化参数，签名过程需要使用
	*/
	function formatBizQueryParaMap($paraMap, $urlencode){
		$buff = "";
		ksort($paraMap);
		foreach ($paraMap as $k => $v)
		{
			if($urlencode)
			{
				$v = urlencode($v);
			}
			//$buff .= strtolower($k) . "=" . $v . "&";
			$buff .= $k . "=" . $v . "&";
		}
		$reqPar; 
		if (strlen($buff) > 0)
		{
			$reqPar = substr($buff, 0, strlen($buff)-1);
		}
		return $reqPar;
	}
	
	/**
	 * 检测字符串是否有特殊字符
	 * @param  [type] $str [description]
	 * @return [type]      [description]
	 */
	function filterEmoji($str){
	 	$str = preg_replace_callback(
	   	'/./u',
	   	function (array $match) {
	    	return strlen($match[0]) >= 4 ? '' : $match[0];
	    },$str);
	 	if(strlen($str)==0){
	 		$str = '16游用户'.rand(0,9999).rand(0,9999);
	 	}
	  	return $str;
	} 

		/**
	 * 按某个字段排序
	 * @param  [arr] $arrUsers   [需要排序的数组]
	 * @param  [string] $field  [排序字段 ]
	 * @param  [string] $direction [排序顺序标志 SORT_DESC 降序；SORT_ASC 升序]
	 * @return [arr]        [description]
	 */
	public static function quick_sort($arrUsers,$field,$direction='SORT_DESC'){
		if(!is_array($arrUsers) || !$arrUsers){
			return $arrUsers;
			exit;
		} 
		$sort = array(  
	        'direction' => $direction, //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序  
	        'field'     => $field,       //排序字段  
		);  
		$arrSort = array();  
		foreach($arrUsers AS $uniqid => $row){  
		    foreach($row AS $key=>$value){  
		        $arrSort[$key][$uniqid] = $value;  
		    }  
		}  
		if($sort['direction']){  
		    array_multisort($arrSort[$sort['field']], constant($sort['direction']), $arrUsers);  
		}  
		return $arrUsers;
	}

	/**
	 * 用户VIP等级
	 * @param  [string] $price [description]
	 * @return [arr]        [num等级  num1等级金钱]
	 */
	public static function vipSort($price=0){
		if(!$price||$price<=5){
            $num = 0;
            $num1 = 5;
        }elseif($price>5&&$price<=100){
            $num = 1;
            $num1 = 100;
        }elseif($price>100&&$price<=500){
            $num = 2;
            $num1 = 500;
        }elseif($price>500&&$price<=1000){
            $num = 3;
            $num1 = 1000;
        }elseif($price>1000&&$price<=5000){
            $num = 4;
            $num1 = 5000;
        }elseif($price>5000&&$price<=10000){
            $num = 5;
            $num1 = 10000;
        }elseif($price>10000&&$price<=20000){
            $num = 6;
            $num1 = 20000;
        }elseif($price>20000&&$price<=50000){
            $num = 7;
            $num1 = 50000;
        }elseif($price>50000&&$price<=100000){
            $num = 8;
            $num1 = 100000;
        }elseif($price>100000&&$price<=200000){
            $num = 9;
            $num1 = 200000;
        }elseif($price>200000&&$price<=500000){
        	$num = 10;
        	$num1 = 500000;
        }elseif($price>500000&&$price<=1000000){
        	$num = 11;
        	$num1 = 1000000;
        }else{
            $num = 12;
            $num1 = 1000000;
        }
        return ['num'=>$num,'num1'=>$num1];
	}

	/**
	 * 判断是移动端还是pc
	 * @return boolean [description]
	 */
	public static function isMobile(){ 
	    //如果有HTTP_X_WAP_PROFILE则一定是移动设备
	    if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
	    {
	        return true;
	    } 
	    //如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
	    if (isset ($_SERVER['HTTP_VIA']))
	    { 
	        // 找不到为flase,否则为true
	        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
	    } 
	    //判断手机发送的客户端标志,兼容性有待提高
	    if (isset ($_SERVER['HTTP_USER_AGENT']))
	    {
	        $clientkeywords = array(
	            'nokia','sony','ericsson','mot',
	            'samsung','htc','sgh','lg','sharp','sie-',
	            'philips','panasonic','alcatel','lenovo','iphone',
	            'ipod','blackberry','meizu','android','netfront',
	            'symbian','ucweb','windowsce','palm','operamini',
	            'operamobi','openwave','nexusone','cldc','midp',
	            'wap','mobile'
	        ); 
	        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
	        {
	            return true;
	        } 
	    } 
	    return false;
	} 
	
   /**
     * 验证用户session的平台id 
     */
    public static function setusersession($user){
    	$user_res = User::findOne(['id'=>$user->id]);
    	if($user_res){
    		if($user_res->pid != $user->pid){//平台id不同时则更改session
    			yii::$app->session['user'] = $user_res;
    			return true;
    		}
    	}
    } 
}
		