=Anypage=
== Intro ==
Add any page to your site. Admin users can define a page address. Pages can be populated
by an editor or by the view anypage/page/name. Pages are always relative to
root.

== Examples ==

	Defined page: test_page
	View: anypage/test_page
	View file: views/default/anypage/test_page.php

	Defined page: about/users
	View: anypage/about/users
	View file: views/default/anypage/about/users.php

	Even works with file extensions!
	Defined page: about/users/index.html
	View: anypage/about/users/index.html.php
	View file: views/default/anypage/about/users/index.html.php