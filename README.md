joomla-repo
===========

Base copy of Joomla with "standard" extras, eg. K2

This repo is constructed using git-subtree to merge a base Joomla install with a base K2 install,
and plumb the K2 install into the Joomla httpdocs using symlinks.

This relies on `starberry/joomla-cms` being branched: convert all tags to branches. This may not be necessary with the use of git-subtree, but it does make the organisation somewhat simpler.

## Build a new site

1. `mkdir newsite && cd newsite`

2. `git init`

3. `git remote add joomla-repo git://github.com/starberry/joomla-repo.git`

4. `git remote add origin git://github.com/starberry/newsite.git`

5. `git remote set-url --push origin https://github.com/starberry/newsite`

6. `git fetch --all`

7. `git pull joomla-repo j2.5.9`

8. `git push -u origin master`


## Add new version of Joomla

1. First, make sure `starberry/joomla-cms` has a branch for the new version, eg. "v2.5.9". This can be created from a tag.

2. `git fetch joomla`

3. `git checkout -b j2.5.9`

4. `git merge master`
 
5. `git subtree pull --squash -P httpdocs joomla v2.5.9`

6. `git subtree pull --squash -P lib/k2 getk2 master`

7. `sudo php lib/create_k2_links.php`

8. `git add -A`

9. `git commit -m'Added K2 Links'`

10. `git push --all`


## Setup of repo from scratch

1. Setup new repo with config:
```
[core]
        repositoryformatversion = 0
        filemode = true
        bare = false
        logallrefupdates = true
[remote "joomla"]
        fetch = +refs/heads/*:refs/remotes/joomla/*
        pushurl = https://github.com/starberry/joomla-cms
        url = https://github.com/starberry/joomla-cms
#       url = git@github.com:starberry/joomla-cms.git
[remote "getk2"]
        fetch = +refs/heads/*:refs/remotes/getk2/*
        pushurl = https://github.com/starberry/getk2
        url = https://github.com/starberry/getk2
#       url = git@github.com:starberry/getk2.git
[remote "origin"]
        fetch = +refs/heads/*:refs/remotes/origin/*
        pushurl = https://github.com/starberry/joomla-repo
        url = https://github.com/starberry/joomla-repo
#       url = git@github.com:starberry/joomla-repo.git
[branch "master"]
        remote = origin
        merge = refs/heads/master
```

2. `git fetch getk2`

3. `git fetch joomla`

4. `mkdir lib`

5. Manually pull in `etc`, `lib/*.php`, `.gitignore`, and copy `.git/config` as `.gitconfig` for reference

6. `git add -A`

7. `git commit`

8. `git checkout master`

9. `git checkout -b j2.5.7`

10. `git subtree add --squash -P lib/k2 getk2/master`

11. `git subtree add --squash -P httpdocs joomla/v2.5.7`

12. `php lib/create_k2_links.php`

13. `git add -A`

14. `git commit -m'Added K2 Links'`

(repeat steps 8-14 as needed)

15. Push --all to origin
