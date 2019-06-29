# Blog
Blog is the fifth project of my studies.
Project made with PHP

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/cee0aae2dc60411585ccc18279ff2374)](https://www.codacy.com/app/MaxiKata/Blog?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=MaxiKata/Blog&amp;utm_campaign=Badge_Grade)
![OenClassRooms](https://img.shields.io/badge/OpenClassRooms-DA_PHP/SF-blue.svg)
![Project](https://img.shields.io/badge/Project-5-blue.svg)
![PHP](https://img.shields.io/badge/PHP-7.2-blue.svg)

## Intallation

Installation of composer => [https://getcomposer.org/download/](https://getcomposer.org/download/)
Once composer installed, launch terminal command : "*composer* *dump-autoload* *-o*" in order to load all classes


For local development, you should install tracy debugger. 
Get tracy debugger => [https://packagist.org/packages/tracy/tracy](https://packagist.org/packages/tracy/tracy)
If you install on live server, tracy MUST be comment in Public/indepx.php lines 8 & 10. Otherwise you would get "**Error 500**"

Create file to connect your DataBase at the root of App directory with the following information :
-   namespace App;
-   use \PDO;
-   class Manager

And set your PDO connection normaly in the class

## Overview
This project is a blog with simple actions:
-   Website Type: **Blog**
-   Users Profile: **User** or **Admin**
-   Article Status: **Published** or **Draft**
-   Comment Status: **Published** or **Modified**