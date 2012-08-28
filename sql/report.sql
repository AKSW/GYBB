
CREATE TABLE IF NOT EXISTS `report` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `biketype` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL,
  `dateoftheft` date NOT NULL,
  `placeoftheft` varchar(255) NOT NULL,
  `description` text,
  `components` text,
  `price` decimal(17,2) DEFAULT NULL,
  `manufacturer` varchar(255) DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `codednumber` varchar(255) DEFAULT NULL,
  `police` varchar(255) DEFAULT NULL,
  `user` int(11) NOT NULL,
  `creationDate` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8  ;
