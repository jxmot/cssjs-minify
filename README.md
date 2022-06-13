# CSS and JS File Minifier

Is this just another CSS and JS minification tool? Well, yes and no. This utility has been designed with the following features:

* Utilizes an web API to minimize CSS and JS files. See [Minification API](#minification-api) for details.
* Can work in conjunction with [minimize-prep](https://github.com/jxmot/minimize-prep#readme). Where **minimize-prep** is another utility that reads HTML files and creates concatenated CSS and JS files from the `<link>` and `<script>` tags it finds.
* Can operate independently with individual CSS and JS files.

## Requirements

* PHP >= 5.6

## Minification API

The API I am using comes from the nice folks at [Toptal](https://www.toptal.com). There are two endpoints, one for CSS and another for JavaScript.

Online minifiers and documentation (*as of **2022-06-12***):

* Online [CSS Minifier](<https://www.toptal.com/developers/cssminifier/>) - The API documentation is [here](<https://www.toptal.com/developers/cssminifier/api>).
* Online [JavaScript Minifier](<https://www.toptal.com/developers/javascript-minifier>) - The API documentation is [here](<https://www.toptal.com/developers/javascript-minifier/documentation>).

The endpoints are (*as of **2022-06-12***):

* CSS - `https://www.toptal.com/developers/cssminifier/raw`
* JavaScript - `https://www.toptal.com/developers/javascript-minifier/api/raw`

### Things to Note

(*as of **2022-06-12***):

In regards to the *online* minifiers at Toptal, they are currently impossible to find via site navigation. However there are some other utilities provided [there](<https://www.toptal.com/utilities-tools>). Including one I occasionally use: [HTML Shell](<https://www.toptal.com/developers/htmlshell>).

And in regards to the API endpoints there is some inconsistency between them. And it appears that they have changed in the past as Toptal acquired the minifier utilities.

So far I have not encountered any errors or problems with the minified output from the Toptal API.

## Running

**Usage:**
**`php cssjs-minify.php [default|minprep|fullmin]`**

Where:

* **`default`** - Iterate through a static list (*see [Default Mode](#default-mode)*) of CSS and JavaScript files. A new minified file will be created in the same location, and using the same name as the input file. 
* **`minprep`** - Assumes that the [minimize-prep](<https://github.com/jxmot/minimize-prep#readme>) utility has already ran. And that it has created the concatenated CSS and JS files, those files will be minified.
* **`fullmin`** - Runs [minimize-prep](<https://github.com/jxmot/minimize-prep#readme>) first, and then minimizes the results.

### Default Mode

**`php cssjs-minify.php default`**

The associated JSON configuration file is `cssjs-minify-default.json`:

```
{
    "verbose": true,
    "api": {
        "cssmin": "https://www.toptal.com/developers/cssminifier/raw",
        "jsmin": "https://www.toptal.com/developers/javascript-minifier/api/raw"
    },
    "minprepcfg": null,
    "_comment": "no wildcards, paths can be relative or absolute.",
    "cssin": ["css/example_1.css","css/bad_example.css", "css/example_2.css","css/example_3.css","css/example_4.css"],
    "jsin": ["js/example_1.js","js/example_2.js","js/example_3.js","js/example_4.js"]
}
```

In this mode the utility will iterate though the `"cssin"` and `"jsin"` arrays, and attempt to minify each individual file. The miniified files will be placed where the originals were found.

If a file in the list is missing an error message will be sent to the console and minification will proceed to the next file.

**Output Example:**

```
Starting Minimization...
css/example_1.css - length = 55
css/example_1.min.css - length = 38
ERROR css/bad_example.css does not exist, skipping.
css/example_2.css - length = 31
css/example_2.min.css - length = 22
css/example_3.css - length = 32
css/example_3.min.css - length = 23
css/example_4.css - length = 33
css/example_4.min.css - length = 22
js/example_1.js - length = 103
js/example_1.min.js - length = 72
js/example_2.js - length = 84
js/example_2.min.js - length = 52
js/example_3.js - length = 83
js/example_3.min.js - length = 52
js/example_4.js - length = 83
js/example_4.min.js - length = 52
Minification Complete.
```

### MinPrep Mode 

**`php cssjs-minify.php minprep`**

The associated JSON configuration file is `cssjs-minify-minprep.json`:

```
{
    "verbose": true,
    "api": {
        "cssmin": "https://www.toptal.com/developers/cssminifier/raw",
        "jsmin": "https://www.toptal.com/developers/javascript-minifier/api/raw"
    },
    "minprepcfg": "../minprep/minprep.json"
}
```

In this mode some *assumptions* are made:

* [minimize-prep](https://github.com/jxmot/minimize-prep#readme) has been installed in a *sibling* folder to the folder where this utility has been installed.
* minimize-prep has been ran successfully and has created the concatenated CSS and JS files from your input file.

**Output Example:**

```
Starting Minimization...
../../public_html/assets/css/site.css - length = 50469
../../public_html/assets/css/site.min.css - length = 31414
../../public_html/assets/js/site.js - length = 81503
../../public_html/assets/js/site.min.js - length = 40803
Minification Complete.
```

### FullMin Mode

**`php cssjs-minify.php fullmin`**

The associated JSON configuration file is `cssjs-minify-fullmin.json`:

```
{
    "verbose": true,
    "api": {
        "cssmin": "https://www.toptal.com/developers/cssminifier/raw",
        "jsmin": "https://www.toptal.com/developers/javascript-minifier/api/raw"
    },
    "minprepcfg": "../minprep/minprep.json",
    "minpreprun": ["../minprep/", "minprep.php"]
}
```

In this mode some *assumptions* are made:

* [minimize-prep](https://github.com/jxmot/minimize-prep#readme) has been installed in a *sibling* folder to the folder where this utility has been installed.
* minimize-prep has been **not** been run yet.

In this mode the `minimize-prep` utility will be ran for you. After it has completed the resulting files will be minified.

**Output Example:**

```
Starting Minimization...
Starting preparation...
Input: ../../public_html/index.php
Files Root Path: ../../public_html/
../../public_html/assets/css/site.css and ../../public_html/assets/js/site.js will be overwritten.


Excluded - <link rel="stylesheet" href="./assets/css/octicons-3.5.0/octicons.css"/>
Excluded - <link rel="stylesheet" href="./assets/css/site.min.css">
Found - ./assets/css/aboutme.css
Found - ./assets/css/workhist.css
Found - ./assets/css/prehist.css
Found - ./assets/css/patents.css
Found - ./assets/css/projects.css
Found - ./assets/css/repoviews.css
Found - ./assets/css/footer.css
Found - ./assets/css/animate.css
Excluded - <script src="./assets/jq/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
Excluded - <script src="https://www.google.com/recaptcha/api.js"></script>
Excluded - <script src="./assets/js/showdown-2.1.0/dist/showdown.js"></script>
Excluded - <script src="./assets/js/site.min.js"></script>
Found - ./assets/js/elemtotop.js
Found - ./assets/js/technologies.js
Found - ./assets/js/projects.js
Found - ./assets/js/categories.js
Found - ./assets/js/repohitsview.js
Found - ./assets/js/workhist.js
Found - ./assets/js/education.js

Preparation Complete.
../../public_html/assets/css/site.css - length = 50469
../../public_html/assets/css/site.min.css - length = 31414
../../public_html/assets/js/site.js - length = 81503
../../public_html/assets/js/site.min.js - length = 40803
Minification Complete.
```

## Silent Running

In any of the JSON configuration files find `"verbose": true` and change it to `false`. The only console output will be error messages or "usage" messages. The application will exit after displaying run-time error messages.

### Error Messages

**`cssjs-minify.php`**:

* `ERROR missing file: cssjs-minify-[default|minprep|fullmin].json` - Indicates that the JSON configuration file that should match the argument choice is missing.
* `ERROR ["minpreprun[0]""minpreprun[1]"] does not exist` - Can only occur in [FullMin Mode](#fullmin-mode). The file specified could not be found.
* `ERROR invalid minpreprun  - ["minpreprun[0]""minpreprun[n]"]` - Can only occur in [FullMin Mode](#fullmin-mode). The array `"minpreprun"` in `cssjs-minify-fullmin.json` is incorrect. It must only have 2 items. Item 0 is the path to where `minimize-prep` is installed and item 1 is always `minprep.php`.

The following errors will not halt the application:

* `ERROR [file.css|file.js] does not exist, skipping.` - Only seen in [Default Mode](#default-mode). It indicates one of the files listed could not be found.
* `ERROR minifiying [file.css|file.js] - [HTTP Status]` - An error has occurred with the Toptal API. And `[HTTP Status]` will be the HTTP error code and message. A partial list of errors can be seen here in [Toptal Documentation](<https://www.toptal.com/developers/javascript-minifier/documentation>)(*at the bottom of the page*).

**`prepmin.php`**: Only in [MinPrep Mode](#minprep-mode) or [FullMin Mode](#fullmin-mode) - 

* `ERROR ["minprepcfg"] does not exist!` - Where `"minprepcfg"` is found in the JSON configuration files but the file specified could not be found.
* `ERROR [concatenated.css|concatenated.js] does not exist!` - The concatenated resource file (*css or js*) could not be found.

The following errors will not halt the application:

* `ERROR minifiying [file.css|file.js] - [HTTP Status]` - An error has occurred with the Toptal API. And `[HTTP Status]` will be the HTTP error code and message. A partial list of errors can be seen here in [Toptal Documentation](<https://www.toptal.com/developers/javascript-minifier/documentation>)(*at the bottom of the page*).


---
<img src="http://webexperiment.info/extcounter/mdcount.php?id=cssjs-minify">
