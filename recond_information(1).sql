-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2020-02-03 21:28:37
-- 服务器版本： 5.5.62-log
-- PHP 版本： 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `edu`
--

-- --------------------------------------------------------

--
-- 表的结构 `recond_information`
--

CREATE TABLE `recond_information` (
  `id` int(11) NOT NULL,
  `recond_table_name` varchar(50) NOT NULL COMMENT '各记录表的表名',
  `recond_explain` varchar(20) NOT NULL,
  `number_of_person` int(11) NOT NULL COMMENT '各记录表的人数',
  `recond_subject` varchar(50) NOT NULL COMMENT '各记录表的项目',
  `class` int(11) NOT NULL COMMENT '各记录表的班级',
  `tl` varchar(500) NOT NULL,
  `upload_file` int(11) NOT NULL,
  `must_upload` tinyint(1) NOT NULL,
  `special_group` varchar(500) NOT NULL,
  `upload_information` int(11) NOT NULL,
  `information_data` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

--
-- 转储表的索引
--

--
-- 表的索引 `recond_information`
--
ALTER TABLE `recond_information`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `recond_information`
--
ALTER TABLE `recond_information`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
