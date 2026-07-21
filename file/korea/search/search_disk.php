<?php
require '../../../connect.php';
$all = $_REQUEST['all'];
$prok_id = $_REQUEST['prok_id'];

$p1 = $all == '' ? '' : "AND (disk_name LIKE '%$all%' OR agen_name LIKE '%$all%' OR disk_name_lao LIKE '%$all%' OR disk_code LIKE '%$all%') ";
$p2 = $prok_id == '' ? '' : "AND disk.prok_id = '$prok_id' ";

$sql = $conn->prepare("SELECT * FROM district_korea as disk
    INNER JOIN province_korea as prok ON disk.prok_id=prok.prok_id
    WHERE 1=1 $p1 $p2
    ORDER BY disk_id ASC
    ");
$sql->execute();
$num = 1;
?>
<?php foreach ($sql as $i => $emp):
?>
  <tr>

    <td style="color:#94a3b8; font-weight:600;"><?= $i + 1 ?></td>

    <td style="font-weight:600; color:#1e293b;">
      <?= htmlspecialchars($emp['disk_id'] ?? '') ?>
    </td>

    <td style="color:#64748b; font-weight:500;">
      <?= htmlspecialchars($emp['disk_name'] ?? '') ?>
    </td>

    <td style="color:#64748b;">
      <?= htmlspecialchars($emp['disk_name_lao'] ?? '') ?>
    </td>
    <td style="color:#64748b;">
      <?= htmlspecialchars($emp['prok_name'] ?? '') ?>
    </td>
    <td style="color:#64748b;">
      <?= htmlspecialchars($emp['agen_name'] ?? '') ?>
    </td>
    <td style="color:#64748b;">
      <?= htmlspecialchars($emp['disk_code'] ?? '') ?>
    </td>
    <td style="color:#64748b;">
      <?= htmlspecialchars($emp['disk_location'] ?? '') ?>
    </td>
    <td style="color:#64748b;">
      <?= htmlspecialchars($emp['disk_phone'] ?? '') ?>
    </td>

    <td class="text-end">
      <div style="display:flex; align-items:center; justify-content:flex-end; gap:6px;">
        <!-- ປຸ່ມແກ້ໄຂ -->
        <button class="btn-edit"
          data-emp="<?php echo htmlspecialchars(json_encode($emp), ENT_QUOTES, 'UTF-8'); ?>"
          onclick="handleEditButtonClick(this)">
          <i class="bi bi-pencil-fill"></i> ແກ້ໄຂ
        </button>
        <!-- ປຸ່ມລົບ -->
        <button class="btn-del del_disk" data-disk_id="<?= $emp['disk_id'] ?>">
          <i class="bi bi-trash3-fill"></i>
        </button>
      </div>
    </td>
  </tr>
<?php endforeach; ?>