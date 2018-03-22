$(document).ready(function(){
//获取当前页面文件信息
var path = window.location.pathname;
console.log(path);



//查询推首页荐

if (path == '\/' || path == '\/index\/index\/index1') {
    $.ajax({
      type:'get',
      dataType:'json',
      url:'/index/index/song_recommendation',
      success:set
    });

    //首页mv展示
  
  $.ajax({
    type:'get',
    dataType:'json',
    url:'/index/index/mv_show',
    success:mv
  });
} 

//音乐播放界面
if (path == '\/index\/index\/videoPlay') {
  //获取url中歌曲mid值
  // var test = window.location.href;
  // alert(test);
   var mid = getUrlParam('mid');
   // alert(mid);
  //将值返回php进行数据查询
  $.ajax({
    type:'get',
    dataType:'json',
    url:'/index/index/playMv',
    data:{mid:mid},
    async:true,
    success:addMv
  });
} 

//加载mv资源
function addMv(data)
{
  // console.log(data);
  $("#jplayer_1").jPlayer({
    ready: function () {
      $(this).jPlayer("setMedia", data[0]);
    },
    swfPath: "js",
    supplied: "webmv, ogv, m4v",
    size: {
      width: "100%",
      height: "auto",
      cssClass: "jp-video-360p"
    },
    globalVolume: true,
    smoothPlayBar: true,
    keyEnabled: true
  });
}

//整理区分首页的各项推荐 
  function set(data)
  {
    console.log(data);
    // 最新
    create1(data[0], data[1]);
    //最热
    create2(data[0], data[1]);
    //巅峰
    create3(data[0], data[1]);

    //每日推荐
    create4(data[0], data[1]);
    success(data[0]);
  }

//便利音乐列表 最新
  function create1(data, id)
  {
    // console.log(data);
    for (var i = 0; i < 9; i++) {
       $('.newSong').append('<li class="list-group-item show"><div class="pull-right m-l" style="line-height: 20px;"><a href="#" class="m-r-sm"><i class="icon-share-alt" onClick="content('+id[i].id+')"><\/i><\/a><a href="#" class="pull-right active" data-toggle="class"><i class="fa fa-heart-o text-active" onclick="like('+id[i].id+')"></i><i class="fa fa-heart text text-danger" onclick="cancel_like('+id[i].id+')"></i></a><a href="#" data-toggle="class"><i class="fa fa-plus-circle text" onclick="add(this)" data-toggle="modal" data-target="#myModal"><i style="display:none">'+id[i].id+'</i></i><i class="fa fa-check-circle text-active text-info"></i></a><\/div><a href="#" class="jp-play-me m-r-sm pull-left" style="line-height: 20px;"><i style="display: none;">'+i+'<\/i><i class="icon-control-play text" onclick="poster(this)"><i style="display:none;">'+data[i].poster+'<\/i><\/i><i class="icon-control-pause text-active"><\/i><\/a><div class="clear text-ellipsis" style="line-height: 20px;"><span style="color: #fff;">'+data[i].title+'—'+data[i].artist+'<\/span><\/div><\/li>');
    }
    // success(data);
  }

//便利音乐列表 最热
  function create2(data, id)
  {
    // console.log(data);
    for (var i = 9; i < 18; i++) {
       $('.hotSong').append('<li class="list-group-item show"><div class="pull-right m-l" style="line-height: 20px;"><a href="#" class="m-r-sm"><i class="icon-share-alt" onClick="content('+id[i].id+')"><\/i><\/a><a href="#" class="pull-right active" data-toggle="class"><i class="fa fa-heart-o text-active" onclick="like('+id[i].id+')"></i><i class="fa fa-heart text text-danger" onclick="cancel_like('+id[i].id+')"></i></a><a href="#" data-toggle="class"><i class="fa fa-plus-circle text" onclick="add(this)" data-toggle="modal" data-target="#myModal"><i style="display:none">'+id[i].id+'</i></i><i class="fa fa-check-circle text-active text-info"></i></a><\/div><a href="#" class="jp-play-me m-r-sm pull-left" style="line-height: 20px;"><i style="display: none;">'+i+'<\/i><i class="icon-control-play text" onclick="poster(this)"><i style="display:none;">'+data[i].poster+'<\/i><\/i><i class="icon-control-pause text-active"><\/i><\/a><div class="clear text-ellipsis" style="line-height: 20px;"><span style="color: #fff;">'+data[i].title+'—'+data[i].artist+'<\/span><\/div><\/li>');
    }
    
  }

  //便利音乐列表 最热
  function create3(data, id)
  {
    // console.log(data);
    $('.topSong').append('<li class="list-group-item show"><div class="pull-right m-l" style="line-height: 20px;"><a href="#" class="m-r-sm"><i class="icon-share-alt" onClick="content('+id[18].id+')"><\/i><\/a><a href="#" class="pull-right active" data-toggle="class"><i class="fa fa-heart-o text-active" onclick="like('+id[18].id+')"></i><i class="fa fa-heart text text-danger" onclick="cancel_like('+id[18].id+')"></i></a><a href="#" data-toggle="class"><i class="fa fa-plus-circle text" onclick="add(this)" data-toggle="modal" data-target="#myModal"><i style="display:none">'+id[18].id+'</i></i><i class="fa fa-check-circle text-active text-info"></i></a><\/div><a href="#" class="jp-play-me m-r-sm pull-left" style="line-height: 20px;"><i style="display: none;">18<\/i><i class="icon-control-play text" onclick="poster(this)"><i style="display:none;">'+data[18].poster+'<\/i><\/i><i class="icon-control-pause text-active"><\/i><\/a><div class="clear text-ellipsis" style="line-height: 20px;"><i class="first_place">1<\/i><span style="color: #fff;">'+data[18].title+'—'+data[18].artist+'<\/span><\/div><\/li>');
    $('.topSong').append('<li class="list-group-item show"><div class="pull-right m-l" style="line-height: 20px;"><a href="#" class="m-r-sm"><i class="icon-share-alt" onClick="content('+id[19].id+')"><\/i><\/a><a href="#" class="pull-right active" data-toggle="class"><i class="fa fa-heart-o text-active" onclick="like('+id[19].id+')"></i><i class="fa fa-heart text text-danger" onclick="cancel_like('+id[19].id+')"></i></a><a href="#" data-toggle="class"><i class="fa fa-plus-circle text" onclick="add(this)" data-toggle="modal" data-target="#myModal"><i style="display:none">'+id[19].id+'</i></i><i class="fa fa-check-circle text-active text-info"></i></a><\/div><a href="#" class="jp-play-me m-r-sm pull-left" style="line-height: 20px;"><i style="display: none;">19<\/i><i class="icon-control-play text" onclick="poster(this)"><i style="display:none;">'+data[19].poster+'<\/i><\/i><i class="icon-control-pause text-active"><\/i><\/a><div class="clear text-ellipsis" style="line-height: 20px;"><i class="second_place">2<\/i><span style="color: #fff;">'+data[19].title+'—'+data[19].artist+'<\/span><\/div><\/li>');
    $('.topSong').append('<li class="list-group-item show"><div class="pull-right m-l" style="line-height: 20px;"><a href="#" class="m-r-sm"><i class="icon-share-alt" onClick="content('+id[20].id+')"><\/i><\/a><a href="#" class="pull-right active" data-toggle="class"><i class="fa fa-heart-o text-active" onclick="like('+id[20].id+')"></i><i class="fa fa-heart text text-danger" onclick="cancel_like('+id[20].id+')"></i></a><a href="#" data-toggle="class"><i class="fa fa-plus-circle text" onclick="add(this)" data-toggle="modal" data-target="#myModal"><i style="display:none">'+id[20].id+'</i></i><i class="fa fa-check-circle text-active text-info"></i></a><\/div><a href="#" class="jp-play-me m-r-sm pull-left" style="line-height: 20px;"><i style="display: none;">20<\/i><i class="icon-control-play text" onclick="poster(this)"><i style="display:none;">'+data[20].poster+'<\/i><\/i><i class="icon-control-pause text-active"><\/i><\/a><div class="clear text-ellipsis" style="line-height: 20px;"><i class="third_place">3<\/i><span style="color: #fff;">'+data[20].title+'—'+data[20].artist+'<\/span><\/div><\/li>');
    for (var i = 21; i < 27; i++) {
       $('.topSong').append('<li class="list-group-item show"><div class="pull-right m-l" style="line-height: 20px;"><a href="#" class="m-r-sm"><i class="icon-share-alt" onClick="content('+id[i].id+')"><\/i><\/a><a href="#" class="pull-right active" data-toggle="class"><i class="fa fa-heart-o text-active" onclick="like('+id[i].id+')"></i><i class="fa fa-heart text text-danger" onclick="cancel_like('+id[i].id+')"></i></a><a href="#" data-toggle="class"><i class="fa fa-plus-circle text" onclick="add(this)" data-toggle="modal" data-target="#myModal"><i style="display:none">'+id[i].id+'</i></i><i class="fa fa-check-circle text-active text-info"></i></a><\/div><a href="#" class="jp-play-me m-r-sm pull-left" style="line-height: 20px;"><i style="display: none;">'+i+'<\/i><i class="icon-control-play text" onclick="poster(this)"><i style="display:none;">'+data[i].poster+'<\/i><\/i><i class="icon-control-pause text-active"><\/i><\/a><div class="clear text-ellipsis" style="line-height: 20px;"><i>'+(i-17)+'<\/i><span style="color: #fff;">'+data[i].title+'—'+data[i].artist+'<\/span><\/div><\/li>');
    }
    
  }

  //每日推荐
  function create4(data, id)
  {
    // console.log(data);
    for (var i = 27; i < data.length; i++) {
       $('.daySong').append('<div class="col-xs-6 col-sm-3"><div class="item"><div class="pos-rlt"><div class="item-overlay opacity r r-2x bg-black"><div class="bottom padder m-b-sm"><a href="#" class="pull-right active" data-toggle="class"><i class="fa fa-heart-o text-active" onclick="like('+id[i].id+')"><\/i><i class="fa fa-heart text text-danger" onclick="cancel_like('+id[i].id+')"><\/i><\/a><a href="#" class="center" data-toggle="class"><i class="icon-share-alt" onClick="content('+id[i].id+')"><\/i><\/a><a href="#" data-toggle="class"><i class="fa fa-plus-circle text" onclick="add(this)" data-toggle="modal" data-target="#myModal"><i style="display:none">'+id[i].id+'</i><\/i><i class="fa fa-check-circle text-active text-info"><\/i><\/a><\/div><div class="center text-center m-t-n"><a href="#" class="jp-play-me m-r-sm text-center"><i style="display: none;">'+i+'<\/i><i class="icon-control-play i-2x text" onclick="poster(this)"><i style="display:none;">'+data[i].poster+'<\/i><\/i><i class="icon-control-pause i-2x text-active"><\/i><\/a><\/div><\/div><a href="#"><img src="'+data[i].poster+'" alt="" class="r r-2x img-full"><\/a><\/div><div class="padder-v"><a href="#" class="text-ellipsis">'+data[i].title+'<\/a><a href="#" class="text-ellipsis text-xs text-muted">'+data[i].artist+'<\/a><\/div><\/div><\/div>');
    }
    
  }

  function success(data)
  {
    // console.log(data);
    var myPlaylist = new window.parent.jPlayerPlaylist({
      jPlayer: "#jplayer_N",
      cssSelectorAncestor: "#jp_container_N"
    }, data, {
      playlistOptions: {
        enableRemoveControls: true,
        autoPlay: false
      },
      swfPath: "/js/jPlayer",
      supplied: "webmv, ogv, m4v, oga, mp3",
      smoothPlayBar: true,
      keyEnabled: true,
      audioFullScreen: false
    });
    
    $(document).on($.jPlayer.event.pause, myPlaylist.cssSelector.jPlayer,  function(){
      $('.musicbar').removeClass('animate');
      $('.jp-play-me').removeClass('active');
      $('.jp-play-me').parent('li').removeClass('active');
    });

    $(document).on($.jPlayer.event.play, myPlaylist.cssSelector.jPlayer,  function(){
      $('.musicbar').addClass('animate');
    });

    $(document).on('click', '.jp-play-me', function(e){
      e && e.preventDefault();
      // console.log(e.preventDefault());
      var $this = $(e.target);
       // console.log($this);
      if (!$this.is('a')) $this = $this.closest('a');

      $('.jp-play-me').not($this).removeClass('active');
      $('.jp-play-me').parent('li').not($this.parent('li')).removeClass('active');

      $this.toggleClass('active');
      $this.parent('li').toggleClass('active');
      // console.log($('#num').html());
      // 获取歌曲ID
      var i = $this.children('i').get(0).innerHTML;
      // console.log(i);
      if( !$this.hasClass('active') ){
        myPlaylist.pause();
      }else{
        myPlaylist.play(i);
      }
      
    });

  }
/***********我的音乐页面***********/
if (path == '\/index\/index\/listen') {
  $.ajax({
    type:'get',
    dataType:'json',
    url:'/index/index/mySong',
    success:mySong
  });
}

//我的歌曲的处理
function mySong(data)
{
  // console.log(data);
  create5(data[0], data[1]);
  success(data[0]);
}

function create5(data, id)
{
    for (var i = 0; i < data.length; i++) {
       $('.mySong').append('<li class="list-group-item"><div class="pull-right m-l"><a href="#" class="m-r-sm"><i class="icon-share-alt" onClick="content('+id[i].id+')"></i></a><a href="#"><i class="icon-close" onclick="del_song1(this,'+id[i].id+')"></i></a></div><a href="#" class="jp-play-me m-r-sm pull-left"><i style="display: none;" id="num">'+i+'</i><i class="icon-control-play text" onclick="poster(this)"><i style="display:none;">'+data[i].poster+'</i></i><i class="icon-control-pause text-active"></i></a><div class="clear text-ellipsis"><span>'+data[i].title+' - '+data[i].artist+'</span></div></li>');
    }
}

/*************我的音乐 结束***********/

/************关于我的歌曲模块中歌单的操作*************/
window.mylist = function (obj){
  list(obj);
}
function list(obj)
{
  // location.reload();
  // console.log(obj);
  // 查询歌单id，返回相应php文件进行查询
  var id = obj.children[0].innerHTML;
  var oSention = document.getElementById('list_songs');
  // console.log(oSention.style);
  // 显示歌单歌曲列表
  oSention.style.display = '';
  // alert(id);
  $.ajax({
    type:'get',
      dataType:'json',
      url:'/index/index/list_play',
      data:{id:id},
      async:true,
      success:list_listen
  });

  function list_listen(data)
  {
    console.log(data)
    success('');
    success(data[1]);
    $('#list_listen').html('');
    for (var i = data[0]; i < data[1].length; i++) {
      $('#list_listen').append('<li class="list-group-item clearfix"><a href="#" class="jp-play-me pull-right m-t-sm m-l text-md"><i class="icon-share-alt text" onClick="content('+data[2][i].id+')"></i></a><a href="#" class="jp-play-me pull-right m-t-sm m-l text-md"><i style="display: none;" id="num">'+i+'</i><i class="icon-control-play text" onclick="poster(this)"><i style="display:none;">'+data[1][i].poster+'</i></i><i class="icon-control-pause text-active"></i></a><a href="#" class="pull-left thumb-sm m-r"><img src="'+data[1][i].poster+'" alt="..."></a><a class="clear" href="#"><span class="block text-ellipsis">'+data[1][i].title+'</span><small class="text-muted">'+data[1][i].artist+'</small></a></li>');
    }
  }

}
/************关于我的歌曲模块中歌单的操作* end************/

/*****************这里是Mv*****************/

  function mv(data) {
    // console.log(data);
    //遍历首页mv
    create_mv(data);
  }

  function create_mv(data)
  {
    for (var i = 0; i < data.length; i++) {
       $('.mv_show').append('<div class="col-lg-2-4"><section class="panel panel-default"><li class="panel-body" style="width: 100%;"><a href="/index/index/videoPlay?mid='+data[i].mid+'"><img style="width: 100%;height: 100%;" src="'+data[i].poster+'" class="attachment-220x125 wp-post-image" alt="mv_1x2_10" /><strong>'+data[i].title+'</strong><strong>'+data[i].singer+'</strong><span><font>'+data[i].title+' &#8211; '+data[i].singer+'</font><font><i></i></font><font><i></i>1042425<em>2 /pic4-12-31</em></font></span></a></li></section></div>');
    }
  }
  // video

  


/*****************这里是Mv分界*****************/


/***************MV模块功能的处理*************/
//页面初始化遍历
if (path == '\/index\/index\/video') {
  //默认初始时展示内地列表
  var style = '内地';
  $.ajax({
    type:'get',
    dataType:'json',
    url:'/index/index/mv_start',
    data:{style:style},
    async:true,
    success:mv_list1
  });
}
  
function mv_list1(data)
{
  // console.log(data);
  //设置分页
  page(data[0]);
  //遍历mv列表
  create6(data[1]);
}

function mv_list2(data)
{
  // console.log(data);
  //遍历mv列表
  create6(data[1]);
}

function create6(data)
{
  $('.mv_list').html('');
  for (var i = 0; i < data.length; i++) {
    $('.mv_list').append('<div class="col-xs-6 col-sm-4 col-md-3"><div class="item"><div class="pos-rlt"><div class="item-overlay opacity r r-2x bg-black"><div class="center text-center m-t-n"><a href="/index/index/videoPlay?mid='+data[i].mid+'"><i class="fa fa-play-circle i-2x"></i></a></div></div><a><img src="'+data[i].poster+'" alt="" class="r r-2x img-full"></a></div><div class="padder-v"><a class="text-ellipsis">'+data[i].title+'</a><a class="text-ellipsis text-xs text-muted">'+data[i].singer+'</a></div></div></div>');
  }
}

function page(data1)
{
  var a = new Vue({
    el:"#app",
    data:{
      pageNo: 1,
      pages:data1,
    },
    methods:{
      msgListView(curPage){
        //根据当前页获取数据
        // alert(curPage);
        $.ajax({
          type:'get',
          dataType:'json',
          url:'/index/index/mv_start',
          data:{style:style,page:curPage},
          async:true,
          success:mv_list2
        });
      }
    }
  })
}

window.mv_style = function(obj){
  style = obj.innerHTML;
  // alert(style);
  // window.location.reload();
  $('.style_name').html(style);
  $('#fenye').html('<div id="app" style="text-align: center;margin-top: 10px;"><navigation :pages="pages" :current.sync="pageNo" @navpage="msgListView" id="page_clear" ></navigation></div>')
  $.ajax({
    type:'get',
    dataType:'json',
    url:'/index/index/mv_start',
    data:{style:style},
    async:true,
    success:mv_list1
  });
};


/***************MV模块功能的处理 end*************/

});

