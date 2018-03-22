<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Session;
use think\Request;
class Index extends Controller
{
	//框架
    public function index(Request $request)
    {
        if(!empty(Session::get('username'))){
            $admin = Db::name('user')->field('admin')->where('nickname', Session::get('username'))->select()[0]['admin'];
            //新消息提醒
            $pyqsum = Db::name('pyqreply')->where('replyuser', Session::get('username'))->where('read', 0)->count();
            $mvsum = Db::name('mvreply')->where('replyuser', Session::get('username'))->where('read', 0)->count();
            $sum = $pyqsum + $mvsum;
            $pyq = Db::name('pyqreply')->field('photo, user, create_time, content, cid, id')->where('replyuser', Session::get('username'))->where('read', 0)->select();
            $mv = Db::name('mvreply')->field('photo, user, create_time, content, cid, id')->where('replyuser', Session::get('username'))->where('read', 0)->select();

            $this->assign('sum', $sum);
            $this->assign('pyq', $pyq);
            $this->assign('mv', $mv);
            $this->assign('admin', $admin);
        }

        return $this->fetch();
    }

    //新消息提醒功能
    function message(Request $request)
    {
        if (!empty($request->get('id'))) {
            $data['read'] = 1;
            if($request->get('lei') == 1){
                $result = Db::name('pyqreply')->where('id', $request->get('id'))->update($data);
            }else{
                $result = Db::name('mvreply')->where('id', $request->get('id'))->update($data);
            }

            if ($result) {
                return json_encode(['state'=>1, 'a'=>$request->get('lei'), 'cid'=>$request->get('cid')]);
            }
        }
    }

    function click_all(Request $request)
    {
        $data['read'] = 1;
        $result1 = Db::name('pyqreply')->where('replyuser', Session::get('username'))->update($data);
        $result2 = Db::name('mvreply')->where('replyuser', Session::get('username'))->update($data);
    }

//首页
    function index1()
    {
        //获取首页推荐歌单 按收藏量推荐
        $list = Db::name('list')->field('id, list, author, sum, poster, create_time, collect')->where('status', 1)->limit(6)->order('collect desc')->select();
        //获取用户歌单
        if(!empty(Session::get('username'))){
            $song_list = Db::name('list')->field('id, list')->where('author', Session::get('username'))->where('status', 1)->order('create_time desc')->select();
            // dump($song_list);
            $this->assign('song_list', $song_list);
        }
        // dump($list);
        //获取轮播图信息
        $lb = Db::name('lbt')->where('status', 1)->limit(5)->order('create_time desc')->select();

        //歌手
        $singer = Db::name('singer')->where('status', 1)->limit(6)->order('top desc, toptime desc')->select();
        $this->assign('singer', $singer);
        $this->assign('list', $list);
        $this->assign('lb', $lb);
        return $this->fetch();
    }

    //歌手页面
    function singer(Request $request)
    {
        if (empty($request->get('mid'))) {
            echo "<script>alert('干啥呀...');window.parent.location.replace('/index/index/index');</script>";
        }
        $id = $request->get('mid');
        //查询歌手信息
        $singer = Db::name('singer')->where('id', $id)->select()[0];
        //歌曲总量
        $arr = explode(',', trim($singer['songid'], ','));
        if (empty($arr[0])) {
            $sum = 0;
        }else{
           $sum = count($arr); 
        }
        if(!empty(Session::get('username'))){
            $song_list = Db::name('list')->field('id, list')->where('author', Session::get('username'))->where('status', 1)->order('create_time desc')->select();
            // dump($song_list);
            $this->assign('song_list', $song_list);
        }
        $this->assign('singer', $singer);
        $this->assign('sum', $sum);
        return $this->fetch();
    }

    //歌手页面的js处理
    function doSinger(Request $request)
    {
        if (empty($request->get('id'))) {
            echo "<script>alert('干啥呀...');window.parent.location.replace('/index/index/index');</script>";
        }
        $id = $request->get('id');
        //查询歌曲的id
        $songId = Db::name('singer')->where('id', $id)->select()[0]['songid'];
        $songId = trim($songId, ',');
        //查询歌曲
        $list[0] = Db::name('song')->field('title, artist, mp3, poster')->where('status', 1)->where('id', 'in', ($songId))->select();
        $list[1] = Db::name('song')->field('id')->where('status', 1)->where('id', 'in', ($songId))->select();
        echo json_encode($list);
    }

