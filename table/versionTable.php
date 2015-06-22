<?php

require_once($CFG->dirroot . "/mod/socialwiki/locallib.php");
require_once($CFG->dirroot . "/mod/socialwiki/table/table.php");
require_once($CFG->dirroot . "/mod/socialwiki/peer.php");

Global $CFG, $PAGE, $USER;

/*
 * how to do this: 
 * 1 - get a list of pages from DB.
 * 2 - choose the headers you want, put them in an array
 * 3 - pass to function: it puts the data into a table.
 */
class versionTable extends socialwiki_table {

    //$uid and swid in parent class
    private $allpeers; //maps peerid to peer object for all peers
    private $allpages; // maps pageid to page object, with additional field $p->likers containing array of likers (peerids)
    private $combiner; // way of combining user trust indicators

    public function __construct($uid, $swid, $pages, $headers, $combiner = 'avg') {
        parent::__construct($uid, $swid, $headers);
        //$this->allpages = $pages;
        $this->get_all_likers($pages); //get all peers involved, store info in $this->allpages and this->allpeers
        // get_table_data(); ?
        $this->combiner = $combiner;
    }

    public function set_headers($h) {
        $this->headers = $h;
    }

    public function set_trust_combiner($c) {
        $this->combiner = $c;
    }

    public function get_as_HTML($tableid = 'a_table') {
        $t = "<table id=" . $tableid . " class='datatable'>";
        $tabledata = $this->get_table_data();
        //headers
        $t .= "<thead><tr>";
        foreach ($this->headers as $h) {
            if (in_array($h, array_map(
                                    function($s) {
                                return get_string($s, 'socialwiki');
                            }, array("followsim", "likesim", "popularity", "distance")
                            )
                    )) {
                $t .= "<th>" . $this->combiner . ' ' . $h . "</th>";
            } else {
                $t .= "<th>" . $h . "</th>";
            }
        }
        $t .= "</tr></thead><tbody>";

        foreach ($tabledata as $row) {
            $t .= "<tr>";
            foreach ($row as $k => $val) {
                $t .= "<td>" . $val . "</td>";
            }
            $t .= "</tr>";
        }

        $t .= "</tbody></table>";
        return $t;
    }

    /**
     * get table data structure from spec:
     * @param pages: a selected list of pages
     * @param headers: requested column headers
     * @return an array of rows, each row being an array of head=>value pairs
     */
    protected function get_table_data() {
        Global $CFG;

        $table = array();

        //$option=optional_param('option', null, PARAM_INT); //?

        foreach ($this->allpages as $page) {
            $updated = socialwiki_format_time($page->timemodified);

            $views = $page->pageviews;
            $likes = socialwiki_numlikes($page->id);

            //////get all contributors
            $contributors = socialwiki_get_contributors($page->id);
            $contrib_string = $this->make_multi_user_div($contributors);

            //$followlink;
            /*
            //TODO: show contributors
            if(socialwiki_is_following($USER->id,$page->userid,$this->swid))
              {
              $img = "<img style='width:22px; vertical-align:middle;' src='".$CFG->wwwroot."/mod/socialwiki/img/icons/man-minus.png'></img>";
              $followlink = "<a style='margin:0;'   class='socialwiki_unfollowlink socialwiki_link' href='".$CFG->wwwroot."/mod/socialwiki/follow.php?user2=".$page->userid."&from=".urlencode($PAGE->url->out()."&option=$option")."&swid=".$this->swid."&option=$option'>".$img."</a>";
              } else {
              $img = "<img style='width:22px; vertical-align:middle;' src='".$CFG->wwwroot."/mod/socialwiki/img/icons/man-plus.png'></img>";
              $followlink = "<a style='margin:0;' class='socialwiki_followlink socialwiki_link' href='".$CFG->wwwroot."/mod/socialwiki/follow.php?user2=".$page->userid."&from=".urlencode($PAGE->url->out()."&option=$option")."&swid=".$this->swid."'>".$img."</a>";
              } */

            $linkpage = "<a style='margin:0;' class='socialwiki_link' href=" . $CFG->wwwroot . "/mod/socialwiki/view.php?pageid=" . $page->id . ">" . $page->title . "</a>";

            if (socialwiki_liked($this->uid, $page->id)) {
                $unlikeimg = "<img style='width:22px;' class='socialwiki_unlikeimg unlikeimg_" . $page->id . "' alt='unlikeimg_" . $page->id . "' src='" . $CFG->wwwroot . "/mod/socialwiki/img/icons/likefilled.png'></img>";
                $likeimg = "<img style='width:22px; display:none;' class='socialwiki_likeimg likeimg_" . $page->id . "' alt='likeimg_" . $page->id . "' src='" . $CFG->wwwroot . "/mod/socialwiki/img/icons/hollowlike.png'></img>";
            } else {
                $unlikeimg = "<img style='width:22px; display:none;' class='socialwiki_unlikeimg unlikeimg_" . $page->id . "'  alt='unlikeimg_" . $page->id . "' src='" . $CFG->wwwroot . "/mod/socialwiki/img/icons/likefilled.png'></img>";
                $likeimg = "<img style='width:22px;' class='socialwiki_likeimg likeimg_" . $page->id . "'  alt='likeimg_" . $page->id . "' src='" . $CFG->wwwroot . "/mod/socialwiki/img/icons/hollowlike.png'></img>";
            }

            /////////// favorites
            $favorites = socialwiki_get_favorites($page->id, $this->swid);
            $favdiv = $this->make_multi_user_div($favorites);

            $combiner = $this->combiner;

            /* trust indicators */
            $peerpop = $this->combine_indicators($page, $combiner, "peerpopularity");
            $likesim = $this->combine_indicators($page, $combiner, "likesimilarity");
            $followsim = $this->combine_indicators($page, $combiner, "followsimilarity");
            $distance = $this->combine_indicators($page, $combiner, "networkdistance");

            $row = array(
                get_string('title', 'socialwiki') => "<div style='white-space: nowrap; width:100%;'>$likeimg$unlikeimg$linkpage</div>",
                get_string('contributors', 'socialwiki') => $contrib_string,
                get_string('updated', 'socialwiki') => $updated,
                get_string('likes', 'socialwiki') => $likes,
                get_string('views', 'socialwiki') => $views,
                get_string('favorite', 'socialwiki') => $favdiv,
                get_string('popularity', 'socialwiki') => substr($peerpop, 0, 4),
                get_string('likesim', 'socialwiki') => substr($likesim, 0, 4),
                get_string('followsim', 'socialwiki') => substr($followsim, 0, 4),
                get_string('networkdistance', 'socialwiki') => substr($distance, 0, 4)
            );
            ////////// add trust values
            $table[] = array_intersect_key($row, array_flip($this->headers)); // filter to get only the requested headers
        }
        return $table;
    }