//歌曲封面
function poster(obj) 
{
  // console.log(obj);
  var pic = obj.children[0].innerHTML;
  // console.log(pic);
  // window.parent.document.getElementById('singerPic')
  var oImg = window.parent.document.getElementById('singerPic');
  oImg.src = pic;
}

//获取url中的参数
function getUrlParam(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
    var r = window.location.search.substr(1).match(reg);  //匹配目标参数
    if (r != null) return unescape(r[2]); return null; //返回参数值
}
//搜索点击事件
function confirm()
{
  //获取所要搜索的内容
  var word = $('#search_word').val();
  if (!word) {
    return false;
  }
  oIframe.src = '/index/index/search';

  //禁止form表单提交时页面刷新
  return false;
}

//收藏
function add(obj)
{
  //获取想要收藏的歌曲id
  var id = obj.children[0].innerHTML;
  // alert(id)
  //返回进行判断
  $.ajax({
    type:'get',
    dataType:'json',
    url:'/index/index/saveSong',
    data:{id:id},
    async:true,
    success:save_result
  });
}
/*****************这里是歌曲收藏处理*******************/
//处理返回状态,收藏歌曲
function save_result(data)
{
  // console.log(data);
  var state = JSON.parse(data).state;
  if (state == 1) {
    alert('您尚未登录,请返回登录...');
    window.parent.location.replace("/index/user/index");
  }
  if (state == 2) {
    alert('该歌单已包含此歌曲...');
  }
  if (state == 3) {
    alert('收藏成功');
  }
  if (state == 4) {
    alert('创建并收藏成功');
  }
  if (state == 5) {
    alert('该歌曲已经添加入喜欢...');
  }
  if (state == 6) {
    alert('添加成功');
  }
  if (state == 7) {
    alert('取消喜欢');
  }
}