    //歌单详细页
    function listSong(Request $request)
    {
        if(!empty(Session::get('username'))){
            $song_list = Db::name('list')->field('id, list')->where('author', Session::get('username'))->where('status', 1)->order('create_time desc')->select();
            // dump($song_list);
            $this->assign('song_list', $song_list);
        }
        //获取歌单ID
        $id = $request->get('id');
        // dump($id);
        //获取歌单信息
        $list = Db::name('list')->where('id', $id)->select();
        // dump($list);
        $songId = trim($list[0]['childrenid'], ',');
        // dump($songId);
        //查询歌单中所有歌曲的信息
        $songs = Db::name('song')->where('status', 1)->where('id', 'in', ($songId))->select();
        // dump($songs);
        $this->assign('song', $songs);
        $this->assign('list', $list[0]);
        return $this->fetch();
    }

    //歌单详细页的js部分
    function listSong_play(Request $request)
    {
        //获得歌单id
         $id = $request->get('id');
        // dump($id);
        //获取歌单信息
        $list = Db::name('list')->field('childrenid')->where('id', $id)->select();
        // dump($list);
        $songId = trim($list[0]['childrenid'], ',');
        // dump($songId);
        //查询歌单中所有歌曲的信息
        $songs[0] = Db::name('song')->field('title, artist, mp3, poster')->where('status', 1)->where('id', 'in', ($songId))->select();
        $songs[1] = Db::name('song')->field('id')->where('status', 1)->where('id', 'in', ($songId))->select();
        echo json_encode($songs);
    }

    //我的歌曲模块中点击歌单时的处理
    function list_play(Request $request)
    {
        //查找该用户的喜欢的歌id
        
             $songId1 = Db::name('user')->field('songId')->where('nickname', Session::get('username'))->select();
            $songId1 = trim($songId1[0]['songId'], ',');
            // 查询喜欢的歌曲
            $song[0] = Db::name('song')->field('title, artist, mp3, poster')->where('status', 1)->where('id', 'in', ($songId1))->select();
            $song[3] = Db::name('song')->field('id')->where('status', 1)->where('id', 'in', ($songId1))->select();
            if (empty($song[0])) {
                $songs[0] = 0;
            }else{
                $songs[0] = count($song[0]);
            }
            //获得歌单id
             $id = $request->get('id');
            // dump($id);
            //获取歌单信息
            $list = Db::name('list')->field('childrenid')->where('id', $id)->select();
            // dump($list);
            $songId = trim($list[0]['childrenid'], ',');
            // dump($songId);
            //查询歌单中所有歌曲的信息
            $song[1] = Db::name('song')->field('title, artist, mp3, poster')->where('status', 1)->where('id', 'in', ($songId))->select();
            $song[4] = Db::name('song')->field('id')->where('status', 1)->where('id', 'in', ($songId))->select();

            $songs[1] = array_merge($song[0], $song[1]);
            $songs[2] = array_merge($song[3], $song[4]);
            echo json_encode($songs);
       
    }

    //首页推荐歌曲
    function song_recommendation()
    {
        //最新推荐
        $list[0] = Db::name('song')->field('title, artist, mp3, poster')->where('status', 1)->limit(9)->order('create_time desc')->select();
        $songid[0] = Db::name('song')->field('id')->where('status', 1)->limit(9)->order('create_time desc')->select();
        //热门推荐
        $list[1] = Db::name('song')->field('title, artist, mp3, poster')->where('status', 1)->limit(9)->order('top desc, toptime desc')->select();
        $songid[1] = Db::name('song')->field('id')->where('status', 1)->limit(9)->order('top desc, toptime desc')->select();
        //巅峰榜
        $list[2] = Db::name('song')->field('title, artist, mp3, poster')->where('status', 1)->limit(9)->order('hits desc')->select();
        $songid[2] = Db::name('song')->field('id')->where('status', 1)->limit(9)->order('hits desc')->select();

        //每日推荐
        $list[3] = Db::name('song')->field('title, artist, mp3, poster')->where('status', 1)->limit(8)->order('id desc')->select();
        $songid[3] = Db::name('song')->field('id')->where('status', 1)->limit(8)->order('id desc')->select();

        $data[0] = array_merge($list[0], $list[1], $list[2], $list[3]);
        $data[1] = array_merge($songid[0], $songid[1], $songid[2], $songid[3]);
        // dump($data);
        echo json_encode($data);
    }

