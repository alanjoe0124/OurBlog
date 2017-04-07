
--
-- Database: `ourblog`
--

-- --------------------------------------------------------

--
-- 表的结构 `blog`
--

CREATE TABLE IF NOT EXISTS `blog` (
 `id` int(10) NOT NULL AUTO_INCREMENT,
 `idx_column_id` tinyint(3) NOT NULL,
 `title` varchar(100) NOT NULL, 
 `content` text,
 `user_id` tinyint(3) NOT NULL,
 `post_time` datetime DEFAULT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=118 DEFAULT CHARSET=utf8 ;


-- --------------------------------------------------------

--
-- 表的结构 `index_column`
--

CREATE TABLE IF NOT EXISTS `index_column` (
 `id` tinyint(3) NOT NULL AUTO_INCREMENT,
 `name` char(20) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;


-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE IF NOT EXISTS `user` (
 `id` tinyint(3) NOT NULL AUTO_INCREMENT,
 `email` varchar(100) NOT NULL,
 `pwd` varchar(40) NOT NULL,
 `reg_time` datetime DEFAULT NULL,
 PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- 表的结构 `blog_tag`
--

CREATE TABLE IF NOT EXISTS `blog_tag` (
 `blog_id` int(10) NOT NULL,
 `tag_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8

-- --------------------------------------------------------

--
-- 表的结构 `blog_tag`
--

CREATE TABLE IF NOT EXISTS `tag` (
 `id` int(10) NOT NULL AUTO_INCREMENT,
 `tag_name` varchar(20) NOT NULL,
 PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8