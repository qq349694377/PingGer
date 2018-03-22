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

class Product extends Controller
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
			if(!in_array(3,$jid)){
				echo "<script>alert('你越界了！');window.location.replace('/admin/user/index');</script>";
				return false;
			}
		}
	}

	//音乐管理---音乐列表
	public function song()
	{
		//调用防跳墙函数
		$this->tiaoqiang();
		return $this->fetch();
	}

	//音乐管理---查询歌曲信息
	public function selectsong(Request $request)
	{
		$result = Db::name('song')->where('status',1)->select();
		$num = count($result);//查询共有多少数据
		$page = new Pager(5, $num);//每页5个(老的方式)

		$limit = $page->limit();
		$result = Db::name('song')->where('status',1)->limit($limit)->select();
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

	//音乐管理---设置热门音乐
	public function remen(Request $request)
	{
		$id = $request->post('id');
		//自动确认设置热门，还是取消热门
		$num = $request->post('num');
		$time = time();
		$a = ['top'=>$num,'toptime'=>$time];
		$result = Db::name('song')->where('id',$id)->update($a);
		if(empty($result)){
			if($num == 1){
				return json_encode(['state'=>4]);
			}else{
				return json_encode(['state'=>6]);
			}
		}else{
			if($num == 1){
				return json_encode(['state'=>3]);
			}else{
				return json_encode(['state'=>5]);
			}
			
		}
	}

	//音乐列表---搜索数据
	public function selectlist1(Request $request)
	{
		//查询和搜索信息一致的信息
		$content = $request->post('content');
		$asd = '%'.$content.'%';
		$result = Db::name('song')->where(['title'=>['like',$asd],'status'=>1])->select();

		$num = count($result);//查询共有多少数据
		$page = new Pager(5, $num);//每页5个(老的方式)
		$limit = $page->limit();//$limit

		$result = Db::name('song')->where(['title'=>['like',$asd],'status'=>1])->select();
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

	//音乐管理---删除歌曲到回收站
	public function delsong(Request $request)
	{
		$id = $request->post('id');
		$a = ['status'=>2,'top'=>2];
		$result = Db::name('song')->where('id',$id)->update($a);
		if(empty($result)){
			return json_encode(['state'=>2]);
		}else{
			return json_encode(['state'=>1]);
		}
	}

	// 音乐管理----批量删除
	public function delete123(Request $request)
	{
		$id = $request->post('id');
		$id = explode(',',$id);//切换成数组
		// 遍历修改状态项
		foreach ($id as $key => $value) {
			$result = Db::name('song')->where('id',$value)->update(['status'=>2]);
		}
	}

	// 音乐回收站
	public function outsong()
	{
		//调用防跳墙函数
		$this->tiaoqiang();
		return $this->fetch();
	}

	//音乐回收站---查询回收站歌单信息
	public function selectoutsong()
	{
		$result = Db::name('song')->where('status',2)->select();
		$num = count($result);//查询共有多少数据
		$page = new Pager(5, $num);//每页5个(老的方式)
		$limit = $page->limit();//$limit

		$result = Db::name('song')->where('status',2)->limit($limit)->select();
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

	//音乐回收站---恢复歌曲到列表
	public function upsong(Request $request)
	{
		$id = $request->post('id');
		$a = ['status'=>1];
		$result = Db::name('song')->where('id',$id)->update($a);
		if(empty($result)){
			return json_encode(['state'=>2]);
		}else{
			return json_encode(['state'=>1]);
		}
	}

	//音乐回收站---搜索数据
	public function selectlist2(Request $request)
	{
		//查询和搜索信息一致的信息
		$content = $request->post('content');
		$asd = '%'.$content.'%';
		$result = Db::name('song')->where(['title'=>['like',$asd],'status'=>2])->select();

		$num = count($result);//查询共有多少数据
		$page = new Pager(5, $num);//每页5个(老的方式)
		$limit = $page->limit();//$limit

		$result = Db::name('song')->where(['title'=>['like',$asd],'status'=>2])->select();
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

	// 音乐回收站 批量恢复
	public function delete2(Request $request)
	{
		$id = $request->post('id');
		$id = explode(',',$id);//切换成数组
		// 遍历修改状态项
		foreach ($id as $key => $value) {
			$result = Db::name('song')->where('id',$value)->update(['status'=>1]);
		}
	}

	// 音乐管理---添加音乐
	public function add()
	{
		//调用防跳墙函数
		$this->tiaoqiang();
		return $this->fetch();
	}
	// 添加音乐
	public function insertadd(Request $request)
	{
		if(empty($_FILES['tupian']['name']) || empty($_FILES['music']['name']) || empty($_FILES['geci']['name']) || empty($request->post('song')) || empty($request->post('mobile1')) || empty($request->post('shoufei'))){
			echo "<script>alert('请输入所有的选项，谢谢');</script>";
			echo '<meta http-equiv="refresh" content="0; url=/admin/product/add">'; 
			die;
		}

		$song = $request->post('song');//歌名
		$mobile = $request->post('mobile1');//歌手
		$shoufei = $request->post('shoufei');//收费
		if($shoufei ==1){
			$money = $request->post('money');//价格
			if(empty($money)){
				$money = 0;
			}
		}else{
			$money = 0;//价格
		}

		// 封面图片
		$img = $_FILES['tupian']['name'];//名字
		$img1 = $_FILES['tupian']['tmp_name'];//路径
		$this->qiniuyun($img,$img1);//文件名  ，  文件路径
		
		// 音乐
		$music = $_FILES['music']['name'];//名字
		$music1 = $_FILES['music']['tmp_name'];//路径
		$this->qiniuyun($music,$music1);//上传七牛云
		
		// 歌词
		$geci = $_FILES['geci']['name'];//名字
		$geci1 = $_FILES['geci']['tmp_name'];//路径
		$this->qiniuyun($geci,$geci1);//上传七牛云
		
		$a = "http://p3ijb4nfy.bkt.clouddn.com/";
		$data = ['title'=>$song,'mp3'=>$a.$music,'artist'=>$mobile,'poster'=>$a.$img,'top'=>2,'hits'=>0,'create_time'=>time(),'word'=>$a.$geci,'charge'=>$shoufei,'money'=>$money,'status'=>1];
		$result = Db::name('song')->insert($data);
		if(empty($result)){
			echo "<script>alert('输入数据不正确');</script>";
			echo '<meta http-equiv="refresh" content="0; url=/admin/product/add">'; 
			die;
		}else{
			echo "<script>alert('添加成功');</script>";
			echo '<meta http-equiv="refresh" content="0; url=/admin/product/add">'; 
			die;
		}		
	}

	// 上传七牛云函数
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

	//音乐资料修改页面
	public function update(Request $request)
	{
		//调用防跳墙函数
		$this->tiaoqiang();
		// 获取音乐资料的信息
		$id= $request->get('id');
		$result = Db::name('song')->where('id',$id)->select();
		$result = $result[0];
		$this->assign('data',$result);
		$this->assign('id',$id);
		return $this->fetch();
	}

	//资料修改页面，信息的数据库修改
	public function updatesong(Request $request)
	{
		$id= $request->post('id');
		$result = Db::name('song')->where('id',$id)->select();

		// 歌名
		if(empty($request->post('song'))){
			$data['title'] = $result[0]['title'];
		}else{
			$data['title'] = $request->post('song');
		}

		// 歌手
		if(empty($request->post('mobile1'))){
			$data['artist'] = $result[0]['artist'];
		}else{
			$data['artist'] = $request->post('mobile1');
		}

		// 收费  和价格
		if(empty($request->post('shoufei'))){
			//当前没填写，之前的信息(可能是收费，也可能是免费)
			$data['charge'] = $result[0]['charge'];

			// 收费
			if($result[0]['charge'] ==1){//1是收费曲目
				// 歌手
				if(empty($request->post('money'))){
					$data['money'] = $result[0]['money'];//没填 原价格
				}else{
					$data['money'] = $request->post('money');//填了，新价格
				}
			}else{//免费项目
				$data['money'] = 0;//价格
			}

		}else{
			// 收费
			if($request->post('shoufei') ==1){//1是收费曲目
				$data['charge'] = $request->post('shoufei');
				// 歌手
				if(empty($request->post('money'))){
					$data['money'] = $result[0]['money'];//没填 原价格
				}else{
					$data['money'] = $request->post('money');//填了，新价格
				}
			}else{//免费项目
				$data['money'] = 0;//价格
				$data['charge'] = $request->post('shoufei');
			}
		}

		$a = "http://p3ijb4nfy.bkt.clouddn.com/";
		// 封面图片
		if(!empty($_FILES['tupian']['name'])){
			$img = $_FILES['tupian']['name'];//名字
			$img1 = $_FILES['tupian']['tmp_name'];//路径
			$this->qiniuyun($img,$img1);//文件名  ，  文件路径
			$data['poster'] = $a . $img;
		}else{
			$data['poster'] = $result[0]['poster'];
		}


		// 音乐
		if(!empty($_FILES['music']['name'])){
			$music = $_FILES['music']['name'];//名字
			$music1 = $_FILES['music']['tmp_name'];//路径
			$this->qiniuyun($music,$music1);//上传七牛云
			$data['mp3'] = $a . $music;
		}else{
			$data['mp3'] = $result[0]['mp3'];
		}

		// 歌词
		if(!empty($_FILES['geci']['name'])){
			$geci = $_FILES['geci']['name'];//名字
			$geci1 = $_FILES['geci']['tmp_name'];//路径
			$this->qiniuyun($geci,$geci1);//上传七牛云
			$data['word'] = $a . $geci;
		}else{
			$data['word'] = $result[0]['word'];
		}
		// dump($data);die;

		$result = Db::name('song')->where('id',$id)->update($data);
		if(empty($result)){
			echo "<script>alert('输入数据不正确');</script>";
			echo '<meta http-equiv="refresh" content="0; url=/admin/product/add">'; 
			die;
		}else{
			echo "<script>alert('修改成功');</script>";
			echo '<meta http-equiv="refresh" content="0; url=/admin/product/add">'; 
			die;
		}		
	}
	//歌手列表页面
	public function singer()
	{
		//调用防跳墙函数
		$this->tiaoqiang();
		return $this->fetch();
	}
	//查询歌手的信息
	public function selectsinger()
	{

		$result = Db::name('singer')->where('status',1)->select();
		$num = count($result);//查询共有多少数据
		$page = new Pager(5, $num);//每页5个(老的方式)
		$limit = $page->limit();//$limit

		$result = Db::name('singer')->where('status',1)->limit($limit)->select();
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

	//歌手置顶
	public function top(Request $request)
	{	
		$id = $request->post('id');
		$data['top'] = $request->post('top');
		$result = Db::name('singer')->where('id',$id)->update($data);
	}

	//直接删除歌手
	public function delete1(Request $request)
	{
		$id = $request->post('id');
		$result = Db::name('singer')->where('id',$id)->delete();
	}

	public function deletesinger1(Request $request)
	{
		$id = $request->post('id');
		$id = explode(',',$id);//切换成数组
		// 遍历修改状态项
		foreach ($id as $key => $value) {
			$result = Db::name('singer')->where('id',$value)->delete();
		}
	}

	//添加歌手页面显示
	public function addsinger()
	{
		//调用防跳墙函数
		$this->tiaoqiang();
		return $this->fetch();
	}

	// 添加歌手(数据的增加)
	public function singeradd(Request $request)
	{
		if(empty($request->post('username')) || empty(request()->file('file')) || empty($request->post('content'))){
			echo '<script>alert("所有都是必填项");</script>';
			echo '<meta http-equiv="refresh" content="0; url=/admin/product/addsinger">'; 
			die;
		}

		$name = $request->post('username');
		$file = request()->file('file');
		$content = $request->post('content');

		$info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
		if($info){
			// 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
			$data['pic'] = '/uploads/' . $info->getSaveName();
		}

		$data['singer'] = $name;
		$data['introduce'] = $content;
		$data['create_time'] = time();
		$result = Db::name('singer')->insert($data);
		if(empty($result)){
			echo '<script>alert("添加失败，请重新添加信息");</script>';
			echo '<meta http-equiv="refresh" content="0; url=/admin/product/addsinger">'; 
			die;
		}else{
			echo '<script>alert("添加成功");</script>';
			echo '<meta http-equiv="refresh" content="0; url=/admin/product/addsinger">'; 
			die;
		}
	}

	//音乐风格类型页面
	public function fengge()
	{
		//调用防跳墙函数
		$this->tiaoqiang();
		$result = Db::name('songstyle')->where('status',1)->select();
		// dump($result);die;
		$this->assign('result',$result);
		return $this->fetch();
	}
	//添加风格页面
	public function addstyle()
	{
		//调用防跳墙函数
		$this->tiaoqiang();
		$result = Db::name('songstyle')->where('status',1)->select();
		$this->assign('result',$result);
		return $this->fetch();
	}
	// 添加风格，数据处理
	public function addstyle1(Request $request)
	{
		if(empty($request->post('songstyle')) ||empty($request->post('id'))){
			echo '<script>alert("数据不能为空");</script>';
			echo '<meta http-equiv="refresh" content="0; url=/admin/product/addstyle">'; 
		}
		$songstyle = $request->post('songstyle');
		$id = $request->post('id');
		if($id == -1){
			$data['grade'] = 1;
			$data['pid'] = 0;
		}else{
			$data['grade'] = 2;
			$data['pid'] = $id;
		}
		//分类名称
		$data['style'] = $songstyle;
		$data['create_time'] = time();
		$data['songid'] = ',';
		$data['status'] = 1;
		$result = Db::name('songstyle')->insert($data);

		//插入成功
		echo "<script>alert('添加成功');window.close();</script>";
	}

	//查询风格信息
	public function selectfengge()
	{
		$result = Db::name('songstyle')->where('status',1)->select();
		$num = count($result);//查询共有多少数据
		$a = [];
		if(!empty($result)){
			//将时间戳转换成日期格式
			foreach ($result as $key => $value) {
				$value['create_time'] = date('Y-m-d H:i:s',$value['create_time']);
				if($value['songid'] == ',' || $value['songid'] == '，'){
					$value['songid'] = "----------";
				}else{
					$value['songid'] = trim($value['songid'],',');
				}
				
				$a[$key] = $value;
			}
		}
		$as = $this->GetTree($a,0,0);
		$b['data'] = $as;
		$b['num'] = $num;
		return json_encode($b);
	}


	//php递归函数
	private function GetTree($arr,$pid,$step){
	    global $tree;
	    foreach($arr as $key=>$val) {
	        if($val['pid'] == $pid) {
	            $flg = str_repeat('|—',$step);
	            $val['style'] = $flg.$val['style'];
	            $tree[] = $val;
	            $this->GetTree($arr , $val['id'] ,$step+1);
	        }
	    }
	    return $tree;
	}

	// 风格管理----批量删除
	public function deletefengge123(Request $request)
	{
		$id = $request->post('id');
		$id = explode(',',$id);//切换成数组
		// 遍历修改状态项
		foreach ($id as $key => $value) {
			//查询删除的项是不是一级目录
			$result1 = Db::name('songstyle')->where('id',$value)->select();
			// 是一级目录，则删除一级目录下的所有二级目录
			if($result1[0]['pid'] == 0){
				$result2 = Db::name('songstyle')->where('pid',$value)->update(['status'=>2]);
			}
			$result = Db::name('songstyle')->where('id',$value)->update(['status'=>2]);
		}
	}

	// 修改风格页面
	public function xiugai(Request $request)
	{
		//调用防跳墙函数
		$this->tiaoqiang();

		$id = $request->get('id');
		$result = Db::name('songstyle')->where('id',$id)->select();
		$a = [];
		foreach($result as $key=>$value){
			$value['songid'] = trim($value['songid'],',');
			$a[$key] = $value;
		}
		$data = $a[0];
		$result1 = Db::name('songstyle')->where(['status'=>1,'pid'=>0])->select();
		$this->assign('one',$result1);
		$this->assign('data',$data);
		return $this->fetch();
	}

	//修改风格页面，提交数据，更新数据;
	public function xiugai123(Request $request)
	{
		$id = $request->post('id');
		$style = $request->post('style');
		$result = Db::name('songstyle')->where('id',$id)->select();
		$pid = $result[0]['pid'];
		if($pid == 0){
			$result1 = Db::name('songstyle')->where('id',$id)->update(['style'=>$style]);
			if(!empty($result1)){
				echo "<script>alert('修改成功')</script>";
				return "<script>window.close();</script>";
			}		
		}
		$songid = $request->post('songid');
		$pid = $request->post('pid');
		$data['style'] = $style;
		$data['pid'] = $pid;
		$data['songid'] = ','.trim($songid,',').',';
		$result2 = Db::name('songstyle')->where('id',$id)->update($data);
		if(!empty($result2)){
			echo "<script>alert('修改成功')</script>";
			return "<script>window.close();</script>";
		}

	}

	//音乐风格类型回收站页面
	public function outfengge()
	{
		//调用防跳墙函数
		$this->tiaoqiang();
		return $this->fetch();
	}

	//查询风格回收站信息
	public function selectoutfengge()
	{
		$result = Db::name('songstyle')->where('status',2)->select();
		$num = count($result);//查询共有多少数据
		$a = [];
		if(!empty($result)){
			//将时间戳转换成日期格式
			foreach ($result as $key => $value) {
				$value['create_time'] = date('Y-m-d H:i:s',$value['create_time']);
				if($value['songid'] == ',' || $value['songid'] == '，'){
					$value['songid'] = "-------------------------";
				}else{
					$value['songid'] = trim($value['songid'],',');
				}
				
				$a[$key] = $value;
			}
		}
		// $as = $this->GetTree($a,0,0);
		// $b['data'] = $as;
		$b['data'] = $a;
		$b['num'] = $num;
		return json_encode($b);	
	}

	// 风格管理----批量恢复
	public function deletefengge1234(Request $request)
	{
		$id = $request->post('id');
		$id = explode(',',$id);//切换成数组
		// 遍历修改状态项
		$b = [];
		foreach ($id as $key => $value) {
			$a = [];
			// 查询当前id是不是一级目录
			$result2 = Db::name('songstyle')->where('id',$value)->select();
			if($result2[0]['pid'] != 0){
				// 当不是一级目录的时候，查询父目录是不是已经被删除
				$result3 = Db::name('songstyle')->where('id',$result2[0]['pid'])->select();
					if($result3[0]['status'] == 2){
						// 保存到不能删除的项id
						$a[$key] = $value;
						$b[$key] = $value;
					}
			}
			// 如果删除的项没有父目录是被删除的，则正常恢复
			if(empty($a)){
				$result4 = Db::name('songstyle')->where('id',$value)->update(['status'=>1]);
			}	
		}
		// 如果$a是空，则删除成功，否则返回没删除的id
		if(empty($b)){
			return json_encode(['states'=>1]);
		}else{
			$c = join(',',$b);
			return json_encode(['states'=>$c]);
		}
		
	}
}