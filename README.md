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

In regards to the *online* minifiers at Toptal, they are currently impossible to find via site navigation. However there are some other utilities provided [there](<https://www.toptal.com/utilities-tools>). Including one I occasionally use: [HTML Shell](<https://www.toptal.com/developers/htmlshell>).

And in regards to the API endpoints there is some inconsistency between them. And it appears that they have changed in the past as Toptal acquired the minifier utilities.

## Running

**Usage:**
**`php cssjs-minify.php [default|minprep|fullmin]`**


Where:

* **`default`** - Iterate through a static list (*see [FullMin Mode](#fullmin-mode)*) of CSS and JavaScript files. A new minified file will be created in the same location, and using the same name as the input file. 
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
    "cssin": ["css/example_1.css","css/example_2.css","css/example_3.css","css/example_4.css"],
    "jsin": ["js/example_1.js","js/example_2.js","js/example_3.js","js/example_4.js"]
}
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


