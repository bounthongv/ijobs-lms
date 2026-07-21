<?php
require '../../../connect.php';
$all = $_REQUEST['all'];

$p1 = $all == '' ? '' : "AND (agen_name LIKE '%$all%'OR agen_name_eng LIKE '%$all%' OR agen_id LIKE '%$all%') ";

$sql = $conn->prepare("SELECT * FROM agency_korea
    WHERE 1=1 $p1
    ORDER BY agen_id ASC
    ");
$sql->execute();
$num = 1;
?>
<?php foreach ($sql as $i => $emp): ?>
  <tr>

    <td style="color:#94a3b8; font-weight:600;"><?= $i + 1 ?></td>

    <td style="font-weight:600; color:#1e293b;">
      <?= htmlspecialchars($emp['agen_id'] ?? '') ?>
    </td>

    <td style="color:#64748b; font-weight:500;">
      <?= htmlspecialchars($emp['agen_name'] ?? '') ?>
    </td>

    <td style="color:#64748b;">
      <?= htmlspecialchars($emp['agen_name_eng'] ?? '') ?>
    </td>
    <td style="color:#64748b;">
      <?= htmlspecialchars($emp['agen_address'] ?? '') ?>
    </td>
    <td style="color:#64748b;">
      <?= htmlspecialchars($emp['agen_tel'] ?? '') ?>
    </td>
    <td style="color:#64748b;">
      <?= htmlspecialchars($emp['agen_email'] ?? '') ?>
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
        <button class="btn-del del_agen" data-agen_id="<?= $emp['agen_id'] ?>">
          <i class="bi bi-trash3-fill"></i>
        </button>
      </div>
    </td>
  </tr>
<?php endforeach; ?>