function message(obj, cid, id, lei)
{
	$.ajax({
		type:'get',
	    dataType:'json',
	    url:'/index/index/message',
	    async:true,
	    data:{id:id,lei:lei,cid:cid},
	    success:set_result
	});
	//去除显示标签
	$(obj).remove();
	//更新新消息数量
	var sum = $('.message_sum').html() - 1;
	if(sum == 0){
		$('.newMessage_sum').html('暂无新消息提醒');
		$('.message_count').remove();
	}else{
		$('.message_sum').html(sum);
		$('.message_count').html(sum);
	}
}

function set_result(data)
{
	var oIframe = document.getElementById('iframe');
	var obj = JSON.parse(data);
	if(obj.state == 1){
		if (obj.a == 1) {
			oIframe.src = '/index/index/blog?id='+obj.cid;
		}else{
			oIframe.src = '/index/index/videoPlay?mid='+obj.cid;
		}
		
	}
}

function click_all()
{
	$.ajax({
		type:'get',
	    dataType:'json',
	    url:'/index/index/click_all',
	    async:true
	});
	$('.message_count').remove();
	$('.newMessage').html('');
	$('.newMessage_sum').html('暂无新消息提醒');

}