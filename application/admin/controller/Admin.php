<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;
use think\Session;
use pager\Pager;
class Admin extends Controller
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
			if(!in_array(8,$jid)){
				echo "<script>alert('你越界了！');window.location.replace('/admin/user/index');</script>";
				return false;
			}
		}
	}

	// 管理员列表
	public function list()
	{
		//调用防跳墙函数
		$this->tiaoqiang();
		return $this->fetch();
	}
	// 查询管理员信息
	public function selectlist()
	{
		$result = Db::name('user')->where(['admin'=>1])->select();
		$num = count($result);//查询共有多少数据
		$page = new Pager(5, $num);//每页5个(老的方式)
		$limit = $page->limit();//$limit

		$result = Db::name('user')->where(['admin'=>1])->limit($limit)->select();
		$a = [];
		if(!empty($result)){
			foreach ($result as $key => $value) {
				//将时间戳转换成日期格式
				$value['create_time'] = date('Y-m-d H:i:s',$value['create_time']);
				
				//查询当前用户所拥有的角色
				$iid = [];
				$result1 = Db::name('one')->where('uid',$value['uid'])->select();
				if(empty($result1)){
					$value['name'] = "<span style='color:red'>还没有安排角色！</span>";
				}else{
					foreach ($result1 as $val) {
						$iid[] = $val['iid'];
					}
					//角色id  转换为角色名称
					$name = '';
					foreach ($iid as $k => $v) {
						$result2 = Db::name('identity')->where('iid',$v)->select();
						$name .= $result2[0]['title'].'，';
					}
					$name = trim($name,'，');
					$value['name'] = $name;
				}
				$a[$key] = $value;
			}
		}
		$b['data'] = $a;
		$b['allPage'] = $page->allPage();
		$b['num'] = $num;
		return json_encode($b);
	}

	//管理员管理---停用
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

	//管理员管理---启用
	public function inssong(Request $request)
	{
		$id = $request->post('id');
		$a = ['status'=>1];
		$result = Db::name('user')->where('uid',$id)->update($a);
		if(empty($result)){
			return json_encode(['state'=>4]);
		}else{
			return json_encode(['state'=>3]);
		}
	}

	// 管理员列表 - 修改页面 
	public function update(Request $request)
	{
		//调用防跳墙函数
		$this->tiaoqiang();

		$uid = $request->get('uid');
		//$uid = 3;
		$request1 = Db::name('user')->where('uid',$uid)->select();
		$request2 = Db::name('identity')->where('status',1)->select();
		$request = $request1[0];
		
		$this->assign('data',$request);
		$this->assign('title',$request2);
		return $this->fetch();
	}

	// 管理员列表---修改管理员信息 - 修改数据
	public function upd(Request $request)
	{
		$name = $request->post('name');
		$pwd = $request->post('pwd');
		$pwd1 = md5($pwd);
		$pwd2 = $request->post('pwd1');
		$pwd3 = md5($pwd2);
		$phone = $request->post('phone');
		$email = $request->post('email');
		$content = $request->post('beizhu');
		$sex = $request->post('sex');
		$quanxian = $request->post('duoxuan');
		
		$shu = Db::name('user')->where(['nickname'=>$name,'password'=>$pwd1])->select();
		if(empty($shu)){
			//初始密码错误
			return json_encode(['state'=>1]);
		}
		$uid = $shu[0]['uid'];
		$data = ['password'=>$pwd3,'phone'=>$phone,'email'=>$email,'sex'=>$sex,'autograph'=>$content,'update_time'=>time()];
		// 将用户的基本信息进行修改
		$request1 = Db::name('user')->where('nickname',$name)->update($data);
		if(empty($request1)){
			//修改数据不符合要求
			return json_encode(['state'=>2]);
		}

		//删除之前的权限
		$request2 = Db::name('one')->where('uid',$uid)->delete();
		
		$iid = trim($quanxian,',');
		$iid = explode(',',$iid);
		foreach($iid as $value){
			$value = (int)$value + 1;
			
			$data1 = ['uid'=>$uid,'iid'=>$value,'create_time'=>time(),'status'=>1];
			// dump($data1);
			$a = Db::name('one')->insert($data1);
		}

		if(!empty($a)){
			return json_encode(['state'=>3]);//成功
		}
	}

	//  管理员列表--添加管理员
	public function add()
	{
		//调用防跳墙函数
		$this->tiaoqiang();

		$request2 = Db::name('identity')->where('status',1)->select();
		$this->assign('title',$request2);
		return $this->fetch();
	}

	// 管理员管理----搜索
	public function selectlist1(Request $request)
	{
		//查询和搜索信息一致的信息
		$content = $request->post('content');
		$asd = '%'.$content.'%';
		$result = Db::name('user')->where(['nickname'=>['like',$asd],'admin'=>1])->select();

		$num = count($result);//查询共有多少数据
		$page = new Pager(5, $num);//每页5个(老的方式)
		$limit = $page->limit();//$limit

		$result = Db::name('user')->where(['nickname'=>['like',$asd],'admin'=>1])->select();
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
	//  管理员管理----添加管理员
	public function insert(Request $request)
	{
		$name = $request->post('name');
		$name = trim($name,'');
		$pwd = $request->post('pwd');
		$pwd1 = md5($pwd);
		$pwd2 = $request->post('pwd1');
		$pwd3 = md5($pwd2);
		$phone = $request->post('phone');
		$phone = (int)$phone;
		$email = $request->post('email');
		$email = trim($email,'');
		$content = $request->post('beizhu');
		$sex = $request->post('sex');
		$sex = (int)$sex;
		$quanxian = $request->post('duoxuan');

		if($pwd != $pwd2){
			//密码不一致
			return json_encode(['state'=>1]);
		}

		$ip = $request->ip();
		$ip = ip2long($ip);
	
		$data = ['password'=>$pwd1,'phone'=>$phone,'email'=>$email,'sex'=>$sex,'autograph'=>$content,'create_time'=>time(),'update_time'=>time(),'ip'=>$ip,'nickname'=>$name,'admin'=>1];

		// 将用户的基本信息进行修改
		$request1 = Db::name('user')->insert($data);

		$uid = Db::name('user')->getLastInsID();
		if(empty($request1)){
			//修改数据不符合要求
			return json_encode(['state'=>2]);
		}

		//删除之前的权限
		$request2 = Db::name('one')->where('uid',$uid)->delete();
		
		$iid = trim($quanxian,',');
		$iid = explode(',',$iid);
		foreach($iid as $value){
			$value = (int)$value + 1;//键从0开始的，id要从1开始
			
			$data1 = ['uid'=>$uid,'iid'=>$value,'create_time'=>time(),'status'=>1];
			// dump($data1);
			$a = Db::name('one')->insert($data1);
		}

		if(!empty($a)){
			return json_encode(['state'=>3]);//成功
		}
	}

	// 角色管理
	public function role()
	{
		//调用防跳墙函数
		$this->tiaoqiang();

		return $this->fetch();
	}

	// 角色管理----查询角色管理的信息
	public function selectrole()
	{
		$result = Db::name('identity')->select();
		$num = count($result);//查询共有多少数据
		$page = new Pager(5, $num);//每页5个(老的方式)
		$limit = $page->limit();//$limit

		$result = Db::name('identity')->limit($limit)->select();
		$a = [];
		if(!empty($result)){
			//将时间戳转换成日期格式
			foreach ($result as $key => $value) {
				$value['create_time'] = date('Y-m-d H:i:s',$value['create_time']);
				$a[$key] = $value;
			}
		}
		// dump($result1);die;
		$b['data'] = $a;
		$b['allPage'] = $page->allPage();
		$b['num'] = $num;
		return json_encode($b);

	}

	//获取拥有角色的昵称
	public function selectname(Request $request)
	{
		$iid = $request->post('iid');
		$result = Db::name('one')->where('iid',$iid)->select();
		$a = '';
		if(!empty($result)){
			foreach($result as $key =>$value){
				$name = $value['uid'];
				$result1 = Db::name('user')->where('uid',$name)->select();
				$a .= $result1[0]['nickname'].'，';
			}
			$a = rtrim($a,'，');
			return json_encode(['state'=>$a]);
		}else{
			return json_encode(['state'=>3.1415926]);
		}
		
	}

	//角色管理---停用
	public function delsong1(Request $request)
	{
		$id = $request->post('id');
		$a = ['status'=>2];
		$result = Db::name('identity')->where('iid',$id)->update($a);
		if(empty($result)){
			return json_encode(['state'=>2]);
		}else{
			return json_encode(['state'=>1]);
		}
	}

	//角色管理---启用
	public function inssong1(Request $request)
	{
		$id = $request->post('id');
		$a = ['status'=>1];
		$result = Db::name('identity')->where('iid',$id)->update($a);
		if(empty($result)){
			return json_encode(['state'=>4]);
		}else{
			return json_encode(['state'=>3]);
		}
	}

	// 角色管理----删除角色()
	public function deluser(Request $request)
	{
		$iid = $request->post('iid');

		$a = ['status'=>2];
		$result = Db::name('identity')->where('iid',$iid)->update($a);
		if(empty($result)){
			return json_encode(['state'=>2]);
		}else{
			return json_encode(['state'=>1]);
		}
	}

	// 角色管理----恢复角色()
	public function insuser(Request $request)
	{
		$iid = $request->post('iid');
		$a = ['status'=>1];
		$result = Db::name('identity')->where('iid',$iid)->update($a);
		if(empty($result)){
			return json_encode(['state'=>4]);
		}else{
			return json_encode(['state'=>3]);
		}
	}


	// 角色管理----修改角色信息
	public function xiugai(Request $request)
	{
		//调用防跳墙函数
		$this->tiaoqiang();

		$iid = $request->get('iid');
		//$iid = 2;
		//当前角色信息
		$result1 = Db::name('identity')->where('iid',$iid)->select();
		$result = $result1[0];

		// 用户信息(主要是名称)
		$result2 = Db::name('one')->where(['status'=>1,'iid'=>$iid])->select();
		$a = '';
		foreach($result2 as $key=>$value){
			$uid = $value['uid'];
			$result3 = Db::name('user')->where('uid',$uid)->select();
			$a .= $result3[0]['nickname'].'，';
		}
		$a = rtrim($a,'，');

		//所有权限信息
		$result4 = Db::name('quanxian')->select();

		$this->assign('data',$result);
		$this->assign('user',$a);
		$this->assign('title',$result4);
		$this->assign('iid',$iid);
		return $this->fetch();
	}

	// 角色列表---修改角色信息
	public function gaigai(Request $request)
	{
		$name = $request->post('pwd');
		$content = $request->post('beizhu');
		//要修改two表的 jid
		$jid = $request->post('duoxuan');
		$jid = rtrim($jid,',');
		//当前角色的iid
		$iid = $request->post('iid');
		//修改角色的信息
		$data = ['title'=>$name,'content'=>$content,'create_time'=>time()];
		// echo json_encode($data);die;
		$result = Db::table('m_identity')->where('iid',$iid)->update($data);
		if(empty($result)){
			return json_encode(['state'=>1]);//修改角色信息错误
		}

		// 修改关联表(权限和角色的---two)
		$jid = explode(',', $jid);

		//删除two表中$iid的当前权限
		$result1 = Db::name('two')->where('iid',$iid)->delete();

		foreach($jid as $value){
			$data1 = ['iid'=>$iid,'jid'=>$value,'create_time'=>time()];
			$result2 = Db::name('two')->insert($data1);
		}

		return json_encode(['state'=>2]);
	}

	// 角色列表---添加角色
	public function addjuese()
	{
		//调用防跳墙函数
		$this->tiaoqiang();

		$request2 = Db::name('quanxian')->select();
		$this->assign('title',$request2);
		return $this->fetch();
	}

	// 角色管理----搜索
	public function selectrole1(Request $request)
	{
		//查询和搜索信息一致的信息
		$content = $request->post('content');
		$asd = '%'.$content.'%';
		$result = Db::name('identity')->where(['title'=>['like',$asd]])->select();

		$num = count($result);//查询共有多少数据
		$page = new Pager(5, $num);//每页5个(老的方式)
		$limit = $page->limit();//$limit

		$result = Db::name('identity')->where(['title'=>['like',$asd]])->select();
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

	public function addjue(Request $request)
	{
		$title = $request->post('pwd');
		$content = $request->post('beizhu');
		$jid = $request->post('duoxuan');

		
		$data = ['title'=>$title,'content'=>$content,'create_time'=>time(),'status'=>1];
		$result = Db::name('identity')->insert($data);
		if(empty($result)){
			//填写的信息超过长度
			return json_encode(['state'=>1]);
		}
		$userId = Db::name('identity')->getLastInsID();
		$jid = explode(',', rtrim($jid,','));
		foreach ($jid as $value) {
			$value = (int)$value;
			$data1 = ['iid'=>$userId,'jid'=>$value,'create_time'=>time(),'status'=>1];
			$result1 = Db::name('two')->insert($data1);
		}
		return json_encode(['state'=>2]);//成功
	}

	// 权限管理
	public function permission()
	{
		//调用防跳墙函数
		$this->tiaoqiang();

		$result = Db::name('quanxian')->select();
		$a = count($result);
		$this->assign('data',$result);
		$this->assign('count',$a);
		return $this->fetch();
	}

}