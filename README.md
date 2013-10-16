joomla-repo
===========

Base copy of Joomla with "standard" extras, eg. K2

This was originally set up using tags on a monotonic master branch. However, it's
now converted so each version of Joomla is a separate branch. This allows
tweaks to the standard config.

## Build a new site

```
# Create a new _uninitialised_ repo on GitHub, eg "newsite".
# then..

# Start with a clean, empty repo which we will eventually push to GitHub
mkdir newsite && cd newsite
git init

# Alternatively, you can start with a fresh vhost, but you might need to
# handle a few merge conflicts.

# We want to pull the joomla-repo onto this new project
git remote add joomla-repo git://github.com/starberry/joomla-repo.git

# Fetch all references, including Joomla version tags
git fetch --all

# Need to initialise the repo's HEAD with a single dummy commit. This can be removed later.
# Alternatively, you could commit the vhost's config files.
touch dummy; git add dummy; git commit -m'Dummy commit' dummy

# Merge the desired version of Joomla onto this new repo
git pull joomla-repo/j3.1.1

# Set up the GitHub repo in GitHub and set the permissions.

# Add the GitHub repo as the origin
git remote add origin git://github.com/starberry/newsite.git
git remote set-url --push origin https://github.com/starberry/newsite

# And push the combined Joomla and fresh repo to the new project
git push -u origin master
```

