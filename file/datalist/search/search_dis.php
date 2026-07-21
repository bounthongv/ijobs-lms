<?php 
    include_once __DIR__ . '/../../check.php';
    $all = $_POST['all'];
    $pro_id = $_POST['pro_id'];
    $p1 = $all == '' ? '' : "AND (dis_name LIKE '%$all%' OR dis_name_lao LIKE '%$all%' OR dis_id LIKE '%$all%' )";
    $p2 = $pro_id == '' ? '' : "AND dis.pro_id = '$pro_id' ";
    $sql = $conn->prepare("SELECT * FROM district as dis
    INNER JOIN province as pro ON dis.pro_id=pro.pro_id
    WHERE 1=1 $p1 $p2
    ORDER BY dis.dis_id ASC");
    $sql->execute();
    $num = 1;
    $users = $sql->fetchAll(PDO::FETCH_ASSOC);

?>
<?php if($sql->rowCount() == 0): ?>
            <!-- ຂໍ້ຄວາມ ຖ້າບໍ່ມີຂໍ້ມູນ -->
          <div id="emptyMsg" style="text-align:center; padding:28px; color:#94a3b8; font-size:13px;">
          <i class="bi bi-inbox" style="font-size:28px; display:block; margin-bottom:8px; opacity:.4;"></i>
          ບໍ່ພົບຂໍ້ມູນ
          </div>
        <?php else:; ?>
        <?php foreach ($users as $i => $u):?>
        <tr>
          <td style="color:#94a3b8; font-weight:600;"><?= $i + 1 ?></td>
          <td style="color:#64748b;"><?= htmlspecialchars($u['dis_id']) ?></td>
          <td style="color:#64748b;"><?= htmlspecialchars($u['pro_name_lao']) ?></td>
          <td style="color:#64748b;"><?= htmlspecialchars($u['dis_name_lao']) ?></td>
          <td style="color:#64748b;"><?= htmlspecialchars($u['dis_name']) ?></td>
          <td class="text-end">
            <div style="display:flex; align-items:center; justify-content:flex-end; gap:6px;">
              <!-- ປຸ່ມແກ້ໄຂ Role -->
              <button class="btn-edit"
                      data-user="<?php echo htmlspecialchars(json_encode($u), ENT_QUOTES, 'UTF-8'); ?>"
                      onclick="handleEditButtonClick(this)">
                <i class="bi bi-pencil-fill"></i> ແກ້ໄຂ
              </button>
              <!-- ປຸ່ມລົບ -->
              <button class="btn-del del_dis" data-dis_id="<?= $u['dis_id'] ?>">
                <i class="bi bi-trash3-fill"></i>
              </button>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>