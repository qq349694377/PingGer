<?php
namespace app\admin\controller;

use think\Controller;
use think\Request;
use code\Code;
use think\Session;
use think\Db;
class User extends Controller
{
	public function index()
	{
		return $this->fetch();
	}

	//显示验证码，并保存session
	public function yzm()
	{
		$a = new Code();
		$b = $a->code;
		Session::set('yzm',$b);
		return $a->outImage();
	}

	//验证账号密码验证码是否正确，并且是管理员
	public function login(Request $request)
	{
		$name = $request->post('name');
		$pwd1 = $request->post('pwd');
		$pwd = md5($pwd1);
		$yzm = $request->post('yzm');
		$yzm1 = Session::get('yzm');
		if($yzm != $yzm1){
			//验证码不正确
			return json_encode(['states'=>1]);
		}

		$request = Db::name('user')->where('nickname',$name)->select();
		if(empty($request)){
			//用户不存在
			return json_encode(['states'=>2]);
		}
		$pwd2 = $request[0]['password'];
		if($pwd != $pwd2){
			//登陆了密码错误
			return json_encode(['states'=>3]);
		}
		//账号被锁定
		if($request[0]['status'] == 2){
			return json_encode(['states'=>6]);
		}
		
		$uid = $request[0]['uid'];
		if($request[0]['admin'] == 2){
			//还不是管理员
			return json_encode(['states'=>4]);
		}elseif($request[0]['admin'] == 1){
			//成功
			Session::set('uid',$uid);

			$result = Db::name('user')->where('uid',$uid)->update(['update_time'=>time()]);
			return json_encode(['states'=>5]);
		}
	}
	// 切换账户
	public function logout()
	{
		Session::delete('uid');
      	echo "<script>window.location.replace('/admin/user/index');</script>";
	}
	// 退出登录
	public function logout1()
	{
		Session::delete('uid');
      	echo "<script>alert('退出成功');window.location.replace('/index/index/index');</script>";
	}
	
}