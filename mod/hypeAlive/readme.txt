hypeAlive is part of the hypeJunction plugin bundle

This plugin is released under a GPL compatible license, as a courtesy of hypeJunction Club
Release of this plugin available at elgg.org might not always correspond to the latest stable release available at www.hypeJunction.com


PLUGIN DESCRIPTION
------------------
hypeAlive is a collection of modules that introduce real-time (ajaxed) interaction for Elgg's user interface.

hypeAlive covers the following:
-- River
    -- Real-time updates to the river (new river items pushed into the loaded page without the need for page reloading)
    -- Pagination replaced with real item loading of following river items
-- Comments / Likes (former hypeComments)
    -- Introduces Comment / Like bar to River items, Content items, Group Discussions
    -- Real-time updates of comments (new comments are pushed into the page without the need to reload)
    -- Creates a real-time discussion effect (like in a chat room)
    -- Introduces traverse commenting, where the users can comment on a comment. Unlimited depth of the comments tree
    -- Notifications to content owners and all people participating in the discussion
-- Search (former hypeLiveSearch)
    -- Introduces real-time search suggestions in a search box

REQUIREMENTS
------------
1) Elgg 1.8.3+
2) hypeFramework 1.8.5+

INTEGRATION / COMPATIBILITY
---------------------------
-- Integrates with Elgg River
-- Replaces Elgg Comments
-- Integrates Likes into Elgg Comments replacement
-- Replaces Group Discussions

-- Likely to be incompatible with other plugins that overwrite / modify Elgg River page_handler

INSTALLATION
------------
-- Install hypeFramework 1.8.5+
-- Place hypeAlive below hypeFramework in the comments list and activate
-- Run upgrade.php

UPGRADING FROM PREVIOUS VERSION
-------------------------------
-- Disable all hype plugins, except hypeFramework
-- Disable hypeFramework
-- Backup your database and files
-- Remove all hype plugins from your server and upload the new version
-- Enable hypeFramework
-- Enable other hype plugins

USER GUIDE
----------


TODO
-----

WARNINGS / NOTES
----------------


BUG REPORTS
-----------
Bugs and feature requests can be submitted at:
http://hypeJunction.com/trac
