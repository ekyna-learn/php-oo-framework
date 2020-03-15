<?php

return [
    "INSERT INTO `user` (`id`, `email`, `password`, `name`, `birthday`, `active`) VALUES ".
        "(1, 'john.doe@example.org', '6c074fa94c98638dfe3e3b74240573eb128b3d16', 'John Doe', '2000-01-01', 1)",
    "INSERT INTO `user` (`id`, `email`, `password`, `name`, `birthday`, `active`) VALUES ".
        "(2, 'jane.doe@example.org', '06d213088a72f4c1ac947c6f3d9ddd321650ebfb', 'Jane Doe', NULL, 0);"
];