    //mv
    function video()
    {
    	return $this->fetch();
    }

    //首页mv展示
    function mv_show()
    {
        $list = Db::name('mv')->field('mid, singer, title, m4v, ogv, webmv, poster')->where('status', 1)->limit(5)->order('create_time desc')->select();
        // dump($list);
        echo json_encode($list);
    }

    //MV模块初始化
    function mv_start(Request $request)
    {
        //获取查询的风格
        if (empty($request->get('style'))) {
            $style = 1;
         }else{
            $style = $request->get('style');
         }
        $list = Db::name('mvstyle')->field('mvid')->where('stylename', $style)->select()[0]['mvid'];
        $list = trim($list, ',');
        // dump($list);
         // 查询页数
         if (empty($request->get('page'))) {
            $count = 1;
         }else{
            $count = $request->get('page');
         }
        //查询mv总数
        $sum = Db::name('mv')->where('mid', 'in', ($list))->where('status', 1)->count();
        //每页显示数
        $num = 8;
        //获得总页数
        $mvs[0] = ceil($sum/$num);
        // dump($mvs[0]);
        //获取mv详细列表
        $mvs[1] = Db::name('mv')->field('mid, singer, title, poster')->where('mid', 'in', ($list))->where('status', 1)->limit(($count-1)*$num.','.$num)->order('create_time desc')->select();

        echo json_encode($mvs);
    }

    //加载选择播放的mv
    function playMv(Request $request)
    {
        $mid = $request->get('mid');
        // echo $mid;
        $list = Db::name('mv')->field('title, m4v, ogv, webmv, poster')->where('mid', $mid)->select();
        echo json_encode($list);
    }

    //我的音乐，歌单、收藏
    function listen()
    {
        //如果没登录,让其返回登录界面
        if(empty(Session::get('username'))){
            echo "<script>alert('您尚未登录,请登录...');window.parent.location.replace('/index/user/index');</script>";
        }
    //实现我的喜欢歌单封面的操作
        //查找该用户的喜欢的歌id
        if (!empty(Session::get('username'))) {
            $songId = Db::name('user')->field('songId')->where('nickname', Session::get('username'))->select();
            $songId = trim($songId[0]['songId'], ',');
            if (empty($songId)) {
                $list[0] = '';
            }else{
                //查询最后一首歌的id
                if (empty($songId)) {
                    $last = '';
                }else{
                    $last = array_slice(explode(',', $songId), -1, 1)[0]; 
                    // dump($last);
                }
                $list = Db::name('song')->field('poster')->where('id', $last)->select();
                // dump($list);
            }
        //这是遍历歌单的操作
            //我的歌单
            $mylists = Db::name('list')->field('id, list, poster, author')->where('author', Session::get('username'))->where('status', 1)->order('create_time desc')->select();
            // dump($mylist);
            //收藏歌单
            $savelist = Db::name('user')->field('savelist')->where('nickname', Session::get('username'))->select();
            $savelist = trim($savelist[0]['savelist'], ',');
            if (empty($savelist)) {
                $savelists = '';
            }else{
                 $savelists = Db::name('list')->field('id, list, poster, author')->where('status', 1)->where('id', 'in', ($savelist))->select();
            }
            $this->assign('mylist', $mylists);
            $this->assign('savelist', $savelists);
            $this->assign('list', $list[0]);
            return $this->fetch();
        }
    }

    //关于我的音乐页面的操作
    function mySong()
    {
        //查找该用户的喜欢的歌id
        $songId = Db::name('user')->field('songId')->where('nickname', Session::get('username'))->select();
        $songId = trim($songId[0]['songId'], ',');
        // dump($songId);
        // 查询喜欢的歌曲
        $list[0] = Db::name('song')->field('title, artist, mp3, poster')->where('id', 'in', ($songId))->select();
        $list[1] = Db::name('song')->field('id')->where('id', 'in', ($songId))->select();
        // dump($list);
        echo json_encode($list);
    }

