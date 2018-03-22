<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;
use think\Session;
use pager\Pager;
class Member extends Controller
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
			if(!in_array(6,$jid)){
				echo "<script>alert('你越界了！');window.location.replace('/admin/user/index');</script>";
				return false;
			}
		}
	}

	// 用户列表
	public function list()
	{
		//调用防跳墙函数
		$this->tiaoqiang();
		return $this->fetch();
	}


	// 用户列表----用户信息
	public function selectlist()
	{
		$result = Db::name('user')->where(['status'=>1,'admin'=>2])->select();
		$num = count($result);//查询共有多少数据
		$page = new Pager(5, $num);//每页5个(老的方式)
		$limit = $page->limit();//$limit

		$result = Db::name('user')->where(['status'=>1,'admin'=>2])->limit($limit)->select();
		if(!empty($result)){
			$a = [];
			//将时间戳转换成日期格式
			foreach ($result as $key => $value) {
				$value['create_time'] = date('Y-m-d H:i:s',$value['create_time']);
				$value['update_time'] = date('Y-m-d H:i:s',$value['update_time']);
				$a[$key] = $value;
			}
		}
		$b['data'] = $a;
		$b['allPage'] = $page->allPage();
		$b['num'] = $num;
		return json_encode($b);
	}

	//用户列表---拉黑用户到回收站
	public function delsong(Request $request)
	{
		$id = $request->post('id');
		$a = ['status'=>2];
		$result = Db::name('user')->where('uid',$id)->update($a);
		if(empty($result)){
			return json_encode(['state'=>2]);
		}else{
			return json_encode(['state'=>1]);
		}
	}

	//用户列表---搜索数据
	public function selectlist1(Request $request)
	{
		//查询和搜索信息一致的信息
		$content = $request->post('content');
		$asd = '%'.$content.'%';
		$result = Db::name('user')->where(['nickname'=>['like',$asd],'status'=>1,'admin'=>2])->select();

		$num = count($result);//查询共有多少数据
		$page = new Pager(5, $num);//每页5个(老的方式)
		$limit = $page->limit();//$limit

		$result = Db::name('user')->where(['nickname'=>['like',$asd],'status'=>1,'admin'=>2])->select();
		$a = [];
		if(!empty($result)){
			//将时间戳转换成日期格式
			foreach ($result as $key => $value) {
				$value['create_time'] = date('Y-m-d H:i:s',$value['create_time']);
				$value['update_time'] = date('Y-m-d H:i:s',$value['update_time']);
				$a[$key] = $value;
			}
		}
		$b['data'] = $a;
		$b['allPage'] = $page->allPage();
		$b['num'] = $num;
		return json_encode($b);
	}


	// 用户回收站
	public function outlist()
	{
		//调用防跳墙函数
		$this->tiaoqiang();
		return $this->fetch();
	}


	// 用户回收站----用户信息
	public function selectoutlist()
	{
		
		$result = Db::name('user')->where(['status'=>2,'admin'=>2])->select();
		$num = count($result);//查询共有多少数据
		$page = new Pager(5, $num);//每页5个(老的方式)
		$limit = $page->limit();//$limit

		$result = Db::name('user')->where(['status'=>2,'admin'=>2])->limit($limit)->select();
		$a = [];
		if(!empty($result)){
			
			//将时间戳转换成日期格式
			foreach ($result as $key => $value) {
				$value['create_time'] = date('Y-m-d H:i:s',$value['create_time']);
				$value['update_time'] = date('Y-m-d H:i:s',$value['update_time']);
				$a[$key] = $value;
			}
		}
		$b['data'] = $a;
		$b['allPage'] = $page->allPage();
		$b['num'] = $num;
		return json_encode($b);
	}

	

	//用户回收站---搜索数据
	public function selectlist2(Request $request)
	{
		//查询和搜索信息一致的信息
		$content = $request->post('content');
		$asd = '%'.$content.'%';
		$result = Db::name('user')->where(['nickname'=>['like',$asd],'status'=>2,'admin'=>2])->select();

		$num = count($result);//查询共有多少数据
		$page = new Pager(5, $num);//每页5个(老的方式)
		$limit = $page->limit();//$limit

		$result = Db::name('user')->where(['nickname'=>['like',$asd],'status'=>2,'admin'=>2])->select();
		$a = [];
		if(!empty($result)){
			//将时间戳转换成日期格式
			foreach ($result as $key => $value) {
				$value['create_time'] = date('Y-m-d H:i:s',$value['create_time']);
				$value['update_time'] = date('Y-m-d H:i:s',$value['update_time']);
				$a[$key] = $value;
			}
		}
		$b['data'] = $a;
		$b['allPage'] = $page->allPage();
		$b['num'] = $num;
		return json_encode($b);
	}

	//用户列表---拉黑用户到回收站
	public function deloutsong(Request $request)
	{
		$id = $request->post('id');
		$a = ['status'=>1];
		$result = Db::name('user')->where('uid',$id)->update($a);
		if(empty($result)){
			return json_encode(['state'=>2]);
		}else{
			return json_encode(['state'=>1]);
		}
	}
	//用户列表--修改用户页面
	public function update(Request $request)
	{
		//调用防跳墙函数
		$this->tiaoqiang();

		$id = $request->get('id');
		$result = Db::name('user')->where('uid',$id)->select();
		$result = $result[0];
		$this->assign('data',$result);
		$this->assign('id',$id);
		return $this->fetch();
	}

	// 用户列表---修改用户信息，数据处理
	public function updatexinxi(Request $request)
	{
		$id = $request->post('id');
		$result = Db::name('user')->where('uid',$id)->select();
		//用户名
		if(empty($request->post('name'))){
			$data['nickname'] = $result[0]['nickname'];
		}else{
			$data['nickname'] = $request->post('name');
		}

		//邮箱
		if(empty($request->post('email'))){
			$data['email'] = $result[0]['email'];
		}else{
			$data['email'] = $request->post('email');
		}

		//手机号
		if(empty($request->post('phone'))){
			$data['phone'] = $result[0]['phone'];
		}else{
			$data['phone'] = $request->post('phone');
		}

		//积分
		if(empty($request->post('grade'))){
			$data['grade'] = $result[0]['grade'];
		}else{
			$data['grade'] = $request->post('grade');
		}

		//个人简介
		if(empty($request->post('autograph'))){
			$data['autograph'] = $result[0]['autograph'];
		}else{
			$data['autograph'] = $request->post('autograph');
		}

		//性别
		if(empty($request->post('sex'))){
			$data['sex'] = $result[0]['sex'];
		}else{
			$data['sex'] = $request->post('sex');
		}

		$result1 = Db::name('user')->where('uid',$id)->update($data);
		// echo '<script type="text/javascript">window.close();</script>';
	}

	// 用户管理----批量删除
	public function delete1(Request $request)
	{
		$id = $request->post('id');
		$id = explode(',',$id);//切换成数组
		// 遍历修改状态项
		foreach ($id as $key => $value) {
			$result = Db::name('user')->where('uid',$value)->update(['status'=>2]);
		}
	}

	// 用户管理----批量删除
	public function delete2(Request $request)
	{
		$id = $request->post('id');
		$id = explode(',',$id);//切换成数组
		// 遍历修改状态项
		foreach ($id as $key => $value) {
			$result = Db::name('user')->where('uid',$value)->update(['status'=>1]);
		}
	}
}