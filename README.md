# toPdf
Generate pdf from html code using Phalcon Framework. This repository uses wkhtmltopdf php wrapper by [@mikehaertl](https://github.com/mikehaertl/phpwkhtmltopdf).

First, you have to download the installer of wkhtmltopdf from [here](https://wkhtmltopdf.org/downloads.html) and then set the binary path to the config.php file.

Then you just need to change base_url and whitelist_ip in the app/config/config.php.

You're ready to go, just host this project on your server and give the POST <html> request to this project link, this will give you either a string of binary pdf, inline pdf or a downloaded pdf, or if you want to save the pdf file to your project server.
