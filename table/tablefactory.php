<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package socialwiki
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

Global $CFG;

$trustcombiner = 'max';  // Default for now, should remove entirely.
require_once($CFG->dirroot . '/mod/socialwiki/table/usertable.php');
require_once($CFG->dirroot . '/mod/socialwiki/table/topictable.php');
require_once($CFG->dirroot . '/mod/socialwiki/table/versiontable.php');

$t = null;
switch ($tabletype) {
    case "recentlikes":      // User likes.
        $t = versiontable::likes_versiontable($userid, $swid, $trustcombiner);
        break;
    case "faves":            // User favourites.
    case "userfaves":        // .Favourites by another user.
        $t = versiontable::favourites_versiontable($userid, $swid, $trustcombiner);
        break;
    case "mypageversions":   // User pages.
    case "userpageversions": // Pages by another user.
        $t = versiontable::user_versiontable($userid, $swid, $trustcombiner);
        break;
    case "versionsfollowed": // Versions by followed users.
        $t = versiontable::followed_versiontable($userid, $swid, $trustcombiner);
        break;
    case "newpageversions":  // New versions.
        $t = versiontable::new_versiontable($userid, $swid, $trustcombiner);
        break;
    case "allpageversions":  // All versions.
        $t = versiontable::all_versiontable($userid, $swid, $trustcombiner);
        break;
    case "followedusers":    // Followed users.
        $t = usertable::followed_usertable($userid, $swid);
        break;
    case "followers":        // Followers.
        $t = usertable::followers_usertable($userid, $swid);
        break;
    case "allusers":         // All users.
        $t = usertable::all_usertable($userid, $swid);
        break;
    case "alltopics":        // All pages (grouped versions).
        $t = topictable::all_topictable($userid, $swid);
        break;
    default:
        $tabletype = 'unknowntabletype ' . $tabletype;
}

if ($t != null) {
    return $t->get_as_html();
} else {
    $message = get_string('no' . $tabletype, 'socialwiki');
    return "<table><tr><td>$message</td></tr></table>";
}
