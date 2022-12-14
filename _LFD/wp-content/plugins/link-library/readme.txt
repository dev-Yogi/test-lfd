=== Link Library ===
Contributors: jackdewey
Donate link: https://ylefebvre.home.blog/wordpress-plugins/link-library/
Tags: link, list, directory, page, library, AJAX, RSS, feeds, inline, search, paging, add, submit, import, batch, pop-up
Requires at least: 4.4
Tested up to: 5.5.1
Stable tag: trunk

The purpose of this plugin is to add the ability to output a list of link categories and a complete list of links with notes and descriptions.

== Description ==

This plugin is used to be able to create a page on your web site that will contain a list of all of the link categories that you have defined inside of the Links section of the Wordpress administration, along with all links defined in these categories. The user can select a sub-set of categories to be displayed or not displayed. Link Library also offers a mode where only one category is shown at a time, using AJAX or HTML Get queries to load other categories based on user input. It can display a search box and find results based on queries. It can also display a form to accept user submissions and allow the site administrator to moderate them before listing the new entries. Finally, it can generate an RSS feed for your link collection so that people can be aware of additions to your link library.

For links that carry RSS feed information, Link Library can display a preview of the latest feed items inline with the all links or in a separate preview window.

This plugin uses the filter method to add contents to the pages. It also contains a configuration page under the admin tools to be able to configure all outputs. This page allows for an unlimited number of different configurations to be created to display links on different pages of a Wordpress site.

