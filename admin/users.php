<?php
session_name('ezApps');
session_start();
require '../tyler_base/global/connect.php';
require '../tyler_base/global/config.php';
$page['name'] = locale('usermanage');

if (!loggedIn) {
    header('Location: '.DOMAIN.'/login');
    exit();
}

//Check if they're staff and have permissions
if (super_admin === 'false' && view_users === 'false') {
    notify('danger', locale('accessdenied'), DOMAIN.'/index');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require '../tyler_base/page/header.php'; ?>
</head>

<body>
    <?php require '../tyler_base/page/nav.php'; ?>
    <?php require '../tyler_base/page/s-nav.php'; ?>
    <div class="lime-container">
        <div class="lime-body">
            <div class="container">
                <div id="ezaMsg"><?php if (isset($message)) { echo $message; } ?></div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo locale('users'); ?></h5>

                                <div class="table-responsive">
                                    <table id="usersTable" class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col" width="15%"><?php echo locale('id'); ?></th>
                                                <th scope="col" width="25%"><?php echo locale('displayname'); ?></th>
                                                <th scope="col" width="25%"><?php echo locale('usergroup'); ?></th>
                                                <th scope="col" width="25%"><?php echo locale('joined'); ?></th>
                                                <th scope="col"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                $getUsersDB = "SELECT * FROM users";
                                                $getUsersDB = $pdo->prepare($getUsersDB);
                                                $getUsersDB->execute();
                                                $usersDB = $getUsersDB->fetchAll(PDO::FETCH_ASSOC);
                                                
                                                foreach ($usersDB as $userDB) {
                                                    $getUGDB = "SELECT name FROM `usergroups` WHERE id = ?"; 
                                                    $getUGDB = $pdo->prepare($getUGDB); 
                                                    $getUGDB->execute([$userDB['usergroup']]); 
                                                    $usersgroupDB = $getUGDB->fetch();
                                                    
                                                    echo '<tr><td>'.$userDB['id'].'</td>';
                                                    echo '<td>'.$userDB['display_name'].'</td>';
                                                    echo '<td>'.$usersgroupDB['name'].'</td>';
                                                    echo '<td>'.$userDB['joined'].'</td>';
                                                    echo '<td><a class="btn btn-primary btn-sm" href="'.DOMAIN.'/user?id='.$userDB['id'].'" role="button">'.locale('profile').'</a></td></tr>';
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php require '../tyler_base/page/copyright.php'; ?>
    </div>

    <?php require '../tyler_base/page/footer.php'; ?>
</body>

</html>