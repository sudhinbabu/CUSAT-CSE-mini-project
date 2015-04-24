-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 28, 2015 at 12:26 PM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `allotment_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE IF NOT EXISTS `applications` (
  `user_id` bigint(20) NOT NULL,
  `year_of_passing` year(4) NOT NULL,
  `religion_id` bigint(20) NOT NULL,
  `first_preference` bigint(20) NOT NULL,
  `second_preference` bigint(20) NOT NULL,
  `third_preference` bigint(20) NOT NULL,
  `current_preference` tinyint(1) NOT NULL DEFAULT '1',
`challan_no` int(11) NOT NULL,
  `chellan_payment` bit(1) NOT NULL DEFAULT b'0',
  `alloted_course_id` bigint(11) DEFAULT NULL,
  `allotment_number` int(2) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`user_id`, `year_of_passing`, `religion_id`, `first_preference`, `second_preference`, `third_preference`, `current_preference`, `challan_no`, `chellan_payment`, `alloted_course_id`, `allotment_number`, `status`) VALUES
(8, 1999, 1, 8, 8, 17, 1, 1, b'0', NULL, NULL, NULL),
(9, 1972, 1, 14, 14, 17, 1, 2, b'0', NULL, NULL, NULL),
(10, 1978, 14, 17, 8, 17, 1, 3, b'0', NULL, NULL, NULL),
(11, 2005, 1, 17, 8, 17, 1, 4, b'0', NULL, NULL, NULL),
(12, 1986, 18, 14, 14, 8, 1, 5, b'0', NULL, NULL, NULL),
(13, 1984, 1, 17, 14, 14, 1, 6, b'0', NULL, NULL, NULL),
(14, 1995, 18, 14, 17, 14, 1, 7, b'0', NULL, NULL, NULL),
(15, 1970, 17, 8, 14, 17, 1, 8, b'0', NULL, NULL, NULL),
(16, 1980, 18, 17, 17, 14, 1, 9, b'0', NULL, NULL, NULL),
(17, 1976, 14, 14, 14, 8, 1, 10, b'0', NULL, NULL, NULL),
(18, 1987, 18, 14, 8, 8, 1, 11, b'0', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE IF NOT EXISTS `courses` (
`course_id` bigint(20) NOT NULL,
  `name` text NOT NULL,
  `seats` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`course_id`, `name`, `seats`) VALUES
(8, 'physics', 3),
(14, 'Maths', 2),
(17, 'B com', 3);

-- --------------------------------------------------------

--
-- Table structure for table `course_dependencies`
--

CREATE TABLE IF NOT EXISTS `course_dependencies` (
`dependency_id` bigint(11) NOT NULL,
  `course_id` bigint(20) NOT NULL,
  `dependency` text NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `course_dependencies`
--

INSERT INTO `course_dependencies` (`dependency_id`, `course_id`, `dependency`) VALUES
(1, 8, 'physics'),
(3, 8, 'maths'),
(4, 13, 'chemistry'),
(5, 13, 'physics'),
(6, 13, 'maths'),
(7, 14, 'maths'),
(8, 15, 'social science'),
(9, 15, 'english'),
(10, 17, 'maths'),
(11, 17, 'ecnomics');

-- --------------------------------------------------------

--
-- Table structure for table `marks`
--

CREATE TABLE IF NOT EXISTS `marks` (
`id` bigint(11) NOT NULL,
  `user_id` bigint(20) NOT NULL COMMENT 'foreign key',
  `subject_name` text NOT NULL,
  `max_mark` int(11) NOT NULL,
  `awarded_mark` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=38 ;

--
-- Dumping data for table `marks`
--

INSERT INTO `marks` (`id`, `user_id`, `subject_name`, `max_mark`, `awarded_mark`) VALUES
(1, 8, 'total_marks', 1200, 710),
(2, 8, 'physics', 100, 79),
(3, 8, 'maths', 100, 72),
(4, 8, 'ecnomics', 100, 98),
(5, 9, 'total_marks', 1200, 941),
(6, 9, 'maths', 100, 80),
(7, 9, 'ecnomics', 100, 75),
(8, 10, 'total_marks', 1200, 766),
(9, 10, 'physics', 100, 81),
(10, 10, 'maths', 100, 72),
(11, 10, 'ecnomics', 100, 87),
(12, 11, 'total_marks', 1200, 711),
(13, 11, 'physics', 100, 93),
(14, 11, 'maths', 100, 68),
(15, 11, 'ecnomics', 100, 98),
(16, 12, 'total_marks', 1200, 916),
(17, 12, 'physics', 100, 94),
(18, 12, 'maths', 100, 64),
(19, 13, 'total_marks', 1200, 601),
(20, 13, 'maths', 100, 55),
(21, 13, 'ecnomics', 100, 76),
(22, 14, 'total_marks', 1200, 759),
(23, 14, 'maths', 100, 92),
(24, 14, 'ecnomics', 100, 60),
(25, 15, 'total_marks', 1200, 664),
(26, 15, 'physics', 100, 87),
(27, 15, 'maths', 100, 84),
(28, 15, 'ecnomics', 100, 59),
(29, 16, 'total_marks', 1200, 660),
(30, 16, 'maths', 100, 75),
(31, 16, 'ecnomics', 100, 63),
(32, 17, 'total_marks', 1200, 750),
(33, 17, 'physics', 100, 69),
(34, 17, 'maths', 100, 77),
(35, 18, 'total_marks', 1200, 985),
(36, 18, 'physics', 100, 80),
(37, 18, 'maths', 100, 88);

-- --------------------------------------------------------

--
-- Table structure for table `news_table`
--

CREATE TABLE IF NOT EXISTS `news_table` (
`news_id` int(11) NOT NULL,
  `news` text NOT NULL,
  `date_added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `news_table`
