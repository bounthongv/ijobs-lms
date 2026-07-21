<?php 
    require '../../../connect.php';
    $prok_id = $_REQUEST['prok_id'];
    $sql = $conn->prepare("SELECT * FROM district_korea WHERE prok_id = ?");
    $sql->execute([$prok_id]);
    foreach($sql as $row):
?>
    <option value="<?= $row['disk_id'] ?>"><?= $row['disk_name'] ?></option>
<?php endforeach ?>