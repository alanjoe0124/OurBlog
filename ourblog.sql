
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
  `title` char(60) NOT NULL,
  `content` mediumtext,
  `user_id` tinyint(3) NOT NULL,
  `post_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;


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
  `email` char(40) NOT NULL,
  `pwd` char(40) NOT NULL,
  `reg_time` datetime DEFAULT NULL,
  `session_validate` char(40) NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;


