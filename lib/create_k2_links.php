#!/usr/bin/env php
<?php
# create_k2_links
# Copyright (C) 2013, Starberry Ltd
#
# by Tom Gidden <gid@starberry.tv>
# June 2013

# This script moves K2 into the lib folder of GitHub
# starberry/joomla3/httpdocs and places symlinks, presumably after
# remove_k2_links followed by a web installion of K2.
#
# As the files are probably owned by apache at this point, sudo is
# necessary.

$files = array(
    'language/en-GB/en-GB.mod_k2_users.sys.ini'	=>	'language/en-GB/en-GB.mod_k2_users.sys.ini',
    'language/en-GB/en-GB.mod_k2_tools.ini'	=>	'language/en-GB/en-GB.mod_k2_tools.ini',
    'language/en-GB/en-GB.mod_k2_content.sys.ini'	=>	'language/en-GB/en-GB.mod_k2_content.sys.ini',
    'language/en-GB/en-GB.com_k2.ini'	=>	'language/en-GB/en-GB.com_k2.ini',
    'language/en-GB/en-GB.mod_k2_content.ini'	=>	'language/en-GB/en-GB.mod_k2_content.ini',
    'language/en-GB/en-GB.mod_k2_users.ini'	=>	'language/en-GB/en-GB.mod_k2_users.ini',
    'language/en-GB/en-GB.mod_k2_user.ini'	=>	'language/en-GB/en-GB.mod_k2_user.ini',
    'language/en-GB/en-GB.mod_k2_comments.sys.ini'	=>	'language/en-GB/en-GB.mod_k2_comments.sys.ini',
    'language/en-GB/en-GB.mod_k2_tools.sys.ini'	=>	'language/en-GB/en-GB.mod_k2_tools.sys.ini',
    'language/en-GB/en-GB.mod_k2_user.sys.ini'	=>	'language/en-GB/en-GB.mod_k2_user.sys.ini',
    'language/en-GB/en-GB.mod_k2_comments.ini'	=>	'language/en-GB/en-GB.mod_k2_comments.ini',
    'administrator/language/en-GB/en-GB.plg_finder_k2.ini'	=>	'administrator/language/en-GB/en-GB.plg_finder_k2.ini',
    'administrator/language/en-GB/en-GB.mod_k2_stats.ini'	=>	'administrator/language/en-GB/en-GB.mod_k2_stats.ini',
    'administrator/language/en-GB/en-GB.plg_finder_k2.sys.ini'	=>	'administrator/language/en-GB/en-GB.plg_finder_k2.sys.ini',
    'administrator/language/en-GB/en-GB.plg_system_k2.ini'	=>	'administrator/language/en-GB/en-GB.plg_system_k2.ini',
    'administrator/language/en-GB/en-GB.com_k2.j16.ini'	=>	'administrator/language/en-GB/en-GB.com_k2.j16.ini',
    'administrator/language/en-GB/en-GB.com_k2.menu.ini'	=>	'administrator/language/en-GB/en-GB.com_k2.menu.ini',
    'administrator/language/en-GB/en-GB.com_k2.ini'	=>	'administrator/language/en-GB/en-GB.com_k2.ini',
    'administrator/language/en-GB/en-GB.plg_search_k2.ini'	=>	'administrator/language/en-GB/en-GB.plg_search_k2.ini',
    'administrator/language/en-GB/en-GB.plg_search_k2.sys.ini'	=>	'administrator/language/en-GB/en-GB.plg_search_k2.sys.ini',
    'administrator/language/en-GB/en-GB.mod_k2_stats.sys.ini'	=>	'administrator/language/en-GB/en-GB.mod_k2_stats.sys.ini',
    'administrator/language/en-GB/en-GB.plg_user_k2.sys.ini'	=>	'administrator/language/en-GB/en-GB.plg_user_k2.sys.ini',
    'administrator/language/en-GB/en-GB.plg_system_k2.sys.ini'	=>	'administrator/language/en-GB/en-GB.plg_system_k2.sys.ini',
    'administrator/language/en-GB/en-GB.mod_k2_quickicons.sys.ini'	=>	'administrator/language/en-GB/en-GB.mod_k2_quickicons.sys.ini',
    'administrator/language/en-GB/en-GB.plg_user_k2.ini'	=>	'administrator/language/en-GB/en-GB.plg_user_k2.ini',
    'administrator/language/en-GB/en-GB.mod_k2.j16.ini'	=>	'administrator/language/en-GB/en-GB.mod_k2.j16.ini',
    'administrator/language/en-GB/en-GB.mod_k2_quickicons.ini'	=>	'administrator/language/en-GB/en-GB.mod_k2_quickicons.ini',
    'administrator/modules/mod_k2_quickicons'	=>	'administrator/modules/mod_k2_quickicons',
    'administrator/modules/mod_k2_stats'	=>	'administrator/modules/mod_k2_stats',
    'administrator/components/com_k2'	=>	'administrator/components/com_k2',
    'plugins/user'	=>	'plugins/user/k2',
    'plugins/search'	=>	'plugins/search/k2',
    'plugins/josetta_ext'	=>	'plugins/josetta_ext',
    'plugins/finder'	=>	'plugins/finder/k2',
    'plugins/system'	=>	'plugins/system/k2',
    'modules/mod_k2_content'	=>	'modules/mod_k2_content',
    'modules/mod_k2_tools'	=>	'modules/mod_k2_tools',
    'modules/mod_k2_comments'	=>	'modules/mod_k2_comments',
    'modules/mod_k2_user'	=>	'modules/mod_k2_user',
    'modules/mod_k2_users'	=>	'modules/mod_k2_users',
    'components/com_k2'	=>	'components/com_k2',
    'media/k2'	=>	'media/k2'
);

