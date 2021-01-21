# Views Slideshow
## INTRODUCTION

Views Slideshow can create slideshows out any content on your Drupal site -
whether that is images, images on content or full rendered entities. The 
`views_slideshow` module provides a base/plugin system/api for building full
featured slideshows within the Views UI. This project also includes a module
implementing that base; `views_slideshow_cycle`. For most users, you'll just
want to enable both of them and install pre-reqs. For advanced users you can
create your own implementation - and there are other implementations for Drupal
7 available (see below).

Built in and most/all of the implementations are powered by jQuery, and are
highly customizable: you may choose slideshow settings for each View display
you create.

### Potential Uses
* News item slideshow (such as the title, image and teaser of the last 5 news
  articles submitted)
* The Last X number of X submitted (images, videos, blog entries, forum posts,
  comments, testimonials, etc.).
* Rotate any image, based on any filters you can apply in views.
* Hottest new products for any ecommerce drupal site.
* Rotate contact links, share links, etc.
* You can even rotate entire nodes, categories, image galleries, etc.
* It's also a great space saver. Places where you had multiple images or items
  such as RSS feeds or category listings can now be presented in a slideshow.

## REQUIREMENTS

* Views Slideshow 8.x-4.x requires Drupal 8 & the core Views module enabled.
* There is no upgrade path from Views Slideshow for Drupal 7.
* Views Slideshow Cycle (Which most users should use) requires some JavaScript
  libraries:
  * [jQuery Cycle 3.x](https://github.com/malsup/cycle)
  * [JSON2](https://github.com/douglascrockford/JSON-js)
  * [jQuery HoverIntent](https://github.com/briancherne/jquery-hoverIntent)
  * [jQuery Pause](https://github.com/tobia/Pause)

## INSTALLATION
Install as you would normally install a contributed Drupal module. See the
[Drupal 8 Instructions](https://drupal.org/documentation/install/modules-themes/modules-8)
if required in the Drupal documentation for further information. Note there are
two modules included in this project; Views Slideshow & Views Slideshow Cycle.
In most cases you will need/want to enable both of them.

If you are using the Views Slideshow Cycle sub-module, you will also need to
install some JavaScript libraries. The required libraries are:

* [jQuery Cycle 3.x](https://github.com/malsup/cycle) in 
  `/libraries/jquery.cycle`
* [JSON2](https://github.com/douglascrockford/JSON-js) in `/libraries/json2`
* [jQuery HoverIntent](https://github.com/briancherne/jquery-hoverIntent)
  in `/libraries/jquery.hoverIntent`
* [jQuery Pause](https://github.com/tobia/Pause) in `/libraries/jquery.pause`

It is recommended to use [Drush](https://drush.org) to automatically install
the requirements to the right spot. To install all of the libraries to the
right places, run `drush dl-cycle-lib`.

If you don't have Drush available, or would prefer to install manually, you can
do so. An example of code you could run in your Drupal root dir to download to
the right place:
```
 mkdir -p libraries/jquery.cycle && cd $_ && wget https://malsup.github.io/jquery.cycle.all.js \
 && mkdir -p ../../libraries/jquery.hoverIntent && cd $_ && wget https://raw.githubusercontent.com/briancherne/jquery-hoverIntent/master/jquery.hoverIntent.js \
 && mkdir -p ../../libraries/json2 && cd $_ && wget https://raw.githubusercontent.com/douglascrockford/JSON-js/master/json2.js \
 && mkdir -p ../../libraries/jquery.pause && cd $_ && wget https://raw.githubusercontent.com/tobia/Pause/master/jquery.pause.js
```

## CONFIGURATION
Configuration is on a per view/display basis. 

Most standard views settings will work fine in conjunction with Views Slideshow.
However, grouping may or may not work. Under most use cases the pager should be
set to either `Display a specified number of items` or `Display all`.

To get started configuring your slideshow, set `Slideshow` as the display
format and configure the slideshow as desired under Format Settings. Next
select the *Skin* - usually `Default` (only one provided with the module).
Then select the *Slideshow Type*; for most users, this will just be `cycle`
with the `views_slideshow_cycle` module.

Below that, there is a lot of different options which should have better
documentation.

### See also:

* [OS Training tutorial on Views Slideshow](https://www.ostraining.com/blog/drupal/drupal-8-slideshows)


## CONTRIBUTORS

Current maintainer:

  * [Neslee Canil Pinto](https://www.drupal.org/u/neslee-canil-pinto)

Past maintainers: 

  * [vbouchet](https://www.drupal.org/u/vbouchet) Initial 8.x port
  
  * [Adam Moore](https://www.drupal.org/u/redndahead)
   
  * [Nick Wilde](https://www.drupal.org/u/nickwilde)
  
 Also, thanks to the many contributors via the issue queues.
