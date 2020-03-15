<?php

return [
    "DROP DATABASE IF EXISTS `{$_ENV['DB_NAME']}`;",
    "CREATE DATABASE `{$_ENV['DB_NAME']}` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;",
    "USE `{$_ENV['DB_NAME']}`;",
    "CREATE TABLE `user` (" .
        "`id` int UNSIGNED NOT NULL, " .
        "`email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, " .
        "`password` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NULL, " .
        "`name` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, " .
        "`birthday` date DEFAULT NULL, " .
        "`active` int NOT NULL DEFAULT '0' " .
    ") ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;",
    "ALTER TABLE `user` ADD PRIMARY KEY (`id`), ADD KEY `email` (`email`);",
    "ALTER TABLE `user` MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;"
];
