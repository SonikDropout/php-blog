DROP DATABASE IF EXISTS php_blog;
CREATE DATABASE php_blog DEFAULT CHARACTER SET utf8 ;

GRANT ALL ON php_blog.* TO 'blog-admin'@'localhost' IDENTIFIED BY 'tutorial';
GRANT ALL ON php_blog.* TO 'blog-admin'@'127.0.0.1' IDENTIFIED BY 'tutorial';

USE php_blog;

CREATE TABLE `users` (
  `id` INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(128) NOT NULL,
  `email` VARCHAR(128) NOT NULL,
  `role` enum('Author', 'Admin') DEFAULT NULL,
  `password` VARCHAR(128) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `posts` (
  `id` INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `user_id` INTEGER DEFAULT NULL,
  `title` VARCHAR(128) NOT NULL,
  `slug` VARCHAR(128) NOT NULL UNIQUE,
  `views` INTEGER NOT NULL DEFAULT '0',
  `tags` VARCHAR(255) NULL,
  `image` VARCHAR(128) NOT NULL,
  `body` TEXT NOT NULL,
  `published` TINYINT NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  FOREIGN KEY (`user_id`)
  REFERENCES `users` (`id`)
  ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `topics` (
  `id` INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(128) NOT NULL,
  `slug` VARCHAR(128) NOT NULL UNIQUE
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `post_topic` (
  `id` INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `post_id` INTEGER NOT NULL,
  `topic_id` INTEGER NOT NULL,
  FOREIGN KEY (`post_id`)
  REFERENCES `posts` (`id`)
  ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`topic_id`)
  REFERENCES `topics` (`id`)
  ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `comments` (
	`id` integer NOT null AUTO_INCREMENT PRIMARY KEY,
  `user_id` integer NOT null,
  `post_id` integer NOT null,
  `body` TINYTEXT NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id)
  REFERENCES users (id)
  ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (post_id)
  REFERENCES posts (id)
  ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=INNODB DEFAULT charset=utf8;


INSERT INTO `users` (`username`, `email`, `role`, `password`) VALUES
  ('admin','admin@example.com', 'Admin', '8C6976E5B5410415BDE908BD4DEE15DFB167A9C873FC4BB8A81F6F2AB448A918'),
  ('author','author@example.com', 'Author', '8C6976E5B5410415BDE908BD4DEE15DFB167A9C873FC4BB8A81F6F2AB448A918'),
  ('user','user@example.com', NULL, '8C6976E5B5410415BDE908BD4DEE15DFB167A9C873FC4BB8A81F6F2AB448A918');

INSERT INTO `posts` (`user_id`, `title`, `slug`, `image`, `body`, `published`) VALUES
  (1, '5 Habits that can improve your life', '5-habits-that-can-improve-your-life', 'banner.jpg', 'Read every day', 1),
  (1, 'Second post on LifeBlog', 'second-post-on-lifeblog', 'banner.jpg', 'This is the body of the second post on this site', 0);

INSERT INTO `topics` (`name`, `slug`) VALUES
  ('Inspiration', 'inspiration'),
  ('Motivation', 'motivation'),
  ('Diary', 'diary');

INSERT INTO `post_topic` (`post_id`, `topic_id`) VALUES
  (1, 1),
  (2, 2);