-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Mar 04, 2019 at 10:42 PM
-- Server version: 5.7.23
-- PHP Version: 7.2.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `mls`
--

-- --------------------------------------------------------

--
-- Table structure for table `mls_banned`
--

CREATE TABLE `mls_banned` (
                            `userid` int(11) NOT NULL,
                            `until` int(11) NOT NULL,
                            `by` int(11) NOT NULL,
                            `reason` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mls_files`
--

CREATE TABLE `mls_files` (
                           `id` int(11) NOT NULL,
                           `userid` int(100) NOT NULL,
                           `name` varchar(255) NOT NULL,
                           `type` varchar(255) NOT NULL,
                           `size` int(11) NOT NULL,
                           `shareids` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mls_files`
--

INSERT INTO `mls_files` (`id`, `userid`, `name`, `type`, `size`, `shareids`) VALUES
(1, 2, '5c7da696afe793.40487211.jpg', 'image/jpeg', 5039558, '1'),
(2, 2, '5c7da6a0497887.14557168.jpg', 'image/jpeg', 5449406, ''),
(3, 1, '5c7da702ce5229.39054775.jpg', 'image/jpeg', 5302263, ''),
(4, 1, '5c7da75535fcc2.73742372.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 27789, ''),
(5, 1, '5c7da7de3b98f4.40341991.docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 27789, ''),
(6, 1, '5c7da8193a5835.71051993.pptx', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 344431, ''),
(7, 1, '5c7da857acb6a3.97568976.pdf', 'application/pdf', 81505, '');

-- --------------------------------------------------------

--
-- Table structure for table `mls_groups`
--

CREATE TABLE `mls_groups` (
                            `groupid` int(11) NOT NULL,
                            `name` varchar(255) NOT NULL,
                            `type` int(11) NOT NULL,
                            `priority` int(11) NOT NULL,
                            `color` varchar(50) NOT NULL,
                            `canban` int(11) NOT NULL,
                            `canhideavt` int(11) NOT NULL,
                            `canedit` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mls_groups`
--

INSERT INTO `mls_groups` (`groupid`, `name`, `type`, `priority`, `color`, `canban`, `canhideavt`, `canedit`) VALUES
(1, 'Guest', 0, 1, '', 0, 0, 0),
(2, 'Member', 1, 1, '#08c', 0, 0, 0),
(3, 'Moderator', 2, 1, 'green', 1, 1, 0),
(4, 'Administrator', 3, 1, '#F0A02D', 1, 1, 1),
(5, 'Corporate', 2, 1, 'purple', 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `mls_news`
--

CREATE TABLE `mls_news` (
                          `id` int(100) NOT NULL,
                          `userid` int(100) NOT NULL,
                          `name` varchar(100) NOT NULL,
                          `comment` varchar(255) NOT NULL,
                          `time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mls_news`
--

INSERT INTO `mls_news` (`id`, `userid`, `name`, `comment`, `time`) VALUES
(1, 1, 'Admin', '<b> Hello This is the title </b>\r\n<br>\r\n<u>This is underlined</u>', 1551557401),
(2, 1, 'Admin', '<b><u> Hello This is the title</u>\r\n<br/><br/>\r\n</b><u>This is underlined</u>', 1551563065),
(3, 1, 'Admin', 'Hello!', 1551648626);

-- --------------------------------------------------------

--
-- Table structure for table `mls_privacy`
--

CREATE TABLE `mls_privacy` (
                             `userid` int(11) NOT NULL,
                             `email` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mls_privacy`
--

INSERT INTO `mls_privacy` (`userid`, `email`) VALUES
(1, 0),
(2, 1),
(3, 0),
(4, 0),
(5, 0);

-- --------------------------------------------------------

--
-- Table structure for table `mls_settings`
--

CREATE TABLE `mls_settings` (
                              `site_name` varchar(255) NOT NULL DEFAULT 'Demo Site',
                              `url` varchar(300) NOT NULL,
                              `admin_email` varchar(255) NOT NULL,
                              `max_ban_period` int(11) NOT NULL DEFAULT '10',
                              `register` int(11) NOT NULL DEFAULT '1',
                              `email_validation` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mls_settings`
--

INSERT INTO `mls_settings` (`site_name`, `url`, `admin_email`, `max_ban_period`, `register`, `email_validation`) VALUES
('Luke\'s Cloud Document Repo', 'http://localhost:8888', 'lukehetherington123@gmail.com', 10, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `mls_users`
--

CREATE TABLE `mls_users` (
                           `userid` int(11) NOT NULL,
                           `username` varchar(50) NOT NULL,
                           `display_name` varchar(255) NOT NULL,
                           `password` varchar(50) NOT NULL,
                           `email` varchar(255) NOT NULL,
                           `key` varchar(50) NOT NULL,
                           `validated` varchar(100) NOT NULL,
                           `groupid` int(11) NOT NULL DEFAULT '2',
                           `lastactive` int(11) NOT NULL,
                           `showavt` int(11) NOT NULL DEFAULT '1',
                           `banned` int(11) NOT NULL,
                           `regtime` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mls_users`
--

INSERT INTO `mls_users` (`userid`, `username`, `display_name`, `password`, `email`, `key`, `validated`, `groupid`, `lastactive`, `showavt`, `banned`, `regtime`) VALUES
(1, 'admin', 'Admin', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'lukehetherington123@gmail.com', '0', '1', 4, 1551738967, 1, 0, 1550774167),
(2, 'User', 'user', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'user@gmail.com', '', '1', 2, 1551738608, 1, 0, 1548718550),
(3, 'Corporate', 'Corporate', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Corporate@gmail.com', '', 'Yes', 5, 1551484791, 1, 0, 1551484242),
(4, 'Moderator', 'Moderator', '7110eda4d09e062aa5e4a390b0a572ac0d2c0220', 'Moderator@gmail.com', '', 'Yes', 3, 1551488097, 1, 0, 1551488097),
(5, 'test', 'test', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 'test@gmail.com', '', 'Yes', 2, 1551489307, 1, 0, 1551489307);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mls_banned`
--
ALTER TABLE `mls_banned`
  ADD UNIQUE KEY `userid` (`userid`);

--
-- Indexes for table `mls_files`
--
ALTER TABLE `mls_files`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `mls_groups`
--
ALTER TABLE `mls_groups`
  ADD PRIMARY KEY (`groupid`);

--
-- Indexes for table `mls_news`
--
ALTER TABLE `mls_news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mls_privacy`
--
ALTER TABLE `mls_privacy`
  ADD UNIQUE KEY `userid` (`userid`);

--
-- Indexes for table `mls_users`
--
ALTER TABLE `mls_users`
  ADD PRIMARY KEY (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mls_files`
--
ALTER TABLE `mls_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `mls_groups`
--
ALTER TABLE `mls_groups`
  MODIFY `groupid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `mls_news`
--
ALTER TABLE `mls_news`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `mls_privacy`
--
ALTER TABLE `mls_privacy`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `mls_users`
--
ALTER TABLE `mls_users`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
