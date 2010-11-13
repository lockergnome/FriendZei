<?php
/**
 * Top Commenters
 */

require 'config.php';

function topCommenters($uid, $since, $count) {
    global $FB;
    $posts = $FB->api('/' . $uid . '/posts', 'get', array('since' => $since, 'limit' => 1000));
    $usersCount = array();
    foreach ($posts['data'] as $p) {
        if ($p['comments'] != null && $p['comments']['data'] != null) {
            foreach ($p['comments']['data'] as $c) {
                if (!array_key_exists($c['from']['id'], $usersCount)) {
                    $usersCount[$c['from']['id']] = array(
                            'uid' => $c['from']['id'],
                            'name' => $c['from']['name'],
                            'count' => 1
                    );
                } else {
                    $usersCount[$c['from']['id']]['count']++;
                }
            }
        }
    }

    sort($usersCount);

    $c = count($usersCount) - 1;
    for ($i = 0; $i <= $c; $i++) {
        for ($j = $c; $j > $i; $j--) {
            if ($usersCount[$j - 1]['count'] < $usersCount[$j]['count']) {
                $tmp = $usersCount[$j - 1];
                $usersCount[$j - 1] = $usersCount[$j];
                $usersCount[$j] = $tmp;
            }
        }
    }

    return json_encode($usersCount);
}

if ($_GET['json'] == 1) {
    header('Content-type: application/json');
    echo topCommenters($_GET['uid'], $_GET['since'], $_GET['count']);
}

