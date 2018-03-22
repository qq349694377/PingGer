<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Session;
use think\Db;
class Index extends Controller
{
	public function index()
	{
		//防跳墙，获取session
		if(empty(Session::get('uid'))){
			echo "<script>alert('看你是要搞事情喽！');window.location.replace('/index/index/index');</script>";
			return false;
		}else{
			$id = Session::get('uid');
			$request = Db::name('user')->where('uid',$id)->select();
			if($request[0]['admin'] == 2){
				echo "<script>alert('你越界了！');window.location.replace('/index/index/index');</script>";
				return false;
			}
		}
		// 查询用户信息
		$uid = Session::get('uid');
		$request2 = Db::name('user')->where('uid',$uid)->select();
		$request3 = $request2[0];
		// 查询用户拥有的角色
		$request = Db::name('one')->where(['uid'=>$uid,'status'=>1])->order('iid')->select();
		$jid = [];
		$iid = [];
		//获取用户拥有的权限id
		foreach($request as $value){
			$iid[] = $value['iid'];//1,2
			//查询对应的权限id(jid)
			$request1 = Db::name('two')->where('iid',$value['iid'])->order('jid')->select();
			//便利所有的权限id，放入$jid中
			foreach($request1 as $value1){
				$jid[] = $value1['jid'];
			}
		}
		$jid = array_unique($jid);

		//管理员对应的角色名称
		$a = '';
		foreach ($iid as $val) {
			$result4 = Db::name('identity')->where('iid',$val)->select();
			$a .= $result4[0]['title'].'，';
		}
		$a = trim($a,'，');
		$this->assign('iid',$a);
		//管理员拥有的权限id
		$this->assign('jid',$jid);
		//查询管理员信息
		$this->assign('user',$request3);
		return $this->fetch();
	}

	public function welcome()
	{
		//防跳墙，获取session
		if(empty(Session::get('uid'))){
			echo "<script>alert('看你是要搞事情喽！');window.location.replace('/index/index/index');</script>";
			return false;
		}
		$uid = Session::get('uid');
		// 查询当前管理员的信息
		$result = Db::name('user')->where('uid',$uid)->select();
		// 将上次的登陆时间改成年月日
		$result[0]['update_time'] = date('Y-m-d H:i:s',$result[0]['update_time']);
		$result[0]['ip'] = long2ip($result[0]['ip']);
		// dump($result[0]);die;
		$data = $result[0];
		//获取当前服务器信息，
		$data1 = [];
		$request123 = Request::instance();
		// 当前服务器域名
		$data1['host'] = $request123->header()['host'];
		// 端口号
		$data1['duankou'] = $_SERVER['SERVER_PORT']; 
		// 域名
		$data['yuming'] = $_SERVER['HTTP_HOST']; 
		//获取当前浏览器和操作系统信息
		$a = $_SERVER['HTTP_USER_AGENT']; 
		$b = explode('/',$a);
		// dump($b);
		// 操作系统
		$data['xitong'] = $b[1];
		// 浏览器
		$data['liulanqi'] = $b[2];
		//共有歌曲数
		$qwe = Db::name('song')->select();
		$data['song'] = count($qwe);
		//共有mv数
		$asd = Db::name('mv')->select();
		$data['mv'] = count($asd);
		//朋友圈发帖数量
		$zxc = Db::name('pyq')->select();
		$data['pyq'] = count($zxc);
		//用户数量----普通用户
		$wer = Db::name('user')->where('admin',2)->select();
		$data['yh'] = count($wer);
		//管理员数量
		$sdf = Db::name('user')->where('admin',1)->select();
		$data['gly'] = count($sdf);
		//当前时间
		$data['time'] = date('Y-m-d H:i:s',time());
		$this->assign('data',$data);
		return $this->fetch();
	}
}