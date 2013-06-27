#!/usr/bin/env php
<?php
# remove_k2_links
# Copyright (C) 2013, Starberry Ltd
#
# by Tom Gidden <gid@starberry.tv>
# June 2013

# This script removes the symlinks in place in the GitHub
# starberry/joomla3/httpdocs for K2, so it can be installed via the web
# front-end, to allow K2 to install the database bits.
#
# Once installation is complete, this should be followed by a call of
# 'sudo php create_k2_links.php' to reinstate them.


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
$ppath = __DIR__;

foreach ($files as $pfn=>$hfn) {
    if(is_link("$hpath/$hfn") or is_file("$hpath/$hfn")) {
        unlink("$hpath/$hfn");
        print "rm '$hpath/$hfn'\n";
    }
    else if(is_dir("$hpath/$hfn")) {
        rrmdir("$hpath/$hfn");
        print "# $hpath/$hfn is a directory, not a symlink\n";
    }
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
