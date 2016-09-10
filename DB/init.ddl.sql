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
);

CREATE TABLE IF NOT EXISTS `books` (
  `book_id` int(11) NOT NULL AUTO_INCREMENT,
  `creator_id` int(11) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_date` datetime NOT NULL,
  `book_category_id` tinyint NOT NULL,
  `active_book_ind` tinyint NOT NULL default 1,
  `won_round_id` int(11),
  /* below are book info fields */
  `title` varchar(1000),
  `author` varchar(1000),
  `ref_link` varchar(1000),
  PRIMARY KEY (`book_id`)
) DEFAULT CHARSET=utf8;
