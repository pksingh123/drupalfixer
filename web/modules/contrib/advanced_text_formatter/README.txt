This module is just a formatter (display) of textfield, text area and text
format. The idea behind this is to provide a simple solution, easy to setup,
with few dependencies to display text on website.

Implementation:

The trim function in this module is taken from Views module with a few
modifications.

Integration:

Of course, this module is fully compatible with any modules that use entity
formatters, such as Views or Panels

Besides that, this module is extremely useful when you use it with view modes.
In order to create a new view mode, you can implement the
hook_entity_info_alter() or install Entity view modes module.

Dependencies:

- Text (Drupal 8.x Core)
- Filter (Drupal 8.x Core)

Installation:

Download the module and simply copy it into your contributed modules folder:
[for example, your_drupal_path/modules] and enable it from the
modules administration/management page.
More information at: Installing contributed modules (Drupal 8)
https://www.drupal.org/documentation/install/modules-themes/modules-8

Configuration

After successful installation, browse to the "Manage Display" settings page,
for the entity (Node content type, for example) with a text field, text area
or text format, choose Advanced Text from the formatters list

Options:
- Trim length:  The maximum number of characters the a field can be. Set this to
  0 if you don't want to cut the text. Otherwise, input a positive integer.
- Ellipsis: If checked, a "..." will be added if a field was trimmed.
- Word Boundary: If checked, this field be trimmed only on a word boundary. This
  is guaranteed to be the maximum characters stated or less. If there are no
  word boundaries this could trim a field to nothing.
- Token Replace: Run token replace on this field.
- Filter: Filter the value of this field.
  - None: No filter.
  - Selected Text Format: Use the format that is chosen by user when he inputs
    the value.
  - Limit allowed HTML tags: A list of HTML tags that can be used.
  - Drupal: Filter this field by using Drupal's filters.
- Allowed HTML tags (when Filter is Limit allowed HTML tags): Specify tags which
  should not be stripped.
- Format (when Filter is Drupal): Drupal's filters.
- Convert line breaks into HTML: Converts line breaks into P tag and BR tag in
  an intelligent fashion.

Optional

If you want to display the list of available tokens, you just need to install
Token module, then do to Manage Fields page, edit the field, and select the
"Show available tokens in field's description"
