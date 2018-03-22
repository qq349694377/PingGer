<?php
namespace app\index\controller;
use think\Controller;
use other\Captcha;
use think\Session;
use think\Request;
use think\Db;
use lib\Ucpaas;
use open\open51094;// 第三方登陆用的

class User extends Controller
{
	//登录界面
    public function index()
    {
        return $this->fetch();
    }
    // 登陆
    public function dologin(Request $request)
    {
      $name = $request->post('name');
      $pwd1 = $request->post('pwd');
      $pwd = md5($pwd1);

      //查询用户名是否存在
      $result = Db::name('user')->where('nickname',"$name")->where('status', 1)->select();
      $result1 = Db::name('user')->where('phone',"$name")->where('status', 1)->select();
      if(empty($result) && empty($result1)){
        //用户名不存在，返回1
        return json_encode(['state'=>1]);
      }else{
        if(!empty($result)){
            $password = $result[0]['password'];
            if($pwd != $password){
               return json_encode(['state'=>2]);
            }
        }else{
            $password1 = $result1[0]['password'];
            if($pwd != $password1){
               return json_encode(['state'=>2]);
            }
        }
      }
      //成功返回3
      // 查询头像
      if (empty($result)) {
        $user = Db::name('user')->field('nickname, photo')->where('phone',$name)->where('status', 1)->select();
        Session::set('username', $user[0]['nickname']);
        Session::set('pic_header', $user[0]['photo']);
      }else{
        $header = Db::name('user')->field('photo')->where('nickname',$name)->where('status', 1)->select()[0]['photo'];
        Session::set('username', $name);
        Session::set('pic_header', $header);
      }
      return json_encode(['state'=>3]);
    }

    // 第三方登陆
    function san(Request $request)
    {
      $open = new open51094();
      $code = $_GET['code'];
      $a = $open->me($code);

      $name = $a['name'];
      $img = $a['img'];
      $sex = $a['sex'];
      $uniq = $a['uniq'];//qq登陆唯一id
      $pwd = md5($uniq);
      $from = $a['from'];

      $nickname = $from.'+'.$name;//昵称为 来源+接受的名字
      $result = Db::name('user')->where('nickname',"$nickname")->select();

      //判断是否是新用户
      if(empty($result)){
        $ip1 = $request->ip();
        $ip = ip2long($ip1);
        $time = time();
        $grade = 50;//注册送50积分

        $data = ['nickname'=>$nickname,'photo'=>$img,'sex'=>$sex,'password'=>$pwd,'ip'=>$ip,'create_time'=>$time,'update_time'=>$time,'phone'=>1,'grade'=>$grade,'email'=>'未设置'];
        $result1 = Db::name('user')->insert($data);
        // session保存用户名name
        Session::set('username', $nickname);
        $this->success('登陆成功','/index/index/index');
      }else{
        $time = time();
        $result2 = Db::name('user')->where('password',$pwd)->update(['update_time'=>$time]);
        // session保存用户名name
        Session::set('username', $nickname);
        Session::set('pic_header', $img);
        $this->success('登陆成功','/index/index/index');
      }
    }



    //注册界面
    function register()
    {
        return $this->fetch();
    }

    //发送手机验证码
    function jieshou(Request $request)
    {
      //载入ucpass类(上面已经use了)
      // require_once('/lib/Ucpaas.class.php');
      
      //给那个手机号发送
      $to = $request->post('phone');
      
      //初始化必填
      $options['accountsid']='216fd82dcd4c33c4a43089a752ca48b7';
      $options['token']='79f2bcdde5a8b55bf5bbf79f242941d3';      
      //初始化 $options必填
      $ucpass = new Ucpaas($options);
      //开发者账号信息查询默认为json或xml
      header("Content-Type:text/html;charset=utf-8");
      //封装验证码
      $str = '012345678901235678901234567890123456789';
      $code = substr(str_shuffle($str),0,5);
      Session::set('sjyzm',$code);
      //$_SESSION['code']=$code;
      //短信验证码（模板短信）,默认以65个汉字（同65个英文）为一条（可容纳字数受您应用名称占用字符影响），超过长度短信平台将会自动分割为多条发送。分割后的多条短信将按照具体占用条数计费。
      $appId = "cfacff47397d4196a5076f83fd8404a2";
      $templateId = "228884";
      //这就是验证码
      $param=$code;
      echo $ucpass->templateSMS($appId,$to,$templateId,$param);
    }
    //注册的时候 获取不到session
    //所以需要通过函数传下
    function pig(Request $request)
    {
      $phone = Session::get('sjyzm');
      //echo $phone
      echo json_encode(['phone'=>$phone]);
    }