$hpath = __DIR__."/../httpdocs";
$lpath = __DIR__."/k2";

// Get the dev user
$stat = stat(__FILE__);
$uid = posix_getpwuid($stat['uid']);

foreach ($files as $lfn=>$hfn) {
    if(file_exists("$hpath/$hfn") and !rchown("$hpath/$hfn", intval($uid))) {
        fwrite(STDERR, "This script needs higher permissions to run: use sudo\n");
        exit (1);
    }
}

foreach ($files as $lfn=>$hfn) {
    if(is_link("$hpath/$hfn")) {
        print "# $hpath/$hfn is already a symlink\n";
    }
    else if(is_dir("$hpath/$hfn")) {
        // If it's a folder, then we should delete the lib version (if it
        // exists) so we can move the httpdocs version into place
        if(is_dir("$lpath/$lfn")) {
            rrmdir("$lpath/$lfn");
            print "rmdir '$lpath/$lfn'\n";
        }
        // Move the httpdocs version to lib
        print "mv '$hpath/$hfn' '$lpath/$lfn'\n";
        rename("$hpath/$hfn", "$lpath/$lfn");
    }
    else if(is_file("$hpath/$hfn")) {
        // If it's a file, then delete the lib version (if it exists) so
        // we can move the httpdocs version into place
        if(is_file("$lpath/$lfn")) {
            unlink("$lpath/$lfn");
        }
    }

    // Construct the correct relative path
    $hdir = dirname($hfn);
    $path = "../lib/k2/$lfn";
    while($hdir !== '.') {
        $hdir = dirname($hdir);
        $path = "../$path";
    }

    // And symlink it into the httpdocs tree
    $hparent = dirname("$hpath/$hfn");
    if(!is_dir($hparent))
        mkdir($hparent, 0775, true);
    symlink($path, "$hpath/$hfn");
    print "ln -s $path '$hpath/$hfn'\n";
}


function rrmdir($dir) {
    foreach(glob($dir . '/*') as $file) {
        if(is_dir($file))
            rrmdir($file);
        else
            unlink($file);
    }
    rmdir($dir);
}

function rchown($dir, $uid) {
    foreach(glob($dir . '/*') as $file) {
        if(!chown($file, intval($uid)))
            return false;
        if(is_dir($file))
            if(!rchown($file, $uid))
                return false;
    }
    return true;
}
