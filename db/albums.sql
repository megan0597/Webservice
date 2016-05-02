-- Adminer 4.2.3 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `albums`;
CREATE TABLE `albums` (
  `Title` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `Artist` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `ReleaseDate` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `Id` int(10) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `albums` (`Title`, `Artist`, `ReleaseDate`, `Id`) VALUES
('25',	'Adele',	'2015',	1),
('The 1975',	'The 1975',	'2013',	2),
('Watermakers',	'Blof',	'2000',	3),
('Legend',	'Bob Marley &amp; The Wailers',	'1984',	4),
('Badlands',	'Halsey',	'2015',	5),
('Shades Of Black',	'Kovacs',	'2015',	6),
('The Truth About Love',	'Pink',	'2012',	7),
('Paramore',	'Paramore',	'2013',	8),
('Heartthrob',	'Tegan and Sara',	'2013',	9),
('The Joshua Tree',	'U2',	'1987',	10),
('The Astonishing',	'Dream Theater',	'2016',	126),
('Tubular Bells',	'Mike Oldfield',	'1973',	127),
('A Night at the Opera',	'Queen',	'1975',	128),
('Metropolis Part 2: Scenes From a Memory',	'Dream Theater',	'1999',	129),
('Ghost Reveries',	'Opeth',	'2005',	130),
('Sunset on the Golden Age',	'Alestorm',	'2014',	131),
('Greatest Hits',	'Megan Bosch',	'2016',	132),
('Gouwe Ouwe Kaskrakers',	'Hannes van der Apfelschwa',	'1954',	133),
('Met een toeter',	'Koos van Kareltjes',	'1971',	134),
('Op de fiets naar Katwijk',	'Annette van den Zon',	'1994',	135),
('Ome Harry is Jarig, en 43 andere klappers',	'George van de Waal',	'2003',	136),
('Todos juntos para trompeta',	'Chicos Calientes',	'2009',	137),
('La mÃºsica de la guitarra a la guitarra',	'Chicos Calientes',	'1991',	138),
('Con el cubo y la tapa',	'Chicos Calientes',	'1999',	141),
('La cerveza es mejor que la escuela',	'Chicos Calientes',	'2002',	142),
('Shawarma debajo de la axila',	'Chicos Calientes',	'2004',	143),
('El grupo tiene una pelea y se odian',	'Chicos Calientes',	'2013',	144),
('Mi sombrero estÃ¡ en llamas',	'Chicos Calientes',	'2016',	145),
('Check Webservice - 8192968',	'Check Webservice - 8192968',	'Check Webservice - 8192968',	172);

-- 2016-05-02 22:11:51
