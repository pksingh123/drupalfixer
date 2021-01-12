# Assets for View Modes Inventory

List of pre configured view modes templates. so that they will kike start a 
content type to use the pre-config of view modes and layouts, and the position
of fields.  

In the CONTENT_TYPE_NAME folder you do have a default set of configurations the 
content type named CONTENT_TYPE_NAME.

To have the full set working for your custom content type, you will need:

Pre Requests for the content type:
--------------------------------------------------------------------------------
  - vmi module should be enabled.
  - Title field [CONTENT_TYPE_NAME].title .
  - Body field [CONTENT_TYPE_NAME].body machine name.
         (Some view modes do not use the body field)
  - Image field [CONTENT_TYPE_NAME].field_image machine name. 
         (Some view modes do not use the image field)
--------------------------------------------------------------------------------


1. Copy the list of needed files from the CONTENT_TYPE_NAME folder to your
   config/install folder of your module or profile.

```
   cp modules/vmi/src/assets/config_templates/CONTENT_TYPE_NAME/* 
      modules/your_module/config/install/
```

2. Replace CONTENT_TYPE_NAME with the machine name of your custom content type
   in all recurring value or filename.

   For Example: if you have a feature module with a content type name "story"
   you could do the following:

```
cd your_module/config/install
rename 's/CONTENT_TYPE_NAME/story/g' *
grep -rl 'CONTENT_TYPE_NAME' * | xargs sed -i "s/CONTENT_TYPE_NAME/story/g" ;
```

3. Import the configuration to your Drupal site by enabling your feature module
   or manually import operation for the configuration.
