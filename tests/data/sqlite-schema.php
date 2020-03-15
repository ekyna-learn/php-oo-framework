<?php

return [
    "CREATE TABLE `user` (" .
        "`id` INTEGER PRIMARY KEY AUTOINCREMENT, " .
        "`email` varchar(255) NOT NULL, " .
        "`password` varchar(64) NOT NULL, " .
        "`name` varchar(64) NOT NULL, " .
        "`birthday` date DEFAULT NULL, " .
        "`active` int NOT NULL DEFAULT '0' " .
    ");",
];
