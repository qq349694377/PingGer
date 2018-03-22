-- phpMyAdmin SQL Dump
-- version 4.7.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: 2018-03-08 17:57:24
-- 服务器版本： 5.5.56-log
-- PHP Version: 7.1.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `music`
--

-- --------------------------------------------------------

--
-- 表的结构 `m_identity`
--

CREATE TABLE `m_identity` (
  `iid` int(11) NOT NULL,
  `title` char(30) NOT NULL COMMENT '身份名称',
  `content` varchar(100) DEFAULT '管理员懒死，没写' COMMENT '描述',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `status` int(2) DEFAULT '1' COMMENT '状态，1生效，2失效'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `m_identity`
--

INSERT INTO `m_identity` (`iid`, `title`, `content`, `create_time`, `status`) VALUES
(1, '超级管理员', '所有权限', 1519424732, 1),
(2, '李云龙团长', '朋友圈管理，轮播图管理', 1519424717, 1),
(3, '我的兄弟叫顺溜', '音乐管理，歌单管理', 1519424230, 1),
(4, '绺子贼九', 'mv管理，会员管理', 1519425937, 1),
(5, '村长', '通知管理，系统管理', 1519425954, 1),
(6, '村支书', '管理员懒死，没写', 23456789, 1),
(7, '村主任', '管理员懒死，没写', 234567654, 2),
(8, '街道大妈', '管理员懒死，没写', 123, 1),
(9, '12313123', '12312312', 1517992404, 1),
(10, '12313123', '12312312', 1517992551, 1),
(11, '民兵葛二蛋', '民兵葛二蛋', 1517992719, 1);

-- --------------------------------------------------------

--
-- 表的结构 `m_lbt`
--

CREATE TABLE `m_lbt` (
  `id` int(11) NOT NULL,
  `title` char(50) CHARACTER SET utf8 DEFAULT NULL COMMENT '标题名称',
  `img` char(100) CHARACTER SET utf8 NOT NULL COMMENT '图片',
  `create_time` int(11) NOT NULL COMMENT '添加时的时间',
  `mid` int(2) NOT NULL COMMENT '轮播图对应mv的id',
  `status` int(2) NOT NULL COMMENT '1显示，2隐藏'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- 转存表中的数据 `m_lbt`
--

INSERT INTO `m_lbt` (`id`, `title`, `img`, `create_time`, `mid`, `status`) VALUES
(1, 'Wake Me Up', 'http://p3ijb4nfy.bkt.clouddn.com/b2.jpg', 1519393400, 14, 1),
(2, 'Waiting For Love(Director\'s Cut)', 'http://p3ijb4nfy.bkt.clouddn.com/b1.jpg', 1519393203, 13, 1),
(3, '朴树十年一歌  平凡之路', 'http://p3ijb4nfy.bkt.clouddn.com/b3.jpg', 1519393223, 21, 1),
(4, '我的天空--南征北战', 'http://p3ijb4nfy.bkt.clouddn.com/b4.jpg', 1517883430, 20, 1),
(6, 'Long Way Home', 'http://p3ijb4nfy.bkt.clouddn.com/b5.jpg', 1519393403, 16, 1);

-- --------------------------------------------------------

--
-- 表的结构 `m_list`
--

CREATE TABLE `m_list` (
  `id` int(11) NOT NULL,
  `list` char(30) NOT NULL COMMENT '歌单名称',
  `content` varchar(200) NOT NULL DEFAULT '作者比较懒，什么都没说' COMMENT '歌单简介',
  `author` char(30) NOT NULL COMMENT '歌单创建者',
  `poster` varchar(255) DEFAULT NULL COMMENT '歌单封面，不设置默认为第一首歌',
  `childrenid` varchar(500) DEFAULT ',' COMMENT '歌曲所包含的歌曲id列表，以字符串的格式加逗号拼接',
  `sum` int(11) DEFAULT '0' COMMENT '歌曲总数',
  `create_time` int(16) NOT NULL COMMENT '创建时间',
  `status` int(2) DEFAULT '1' COMMENT '1显示 2屏蔽',
  `collect` int(11) DEFAULT '0' COMMENT '收藏量'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `m_list`
--

INSERT INTO `m_list` (`id`, `list`, `content`, `author`, `poster`, `childrenid`, `sum`, `create_time`, `status`, `collect`) VALUES
(7, '睡前小调', '作者比较懒，什么都没说', '小西瓜', 'http://p3ijb4nfy.bkt.clouddn.com/as7.jpg', ',2,5,7,8,9,10,11,', 7, 1, 1, 0),
(2, '跑步专用', '作者比较懒，什么都没说', '小迷糊', 'http://p3ijb4nfy.bkt.clouddn.com/as1.jpg', ',3,5,13,14,16,18,', 6, 3, 1, 0),
(3, '电音', '作者比较懒，什么都没说', '哈哈儿', 'http://p3ijb4nfy.bkt.clouddn.com/as2.jpg', ',2,20,25,26,23,27,', 6, 8, 1, 0),
(4, '生命不息，抖腿不止', '作者比较懒，什么都没说', '小陀螺', 'http://p3ijb4nfy.bkt.clouddn.com/as3.jpg', ',3,4,9,30,33,34,35,', 7, 4, 1, 2),
(5, '一种态度', '作者比较懒，什么都没说', '嘻嘻', 'http://p3ijb4nfy.bkt.clouddn.com/as4.jpg', ',5,11,22,25,13,', 5, 6, 1, 0),
(6, 'biubiu', '作者比较懒，什么都没说', 'fvc', 'http://p3ijb4nfy.bkt.clouddn.com/as3.jpg', ',3,4,9,30,33,34,35,', 7, 3, 1, 0),
(8, '哈哈', '作者比较懒，什么都没说', 'admin', 'http://p3ijb4nfy.bkt.clouddn.com/as4.jpg', ',3,4,9,30,33,34,35,15,', 8, 1519482916, 1, 0),
(12, '舒缓', '娃哈哈呀娃哈哈', 'admin', 'http://p3ijb4nfy.bkt.clouddn.com/s10.jpg', ',3,6,', 2, 1519881032, 1, 0),
(10, 'PingGer~', '那年', 'admin', 'http://p3ijb4nfy.bkt.clouddn.com/as5.jpg', ',3,4,9,30,33,34,35,', 7, 1519638572, 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `m_mv`
--

CREATE TABLE `m_mv` (
  `mid` int(11) NOT NULL,
  `title` char(255) NOT NULL COMMENT 'mv名字',
  `singer` char(30) NOT NULL COMMENT '歌手',
  `content` varchar(300) NOT NULL DEFAULT '作者比较懒，什么都没说' COMMENT 'mv详情',
  `webmv` varchar(255) NOT NULL COMMENT '视频格式webm',
  `m4v` varchar(255) DEFAULT NULL COMMENT '视频格式m4v(不用)',
  `ogv` varchar(255) DEFAULT NULL COMMENT '视频格式ogv(不用)',
  `create_time` int(16) NOT NULL COMMENT '上传时间',
  `hits` int(11) DEFAULT '0' COMMENT '浏览量',
  `poster` varchar(255) NOT NULL COMMENT '封面',
  `top` int(1) DEFAULT '0' COMMENT '0不置顶，1置顶',
  `toptime` int(16) DEFAULT '0' COMMENT '置顶时间',
  `replycount` int(11) DEFAULT '0' COMMENT '回复数',
  `status` int(2) DEFAULT '1' COMMENT '上架1 下架2'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `m_mv`
--

INSERT INTO `m_mv` (`mid`, `title`, `singer`, `content`, `webmv`, `m4v`, `ogv`, `create_time`, `hits`, `poster`, `top`, `toptime`, `replycount`, `status`) VALUES
(12, 'Sing Me to Sleep', 'Alan Walker', '作者比较懒，什么都没说', 'http://p3ijb4nfy.bkt.clouddn.com/Sing Me to Sleep.webm', NULL, NULL, 1, 0, 'http://p3ijb4nfy.bkt.clouddn.com/mv1.jpg', 1, 1519885658, 0, 1),
(13, 'Waiting For Love(Director\'s Cut)', 'Avicii', '作者比较懒，什么都没说', 'http://p3ijb4nfy.bkt.clouddn.com/Waiting For Love(Director\'s Cut).webm', NULL, NULL, 2, 0, 'http://p3ijb4nfy.bkt.clouddn.com/mv2.jpg', 0, 0, 0, 1),
(14, 'Wake Me Up', 'Avicii', '作者比较懒，什么都没说', 'http://p3ijb4nfy.bkt.clouddn.com/Wake Me Up.webm', NULL, NULL, 3, 0, 'http://p3ijb4nfy.bkt.clouddn.com/mv3.jpg', 0, 0, 0, 1),
(15, 'Dream It Possible', 'Delacey', '作者比较懒，什么都没说', 'http://p3ijb4nfy.bkt.clouddn.com/Dream It Possible.webm', NULL, NULL, 4, 0, 'http://p3ijb4nfy.bkt.clouddn.com/mv4.jpg', 0, 0, 0, 1),
(16, 'Long Way Home', 'Gareth Emery', '作者比较懒，什么都没说', 'http://p3ijb4nfy.bkt.clouddn.com/Long Way Home.webm', NULL, NULL, 5, 0, 'http://p3ijb4nfy.bkt.clouddn.com/mv5.jpg', 0, 0, 0, 1),
(17, 'Alone', 'Marshmello', '作者比较懒，什么都没说', 'http://p3ijb4nfy.bkt.clouddn.com/Alone.webm', NULL, NULL, 0, 0, 'http://p3ijb4nfy.bkt.clouddn.com/mv6.jpg', 0, 0, 0, 1),
(18, 'Something Just Like This (歌词版)', 'The Chainsmokers', '作者比较懒，什么都没说', 'http://p3ijb4nfy.bkt.clouddn.com/Something Just Like This (歌词版).webm', NULL, NULL, 0, 0, 'http://p3ijb4nfy.bkt.clouddn.com/mv7.jpg', 0, 0, 0, 1),
(19, 'Beautiful Now', 'Zedd', '作者比较懒，什么都没说', 'http://p3ijb4nfy.bkt.clouddn.com/Beautiful Now.webm', NULL, NULL, 0, 0, 'http://p3ijb4nfy.bkt.clouddn.com/mv8.jpg', 0, 0, 0, 1),
(20, '我的天空', '南征北战', '作者比较懒，什么都没说', 'http://p3ijb4nfy.bkt.clouddn.com/mysky.webm', NULL, NULL, 0, 0, 'http://p3ijb4nfy.bkt.clouddn.com/mv9.jpg', 0, 0, 0, 1),
(21, '平凡之路', '朴树', '作者比较懒，什么都没说', 'http://p3ijb4nfy.bkt.clouddn.com/平凡之路.webm', NULL, NULL, 0, 0, 'http://p3ijb4nfy.bkt.clouddn.com/mv10.jpg', 0, 0, 0, 1);

-- --------------------------------------------------------

--
-- 表的结构 `m_mvreply`
--

CREATE TABLE `m_mvreply` (
  `id` int(11) NOT NULL,
  `content` mediumtext NOT NULL COMMENT '评论内容',
  `userid` int(11) DEFAULT '0' COMMENT '评论人id',
  `replyuser` char(50) DEFAULT NULL COMMENT '被回复人',
  `user` char(50) NOT NULL COMMENT '评论人',
  `photo` varchar(200) DEFAULT '/images/a1.png' COMMENT '评论人头像',
  `pid` int(11) DEFAULT '-1' COMMENT '评论父级的id',
  `cid` int(11) NOT NULL COMMENT '评论文章的id',
  `create_time` int(16) NOT NULL COMMENT '评论时间',
  `read` int(2) DEFAULT '0' COMMENT '是否读取0未1是',
  `status` int(2) NOT NULL DEFAULT '1' COMMENT '1显示，2隐藏'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `m_mvreply`
--

INSERT INTO `m_mvreply` (`id`, `content`, `userid`, `replyuser`, `user`, `photo`, `pid`, `cid`, `create_time`, `read`, `status`) VALUES
(43, '这都是什么玩意', 0, NULL, 'admin1', '/images/zbs.jpg', -1, 16, 1519870482, 0, 1),
(44, 'php是最好的语言', 0, NULL, 'admin1', '/images/zbs.jpg', -1, 16, 1519870502, 0, 1),
(45, '老铁，没毛病！', 0, 'admin1', 'admin222', '/uploads/20180301/e10f181a986944697cef9861e237c7f6.JPG', 44, 16, 1519870544, 1, 1),
(46, '同志，请保护好你的发际线。', 0, 'admin222', 'admin1', '/images/zbs.jpg', 44, 16, 1519870746, 1, 1),
(47, '0.0', 0, 'admin1', 'admin222', '/uploads/20180301/e10f181a986944697cef9861e237c7f6.JPG', 44, 16, 1519870770, 0, 1),
(48, '不错', 0, NULL, 'admin222', '/uploads/20180301/e10f181a986944697cef9861e237c7f6.JPG', -1, 14, 1519871215, 0, 1),
(49, '听见某个名字，想起某些事情，这个城市安静的让人心颤。', 0, NULL, 'admin', '/uploads/20180226/8258ba7633941dfdf840beafd11f4203.jpg', -1, 15, 1519872097, 0, 1),
(50, 'GAY杨', 0, 'admin', 'admin222', '/uploads/20180301/e10f181a986944697cef9861e237c7f6.JPG', 49, 15, 1519872134, 1, 1),
(51, '逗比杨', 0, 'admin', 'admin222', '/uploads/20180301/e10f181a986944697cef9861e237c7f6.JPG', 49, 15, 1519872828, 1, 1),
(52, '作者比较懒，什么都没说', 0, 'admin', 'admin222', '/uploads/20180301/e10f181a986944697cef9861e237c7f6.JPG', 49, 15, 1519872876, 1, 1),
(53, '阔以的', 0, 'admin222', 'admin', '/uploads/20180226/8258ba7633941dfdf840beafd11f4203.jpg', 48, 14, 1519881205, 0, 1),
(54, '很酷呀', 0, NULL, 'qq+Traveller、', '/images/a0.png', -1, 14, 1519981368, 0, 1),
(55, '‘’', 0, NULL, 'qq+Traveller、', '/images/a0.png', -1, 21, 1519981481, 0, 1);

-- --------------------------------------------------------

--
-- 表的结构 `m_mvstyle`
--

CREATE TABLE `m_mvstyle` (
  `id` int(11) NOT NULL,
  `stylename` char(255) NOT NULL COMMENT 'mv风格名称',
  `mvid` varchar(500) DEFAULT ',' COMMENT '该风格下的mv的id',
  `create_time` int(16) NOT NULL COMMENT '创建时间',
  `status` int(2) DEFAULT '1' COMMENT '1  显示 ， 2屏蔽'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `m_mvstyle`
--

INSERT INTO `m_mvstyle` (`id`, `stylename`, `mvid`, `create_time`, `status`) VALUES
(1, '内地', ',12,13,14,15,20,21,', 1, 1),
(2, '港台', ',12,13,14,15,', 3, 1),
(3, '欧美', ',16,17,18,19,', 2, 1),
(4, '日韩', ',19,20,21,', 4, 1);

-- --------------------------------------------------------

--
-- 表的结构 `m_one`
--

CREATE TABLE `m_one` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL COMMENT '用户id',
  `iid` int(11) NOT NULL COMMENT '身份id',
  `create_time` int(11) NOT NULL COMMENT '添加时间',
  `status` int(2) NOT NULL COMMENT '状态，1生效，2失效'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- 转存表中的数据 `m_one`
--

INSERT INTO `m_one` (`id`, `uid`, `iid`, `create_time`, `status`) VALUES
(1, 2, 1, 123123123, 1),
(2, 2, 2, 12312312, 1),
(35, 9, 4, 1517968943, 1),
(34, 8, 9, 1517968582, 1),
(5, 2, 9, 1517936611, 1),
(33, 3, 3, 1517968536, 1),
(36, 3, 2, 12312312, 1),
(38, 11, 1, 1519423433, 1);

-- --------------------------------------------------------

--
-- 表的结构 `m_pyq`
--

CREATE TABLE `m_pyq` (
  `id` int(11) NOT NULL,
  `title` char(80) NOT NULL COMMENT '标题',
  `content` mediumtext NOT NULL COMMENT '内容   ',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `img` char(50) NOT NULL COMMENT '图片',
  `reply` int(5) DEFAULT '0' COMMENT '回复数',
  `fabulous` int(5) DEFAULT '0' COMMENT '点赞数',
  `uid` char(50) DEFAULT NULL COMMENT '用户名',
  `bgmid` int(11) DEFAULT NULL COMMENT '分享的音乐id',
  `status` int(2) DEFAULT '1' COMMENT '状态,1显示，2回收站'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='朋友圈表';

--
-- 转存表中的数据 `m_pyq`
--

INSERT INTO `m_pyq` (`id`, `title`, `content`, `create_time`, `img`, `reply`, `fabulous`, `uid`, `bgmid`, `status`) VALUES
(25, '爱的故事(下)', '哈哈，太好听了', 1519868886, 'http://p3ijb4nfy.bkt.clouddn.com/s12.jpg', 0, 0, 'admin', 1, 1),
(26, '带你去旅行', 'asdfgbn ', 1519869921, 'http://p3ijb4nfy.bkt.clouddn.com/s10.jpg', 0, 0, 'qq+13.10.6', 3, 1),
(27, '心碎(粤)', '宏平衡之所以将地球分化为不同信念的规则世界，是为了让地球人类根据自己的信念选择自己的道路，最终拥有相同信念的人会处于同一个规则的世界之中', 1519871754, 'http://p3ijb4nfy.bkt.clouddn.com/s5.jpg', 0, 0, 'admin', 10, 1),
(28, '키 작은 꼬마 이야기', 'I don\'t know if we each have a destiny, or if we\'re all just floating around accidental—like on a breeze. ', 1519871832, 'http://p3ijb4nfy.bkt.clouddn.com/s8.jpg', 0, 0, 'admin', 5, 1),
(29, '心碎(粤)', 'ThinkPHP是一个免费开源的，快速、简单的面向对象的轻量级PHP开发框架，是为了敏捷WEB应用开发和简\n化企业应用开发而诞生的。', 1519871860, 'http://p3ijb4nfy.bkt.clouddn.com/s5.jpg', 0, 0, 'admin222', 10, 1),
(30, ' 나란 놈은 답은 너다', 'ThinkPHP从诞生以来一直秉承简洁实用的设计原则，在保持出色的性能和至简的\n代码的同时，也注重易用性。遵循 Apache2 开源许可协议发布，意味着你可以免费使用ThinkPHP，甚至允\n许把你基于ThinkPHP开发的应用开源或商业产品发布/销售。', 1519871869, 'http://p3ijb4nfy.bkt.clouddn.com/s30.jpg', 0, 0, 'admin222', 36, 1),
(31, '像风一样', 'ThinkPHP5.0版本是一个颠覆和重构版本，采用全新的架构思想，引入了更多的PHP新特性，优化了核\n心，减少了依赖，实现了真正的惰性加载，支持composer，并针对API开发做了大量的优化，包括路由、\n日志、异常、模型、数据库、模板引擎和验证等模块都已经重构，不适合原有3.2项目的升级，请慎重考虑\n商业项目升级，但绝对是新项目的首选（无论是WEB还是API开发）。', 1519871898, 'http://p3ijb4nfy.bkt.clouddn.com/s21.jpg', 0, 0, 'admin222', 23, 1),
(32, 'Nevada', '有的人，一段时间没见，会和你说，过得好不好，是问句。有的人，一段时间没见，会说，又瘦了，是肯定句。他知道，不论过多久，再看到你，总没有记忆中那么好。他知道，不论有谁陪着你，总也会过得很辛苦，我身边，没有这个人。\n', 1519871917, 'http://p3ijb4nfy.bkt.clouddn.com/s35.jpg', 0, 0, 'admin', 30, 1),
(33, '如果有来生', 'sound great!!!!', 1519881003, 'http://p3ijb4nfy.bkt.clouddn.com/s6.jpg', 0, 0, 'admin', 11, 1),
(34, '爱的故事(下)', '刚刚', 1519981411, 'http://p3ijb4nfy.bkt.clouddn.com/s12.jpg', 0, 0, 'qq+Traveller、', 1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `m_pyqreply`
--

CREATE TABLE `m_pyqreply` (
  `id` int(11) NOT NULL,
  `content` mediumtext NOT NULL COMMENT '评论内容',
  `userid` int(11) DEFAULT NULL COMMENT '评论人id',
  `replyuser` char(50) DEFAULT NULL COMMENT '被回复人',
  `user` char(50) NOT NULL COMMENT '评论人',
  `photo` varchar(200) DEFAULT '/images/a1.png' COMMENT '评论人头像',
  `pid` int(11) DEFAULT '-1' COMMENT '评论父级的id,父级是mv则为-1，否则为回复的id',
  `cid` int(11) NOT NULL COMMENT '评论文章的id',
  `create_time` int(16) NOT NULL COMMENT '评论时间',
  `read` int(2) DEFAULT '0' COMMENT '是否读取0未，1读',
  `status` int(2) DEFAULT '1' COMMENT '1显示，2隐藏'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `m_pyqreply`
--

INSERT INTO `m_pyqreply` (`id`, `content`, `userid`, `replyuser`, `user`, `photo`, `pid`, `cid`, `create_time`, `read`, `status`) VALUES
(26, '你好', NULL, NULL, 'admin', '/uploads/20180226/8258ba7633941dfdf840beafd11f4203.jpg', -1, 31, 1519872885, 0, 1),
(27, '作者比较懒，什么都没说', NULL, 'admin', 'admin222', '/uploads/20180301/e10f181a986944697cef9861e237c7f6.JPG', 26, 31, 1519872890, 1, 1),
(28, '作者比较懒', NULL, 'admin', 'admin222', '/uploads/20180301/e10f181a986944697cef9861e237c7f6.JPG', 26, 31, 1519872900, 1, 1),
(29, '55555', NULL, NULL, 'qq+stan', '/images/a0.png', -1, 26, 1519884587, 0, 1),
(30, 'hhh ', NULL, NULL, 'admin', '/uploads/20180226/8258ba7633941dfdf840beafd11f4203.jpg', -1, 33, 1519885156, 0, 1);

-- --------------------------------------------------------

--
-- 表的结构 `m_quanxian`
--

CREATE TABLE `m_quanxian` (
  `jid` int(11) NOT NULL,
  `title` char(50) NOT NULL COMMENT '权限名称'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `m_quanxian`
--

INSERT INTO `m_quanxian` (`jid`, `title`) VALUES
(1, '朋友圈管理'),
(2, '轮播图管理'),
(3, '音乐管理'),
(4, '歌单管理'),
(5, 'MV管理'),
(6, '会员管理'),
(7, '通知管理'),
(8, '管理员管理'),
(9, '系统管理');

-- --------------------------------------------------------

--
-- 表的结构 `m_singer`
--

CREATE TABLE `m_singer` (
  `id` int(11) NOT NULL,
  `singer` char(50) NOT NULL COMMENT '歌手',
  `pic` varchar(500) NOT NULL COMMENT '歌手头像',
  `songid` varchar(500) DEFAULT ',' COMMENT '歌曲id',
  `introduce` mediumtext NOT NULL COMMENT '歌手介绍',
  `create_time` int(16) NOT NULL,
  `toptime` int(16) DEFAULT '0' COMMENT '置顶时间',
  `top` int(2) DEFAULT '2' COMMENT '1置顶，2未置顶',
  `status` int(2) DEFAULT '1' COMMENT '1上架，下架'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `m_singer`
--

INSERT INTO `m_singer` (`id`, `singer`, `pic`, `songid`, `introduce`, `create_time`, `toptime`, `top`, `status`) VALUES
(1, '张国荣', 'http://p3ijb4nfy.bkt.clouddn.com/zgr.png', ',1,2,3,', '张国荣（1956年9月12日-2003年4月1日），生于香港，男歌手、演员、音乐人；影视歌多栖发展的代表之一。1977年正式出道。1983年以《风继续吹》成名。1984年演唱的《Monica》是香港歌坛第一支同获十大中文金曲、十大劲歌金曲的舞曲。', 1519412902, 0, 1, 1),
(2, '刘德华', 'http://p3ijb4nfy.bkt.clouddn.com/ldh.png', '4,5,6,7,8', '1985年发行首张个人专辑《只知道此刻爱你》。1990年凭借专辑《可不可以》在歌坛获得关注 。1994年获得十大劲歌金曲最受欢迎男歌星奖。1995年在央视春晚上演唱歌曲《忘情水》  。', 1519412342, 0, 1, 1),
(3, '张学友', 'http://p3ijb4nfy.bkt.clouddn.com/zxy.png', '9,10,11,12,13', '张学友（Jacky Cheung），1961年7月10日出生于香港，中国香港男歌手、演员，毕业于香港崇文英文书院。1984年因获得首届香港十八区业余歌唱大赛冠军而出道。1985年发行个人首张专辑《Smile》。1993年发行的专辑《吻别》打破华语唱片在台湾的销量纪录。', 1513123123, 0, 1, 1),
(4, '容祖儿', 'http://p3ijb4nfy.bkt.clouddn.com/rze.png', ',15,16,17,18,19,', '容祖儿（Joey Yung），1980年6月16日生于中国香港，中国香港女歌手。香港英皇集团旗下艺人。\r\n1995年，参加卡拉OK大赛获得冠军。1999年，发行首张EP《未知》销量超过13万张。2001年，首次在红馆举行个人售票会。', 1531237123, 0, 1, 1),
(5, '张靓颖', 'http://p3ijb4nfy.bkt.clouddn.com/zly.png', ',20,21,22,23,24,25', '张靓颖（Jane Zhang），1984年10月11日出生于四川成都，中国流行女歌手、词曲创作人，现任全国青联委员。2006年，发行首张专辑《THE ONE》，年终销量超过100万张，并凭此专辑获得中国金唱片奖通俗类女演员奖。2007年，发行专辑《UPDATE*JANE》，并在美国洛杉矶举行售票演唱会。', 1531263123, 0, 2, 1),
(6, '陈奕迅', 'http://p3ijb4nfy.bkt.clouddn.com/cyx.png', ',25,26,27,28,29,', '陈奕迅（Eason Chan），1974年7月27日出生于香港，中国香港流行乐男歌手、演员，毕业于英国金斯顿大学。\r\n1995年因获得第14届新秀歌唱大赛冠军而正式出道。1996年发行个人首张专辑《陈奕迅》。1997年主演个人首部电影《旺角大家姐》。1998年凭借歌曲《天下无双》在乐坛获得关注。2000年发行的歌曲《K歌之王》奠定其在歌坛的地位。', 1531111111, 0, 2, 1);

-- --------------------------------------------------------

--
-- 表的结构 `m_song`
--

CREATE TABLE `m_song` (
  `id` int(11) NOT NULL,
  `title` char(50) NOT NULL COMMENT '歌名',
  `mp3` varchar(255) DEFAULT NULL COMMENT '音乐链接',
  `artist` char(30) NOT NULL DEFAULT '' COMMENT '歌手',
  `poster` varchar(255) NOT NULL COMMENT '歌曲封面',
  `top` int(1) DEFAULT '2' COMMENT '1置顶，0未置顶',
  `toptime` int(16) DEFAULT '0' COMMENT '置顶时间',
  `hits` int(11) DEFAULT '0' COMMENT '浏览次数',
  `create_time` int(16) NOT NULL COMMENT '上传时间',
  `word` varchar(500) DEFAULT NULL COMMENT '歌词',
  `charge` int(2) NOT NULL DEFAULT '2' COMMENT '1,收费 2，免费',
  `money` int(5) NOT NULL DEFAULT '0' COMMENT '价格',
  `status` int(2) NOT NULL DEFAULT '1' COMMENT '1，上架，2下架'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `m_song`
--

INSERT INTO `m_song` (`id`, `title`, `mp3`, `artist`, `poster`, `top`, `toptime`, `hits`, `create_time`, `word`, `charge`, `money`, `status`) VALUES
(1, '爱的故事(下)', 'http://p3ijb4nfy.bkt.clouddn.com/孙耀威 - 爱的故事(下)-但愿他珍惜你.mp3', '孙耀威', 'http://p3ijb4nfy.bkt.clouddn.com/s12.jpg', 2, 1519838190, 0, 1519838885, NULL, 2, 0, 1),
(2, '一万次悲伤', 'http://p3ijb4nfy.bkt.clouddn.com/逃跑计划 - 一万次悲伤.mp3', '逃跑计划', 'http://p3ijb4nfy.bkt.clouddn.com/s11.jpg', 2, 1519838190, 0, 1519838885, NULL, 2, 0, 1),
(3, '带你去旅行', 'http://p3ijb4nfy.bkt.clouddn.com/校长 - 带你去旅行.mp3', '校长', 'http://p3ijb4nfy.bkt.clouddn.com/s10.jpg', 2, 1519838190, 0, 1519838885, NULL, 2, 0, 1),
(4, '像风一样', 'http://p3ijb4nfy.bkt.clouddn.com/薛之谦 - 像风一样.mp3', '薛之谦', 'http://p3ijb4nfy.bkt.clouddn.com/s9.jpg', 1, 1519838190, 2, 1519838885, NULL, 2, 0, 1),
(5, '키 작은 꼬마 이야기', 'http://p3ijb4nfy.bkt.clouddn.com/哈哈 - 키 작은 꼬마 이야기.mp3', '哈哈', 'http://p3ijb4nfy.bkt.clouddn.com/s8.jpg', 2, 1519838190, 100, 1519838885, NULL, 2, 0, 1),
(6, '世界が終るまでは', 'http://p3ijb4nfy.bkt.clouddn.com/WANDS - 世界が終るまでは.mp3', 'WANDS', 'http://p3ijb4nfy.bkt.clouddn.com/s1.jpg', 2, 1519802765, 3, 1519838885, NULL, 2, 0, 1),
(7, ' 写给遇见', 'http://p3ijb4nfy.bkt.clouddn.com/Fi9江澈 - 写给遇见.mp3', 'Fi9江澈', 'http://p3ijb4nfy.bkt.clouddn.com/s2.jpg', 1, 1517997007, 0, 1519838885, NULL, 2, 0, 1),
(8, ' 君が好きだと叫びたい', 'http://p3ijb4nfy.bkt.clouddn.com/BAAD - 君が好きだと叫びたい.mp3', 'BAAD', 'http://p3ijb4nfy.bkt.clouddn.com/s3.jpg', 2, 1518768693, 4, 1519838885, NULL, 2, 0, 1),
(9, '亡命之徒', 'http://p3ijb4nfy.bkt.clouddn.com/纵贯线 - 亡命之徒.mp3', '纵贯线', 'http://p3ijb4nfy.bkt.clouddn.com/s4.jpg', 2, 1519802765, 0, 1519838885, NULL, 2, 0, 1),
(10, '心碎(粤)', 'http://p3ijb4nfy.bkt.clouddn.com/易欣 - 心碎(粤).mp3', '易欣', 'http://p3ijb4nfy.bkt.clouddn.com/s5.jpg', 2, 1519802765, 6, 1519838888, NULL, 2, 0, 1),
(11, '如果有来生', 'http://p3ijb4nfy.bkt.clouddn.com/谭维维 - 如果有来生.mp3', '谭维维', 'http://p3ijb4nfy.bkt.clouddn.com/s6.jpg', 2, 1519802765, 0, 1519833212, NULL, 2, 0, 1),
(12, '根本你不懂得爱我', 'http://p3ijb4nfy.bkt.clouddn.com/韦雄 - 根本你不懂得爱我.mp3', '韦雄', 'http://p3ijb4nfy.bkt.clouddn.com/s7.jpg', 2, 1518768693, 0, 1519832222, NULL, 2, 0, 1),
(13, '鬼迷心窍', 'http://p3ijb4nfy.bkt.clouddn.com/李宗盛 - 鬼迷心窍.mp3', '李宗盛', 'http://p3ijb4nfy.bkt.clouddn.com/s14.jpg', 2, 1519802765, 1000, 1519831123, NULL, 2, 0, 1),
(14, '病变', 'http://p3ijb4nfy.bkt.clouddn.com/病变.mp3', 'BINGBIAN', 'http://p3ijb4nfy.bkt.clouddn.com/s15.jpg', 1, 1517997007, 0, 1519830000, NULL, 2, 0, 1),
(15, '都选C', 'http://p3ijb4nfy.bkt.clouddn.com/大鹏 - 都选C.mp3', '大鹏', 'http://p3ijb4nfy.bkt.clouddn.com/s16.jpg', 2, 1519802765, 0, 1519830011, NULL, 2, 0, 1),
(16, '差一步', 'http://p3ijb4nfy.bkt.clouddn.com/大壮 - 差一步.mp3', '大壮', 'http://p3ijb4nfy.bkt.clouddn.com/s17.jpg', 2, 1518768693, 0, 1519831122, NULL, 2, 0, 1),
(17, '火锅底料', 'http://p3ijb4nfy.bkt.clouddn.com/火锅底料.mp3', 'GAY', 'http://p3ijb4nfy.bkt.clouddn.com/s18.jpg', 1, 1517997007, 444, 1519831122, NULL, 2, 0, 1),
(19, '你猜我猜不猜', 'http://p3ijb4nfy.bkt.clouddn.com/你猜我猜不猜.mp3', 'GAY', 'http://p3ijb4nfy.bkt.clouddn.com/s19.jpg', 2, 1518768693, 222, 1519831234, NULL, 2, 0, 1),
(20, '떠나가지 못하는 남자', 'http://p3ijb4nfy.bkt.clouddn.com/떠나가지 못하는 남자.mp3', 'sessang', 'http://p3ijb4nfy.bkt.clouddn.com/s20.jpg', 1, 1517997007, 0, 1519831231, NULL, 2, 0, 1),
(23, '像风一样', 'http://p3ijb4nfy.bkt.clouddn.com/薛之谦 - 像风一样.mp3', '薛之谦', 'http://p3ijb4nfy.bkt.clouddn.com/s21.jpg', 1, 1517997007, 0, 1519831111, NULL, 2, 0, 1),
(25, '思念是一种病', 'http://p3ijb4nfy.bkt.clouddn.com/张震岳 - 思念是一种病.mp3', '张震岳', 'http://p3ijb4nfy.bkt.clouddn.com/s22.jpg', 2, 1518768693, 212, 1519838190, NULL, 2, 0, 1),
(26, '妳太善良', 'http://p3ijb4nfy.bkt.clouddn.com/张智霖 - 妳太善良.mp3', '张智霖', 'http://p3ijb4nfy.bkt.clouddn.com/s23.jpg', 1, 1517997007, 0, 1519838190, NULL, 2, 0, 1),
(27, '给自己的歌', 'http://p3ijb4nfy.bkt.clouddn.com/纵贯线 - 给自己的歌.mp3', '纵贯线', 'http://p3ijb4nfy.bkt.clouddn.com/s24.jpg', 2, 1519802765, 0, 1519838190, NULL, 2, 0, 1),
(28, '公路', 'http://p3ijb4nfy.bkt.clouddn.com/纵贯线 - 公路.mp3', '纵贯线', 'http://p3ijb4nfy.bkt.clouddn.com/s25.jpg', 2, 1518768693, 0, 1519838190, NULL, 2, 0, 1),
(29, '光阴的故事', 'http://p3ijb4nfy.bkt.clouddn.com/纵贯线 - 光阴的故事.mp3', '纵贯线', 'http://p3ijb4nfy.bkt.clouddn.com/s26.jpg', 1, 1517997007, 0, 1519838190, NULL, 2, 0, 1),
(33, ' 单行的轨道', 'http://p3ijb4nfy.bkt.clouddn.com/G.E.M.邓紫棋 - 单行的轨道.mp3', 'G.E.M.邓紫棋', 'http://p3ijb4nfy.bkt.clouddn.com/s27.jpg', 2, 1519802765, 0, 1519838190, NULL, 2, 0, 1),
(34, '新的心跳', 'http://p3ijb4nfy.bkt.clouddn.com/G.E.M.邓紫棋 - 新的心跳.mp3', 'G.E.M.邓紫棋', 'http://p3ijb4nfy.bkt.clouddn.com/s28.jpg', 2, 1518768693, 0, 1519838190, NULL, 2, 0, 1),
(35, '내가 웃는게 아니야', 'http://p3ijb4nfy.bkt.clouddn.com/Leessang,Ali - 내가 웃는게 아니야.mp3', 'Leessang,Ali', 'http://p3ijb4nfy.bkt.clouddn.com/s29.jpg', 1, 1517997007, 0, 1519838190, NULL, 2, 0, 1),
(36, ' 나란 놈은 답은 너다', 'http://p3ijb4nfy.bkt.clouddn.com/Leessang,河琳 - 나란 놈은 답은 너다.mp3', 'Leessang,河琳', 'http://p3ijb4nfy.bkt.clouddn.com/s30.jpg', 2, 1519802765, 0, 1519838190, NULL, 2, 0, 1),
(32, 'TV를 껐네', 'http://p3ijb4nfy.bkt.clouddn.com/Leessang,尹美莱,权正烈 - TV를 껐네.mp3', 'Leessang,尹美莱,权正烈', 'http://p3ijb4nfy.bkt.clouddn.com/s31.jpg', 2, 1518768693, 0, 1519838190, NULL, 2, 0, 1),
(31, '우리 지금 만나', 'http://p3ijb4nfy.bkt.clouddn.com/Leessang,张基河和脸们 - 우리 지금 만나.mp3', 'Leessang,张基河', 'http://p3ijb4nfy.bkt.clouddn.com/s32.jpg', 1, 1517997007, 0, 1519838190, NULL, 2, 0, 1),
(30, 'Nevada', 'http://p3ijb4nfy.bkt.clouddn.com/Nevada.mp3', 'Nevada', 'http://p3ijb4nfy.bkt.clouddn.com/s35.jpg', 2, 1518768693, 0, 1519838190, NULL, 2, 0, 1);

-- --------------------------------------------------------

--
-- 表的结构 `m_songstyle`
--

CREATE TABLE `m_songstyle` (
  `id` int(11) NOT NULL,
  `style` char(255) NOT NULL COMMENT '风格，包含二级风格',
  `grade` int(11) DEFAULT '1' COMMENT '风格等级，1主风格，2二级风格',
  `pid` int(11) DEFAULT '0' COMMENT '二级对应的所属主风格，主风格默认为0',
  `songid` varchar(500) DEFAULT ',' COMMENT '二级风格下对应的歌曲id，主风格没有',
  `create_time` int(12) NOT NULL COMMENT '创建时间',
  `status` int(2) DEFAULT '1' COMMENT '1显示， 2屏蔽'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `m_songstyle`
--

INSERT INTO `m_songstyle` (`id`, `style`, `grade`, `pid`, `songid`, `create_time`, `status`) VALUES
(1, '语种', 1, 0, '，', 0, 2),
(2, '风格', 1, 0, '，', 0, 1),
(3, '场景', 1, 0, '，', 0, 1),
(4, '情感', 1, 0, '，', 0, 1),
(5, '主题', 1, 0, '，', 0, 1),
(6, '华语', 2, 1, ',1,2,3,4,5,7,9,', 0, 2),
(7, '欧美', 2, 1, ',2,3,4,5,', 0, 2),
(8, '日语', 2, 1, ',4,5,', 0, 2),
(9, '粤语', 2, 1, ',5,7,9,', 0, 2),
(10, '流行', 2, 2, ',2,4,5,6,', 0, 1),
(11, '民谣', 2, 2, ',4,6,8,9,', 0, 1),
(12, '电音', 2, 2, ',1,2,3,4,', 0, 1),
(13, '后摇', 2, 2, ',2,4,5,6,', 0, 1),
(14, '乡村', 2, 2, ',3,4,5,', 0, 1),
(15, '轻音乐', 2, 2, ',2,3,4,5,', 0, 1),
(16, '旅行', 2, 3, ',4,5,7,9,', 0, 1),
(17, '学习', 2, 3, ',3,4,5,8,', 0, 1),
(18, '休息', 2, 3, '2,3,4,7,8,', 0, 1),
(19, '运动', 2, 3, ',2,3,4,7,9', 0, 1),
(20, '清新', 2, 4, ',1,3,4,5,', 0, 1),
(21, '孤独', 2, 4, ',2,3,4,6,7,', 0, 1),
(22, '怀旧', 2, 4, ',4,5,6,8,', 0, 1),
(23, '治愈', 2, 4, ',3,4,5,', 0, 1),
(24, '影视原声', 2, 5, ',1,2,4,7,', 0, 1),
(25, '吉他', 2, 1, ',3,4,5,6,7,', 0, 2),
(26, '钢琴', 2, 5, ',1,4,5,', 0, 1),
(27, '青春', 3, 6, ',3,6,7,8,9,', 0, 1),
(28, '阿三的歌', 1, 0, ',', 1519802528, 1),
(29, 'www', 2, 0, ',', 1519885556, 1);

-- --------------------------------------------------------

--
-- 表的结构 `m_two`
--

CREATE TABLE `m_two` (
  `tid` int(11) NOT NULL,
  `iid` int(11) NOT NULL COMMENT '身份id',
  `jid` int(11) NOT NULL COMMENT '权限id',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `status` int(2) NOT NULL DEFAULT '1' COMMENT '状态，1生效，2失效'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `m_two`
--

INSERT INTO `m_two` (`tid`, `iid`, `jid`, `create_time`, `status`) VALUES
(21, 11, 1, 1517992719, 1),
(22, 11, 2, 1517992719, 1),
(36, 3, 3, 1519424230, 1),
(37, 3, 4, 1519424230, 1),
(51, 2, 1, 1519424717, 1),
(52, 2, 2, 1519424717, 1),
(53, 1, 1, 1519424732, 1),
(54, 1, 2, 1519424732, 1),
(55, 1, 3, 1519424732, 1),
(56, 1, 4, 1519424732, 1),
(57, 1, 5, 1519424732, 1),
(58, 1, 6, 1519424732, 1),
(59, 1, 7, 1519424732, 1),
(60, 1, 8, 1519424732, 1),
(61, 1, 9, 1519424733, 1),
(62, 4, 5, 1519425938, 1),
(63, 4, 6, 1519425938, 1),
(64, 5, 7, 1519425954, 1),
(65, 5, 9, 1519425954, 1);

-- --------------------------------------------------------

--
-- 表的结构 `m_user`
--

CREATE TABLE `m_user` (
  `uid` int(11) NOT NULL,
  `nickname` char(30) NOT NULL COMMENT '昵称',
  `admin` int(4) DEFAULT '2' COMMENT ' 管理员权限，1是管理员,2是普通用户',
  `phone` char(20) NOT NULL COMMENT '手机',
  `password` varchar(50) NOT NULL COMMENT '密码',
  `email` varchar(50) NOT NULL,
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `update_time` int(11) NOT NULL COMMENT '更新时间',
  `ip` int(11) NOT NULL,
  `sex` int(11) DEFAULT NULL COMMENT '男1，女2，0保密',
  `birthday` varchar(30) DEFAULT NULL COMMENT '生日',
  `province` char(30) DEFAULT NULL COMMENT '省',
  `city` char(50) DEFAULT NULL COMMENT '市',
  `autograph` varchar(500) DEFAULT '无个性不签名' COMMENT '个人介绍',
  `photo` varchar(200) DEFAULT '/images/zbs.jpg' COMMENT '头像',
  `level` int(11) DEFAULT '1' COMMENT '等级',
  `grade` int(11) DEFAULT '50' COMMENT '积分',
  `friends` int(11) DEFAULT '0' COMMENT '好友个数',
  `post` int(11) DEFAULT '0' COMMENT '发帖数',
  `reply` int(11) DEFAULT '0' COMMENT '回帖数',
  `music` int(11) DEFAULT '0' COMMENT '歌单数',
  `savelist` varchar(500) DEFAULT ',' COMMENT '收藏的歌单id',
  `mylist` varchar(500) DEFAULT ',' COMMENT '创建的歌单id列表',
  `songId` varchar(500) DEFAULT ',' COMMENT ' 喜欢歌曲的ID，字符串形式存储，例如1,2,3,',
  `status` int(1) DEFAULT '1' COMMENT '状态,1未锁，2锁定'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `m_user`
--

INSERT INTO `m_user` (`uid`, `nickname`, `admin`, `phone`, `password`, `email`, `create_time`, `update_time`, `ip`, `sex`, `birthday`, `province`, `city`, `autograph`, `photo`, `level`, `grade`, `friends`, `post`, `reply`, `music`, `savelist`, `mylist`, `songId`, `status`) VALUES
(1, '小迷糊', 2, '13167630699', 'hy12345', '1145358940@qq.com', 111111111, 22222222, 192168, 1, '1994/10/08', '安徽', '宿州', '无个性不签名', '/images/zbs.jpg', 1, 1000, 0, 0, 0, 0, ',7,2,3,4,5,6,', ',7,2,3,4,5,6,', ',1,3,4,5,9,8,6,', 1),
(2, 'admin', 1, '18310154131', '21232f297a57a5a743894a0e4a801fc3', 'admin@qq.com', 11, 1519885467, 1, 0, '2017/2/3', '天津市', '河东区', '哈哈哈~', '/uploads/20180226/8258ba7633941dfdf840beafd11f4203.jpg', 1, 0, 0, 0, 0, 0, ',2,', ',', ',5,10,', 1),
(3, 'admin1', 1, '111111111', '21232f297a57a5a743894a0e4a801fc3', '1145358940@qq.com', 1517887233, 1519802276, 0, 1, NULL, NULL, NULL, '无个性不签名', '/images/zbs.jpg', 1, 50, 0, 0, 0, 0, ',', ',', ',5,', 1),
(4, 'admin222', 2, '18310154130', '21232f297a57a5a743894a0e4a801fc3', 'admin2@qq.com', 1517887344, 1517887344, 0, 1, NULL, '省份', '市/区', '无个性不签名', '/uploads/20180301/e10f181a986944697cef9861e237c7f6.JPG', 1, 50, 0, 0, 0, 0, ',', ',', ',', 1),
(5, 'admin3', 2, '18310154137', '21232f297a57a5a743894a0e4a801fc3', 'admin3@qq.com', 1517887379, 1517887379, 0, 2, NULL, NULL, NULL, '无个性不签名', '/images/zbs.jpg', 1, 50, 0, 0, 0, 0, ',', ',', ',', 1),
(6, 'admin44', 2, '18310154138', '21232f297a57a5a743894a0e4a801fc3', '123@123.com', 1517887431, 1517887431, 0, 1, NULL, NULL, NULL, '无个性不签名', '/images/zbs.jpg', 1, 50, 0, 0, 0, 0, ',', ',', ',', 1),
(7, '678687', 1, '1111', '21232f297a57a5a743894a0e4a801fc3', 'admin1@qq.com', 1517939827, 1517939827, 0, 1, NULL, NULL, NULL, '123', '/images/zbs.jpg', 1, 50, 0, 0, 0, 0, ',', ',', ',', 1),
(8, '123', 1, '183105678', '4297f44b13955235245b2497399d7a93', '1@q.com', 1517968581, 1517968581, 0, 1, NULL, NULL, NULL, '123', '/images/zbs.jpg', 1, 50, 0, 0, 0, 0, ',', ',', ',', 1),
(9, '123123aa', 1, '123122', '4297f44b13955235245b2497399d7a93', '1@qwe.com', 1517968943, 1517968943, 0, 1, NULL, NULL, NULL, '1231231', '/images/zbs.jpg', 1, 50, 0, 0, 0, 0, ',', ',', ',', 1),
(10, 'qq+赤脚青春', 2, '1', '404c6ee0c2e39f501bad70942b0def71', '未设置', 1518074813, 1518074813, 0, 1, NULL, NULL, NULL, '无个性不签名', 'http://q.qlogo.cn/qqapp/100378832/CCCA9B9D25147F9F21C3C8090303AB92/100', 1, 50, 0, 0, 0, 0, ',', ',', ',', 1),
(11, 'cheng', 1, '123451', '21232f297a57a5a743894a0e4a801fc3', '12@qq.com', 1519423392, 1519423433, 0, 1, NULL, NULL, NULL, 'cheng123', '/images/zbs.jpg', 1, 50, 0, 0, 0, 0, ',', ',', ',', 2),
(12, 'xiaoxigua', 2, '13167630600', '849b436e21013aa16f974e0928d75c86', 'admingff@qq.com', 1519735880, 1519735880, 0, NULL, NULL, NULL, NULL, '无个性不签名', '/images/zbs.jpg', 1, 50, 0, 0, 0, 0, ',', ',', ',', 1),
(13, 'qq+Cheng', 2, '1', '1e760e41946b46dec94c65f401fd9f6a', '未设置', 1519804546, 1519867526, 1875814974, 1, NULL, NULL, NULL, '无个性不签名', 'http://thirdqq.qlogo.cn/qqapp/100378832/A2F967D2DAF7634B016E3026B95EF6F5/100', 1, 50, 0, 0, 0, 0, ',', ',', ',', 1),
(14, 'qq+～          ⊙_⊙all about you', 2, '1', 'f7d0852a55baa47f134551483b6d8644', '未设置', 1519815600, 1519816047, 1875526178, 1, NULL, NULL, NULL, '无个性不签名', 'http://thirdqq.qlogo.cn/qqapp/100378832/ABA9C86FC17C1F742C4B80C0521F3A2F/100', 1, 50, 0, 0, 0, 0, ',', ',', ',', 1),
(15, 'qq+13.10.6', 2, '18435154504', 'ffc8b8d322554b6bd1963ae85d98cbf8', '未设置', 1519825787, 1519869885, 1875814974, 1, '1994/3/5', '山西省', '临汾市', '谁说的', 'http://thirdqq.qlogo.cn/qqapp/100378832/20AAA41662986CFF12806E12629A5717/100', 1, 50, 0, 0, 0, 0, ',4,', ',', ',2,', 1),
(16, 'qq+快乐就好', 2, '1', 'ebefa9a488d0eb51a94648ff93ea01d9', '未设置', 1519833976, 1519834036, 1851828262, 1, NULL, NULL, NULL, '无个性不签名', 'http://thirdqq.qlogo.cn/qqapp/100378832/FBFBCF66EB56CCD6DB265DB333F4688D/100', 1, 50, 0, 0, 0, 0, ',', ',', ',', 1),
(17, 'qq+stan', 2, '1', '899f969924fcbe51bbd41ab4b8405614', '未设置', 1519884549, 1519902403, 1875526178, 1, NULL, NULL, NULL, '无个性不签名', 'http://thirdqq.qlogo.cn/qqapp/100378832/2E0788434653968834FFC83950AFE032/100', 1, 50, 0, 0, 0, 0, ',', ',', ',', 1),
(18, 'qq+Traveller、', 2, '1', '16da3ad7eac1303bce0611b0437dab43', '未设置', 1519981272, 1519981272, 1780891290, 1, NULL, NULL, NULL, '无个性不签名', 'http://thirdqq.qlogo.cn/qqapp/100378832/E7828E3711FB41E85D1809D91E8B8301/100', 1, 50, 0, 0, 0, 0, ',', ',', ',1,', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `m_identity`
--
ALTER TABLE `m_identity`
  ADD PRIMARY KEY (`iid`);

--
-- Indexes for table `m_lbt`
--
ALTER TABLE `m_lbt`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `m_list`
--
ALTER TABLE `m_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `m_mv`
--
ALTER TABLE `m_mv`
  ADD PRIMARY KEY (`mid`);

--
-- Indexes for table `m_mvreply`
--
ALTER TABLE `m_mvreply`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `m_mvstyle`
--
ALTER TABLE `m_mvstyle`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `m_one`
--
ALTER TABLE `m_one`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `m_pyq`
--
ALTER TABLE `m_pyq`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `m_pyqreply`
--
ALTER TABLE `m_pyqreply`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `m_quanxian`
--
ALTER TABLE `m_quanxian`
  ADD PRIMARY KEY (`jid`);

--
-- Indexes for table `m_singer`
--
ALTER TABLE `m_singer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `m_song`
--
ALTER TABLE `m_song`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `m_songstyle`
--
ALTER TABLE `m_songstyle`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `m_two`
--
ALTER TABLE `m_two`
  ADD PRIMARY KEY (`tid`);

--
-- Indexes for table `m_user`
--
ALTER TABLE `m_user`
  ADD PRIMARY KEY (`uid`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `m_identity`
--
ALTER TABLE `m_identity`
  MODIFY `iid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- 使用表AUTO_INCREMENT `m_lbt`
--
ALTER TABLE `m_lbt`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- 使用表AUTO_INCREMENT `m_list`
--
ALTER TABLE `m_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- 使用表AUTO_INCREMENT `m_mv`
--
ALTER TABLE `m_mv`
  MODIFY `mid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- 使用表AUTO_INCREMENT `m_mvreply`
--
ALTER TABLE `m_mvreply`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;
--
-- 使用表AUTO_INCREMENT `m_mvstyle`
--
ALTER TABLE `m_mvstyle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- 使用表AUTO_INCREMENT `m_one`
--
ALTER TABLE `m_one`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
--
-- 使用表AUTO_INCREMENT `m_pyq`
--
ALTER TABLE `m_pyq`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
--
-- 使用表AUTO_INCREMENT `m_pyqreply`
--
ALTER TABLE `m_pyqreply`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
--
-- 使用表AUTO_INCREMENT `m_quanxian`
--
ALTER TABLE `m_quanxian`
  MODIFY `jid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- 使用表AUTO_INCREMENT `m_singer`
--
ALTER TABLE `m_singer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- 使用表AUTO_INCREMENT `m_song`
--
ALTER TABLE `m_song`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;
--
-- 使用表AUTO_INCREMENT `m_songstyle`
--
ALTER TABLE `m_songstyle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
--
-- 使用表AUTO_INCREMENT `m_two`
--
ALTER TABLE `m_two`
  MODIFY `tid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;
--
-- 使用表AUTO_INCREMENT `m_user`
--
ALTER TABLE `m_user`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