For screenshots showing how to achieve these results, check out my [site](https://ylefebvre.home.blog/wordpress-plugins/link-library/link-library-faq/)

All pages are generated using different configurations all managed by Link Library. Link Library is compatible with the [My Link Order](http://wordpress.org/extend/plugins/my-link-order/) plugin to define category and link ordering.

* [Changelog](http://wordpress.org/extend/plugins/link-library/other_notes/)
* [Support Forum](http://wordpress.org/tags/link-library)

== Installation ==

1. Download the plugin
1. Upload link-library.php to the /wp-content/plugins/ directory
1. Activate the plugin in the Wordpress Admin

To get a basic Link Library list showing on one of your Wordpress pages:<br />
1. In the Wordpress Admin, create a new page and type the following text, where # should be replaced by the Settings Set number:<br />
   [link-library settings=#]

1. To add a list of categories to jump to a certain point in the list, add the following text to your page:<br />
   [link-library-cats settings=#]<br />

1. To add a search box to your Link Library list, add the following text to your page:<br />
   [link-library-search]

1. To add a form for users to be able to submit new links:<br />
   [link-library-addlink settings=#]

In addition to specifying a library, categories to be displayed can be specified using addition keywords. Read the FAQ for more information on this topic.

Further configuration is available under the Link Library Settings panel.

== Changelog ==

= 6.6.1 =
* Added new option field for user submission form to specify tooltips to be displayed when user hovers over the fields
* Fixed issue with export all links function not working if no tags are assigned
* Fixed issue with some form fields not being re-displayed is captcha is wrong
* Fixed issue with form validation not working with description field is set to required

= 6.6 =
* Fixed editor issue in WP 5.5

= 6.5.8 =
* Added option to limit depth of categories hierarchy levels for category list

= 6.5.7 =
* Fixed issue with link tooltips having HTML code when search results are found in description
* Fixed issue where the Reset Search button did not use the search form submission URL

= 6.5.6 =
* Added support for tag selection in Common section of library configuration
* Added new sort option for links to sort based on publication date
* Fixed option to not display any tooltip on Link Name
* Add spaces between tags
* Fixed issue with favicon generation not working when editing individual links
* Fixed issue with duplicate images created in media library when assigning images to links
* Added some better tooltips in admin
* Reciprocal link validation will not longer be done if the field is set to be displayed, but not required

= 6.5.5 =
* Added menu item promoting Accessibe service

= 6.5.4 =
* Fixed issue with category unordered list not rendering correctly in Firefox

= 6.5.3 =
* Fix to load thumbnails using appropriate protocol (http or https) based on site protocol

= 6.5.2 =
* Integrated Shrink the Web and Pagepeeker thmbnail generation services
* Added option to display either the Updated date or the publication date for links

= 6.5.1 =
* New ads are now removed if you choose to hide donation links
* Ad no longer blocks selections from drop-down lists

= 6.5 =
* Removed Link Library 6.0 upgrade message from admin
* Fix for Plain Web Address Web link not displaying secondary address correctly
* Fix issue with pagination

= 6.4.9 =
* Search improvements. When searching for multiple terms in Link Library, it will search for these terms separately in the link description, large description and notes instead of only looking for them next to each other
* Fixed issue with link counts in category list
* Fixed issue with pagination system displaying too many dots if there are a lot of pages in library

= 6.4.8 =
* Fixed bug with using a search box defined in a library with a number higher than 1

= 6.4.7 =
* Add tags around all possible instances of category name
* Fix to properly close link tags when linking to dedicated page

= 6.4.6 =
* Fixed issue with image display introduced by 6.4.4

= 6.4.5 =
* Fixed layout issues introduced by 6.4.4 update

= 6.4.4 =
* Sub-categories now work with expansion and collapse
* Before and after tags now more consistently output, especially if there is no value in the field
* Fix for category list with pagination mode enabled

= 6.4.3 =
* Fixed bug with link category description being displayed twice
* Added missing div tag to search results

= 6.4.2 =
* Fixed issue with free price filter

= 6.4.1 =
* Corrects issue with custom post type permalinks introduced in version 6.4

= 6.4 =
* Permalinks for links now use the link URL is links are set not to be accessible publicly

= 6.3.12 =
* Corrected bug where extra spaces were added to the first line of the stylesheet editor content

= 6.3.11 =
* Improvements to broken link and reciprocal link checkers

= 6.3.10 =
* Added ability to sort links by their updated date in admin

= 6.3.9 =
* Fixed typo that prevented link checker tools from working

= 6.3.8 =
* Fix problem with changes to link checker tools where check stops

= 6.3.7 =
* Changed FAQ link to open in new tab

= 6.3.6 =
* Edit links in reciprocal link checker, broken link checker and duplicate link checker now open in new tab
* Valid links in reciprocal and broken link checkers are no longer displayed. Only links with issues
* Added class tags around the No Links Found output messages

= 6.3.5 =
* Added edit links in results of reciprocal link checker, broken link checker and duplicate link checker

= 6.3.4 =
* Added new element to filters shortcode to be able to filter based on first letter of links

= 6.3.3 =
* Added import and export fields for link tags

= 6.3.2 =
* Added new shortcode parameters to be able to override the number of links to be displayed, the order in which links are displayed and the direction of that order. All new parameters are documented in the first tab of the library configurations page.

= 6.3.1 =
* Add new shortcode parameter taglistoverride allowing users to specify a comma-separated list of tags to filter links with

= 6.3 =
* Added new Presets section to library configurations to allow new users to quickly experience the most commonly requested link layouts

= 6.2.21 =
* Added new option to display Telephone links as tel links so that they can be called
* Added a span class around no links to display message to be able to hide it

= 6.2.20 =
* Added new Global Option for default protocol for links that are saved without a protocol

= 6.2.19 =
* Added some shortcodes to use in single item layout template

= 6.2.18 =
* Admin link listing now has tags columns
* Admin link listing now allows for filtering of links by tag

= 6.2.17 =
* Fix for link featured images getting duplicated in media library

= 6.2.16 =
* Automatically add featured image for links using link image. Useful when trying to display the links custom post type using featured images

= 6.2.15 =
* Add option in user submission form to allow users to upload images

= 6.2.14 =
* Small fix to correct errors with Gutenberg editor

= 6.2.13 =
* Updated to import routine to work with clean installations that only have Link Manager in place

= 6.2.12 =
* Fix for [link-library-filters] shortcode to remove extra closing div tag
* Fix option to not display price section in [link-library-filters] shortcode

= 6.2.11 =
* Corrected problem displaying sub-categories when showing one category at a time in HTML GET with Slugs mode

= 6.2.10 =
* Added column to tag list to show tag IDs

= 6.2.9 =
* Added new option to display updated date as tooltip when hovering over links, separating it from showing link updated flag

= 6.2.8 =
* Add option in user submission only allow one link to be submitted domain

= 6.2.7 =
* Add ability to display category name in Advanced Tab

= 6.2.6 =
* Additional fix related to work done in previous update
* Fixes for looping category output when maximum number to display is set

= 6.2.5 =
* Changed way that 'Max number of links to display per category' is queried to avoid caching issues with large databases
* Moved some configuration fields from Advanced tab to Links tab of library configurations

= 6.2.4 =
* Added new sort type for links and categories, allowing users to manually order them using Simple Custom Post Order plugin (see https://wordpress.org/support/topic/link-order-update/ for setup instructions)

= 6.2.3 =
* Added option to have a selection entry in category drop-down list for link-library-cats shortcode

= 6.2.2 =
* Corrected bug that prevented users from being able to select default category in user submission form
* Added option to change text in category drop-down list from "Select a category"

= 6.2.1 =
* Fix for category description data not displaying when selected in Advanced configuration table

= 6.2 =
* Added new option for user submission form to change the default text that appears in the link address field
* Set default value for new option to https, since more and more sites use https by default

= 6.1.28 =
* Updated donation, plugin and FAQ links

= 6.1.27 =
* Fix for double-line display when showing links on single pages

= 6.1.26 =
* Added ability to have shortcodes in large descriptions
* Added ability to have shortcodes in full-page content

= 6.1.25 =
* Reverted experimented thumbnail generation code

= 6.1.24 =
* Fix for conflicts with the Relevanssi in some installations

= 6.1.23 =
* New integration to BuddyPress. User submitted links can now appear in activity feed.

= 6.1.22 =
* Fix for combined results list not appearing anymore

= 6.1.21 =
* Complete fix for empty search results not displaying message indicated in admin

= 6.1.20 =
* Partial fix for empty search results not displaying message indicated in admin

= 6.1.19 =
* Added option to suppress noreferrer and noopener tags

= 6.1.18 =
* Fix to allow Link Library custom post type to be seen by other plugins that support CPTs

= 6.1.17 =
* Fix issue with search filters remaining in place after search has executed

= 6.1.16 =
* Fixed for displaying list of categories in drop-down list format with sub-categories

= 6.1.15 =
* Export all links function adds a new column for category slugs. This column had priority over category names. Also renamed Category column to Category Names.
* Import links functionality now check for category with Category Slug name. If it does not exist, it uses the category name field to create the category

= 6.1.14 =
* Added submitter name, submitter e-mail and link visits to Export All Links functionality
* Added ability to import these 3 new fields

= 6.1.13 =
* Add support for list of category slugs in shortcode parameters
* Additional fix for empty sub-categories not appearing correctly in [link-library-cats] shortcode

= 6.1.12 =
* Fix for empty sub-categories not appearing correctly in [link-library-cats] shortcode

= 6.1.11 =
* Fix issue with pagination mode and combine all results without categories mode not working together

= 6.1.10 =
* Changed default order of items in target drop-down list in link editor
* Added option in Global Options to choose which target option should appear first in link editor. Defaults to _blank.

= 6.1.9 =
* Fixed issue with category name not appearing when selecting 'After category name' or 'After top-level category name' as the category description position
* Fixed issue with link count not displaying when category description configured to appear on left side of category name in [link-library-cats] shortcode

= 6.1.8 =
* Small fix for data validation of reciprocal link URL

= 6.1.7 =
* Fixed shortcode so that you can display a sub-category using the [link-library categorylistoverride="XX"] parameter

= 6.1.6 =
* Made 'Powered by Robothumb' link have _empty target to open in new tab / window
* Added condition to refuse reciprocal links that are set to the posting site

= 6.1.5 =
* Additional fixes for Robothumb message
* Fixed for image links not being closed properly when using external thumbnail generation service

= 6.1.4 =
* Fix so that Robothumb message only appears once if you use sub-categories

= 6.1.3 =
* Fix to exclude links from custom post types that can be imported back into Link Library in Import/Export section

= 6.1.2 =
* Fix for undefined index when using link-library-count shortcode
* Adds new option to sort categories by slug

= 6.1.1 =
* Added rel tags noopener and noreferrer to links for security

= 6.1 =
* Stable release one month after 6.0 version. Update issues have trickled down, so I making this version 6.1.
* Fixes for RSS Preview pop-up window
* Fixed for intermittent before and after link fields in advanced library configuration
* Added new field to be able to specify max number of links to display per category, instead of only in total

= 6.0.48 =
* Fix for single category GET category variable

= 6.0.47 =
* Added new option for Web Link display to only display Plain Web Address, without any hyperlink
* Fix for [link-library-count] shortcode to properly display link count
* Added code so that permalinks rewrite rules are automatically flushed when you save general settings
* Fixed problem with quick edit of link URL

= 6.0.46 =
* Improvements to upgrade routine to account for users who were running older version of Link Library before upgrade
* Search improvements. Brings back search in link descriptions, notes and large descriptions. Also searches through these fields when searching for links in admin
* Fixes for text after double-quotes getting cut off in link description and note fields

= 6.0.45 =
* Fix to properly order links ordered by link visits

= 6.0.44 =
* Fixed word array appearing in new link admin e-mail for categories

= 6.0.43 =
* Fixed link moderation table, showing 'None assigned' messages outside of the table for links without tags
* Fixed notification e-mail missing title
* Fixed pages with cat variable in link not displaying links properly

= 6.0.42 =
* Sub-categories now display hierarchically in User link submission form
* Increased timeout to 10 seconds for reciprocal and broken link checkers to avoid timeouts with some sites
* Fix to pagination display when using sub-categories

= 6.0.41 =
* Fix to link checking tools to properly check status of box to delete links that return a 403 error

= 6.0.40 =
* Fixes for new reciprocal and broken link checkers

= 6.0.39 =
* Re-worked reciprocal and broken link checkers so they check links using ajax one by one. This way, the checker can never time out, even with large libraries

= 6.0.38 =
* Fixed user link submission when not allowing user to select category

= 6.0.37 =
* Fixed to new date diff code implementation

= 6.0.36 =
* Empty categories now properly appear again when displaying categories in drop-down list mode when showing empties
* Replaced references to setTimestamp to be compatible with PHP 5.2

= 6.0.35 =
* Fix for "Generating cat link" debug message appearing in some configurations

= 6.0.34 =
* Adjustments for calculation of height of multi-select fields in user submission form

= 6.0.33 =
* Added option to display links that are scheduled for publication at a latter date, under links tab of library settings

= 6.0.32 =
* Fix for user submission form

= 6.0.31 =
* Add new section to user submission form to allow visitors to assign tags to the links they submit

= 6.0.30 =
* Add option to display categories as a multi-select list in user submission form

= 6.0.29 =
* Fix for PHP warning if categories are set to be displayed in list order and no included categories are specified

= 6.0.28 =
* Fix to category drop-down list so that it does not show excluded categories when showing one category at a time

= 6.0.27 =
* Fixes to tools such as duplicate checker and thumbnail generator so they no longer consider trashed links

= 6.0.26 =
* Added calls to wp_reset_post data. In some circumstances, not calling this caused some strange behaviour on some sites

= 6.0.25 =
* Added button in general option to delete all links in old format
* Converted new lines from editor to line breaks in single item layout
* Added [link-category] as tag for single item layout

= 6.0.24 =
* Fixed to RSS Feed generation to properly filter links based on category inclusion or exclusion

= 6.0.23 =
* Correction for extra UL tag displayed when Link Display Format is set to No surrounding tags

= 6.0.22 =
* Added new general option to be able to change Link Library post slug from default "links"

= 6.0.21 =
* Brought back the moderation link submission notification icon to the top-level Link Library menu

= 6.0.20 =
* Added new column in Link Library admin list to show updated date and display text when links have been recently updated
* Added information about single item layout, along with list of fields that can be used in template
* Introduced new widget. Users only need to select a display configuration

= 6.0.19 =
* Fix user-submitted links with user-submitted categories missing category

= 6.0.18 =
* Added tool in Global Options to export list of category ID mappings between version 5.9 and 6.0

= 6.0.17 =
* Updated Welcome message box for people updating to show FAQ

= 6.0.16 =
* Restored ability to specify max number of links to display

= 6.0.15 =
* Fix to prevent warning in some configurations of 6.0.14

= 6.0.14 =
* Fixes for link display format selector
* Fixes for table layout when using combined results option
* Removed references to My Link Order plugin since it is no longer supported

= 6.0.13 =
* Fixed Edit links not appearing for links since 6.0

= 6.0.12 =
* Improvements to update mechanism to avoid duplicate entries

= 6.0.11 =
* Fixed issue with user submitted links not having a category assigned to them

= 6.0.10 =
* Fixes to link ordering methods
* Fixes to bring back featured links to be displayed at top of each category
* Fix to bring back support for link category override selection list in user submission tab
* Added extra column to category list to make it easier to find category IDs

= 6.0.9 =
* Fix for number of links per category when showing list of categories as a drop-down

= 6.0.8 =
* Removed automatic re-import mechanism

= 6.0.7 =
* Fine-tune automatic re-import process

= 6.0.6 =
* Automate re-import process so that sites that automatically updated to 6.0 don't need to manually re-import links

= 6.0.5 =
* Added line to disable link manager option for legacy installations
* Additional fixes to update routine to avoid duplicate entries

= 6.0.4 =
* Additional fixed to update routine to avoid duplicate entries

= 6.0.3 =
* Fix for parse error on older versions of PHP
* Small fix to update function

= 6.0.2 =
* Correct warning with sizeof function when combining search results
* Fix for category exclusions in link submission form

= 6.0.1 =
* Re-written link importer to avoid duplicate entries
* Fix for Large Description HTML tags not being displayed correctly

= 6.0 =
* Official release of Link Library 6.0

= 6.0 Beta 12 =
* Improvements in performance of initial link import to help with websites that have large quantity of links

= 6.0 Beta 11 =
* Fix in links importer to properly read in data exported by plugin

= 6.0 Beta 10 =
* Fix to display child categories when using shortcode category override options

= 6.0 Beta 9 =
* Added shortcode parameters to the Usage tab under Library Configuration
* Modified All Links page filter to show all categories instead of only showing categories containing links. Also made category list hierarchical.
* Fix to show child categories in Common tab of Library Configurations

= 6.0 Beta 8 =
* Updated bookmarket to work with new link custom post types (need to re-create bookmarklet from General Options to work)
* Fixed problem selecting categories in Common tab of library configuration
* Fix for shortcode category overrides not working (categorylistoverride, excludecategoryoverride)

= 6.0 Beta 7 =
* Resolved undefined variable warning linkeditoruser
* Fix importer and exporter field order to accept imports from version 5.9 and be more flexible towards changes
* Saving library configuration for library other than #1 now go back to the current library edited

= 6.0 Beta 6 =
* Added new option field under Web Link item in Advanced table to specify target
* Fixed warning in user submission form
* Updated Norsk translation
* Fixed default value of single item template
* Fixed PHP warnings if link fields are left empty

= 6.0 Beta 5 =
* Fixed issue with Robothumb link appearing after each category instead of only once after all links
* Fixed issue with links being displayed as featured even if they had not been set as such
* Fixed issue with front-end Edit links not sending administrators to the right place
* Fixed issue with drag-and-drop of elements in advanced section sometimes creating duplicates
* Fixed issue with category names not displaying if Link Library configured to display with headings instead of divs

= 6.0 Beta 4 =
* Corrected warnings when activating plugin on a site that had never had old version of Link Library
* Added button under General Options to re-import links from 5.9.x to 6.0
* Removed code in plugin initialization referring to inexistent table

= 6.0 Beta 3 =
* Incorporated changes from 5.9.15.3

= 6.0 Beta 2 =
* Fixed compatibility issue with versions of PHP older than 5.5

= 6.0 Beta 1 =
* Complete re-work of Link Library to use Custom Post Types instead of the WordPress legacy link tables

= 5.9.15.8 =
* Fixed link exported so that links with multiple categories do not become duplicates
* Final version of Link Library 5.9, before 6.0 release

= 5.9.15.7 =
* Added class around RSS inline feed items date to be able to hide it

= 5.9.15.6 =
* Fix dismissal of message introduced in previous update

= 5.9.15.5 =
* Added admin message for 6.0 release target date and latest beta

= 5.9.15.4 =
* Small change to link exporter to make it compatible with Link Library for those preferring to export and re-import links than upgrading links in place

= 5.9.15.3 =
* Added option to determine if new reset search button should be shown or not

= 5.9.15.2 =
* Added admin message for version 6.0 Beta 1
* Added reset button to search form
* Added function to grey out categories when displayed in search if they do not contain any links

= 5.9.15.1 =
* Added new field to link editor to be able to specify additional rel tags for a link using free-form text

= 5.9.15 =
* Additional fix related to pagination with permalinks

= 5.9.14.16 =
* Fixed pagination mode when using HTML Get with Permalinks switching method

= 5.9.14.15 =
* Added AJAX mode to pagination system

= 5.9.14.15 =
* Added new option to General Settings to specify the user level required to access Link Library configuration and to be able to edit links

= 5.9.14.14 =
* Fixed for [link-library-count] shortcode to work with pagination mode

= 5.9.14.13 =
* Removed rating limit for link import to be higher than 10. Note that if you edit and save a link, the rating will go to a value between 0 and 10. This only helps if you always import your links.

= 5.9.14.12 =
* Correction for warning related to user link submission form

= 5.9.14.11 =
* Corrected some PHP notices about undefined indices

= 5.9.14.10 =
* Fixed warning about missing captchagenerator index in array

= 5.9.14.9 =
* Fixed issue where library settings did not update immediate after copying a library

= 5.9.14.8 =
* Added updated date field to link export

= 5.9.14.7 =
* Added new sort option for links to display based on number of visits

= 5.9.14.6 =
* Renamed configuration page tab classes to avoid conflicts with other plugins

= 5.9.14.5 =
* Modified user submission form so it does not display captcha is user is logged in

= 5.9.14.4 =
* Added the option to select Google reCAPTCHA as the Captcha generator

= 5.9.14.3 =
* Added ability to specify updated date when importing links via CSV file. Can be left empty to leave updated date empty

= 5.9.14.2 =
* Fix to avoid losing save button in Link Library configuration dialogs

= 5.9.14.1 =
* Fixed updated flag functionality and added options to specify updated text and position

= 5.9.14 =
* Added ability to specify a query string to be added to all links in a library, or all links in a single category
* Converted space indents to tabs

= 5.9.13.27 =
* Addressed potential security vulnerability

= 5.9.13.26 =
* Fix to avoid SQL warning when displaying a single category at a time in AJAX mode

= 5.9.13.25 =
* Fix to have some sites that were showing as unreachable in link checking tools now appear as valid sites

= 5.9.13.24 =
* Removed debug traces for web links

= 5.9.13.23 =
* Fix display of web links so that it does not display if link is empty
* Introduce override in shortcode for single category to display in AJAX single cat mode

= 5.9.13.22 =
* Trying to fix version update loop

= 5.9.13.21 =
* Added way to create direct links to categories when showing one category at a time via AJAX (e.g. www.mysite.com/links/?cat_id=244)
* Corrected a PHP warning

= 5.9.13.20 =
* Changed encoding of e-mails sent by user submission
* Fixed condition where user submission e-mail could have no title

= 5.9.13.19 =
* Re-do of changes from 5.9.13.17
* Fixed problem on link activation with table creation syntax
* Fixed bug with after last link field in advanced configuration table
* Added new link display mode that is not a table or unordered list

= 5.9.13.18 =
* Rollback of modications made in 5.9.13.17

= 5.9.13.17 =
* Fixed problem on link activation with table creation syntax
* Fixed bug with after last link field in advanced configuration table
* Added new link display mode that is not a table or unordered list

= 5.9.13.16 =
* Renamed Reciprocal Link Checker to Link checking tools
* Added new tool to check for duplicate links (URL or name)

= 5.9.13.15 =
* Fix to use moderator e-mail for link submission notification

= 5.9.13.14 =
* Fixed issue with search not working when using Basic permalinks (e.g. website.com/?page_id=4)
* Fixed issue with cat alpha filter and pagination landing on pages that don't exist

= 5.9.13.13 =
* Updated constructors for RSS Genesis and Captcha classes to use __construct instead of PHP 4 style constructors
* Added field to user submission config to add content to initial e-mail sent to link submitters

= 5.9.13.12 =
* Set initial value of user-submission URL fields to http://

= 5.9.13.11 =
* User submitted link are nwo properly saved with a submitted date
* Clarified message for URL missing http in user submission form.

= 5.9.13.10 =
* Added hook for users to register their own link reciprocal check function

= 5.9.13.9 =
* Fixed issue with copying library settings in Internet Explorer

= 5.9.13.8 =
* Fixed javascript errors with quotes in translated langauges for user submission form messages

= 5.9.13.7 =
* Updated translation template file

= 5.9.13.6 =
* Fixed issue with suppression of Link Library message from rejection e-mails

= 5.9.13.5 =
* Fixed issue with CSS not appearing problem in some themes
* Added new span class around link count for category display
* Modified div for category link count display

= 5.9.13.4 =
* Fixed issues with RSS Preview pop-up window
* Changed RSS inline display to respect site date format
* Changes RSS inline display to translate dates
* Added option under general settings to allow user to change RSS cache duration for RSS feeds

= 5.9.13.2 =
* Fixed issue with telephone output introduced in 5.9.13.1

= 5.9.13.1 =
* Fixed issue with user link submission with spam filtering on and Contact Form 7 not installed
* Added new option to Link Name field to be able to display description as tooltip
* Added new option in Advanced tab to not display fields that do not contain any data (note that this could have negative effects on page layout)
* Added new item in Advanced tab to display category description as part of Link Library output

= 5.9.13 =
* Corrected XSS vulnerability

= 5.9.12.29 =
* Modified hook to add button to editor to make it removable by users

= 5.9.12.28 =
* Fixed issue with export section when site has too many pages

= 5.9.12.27 =
* Added new variable for pop-up content %link_email_link%

= 5.9.12.26 =
* Modified FAQ links to point to new wiki

= 5.9.12.25 =
* Modified code that generates update timestamp to avoid conflict with other plugins

= 5.9.12.24 =
* Modifications to Akismet user link validation code to avoid sending empty fields

= 5.9.12.23 =
* Fixed mailto output to accept HTML web links in addition to e-mails

= 5.9.12.22 =
* Added missing file for update 5.9.12.20

= 5.9.12.21 =
* Added buttons to delete all locally stored thumbnails or all locally stored favicons for links

= 5.9.12.20 =
* Added new section to Import/Export to import links from existing site content (pages, posts, CPTs)

= 5.9.12.19 =
* Added option to reject user links where reciprocal link is on same domain as an existing reciprocal link

= 5.9.12.18 =
* Fix to allow multiple libraries configured to use AJAX updates working on the same page

= 5.9.12.17 =
* Updated RSS feed generation code to fix header

= 5.9.12.16 =
* Updates supported version number and removed surveys

= 5.9.12.15 =
* Adds submitter name as a field that can be displayed

= 5.9.12.14 =
* Fix to only display a single link when using singlelinkid parameter

= 5.9.12.13 =
* Added ability to only display links submitted by the current logged user

= 5.9.12.12 =
* Fix div error in previous release

= 5.9.12.11 =
* Added ability to only display RSS feed inline if they are no older than a certain number of days
* Added ability to hide links if they do not have any inline RSS items to display

= 5.9.12.10 =
* Added support to specify multiple categories for imported links, comma-separated

= 5.9.12.9 =
* Fixes to avoid possible division by 0 if number of columns if set to null or 0

= 5.9.12.8 =
* Corrections to link submission generated e-mails

= 5.9.12.7 =
* Fixed issues for PHP 7 compatibility
* Added conditions only to filter unused fields from e-mail notification for new user links
* Added information fields to visitor e-mail when new link is submitted

= 5.9.12.6 =
* Fix issue where deleting Settings #1 resets general options

= 5.9.12.5 =
* Added general setting to enable Akismet filtering for all libraries

= 5.9.12.4 =
* Fix for link pop-up not working properly from link image

= 5.9.12.3 =
* Added ability to translate messages from link submission form validation script
* Fixed min length validation of link submission form fields
* Made URL a link in user submission notification e-mail and added textual category name instead of having only ID

= 5.9.12.2 =
* Added option to specify default category in user link submission form
* Added support to validate user links using Akismet (requires Akismet plugin and valid Akismet key)
* Corrected issue with phantom links appearing in Moderation list

= 5.9.12.1 =
* Added configuration field under Search tab to allow users to define No Results text to be displayed
* Added max lengths to field in user submission form
* Added option to send e-mail notification to visitors when they submit links

= 5.9.12 =
* Change way that lik pop-up dialog content is loaded to avoid conflicts with other plugins

= 5.9.11.7 =
* Allow shortcodes to be executed in link pop-up content

= 5.9.11.6 =
* Added new mode to Web Link display element to only output URL

= 5.9.11.5 =
* Added %link_url% to variables that can be used in link pop-up dialog
* Added set_time_limit in reciprocal link checker to avoid issues when checking a large number of links

= 5.9.11.4 =
* Changed reciprocal link checker output to send steady data to browser instead of building entire buffer to avoid timeout

= 5.9.11.3 =
* Added validation for submitter e-mail field

= 5.9.11.2 =
* Added call to process shortcodes in Link Library output content
* Updated french translation

= 5.9.11.1 =
* Added more advanced validation for e-mail and URL fields in user-submission form

= 5.9.11 =
* Updated reciprocal link check to work on sites without allow_url_fopen
* Added broken link checker
* Added Form validation script to user-submission form to catch required fields before form submission

= 5.9.10.4 =
* Fix for search page target not working

= 5.9.10.3 =
* Changes to accept UTF8 characters in large description field (deactivate and reactivate the plugin to fix the database)
* Pre-fill submitter name and e-mail address field if user is logged in

= 5.9.10.2 =
* Added action hooks on user-submitted link approval and rejection. Actions are called link_library_approval_email and link_library_rejection_email. They receive two parameters, $linkdata and $linkextradata.

= 5.9.10.1 =
* Fix for fields not appearing after submitting user link with bad captcha

= 5.9.10 =
* Added user survey results

= 5.9.9.8 =
* User-created links will now set the Updated Date field

= 5.9.9.7 =
* Fix for large description field HTML tags getting messed up on save

= 5.9.9.6 =
* Fixes to category drop-down list display mode

= 5.9.9.5 =
* Fix to avoid re-writing .htacess file when you save settings

= 5.9.9.4 =
* Added span and class to edit link to facilitate positioning or hiding

= 5.9.9.3 =
* Corrections to link importer
* Fix for required field when using a Link Acknowledgement URL

= 5.9.9.2 =
* Prevent submission of search form if visitor has not changed search box content

= 5.9.9.1 =
* Modified importer to update links with same URL instead of creating new ones
* Export of links and library settings now directly trigger file download

= 5.9.9 =
* New option to specify search box initial text
* New option to specify Category filter text

= 5.9.8.3 =
* Change to save paragraph indicators in large description field

= 5.9.8.2 =
* Fix for duplicate link visit counts when multiple instances of Link Library on a page

= 5.9.8.1 =
* Add new field for link target in link export and import operations

= 5.9.8 =
* Add default stylesheet elements for alpha filter
* Update french translation

= 5.9.7.5 =
* Fix problem with Alpha filter in link-library-cats shortcode

= 5.9.7.4 =
* Added ability to import and export link rating information

= 5.9.7.3 =
* Corrected issue where some library configurations led to no links being displayed after 5.9.7.2 update

= 5.9.7.2 =
* Fixed problem with featured link mode to make features links appear correctly in each category

= 5.9.7.1 =
* Adds options to specify code/text to be output before first link in each category and last link in each category

= 5.9.7 =
* Fixed problem with Link Display Format not saving correctly in admin

= 5.9.6.1 =
* Improvements to alphabetic category filter to play nice with the search feature

= 5.9.6 =
* Fixed for add_query_arg potential XSS vulnerabilty issue

= 5.9.5.5 =
* Adds message to invite users to participate to Link Library User Survey. Message can be dismissed.

= 5.9.5.4 =
* Added new alphabetic category filter feature, configurable under Common Library Settings

= 5.9.5.3 =
* Adds a new shortcode [link-library-count] to display the number of links that a library will show

= 5.9.5.2 =
* Added visual editor for large description
* Allow HTML code to be part of large description

= 5.9.5.1 =
* Added Serbian translation

= 5.9.5 =
* Correct problem with user-submission form, displaying a choice for user-defined categories even if configured not to allow them

= 5.9.4.4 =
* Additional fix for 'Show link name when no image is assigned' option

= 5.9.4.3 =
* Fix for 'Show link name when no image is assigned' option

= 5.9.4.2 =
* Fix for new functionality introduced in 5.9.4.2 when using HTML Get with category names method

= 5.9.4.1 =
* Added new option for category drop-down list to go directly to selection without needed visitor to press Go button

= 5.9.4 =
* Fix monthly update mechanism. Setting number and leaving for a few days to make sure update occurs

= 5.9.3.2 =
* Corrected unexpected output on plugin reactivation

= 5.9.3.1 =
* Corrects activation error for new users

= 5.9.3 =
* Added ability to approve user-submitted links automatically if they provide a valid reciprocal link

= 5.9.2.7 =
* Correction to resolve PHP warning

= 5.9.2.6 =
* Added new option to build URLs for one category at a time mode using category names

= 5.9.2.5 =
* Additional corrections for RSS feeds

= 5.9.2.4 =
* Changed to RSS feed generation to make it compatible with feedburner and other rss services. RSS feed address is now site/feed/linklibraryfeed?settingsset=1.

= 5.9.2.3 =
* Corrected to modified image display code

= 5.9.2.2 =
* Modification to image display code to always show before and after tags, whether or not an image is present

= 5.9.2.1 =
* Modified thumbnail generation to skip items that already have images when option 'Give priority to images assigned to links if present' is active

= 5.9.2 =
* Added new option to build URLs for one category at a time mode using category slugs

= 5.9.1.7 =
* Fixed issue with ordering links in descending order

= 5.9.1.6 =
* Fixed to support file links in secondary link field

= 5.9.1.5 =
* Additional enhancements to admin panel to organize using tabs

= 5.9.1.4 =
* Re-structured Library Settings section to use tab to avoid scrolling up and down all the time

= 5.9.1.3 =
* Re-integrate Thumbshots.org service to generate thumbnails and offer users choice between Thumbshots and Robothumb

= 5.9.1.2 =
* Fixed issue with single quote in library name

= 5.9.1.1 =
* Fixed issue with submission message not appearing when user specifies user-defined category
* Updated french translation

= 5.9.1 =
* Replaced Thumbshots.org service with Robothumb.com to avoid regular issues with site
* Added new option to specify thumbnail size under general settings

= 5.8.12.7 =
* Fix issue with theme preview not working when Link Library is enabled

= 5.8.12.6 =
* Added new configuration options under User Submission form configuration to allow to set fields as Required

= 5.8.12.5 =
* Fixed link importer to import extended fields (large description field, no follow, etc...)

= 5.8.12.4 =
* Fixed problem with Heading tag around category names

= 5.8.12.3 =
* Fixed favicon generator
* Minor PHP warning bug fixes

= 5.8.12.2 =
* Fixed problem with table layout introduced in version 5.8.12.1
* Updated french translation
* Tested against WordPress 4.1

= 5.8.12.1 =
* Updated code for translation and translation files
* Added new option to combine all links results together without displaying category

= 5.8.12 =
* Added filters to allow users to implement their own captcha and captcha validation solution
* Added new dialog above text editor to simplify insert of Link Library shortcodes in content

= 5.8.11.3 =
* Corrected problem with no follow option getting set when users submit links

= 5.8.11.2 =
* Update french translation
* Fix one string that could not be translated

= 5.8.11.1 =
* Added new option to show hidden links to editors and administrators

= 5.8.11 =
* Resolved XSS security issue

= 5.8.10.6 =
* Change in general defaults function to avoid reading in stylesheet file unless necessary

= 5.8.10.5 =
* Updated internationalization template

= 5.8.10.4 =
* Added new general option to allow user to specify additional link protocols to be accepted by link editor

= 5.8.10.3 =
* Added alternate shortcode with different endings to resolve issue in WP 3.0.1 around shortcode confusion [search-link-library][cats-link-library] and [addlink-link-library]

= 5.8.10.2 =
* Fixed problem with new singlelinkid option

= 5.8.10.1 =
* Fix problem on WordPress 3.0 with is_multisite and is_network_admin functions not existing
* Fix for category meta creation issue on upgrade

= 5.8.10 - October 2014 =
* Ability to select standard updates (as soon as they are released) or monthly updates (rolled up on first of month)
* Fixed bug with pagination when displayed before Link Library
* Added performance metrics in debug mode
* Added new [link-library] shortcode option to display single link (singlelinkid)
* Change user permission check only to be done one per library instead of every link
* Updated RSS Genesis library to avoid some warnings

= 5.8.8.4 =
* Fixed issue with pagination in AJAX mode

= 5.8.8.3 =
* Fixed issue with creating new libraries beyond the initial first library

= 5.8.8.2 =
* Fixed issue with default category is displayed when only showing one category at a time

= 5.8.8.1 =
* Fixed error with missing function addhttp

= 5.8.8 =
* Re-arranged Link Library code only to load necessary elements depending on the shortcodes that are actually used to increase performance
* If you run into any major issues and need to roll back to previous version until I address them. [Download here](http://downloads.wordpress.org/plugin/link-library.5.8.7.1.zip), then report issue in [support forum](http://wordpress.org/support/plugin/link-library)

= 5.8.7.1 =
* Corrected problem with user link submissions not getting stored

= 5.8.7 =
* Fixed problem with link losing some data such as featured flag when generating favicons for all links

= 5.8.6.9 =
* Fixed problem with importer when category name contains special characters

= 5.8.6.8 =
* Added options to specify custom images for expand and collapse icons

= 5.8.6.7 =
* Fixed issue with link category list not appearing correctly when selecting in Multi-select list mode
* Added warning for users who do not have any link categories in their install

= 5.8.6.6 =
* Corrected PHP warnings

= 5.8.6.5 =
* Updated information on RSS feed link

= 5.8.6.4 =
* Updated french translation
* Added missing translation strings
* Fixed problem with reciprocal link checker and rss feed if links_recently_updated_time variable not set

= 5.8.6.3 =
* Fixed fatal error on ll_reset_options
* Added space between track_this_link and featured classes in Link Library output

= 5.8.6.2 =
* Added new functionality to expand and collapse link categories on display
* Add new option to select categories via selection lists instead of a comma-seperated list of IDs
* Add warning before copying a library onto another
* Corrects PHP warning in admin pages

= 5.8.6.1 =
* Fixed problem with No Links Found message not appearing if no search results found and no link category is set to display on startup
* Fixed some PHP warnings

= 5.8.6 =
* Final fixes for new link submission response e-mail variable %linkurl%
* Added new message for finding no results in search

= 5.8.5.9 =
* Fixed problem with new variable %linkurl%

= 5.8.5.8 =
* Added variable %linkurl% for link submission response e-mail template
* Fixed problem with link title having bad tags when using link description as title and search terms are found in that description

= 5.8.5.7 =
* Fixed tabs not appearing correctly in reciprocal link checker
* Updated french translation

= 5.8.5.6 =
* Fixed problem with resetting stylesheet to default
* Added FAQ section, include embedded new Link Library tutorial videos

= 5.8.5.5 =
* Updated pagination code to preserve URL parameters from other plugins
* Updated FAQ section on plugin page

= 5.8.5.4 =
* Changed layout of delete and reset buttons to make them more obvious to use
* Fixed late refresh after deleting or resetting options

= 5.8.5.3 =
* Fix link updated date setting to use blog time instead of GMT time
* Corrected issue with RSS feed URL generation

= 5.8.5.2 =
* Fix problem with user-submitted categories on link submission

= 5.8.5.1 =
* Reverted all new functions from 5.8.5 until further testing can be done to resolve layout with new version

= 5.8.4.3 =
* Fixed problem with category links when paginating results

= 5.8.4.2 =
* Fix problem with link counts when displaying category list

= 5.8.4.1 =
* Added hack to allow people to page through links by adding parameters to the end of the link manager URL (?linksperpage=1&linkspage=2)

= 5.8.4 =
* Updated admin menu look

= 5.8.3.2 =
* Second fix to allow searches to work with apostrophes
* Fix to allow categories containing no links to be displayed

= 5.8.3.1 =
* Allow link searches to work with apostrophes
* Fix issue with duplicate images appearing

= 5.8.3 =
* Added entry to stylesheet template to style featured links
* Added new option to use local images attached to link if they are present when thumbshots automatic thumbnail generation is active
* Restores support for versions before 3.5 that did not have the media uploader

= 5.8.2.9 =
* Fixed bug with links and settings export functionality

= 5.8.2.8 =
* Fixed problem with AJAX mode when configured not to show library at first. Library was still showing if empty.
* Link dates can now be formatted and translated to local site language
* Changed links and settings export folder to site upload folder instead of plugin folder

= 5.8.2.7 =
* Added option to hide donation links and Support the Author ad
* Added ability for link updated date to be translated to site language
* Updated french translation

= 5.8.2.6 =
* Increased execution time when importing links to allow for larger imports
* Enhanced media uploader selection to show image right away when no previous image was present

= 5.8.2.5 =
* Fixed bug with Link popup option, which was not creating link with correct URL

= 5.8.2.4 =
* Added option to suppress Link Library footer in notification e-mails

= 5.8.2.3 =
* Fixes bug with search results appearing in ajax mode when hiding links on first page display

= 5.8.2.2 =
* Fixed additional PHP warning

= 5.8.2.1 =
* Fixed PHP warnings

= 5.8.2 =
* Fix compatibility issue with WordPress 3.9

= 5.8.1.3 =
* Fix additional problems found with pagination mode

= 5.8.1.2 =
* Fixed problem with AJAX and Pagination mode both active at the same. Pages other then 1 in a category would not display

= 5.8.1.1 =
* Fixed RSS Preview functionality that had been broken in 5.8.1 update

= 5.8.1 =
* Addressed security concerns raised by Wordpress.org plugin maintenance team

= 5.8.0.9 =
* Added option to display pagination selector before or after links

= 5.8.0.7 =
* Corrected problem with user link submissions introduced by previous update

= 5.8.0.6 =
* Updated french translation
* Added support for media upload to attach new images to links

= 5.8.0.5 =
* Fixed link moderation page to show links submitted by bots with no category

= 5.8.0.4 =
* Added option to display link name or description in link title tag

= 5.8.0.3 =
* Added debug code in moderation section to help troubleshoot issues

= 5.8.0.2 =
* Small styling change in admin interface to adjust to removal of background image in WP 3.6

= 5.8.0.1 =
* Corrected redirection bug when user submitted link with bad field, then corrected and re-submitted

= 5.8 =
* Added new Export All Links button to download all links and their associated data in a CSV format

= 5.7.9.7 =
* Fixed issue for search to now be able to accept non-alphabetic characters

= 5.7.9.6 =
* Fixed issue with disappearing category names

= 5.7.9.5 =
* Allow images to be associated to links when they are first created instead of only when you edit them later

= 5.7.9.4 =
* Fixed bug with large description field not getting saved when users submit links

= 5.7.9.3 =
* Corrected undefined method errors when users submitted new links

= 5.7.9.2 =
* Fixed wp_set_link_cat error when user submission option is active
* Fixed problem with links extra info table not getting created in new installations

= 5.7.9.1 =
* Fixed javascript error on add new link page

= 5.7.9 =
* Fix for error submitting links in 5.7.8

= 5.7.8 =
* Fixed error with unknown column when selecting List Featured Links ahead of Regular Links options

= 5.7.7 =
* Cleanup to avoid PHP warnings when some variables don't exist
* Support for plugin being located in a non-standard location
* Addition on uninstall scripts to delete extra table and settings on plugin deletion
* Thanks to Juliette Reinders Folmer for identifying all these issues
* Split code from admin in separate file to improve load times

= 5.7.6 =
* Added option in general settings to select if images that are uploaded or generated should be stored using their full path (functionality in 5.7.3 or older) or relative paths

= 5.7.5 =
* Fixed issue around category name div tags in list

= 5.7.4 =
* Changed stored URL for uploaded images to use relative paths

= 5.7.3 =
* Fixed bug in on-demand thumbnail generation

= 5.7.2 =
* Added div around Thumbshots notice to allow paying users to hide notice (free users need to keep notice displayed to avoid being banned by service)

= 5.7.1 =
* Fixed issue with Thumbshots CID having non-url compliant characters

= 5.7 =
* Re-worked Thumbshots integration to comply with new terms of use
* Updated french translation

= 5.6.9 =
* Added option to load styling on all pages with keyword 'all'

= 5.6.8 =
* Corrected error from version 5.6.7

= 5.6.7 =
* Added option to load styling on category pages with keyword 'category'

= 5.6.6 =
* Added option to load styling on front page with keyword 'front'

= 5.6.5 =
* Adds a div class to category names for styling

= 5.6.4 =
* Added new option so that no category is shown in AJAX mode until the visitor selects a category

= 5.6.3 =
* Re-implemented changes from version 5.6.1

= 5.6.2 =
* Rolled back changes

= 5.6.1 =
* Corrected problem with two undefined variables

= 5.6 =
* Added count of links to moderate in top-level Link Library menu item
* Added dashboard widget to display count of links to moderate

= 5.5.9.1 =
* Changed WP 3.5 support code to force presence of Link Manager if Link Library is installed and activated

= 5.5.9 =
* Removed check for presence of Link Manager in versions older than 3.5

= 5.5.8 =
* Added check for presence of Link Manager to support version 3.5 where the link manager will be inactive by default

= 5.5.7 =
* Added codes in pop-up content to display link rating and rss link

= 5.5.6 =
* Fixed link visit tracking code

= 5.5.5 =
* Added codes in pop-up content to display link submitter, link alternate URL and number of visits

= 5.5.4 =
* Attached popup to image and link name instead of only link name, when configured

= 5.5.3 =
* Added code in pop-up content to display link description, link large description, telephone number and e-mail
* Added pop-up configuration options to specify width and height

= 5.5.2 =
* Updated link popup mechanism to use link target information

= 5.5.1 =
* Added support to convert [ and ] to < and > in large description fields

= 5.5 =
* Added new option to display a popup with user-defined content when links are clicked, then present user with link to click through
* Updated danish translation

= 5.4.9.5 =
* Fixed problem with category links not going to the correct results page when pagination is turned on

= 5.4.9.4 =
* Fixed problem with nofollow and featured item check boxed not unchecking properly

= 5.4.9.3 =
* Fixed problem with e-mail notification when user-submitted links were received

= 5.4.9.2 =
* Correction to bad site admin path in e-mail notification

= 5.4.9.1 =
* Changes method used to build paths to images and other plugin files
* Fixed reciprocal link checker

= 5.4.9 =
* Removed previous affiliate link and added information on my upcoming book.

= 5.4.8 =
* Reverted all changes made in version 5.4.7 to remove undefined variable warnings

= 5.4.7 =
* Fixed undefined variable warnings, which showed up when WP_DEBUG was activated

= 5.4.6 =
* Clearly identified affiliate link in user interface
* Made affiliate image local to plugin

= 5.4.5 =
* Fixed problem with Link ID missing on some installations from links in moderation page

= 5.4.4 =
* Updated danish translation

= 5.4.3 =
* Cleanup to avoid PHP debug warnings

= 5.4.2 =
* Fixed problem with library switching logic

= 5.4.1 =
* Fixed path to plugin icon file

= 5.4 =
* Redesigned user submission processing code to send post data to external php file and redirect back after data storage and validation

= 5.3.3 =
* Fixed duplicate check on user-submitted links
* Fixed problem with other plugins causing shortcode to be evaluated multiple times, resulting in multiple links submission

= 5.3.2 =
* Updated meta box creation code to be compatible with WordPress 3.3

= 5.3.1 =
* Added option to specify address for search box results. This allow you to place search box on one page and results on another page.
* Updated French translation
* Updated Italian translation
* Added Turkish translation

= 5.3 =
* Fixed way that messages were displayed when using user submission form
* Added error message when submitting a link without an address

= 5.2.9 =
* Updated Italian translation
* Updated French translation
* Added missing translation

= 5.2.8 =
* Updated translation file to add text for new option introduced in 5.2.7

= 5.2.7 =
* Added option to replace image with link name if no image is assigned to a link

= 5.2.6 =
* Added missing translation for UI element

= 5.2.5 =
* Split a sentence in two full pieces to facilitate translation.

= 5.2.4 =
* Fixed glitch with reciprocal check menu item disappearing

= 5.2.3 =
* Added italian translation (Thanks to Gianni Diurno!)
* Fixed some text strings which could not be translated

= 5.2.2 =
* Added check for search parameters to verify that they are only text or numbers

= 5.2.1 =
* Added an option to display link category list on search results page

= 5.2 =
* Added span tag to link category description to allow for styling

= 5.1.9 =
* Fixed updated date mechanism

= 5.1.8 =
* Removed debug code in link moderation screen
* Fixed problem with empty categories not showing up even if Hide when empty is not checked

= 5.1.7 =
* Fixed problem with category drop-down in user-submission form that prevented it from appearing in some situations
* Added data type validation code to avoid security exploits

= 5.1.6 =
* Corrected problem with link moderation list screen

= 5.1.5 =
* Corrected a problem with new link submission in admin editor

= 5.1.4 =
* Removed extra debugging code

= 5.1.3 =
* Added new sort mode to display links based on updated date
* Added field to links editor to see updated date and be able to manually change it

= 5.1.2 =
* Changed RSS parsing library back to Simplepie, but using version supplied with Wordpress with extra layer

= 5.1.1 =
* Corrected plugin installation function to properly create custom tables in multi-site environment
* Replaced SimplePie with PHP_RSS library to avoid PHP5 validation errors and have simpler code base

= 5.1.0 =
* Correct path for RSS Icon and RSS Preview Icons

= 5.0.9 =
* Security fix

= 5.0.8 =
* Fixed RSS Feed generation code to accept https links

= 5.0.7 =
* Added details on use of catogory list in link submission form
* Now using Moderator E-mail (if present) as destination address for moderation notifications

= 5.0.6 =
* Fixed problem with path of images uploaded for links

= 5.0.5 =
* Added option in reciprocal checker to delete links that return a 403 error during check

= 5.0.4 =
* Made code change to always load jQuery as soon as Link Library is rendered on a site to enable link click tracking
= 5.0.3 =
* Changed link id field to contain text before their numeric ID to be xhtml compliant
* Added code to strip slashes on custom fields in link editor
* Added link category to link moderation screen
* Add option to use textarea instead of input field for link notes in user submission form
* Added check in Reciprocal checked to see if site is dead. Display appropriate message if it is.

= 5.0.2 =
* Corrected a problem where slashes were getting added in front of quote and apostrophes when editing large description
= 5.0.1 =
* Added code to escape special characters in large description field when importing links

= 5.0 =
* Added new mode to show link categories in a drop-down list
* Replaced calls to wp_specialchars with esc_html since the previous function was deprecated in WP 2.8
* Corrected bad CSS styling in admin sections
* Added bookmarklet creation section in admin to allow for quick link creation
* Fixed problem where settings became "sticky" until you went out of plugin admin and came back after resetting them

== Frequently Asked Questions ==

= Where can I find documentation for Link Library? =

Visit the [official documentation for Link Library](https://ylefebvre.home.blog/wordpress-plugins/link-library/link-library-faq/)

= Who are the translators behind Link Library? =

* French Translation courtesy of Luc Capronnier
* Danish Translation courtesy of [GeorgWP](http://wordpress.blogos.dk)
* Italian Translation courtesy of Gianni Diurno
* Serbian Translation courtesy of [Ogi Djuraskovic, firstsiteguide.com](http://firstsiteguide.com)

= Where do I find my category IDs to place in the "Categories to be Displayed" and "Categories to be Excluded" fields? =

The category IDs are numeric IDs. You can find them by going to the page to see and edit link categories, then placing your mouse over a category and seeing its numeric ID in the link that is associated with that name.

= How can I display different categories on different pages? =

If you want all of your link pages to have the same layout, create a single setting set, then specify the category to be displayed when you add the short code to each page. For example: [link-library categorylistoverride="28"]
If the different pages have different styles for different categories, then you should create distinct setting sets for each page and set the categories to be displayed in the "Categories to be Displayed" field in the admin panel.

= After assigning a Link Acknowledgement URL, why do links no longer get added to my database? =

When using this option, the short code [link-library-addlinkcustommsg] should be placed on the destination page.

= How can I override some of the options when using shortcodes in my pages =

To override the settings specified inside of the plugin settings page, the two commands can be called with options. Here is the syntax to call these options:

[link-library-cats categorylistoverride="28"]

Overrides the list of categories to be displayed in the category list

[link-library-cats excludecategoryoverride="28"]

Overrides the list of categories to be excluded in the category list

[link-library categorylistoverride="28"]

Overrides the list of categories to be displayed in the link list

[link-library excludecategoryoverride="28"]

Overrides the list of categories to be excluded in the link list

[link-library notesoverride=0]

Set to 0 or 1 to display or not display link notes

[link-library descoverride=0]

Set to 0 or 1 to display or not display link descriptions

[link-library rssoverride=0]

Set to 0 or 1 to display or not display rss information

[link-library tableoverride=0]

Set to 0 or 1 to display links in an unordered list or a table.

= Can Link Library be used as before by calling PHP functions? =

For legacy users of Link Library (pre-1.0), it is still possible to call the back-end functions of the plugin from PHP code to display the contents of your library directly from a page template.

The main differences are that the function names have been changed to reflect the plugin name. However, the parameters are compatible with the previous function, with a few additions having been made. Also, it is important to note that the function does not output the Link Library content by themselves as they did. You now need to print the return value of these functions, which can be simply done with the echo command. Finally, it is possible to call these PHP functions with a single argument ('AdminSettings1', 'AdminSettings2', 'AdminSettings3', 'AdminSettings4' or 'AdminSettings5') so that the settings defined in the Admin section are used.

Here would be the installation procedure:

1. Download the plugin
1. Upload link-library.php to the /wp-content/plugins/ directory
1. Activate the plugin in the Wordpress Admin
1. Use the following functions in a [new template](http://codex.wordpress.org/Pages#Page_Templates) and select this template for your page that should display your Link Library.

`&lt;?php echo $my_link_library_plugin->LinkLibraryCategories('name', 1, 100, 3, 1, 0, '', '', '', false, '', ''); ?&gt;<br />
`&lt;br /&gt;<br />
&lt;?php echo $my_link_library_plugin->LinkLibrary('name', 1, 1, 1, 1, 0, 0, '', 0, 0, 1, 1, '&lt;td>', '&lt;/td&gt;', 1, '', '&lt;tr&gt;', '&lt;/tr&gt;', '&lt;td&gt;', '&lt;/td&gt;', 1, '&lt;td&gt;', '&lt;/td&gt;', 1, "Application", "Description", "Similar to", 1, '', '', '', false, 'linklistcatname', false, 0, null, null, null, false, false, false, false, '', ''); ?&gt;

== Screenshots ==

1. The Settings Panel used to configure the output of Link Library
2. A sample output page, displaying a list of categories and the links for all categories in a table form.
2. A second sample output showing a list of links with RSS feed icons and RSS preview link.
