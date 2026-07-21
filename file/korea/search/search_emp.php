<?php 
    require '../../../connect.php';
    $all = $_REQUEST['all'];
    $prok_id = $_REQUEST['prok_id'];
    $disk_id = $_REQUEST['disk_id'];

    $p1 = $all == '' ? '' : "AND emp_name = '$all' ";
    $p2 = $prok_id == '' ? '' : "AND prok_id = '$prok_id' ";
    $p3 = $disk_id == '' ? '' : "AND disk_id = '$disk_id' ";

    $sql = $conn->prepare("SELECT * FROM employer as emp
    LEFT JOIN province_korea as prok ON emp.prok_id=prok.prok_id
    LEFT JOIN district_korea as disk ON emp.disk_id=disk.disk_id 
    WHERE 1=1 $p1 $p2 $p3");
    $sql->execute();
    $num = 1;
?>
<?php foreach ($sql as $i => $emp):
        ?>
        <tr data-emp_id="<?= htmlspecialchars($emp['emp_id']) ?>"
            data-emp_name="<?= htmlspecialchars($emp['emp_name']) ?>"
            data-emp_email="<?= htmlspecialchars($emp['emp_email']) ?>"
            >

          <td style="color:#94a3b8; font-weight:600;"><?= $i + 1 ?></td>

          <td style="font-weight:600; color:#1e293b;">
            <?= htmlspecialchars($emp['emp_id'] ?? '') ?>
          </td>

          <td style="color:#64748b;">
            <span class="badge" style="background:#fef08a; color:#713f12;">
              <?= htmlspecialchars($emp['bus_type'] ?? '') ?>
            </span>
          </td>

          <td style="color:#64748b; font-weight:500;">
            <?= htmlspecialchars($emp['emp_name'] ?? '') ?>
          </td>
          <td style="color:#64748b; font-weight:500;">
            <?= htmlspecialchars($emp['emp_name_eng'] ?? '') ?>
          </td>

          <td style="color:#64748b;">
            <?= htmlspecialchars($emp['emp_com'] ?? '') ?>
          </td>

          <td style="color:#64748b;">
            <?= htmlspecialchars($emp['location'] ?? '') ?>
          </td>

          <td style="color:#64748b;">
            <?= htmlspecialchars($emp['emp_phone'] ?? '') ?>
          </td>

          <td style="color:#64748b;">
            <?= htmlspecialchars($emp['emp_email'] ?? '') ?>
          </td>

          <td style="color:#64748b;">
            <?= htmlspecialchars($emp['prok_name'] ?? '') ?>
          </td>

          <td style="color:#64748b;">
            <?= htmlspecialchars($emp['disk_name'] ?? '') ?>
          </td>

          <td style="color:#64748b;">
            <?= htmlspecialchars($emp['emp_code'] ?? '') ?>
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
              <button class="btn-del del_emp" data-emp_id="<?= $emp['emp_id'] ?>" >
                <i class="bi bi-trash3-fill"></i>
              </button>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>