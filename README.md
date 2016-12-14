# README #

This README describes the set-up and guidelines for Team Null's project for CSCI-320.

### Set-Up ###

* Required Software
    * We are currently testing this on WAMPServer 3.0.6 with:
        * PHP 7.0.10
        * Apache 2.4.23
        * MySQL 5.7.14
    * Use a recent version of a standard web browser to ensure all features function properly.
* Configuration
    * Place all files in a directory on the server.
    * Run `setup.sql` on the database.
    * Open `localhost/path` in a web browser where `path` is the directory on the server this project is stored in.
* Deployment Instructions
    * We have yet to establish a remote server to use.

### Contribution Guidelines ###

* Modifications
    * Check with other users before modifying code to ensure no duplicated effort.
* Style
    * General
        * All files are encoded in UTF-8.
		* Indentation is 1 tab.
		* Variables should use `separated_by_underscores` unless otherwise specified.
		* Line wrapping is not required.
    * HTML
        * HTML validates as HTML5.
		* Follow [Google HTML/CSS Style Guide](https://google.github.io/styleguide/htmlcssguide.xml) unless it conflicts with the above.
    * CSS
        * CSS validates as CSS3.
		* Follow [Google HTML/CSS Style Guide](https://google.github.io/styleguide/htmlcssguide.xml) unless it conflicts with the above.
    * MySQL
        * All keywords are `UPPERCASE`.
        * Table and attribute names are `separated_by_underscores`.
		* Table names are plural, as a large number of our table names are reserved words in the singular.
	* PHP
	    * Follow [PHP Standards Recommendations](http://www.php-fig.org/psr/) unless it conflicts with the above.
	* JavaScript
	    * All JavaScript in external files and `<script>` tags should begin with `"use strict";`.
	    * Follow [Google JavaScript Style Guide](https://google.github.io/styleguide/javascriptguide.xml) unless it conflicts with the above.
* Other guidelines
