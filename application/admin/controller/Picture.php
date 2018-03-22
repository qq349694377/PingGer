<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;
use think\Session;
use pager\Pager;
// use think\Upload;
class Picture extends Controller
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
			if(!in_array(2,$jid)){
				echo "<script>alert('你越界了！');window.location.replace('/admin/user/index');</script>";
				return false;
			}
		}
	}

	// 图片管理
	public function list()
	{
		//调用防跳墙函数
		$this->tiaoqiang();
		return $this->fetch();
	}

	//图片管理----查询图片信息
	public function selectlist()
	{
		$result = Db::name('lbt')->where('status',1)->select();
		$num = count($result);//查询共有多少数据
		$page = new Pager(5, $num);//每页5个(老的方式)
		$limit = $page->limit();//$limit

		$result = Db::name('lbt')->where('status',1)->limit($limit)->select();
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

	//将图片删除到回收站
	public function delsong(Request $request)
	{
		$id = $request->post('id');
		$a = ['status'=>2];
		$result = Db::name('lbt')->where('id',$id)->update($a);
		if(empty($result)){
			return json_encode(['state'=>2]);
		}else{
			return json_encode(['state'=>1]);
		}
	}

	//图片管理----批量删除
	public function delete123(Request $request)
	{
		$id = $request->post('id');
		$id = explode(',',$id);//切换成数组
		// 遍历修改状态项
		foreach ($id as $key => $value) {
			$result = Db::name('lbt')->where('id',$value)->update(['status'=>2]);
		}
	}

	// 图片回收站
	public function outlist()
	{
		//调用防跳墙函数
		$this->tiaoqiang();
		return $this->fetch();
	}

	//图片回收站----查询图片信息
	public function selectoutlist()
	{
		$result = Db::name('lbt')->where('status',2)->select();
		$num = count($result);//查询共有多少数据
		$page = new Pager(5, $num);//每页5个(老的方式)
		$limit = $page->limit();//$limit

		$result = Db::name('lbt')->where('status',2)->select();
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

	//图片回收站---将图片还原到轮播图
	public function deloutsong(Request $request)
	{
		$id = $request->post('id');
		$a = ['status'=>1];
		$result = Db::name('lbt')->where('id',$id)->update($a);
		if(empty($result)){
			return json_encode(['state'=>2]);
		}else{
			return json_encode(['state'=>1]);
		}
	}

	//图片回收站----批量删除
	public function delete1234(Request $request)
	{
		$id = $request->post('id');
		$id = explode(',',$id);//切换成数组
		// 遍历修改状态项
		foreach ($id as $key => $value) {
			$result = Db::name('lbt')->where('id',$value)->update(['status'=>1]);
		}
	}

	// MV详情（在轮播图中点击id，获取的mv详情）
	public function show(Request $request)
	{
		$id = $request->get('id');
		$result1 = Db::name('mv')->where('mid',$id)->select();
		$result = [];
		if(!empty($result1)){
			foreach ($result1 as $key => $value) {
				$value['create_time'] = date('Y-m-d H:i:s',$value['create_time']);
				$result[$key] = $value;
			}
			$result = $result[0];
		}
		$this->assign('data',$result);
		// dump($result);
		return $this->fetch();
	}

	// 图片添加页面
	public function add()
	{
		//调用防跳墙函数
		$this->tiaoqiang();
		return $this->fetch();
	}

	// 提交
	public function insert(Request $request)
	{

		// 获取表单上传文件 例如上传了001.jpg
		// $title = $request->post('biaoti');
		$title = input('post.biaoti');
		$mid = $request->post('mid');
		$content = $request->post('zhaiyao');
		$file = request()->file('image');
		
		// dump($file);
		if(empty($file) || empty($title) || empty($mid) || empty($content)){
			echo "<script>alert('上传文件不存在');</script>";
			header('refresh:0;/admin/picture/add');die;
			// return json_encode(['state'=>5]);//上传文件不存在
		}
		// dump($file);die;
		// 移动到框架应用根目录/public/uploads/ 目录下
		$info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
		if($info){
			// 成功上传后 获取上传信息
			// 输出 jpg
			//echo $info->getExtension();
			// 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
			$img ='/uploads/'.str_replace('\\', '/', $info->getSaveName());
			
			$result = Db::name('mv')->where('mid',$mid)->select();
			//dump($result);die;
			if(empty($result)){
				echo "<script>alert('所选mv不存在');</script>";
				header('refresh:0;/admin/picture/add');die;
				//return json_encode(['state'=>2]); //所选mv不存在
			}else if($result[0]['status'] == 2){
				echo "<script>alert('所选mv在回收站');</script>";
				header('refresh:0;/admin/picture/add');die;
				//return json_encode(['state'=>3]); //所选mv在回收站
			}
			$a = ['title'=>$title,'img'=>$img,'mid'=>$mid,'create_time'=>time(),'status'=>1];
			$result1 = Db::name('lbt')->insert($a);
			echo "<script>alert('添加成功');</script>";
			header('refresh:0;/admin/picture/add');die;
			//return json_encode(['state'=>4]); //添加成功
			// 输出 42a79759f284b767dfcb2a0197904287.jpg
			//echo $info->getFilename();
		}else{
		// 上传失败获取错误信息
		// return json_encode(['state'=>1]); 
		echo "<script>alert('上传失败获取错误信息');</script>";
		header('refresh:0;/admin/picture/add');die;
		//echo $file->getError();
		}
	}
}