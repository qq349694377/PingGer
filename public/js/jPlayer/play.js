$(function (){
//获取当前页面文件信息
var path = window.location.pathname;
// console.log(path);
//我的音乐页面
if (path == '\/index\/index\/listen') {
  $.ajax({
    type:'get',
    dataType:'json',
    url:'/index/index/mySong',
    success:success
  });
}

//查询推首页荐
  if (path == '\/' || path == '\/index\/index\/index') {
  //当鼠标移入该歌单时加载该歌单
  //辅助值
      var a = true;
      var b = true;
      var c = true;
      $('#new').mouseover(function (){
        if (a) {
          $.ajax({
            type:'get',
            dataType:'json',
            url:'/index/index/song_recommendation',
            success:set1
          });
          a = !a;
          b = true;
          c = true;
        } 
      });
      $('#hot').mouseover(function (){
        if (b) {
          $.ajax({
            type:'get',
            dataType:'json',
            url:'/index/index/song_recommendation',
            success:set2
          });
          b = !b;
          a = true;
          c = true;
        } 
      });

      $('#top').mouseover(function (){
        if (b) {
          $.ajax({
            type:'get',
            dataType:'json',
            url:'/index/index/song_recommendation',
            success:set2
          });
          c = !c;
          a = true;
          b = true;
        } 
      });
  } 
});

function set1(data)
{
  success(data[0]);
}
function set2(data)
{
  success(data[1]);
}

//引入播放插件
function success(data)
  {
    console.log(data);
    var myPlaylist = new jPlayerPlaylist({
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