<?php
// ດຶງລາຍການ Menu ຫຼັກ (ໃຊ້ $conn ທີ່ໄດ້ຮັບການ include ມາຈາກ index.php)
$menuMainListEdit = $conn->query("SELECT menu_id, menu_name FROM menu ORDER BY menu_id ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="modal-overlay" id="modalOverlayEdit" onclick="closeOnOverlayEdit(event)">
  <div class="modal-box modal-box-lg">

      <div class="modal-head">
        <span class="modal-head-title">
          <i class="bi bi-person-gear text-primary"></i> ແກ້ໄຂ User
        </span>
        <button type="button" class="modal-btn-close" onclick="closeEditModal()">×</button>
      </div>

      <div class="modal-body">
        <form action="" id="edit_user">
          <input type="hidden" name="user_id" id="edit_user_id">

        <div class="user-form-grid">

          <!-- ===== ຊ້າຍ: ຂໍ້ມູນ User ===== -->
          <div>
            <div class="form-col-title"><i class="bi bi-person-badge"></i> ຂໍ້ມູນຜູ້ໃຊ້</div>

            <div class="form-group">
              <label class="form-label"><i class="bi bi-person me-1"></i>FirstName</label>
              <input type="text" name="fname" id="edit_fname" class="form-control-custom" placeholder="ປ້ອນຊື່ແທ້...">
            </div>

            <div class="form-group">
              <label class="form-label"><i class="bi bi-person me-1"></i>LastName</label>
              <input type="text" name="lname" id="edit_lname" class="form-control-custom" placeholder="ປ້ອນນາມສະກຸນ...">
            </div>

            <div class="form-group">
              <label class="form-label"><i class="bi bi-person me-1"></i>Username</label>
              <input type="text" name="username" id="edit_username" class="form-control-custom" placeholder="ປ້ອນຊື່ຜູ້ໃຊ້ງານ...">
            </div>

            <div class="form-group">
              <label class="form-label"><i class="bi bi-envelope me-1"></i>Email</label>
              <input type="email" name="email" id="edit_email" class="form-control-custom" placeholder="example@job.la">
            </div>

            <div class="form-group">
              <label class="form-label"><i class="bi bi-shield me-1"></i>Role</label>
              <!-- <select name="status" id="edit_status" class="form-select-custom"> -->
                <select name="status" id="edit_status" class="form-select-custom" onchange="handleStatusChangeEdit(this)">
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
              <span class="perm-count-badge ms-auto" id="editPermCountBadge">0 ລາຍການ</span>
            </div>

            <div class="menu-selector-wrap">
              <!-- ປຸ່ມ Menu ຫຼັກ -->
              <div class="menu-main-list" id="editMenuMainList">
                <?php foreach ($menuMainListEdit as $m): ?>
                <button type="button" class="menu-main-btn" data-menu-id="<?= htmlspecialchars($m['menu_id']) ?>" onclick="selectMainMenuEdit(this)">
                  <span><?= htmlspecialchars($m['menu_name']) ?></span>
                  <span class="menu-sel-badge" data-count="0"></span>
                </button>
                <?php endforeach; ?>
              </div>

              <!-- ລາຍການ Checkbox Menu ຍ່ອຍ -->
              <div class="menu-item-list" id="editMenuItemList">
                <div class="menu-item-placeholder">
                  <i class="bi bi-arrow-left-short"></i> ກະລຸນາເລືອກ Menu ຫຼັກກ່ອນ
                </div>
              </div>
            </div>

            <!-- ເກັບຄ່າ item_id ທັງໝົດທີ່ຖືກເລືອກ ໄວ້ສົ່ງໄປພ້ອມຟອມ (ຮູບແບບ JSON String) -->
            <input type="hidden" name="menu_permissions" id="editMenuPermissionsInput" value="">
          </div>

        </div>
        </form>
      </div>

      <div class="modal-foot">
        <button type="button" class="btn-cancel" onclick="closeEditModal()">
          <i class="bi bi-x-lg me-1"></i>ຍົກເລີກ
        </button>
        <button type="button" class="btn-save" id="e_btn_user">
          <i class="bi bi-check-lg"></i> ແກ້ໄຂ
        </button>
      </div>

  </div>
</div>
<script>
// ============================================================
// ສະຖານະການເລືອກສິດເມນູ (Edit User)
// ໂຄງສ້າງ: { "01": ["0101","0102"], "02": ["0201"] }
// ============================================================
let editPermState = {};
let editCurrentMenuId = null;
let editAllMenuIds = []; // เก็บ menu_id ทั้งหมดที่มีในระบบ (ดึงจากปุ่มที่ render ไว้)

// ===== ฟังก์ชัน เปิด Modal Edit และโหลดข้อมูลเก่ามาใส่ในฟอร์ม =====
function handleEditButtonClick(button) {
    const userData = JSON.parse(button.getAttribute('data-user'));
    openModalEdit(userData);
}

function openModalEdit(userData) {
    // 1. นำข้อมูลเก่ามาใส่ input แต่ละตัว
    document.getElementById('edit_user_id').value = userData.user_id || '';
    document.getElementById('edit_fname').value = userData.fname || '';
    document.getElementById('edit_lname').value = userData.lname || '';
    document.getElementById('edit_username').value = userData.username || '';
    document.getElementById('edit_email').value = userData.email || '';
    document.getElementById('edit_status').value = userData.status || '';

    // 2. รีเซ็ตสถานะสิทธิ์เมนู แล้วโหลดค่าเก่าจาก CSV string (menu_id, item_id)
    editPermState = {};

    // userData.item_id  เช่น "0101,0102,0201"
    // userData.menu_id  เช่น "01,02"  (ใช้เพื่อรู้ว่ามีเมนูหลักไหนถูกเลือกบ้าง แม้ item จะว่าง)
    const menuIdCsv = (userData.menu_id || '').toString().trim();
    const itemIdCsv = (userData.item_id || '').toString().trim();

    const menuIdList = menuIdCsv ? menuIdCsv.split(',').map(s => s.trim()).filter(Boolean) : [];
    const itemIdList = itemIdCsv ? itemIdCsv.split(',').map(s => s.trim()).filter(Boolean) : [];

    // เตรียม key ว่างไว้ก่อนสำหรับทุก menu_id ที่มีสิทธิ์ (เผื่อกรณี item ว่างแต่ menu ถูกติ๊กไว้)
    menuIdList.forEach(mid => {
        if (!editPermState[mid]) editPermState[mid] = [];
    });

    // จัดกลุ่ม item_id เข้ากับ menu_id ที่ตรงกัน โดยเทียบ 2 ตัวอักษรแรกของ item_id กับ menu_id
    // เช่น item_id "0201" -> menu_id "02"
    itemIdList.forEach(itemId => {
        const mid = itemId.substring(0, 2);
        if (!editPermState[mid]) editPermState[mid] = [];
        if (!editPermState[mid].includes(itemId)) editPermState[mid].push(itemId);
    });

    editCurrentMenuId = null;

    // เก็บ menu_id ทั้งหมดที่มีในระบบไว้ใช้ตอนกด Admin (select all)
    editAllMenuIds = Array.from(document.querySelectorAll('#editMenuMainList .menu-main-btn'))
        .map(b => b.getAttribute('data-menu-id'));

    // อัปเดต Badge ของ Menu หลักทุกตัวให้ตรงกับค่าเดิม
    document.querySelectorAll('#editMenuMainList .menu-main-btn').forEach(b => {
        b.classList.remove('active');
        updateMenuBadgeEdit(b.getAttribute('data-menu-id'));
    });

    document.getElementById('editMenuItemList').innerHTML =
        '<div class="menu-item-placeholder"><i class="bi bi-arrow-left-short"></i> ກະລຸນາເລືອກ Menu ຫຼັກກ່ອນ</div>';

    updateEditPermCount();
    syncEditHiddenInput();

    // 3. ถ้า Role ที่โหลดมาเป็น Admin ให้ auto-select ทั้งหมดทันที (เผื่อข้อมูลเดิมไม่ครบ/ไม่ตรง)
    if ((userData.status || '') === 'Admin') {
        selectAllMenusEdit();
    }

    // 4. แสดง Modal
    document.getElementById('modalOverlayEdit').classList.add('show');
}

// ===== ปิด Modal Edit =====
function closeEditModal() {
    document.getElementById('modalOverlayEdit').classList.remove('show');
}

// ===== ปิด Modal ถ้าคลิก Overlay =====
function closeOnOverlayEdit(e) {
    if (e.target === document.getElementById('modalOverlayEdit')) {
        closeEditModal();
    }
}

// ============================================================
// ===== เมื่อเปลี่ยน Role (Admin -> ติ๊กทั้งหมด / อื่นๆ -> ไม่ติ๊กเลย) =====
// ============================================================
function handleStatusChangeEdit(selectEl) {
    const value = selectEl.value;
    if (value === 'Admin') {
        selectAllMenusEdit();
    } else {
        clearAllMenusEdit();
    }
}

// ===== ติ๊กเลือกทุกเมนู/ทุกรายการย่อยทั้งหมดในระบบ (สำหรับ Admin) =====
function selectAllMenusEdit() {
    if (!editAllMenuIds.length) {
        editAllMenuIds = Array.from(document.querySelectorAll('#editMenuMainList .menu-main-btn'))
            .map(b => b.getAttribute('data-menu-id'));
    }
    if (!editAllMenuIds.length) return;

    let pending = editAllMenuIds.length;

    editAllMenuIds.forEach(menuId => {
        $.ajax({
            type: 'POST',
            url: 'get_menu_item.php',
            data: { menu_id: menuId },
            dataType: 'json',
            success: function (res) {
                if (res.sts === 'success' && res.data.length) {
                    editPermState[menuId] = res.data.map(item => String(item.item_id));
                } else {
                    editPermState[menuId] = [];
                }
            },
            error: function () {
                editPermState[menuId] = editPermState[menuId] || [];
            },
            complete: function () {
                updateMenuBadgeEdit(menuId);

                // ถ้าเมนูที่กำลังแสดงอยู่ตอนนี้ตรงกับ menuId ที่เพิ่งอัปเดต ให้ re-render checkbox ให้ติ๊กครบ
                if (editCurrentMenuId === menuId) {
                    loadMenuItemsEdit(menuId);
                }

                pending--;
                if (pending === 0) {
                    updateEditPermCount();
                    syncEditHiddenInput();
                }
            }
        });
    });
}

// ===== ล้างสิทธิ์ทั้งหมด (ไม่ติ๊กอะไรเลย) สำหรับ Role อื่นที่ไม่ใช่ Admin =====
function clearAllMenusEdit() {
    editPermState = {};

    document.querySelectorAll('#editMenuMainList .menu-main-btn').forEach(b => {
        updateMenuBadgeEdit(b.getAttribute('data-menu-id'));
    });

    // ถ้ากำลังแสดงรายการ checkbox ของเมนูใดอยู่ ให้ uncheck ทั้งหมดในหน้าจอด้วย
    if (editCurrentMenuId) {
        const rows = document.getElementById('editMenuItemRows');
        if (rows) {
            rows.querySelectorAll('input[type="checkbox"]').forEach(c => c.checked = false);
        }
        const selAll = document.querySelector('#editMenuItemList .edit-sel-all-checkbox');
        if (selAll) selAll.checked = false;
    }

    updateEditPermCount();
    syncEditHiddenInput();
}

// ===== คลิกเลือก Menu หลัก -> ไปดึง Menu ย่อย =====
function selectMainMenuEdit(btn) {
    document.querySelectorAll('#editMenuMainList .menu-main-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    editCurrentMenuId = btn.getAttribute('data-menu-id');
    loadMenuItemsEdit(editCurrentMenuId);
}

// ===== ดึง Menu ย่อยด้วย AJAX ตาม menu_id =====
function loadMenuItemsEdit(menuId) {
    const container = document.getElementById('editMenuItemList');
    container.innerHTML = '<div class="menu-item-loading"><i class="bi bi-hourglass-split"></i> ກຳລັງໂຫຼດ...</div>';

    $.ajax({
        type: 'POST',
        url: 'get_menu_item.php', // TODO: ปรับ path ให้ตรงกับตำแหน่งไฟล์จริง
        data: { menu_id: menuId },
        dataType: 'json',
        success: function (res) {
            if (res.sts !== 'success' || !res.data.length) {
                container.innerHTML = '<div class="menu-item-placeholder">ບໍ່ພົບ Menu ຍ່ອຍ</div>';
                return;
            }
            renderMenuItemsEdit(menuId, res.data);
        },
        error: function () {
            container.innerHTML = '<div class="menu-item-placeholder">ດຶງຂໍ້ມູນຜິດພາດ</div>';
        }
    });
}

// ===== สร้าง HTML รายการ Checkbox จาก Menu ย่อย (พร้อมติ๊กค่าเดิม) =====
function renderMenuItemsEdit(menuId, items) {
    const container = document.getElementById('editMenuItemList');
    const selected = (editPermState[menuId] || []).map(id => String(id));

    let html = `
        <div class="menu-item-select-all">
            <input type="checkbox" class="edit-sel-all-checkbox" id="editSelAll_${menuId}" onchange="toggleSelectAllEdit('${menuId}', this)">
            <label for="editSelAll_${menuId}">ເລືອກທັງໝົດ</label>
        </div>
        <div class="menu-item-rows" id="editMenuItemRows">`;

    items.forEach(item => {
        const checked = selected.includes(String(item.item_id)) ? 'checked' : '';
        html += `
            <div class="menu-item-row">
                <input type="checkbox" class="menu-item-checkbox" id="editItem_${item.item_id}" value="${item.item_id}"
                       data-menu-id="${menuId}" ${checked} onchange="onItemCheckEdit(this)">
                <label for="editItem_${item.item_id}">${item.item_name}</label>
            </div>`;
    });

    html += `</div>`;
    container.innerHTML = html;
    syncSelectAllStateEdit(menuId);
}

// ===== เมื่อติ๊ก/ถอดติ๊ก Checkbox รายการ =====
function onItemCheckEdit(checkbox) {
    const menuId = checkbox.getAttribute('data-menu-id');
    const itemId = String(checkbox.value);

    if (!editPermState[menuId]) editPermState[menuId] = [];

    if (checkbox.checked) {
        if (!editPermState[menuId].includes(itemId)) editPermState[menuId].push(itemId);
    } else {
        editPermState[menuId] = editPermState[menuId].filter(id => String(id) !== itemId);
    }

    syncSelectAllStateEdit(menuId);
    updateMenuBadgeEdit(menuId);
    updateEditPermCount();
    syncEditHiddenInput();
}

// ===== ติ๊ก "เลือกทั้งหมด" ในเมนูย่อยปัจจุบัน =====
function toggleSelectAllEdit(menuId, selAllCheckbox) {
    const rows = document.getElementById('editMenuItemRows');
    if (!rows) return;

    const checks = rows.querySelectorAll('input[type="checkbox"]');
    const isChecked = selAllCheckbox.checked;

    editPermState[menuId] = [];

    checks.forEach(c => {
        c.checked = isChecked;
        if (isChecked) {
            editPermState[menuId].push(String(c.value));
        }
    });

    updateMenuBadgeEdit(menuId);
    updateEditPermCount();
    syncEditHiddenInput();
}

// ===== เช็คว่า "เลือกทั้งหมด" ควรติ๊กหรือไม่ =====
function syncSelectAllStateEdit(menuId) {
    const rows = document.getElementById('editMenuItemRows');
    const container = document.getElementById('editMenuItemList');
    const selAll = container ? container.querySelector('.edit-sel-all-checkbox') : null;

    if (!selAll || !rows) return;

    const checks = rows.querySelectorAll('input[type="checkbox"]');
    if (!checks.length) return;

    selAll.checked = Array.from(checks).every(c => c.checked);
}

// ===== อัปเดต Badge จำนวนรายการที่ถูกเลือก ในปุ่ม Menu หลัก =====
function updateMenuBadgeEdit(menuId) {
    const btn = document.querySelector(`#editMenuMainList .menu-main-btn[data-menu-id="${menuId}"]`);
    if (!btn) return;

    const badge = btn.querySelector('.menu-sel-badge');
    const count = (editPermState[menuId] || []).length;

    badge.textContent = count > 0 ? count : '';
    badge.setAttribute('data-count', count);
}

// ===== อัปเดตจำนวนสิทธิ์ทั้งหมดที่ถูกเลือก =====
function updateEditPermCount() {
    const badge = document.getElementById('editPermCountBadge');
    if (!badge) return;

    let total = 0;
    Object.values(editPermState).forEach(arr => total += arr.length);
    badge.textContent = total + ' ລາຍການ';
}

// ===== นำ editPermState แปลงเป็น JSON ใส่ Hidden Input ก่อนส่งฟอร์ม =====
function syncEditHiddenInput() {
    document.getElementById('editMenuPermissionsInput').value = JSON.stringify(editPermState);
}
</script>