    //歌曲分类
     public function genres()
    {
        if(!empty(Session::get('username'))){
            $song_list = Db::name('list')->field('id, list')->where('author', Session::get('username'))->where('status', 1)->order('create_time desc')->select();
            // dump($song_list);
            $this->assign('song_list', $song_list);
        }
        //查询主风格
        $style_one = Db::name('songstyle')->where('status', 1)->where('grade', 1)->select();
        //查询二级风格
        $style_two = Db::name('songstyle')->where('status', 1)->where('grade', 2)->select();
        $this->assign('style_one', $style_one);
        $this->assign('style_two', $style_two);
    	return $this->fetch();
    }

    //歌曲分类模块的js显示操作
    function doGeners(Request $request)
    {
        $list[2] = $request->get('style');
        // 查询页数
         if (empty($request->get('page'))) {
            $count = 1;
         }else{
            $count = $request->get('page');
         }
        //每页显示数
        $num = 12;
        if ($list[2] == '全部') {
            $sum = Db::name('song')->where('status', 1)->count();
            //获得总页数
            $list[0] = ceil($sum/$num);
            $list[1] = Db::name('song')->field('title, artist, mp3, poster')->where('status', 1)->limit(($count-1)*$num.','.$num)->order('top desc, toptime desc')->select();
            $list[3] = Db::name('song')->field('id')->where('status', 1)->limit(($count-1)*$num.','.$num)->order('top desc, toptime desc')->select();
        } else {
            //查询该风格下所包含的歌曲ID
            $songId = Db::name('songstyle')->field('songid')->where('status', 1)->where('style', $list[2])->where('grade', 2)->select()[0]['songid'];
            $songId = trim($songId, ',');
            $sum = Db::name('song')->where('status', 1)->where('id', 'in', ($songId))->count();
            //获得总页数
            $list[0] = ceil($sum/$num);
            $list[1] = Db::name('song')->field('title, artist, mp3, poster')->where('status', 1)->where('id', 'in', ($songId))->limit(($count-1)*$num.','.$num)->order('top desc, toptime desc')->select();
            $list[3] = Db::name('song')->field('id')->where('status', 1)->where('id', 'in', ($songId))->limit(($count-1)*$num.','.$num)->order('top desc, toptime desc')->select();
        }
       
        echo json_encode($list);
    }

    //朋友圈动态
    public function events()
    {
        //查询朋友圈
        $list = Db::name('pyq')->where('status', 1)->order('create_time desc')->select();
        $this->assign('list', $list);
    	return $this->fetch();
    }

    //动态详情页面
    function blog(Request $request)
    {
        if (empty($request->get('id'))) {
            echo "<script>alert('干啥呀...');window.parent.location.replace('/index/index/video');</script>";
        }
        $id = $request->get('id');
        
        //查询mv信息
        $pyq = Db::name('pyq')->where('id', $id)->select();
         
        // 相关推荐
        $mvs = Db::name('pyq')->where('status', 1)->where('id', 'neq', $id)->where('uid', $pyq[0]['uid'])->order('create_time desc')->limit(10)->select();
        // dump($mvs);
        $data = Db::name('pyqreply')->where('status', 1)->where('cid', $id)->order('create_time')->select();
        $sum = Db::name('pyqreply')->where('status', 1)->where('cid', $id)->order('create_time')->count();
        $tree = $this->getSubTree($data, 'pid', 'id', '-1');
        // dump($tree);
        if(!empty(Session::get('username'))){
            $song_list = Db::name('list')->field('id, list')->where('author', Session::get('username'))->where('status', 1)->order('create_time desc')->select();
            // dump($song_list);
            $this->assign('song_list', $song_list);
        }
        $this->assign('mvs', $mvs);
        $this->assign('sum', $sum);
        $this->assign('tree', $tree);
        $this->assign('pyq', $pyq[0]);
        return $this->fetch();
    }

    //动态详情页面音乐播放js处理
    function doBlog(Request $request)
    {
        $id = $request->get('id');
        //查询歌曲的id
        $songId = Db::name('pyq')->field('bgmid')->where('id', $id)->select()[0]['bgmid'];
        $list[0] = Db::name('song')->field('title, artist, mp3, poster')->where('status', 1)->where('id', $songId)->select();
        $list[1] = $songId;
        echo json_encode($list);
    }

