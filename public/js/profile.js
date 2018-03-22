function reset()
{
	$('.profile').show();
}

function resetPass()
{
	password = $('.password').val();
	pass = $('.pass').val();
	newpass = $('.newpass').val();
	email = $('.email').val();
	$.ajax({
      type:'get',
      dataType:'json',
      url:'/index/user/doReset',
      data:{password:password,pass:pass,newpass:newpass,email:email},
      async:true,
      success:result
    });
}

function result(data)
{
	var state = JSON.parse(data).state;
	if (state == 1) {
	alert('旧密码不能为空...');
	}
	if (state == 2) {
	alert('密码错误...');
	}
	if (state == 3) {
	alert('新密码长度不合格');
	}
	if (state == 4) {
	alert('密码不能全为数字');
	}
	if (state == 5) {
	alert('请确认密码...');
	}
	if (state == 6) {
	alert('密码确认失败');
	}
	if (state == 7) {
	alert('邮箱格式错误');
	}
	if (state == 8) {
	alert('修改成功');
	$('.profile').hide();
	}
	if (state == 9) {
	alert('修改失败');
	}
}
//修改资料
function setDetail()
{
	var pic = $('#images').attr('src');
	var sex = $('#sex  option:selected').val();
	var brithyear = $('#brithyear  option:selected').text();
	var brithmonth = $('#brithmonth  option:selected').text();
	var brithday = $('#brithday  option:selected').text();
	var province = $('#province  option:selected').text();
	var city = $('#city  option:selected').text();
	var phone = $('#phone').val();
	var autograph = $('#autograph').val();
	//进行处理
	$.ajax({
      type:'get',
      dataType:'json',
      url:'/index/user/setDetail',
      data:{pic:pic,sex:sex,brithyear:brithyear,brithmonth:brithmonth,brithday:brithday,province:province,city:city,phone:phone,autograph:autograph},
      async:true,
      success:result
    });
}

