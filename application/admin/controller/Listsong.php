<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;
use think\Session;
use pager\Pager;
// 歌单管理
class Listsong extends Controller
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
			if(!in_array(4,$jid)){
				echo "<script>alert('你越界了！');window.location.replace('/admin/user/index');</script>";
				return false;
			}
		}
	}

	// 歌单管理---列表
	public function list()
	{
		//调用防跳墙函数
		$this->tiaoqiang();
		return $this->fetch();
	}

	//歌单列表---查询歌单信息
	public function selectlist()
	{
		$result = Db::name('list')->where('status',1)->select();
		$num = count($result);//查询共有多少数据
		$page = new Pager(5, $num);//每页5个(老的方式)
		$limit = $page->limit();//$limit

		$result = Db::name('list')->where('status',1)->limit($limit)->select();
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

	//歌单列表---删除歌单到回收站
	public function dellistsong(Request $request)
	{
		$id = $request->post('id');
		$a = ['status'=>2];
		$result = Db::name('list')->where('id',$id)->update($a);
		if(empty($result)){
			return json_encode(['state'=>2]);
		}else{
			return json_encode(['state'=>1]);
		}
	}


	// 歌单回收站---列表
	public function outlist()
	{
		//调用防跳墙函数
		$this->tiaoqiang();
		return $this->fetch();
	}

	//歌单回收站---查询歌单信息
	public function selectoutlist()
	{
		$result = Db::name('list')->where('status',2)->select();
		$num = count($result);//查询共有多少数据
		$page = new Pager(5, $num);//每页5个(老的方式)
		$limit = $page->limit();//$limit

		$result = Db::name('list')->where('status',2)->select();
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

	//歌单回收站---恢复歌单到回收站
	public function deloutlistsong(Request $request)
	{
		$id = $request->post('id');
		$a = ['status'=>1];
		$result = Db::name('list')->where('id',$id)->update($a);
		if(empty($result)){
			return json_encode(['state'=>2]);
		}else{
			return json_encode(['state'=>1]);
		}
	}

	//批量删除
	public function delete1(Request $request)
	{
		$id = $request->post('id');
		$id = explode(',',$id);//切换成数组
		// 遍历修改状态项
		foreach ($id as $key => $value) {
			$result = Db::name('list')->where('id',$value)->update(['status'=>2]);
		}
		// echo json_encode($id);
	}

	//批量恢复
	public function deleteout1(Request $request)
	{
		$id = $request->post('id');
		$id = explode(',',$id);//切换成数组
		// 遍历修改状态项
		foreach ($id as $key => $value) {
			$result = Db::name('list')->where('id',$value)->update(['status'=>1]);
		}
		// echo json_encode($id);
	}
}