function reply_content(obj)
{
	//获取要回复的用户
	var user = obj.children[0].innerHTML;
	var pic = obj.children[1].innerHTML;
	var id = obj.children[2].innerHTML;
	
	// console.log(user);
	var article = obj.parentNode.parentNode.parentNode.parentNode;
	// console.log(article);
	$('.reply_article').remove();
	$(article).append('<article class="comment-item media reply_article" id="comment-form"><a class="pull-left thumb-sm avatar"><img src="'+pic+'" alt="..." id="image"></a><section class="media-body"><form action="index.html" class="m-b-none"><div class="input-group"><input type="text" class="form-control" id="replyed" placeholder="@'+user+'"><span class="input-group-btn"><button class="btn btn-primary" type="button" onclick="replyed_content(this)"><i style="display:none;">'+user+'</i><i style="display:none;">'+id+'</i>POST</button></span></div></form></section></article>');
}

//提取回复内容
function replyed_content(obj)
{
	var cid = getUrlParam('mid');
	var user = obj.children[0].innerHTML;
	var id = obj.children[1].innerHTML;
	var content = $('#replyed').val();
	var pic = $('#image').attr('src');
	$.ajax({
      type:'get',
      dataType:'json',
      url:'/index/index/reply_content',
      data:{content:content,cid:cid,user:user,pic:pic,id:id},
      async:true,
      success:result
    });
}

//处理结果
function result(data)
{
	var obj = JSON.parse(data);
	// console.log(obj);
	if (obj == 1) {
		alert('您尚未登录,请返回登录...');
    	window.parent.location.replace("/index/user/index");
	}else{
		$('.reply_article').html('<a class="pull-left thumb-sm avatar"><img src="'+obj.photo+'" alt="..."></a><span class="arrow left"></span><section class="comment-body panel panel-default text-sm"><div class="panel-body"><span class="text-muted m-l-sm pull-right"><i class="fa fa-clock-o"></i>刚刚</span><a href="#">'+obj.user+'</a><label class="label bg-dark m-l-xs">@</label>'+obj.replyuser+'</div><div class="panel-body"><div>'+obj.content+'</div>');

	}
}

//获取url中的参数
function getUrlParam(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
    var r = window.location.search.substr(1).match(reg);  //匹配目标参数
    if (r != null) return unescape(r[2]); return null; //返回参数值
}

//一级评论
function reply_first()
{
	var content = $('#textarea').val();
	var cid = getUrlParam('mid');
	$.ajax({
      type:'get',
      dataType:'json',
      url:'/index/index/reply',
      data:{content:content,cid:cid},
      async:true,
      success:result2
    });
}

function result2(data)
{
	var obj = JSON.parse(data);
	// console.log(data);
	if (data == 1) {
		alert('您尚未登录,请返回登录...');
    	window.parent.location.replace("/index/user/index");
	}else{
		$('#reply_info').append('<section class="comment-list block"><article id="comment-id-1" class="comment-item"><a class="pull-left thumb-sm avatar"><img src="'+obj.photo+'" class="img-circle" alt="..."></a><span class="arrow left"></span><section class="comment-body panel panel-default"><header class="panel-heading bg-white"><a href="#">'+obj.user+'</a><span class="text-muted m-l-sm pull-right"><i class="fa fa-clock-o"></i>刚刚</span></header><div class="panel-body"><div>'+obj.content+'</div></section></article></section>');
		$('#textarea').val('');
	}
	
}