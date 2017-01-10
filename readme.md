A task:
=====================

Develop a web application to upload files to a folder with the visualization of the file structure on a separate page
---
The application consists of 2 sections:
---
CRUD editor files (backend)
page output the resulting file structure (frontend)

**Remarks:**
---
Each uploaded file must be physically popact a folder
in the same folder should be no more than 10 files, the names must be unique
![screenshot of tree-structure](https://github.com/mslobodyanyuk/KTTsoft-test/master/images/imageTree.png)

---
authorization, the login is not required

visual interface and a choice of framework / libraries is left to the discretion of the programmer

The database must contain an exact copy of the files in a folder structure, and change with the addition / deletion of files
![screenshot of table files](https://github.com/mslobodyanyuk/KTTsoft-test/master/images/imageTable.png)

by clicking on the file name `(frontend)` you can see / download
![screenshot of file contents](https://github.com/mslobodyanyuk/KTTsoft-test/master/images/imageFileContents.png)
loading only text files

**MySQL database**

    Category | _
    - fil1-1
    - fil1-2
    - file1
    - File2 | _



After the composer install, gives out when you run the project:
=====================

***Hardcoding // - _(replace_ _bindShared();_ _for_ _singleton();_ _in_ _line_ _36_ _and_ _49)_***
---
>FatalErrorException in HtmlServiceProvider.php
>Call to undefined method illuminate / Foundation / Application :: bindShared ()
>- Replace: bindShared () with singleton () on line no: 36 and 49
>(- Must be replaced bindShared () on the singleton () in line 36 and 49)
>(Deprecated)
---
[- This component bug, fix it, but here is the solution,](http://stackoverflow.com/questions/31250211/call-to-undefined-method-illuminate-foundation-applicationbindshared)
Depending replaced with illuminate / html to laravelcollective / html
and to establish if there is no component of the composer require laravelcollective / html
(In PHPStorm Terminal> composer require laravelcollective / html)

namely, `in config / app.php:`
-----------------------------------
```php
'Providers' => [
/ *
* Laravel Framework Service Providers ...
* /
// 'Illuminate \ Html \ HtmlServiceProvider',
// !!! bindShared () deprecated error
Collective \ Html \ HtmlServiceProvider :: class,

'Aliases' => [
/// ???
// 'Form' => 'Illuminate \ Html \ FormFacade',
// 'Html' => 'Illuminate \ Html \ HtmlFacade',
// !!! bindShared () deprecated error
'Form' => Collective \ Html \ FormFacade :: class,
'Html' => Collective \ Html \ HtmlFacade :: class,
```
---
1. The first edit composer.json -> replace illuminate / html to laravelcollective / html
2. Then in the terminal> composer require laravelcollective / html (the team performed in the project folder - composer.json added)





Features of "unfolding" of the project (kttsoft) for Ubuntu.
=====================
_I Do:_
`composer install`
-----------------------------------
**Problem 1**
-----------------------------------
```php
    - Installation request for laravel / framework v5.2.13 -> satisfiable by laravel / framework [v5.2.13].
    - Laravel / framework v5.2.13 requires ext-mbstring * -> the requested PHP extension mbstring is missing from your system.
```
**Problem 2**
-----------------------------------
```php
    - Laravel / framework v5.2.13 requires ext-mbstring * -> the requested PHP extension mbstring is missing from your system.
    - Laravelcollective / html v5.2.4 requires illuminate / http 5.2 * -.> Satisfiable by laravel / framework [v5.2.13].
    - Installation request for laravelcollective / html v5.2.4 -> satisfiable by laravelcollective / html [v5.2.4].
```
apt-get install php-mbstring
[Now something similar write -](http://askubuntu.com/questions/764782/install-laravel-5-on-ubuntu-16-04)
and extension in php.ini could not be
To enable extensions, verify that they are enabled in those .ini files:
    `- /etc/php/5.6/cli/php.ini ...`

write that it is necessary to establish, as part of the included in the package or separately, and uncomment the connect library in php.ini
+ <http://stackoverflow.com/questions/36979019/getting-error-while-update-composer>

3. .env database configuration file.

4. `PHPStorm Terminal> php artisan migrate`
5. `> php artisan db: seed (start siderite, fixtures)`
although it is possible and without Sidera, upl folder is created and the software.
6. configure the host.