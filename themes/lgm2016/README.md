# LGM 2016 WordPress theme

A WordPress theme for the 2016 edition of LGM.

## WordPress theme 101

Not sure how a WordPress theme works? It’s not that complicated! The best place to get started [is probably here](https://codex.wordpress.org/Theme_Development).

## folder structure of this theme

### CSS

Manuel: for greater ease of development, I propose to use the following CSS directory structure: 

- The file style.css at root level is necessary for the WordPress theme to work, but we don't load it.
- We put the css files into /css/dev/, where we split them into different files : typography, layout, mediaqueries, print styles...
- Once the site is in an advanced state, we can use a build-script (such as Grunt) that compiles them into a single minimized CSS file, in the /css/build/ directory. This will improve the performance (speed) of the website.