    // 添加数据
    function insert(Request $request)
    {
      $nickname = $request->post('nickname');
      $phone = $request->post('phone');
      $email = $request->post('email');
      $pwd1 = $request->post('pwd');
      $pwd = md5($pwd1);
      //昵称已经注册
      $result = Db::name('user')->where('nickname',"$nickname")->select();
      if(!empty($result)){
        echo json_encode(['state'=>1]);
        die;
      }
      //手机号已经注册过了
      $result1 = Db::name('user')->where('phone',"$phone")->select();
      if(!empty($result1)){
        echo json_encode(['state'=>2]);
        die;
      }

      //email已经注册过了
      $result2 = Db::name('user')->where('email',"$email")->select();
      if(!empty($result2)){
        echo json_encode(['state'=>3]);
        die;
      }

      $ip1 = $request->ip();
      $ip = ip2long($ip1);
      $time = time();
      //注册送50积分
       $grade = 50;
      $data = ['nickname'=>$nickname,'phone'=>$phone,'password'=>$pwd,'email'=>$email,'ip'=>$ip,'grade'=>$grade,'create_time'=>$time,'update_time'=>$time];
      $result3 = Db::name('user')->insert($data);
      //插入失败
      if(empty($result3)){
        echo json_encode(['state'=>4]);
        die;
      }else{
        echo json_encode(['state'=>5]);
        // 保存session
        Session::set('username',$nickname);
        die;
      }
    }

    //退出登录
    function logout()
    {
      
      Session::delete('username');
      Session::delete('pic_header');
      echo "<script>alert('退出成功');window.location.replace('/index/index/index');</script>";
      
    }

    //个人资料
    function profile()
    {    //如果没登录,让其返回登录界面
        if(empty(Session::get('username'))){
            echo "<script>alert('您尚未登录,请登录...');window.parent.location.replace('/index/user/index');</script>";
        }
        //查询用户信息
        $user = Db::name('user')->where('nickname', Session::get('username'))->select()[0];
        // dump($user);
        //查询用户创建歌单数量
        $sum_list = Db::name('list')->where('author', Session::get('username'))->count('id');
        //查询用户喜欢的歌曲总量
        $arr = explode(',', trim($user['songId'], ','));
        if (empty($arr[0])) {
           $sum = 0;
         } else{
            $sum = count($arr);
         }
         if (!empty($user['birthday'])) {
            $birth = explode('/', $user['birthday']);
            $brithyear = $birth[0];
            //var_dump($birthyear);
            $brithmonth = $birth[1];
            $brithday = $birth[2];
          }else{
            $brithyear = 0;
            $brithmonth = 0;
            $brithday = 0;
          }
        //我的动态
        $mvs = Db::name('pyq')->where('status', 1)->where('uid', Session::get('username'))->order('create_time desc')->limit(5)->select();
        $this->assign('brithday', $brithday);
        $this->assign('brithyear', $brithyear);
        $this->assign('brithmonth', $brithmonth);
        $this->assign('sum_list', $sum_list);
        $this->assign('sum', $sum);
        $this->assign('user', $user);
        $this->assign('mvs', $mvs);
        return $this->fetch();
    }

    //修改密码
    function doReset(Request $request)
    {
      if (empty($request->get('password'))) {
          return json_encode(['state'=>1]);
       }else{
          $password = md5($request->get('password'));
       }

      $res = Db::name('user')->where('nickname', Session::get('username'))->where('password', $password)->select();
      //var_dump($res);
      if (!$res) {
        return json_encode(['state'=>2]);
      }

      if (!empty($request->get('pass'))) {
        if (strlen($request->get('pass'))<5 || strlen($request->get('pass'))>12) {
          return json_encode(['state'=>3]);
        }

        if (is_numeric($request->get('pass'))) {
          return json_encode(['state'=>4]);
        }

        if (empty($request->get('newpass'))) {
          return json_encode(['state'=>5]);
        }

        if ($request->get('newpass') != $request->get('pass')) {
          return json_encode(['state'=>6]);
        }
        $data['password'] = md5($request->get('pass'));
      }

      if (!empty($request->get('email'))) {
        $pattern="/([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a-z]{2,3}([.][a-z]{2})?/i";
        if(!preg_match($pattern,$request->get('email'))){
          return json_encode(['state'=>7]);
        }

        $data['email'] = $request->get('email');
      }

      $result = Db::name('user')->where('nickname', Session::get('username'))->update($data);
      if ($result) {
        return json_encode(['state'=>8]);
      } else {
        return json_encode(['state'=>9]);
      }

    }