    private function make_multi_user_div($contributors) {
        Global $CFG, $PAGE;
        $idfirst = array_pop($contributors);
        $firstctr = fullname(socialwiki_get_user_info($idfirst));
        $num = count($contributors);
        if ($num == 1) {
            $firstctr .= " and 1 other";
        } else if ($num > 1) {
            $firstctr .= " and " . $num . " others";
        }

        $ctr = "";
        if ($num != 0) {
            $ctr = "Others:\n";
            foreach (array_reverse($contributors) as $c) {
                $ctr .= fullname(socialwiki_get_user_info($c)) . "\n";
            }
        }

        if ($idfirst == $this->uid) {
            $href = "href='" . $CFG->wwwroot . "/mod/socialwiki/home.php?id=" . $PAGE->cm->id . "'";
        } else {
            $href = "href='" . $CFG->wwwroot . "/mod/socialwiki/viewuserpages.php?userid=" . $idfirst . "&subwikiid=" . $this->swid . "'";
        }

        return "<a class='socialwiki_link' " . $href . " title='$ctr'>$firstctr</a>";
    }

    /**
     * combines trust indicators obtained from the peers who like a page
     */
    private function combine_indicators($page, $reducer, $indicator) {
        $uservals = array();
        foreach ($page->likers as $u) {
            $peer = $this->allpeers[$u];

            $score = 0; // meant to stand out if errors come up
            switch ($indicator) {
                case "followsimilarity":
                    $score = $peer->followsim;
                    break;
                case "likesimilarity":
                    $score = $peer->likesim;
                    break;
                case "peerpopularity":
                    $score = $peer->popularity;
                    break;
                case "networkdistance":
                    $score = max(0, $peer->depth);
                    break;
            }
            $uservals[] = $score;
        }

        if (count($uservals) == 0)
            return 0;

        switch ($reducer) {
            case "max":
                return max($uservals);

            case "min":
                return min($uservals);

            case "avg":
                $len = count($uservals);
                return (array_reduce($uservals, function($a, $b) {
                            return $a + $b;
                        }) / $len);

            case "sum":
                return array_reduce($uservals, function($a, $b) {
                    return $a + $b;
                });
        }


        return 0.99; //kludge: just an error value
    }

