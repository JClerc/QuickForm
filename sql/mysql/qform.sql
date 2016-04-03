-- phpMyAdmin SQL Dump
-- version 4.4.10
-- http://www.phpmyadmin.net
--
-- Host: localhost:8889
-- Generation Time: Apr 02, 2016 at 08:38 PM
-- Server version: 5.5.42
-- PHP Version: 7.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `qform`
--

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE `answers` (
  `id` int(11) NOT NULL,
  `fill_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `content` varchar(255) NOT NULL,
  `choice_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `answers`
--

INSERT INTO `answers` (`id`, `fill_id`, `question_id`, `content`, `choice_id`) VALUES
(1, 1, 1, '', 1),
(2, 1, 5, '', 6),
(3, 1, 5, '', 8),
(4, 1, 3, 'Steak frites', 0),
(5, 2, 1, '', 1),
(6, 2, 5, '', 7),
(7, 2, 5, '', 8),
(8, 2, 3, 'Pizza', 0),
(9, 2, 4, 'France, Canada, Japon, Australie', 0),
(10, 3, 1, '', 2),
(11, 3, 5, '', 6),
(12, 3, 5, '', 9),
(13, 3, 3, 'Salade', 0),
(14, 3, 4, 'Espagne et Italie', 0),
(15, 4, 1, '', 1),
(16, 4, 5, '', 6),
(17, 4, 5, '', 8),
(18, 4, 3, 'Sushis', 0),
(19, 5, 1, '', 2),
(20, 5, 5, '', 9),
(21, 6, 1, '', 1),
(22, 6, 5, '', 6),
(23, 6, 5, '', 7),
(24, 6, 3, 'Gratin daufinois', 0),
(25, 6, 4, 'Finlande et Danemark', 0),
(26, 7, 1, '', 1),
(27, 7, 5, '', 7),
(28, 7, 3, 'Tagliatelle au poulet', 0),
(29, 7, 4, 'USA, Mexique et Qatar', 0),
(30, 8, 1, '', 2),
(31, 8, 5, '', 6),
(32, 8, 5, '', 8),
(33, 8, 3, 'Poisson pané', 0),
(34, 8, 4, 'Aucun', 0),
(35, 9, 1, '', 2),
(36, 9, 5, '', 6),
(37, 9, 3, 'McDonalds', 0),
(38, 9, 4, 'Colombie, Afrique du Sud et Chine', 0);

-- --------------------------------------------------------

--
-- Table structure for table `choices`
--

CREATE TABLE `choices` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `content` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `choices`
--

INSERT INTO `choices` (`id`, `question_id`, `content`) VALUES
(1, 1, 'Un homme'),
(2, 1, 'Une femme'),
(6, 5, 'Faire du sport'),
(7, 5, 'Regarder la TV'),
(8, 5, 'Surfer sur internet'),
(9, 5, 'Lire un livre');

-- --------------------------------------------------------

--
-- Table structure for table `fills`
--

CREATE TABLE `fills` (
  `id` int(11) NOT NULL,
  `form_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `fills`
--

INSERT INTO `fills` (`id`, `form_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1);

-- --------------------------------------------------------

--
-- Table structure for table `forms`
--

CREATE TABLE `forms` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `updated` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `forms`
--

INSERT INTO `forms` (`id`, `title`, `desc`, `email`, `updated`) VALUES
(1, 'Test de personnalité', 'Quel genre de personne es tu ?', 'qform.service@gmail.com', 1459622256);

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `form_id` int(11) NOT NULL,
  `type` varchar(64) NOT NULL,
  `mandatory` tinyint(1) NOT NULL,
  `phrase` varchar(255) NOT NULL,
  `position` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `form_id`, `type`, `mandatory`, `phrase`, `position`) VALUES
(1, 1, 'radio', 1, 'Vous êtes..', 0),
(3, 1, 'input', 0, 'Quel est votre repas préféré ?', 2),
(4, 1, 'textarea', 0, 'Quels pays avez vous visités ?', 3),
(5, 1, 'checkbox', 1, 'Vous aimez..', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `choices`
--
ALTER TABLE `choices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fills`
--
ALTER TABLE `fills`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `forms`
--
ALTER TABLE `forms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `answers`
--
ALTER TABLE `answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT for table `choices`
--
ALTER TABLE `choices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `fills`
--
ALTER TABLE `fills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `forms`
--
ALTER TABLE `forms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
