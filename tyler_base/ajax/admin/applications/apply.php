<?php
session_name('ezApps');
session_start();
require ('../../../../tyler_base/global/connect.php');
require ('../../../../tyler_base/global/config.php');

$appID = strip_tags($_GET['appID']);

$sql = "SELECT * FROM applications WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$appID]);
$appInfo = $stmt->fetch(PDO::FETCH_ASSOC);

$_SESSION['applying_for'] = $appID;
$_SESSION['applying_for_name'] = $appInfo['name'];
$_SESSION['applying_for_status'] = $appInfo['status'];
$_SESSION['applying_for_format'] = $appInfo['format'];
$_SESSION['applying_for_desc'] = $appInfo['description'];
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title></title>
</head>

<body>
    <form method="post">
        <div class="row">
            <div class="col-md-12">
            <?php if($appInfo['status'] === "CLOSED"): ?>
                <div class="alert alert-danger m-b-lg" role="alert">
                    <?php echo locale('appclosed'); ?>
                </div>
            <?php else: ?>
                <?php if($appInfo['description'] <> NULL || $appInfo['description'] <> ""): ?>
                    <div class="alert alert-info m-b-lg" role="alert">
                        <strong><?php echo locale('appdescription'); ?>:</strong><hr>
                        <?php echo nl2br($appInfo['description']); ?>
                    </div>
                <?php endif; ?>
                <div class="form-group">
                    <label for="app_name"><?php echo locale('appformatname'); ?></label>
                    <input type="text" class="form-control" value="<?php echo $appInfo['name']; ?>" required disabled>
                </div> 
                <?php
                //Select app questions
                $appQuestions = "SELECT * FROM applicant_inputs WHERE aid = ?";
                $appQuestions = $pdo->prepare($appQuestions);
                $appQuestions->execute([$appID]);
                $appqDB = $appQuestions->fetchAll(PDO::FETCH_ASSOC);
                                                
                foreach ($appqDB as $question) {
                    echo '<div class="form-group">
                    <label for="'.$question['ukey'].'">'.$question['question'].'</label>
                    <input type="text" class="form-control" name="'.$question['ukey'].'" id="'.$question['ukey'].'" placeholder="'.$question['question'].'">
                    </div>';
                }
                ?>
                <label><i><?php echo locale('autodenialnote'); ?></i></label>
            <?php endif; ?>
            </div>
        </div>
        <hr>
        <button type="submit" name="applyApp" class="btn btn-primary btn-md float-right mb-3"><?php echo locale('submitapp'); ?></button>
    </form>
</body>

</html>