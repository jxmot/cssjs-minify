# CSS and JS File Minifier

Is this just another CSS and JS minification tool? Well, yes and no. This utility has been designed with the following features:

* Utilizes an web API to minimize CSS and JS files. See [Minification API](#minification-api) for details.
* Can work in conjunction with [minimize-prep](https://github.com/jxmot/minimize-prep#readme). Where **minimize-prep** is another utility that reads HTML files and creates concatenated CSS and JS files from the `<link>` and `<script>` tags it finds.
* Can operate independently with individual CSS and JS files.

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
Found - ./assets/css/reseter.css
Found - ./assets/css/nobs-palettes.css
Found - ./assets/css/nobs-default.css
Found - ./assets/css/splash.css
Found - ./assets/css/nobs.css
Found - ./assets/css/nobs-totop.css
Found - ./assets/css/nobs-contact.css
Found - ./assets/css/aboutme.css
Found - ./assets/css/workhist.css
Found - ./assets/css/prehist.css
Found - ./assets/css/patents.css
Found - ./assets/css/projects.css
Found - ./assets/css/repoviews.css
Found - ./assets/css/footer.css
Found - ./assets/css/github-feed-dark.css
Found - ./assets/css/github-feed.css
Found - ./assets/css/animate.css
Excluded - <script src="./assets/jq/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
Excluded - <script src="https://www.google.com/recaptcha/api.js"></script>
Excluded - <script src="./assets/js/showdown-2.1.0/dist/showdown.js"></script>
Excluded - <script src="./assets/js/site.min.js"></script>
Found - ./assets/js/utils.js
Found - ./assets/js/statusmsg.js
Found - ./assets/js/thankyou.js
Found - ./assets/js/nobs.js
Found - ./assets/js/nobs-contact.js
Found - ./assets/js/totop.js
Found - ./assets/js/github-feed.js
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



