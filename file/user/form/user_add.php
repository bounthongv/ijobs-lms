<?php
// ດຶງລາຍການ Menu ຫຼັກ (ໃຊ້ $conn ທີ່ໄດ້ຮັບການ include ມາຈາກ index.php)
$menuMainList = $conn->query("SELECT menu_id, menu_name FROM menu ORDER BY menu_id ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="modal-overlay" id="modalOverlay" onclick="closeOnOverlay(event)">
  <div class="modal-box modal-box-lg">

      <div class="modal-head">
        <span class="modal-head-title">
          <i class="bi bi-person-plus-fill text-primary"></i> ເພີ່ມ User ໃໝ່
        </span>
        <button type="button" class="modal-btn-close" onclick="closeModal()">×</button>
      </div>

      <div class="modal-body">
        <form action="" id="save_user">
        <div class="user-form-grid">

          <!-- ===== ຊ້າຍ: ຂໍ້ມູນ User ===== -->
          <div>
            <div class="form-col-title"><i class="bi bi-person-badge"></i> ຂໍ້ມູນຜູ້ໃຊ້</div>

            <div class="form-group">
              <label class="form-label"><i class="bi bi-person me-1"></i>FirstName</label>
              <input type="text" name="fname" class="form-control-custom" placeholder="ປ້ອນຊື່ແທ້...">
            </div>

            <div class="form-group">
              <label class="form-label"><i class="bi bi-person me-1"></i>LastName</label>
              <input type="text" name="lname" class="form-control-custom" placeholder="ປ້ອນນາມສະກຸນ...">
            </div>

            <div class="form-group">
              <label class="form-label"><i class="bi bi-person me-1"></i>Username</label>
              <input type="text" name="username" class="form-control-custom" placeholder="ປ້ອນຊື່ຜູ້ໃຊ້ງານ...">
            </div>

            <div class="form-group">
              <label class="form-label"><i class="bi bi-envelope me-1"></i>Email</label>
              <input type="email" name="email" class="form-control-custom" placeholder="example@job.la">
            </div>

            <div class="form-group">
              <label class="form-label"><i class="bi bi-lock me-1"></i>Password</label>
              <input type="password" name="password" class="form-control-custom" placeholder="ຢ່າງໜ້ອຍ 6 ຕົວອັກສອນ">
            </div>

            <div class="form-group">
              <label class="form-label"><i class="bi bi-lock me-1"></i>Confirm Password</label>
              <input type="password" name="conpass" class="form-control-custom" placeholder="ປ້ອນລະຫັດຜ່ານຄືນອີກຄັ້ງ">
            </div>

            <div class="form-group">
              <label class="form-label"><i class="bi bi-shield me-1"></i>Role</label>
              <!-- ===== แก้ dropdown Role เพิ่ม onchange ===== -->
              <select name="status" class="form-select-custom" onchange="handleStatusChangeAdd(this)">
                  <option value="">-- ເລືອກສິດການໃຊ້ງານ --</option>
                  <option value="Admin">Admin</option>
                  <option value="User">User</option>
              </select>
            </div>
          </div>

          <!-- ===== ຂວາ: ສິດການໃຊ້ງານເມນູ ===== -->
          <div>
            <div class="form-col-title">
              <i class="bi bi-diagram-3"></i> ສິດການເຂົ້າໃຊ້ເມນູ
              <span class="perm-count-badge ms-auto" id="permCountBadge">0 ລາຍການ</span>
            </div>

            <div class="menu-selector-wrap">
              <!-- ປຸ່ມ Menu ຫຼັກ -->
              <div class="menu-main-list" id="menuMainList">
                <?php foreach ($menuMainList as $m): ?>
                <button type="button" class="menu-main-btn" data-menu-id="<?= htmlspecialchars($m['menu_id']) ?>" onclick="selectMainMenu(this)">
                  <span><?= htmlspecialchars($m['menu_name']) ?></span>
                  <span class="menu-sel-badge" data-count="0"></span>
                </button>
                <?php endforeach; ?>
              </div>

              <!-- ລາຍການ Checkbox Menu ຍ່ອຍ -->
              <div class="menu-item-list" id="menuItemList">
                <div class="menu-item-placeholder">
                  <i class="bi bi-arrow-left-short"></i> ກະລຸນາເລືອກ Menu ຫຼັກກ່ອນ
                </div>
              </div>
            </div>

            <!-- ເກັບຄ່າ item_id ທັງໝົດທີ່ຖືກເລືອກ ໄວ້ສົ່ງໄປພ້ອມຟອມ (ຮູບແບບ JSON String) -->
            <input type="hidden" name="menu_permissions" id="menuPermissionsInput" value="">
          </div>

        </div>
        </form>
      </div>

      <div class="modal-foot">
        <button type="button" class="btn-cancel" onclick="closeModal()">
          <i class="bi bi-x-lg me-1"></i>ຍົກເລີກ
        </button>
        <button type="button" class="btn-save" id="btn_user">
          <i class="bi bi-check-lg"></i> ບັນທຶກ
        </button>
      </div>

  </div>
</div>
<script>
// ============================================================
// สถานะการเลือกสิทธิ์เมนู (Add User)
// Structure: { "01": ["0101","0102"], "02": ["0201"] }
// ============================================================
let addPermState = {};
let addCurrentMenuId = null;
let addAllMenuIds = []; // เก็บ menu_id ทั้งหมดที่มีในระบบ (ดึงจากปุ่มที่ render ไว้)

// ===== เปิด Modal =====
function openModal() {
  // รีเซ็ตทุกครั้งที่เปิด modal ใหม่ เพื่อไม่ให้ค้างข้อมูลจาก user ก่อนหน้า
  addPermState = {};
  addCurrentMenuId = null;

  document.querySelectorAll('#menuMainList .menu-main-btn').forEach(b => {
      b.classList.remove('active');
      updateMenuBadge(b.getAttribute('data-menu-id'));
  });

  document.getElementById('menuItemList').innerHTML =
      '<div class="menu-item-placeholder"><i class="bi bi-arrow-left-short"></i> ກະລຸນາເລືອກ Menu ຫຼັກກ່ອນ</div>';

  updatePermCount();
  syncHiddenInput();

  // เตรียม menu_id ทั้งหมดไว้ล่วงหน้า (เผื่อกด Admin ทันที)
  addAllMenuIds = Array.from(document.querySelectorAll('#menuMainList .menu-main-btn'))
      .map(b => b.getAttribute('data-menu-id'));

  document.getElementById('modalOverlay').classList.add('show');
}

// ===== ปิด Modal =====
function closeModal() {
  document.getElementById('modalOverlay').classList.remove('show');
}

// ===== ปิด Modal เมื่อคลิก Overlay =====
function closeOnOverlay(e) {
  if (e.target === document.getElementById('modalOverlay')) closeModal();
}

// ============================================================
// ===== เมื่อเปลี่ยน Role (Admin -> ติ๊กทั้งหมด / อื่นๆ -> ไม่ติ๊กเลย) =====
// ============================================================
function handleStatusChangeAdd(selectEl) {
    const value = selectEl.value;
    if (value === 'Admin') {
        selectAllMenusAdd();
    } else {
        clearAllMenusAdd();
    }
}

// ===== ติ๊กเลือกทุกเมนู/ทุกรายการย่อยทั้งหมดในระบบ (สำหรับ Admin) =====
function selectAllMenusAdd() {
    if (!addAllMenuIds.length) {
        addAllMenuIds = Array.from(document.querySelectorAll('#menuMainList .menu-main-btn'))
            .map(b => b.getAttribute('data-menu-id'));
    }
    if (!addAllMenuIds.length) return;

    let pending = addAllMenuIds.length;

    addAllMenuIds.forEach(menuId => {
        $.ajax({
            type: 'POST',
            url: 'get_menu_item.php',
            data: { menu_id: menuId },
            dataType: 'json',
            success: function (res) {
                if (res.sts === 'success' && res.data.length) {
                    addPermState[menuId] = res.data.map(item => String(item.item_id));
                } else {
                    addPermState[menuId] = [];
                }
            },
            error: function () {
                addPermState[menuId] = addPermState[menuId] || [];
            },
            complete: function () {
                updateMenuBadge(menuId);

                // ถ้าเมนูที่กำลังแสดงอยู่ตอนนี้ตรงกับ menuId ที่เพิ่งอัปเดต ให้ re-render checkbox ให้ติ๊กครบ
                if (addCurrentMenuId === menuId) {
                    loadMenuItems(menuId);
                }

                pending--;
                if (pending === 0) {
                    updatePermCount();
                    syncHiddenInput();
                }
            }
        });
    });
}

// ===== ล้างสิทธิ์ทั้งหมด (ไม่ติ๊กอะไรเลย) สำหรับ Role อื่นที่ไม่ใช่ Admin =====
function clearAllMenusAdd() {
    addPermState = {};

    document.querySelectorAll('#menuMainList .menu-main-btn').forEach(b => {
        updateMenuBadge(b.getAttribute('data-menu-id'));
    });

    // ถ้ากำลังแสดงรายการ checkbox ของเมนูใดอยู่ ให้ uncheck ทั้งหมดในหน้าจอด้วย
    if (addCurrentMenuId) {
        const container = document.getElementById('menuItemList');
        container.querySelectorAll('.menu-item-checkbox').forEach(c => c.checked = false);
        const selAll = container.querySelector('.sel-all-checkbox');
        if (selAll) selAll.checked = false;
    }

    updatePermCount();
    syncHiddenInput();
}

// ===== คลิกเลือก Menu หลัก =====
function selectMainMenu(btn) {
  document.querySelectorAll('#menuMainList .menu-main-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');

  addCurrentMenuId = btn.getAttribute('data-menu-id');
  loadMenuItems(addCurrentMenuId);
}

// ===== ดึง Menu ย่อยด้วย AJAX =====
function loadMenuItems(menuId) {
  const container = document.getElementById('menuItemList');
  container.innerHTML = '<div class="menu-item-loading"><i class="bi bi-hourglass-split"></i> กำลังโหลด...</div>';

  $.ajax({
    type: 'POST',
    url: 'get_menu_item.php',
    data: { menu_id: menuId },
    dataType: 'json',
    success: function (res) {
      if (res.sts !== 'success' || !res.data.length) {
        container.innerHTML = '<div class="menu-item-placeholder">ไม่พบ Menu ย่อย</div>';
        return;
      }
      renderMenuItems(menuId, res.data);
    },
    error: function () {
      container.innerHTML = '<div class="menu-item-placeholder">ดึงข้อมูลผิดพลาด</div>';
    }
  });
}

// ===== สร้าง HTML รายการ Checkbox =====
function renderMenuItems(menuId, items) {
  const container = document.getElementById('menuItemList');
  const selected = addPermState[menuId] || [];

  let html = `
    <div class="menu-item-select-all">
      <input type="checkbox" class="sel-all-checkbox" id="selAll_${menuId}" onchange="toggleSelectAll('${menuId}', this)">
      <label for="selAll_${menuId}">ເລືອກທັງໝົດ</label>
    </div>`;

  items.forEach(item => {
    const checked = selected.includes(String(item.item_id)) ? 'checked' : '';
    html += `
      <div class="menu-item-row">
        <input type="checkbox" class="menu-item-checkbox" id="item_${item.item_id}" value="${item.item_id}"
               data-menu-id="${menuId}" ${checked} onchange="onItemCheck(this)">
        <label for="item_${item.item_id}">${item.item_name}</label>
      </div>`;
  });

  container.innerHTML = html;
  syncSelectAllState(menuId);
}

// ===== ติ๊กเลือกรายการเดี่ยว =====
function onItemCheck(checkbox) {
  const menuId = checkbox.getAttribute('data-menu-id');
  const itemId = checkbox.value;

  if (!addPermState[menuId]) addPermState[menuId] = [];

  if (checkbox.checked) {
    if (!addPermState[menuId].includes(itemId)) addPermState[menuId].push(itemId);
  } else {
    addPermState[menuId] = addPermState[menuId].filter(id => id !== itemId);
  }

  syncSelectAllState(menuId);
  updateMenuBadge(menuId);
  updatePermCount();
  syncHiddenInput();
}

// ===== เลือกทั้งหมด / ยกเลิกทั้งหมด (ในเมนูย่อยที่กำลังแสดงอยู่) =====
function toggleSelectAll(menuId, selAllCheckbox) {
  const container = document.getElementById('menuItemList');
  const checks = container.querySelectorAll('.menu-item-checkbox');
  const isChecked = selAllCheckbox.checked;

  addPermState[menuId] = [];

  checks.forEach(c => {
    c.checked = isChecked;
    if (isChecked) {
      addPermState[menuId].push(c.value);
    }
  });

  updateMenuBadge(menuId);
  updatePermCount();
  syncHiddenInput();
}

// ===== ซิงค์สถานะของปุ่ม "เลือกทั้งหมด" =====
function syncSelectAllState(menuId) {
  const container = document.getElementById('menuItemList');
  const checks = container.querySelectorAll('.menu-item-checkbox');
  const selAll = container.querySelector('.sel-all-checkbox');

  if (!selAll || !checks.length) return;

  selAll.checked = Array.from(checks).every(c => c.checked);
}

// ===== อัปเดต Badge จำนวนรายการที่เลือกใน Menu หลัก =====
function updateMenuBadge(menuId) {
  const btn = document.querySelector(`#menuMainList .menu-main-btn[data-menu-id="${menuId}"]`);
  if (!btn) return;
  const badge = btn.querySelector('.menu-sel-badge');
  const count = (addPermState[menuId] || []).length;
  badge.textContent = count > 0 ? count : '';
  badge.setAttribute('data-count', count);
}

// ===== อัปเดตจำนวนสิทธิ์ทั้งหมดที่ถูกเลือก =====
function updatePermCount() {
  const badge = document.getElementById('permCountBadge');
  if (!badge) return;
  let total = 0;
  Object.values(addPermState).forEach(arr => total += arr.length);
  badge.textContent = total + ' ລາຍການ';
}

// ===== แปลง Array เป็น JSON สรุปใส่ Hidden Input =====
function syncHiddenInput() {
  document.getElementById('menuPermissionsInput').value = JSON.stringify(addPermState);
}
</script>