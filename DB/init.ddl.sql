/*create database*/

/*create table*/
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `active_ind` tinyint NOT NULL default 0,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_name` (`user_name`),
  UNIQUE KEY `email` (`email`)
) DEFAULT CHARSET=utf8;;

CREATE TABLE IF NOT EXISTS `books` (
  `book_id` int(11) NOT NULL AUTO_INCREMENT,
  `creator_id` int(11) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `book_category_id` tinyint NOT NULL, /*1 fiction, 2 non-fiction*/
  `active_book_ind` tinyint NOT NULL default 1,
  `won_round_id` int(11),
  /* below are book info fields */
  `title` varchar(1000),
  `author` varchar(1000),
  `ref_link` varchar(1000),
  PRIMARY KEY (`book_id`)
) DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `rounds` (
  `round_id` int(11) NOT NULL AUTO_INCREMENT,
  `creator_id` int(11) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `book_category_id` int(11) NOT NULL,
  `round_status` tinyint NOT NULL default 1, /*1 new, 2 closed*/
  `winner_book_id` int(11),
  PRIMARY KEY (`round_id`)
);

CREATE TABLE IF NOT EXISTS `round_book_mapping` (
  `round_book_map_id` int(11) NOT NULL AUTO_INCREMENT,
  `book_id` int(11) NOT NULL,
  `round_id` int(11) NOT NULL,
  `creator_id` int(11) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `total_vote` decimal(10,2) default 0.0,
  PRIMARY KEY (`round_book_map_id`)
);
