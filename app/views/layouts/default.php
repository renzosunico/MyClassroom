<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Classroom</title>
    <link rel="icon" type="image/png" href="/bootstrap/img/favicon.png" />
    <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="/bootstrap/css/customized.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Candal' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Droid+Sans' rel='stylesheet' type='text/css'>
  </head>

  <body>

    <head>
        <nav class="navbar navbar-default navbar-fixed-top">
          <div class="container-fluid">
            <div class="navbar-header">
              <a class="navbar-brand" href="<?php encode_quotes(url('thread/index')) ?>">My Classroom</a>
                  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#buttontoggle" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                  </button>
            </div>

            <div class="collapse navbar-collapse navbar-right" id="buttontoggle">
              <ul class="nav navbar-nav">
                <?php if(preg_match('/(registration|user\/login)/', $_SERVER['REQUEST_URI'])): ?>
                  <?php redirect_to_index() ?>
                <?php else: ?>
                  <?php redirect_to_login() ?>
                <?php endif ?>

                <?php if(isset($page) && preg_match('/^login$/', $page)): ?>
                  <li><a href="<?php echo(url('user/registration')) ?>">Sign up</a></li>
                <?php endif ?>

                <?php if(isset($_SESSION['username'])): ?>
                  <li> <a href="<?php echo isset($_SESSION['username']) ? encode_quotes(url('thread/index')) : "" ?>">Home</a>
                  <li> <a href="<?php echo isset($_SESSION['username']) ? encode_quotes(url('user/profile',array('user_id' => $_SESSION['userid']))) : "" ?>">Profile</a>
                  <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $_SESSION['username'] ?><span class="caret"></span></a>
                    <ul id="nav-dropdown" class="dropdown-menu">
                      <li><a href="<?php encode_quotes(url('user/edit')) ?>">Edit profile</a></li>
                      <li><a href="<?php encode_quotes(url('user/logout')) ?>">Logout</a></li> 
                    </ul>
                  </li>
                <?php endif ?>
              </ul>
            </div>

          </div>
        </nav>
    </head>

    <div class="container">
      <?php echo $_content_ ?>
    </div>

    <script>console.log(<?php encode_quotes(round(microtime(true) - TIME_START, 3)) ?> + 'sec');</script>
    <script src="/bootstrap/js/jquery-2.1.4.min.js"></script>
    <script src="/bootstrap/js/bootstrap.js"></script>


  </body>
  
</html>
