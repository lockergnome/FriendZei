<?php
require 'config.php';

$session = $FB->getSession();
$loggedIn = ($session['uid']) ? true : false;
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"
      xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>FriendZei</title>
    <style type="text/css">
    html, body {
        font-family: Tahoma, sans-serif;
    }
    #header {
        width: 100%;
        padding: 0;
        margin: 0;
    }
    #content {
        width: 100%;
        padding: 0;
        margin: 0;
    }
    #col1 {
        float: left;
        width: 45%;
    }
    #col2 {
        float: right;
        width: 45%;
    }
    #footer {
        clear: both;
        font-size: 10px;
        padding: 5px;
        text-align: right;
    }
    .block {
        border: 1px solid #1D4088;
        margin: 15px 0;
    }
    .block .header {
        background-color: #627AAD;
        color: #FFF;
        font-size: 12px;
        font-weight: bold;
        padding: 5px 10px;
    }
    .block .content {
        padding: 10px;
    }
    .block .content ul {
        list-style-type: none;
        margin: 0;
        padding: 0;
    }
    </style>
</head>
<body>
    <div id="wrapper">
        <div id="header">
            <h1>FriendZei</h1>
        </div>
        <div id="content">
            <?php if (!$loggedIn) { ?>
            <div class="block">
                <div class="header">
                    Permission Required
                </div>
                <div class="content">
                    You must first log in to Facebook/grant FriendZei permission to access your data before you can continue.<br/>
                    <fb:login-button perms="read_stream"></fb:login-button>
                </div>
            </div>
            <?php } else { ?>
            <div id="col1">
                <div class="block" id="under-construction">
                    <div class="header">
                        Under Construction
                    </div>
                    <div class="content">
                    </div>
                </div>
                <div class="block" id="top-commenters">
                    <div class="header">
                        Top Commenters This Week
                    </div>
                    <div class="content">
                    </div>
                </div>
            </div>
            <div id="col2">
                <div class="block" id="top-commented-posts">
                    <div class="header">
                        Top Most Commented Wall Posts
                    </div>
                    <div class="content">
                    </div>
                </div>
                <div class="block" id="top-liked-posts">
                    <div class="header">
                        Top Most Liked Wall Posts
                    </div>
                    <div class="content">
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
        <div id="footer">
            Concept by <a href="http://chris.pirillo.com" target="_blank">Chris Pirillo</a><br/>
            Development by <a href="http://eddieringle.com" target="_blank">Eddie Ringle</a><br/>
            Hosted by <a href="http://idlesoft.net" target="_blank">Idlesoft</a>
        </div>
        <div id="fb-root"></div>
        <script type="text/javascript" src="http://connect.facebook.net/en_US/all.js"></script>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
        <script>
        function fillWithContent(selector, content, anim) {
            if (anim === true) {
                $(selector).slideUp('slow', function() {
                    $(selector).html(content);
                    $(selector).slideDown('slow');
                });
            } else {
                $(selector).html(content);
            }
        }
        function topCommenters() {
            $.getJSON('topCommenters.php?json=1&uid=<?php echo $session['uid']; ?>&since=-1%20week&count=1000', function(resp) {
                var users = "<ul>";
                for (c in resp) {
                    users = users + "<li>";
                    users = users + "<img src=\"http://graph.facebook.com/" + resp[c]['uid'] + "/picture\"></img>";
                    users = users + resp[c]['name'];
                    users = users + ": " + resp[c]['count'] + " comment(s).";
                    users = users + "</li>";
                }
                users = users + "</ul>";
                fillWithContent('#top-commenters div.content', users, true);
            });
        }
        function runAway() {
            // redirect to the Facebook homepage
        }
        function continueJourney() {
            fillWithContent('.block div.content', 'Loading... <img src="spinner.gif" alt="Please wait."></img>');
            topCommenters();
        }
        window.fbAsyncInit = function() {
            FB.init({appId: '159073957460563', status: true, cookie: true, xfbml: true});
            FB.Event.subscribe('auth.sessionChange', function(resp) {
                window.location.reload();
            });
            FB.api('/me', function(resp) {
                fillWithContent("#under-construction div.content", "Logged in as " + resp.name + "; UID: " + resp.id, true);
            });
            <?php if ($loggedIn) { ?>
            continueJourney();
            <?php } ?>
        };
        </script>
    </div>
</body>
</html>
