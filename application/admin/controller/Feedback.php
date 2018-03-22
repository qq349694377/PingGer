<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;
use think\Session;
use pager\Pager;

require_once __DIR__ . '../../../../extend/qiniu/php-sdk-7.2.2/autoload.php';  //引用上传文件的sdk包
use Qiniu\Auth;
use Qiniu\Storage\BucketManager;
use Qiniu\Storage\UploadManager;

class Feedback extends Controller
{
	// 防跳墙方法
	public function tiaoqiang()
	{
		//防跳墙，获取session
		if(empty(Session::get('uid'))){
			echo "<script>alert('看你是要搞事情喽！');window.location.replace('/index/index/index');</script>";
			return false;
		}else{
			$id = Session::get('uid');
			//查询当前用户的身份
			$result = Db::name('one')->where('uid',$id)->select();
			$jid = [];
			foreach ($result as $value) {
				$result1 = Db::name('two')->where('iid',$value['iid'])->select();
				foreach ($result1 as $val) {
					$jid[] = $val['jid'];
				}
			}
			//取出重复的jid
			$jid = array_unique($jid);
			// 判断当前jid 是否存在于$jid中
			if(!in_array(5,$jid)){
				echo "<script>alert('你越界了！');window.location.replace('/admin/user/index');</script>";
				return false;
			}
		}
	}

	//MV管理---mv列表
	public function list()
	{
		//调用防跳墙函数
		$this->tiaoqiang();
		return $this->fetch();
	}

	//MV管理---mv信息查询
	public function selectlist()
	{
		$result = Db::name('mv')->where('status',1)->select();
		$num = count($result);//查询共有多少数据
		$page = new Pager(5, $num);//每页5个(老的方式)
		$limit = $page->limit();//$limit
		
		$result = Db::name('mv')->where('status',1)->limit($limit)->order('mid asc')->select();
		//查询不能为空
		$a = [];
		if(!empty($result)){
			//将时间戳转换成日期格式
			foreach ($result as $key => $value) {
				$value['create_time'] = date('Y-m-d H:i:s',$value['create_time']);
				$a[$key] = $value;
			}
		}
		$b['data'] = $a;
		$b['allPage'] = $page->allPage();
		$b['num'] = $num;
		return json_encode($b);
	}

	// 设置置顶
	public function remen(Request $request)
	{
		$id = $request->post('id');
		$num = $request->post('num');
		$data['top'] = $num;//1是置顶，2是不置顶
		//更新置顶时间
		if($num ==1){
			$data['toptime'] = time();
		}
		$result = Db::name('mv')->where('mid',$id)->update($data);
		if(empty($result)){
			if($num == 1){
				return json_encode(['state'=>4]);//置顶失败
			}else{
				return json_encode(['state'=>6]);//取消置顶失败
			}
		}else{
			if($num == 1){
				return json_encode(['state'=>3]);//置顶成功
			}else{
				return json_encode(['state'=>5]);//取消置顶成功
			}
		}
	}

	//mv列表---搜索数据
	public function selectlist1(Request $request)
	{
		//查询和搜索信息一致的信息
		$content = $request->post('content');
		$asd = '%'.$content.'%';
		$result = Db::name('mv')->where(['title'=>['like',$asd],'status'=>1])->select();

		$num = count($result);//查询共有多少数据
		$page = new Pager(5, $num);//每页5个(老的方式)
		$limit = $page->limit();//$limit

		$result = Db::name('mv')->where(['title'=>['like',$asd],'status'=>1])->select();
		$a = [];
		if(!empty($result)){
			//将时间戳转换成日期格式
			foreach ($result as $key => $value) {
				$value['create_time'] = date('Y-m-d H:i:s',$value['create_time']);
				$a[$key] = $value;
			}
		}
		$b['data'] = $a;
		$b['allPage'] = $page->allPage();
		$b['num'] = $num;
		return json_encode($b);
	}

