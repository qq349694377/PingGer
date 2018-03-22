// 这里是单独处理歌单详细歌曲列表的js


  var id = document.getElementById('listId').innerHTML;
  // console.log(id);
  $.ajax({
    type:'get',
    dataType:'json',
    url:'/index/index/listSong_play',
    data:{id:id},
    async:true,
    success:set
  });

 
  function set(data)
  {
    // console.log(data);
    // 最新
    create(data[0], data[1]);
    success(data[0]);
  }

//便利音乐列表
  function create(data, id)
  {
    // console.log(data);
    for (var i = 0; i < data.length; i++) {
       $('#songList').append('<div class="col-xs-6 col-sm-4 col-md-3 col-lg-2"><div class="item"><div class="pos-rlt"><div class="item-overlay opacity r r-2x bg-black"><div class="bottom padder m-b-sm"><a href="#" class="pull-right active" data-toggle="class"><i class="fa fa-heart-o text-active" onclick="like('+id[i].id+')"></i><i class="fa fa-heart text text-danger" onclick="cancel_like('+id[i].id+')"></i></a><a href="#" class="pull-right padder" data-toggle="class"><i class="icon-share-alt" onClick="content('+id[i].id+')"><\/i><\/a><a href="#" data-toggle="class"><i class="fa fa-plus-circle text" onclick="add(this)" data-toggle="modal" data-target="#myModal"><i style="display:none">'+id[i].id+'</i></i><i class="fa fa-check-circle text-active text-info"></i></a></div><div class="center text-center m-t-n"><a href="#" class="jp-play-me m-r-sm text-center"><i style="display: none;">'+i+'</i><i class="icon-control-play i-2x text" onclick="poster(this)"><i style="display:none;"></i></i><i class="icon-control-pause i-2x text-active"></i></a></div></div><a><img src="'+data[i].poster+'" alt="" class="r r-2x img-full"></a></div><div class="padder-v"><a href="track-detail.html" data-bjax data-target="#bjax-target" data-el="#bjax-el" data-replace="true" class="text-ellipsis">'+data[i].title+'</a><a href="track-detail.html" data-bjax data-target="#bjax-target" data-el="#bjax-el" data-replace="true" class="text-ellipsis text-xs text-muted">'+data[i].artist+'</a></div></div></div>');
    }
    // success(data);
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

  


//歌曲封面
function poster(obj) 
{
  console.log(obj);
  var pic = obj.parentNode.parentNode.parentNode.parentNode.children[1].children[0].src;
  console.log(pic);
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
  if (state == 8) {
    alert('已收藏');
  }
  if (state == 9) {
    alert('取消收藏');
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

//收藏歌单
function save_list(id)
{
  $.ajax({
    type:'get',
    dataType:'json',
    url:'/index/index/save_list',
    data:{id:id},
    async:true,
    success:save_result
  });
}

// 取消收藏
function save_cancel(id)
{
  $.ajax({
    type:'get',
    dataType:'json',
    url:'/index/index/save_cancel',
    data:{id:id},
    async:true,
    success:save_result
  });
}

