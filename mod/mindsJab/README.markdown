### Beechat the first XMPP chat for Elgg

Beechat is a facebook like chat for elgg using the XMPP protocol. It requires the XMPP serveur ejabberd. 

## Installation

French Documentation

http://github.com/beechannels/beechat/wikis/guide-dinstallation

English Documentation

http://github.com/beechannels/beechat/wikis/setup-guide

## Feedback

We are relying on the [GitHub issues tracker][issues] linked from above for
feedback. File bugs or other issues [here][issues].

[issues]: http://github.com/beechannels/beechat/issues

## Changes

August 18th, 2010

- Fix issue with ie
- Add an alert sound when receiving a new message

August 9th, 2010

- cooked js doesn't cook tokens or languages (separated now to another file)
- db data now can be set from the admin panel
- users can disable the chat in their settings
- migrate.php added that will make the initial sync
- make the get roster js action asynchronous
- fix buddy list issue when reloading page
- fix security issues (XSS)
- works on Elgg 1.6 and 1.7
- user profile picture appears in chat box

## Thanks

We'd like to thank Jean-Manuel Da Silva ( http://github.com/dasilvj), Pablo Martin ( http://github.com/caedesvvv ) and Benjamin H. Graham ( http://github.com/bhgraham ) for their work and help on Beechat.