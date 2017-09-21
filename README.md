# wccm
a repository for WCCM Wordpress site


Installation:
- Copy wp-config.php.default to wp-config.php
- In this directory, run `docker-compose up -d`
- Go to localhost:8000 
- Walk through a dummy install
 - Activate all Plugins
- Go to Tools > Migrate DB
 -	Select "Pull" and enter connection info from [the staging site](http://wccm.being.design/wp-admin/network/settings.php?page=wp-sync-db#settings)
 - Enter your local url in the first find/replace box. The second row shouldn't matter.
 - Select all tables EXCEPT `wpj7_users` and `wpj7_usermeta`
 - Skip importing the active plugins
 - I recommend saving the import profile for later

That's it!