	//mv回收站---搜索数据
	public function selectlist2(Request $request)
	{
		//查询和搜索信息一致的信息
		$content = $request->post('content');
		$asd = '%'.$content.'%';
		$result = Db::name('mv')->where(['title'=>['like',$asd],'status'=>2])->select();

		$num = count($result);//查询共有多少数据
		$page = new Pager(5, $num);//每页5个(老的方式)
		$limit = $page->limit();//$limit

		$result = Db::name('mv')->where(['title'=>['like',$asd],'status'=>2])->select();
		$a = [];
		if(!empty($result)){
			//将时间戳转换成日期格式
			foreach ($result as $key => $value) {
				$value['create_time'] = date('Y-m-d H:i:s',$value['create_time']);
				$a[$key] = $value;
			}
		}
		$b['data'] = $a;
		$b['allPage'] = $page->allPage();
		$b['num'] = $num;
		return json_encode($b);
	}

	//删除数据到回收站
	public function delsong(Request $request)
	{
		//获取当前mv的mid
		$id = $request->post('id');
		//将当前mv的status改为2
		$result = Db::name('mv')->where('mid',$id)->update(['status'=>2]);
		if(empty($result)){
			return json_encode(['state'=>2]);
		}else{
			// 将对应mv下面评论的status 改为2
			$result1 = Db::name('mvreply')->where('cid',$id)->update(['status'=>2]);
			return json_encode(['state'=>1]);
		}
	}

	// mv管理----批量删除
	public function delete1(Request $request)
	{
		$id = $request->post('id');
		$id = explode(',',$id);//切换成数组
		// 遍历修改状态项
		foreach ($id as $key => $value) {
			$result = Db::name('mv')->where('mid',$value)->update(['status'=>2]);
			// 将对应mv下面评论的status 改为2
			$result1 = Db::name('mvreply')->where('cid',$value)->update(['status'=>2]);
		}
	}

	//MV管理---添加mv
	public function add()
	{
		//调用防跳墙函数
		$this->tiaoqiang();
		return $this->fetch();
	}

	//MV管理---添加mv数据增加
	public function addmv(Request $request)
	{
		if(empty($_FILES['image']['name']) || empty($_FILES['mv']['name']) || empty($request->post('name')) ||empty($request->post('singer')) || empty($request->post('content')) || empty($request->post('select'))){
			echo "<script>alert('请输入所有的选项，谢谢');</script>";
			echo '<meta http-equiv="refresh" content="0; url=/admin/feedback/add">'; 
			die;
		}

		//mv名字
		$name = $request->post('name');
		//歌手名字
		$singer = $request->post('singer');
		//简介
		$content = $request->post('content');

		//mv对应的风格id
		$styleid = $request->post('select');
		// 封面图片
		$img = $_FILES['image']['name'];//名字
		$img1 = $_FILES['image']['tmp_name'];//路径
		$this->qiniuyun($img,$img1);//文件名  ，  文件路径
		
		// mv
		$music = $_FILES['mv']['name'];//名字
		$music1 = $_FILES['mv']['tmp_name'];//路径
		$this->qiniuyun($music,$music1);//上传七牛云

		$a = "http://p3ijb4nfy.bkt.clouddn.com/";
		$data = ['title'=>$name,'singer'=>$singer,'content'=>$content,'webmv'=>$a.$music,'poster'=>$a.$img,'top'=>2,'create_time'=>time(),'status'=>1];
		//添加mv数据
		$result = Db::name('mv')->insert($data);
		//添加mv对应的id
		$id = Db::name('mv')->getLastInsID();

		if(empty($result)){
			echo "<script>alert('添加失败');</script>";
			die;
		}else{
			// 查询所选mv风格的信息
			$result1 = Db::name('mvstyle')->where('id',$styleid)->select();
			$mvid = $result1[0]['mvid'];
			//删除两面的,
			$mvid = trim($mvid,',');
			//拼接mvid，并拼接两边的逗号
			$data1 = ','.$mvid.','.$id.',';
			// 修改对应mv风格中的mvid项
			$result2 = Db::name('mvstyle')->where('id',$styleid)->update(['mvid'=>$data1]);
			echo "<script>alert('添加成功');</script>";
			// echo '<meta http-equiv="refresh" content="0; url=/admin/product/add">';
			echo "<script> window.close();</script>";
			die;
		}

	}