    /** from list of pages, get list of users that like any of the pages, with all their relevant info
     * adds the pages to $this->allpages and the peers to the existing list of peers
     */
    private function get_all_likers($pagelist) {
        $peerids = array();
        //$this->allpages = []; //reboot this!
        foreach ($pagelist as $p) {
            $likers = socialwiki_get_likers($p->id, $this->swid); //gets list of user likers
            $p->likers = $likers;
            $this->allpages[$p->id] = $p; //add pages to list
            $peerids = array_unique(array_merge($peerids, $likers));
        }

        $this->allpeers = $this->get_peers($peerids); //see below 
        //TODO: need to merge into existing list instead of overwriting
    }

    //get peers from user ids, with all relevant info: used by above
    private function get_peers($ids) {
        //$number_of_users = socialwiki_get_user_count($this->swid);

        $me = $this->uid;
        $swid = $this->swid;

        //define function to get peer from userid
        $build_function = function ($id) use ($me, $swid) {
            return peer::socialwiki_get_peer($id, $swid, $me);
        };
        return array_combine($ids, array_map($build_function, $ids));
        //will return an associative array with peerid => peer object for each peerid
    }

    public static function getHeaders($type) {
        switch ($type) {
            case "version":
                return array(
                    get_string('title', 'socialwiki'),
                    get_string('contributors', 'socialwiki'),
                    get_string('updated', 'socialwiki'),
                    get_string('likes', 'socialwiki'),
                    get_string('views', 'socialwiki'),
                    get_string('favorite', 'socialwiki'),
                    get_string('popularity', 'socialwiki'),
                    get_string('likesim', 'socialwiki'),
                    get_string('followsim', 'socialwiki'),
                    get_string('networkdistance', 'socialwiki')
                );
            case "mystuff":
                return array(
                    get_string('title', 'socialwiki'),
                    get_string('contributors', 'socialwiki'),
                    get_string('updated', 'socialwiki'),
                    get_string('likes', 'socialwiki'),
                    get_string('views', 'socialwiki'),
                    get_string('favorite', 'socialwiki')
                );
            case "topics":
                return array(
                    get_string('title', 'socialwiki'),
                    get_string('versions', 'socialwiki'),
                    get_string('views', 'socialwiki'),
                    get_string('likes', 'socialwiki')
                );
            case "user":
                return array(
                    get_string('popularity', 'socialwiki'),
                    get_string('likesim', 'socialwiki'),
                    get_string('followsim', 'socialwiki'),
                    get_string('networkdistance', 'socialwiki')
                );
            default:
                return array('error in getHeaders:' . $type);
        }
    }

    //=======================================================================
    // factory method
    //=======================================================================

    public static function makeFavouritesTable($uid, $swid, $combiner = 'avg') {
        if ($favs = socialwiki_get_user_favorites($uid, $swid)) {
            $headers = versionTable::getHeaders('mystuff');
            return new versionTable($uid, $swid, $favs, $headers, $combiner);
        } else {
            return null;
        }
    }

    public static function makeRecentLikesTable($uid, $swid, $combiner = 'avg') {
        $likes = socialwiki_get_liked_pages($uid, $swid);
        
        if (!empty($likes)) {
            $headers = versionTable::getHeaders('mystuff');
            return new versionTable($uid, $swid, $likes, $headers, $combiner);
        } else {
            return null;
        }
    }

    public static function makeFollowedVersionsTable($userid, $swid) {
        $pages = socialwiki_get_pages_from_followed($userid, $swid);

        if ($pages) {
            $headers = versionTable::getHeaders('version');
            return new versionTable($userid, $swid, $pages, $headers);
        }
        return null;
    }

    public static function makeNewVersionsTable($uid, $swid, $combiner = 'avg') {
        $pages = socialwiki_get_updated_pages_by_subwiki($swid, $uid);

        if ($pages) {
            $headers = versionTable::getHeaders('version');
            return new versionTable($uid, $swid, $pages, $headers, $combiner);
        }
        return null;
    }

    public static function makeAllVersionsTable($uid, $swid, $combiner = 'avg') {
        $pages = socialwiki_get_page_list($swid);

        if (!empty($pages)) {
            $headers = versionTable::getHeaders('version');
            return new versionTable($uid, $swid, $pages, $headers, $combiner);
        }
    }

    public static function makeUserVersionsTable($uid, $swid, $combiner = 'avg') {
        $pages = socialwiki_get_user_page_list($uid, $swid);

        if (!empty($pages)) {
            $headers = versionTable::getHeaders('mystuff');
            return new versionTable($uid, $swid, $pages, $headers, $combiner);
        }
    }

    //public static function 

    public static function makeHTMLVersionTable($uid, $swid, $pages, $type, $tabid) {
        $headers = versionTable::getHeaders($type);
        $thetable = new versionTable($uid, $swid, $pages, $headers);
        //echo $thetable;
        return $thetable->get_as_HTML($tabid); // defined in parent class
    }

}
