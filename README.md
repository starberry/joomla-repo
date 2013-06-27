joomla-repo
===========

Base copy of Joomla with "standard" extras, eg. K2

This repo is constructed by layering releases of Joomla on a single main branch
and plumbing a K2 install into the Joomla httpdocs using symlinks.

This was attempted with multiple branches, git-subtree and other methods, but it
just got too complicated. As a result, this is now a simple monotonic branch
along to Joomla 2.5.11, then the latest 2.5.x to date (June 27, 2013), then
a switch over to monotonic releases of 3.0.x then 3.1.x to date.

As a result, rebasing to a newer Joomla should be more straightforward.

## Build a new site

1. `mkdir newsite && cd newsite`

2. `git init`

3. `git remote add joomla-repo git://github.com/starberry/joomla-repo.git`

4. `git remote add origin git://github.com/starberry/newsite.git`

5. `git remote set-url --push origin https://github.com/starberry/newsite`

6. `git fetch --all`

7. `git pull joomla-repo j2.5.9`

8. `git push -u origin master`