	// 上传七牛云文件
	public function qiniuyun($a,$b){
		// 需要填写你的 Access Key 和 Secret Key
		$accessKey ="7rJ-Q7UBOxS6w8p79h6-EWsLbz3jXHQ8Ze_12aZ7";
		$secretKey = "Kxs_2-66YBKtRV3WWloD73bvs62jQfjGnAtIGVZk";
		$bucket = "youngyang";
		// 构建鉴权对象
		$auth = new Auth($accessKey, $secretKey);
		// 生成上传 Token
		$token = $auth->uploadToken($bucket);
		// 要上传文件的本地路径
		$filePath = $b;
		// 上传到七牛后保存的文件名
		$key = $a;
		// 初始化 UploadManager 对象并进行文件的上传。
		$uploadMgr = new UploadManager();
		// 调用 UploadManager 的 putFile 方法进行文件的上传。
		list($ret, $err) = $uploadMgr->putFile($token, $key, $filePath);
	}


	//MV管理---修改mv页面
	public function update(Request $request)
	{
		//调用防跳墙函数
		$this->tiaoqiang();

		$mid = $request->get('id');
		// $mid = 1;
		$result = Db::name('mv')->where('mid',$mid)->select();
		// dump($result);
		$data = $result[0];
		$this->assign('data',$data);
		return $this->fetch();
	}
	//提交修改页面，数据处理
	public function updatemv(Request $request)
	{
		$id = $request->post('id');
		if(empty($request->post('name')) || empty($request->post('singer')) || empty($request->post('content'))){
			echo "<script>alert('文字项必填，谢谢');</script>";
			echo '<meta http-equiv="refresh" content="0; url=/admin/feedback/update">'; 
			die;
		}
		$data['title'] = $request->post('name');
		$data['singer'] = $request->post('singer');
		$data['content'] = $request->post('content');

		$a = "http://p3ijb4nfy.bkt.clouddn.com/";
		//封面图片是否上传，上传则修改
		if(!empty($_FILES['image']['name'])){
			// 封面图片
			$img = $_FILES['image']['name'];//名字
			$img1 = $_FILES['image']['tmp_name'];//路径
			$this->qiniuyun($img,$img1);//文件名  ，  文件路径
			$data['poster'] = $a.$img;
		}

		// mv是否上传，上传则修改
		if(!empty($_FILES['mv']['name'])){
			// 封面图片
			$mv = $_FILES['mv']['name'];//名字
			$mv1 = $_FILES['mv']['tmp_name'];//路径
			$this->qiniuyun($mv,$mv1);//文件名  ，  文件路径
			$data['webmv'] = $a.$mv;
		}
		$result1 = Db::name('mv')->where('mid',$id)->update($data);
		echo "<script> window.close(); </script>";


		
	}

	//MV管理---mv回收站
	public function outlist()
	{
		//调用防跳墙函数
		$this->tiaoqiang();

		return $this->fetch();
	}

	//MV管理---mv回收站信息查询
	public function selectoutlist()
	{
		$result = Db::name('mv')->where('status',2)->select();
		$num = count($result);//查询共有多少数据
		$page = new Pager(5, $num);//每页5个(老的方式)
		$limit = $page->limit();//$limit

		$result = Db::name('mv')->where('status',2)->limit($limit)->select();
		//查询不能为空
		$a = [];
		if(!empty($result)){
			//将时间戳转换成日期格式
			foreach ($result as $key => $value) {
				$value['create_time'] = date('Y-m-d H:i:s',$value['create_time']);
				$a[$key] = $value;
			}
		}
		$b['data'] = $a;
		$b['allPage'] = $page->allPage();
		$b['num'] = $num;
		return json_encode($b);
	}
	// mv回收站----恢复数据
	public function deloutsong(Request $request)
	{
		$id = $request->post('id');
		$result = Db::name('mv')->where('mid',$id)->update(['status'=>1]);
		if(empty($result)){
			return json_encode(['state'=>2]);
		}else{
			return json_encode(['state'=>1]);
		}
	}
	// mv回收站 批量删除
	public function delete2(Request $request)
	{
		$id = $request->post('id');
		$id = explode(',',$id);//切换成数组
		// 遍历修改状态项
		foreach ($id as $key => $value) {
			$result = Db::name('mv')->where('mid',$value)->update(['status'=>1]);
			// 将对应mv下面评论的status 改为2
			$result1 = Db::name('mvreply')->where('cid',$value)->update(['status'=>1]);
		}
	}

