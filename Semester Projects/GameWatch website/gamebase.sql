-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 27, 2021 at 04:55 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gamebase`
--

CREATE DATABASE gamebase;
USE gamebase;

-- --------------------------------------------------------

--
-- Table structure for table `cards`
--

CREATE TABLE `cards` (
  `email` varchar(30) NOT NULL,
  `cardNo` varchar(19) NOT NULL,
  `expiry` date NOT NULL,
  `cvc` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cards`
--

INSERT INTO `cards` (`email`, `cardNo`, `expiry`, `cvc`) VALUES
('bassam123@gmail.com', '1234-1234-1234-1234', '2021-01-11', 1234),
('bassam123@gmail.com', '4321-4321-4321-4321', '2021-01-11', 1234);

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE `games` (
  `title` varchar(30) NOT NULL,
  `rdate` date NOT NULL,
  `rating` float NOT NULL,
  `type` varchar(10) NOT NULL,
  `descp` varchar(1000) NOT NULL,
  `imgloc` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`title`, `rdate`, `rating`, `type`, `descp`, `imgloc`) VALUES
('Call of duty warzone', '2019-08-23', 7.4, 'AAA', 'Call of Duty: Warzone is a free-to-play battle royale video game released on March 10, 2020, for PlayStation 4, Xbox One, and Microsoft Windows. ... Warzone allows online multiplayer combat among 150 players, although some limited-time game modes support 200 players.', '../IMG/G3.jpg'),
('Cuphead', '2017-09-29', 8.5, 'Indie', 'Cuphead is a classic run and gun action game heavily focused on boss battles. Inspired by cartoons of the 1930s, the visuals and audio are painstakingly created with the same techniques of the era, i.e. traditional hand drawn cel animation, watercolor backgrounds, and original jazz recordings.', '../IMG/G2.jpg'),
('Cyber punk 2077', '2020-12-10', 7.4, 'AAA', 'Cyberpunk is a subgenre of science fiction in a dystopian futuristic setting that tends to focus on a \"combination of low-life and high tech\" featuring advanced technological and scientific achievements, such as artificial intelligence and cybernetics, juxtaposed with a degree of breakdown or radical change in the social order.', '../IMG/G1.jpg'),
('Fall guys', '2020-08-04', 9.2, 'Indie', 'Fall Guys: Ultimate Knockout is a platformer battle royale game developed by Mediatonic and published by Devolver Digital. It released for Microsoft Windows and PlayStation 4 on 4 August 2020. The game draws inspiration from game shows like Takeshi\'s Castle, It\'s a Knockout and Wipeout, and playground games like tag and British bulldog.', '../IMG/G4.jpg'),
('Far Cry 6', '2021-05-25', 0, 'AAA', 'FAR CRY®6 thrusts players into the adrenaline-filled world of a modern-day guerrilla revolution. As dictator of Yara, Anton Castillo is intent on restoring his nation back to its former glory by any means, with his son, Diego, following in his bloody footsteps.', '../IMG/G8.jpg'),
('Fortnite', '2017-07-25', 7.1, 'AAA', 'Fortnite is a survival game where 100 players fight against each other in player versus player combat to be the last one standing. It is a fast-paced, action-packed game, not unlike The Hunger Games, where strategic thinking is a must in order to survive. There are an estimated 125 million players on Fortnite.', '../IMG/G10.jpg'),
('Hitman 3', '2021-01-20', 0, 'AAA', 'HITMAN 3 is the dramatic conclusion to the World of Assassination trilogy and takes players around the world on a globetrotting adventure to sprawling sandbox locations. Agent 47 returns as a ruthless professional for the most important contracts of his entire career.', '../IMG/G7.jpg'),
('PUBG', '2017-03-23', 8.9, 'Indie', 'Battlegrounds is a player versus player shooter game in which up to one hundred players fight in a battle royale, a type of large-scale last man standing deathmatch where players fight to remain the last alive. Players can choose to enter the match solo, duo, or with a small team of up to four people.', '../IMG/G9.jpg'),
('Red dead redemption 2', '2018-10-26', 9.8, 'AAA', 'The game is the third entry in the Red Dead series and is a prequel to the 2010 game Red Dead Redemption. The story is set in 1899 in a fictionalized representation of the Western, Midwestern, and Southern United States and follows outlaw Arthur Morgan, a member of the Van der Linde gang.', '../IMG/G5.jpg'),
('Rocket league', '2015-07-07', 9, 'AAA', 'Rocket League is a fantastical sport-based video game, developed by Psyonix (it\'s “soccer with cars”). It features a competitive game mode based on teamwork and outmaneuvering opponents. Players work with their team to advance the ball down the field, and score goals in their opponents\' net.', '../IMG/G6.jpg'),
('Rust', '2013-12-11', 9, 'AAA', 'Rust is a multiplayer game, so there will be other players trying to survive in the same way that you are. Unfortunately for you they can find you, kill you and take your stuff. Fortunately for you – you can kill them and take their stuff. Or maybe you can make friends and help each other survive. Rust’s world is harsh – so you might need to make friends to survive.', '../IMG/g11.png');

-- --------------------------------------------------------

--
-- Table structure for table `info`
--

CREATE TABLE `info` (
  `name` varchar(25) NOT NULL,
  `email` varchar(30) NOT NULL,
  `password` varchar(20) NOT NULL,
  `dob` date NOT NULL,
  `phone` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `info`
--

INSERT INTO `info` (`name`, `email`, `password`, `dob`, `phone`) VALUES
('Beast', 'bassam123@gmail.com', 'Abc123', '2021-01-10', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cards`
--
ALTER TABLE `cards`
  ADD PRIMARY KEY (`email`,`cardNo`);

--
-- Indexes for table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`title`),
  ADD UNIQUE KEY `imgloc` (`imgloc`);

--
-- Indexes for table `info`
--
ALTER TABLE `info`
  ADD PRIMARY KEY (`email`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cards`
--
ALTER TABLE `cards`
  ADD CONSTRAINT `cards_ibfk_1` FOREIGN KEY (`email`) REFERENCES `info` (`email`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
