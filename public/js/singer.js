$(document).ready(function(){
	var id = getUrlParam('mid');
	$.ajax({
		type:'get',
	    dataType:'json',
	    url:'/index/index/doSinger',
	    async:true,
	    data:{id:id},
	    success:set
	});
	function set(data)
	{
		success(data[0]);
		create(data[0], data[1]);
		console.log(data);
	}

	function create(data, id)
	{
    if (data != '') {
      for (var i = 0; i < data.length; i++) {
          $('.search_result').append('<ul class="list-group list-group-lg no-bg auto m-b-none m-t-n-xxs" id="list_listen"><li class="list-group-item clearfix"><a href="#" class="pull-right active m-t-sm m-l text-md" data-toggle="class"><i class="fa fa-heart-o text-active" onclick="like('+id[i].id+')"></i><i class="fa fa-heart text text-danger" onclick="cancel_like('+id[i].id+')"></i></a><a href="#" class="pull-right active m-t-sm m-l text-md" data-toggle="class"><i class="fa fa-plus-circle text-active" onclick="add(this)" data-toggle="modal" data-target="#myModal"><i style="display:none">'+id[i].id+'</i><\/i><i class="fa fa-check-circle text text-info"><\/i><\/a><a href="#" class="jp-play-me pull-right m-t-sm m-l text-md"><i style="display: none;" id="num">'+i+'</i><i class="icon-control-play text" onclick="poster(this)"><i style="display:none;">'+data[i].poster+'</i></i><i class="icon-control-pause text-active"></i></a><a href="#" class="pull-left thumb-sm m-r"><img src="'+data[i].poster+'" alt="..."></a><a class="clear" href="#"><span class="block text-ellipsis">'+data[i].title+'</span><small class="text-muted">'+data[i].artist+'</small></a></li></ul>');
      }
    }else{
        $('.search_result').append('<p class="text-center">该歌手还没有歌曲发布哦...</p>');
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
});

//获取url中的参数
function getUrlParam(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
    var r = window.location.search.substr(1).match(reg);  //匹配目标参数
    if (r != null) return unescape(r[2]); return null; //返回参数值
}

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
/*****************这里是歌曲收藏处理*******************/
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
//处理返回状态,收藏歌曲
function save_result(data)
{
  console.log(data);
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
  alert(id)
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
