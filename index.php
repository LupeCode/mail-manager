<?php

use Aura\Session\SessionFactory;
use Aura\Sql\ExtendedPdo;

require_once 'config.php';
require_once 'vendor/autoload.php';
$session_factory = new SessionFactory;
$session         = $session_factory->newInstance($_COOKIE)->getSegment(MM_SESSION_NAME);

?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .container {
                max-width: 1333px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <?php

            if (isset($_REQUEST['password']) && $_REQUEST['password'] === MM_PASSWORD) {
                $session->set("auth'd", true);
            }

            $db = new ExtendedPdo(MM_DATABASE_TYPE . ':host=' . MM_DATABASE_HOST . ';dbname=' . MM_DATABASE_NAME, MM_DATABASE_USER, MM_DATABASE_PASS);

            $Domain = $db->fetchOne(MM_QUERY_DOMAIN)['name'];

            if (isset($_REQUEST['do'])) {
                if ($_REQUEST['do'] === 'new_mail_user') {
                    $Query      = MM_QUERY_NEW_USER;
                    $BindValues = ['email' => $_REQUEST['new_mail_user'] . '@' . $Domain, 'password' => $_REQUEST['new_mail_pass']];
                } elseif ($_REQUEST['do'] === 'change_password') {
                    $Query      = MM_QUERY_CHANGE_PASSWORD;
                    $BindValues = ['email' => $_REQUEST['change_password_user'] . '@' . $Domain, 'password' => $_REQUEST['change_mail_pass']];
                } elseif ($_REQUEST['do'] === 'new_alias') {
                    $Query      = MM_QUERY_FIND_ALIAS;
                    $BindValues = ['dest' => $_REQUEST['new_alias_destination']];
                    $Users      = $db->fetchAll($Query, $BindValues);
                    $DID        = null;
                    foreach ($Users as $user) {
                        if ($_REQUEST['new_alias_destination'] === $user['email']) {
                            $DID = $user['id'];
                            break;
                        }
                    }
                    $Query      = MM_QUERY_NEW_ALIAS;
                    $BindValues = ['did' => $DID, 'source' => $_REQUEST['new_alias_source'], 'destination' => $_REQUEST['new_alias_destination']];
                } elseif ($_REQUEST['do'] === 'delete_email') {
                    $Query      = MM_QUERY_DELETE_USER;
                    $BindValues = ['id' => $_REQUEST['email_id']];
                } elseif ($_REQUEST['do'] === 'delete_alias') {
                    $Query      = MM_QUERY_DELETE_ALIAS;
                    $BindValues = ['id' => $_REQUEST['alias_id']];
                }
                $sth = $db->perform($Query, $BindValues);
            }

            $Query = MM_QUERY_SELECT_USERS;
            $Users = $db->fetchAll($Query);

            $Query   = MM_QUERY_SELECT_ALIAS;
            $Aliases = $db->fetchAll($Query);

            if ($session->get("auth'd") === true) {
                ?>
                <div class="row">
                    <div class="col-12 col-md-4 col-lg-4">
                        <div class="row">
                            <div class="col-12">
                                <form method="post">
                                    <fieldset>
                                        <legend>Create new Mail User</legend>
                                        <div class="form-group">
                                            <label>
                                                User:
                                                <input type="text" name="new_mail_user" class="form-control"/>
                                            </label>
                                        </div>
                                        <div class="form-group">
                                            <label>
                                                Pass:
                                                <input type="password" name="new_mail_pass" class="form-control"/>
                                            </label>
                                        </div>
                                        <input type="hidden" name="do" value="new_mail_user"/>
                                        <button type="submit" class="btn btn-primary">
                                            <span>Add User</span>
                                        </button>
                                    </fieldset>
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <form method="post">
                                    <fieldset>
                                        <legend>Change Password</legend>
                                        <div class="form-group">
                                            <label>
                                                User:
                                                <select name="change_password_user" class="form-control">
                                                    <?php
                                                    foreach ($Users as $user) {
                                                        echo "<option value='{$user['id']}'>{$user['email']}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </label>
                                        </div>
                                        <div class="form-group">
                                            <label>
                                                Pass:
                                                <input type="password" name="change_mail_pass" class="form-control"/>
                                            </label>
                                        </div>
                                        <input type="hidden" name="do" value="change_password"/>
                                        <button type="submit" class="btn btn-primary">
                                            <span>Update Password</span>
                                        </button>
                                    </fieldset>
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <form method="post">
                                    <fieldset>
                                        <legend>Add Alias</legend>
                                        <div class="form-group">
                                            <label>
                                                Source:
                                                <input type="text" name="new_alias_source" class="form-control"/>
                                            </label>
                                        </div>
                                        <div class="form-group">
                                            <label>
                                                Destination:
                                                <input type="text" name="new_alias_destination" class="form-control"/>
                                            </label>
                                        </div>
                                        <input type="hidden" name="do" value="new_alias"/>
                                        <button type="submit" class="btn btn-primary">
                                            <span>Add Alias</span>
                                        </button>
                                    </fieldset>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-8 col-lg-8">
                        <div class="row">
                            <div class="col-12">
                                <fieldset>
                                    <legend>Current Accounts</legend>
                                    <?php
                                    foreach ($Users as $user) {
                                        echo "<div class='row'>";
                                        echo "<div class='col'>{$user['email']}</div>";
                                        echo "<div class='col-1'><a href='index.php?do=delete_email&email_id={$user['id']}' title='Delete'>X</a></div>";
                                        echo "</div>\n";
                                    }
                                    ?>
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <fieldset>
                                    <legend>Current Aliases</legend>
                                    <?php
                                    foreach ($Aliases as $alias) {
                                        echo "<div class='row'>";
                                        echo "<div class='col text-right'>{$alias['source']}</div>";
                                        echo "<div class='col-1'>&rarr;</div>";
                                        echo "<div class='col'>{$alias['destination']}</div>";
                                        echo "<div class='col-1'><a href='index.php?do=delete_alias&alias_id={$alias['id']}' title='Delete'>X</a></div>";
                                        echo "</div>\n";
                                    }
                                    ?>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            } else {
                ?>
                <form method="post">
                    <fieldset>
                        <div class="form-group">
                            <label>
                                Authorize Yourself:
                                <input type="password" name="password" class="form-control"/>
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <span>Go!!</span>
                        </button>
                    </fieldset>
                </form>
                <?php
            }
            ?>
            <br/>
            <span>Manage Mail Accounts for <?= $Domain; ?></span>
        </div>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </body>
</html>