--

INSERT INTO `news_table` (`news_id`, `news`, `date_added`) VALUES
(1, 'registration starts on 19 April 2015', '2015-02-05 00:00:00'),
(2, 'registarion will be closed at 1 may 2015', '2015-02-05 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `religions`
--

CREATE TABLE IF NOT EXISTS `religions` (
`religion_id` bigint(20) NOT NULL,
  `name` text NOT NULL,
  `caste_name` text NOT NULL,
  `category` varchar(15) NOT NULL DEFAULT 'general',
  `reservation_percentage` tinyint(4) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `religions`
--

INSERT INTO `religions` (`religion_id`, `name`, `caste_name`, `category`, `reservation_percentage`) VALUES
(1, 'hindu', 'paraya', 'SC', 35),
(13, 'christian', 'Marthoma', 'OEC', 0),
(14, 'hindu', 'brahmin', 'general', 0),
(17, 'christian', 'malankara catholic', 'general', 0),
(18, 'muslim', 'muslim', 'OEC', 10);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
`id` bigint(11) NOT NULL,
  `label` text NOT NULL,
  `value` varchar(50) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `label`, `value`) VALUES
(1, 'TOTAL_INDEX_MARGIN', '1500'),
(2, 'APPLICATION_FEE', '205'),
(5, 'PREFERENCE_INDEX_MARGIN', '600'),
(6, 'SUBMIT_APPLICATION', 'TRUE'),
(8, 'SYSTEM_STATUS', '1');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`user_id` bigint(11) NOT NULL COMMENT 'primary key',
  `email` varchar(25) NOT NULL COMMENT 'unique',
  `password` varchar(150) NOT NULL,
  `name` text NOT NULL,
  `dob` date NOT NULL,
  `gender` text NOT NULL,
  `phone` bigint(11) NOT NULL,
  `parent_name` text NOT NULL,
  `occupation` text NOT NULL,
  `address` text NOT NULL,
  `username` varchar(20) NOT NULL,
  `user_type` varchar(9) NOT NULL DEFAULT 'applicant'
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `password`, `name`, `dob`, `gender`, `phone`, `parent_name`, `occupation`, `address`, `username`, `user_type`) VALUES
(1, '', '4ff0ee8da3b9806b18c877dbf29bbde50b5bd8e4dad7a3a725000feb82e8f1f87f405941cf41f0c2b5b1939e8a1f9edac7e03c7ceb1491ca5ef467f3bdc6db', 'admin', '0000-00-00', '', 0, '', '', '', 'admin', 'admin'),
(8, 'vobaq@yahoo.com', '4ff0ee8da3b9806b18c877dbf29bbde50b5bd8e4dad7a3a725000feb82e8f121c57e5baafd3838e6a9bf49ea0c9974c6a8fbf3589d4565958e912bb1860dea', 'Sharon Rodriquez', '1994-03-21', 'male', 9332383346, 'Lee Hampton', 'In amet labore quia aliquid fugiat aut enim amet', 'Dolor consequuntur ex amet, ut laudantium, repudiandae adipisci id, cupiditate nihil.', 'femylile', 'applicant'),
(9, 'kafalecyqy@yahoo.com', '4ff0ee8da3b9806b18c877dbf29bbde50b5bd8e4dad7a3a725000feb82e8f117a5f48b3bc40bacbb4a709511f06450a91cc7a0ded18efe1a8455aef4e85f53', 'Kessie Nieves', '1975-04-25', 'female', 9158950536, 'Paloma Nelson', 'Odio non minus voluptatem perferendis et nisi magnam cum autem error exercitation laborum exercitation molestiae quaerat', 'Tempor ipsam eiusmod nobis ea omnis laborum. Ex commodi ut nulla cum in aliquid quis enim dolor.', 'lupyp', 'applicant'),
(10, 'qaqatom@hotmail.com', '4ff0ee8da3b9806b18c877dbf29bbde50b5bd8e4dad7a3a725000feb82e8f116a3ea8de81a38a4ef03048b473e0aa3ff3945c3f368b67c11fb1a8fe13c4c0f', 'Tana Bishop', '1986-09-12', 'female', 9669041096, 'Sawyer Daniels', 'Ut molestiae nihil fugit eos in obcaecati esse rerum nihil omnis nisi quidem qui voluptas perferendis', 'Assumenda voluptatem. Voluptatem, aut aut consequatur, nesciunt, rerum eos, sint veritatis eum perferendis animi, dolore sit.', 'futap', 'applicant'),
(11, 'cazyj@hotmail.com', '4ff0ee8da3b9806b18c877dbf29bbde50b5bd8e4dad7a3a725000feb82e8f17f20a1e635ba1c03bb95455ff8bf6877e654d98dd9edf04be4a7a5a75794caff', 'Upton Curry', '2015-01-20', 'male', 9521071521, 'Shaine Blevins', 'Excepturi sint dolor enim cum delectus est ipsum', 'Omnis est facilis voluptatum totam qui laudantium, at qui do saepe quis velit enim veniam, tempor vitae fuga.', 'desoteqi', 'applicant'),
(12, 'riqy@gmail.com', '4ff0ee8da3b9806b18c877dbf29bbde50b5bd8e4dad7a3a725000feb82e8f1878685528dd39dc5ed2cd8b0a0b4a440ec9e068eff1cbb038af88b346d0d85fb', 'Keane Kelly', '2001-01-08', 'female', 9784182125, 'Veronica Stevenson', 'Cumque cumque omnis accusamus aliquid proident quia lorem at nulla duis est quis aut excepteur dolor qui', 'Dolores esse, placeat, molestiae pariatur? Facilis sunt ut similique consequatur voluptatibus.', 'bahejizan', 'applicant'),
(13, 'fuqiwugij@yahoo.com', '4ff0ee8da3b9806b18c877dbf29bbde50b5bd8e4dad7a3a725000feb82e8f1607d93bf51882c7ade60f2e33c17f30c8e92bab1d2f5bf2e4c493669a15646b3', 'Melissa Parsons', '1975-04-09', 'female', 9999911599, 'Amaya Fuentes', 'Vitae et recusandae Nulla possimus duis animi voluptatibus ipsam est voluptate pariatur In velit sint nobis', 'Ut consequat. Ex adipisci est, repellendus. Ut accusantium ducimus, officia aut voluptas in voluptatem sunt voluptas.', 'jocuvymomu', 'applicant'),
(14, 'nacoluli@gmail.com', '4ff0ee8da3b9806b18c877dbf29bbde50b5bd8e4dad7a3a725000feb82e8f134e13d727d94bda82f559254023dcd2f803c59ea8cba193fb0b1f9e015653f1e', 'Leigh Calhoun', '1984-02-12', 'female', 9145500202, 'Jena Hardy', 'Ab pariatur Laudantium sit eiusmod iusto ut illo ipsum quisquam qui et ipsum voluptate consequatur Ad omnis ut magnam voluptates', 'Ipsum, vel vel in ipsa, labore adipisicing ex ad reiciendis saepe duis consequatur? Sint, animi, laboris qui laborum. Porro.', 'kawywiqyla', 'applicant'),
(15, 'cibicu@hotmail.com', '4ff0ee8da3b9806b18c877dbf29bbde50b5bd8e4dad7a3a725000feb82e8f1cd5b6adce6d23b9315f96922b0ae91fb0895d169945250ce8e1494aa9a9d801c', 'Bianca Cooke', '1986-12-06', 'female', 9044919787, 'Josiah Valencia', 'Perferendis aut nobis velit sed in unde in veniam hic ad', 'Fugit, eum et cillum reprehenderit voluptatibus molestias ea ex occaecat est deleniti.', 'cidahyz', 'applicant'),
(16, 'kutevat@gmail.com', '4ff0ee8da3b9806b18c877dbf29bbde50b5bd8e4dad7a3a725000feb82e8f1daaf87a7e266f01b305d935f5323a558cb0145ecc4357fd962e23eb2056f41cd', 'Stone Swanson', '1990-06-19', 'female', 9570560325, 'Elaine Ratliff', 'Rerum dignissimos dolore quam soluta quos veniam sequi sint odio porro', 'Iste sunt reiciendis excepteur minima omnis ut eiusmod sit, et qui non hic consequatur nihil fugiat.', 'morulojiv', 'applicant'),
(17, 'heqaquh@yahoo.com', '4ff0ee8da3b9806b18c877dbf29bbde50b5bd8e4dad7a3a725000feb82e8f19516fe181bd82a63c70a8b1fafaa0c5a89a6a93c12aea26ad82ffd1e31044a61', 'Beatrice Kinney', '2015-01-09', 'female', 9697189300, 'Karina Baldwin', 'Aliquid architecto iure dolores at rerum esse aut beatae sit amet nihil sunt consequuntur duis', 'Ut distinctio. Molestias minima ut officia voluptas est numquam aliquid nihil incididunt eu tempore, facere.', 'vybocywywa', 'applicant'),
(18, 'zydylycemi@gmail.com', '4ff0ee8da3b9806b18c877dbf29bbde50b5bd8e4dad7a3a725000feb82e8f19165f5880c6fee1ed8d161e80c2423e2e113cab3a8d9065a5d22203daf4babd7', 'Hillary Pierce', '1986-07-22', 'male', 9084825576, 'Quin Graves', 'Elit ad possimus exercitation ex ut ex sit aliquam', 'Voluptas dolore accusamus quia aliquid sunt, qui velit, beatae eius sed quisquam aut exercitation voluptates irure.', 'kykovo', 'applicant');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
 ADD PRIMARY KEY (`user_id`), ADD UNIQUE KEY `chellan_no` (`challan_no`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
 ADD PRIMARY KEY (`course_id`);

--
-- Indexes for table `course_dependencies`
--
ALTER TABLE `course_dependencies`
 ADD PRIMARY KEY (`dependency_id`);

--
-- Indexes for table `marks`
--
ALTER TABLE `marks`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `news_table`
--
ALTER TABLE `news_table`
 ADD PRIMARY KEY (`news_id`);

--
-- Indexes for table `religions`
--
ALTER TABLE `religions`
 ADD PRIMARY KEY (`religion_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`user_id`), ADD UNIQUE KEY `user_id` (`user_id`), ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
MODIFY `challan_no` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
MODIFY `course_id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `course_dependencies`
--
ALTER TABLE `course_dependencies`
MODIFY `dependency_id` bigint(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `marks`
--
ALTER TABLE `marks`
MODIFY `id` bigint(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=38;
--
-- AUTO_INCREMENT for table `news_table`
--
ALTER TABLE `news_table`
MODIFY `news_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `religions`
--
ALTER TABLE `religions`
MODIFY `religion_id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
MODIFY `id` bigint(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `user_id` bigint(11) NOT NULL AUTO_INCREMENT COMMENT 'primary key',AUTO_INCREMENT=19;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