    //上传头像
    function upload_head(Request $request)
    {
      // 获取表单上传文件
      $file = $request->file('picture');
      if (empty($file)) {
        return json_encode(['state'=>1]);
      }
      // 移动到框架应用根目录/public/uploads/ 目录下
      $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
      if ($info) {
        $pic = str_replace('\\', '/', $info->getSaveName());
        return json_encode(['state'=>$pic]);
      } else {
      // 上传失败获取错误信息
        return json_encode(['state'=>2]);
      }
    }

    //修改资料
    function setDetail(Request $request)
    {
      $data['photo'] = $request->get('pic');
      $data['sex'] = $request->get('sex');
      if (is_numeric($request->get('brithyear')) && is_numeric($request->get('brithmonth')) && is_numeric($request->get('brithday'))) {
        $birthday = [$request->get('brithyear'), $request->get('brithmonth'), $request->get('brithday')];
        $data['birthday'] = join('/' , $birthday);
      }
      $data['province'] = $request->get('province');
      $data['city'] = $request->get('city');
      if (!empty($request->get('phone'))) {
        $data['phone'] = $request->get('phone');
      }

      if (empty($request->get('autograph'))) {
        $data['autograph'] = '';
      }else{
        $data['autograph'] = $request->get('autograph');
      }
      //执行更新语句
      $result = Db::name('user')->where('nickname', Session::get('username'))->update($data);
      if ($result) {
        return json_encode(['state'=>8]);
      }
    }

    //忘记密码
    function forget()
    {
      return $this->fetch();
    }

    function reset(Request $request)
  {
    if (empty(input('username')) || empty(input('email'))){
      echo "<script>alert('用户名或邮箱不能为空...');window.parent.location.replace('/index/user/forget');</script>";
      exit;
    }
    $result = Db::name('user')->where('nickname', input('username'))->where('email', input('email'))->select();
    // //var_dump($result);
    if (!$result) {
      echo "<script>alert('用户名或邮箱不存在...');window.parent.location.replace('/index/user/forget');</script>";
      exit;
    }

    require_once("email/functions.php");
    // //设置随机验证码
    $str = substr(str_shuffle('1234567890') , 0 , 6);
    
    // //发送邮件
    $flag = sendMail(input('email') , '申请重置密码' , input('username').', 您正在申请重置密码,验证码为'.$str.',请勿泄露');
    // //var_dump($flag);
    if ($flag) {
      Session::set('str', $str);
      Session::set('name', input('username'));
      Session::set('email', input('email'));
      echo "<script>alert('密码重置邮件发送成功,请查收');window.parent.location.replace('/index/user/forget');</script>";
      exit;
    }else{
      echo "<script>alert('邮件发送失败');window.parent.location.replace('/index/user/forget');</script>";
      exit;
    }
  }

  function do_Reset()
  {
    if (input('code') != Session::get('str')) {
      echo "<script>alert('验证码不正确');window.parent.location.replace('/index/user/forget');</script>";
      exit;
    }
    require_once("email/functions.php");
    $pass = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFJHIJKLMNOPQRSTUVWXYZ1234567890') , 0 , 6);
    $flag = sendMail(Session::get('email') , '重置密码' , Session::get('name').', 您的密码重置成功,重置后的密码为'.$pass.',请勿泄露');
    if ($flag) {
      $data['password'] = md5($pass);
      $res = Db::name('user')->where('nickname', Session::get('name'))->update($data);
      Session::delete('name');
      Session::delete('str');
      Session::delete('email');
      echo "<script>alert('密码重置成功,请查收邮件获得新的密码并返回登录');window.parent.location.replace('/index/user/index');</script>";
      exit;
    } else {
      Session::delete('name');
      Session::delete('str');
      Session::delete('email');
      echo "<script>alert('密码重置失败');window.parent.location.replace('/index/user/forget');</script>";
      exit;
    }
  }

}
