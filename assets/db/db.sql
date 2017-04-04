-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mar 04 Avril 2017 à 16:19
-- Version du serveur: 5.5.54-0ubuntu0.14.04.1
-- Version de PHP: 7.0.15-1+deb.sury.org~trusty+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `garage`
--

-- --------------------------------------------------------

--
-- Structure de la table `reparations`
--

CREATE TABLE IF NOT EXISTS `reparations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `intervention` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `vehicule_FK` int(11) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=78 ;

--
-- Contenu de la table `reparations`
--

INSERT INTO `reparations` (`id`, `intervention`, `description`, `vehicule_FK`, `date`) VALUES
(4, 'Intervention 1 Kawa', 'Description intervention 1 kawasaki', 55, '2017-03-22'),
(10, 'Intervention 4 DS', 'Intervention n° 4 sur le DD', 55, '2017-02-13'),
(11, 'Un petit kawa', 'Un café\r\n2 sucres', 71, '2017-03-27'),
(20, 'Touchy Intervention', 'Super réparation, élégante et tout !', 56, '2017-12-22'),
(21, 'Test', 'TESSSTTTTT', 55, '2017-02-24'),
(24, 'Intervention 1Testarossa', 'Super description', 71, '2017-01-24'),
(35, 'salut', 'salutos', 77, '2017-03-23'),
(38, 'test', 'abcdef', 79, '2017-03-15'),
(39, 'hihii', 'Yoyoyo', 80, '2017-03-22'),
(40, 'yup', 'yop', 77, '2017-03-18'),
(42, 'Yop', 'Yiiyiyiyiyi', 81, '2017-03-24'),
(43, 'Yep', 'Yup', 75, '2017-03-24'),
(45, 'test', 'test', 82, '2017-03-04'),
(46, 'test', 'testos', 83, '2017-03-18'),
(47, 'Supra cool', 'super !', 75, '2017-12-31'),
(48, 'Salut man', 'Comment ça va', 75, '2017-03-23'),
(49, 'Gros gros test', 'Super gros test', 75, '2017-03-20'),
(50, 'Testos', 'De sameros', 75, '2017-03-22'),
(51, 'Yep', 'Yup', 75, '2017-04-12'),
(52, 'Test', 'Test', 82, '2017-03-22'),
(53, 'Hey !', 'Salut', 82, '2017-03-29'),
(55, 'Zlatenerie', 'Super zlatanerie', 65, '2017-03-22'),
(56, 'Test', 'De dingue', 75, '2017-03-23'),
(57, 'yihi', 'yaha', 75, '2017-03-10'),
(58, 'yo', 'yo', 75, '2017-12-31'),
(59, 'Intervention 4 DS', 'fjsqmd', 75, '2017-03-14'),
(60, 'yip', 'yop', 75, '2017-12-31'),
(61, 'super l', 'super l&#039;intervention', 75, '2017-03-22'),
(62, 'super l&#039;intervention', 'super l&#039;intervention', 75, '2017-02-16'),
(63, 'super l''intervention', 'super l''intervention', 81, '2017-03-15'),
(64, 'super l''intervention', 'super l''intervention', 75, '2017-03-17'),
(65, 'yip', 'cool !', 75, '2017-12-31'),
(66, 'So nice', 'cool !', 233, '2017-12-31'),
(68, 'grosse rep', 'grosse grosse rep', 79, '2017-03-28'),
(70, 'le nouveau son', 'de manau', 84, '2017-03-08'),
(71, 'Salut le toucan', 'tu boumes ?', 85, '2017-03-14'),
(72, 'hey', 'hey', 78, '2017-03-17'),
(74, 'yip', 'yop', 81, '2017-03-23'),
(75, 'Cal', 'Andre', 231, '2017-03-24'),
(76, 'jfdqslm', 'mldqs', 237, '2017-03-08'),
(77, 'mfdlq', 'mldmqs', 237, '2017-03-16');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `login`, `password`) VALUES
(1, 'tim', 'isfce'),
(2, 'bob', 'ISFCE1040');

-- --------------------------------------------------------

--
-- Structure de la table `vehicules`
--

CREATE TABLE IF NOT EXISTS `vehicules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `numero_chassis` varchar(255) NOT NULL,
  `plaque` varchar(255) NOT NULL,
  `marque` varchar(255) NOT NULL,
  `modele` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=239 ;

--
-- Contenu de la table `vehicules`
--

INSERT INTO `vehicules` (`id`, `numero_chassis`, `plaque`, `marque`, `modele`, `type`) VALUES
(55, '12345-13546-89879-979746', '1-TIM-666', 'Citroën', 'C3D', 'Camion'),
(56, '12345-12324-68753-543586', '1DFQKLJFDQ', 'Honda', 'CR-ZX', 'Moto'),
(63, '12345-12345-12345-123456', '1-ABC-123', 'Lada', 'Super Vieille', 'Camion'),
(65, '11111-11111-11111-111111', '1-XXX-666', 'Zlatan', 'Ibrahomivic', 'Zlatan'),
(69, '65879-98745-65672-684354', '0-ABC-542', 'John Deer', 'Tractor II', 'Tracteur'),
(71, '98765-43356-48987-545639', '1-QSD-432', 'Tesla', 'Thunder', 'Moto'),
(75, '11111-11111-11111-456789', '1-ABC-123', 'Abart', 'Fox', 'Zlatan'),
(76, '67894-65498-45678-654321', '1-MKX-123', 'Yamaha', 'XRK-456', 'Biplan'),
(78, '12345-12345-12345-123456', '1-BKR-648', 'Mazda', '323', 'Tricycle'),
(79, '98745-98745-98745-654321', '0-DEF-456', 'Chevrolet', 'Corvette', 'Voiture'),
(81, '45698-75654-68978-964564', '0-KDS-487', 'Alfa Romeo', 'Dona', 'Voiture'),
(82, '45687-98765-43213-654987', '1-ECU-033', 'Renault', 'Kangoo', 'Voiture'),
(83, '12345-12345-12345-123456', '0-DEF-456', 'Jeep', 'Cherokee', 'Camion'),
(85, '45678-45678-45678-456789', '0-ABC-456', 'bob "le toucan"', 'salut', 'Biplan'),
(230, '12465-45645-15648-456786', '1-ABC-324', 'Ferrari', 'Testarossa', 'Bolide'),
(231, '12465-45645-15648-456785', '1-ABC-323', 'Bentley', 'Phantom', 'Bolide'),
(232, '12465-45645-15648-456784', '1-ABC-322', 'Porsche', 'Cayenne', 'Bolide'),
(233, '12465-45645-15648-456783', '1-JKL-321', 'Jaguar', 'XRS', 'Bolide'),
(234, '12465-45645-15648-456782', '1-ABC-320', 'BMW', 'Serie 3', 'Bolide'),
(235, '12465-45645-15648-456780', '1-ABC-318', 'VW', 'Lupo', 'Tacot'),
(236, '12345-12345-12345-645646', '1-ABC-456', 'dsq', 'fdlmq', 'Voiture'),
(238, '12345-12345-13455-234567', '1-ABC-456', 'TEST FINAL', 'TESSSST', 'Camion');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
