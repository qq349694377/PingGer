<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;
use think\Session;
use pager\Pager;
// 朋友圈
class Article extends Controller
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
			if(!in_array(1,$jid)){
				echo "<script>alert('你越界了！');window.location.replace('/admin/user/index');</script>";
				return false;
			}
		}
	}

	// 朋友圈----内容管理
	public function list()
	{
		//调用防跳墙函数
		$this->tiaoqiang();
		return $this->fetch();
	}

	//朋友圈---内容信息
	public function selectlist()
	{
		$result = Db::name('pyq')->where('status',1)->select();
		$num = count($result);//查询共有多少数据
		$page = new Pager(5, $num);//每页5个(老的方式)
		$limit = $page->limit();//$limit

		$result = Db::name('pyq')->where('status',1)->limit($limit)->select();
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

	//朋友圈---删除信息
	public function delsong(Request $request)
	{
		$id = $request->post('id');
		$a = ['status'=>2];
		$result = Db::name('pyq')->where('id',$id)->update($a);
		if(empty($result)){
			return json_encode(['state'=>2]);
		}else{
			// 将对应mv下面评论的status 改为2
			$result1 = Db::name('pyqreply')->where('cid',$id)->update(['status'=>2]);
			return json_encode(['state'=>1]);
		}
	}

	//评论内容----批量删除
	public function delete123(Request $request)
	{
		$id = $request->post('id');
		$id = explode(',',$id);//切换成数组
		// 遍历修改状态项
		foreach ($id as $key => $value) {
			$result = Db::name('pyq')->where('id',$value)->update(['status'=>2]);
			// 将对应mv下面评论的status 改为2
			$result1 = Db::name('pyqreply')->where('cid',$value)->update(['status'=>2]);
		}
	}

	// 朋友圈回收站
	public function outlist()
	{
		//调用防跳墙函数
		$this->tiaoqiang();
		return $this->fetch();
	}

	//朋友圈回收站---内容信息
	public function selectoutlist()
	{
		$result = Db::name('pyq')->where('status',2)->select();
		$num = count($result);//查询共有多少数据
		$page = new Pager(5, $num);//每页5个(老的方式)
		$limit = $page->limit();//$limit

		$result = Db::name('pyq')->where('status',2)->select();
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

	//朋友圈回收站---恢复信息
	public function deloutsong(Request $request)
	{
		$id = $request->post('id');
		$a = ['status'=>1];
		$result = Db::name('pyq')->where('id',$id)->update($a);
		if(empty($result)){
			return json_encode(['state'=>2]);
		}else{
			return json_encode(['state'=>1]);
		}
	}

	//评论内容----批量删除
	public function delete1234(Request $request)
	{
		$id = $request->post('id');
		$id = explode(',',$id);//切换成数组
		// 遍历修改状态项
		foreach ($id as $key => $value) {
			$result = Db::name('pyq')->where('id',$value)->update(['status'=>1]);
		}
	}

	//朋友圈评论内容
	public function huifu()
	{
		//调用防跳墙函数
		$this->tiaoqiang();
		return $this->fetch();
	}

	// 朋友圈评论内容---数据信息
	public function selecthuifu()
	{
		$result = Db::name('pyqreply')->where('status',1)->select();
		$num = count($result);//查询共有多少数据
		$page = new Pager(5, $num);//每页5个(老的方式)
		$limit = $page->limit();//$limit
		$result = Db::name('pyqreply')->where('status',1)->limit($limit)->select();
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
	public function delete1(Request $request)
	{
		$id = $request->post('id');
		$id = explode(',',$id);//切换成数组
		// 遍历修改状态项
		foreach ($id as $key => $value) {
			$result = Db::name('pyqreply')->where('id',$value)->update(['status'=>2]);
		}
	}


	// 评论回收站
	public function outhuifu()
	{
		//调用防跳墙函数
		$this->tiaoqiang();
		return $this->fetch();
	}

	// 朋友圈回收站评论内容---数据信息
	public function selectouthuifu()
	{
		$result = Db::name('pyqreply')->where('status',2)->select();
		$num = count($result);//查询共有多少数据
		$page = new Pager(5, $num);//每页5个(老的方式)
		$limit = $page->limit();//$limit
		$result = Db::name('pyqreply')->where('status',2)->limit($limit)->select();
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
	public function delete2(Request $request)
	{
		$id = $request->post('id');
		$id = explode(',',$id);//切换成数组
		// 遍历修改状态项
		foreach ($id as $key => $value) {
			$result = Db::name('pyqreply')->where('id',$value)->update(['status'=>1]);
		}
	}
}