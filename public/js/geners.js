$(document).ready(function(){
	var style = '全部';
	construct(style);
	function construct(style)
	{
		$.ajax({
			type:'get',
		    dataType:'json',
		    url:'/index/index/doGeners',
		    async:true,
		    data:{style:style},
		    success:set
		});
	}

	function construct1(style)
	{
		$.ajax({
			type:'get',
		    dataType:'json',
		    url:'/index/index/doGeners',
		    async:true,
		    data:{style:style},
		    success:set3
		});
	}

	function set(data)
	{
			success(data[1]);
			create(data[1], data[2], data[3]);
			page(data[0], data[2]);
			// console.log(data);
	}

	function set2(data)
	{
			success('');
			success(data[1]);
			create(data[1], data[2], data[3]);
	}

	function set3(data)
	{
			success(data[1]);
			create(data[1], data[2], data[3]);
			// console.log(data);
	}

	function create(data, style, id)
	{
		$('.search_result').html('');
		// alert(style);
		console.log(data);
		if (data != '') {

			for (var i = 0; i < data.length; i++) {
				$('.search_result').append('<div class="col-xs-6 col-sm-4 col-md-3 col-lg-2"><div class="item"><div class="pos-rlt"><div class="item-overlay opacity r r-2x bg-black"><div class="bottom padder m-b-sm"><a href="#" class="pull-right active" data-toggle="class"><i class="fa fa-heart-o text-active" onclick="like('+id[i].id+')"><\/i><i class="fa fa-heart text text-danger" onclick="cancel_like('+id[i].id+')"><\/i><\/a><a href="#" class="pull-right padder" data-toggle="class"><i class="icon-share-alt" onClick="content('+id[i].id+')"><\/i><\/a><a href="#" data-toggle="class"><i class="fa fa-plus-circle text" onclick="add(this)" data-toggle="modal" data-target="#myModal"><i style="display:none">'+id[i].id+'</i><\/i><i class="fa fa-check-circle text-active text-info"><\/i><\/a><\/div><div class="center text-center m-t-n"><a href="#" class="jp-play-me m-r-sm text-center"><i style="display: none;">'+i+'<\/i><i class="icon-control-play i-2x text" onclick="poster(this)"><i style="display:none;">'+data[i].poster+'<\/i><\/i><i class="icon-control-pause i-2x text-active"><\/i><\/a><\/div><\/div><a href="#"><img src="'+data[i].poster+'" alt="" class="r r-2x img-full"><\/a><\/div><div class="padder-v"><a href="#" class="text-ellipsis">'+data[i].title+'<\/a><a href="#" class="text-ellipsis text-xs text-muted">'+data[i].artist+'<\/a><\/div><\/div><\/div>')
			}
		} else {
			$('.search_result').append('<p class="text-center">该标签下暂未有音乐...</p>');
			$('#fenye').html('');
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


	//分页处理
	function page(data, style)
	{
	  var a = new Vue({
	    el:"#app",
	    data:{
	      pageNo: 1,
	      pages:data,
	    },
	    methods:{
	      msgListView(curPage){
	        //根据当前页获取数据
	        $.ajax({
	          type:'get',
	          dataType:'json',
	          url:'/index/index/doGeners',
	          data:{style:style,page:curPage},
	          async:true,
	          success:set2
	        });
	      }
	    }
	  })
	}

	// 点击事件处理
	window.style_select = function(obj){
		select(obj);
	};

	function select(obj)
	{
		var style = obj.innerHTML;
		$('.style_title').html(style);
		$('#fenye').html('<div id="app" style="text-align: center;margin-top: 10px;"><navigation :pages="pages" :current.sync="pageNo" @navpage="msgListView" id="page_clear" ></navigation></div>')
		construct1(style);
		construct(style);
	}

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