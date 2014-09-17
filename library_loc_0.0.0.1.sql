-- phpMyAdmin SQL Dump
-- version 4.0.6deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 17, 2014 at 07:43 AM
-- Server version: 5.5.37-0ubuntu0.13.10.1
-- PHP Version: 5.5.3-1ubuntu2.6

/*DATA BASE VERSION 0.0.0.1*/

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `library.loc`
--

-- --------------------------------------------------------

--
-- Table structure for table `authors`
--

CREATE TABLE IF NOT EXISTS `authors` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `author_name` varchar(255) NOT NULL,
  `author_creation_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `authors_books_relation`
--

CREATE TABLE IF NOT EXISTS `authors_books_relation` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `book_id` int(10) NOT NULL,
  `author_id` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `book_id` (`book_id`,`author_id`),
  KEY `rel_auth_id` (`author_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE IF NOT EXISTS `books` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `count_books` int(5) NOT NULL,
  `reserved_count` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `readership`
--

CREATE TABLE IF NOT EXISTS `readership` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `reader_name` varchar(255) NOT NULL,
  `reader_creation_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `readers_books_relation`
--

CREATE TABLE IF NOT EXISTS `readers_books_relation` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `book_id` int(10) NOT NULL,
  `reader_id` int(10) NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `count_books` int(5) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `reader_rel_book_id` (`book_id`),
  KEY `reader_rel_read_id` (`reader_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `authors_books_relation`
--
ALTER TABLE `authors_books_relation`
  ADD CONSTRAINT `rel_book_id` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rel_auth_id` FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `readers_books_relation`
--
ALTER TABLE `readers_books_relation`
  ADD CONSTRAINT `reader_rel_book_id` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reader_rel_read_id` FOREIGN KEY (`reader_id`) REFERENCES `readership` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;



CREATE TABLE IF NOT EXISTS `search` (
`book_id` int(11) DEFAULT NULL,
`author_id` int(11) DEFAULT NULL,
`text` text,
FULLTEXT KEY `IX_search_text` (`text`)
) ENGINE=MyISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

/*trigger for search*/
DROP TRIGGER IF EXISTS insert_book ;
delimiter //
CREATE
TRIGGER `insert_book`
AFTER INSERT
ON books
FOR EACH ROW
BEGIN
INSERT INTO search (`book_id`, `text`) VALUES(NEW.`id`, LOWER(NEW.`name` ));
END; //
delimiter ;

/*trigger for search*/
DROP TRIGGER IF EXISTS insert_book_autor ;
delimiter //
CREATE
TRIGGER `insert_book_autor`
AFTER INSERT
ON authors_books_relation
FOR EACH ROW
BEGIN
INSERT INTO search (`book_id`,`author_id`, `text`) SELECT NEW.book_id, NEW.author_id, author_name FROM `authors` WHERE `id`=NEW.author_id;
END; //
delimiter ;

/*trigger for search*/
DROP TRIGGER IF EXISTS update_book ;
delimiter //
CREATE
TRIGGER `update_book`
AFTER UPDATE
ON books
FOR EACH ROW
BEGIN
IF NEW.name!=OLD.name THEN
UPDATE search SET `text`= NEW.name WHERE author_id IS NULL AND book_id = NEW.id;
END IF;
END; //
delimiter ;

/*trigger for search*/
DROP TRIGGER IF EXISTS update_book_author ;
delimiter //
CREATE
TRIGGER `update_book_author`
AFTER UPDATE
ON authors
FOR EACH ROW
BEGIN
IF NEW.author_name!=OLD.author_name THEN
UPDATE search SET `text`= NEW.author_name WHERE author_id = NEW.id;
END IF;
END; //
delimiter ;

/*trigger for search*/
DROP TRIGGER IF EXISTS delete_rel ;
delimiter //
CREATE
TRIGGER `delete_rel`
AFTER DELETE
ON authors_books_relation
FOR EACH ROW
BEGIN
DELETE FROM search WHERE author_id = OLD.author_id AND book_id=OLD.book_id;
END; //
delimiter ;

/*trigger for search*/
DROP TRIGGER IF EXISTS delete_book ;
delimiter //
CREATE
TRIGGER `delete_book`
AFTER DELETE
ON books
FOR EACH ROW
BEGIN
DELETE FROM search WHERE book_id=OLD.id;
END; //
delimiter ;

/*trigger for search*/
DROP TRIGGER IF EXISTS delete_author ;
delimiter //
CREATE
TRIGGER `delete_author`
AFTER DELETE
ON authors
FOR EACH ROW
BEGIN
DELETE FROM search WHERE author_id = OLD.id;
END; //
delimiter ;


/*trigger for change reserved book count*/
DROP TRIGGER IF EXISTS insert_readers_book_rel ;
delimiter //
CREATE
TRIGGER `insert_readers_book_rel`
AFTER INSERT
ON readers_books_relation
FOR EACH ROW
BEGIN
Update books SET reserved_count=reserved_count+NEW.count_books WHERE books.id = NEW.book_id;
END; //
delimiter ;

/*trigger for change reserved book count*/
DROP TRIGGER IF EXISTS delete_readers_book_rel ;
delimiter //
CREATE
TRIGGER `delete_readers_book_rel`
AFTER DELETE
ON readers_books_relation
FOR EACH ROW
BEGIN
Update books SET reserved_count=reserved_count-OLD.count_books WHERE books.id = OLD.book_id;
END; //
delimiter ;

/*trigger for change reserved book count*/

DROP TRIGGER IF EXISTS delete_readers ;
delimiter //
CREATE
TRIGGER `delete_readers`
BEFORE DELETE
ON readership
FOR EACH ROW

BEGIN

declare done int default false;
declare _count_book int;
declare _book_id int;
declare readers_cursor cursor for select book_id,count_books from readers_books_relation where reader_id=OLD.id;
declare continue handler for not found set done = true;
open readers_cursor;

my_loop: loop

set done = false;
fetch readers_cursor into _book_id,_count_book;

if done then
leave my_loop;
end if;

Update books SET reserved_count=reserved_count-_count_book WHERE books.id = _book_id;
end loop my_loop;

close readers_cursor;


END; //
delimiter ;