    //动态评论
    function reply_blog(Request $request)
    {
        if(empty(Session::get('username'))){
            $data = 1;
            return json_encode($data);
        }
        if (!empty($request->get('content'))) {
            $data['content'] = $request->get('content');
            $data['cid'] = $request->get('cid');
            $data['user'] = Session::get('username');
            if (empty(Session::get('pic_header'))) {
                $data['photo'] = '/images/a0.png';
            }else{
                $data['photo'] = Session::get('pic_header');
            }
            
            $data['pid'] = -1;
            $data['create_time'] = time(); 
            $result = Db::name('pyqreply')->insert($data);
            if ($result) {
                return json_encode($data);
            }
        }
    }
    function reply_blog_content(Request $request)
    {
        if(empty(Session::get('username'))){
            $data = 1;
            return json_encode($data);
        }
        if(!empty($request->get('content'))){
            $data['content'] = $request->get('content');
            $data['cid'] = $request->get('cid');
            $data['replyuser'] = $request->get('user');
            $data['user'] = Session::get('username');
            $data['photo'] = $request->get('pic');
            $data['pid'] = $request->get('id');
            $data['create_time'] = time();

            $result = Db::name('pyqreply')->insert($data);
            if ($result) {
                return json_encode($data);
            }
        }
    }

    //分享音乐，作为朋友圈动态
    function share(Request $request)
    {
        if(empty(Session::get('username'))){
            return json_encode(['state'=>3]);
        }
        if(!empty($request->get('content'))){
            $data['content'] = $request->get('content');
            //通过id查询title
            $data['title'] = Db::name('song')->field('title')->where('id', $request->get('id'))->select()[0]['title'];
            $data['img'] = Db::name('song')->field('poster')->where('id', $request->get('id'))->select()[0]['poster'];
            //此处UID为分享人名字
            $data['uid'] = Session::get('username');
            $data['bgmid'] = $request->get('id');
            $data['create_time'] = time();
            $result = Db::name('pyq')->insert($data);
            if ($result) {
                return json_encode(['state'=>1]);
            }else{
                return json_encode(['state'=>2]);
            }
        }
    }
    //视频播放界面
    function videoPlay(Request $request)
    {
        if (empty($request->get('mid'))) {
            echo "<script>alert('干啥呀...');window.parent.location.replace('/index/index/video');</script>";
        }
        $mid = $request->get('mid');
        
        //查询mv信息
        $mv = Db::name('mv')->field('singer, title, create_time, replycount, content')->where('mid', $mid)->select();
         
        // 相关推荐
        $mvs = Db::name('mv')->field('mid, singer, title, create_time, poster')->where('status', 1)->where('mid', 'neq', $mid)->where('singer', $mv[0]['singer'])->order('create_time desc')->select();
        // dump($mvs);
        $data = Db::name('mvreply')->where('status', 1)->where('cid', $mid)->order('create_time')->select();
        $sum = Db::name('mvreply')->where('status', 1)->where('cid', $mid)->order('create_time')->count();
        $tree = $this->getSubTree($data, 'pid', 'id', '-1');
        // dump($tree);
        $this->assign('mvs', $mvs);
        $this->assign('sum', $sum);
        $this->assign('tree', $tree);
        $this->assign('mv', $mv[0]);
        return $this->fetch();
    }

    //视频评论功能
    function reply(Request $request)
    {
        if(empty(Session::get('username'))){
            $data = 1;
            return json_encode($data);
        }
        if (!empty($request->get('content'))) {
            $data['content'] = $request->get('content');
            $data['cid'] = $request->get('cid');
            $data['user'] = Session::get('username');
            if (empty(Session::get('pic_header'))) {
                $data['photo'] = '/images/a0.png';
            }else{
                $data['photo'] = Session::get('pic_header');
            }
            
            $data['pid'] = -1;
            $data['create_time'] = time(); 
            $result = Db::name('mvreply')->insert($data);
            if ($result) {
                return json_encode($data);
            }
        }
    }
    function reply_content(Request $request)
    {
        if(empty(Session::get('username'))){
            $data = 1;
            return json_encode($data);
        }
        if(!empty($request->get('content'))){
            $data['content'] = $request->get('content');
            $data['cid'] = $request->get('cid');
            $data['replyuser'] = $request->get('user');
            $data['user'] = Session::get('username');
            $data['photo'] = $request->get('pic');
            $data['pid'] = $request->get('id');
            $data['create_time'] = time();

            $result = Db::name('mvreply')->insert($data);
            if ($result) {
                return json_encode($data);
            }
        }
    }

