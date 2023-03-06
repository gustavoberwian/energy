<?php
echo strtolower(explode('/', $_SERVER['SERVER_PROTOCOL'])[0]) . "://" . $_SERVER['SERVER_NAME'].'/';
phpinfo();