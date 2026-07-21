<div class="modal-overlay" id="modalOverlayEdit" onclick="closeOnOverlayEdit(event)">
  <div class="modal-box">
      
      <div class="modal-head">
        <span class="modal-head-title">
          <i class="bi bi-person-plus-fill text-primary"></i> ແກ້ໄຂ Province 
        </span>
        <button type="button" class="modal-btn-close" onclick="closeEditModal()">×</button>
      </div>

      <div class="modal-body">
        <form action="" id="edit_pro"> 
        <div class="form-group">
          <label class="form-label">
             Province ID
          </label>
          <input type="text" name="pro_id" id="edit_pro_id" class="form-control-custom">
        </div>

        <div class="form-group">
          <label class="form-label">
            Province Name (Lao)
          </label>
          <input type="text" name="pro_name_lao" id="edit_pro_name_lao" class="form-control-custom">
        </div>

        <div class="form-group">
          <label class="form-label">
             Province Name (Eng)
          </label>
          <input type="text" name="pro_name" id="edit_pro_name" class="form-control-custom">
        </div>

        </form>
      </div>

      <div class="modal-foot">
        <button type="button" class="btn-cancel" onclick="closeEditModal()">
          <i class="bi bi-x-lg me-1"></i>ຍົກເລີກ
        </button>
        <button type="button" class="btn-save" id="e_btn_pro">
          <i class="bi bi-check-lg"></i> ແກ້ໄຂ
        </button>
      </div>
      
  </div>
</div>

<script>
// ===== ຕົວແປ Global =====
let currentEditStatus = 'active';

// ===== ຟັງຊັນ ເປີດ Modal Edit และโหลดข้อมูลเก่ามาใส่ในฟอร์ม =====
// คุณสามารถส่ง Object ข้อมูล user เข้ามาในฟังก์ชันนี้ได้เลย เช่น openModalEdit({id: 1, fname: 'John', ...})
function handleEditButtonClick(button) {
    // ดึงค่า JSON string ออกมาจาก data-user แล้วแปลงกลับเป็น Object
    const userData = JSON.parse(button.getAttribute('data-user'));
    
    // เรียกฟังก์ชันเปิด Modal ตัวเดิมที่คุณมีอยู่แล้ว
    openModalEdit(userData);
}
function openModalEdit(userData) {
    // 1. นำข้อมูลเก่ามาหยอดใส่ input แต่ละตัว
    document.getElementById('edit_pro_id').value = userData.pro_id || '';
    document.getElementById('edit_pro_name_lao').value = userData.pro_name_lao || '';
    document.getElementById('edit_pro_name').value = userData.pro_name || '';
    

    // 2. แสดง Modal
    document.getElementById('modalOverlayEdit').classList.add('show');
}

// ===== ປິດ Modal Edit =====
function closeEditModal() {
    document.getElementById('modalOverlayEdit').classList.remove('show');
}

// ===== ປິດ Modal ຖ້າຄລິກ Overlay =====
function closeOnOverlayEdit(e) {
    if (e.target === document.getElementById('modalOverlayEdit')) {
        closeEditModal();
    }
}
</script>