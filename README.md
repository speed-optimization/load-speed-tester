# Load Speed Tester

Load Speed Tester is a set of scripts to measure the load speed of a URL.


## Getting Started

1. Copy `config/config.ini.dist` to `config/config.ini`.

2. Obtain a [Google API key](https://code.google.com/apis/console/) for [Page Speed Insights](https://developers.google.com/speed/docs/insights/), if you do not have one.

3. Enter your Google API key in `config.ini` at the key `googleApiKey`.

4. Download and install [PhantomJs](http://phantomjs.org/).

5. Enter the full path to PhantomJs in `config.ini` at the key `phantomJsExec`.


## Usage

Load Speed Tester ships with the following scripts:

### `load-speed.php`

Loads the specified URL the specified number of times and returns the load time (onLoad) of each run and four averages.

### `load-speed-analysis.php`

Does the same as above, but in addition, sends the URL under test to Page Speed Insights to get a report from Google in addition.

To find out how they work, simply pass `--help` or `-h` at the command line. For example:

- `php load-speed.php --help`
- `php load-speed-analysis.php --help`

The output of `load-speed-analysis.php` includes the section _Suggested Improvements_. To learn more about these suggestions, please take a look at the [PageSpeed Insights Rules](https://developers.google.com/speed/docs/insights/rules).

Please note that this output is intended as a summary only. To get detailed suggestions on each rule, please rerun the test by entering the URL of the page under test at [PageSpeed Online](https://developers.google.com/speed/pagespeed/insights).

## Examples

- `php load-speed.php --url=http://www.iana.org/domains/example/`
- `php load-speed.php --url=http://www.iana.org/domains/example/ --rounds=10`

- `php load-speed-analysis.php --url=http://www.iana.org/domains/example/`
- `php load-speed-analysis.php --url=http://www.iana.org/domains/example/ --rounds=10`
- `php load-speed-analysis.php --url=http://www.iana.org/domains/example/ --strategy=desktop --rounds=10`


## Sample Output

### `load-speed.php`

    $ php load-speed.php --url=http://www.iana.org/domains/example/

    Load Speed Results for:
      http://www.iana.org/domains/example/

    Rounds (5)
    +- 1.............................................2791 ms
    +- 2.............................................2432 ms
    +- 3.............................................2285 ms
    +- 4.............................................2400 ms
    +- 5.............................................2268 ms

    Averages (4)
    +- Mean..........................................2435 ms
    +- Median........................................2400 ms
    +- Mode..........................................2268 ms
    +- Range..........................................523 ms



### `load-speed-analysis.php`

    $ php load-speed-analysis.php --url=http://www.iana.org/domains/example/

    Load Speed Results for:
      http://www.iana.org/domains/example/

    Summary
    +- Load Speed....................................2289 ms
    +- Score..........................................78 pct

    Response (count)......................................12
    +- CSS.................................................3
    +- HTML................................................1
    +- Image...............................................5
    +- JS..................................................3

    Response (size)................................194.25 KB
    +- CSS..........................................17.33 KB
    +- HTML..........................................3.22 KB
    +- Image........................................18.76 KB
    +- JS..........................................154.94 KB

    Suggested Improvements (5)
    +- Leverage browser caching............................6
    +- Defer parsing of JavaScript.........................1
    +- Minify JavaScript...................................1
    +- Optimize images.....................................0
    +- Minify CSS..........................................0


## Known Bugs

Coming soon.


## Next Steps

Coming soon.
