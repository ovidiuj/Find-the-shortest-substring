# Find-the-shortest-substring

The application provides a script that receives and processes an input stream consisting of single characters.

The stream is of unknown and possibly very large length and the script should work regardless of the size of the input.
The script should take a set of search characters as parameters and using those determine the length of the shortest substring of s that contains all search characters.

As for receiving the data the tool should work with three kinds of inputs:
* Values piped directly into the process using `cat input.txt | php get-shortest-sub-string.php EOT `
* Values generated using a secure random source from within the language
* Values loaded via HTTP using the API provided by http://www.random.org/clients/http/

## Example
Given the string

``` THEQUICKBROWNFOXJUMPSOVERTHELAZYDOG ```

and the search characters

``` E O T ```

The length of the shortest substring containing all search characters is **5**

The match would be here

`THEQUICKBROWNFOXJUMPS`**`OVERT`**`HELAZYDOG`

## Installation & Usage

* run ``` composer update ```
* run ``` vendor/bin/phpunit ```
* run ``` cat input.txt | php get-shortest-sub-string.php EOT ``` or ``` php get-shortest-sub-string.php EOT THEQUICKBROWNFOXJUMPSOVERTHELAZYDOG ```
* go to: ``` /http-generator/EOT/ ```
* go to: ``` /random-generator/300/EOT ``` where **300** is the length of generated string and **EOT** is the set of search characters