	// mv风格
	public function fengge()
	{
		//调用防跳墙函数
		$this->tiaoqiang();

		return $this->fetch();
	}

	//mv风格详情
	public function selectfengge()
	{
		$result = Db::name('mvstyle')->where('status',1)->select();
		$num = count($result);//查询共有多少数据
		$page = new Pager(5, $num);//每页5个(老的方式)
		$limit = $page->limit();//$limit

		$result = Db::name('mvstyle')->where('status',1)->limit($limit)->select();
		//查询不能为空
		$a = [];
		if(!empty($result)){
			//将时间戳转换成日期格式
			foreach ($result as $key => $value) {
				$value['create_time'] = date('Y-m-d H:i:s',$value['create_time']);
				$value['mvid'] = trim($value['mvid'],',');
				$a[$key] = $value;
			}
		}
		$b['data'] = $a;
		$b['allPage'] = $page->allPage();
		$b['num'] = $num;
		return json_encode($b);
	}

	//mv风格，修改风格中的mvid
	public function updatefengge(Request $request)
	{
		if(empty($request->post('mvid'))){
			$data['mvid'] = ',';
		}
		$mvid = $request->post('mvid');
		$mvid = trim($mvid,',');//先去掉两面的逗号
		$data['mvid'] = ','.$mvid.',';//在添加两面的逗号
		$id = $request->post('id');
		$result = Db::name('mvstyle')->where('id',$id)->update($data);
	}

	//mv评论页面
	public function huifu()
	{
		//调用防跳墙函数
		$this->tiaoqiang();

		return $this->fetch();
	}

	public function selecthuifu()
	{
		$result = Db::name('mvreply')->where('status',1)->select();
		$num = count($result);//查询共有多少数据
		$page = new Pager(5, $num);//每页5个(老的方式)
		$limit = $page->limit();//$limit
		$result = Db::name('mvreply')->where('status',1)->limit($limit)->select();
		$a = [];
		if(!empty($result)){
			//将时间戳转换成日期格式
			foreach ($result as $key => $value) {
				$value['create_time'] = date('Y-m-d H:i:s',$value['create_time']);
				$a[$key] = $value;
			}
		}
		$b['data'] = $a;
		$b['allPage'] = $page->allPage();
		$b['num'] = $num;
		return json_encode($b);
	}

	//评论内容----批量删除
	public function deletehuifu1(Request $request)
	{
		$id = $request->post('id');
		$id = explode(',',$id);//切换成数组
		// 遍历修改状态项
		foreach ($id as $key => $value) {
			$result = Db::name('mvreply')->where('id',$value)->update(['status'=>2]);
		}
	}


	// mv评论回收站页面
	public function outhuifu()
	{
		//调用防跳墙函数
		$this->tiaoqiang();

		return $this->fetch();
	}

	// mv评论回收站评论内容---数据信息
	public function selectouthuifu()
	{
		$result = Db::name('mvreply')->where('status',2)->select();
		$num = count($result);//查询共有多少数据
		$page = new Pager(5, $num);//每页5个(老的方式)
		$limit = $page->limit();//$limit
		$result = Db::name('mvreply')->where('status',2)->limit($limit)->select();
		$a = [];
		if(!empty($result)){
			//将时间戳转换成日期格式
			foreach ($result as $key => $value) {
				$value['create_time'] = date('Y-m-d H:i:s',$value['create_time']);
				$a[$key] = $value;
			}
		}
		$b['data'] = $a;
		$b['allPage'] = $page->allPage();
		$b['num'] = $num;
		return json_encode($b);
	}

	//评论回收站----批量删除
	public function deletehuifu2(Request $request)
	{
		$id = $request->post('id');
		$id = explode(',',$id);//切换成数组
		// 遍历修改状态项
		foreach ($id as $key => $value) {
			$result = Db::name('mvreply')->where('id',$value)->update(['status'=>1]);
		}
	}
}