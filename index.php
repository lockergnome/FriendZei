<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"
      xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>Community Zeitgeist</title>
    <style type="text/css">
    html, body {
        font-family: Tahoma, sans-serif;
    }
    #header {
        width: 100%;
        padding: 0;
        margin: 0;
    }
    #precontent, #content {
        width: 100%;
        padding: 0;
        margin: 0;
    }
    #content {
        display: none;
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
            <h1>Community Zeitgeist</h1>
        </div>
        <div id="precontent">
            <div class="block">
                <div class="header">
                    Permission Required
                </div>
                <div class="content">
                    You must first log in to Facebook/grant Community Zeitgeist permission to access your data before you can continue.<br/>
                    <button id="loginbtn">Login/Grant Permissions</button>
                </div>
            </div>
        </div>
        <div id="content">
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
            FB.api('/me/posts', { since: '-1 week', limit: 1000 }, function(resp) {
                var usersCount = new Array();
                for (p in resp.data) {
                    if (resp.data[p].comments !== undefined && resp.data[p].comments.data !== undefined) {
                        for (c in resp.data[p].comments.data) {
                            if (usersCount[resp.data[p].comments.data[c].from.id] === undefined) {
                                usersCount[resp.data[p].comments.data[c].from.id] = 1;
                            } else {
                                usersCount[resp.data[p].comments.data[c].from.id]++;
                            }
                        }
                    }
                }
                var users = "<ul>";
                for (c in usersCount) {
                    var userName = 'Billy Bob Joe';
                    FB.api('/'+c, function(resp) {
                        userName = resp.name;
                    });
                    users = users + "<li>";
                    users = users + "<img src=\"http://graph.facebook.com/" + c + "/picture\"></img>";
                    users = users + userName;
                    users = users + ": " + usersCount[c] + " comment(s).";
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
            $('#precontent').hide();
            $('#content').show();
            fillWithContent('.block div.content', 'Loading... <img src="spinner.gif" alt="Please wait."></img>');
            topCommenters();
        }
        var cb = function(response) {
            if (response.session) {
                if (response.perms) {
                    continueJourney();
                } else {
                    runAway();
                }
            } else {
            }
        };
        FB.init({appId: '159073957460563', status: true, cookie: true, xfbml: true});
        $('#loginbtn').click(function() {
            FB.login(cb, { perms:'read_stream' });
        });
        FB.api('/me', function(resp) {
            fillWithContent("#under-construction div.content", "Logged in as " + resp.name + "; UID: " + resp.id, true);
        });
        </script>
    </div>
</body>
</html>