//选择歌单进行收藏歌曲
function add_list(id)
{
  $.ajax({
    type:'get',
    dataType:'json',
    url:'/index/index/saveSong',
    data:{lid:id},
    async:true,
    success:save_result
  });
}

//新建歌单
function add_mylist(obj)
{
  $('#add_list').hide();
  $('#add_mylist').show();
}

//创建歌单
function confirm_add()
{
  var name = $('#listName').val();
  var content = $('#listContent').val();
  $.ajax({
    type:'get',
    dataType:'json',
    url:'/index/index/saveSong',
    data:{name:name,content:content},
    async:true,
    success:save_result
  });
}
/*****************这里是歌曲收藏处理 end*******************/

/*****************这里是歌曲喜欢处理*******************/
function like(id)
{
  // alert(id)
  $.ajax({
    type:'get',
    dataType:'json',
    url:'/index/index/likeSong',
    data:{id:id},
    async:true,
    success:save_result
  });
}

function cancel_like(id)
{
  // alert(id);
  $.ajax({
    type:'get',
    dataType:'json',
    url:'/index/index/cancel_likeSong',
    data:{id:id},
    async:true,
    success:save_result
  });
}
/*****************这里是歌曲喜欢处理 end*******************/

function del_song1(obj, id)
{
  $.ajax({
    type:'get',
    dataType:'json',
    url:'/index/index/del_song1',
    data:{id:id},
    async:true,
    success:save_result
  });
  $(obj.parentNode.parentNode.parentNode).remove();
}