    //搜索结果页面
    function search()
    {
        if(!empty(Session::get('username'))){
            $song_list = Db::name('list')->field('id, list')->where('author', Session::get('username'))->where('status', 1)->order('create_time desc')->select();
            // dump($song_list);
            $this->assign('song_list', $song_list);
        }
        return $this->fetch();
    }

    //搜索功能处理
    function doSearch(Request $request)
    {
        $value = $request->get('value');
        $list[2] = $request->get('style');
        // 查询页数
         if (empty($request->get('page'))) {
            $count = 1;
         }else{
            $count = $request->get('page');
         }
        //每页显示数
        $num = 4;
        if ($list[2] == '单曲') {
            $sum = Db::name('song')->where('title', 'like', '%'.$value.'%')->where('status', 1)->count();
            //获得总页数
            $list[0] = ceil($sum/$num);
            $list[1] = Db::name('song')->field('title, artist, mp3, poster')->where('title', 'like', '%'.$value.'%')->where('status', 1)->limit(($count-1)*$num.','.$num)->order('top desc, toptime desc')->select();
            $list[3] = Db::name('song')->field('id')->where('title', 'like', '%'.$value.'%')->where('status', 1)->limit(($count-1)*$num.','.$num)->order('top desc, toptime desc')->select();
        }
        if ($list[2] == 'MV') {
            $sum = Db::name('mv')->where('title', 'like', '%'.$value.'%')->where('status', 1)->count();
            //获得总页数
            $list[0] = ceil($sum/$num);
            $list[1] = Db::name('mv')->field('mid, singer, title, poster')->where('title', 'like', '%'.$value.'%')->where('status', 1)->limit(($count-1)*$num.','.$num)->order('create_time desc')->select();
            $list[3] = '占空';
        }

        if ($list[2] == '歌单') {
            $sum = Db::name('list')->where('list', 'like', '%'.$value.'%')->where('status', 1)->count();
            //获得总页数
            $list[0] = ceil($sum/$num);
            $list[1] = Db::name('list')->field('id, list, author, sum, poster, create_time')->where('list', 'like', '%'.$value.'%')->where('status', 1)->limit(($count-1)*$num.','.$num)->order('collect desc')->select();
            $list[3] = '占空';
        }

        if ($list[2] == '歌手') {
            $sum = Db::name('singer')->where('singer', 'like', '%'.$value.'%')->where('status', 1)->count();
            //获得总页数
            $list[0] = ceil($sum/$num);
            $list[1] = Db::name('singer')->where('singer', 'like', '%'.$value.'%')->where('status', 1)->limit(($count-1)*$num.','.$num)->order('top desc, toptime desc')->select();
            $list[3] = '占空';
        }
       
        echo json_encode($list);
    }

    //歌曲收藏
    function saveSong(Request $request)
    {
        if (!empty($request->get('id'))) {
            Session::set('songId', $request->get('id'));
        }
        //如果没登录,让其返回登录界面
        if(empty(Session::get('username'))){
            if (!empty(Session::get('songId'))) {
                Session::delete('songId');
            }
            return json_encode(['state'=>1]);
        }
        
        if (!empty($request->get('lid'))) {
           //查询该歌单下是否含有该歌曲
            $ids = Db::name('list')->field('childrenid')->where('id', $request->get('lid'))->select()[0]['childrenid'];
            $id = ','.Session::get('songId').',';
            if (strpos($ids, $id) !== false) {
                return json_encode(['state'=>2]);
            }else{
                $ids = $ids.Session::get('songId').',';
                $sum = count(explode(',', trim($ids, ',')));
                $result = Db::name('list')->where('id', $request->get('lid'))->update(['childrenid'=>$ids, 'sum'=>$sum]);
                if ($result) {
                    return json_encode(['state'=>3]);
                }
            }
        }

        //创建歌单
        if (!empty($request->get('name'))) {
            //获取歌曲封面作为歌单封面
            $poster = Db::name('song')->field('poster')->where('id', Session::get('songId'))->select()[0]['poster'];
            if (empty($request->get('content'))) {
                $result = Db::name('list')->insert(['childrenid'=>','.Session::get('songId').',', 'sum'=>1, 'create_time'=>time(), 'content'=>'作者比较懒，什么都没说', 'list'=>$request->get('name'), 'author'=>Session::get('username'), 'poster'=>$poster]);
            }else{
                $result = Db::name('list')->insert(['childrenid'=>','.Session::get('songId').',', 'sum'=>1, 'create_time'=>time(), 'content'=>$request->get('content'), 'list'=>$request->get('name'), 'author'=>Session::get('username'), 'poster'=>$poster]);   
            }
            if ($result) {
                return json_encode(['state'=>4]);
            }
        }
    }

