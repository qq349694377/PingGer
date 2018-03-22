// 关于左侧菜单切换的功能
var oIframe = document.getElementById('iframe');


function genres(obj) 
{
  oIframe.src = '/index/index/genres';
}

function events(obj) 
{
  oIframe.src = '/index/index/events';
}

function listen(obj) {
  oIframe.src = '/index/index/listen';
}

function videoMv(obj) 
{

  oIframe.src = '/index/index/video';
}

function index1(obj) 
{
  oIframe.src = '/index/index/index1';
}

function profile(obj) 
{
  oIframe.src = '/index/user/profile';
}