    //添加喜欢的歌曲
    function likeSong(Request $request)
    {
        if(empty(Session::get('username'))){
            if (!empty(Session::get('songId'))) {
                Session::delete('songId');
            }
            return json_encode(['state'=>1]);
        }
        //查询有没有已添加喜欢
       $songId = Db::name('user')->field('songId')->where('nickname', Session::get('username'))->select()[0]['songId'];
       $id = ','.$request->get('id').',';
       if (strpos($songId, $id) !== false) {
            return json_encode(['state'=>5]);
        }else{
            $ids = $songId.$request->get('id').',';
            $result = Db::name('user')->where('nickname', Session::get('username'))->update(['songId'=>$ids]);
            if ($result) {
                return json_encode(['state'=>6]);
            }
        }
    }

//  取消喜欢
    function cancel_likeSong(Request $request)
    {
       $songId = Db::name('user')->field('songId')->where('nickname', Session::get('username'))->select()[0]['songId'];
       $arr = explode(',', trim($songId, ','));
       $key = array_search($request->get('id'), $arr);
       array_splice($arr, $key, 1);
       if (empty($arr)) {
           $songId = ',';
       }else{
            $songId = ','.join(',', $arr).',';
       }
       
        $result = Db::name('user')->where('nickname', Session::get('username'))->update(['songId'=>$songId]);
        if ($result) {
            return json_encode(['state'=>7]);
        }
    }

    //收藏歌单
    function save_list(Request $request)
    {
        //如果没登录,让其返回登录界面
        if(empty(Session::get('username'))){
            return json_encode(['state'=>1]);
        }else{
            $savelist = Db::name('user')->field('savelist')->where('nickname', Session::get('username'))->select()[0]['savelist'];
            $id = ','.$request->get('id').',';
           if (strpos($savelist, $id) !== false) {
                return json_encode(['state'=>8]);
            }else{
                $ids = $savelist.$request->get('id').',';
                $result = Db::name('user')->where('nickname', Session::get('username'))->update(['savelist'=>$ids]);
                if ($result) {
                    return json_encode(['state'=>3]);
                }
            }
        }
    }

    //取消收藏
    function save_cancel(Request $request)
    {
       $savelist = Db::name('user')->field('savelist')->where('nickname', Session::get('username'))->select()[0]['savelist'];
       $arr = explode(',', trim($savelist, ','));
       $key = array_search($request->get('id'), $arr);
       array_splice($arr, $key, 1);
       if (empty($arr)) {
           $savelist = ',';
       }else{
            $savelist = ','.join(',', $arr).',';
       }
       
        $result = Db::name('user')->where('nickname', Session::get('username'))->update(['savelist'=>$savelist]);
        if ($result) {
            return json_encode(['state'=>9]);
        }
    }

    function del_song1(Request $request)
    {
        if (!empty($request->get('id'))) {
            $songId = Db::name('user')->field('songId')->where('nickname', Session::get('username'))->select()[0]['songId'];
           $arr = explode(',', trim($songId, ','));
           $key = array_search($request->get('id'), $arr);
           array_splice($arr, $key, 1);
           if (empty($arr)) {
               $songId = ',';
           }else{
                $songId = ','.join(',', $arr).',';
           }
           
            $result = Db::name('user')->where('nickname', Session::get('username'))->update(['songId'=>$songId]);
            if ($result) {
                return json_encode(['state'=>7]);
            }
        }
    }

    /** * @param $data array 数据 * @param $parent string 父级元素的名称 如 parent_id * @param $son string 子级元素的名称 如 comm_id * @param $pid int 父级元素的id 实际上传递元素的主键 * @return array */ 
    protected function getSubTree($data , $parent , $son , $pid) { 
      $tmp = array();
      foreach ($data as $key => $value) { 
        if($value[$parent] == $pid) { 
          $value['child'] = $this->getSubTree($data , $parent , $son , $value[$son]); 
          $tmp[] = $value; 
        } 
      } 
      return $tmp; 
    }
}
