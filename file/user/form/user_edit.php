<div class="modal-overlay" id="modalOverlayEdit" onclick="closeOnOverlayEdit(event)">
  <div class="modal-box">
      
      <div class="modal-head">
        <span class="modal-head-title">
          <i class="bi bi-person-plus-fill text-primary"></i> ແກ້ໄຂ User 
        </span>
        <button type="button" class="modal-btn-close" onclick="closeEditModal()">×</button>
      </div>

      <div class="modal-body">
        <form action="" id="edit_user"> 
          <input type="hidden" name="user_id" id="edit_user_id">

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
          <select name="status" id="edit_status" class="form-select-custom">
              <option value="">-- ເລືອກສິດການໃຊ້ງານ --</option>
              <option value="Admin">Admin</option>
              <option value="User">User</option>
          </select>
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
    document.getElementById('edit_user_id').value = userData.user_id || '';
    document.getElementById('edit_fname').value = userData.fname || '';
    document.getElementById('edit_lname').value = userData.lname || '';
    document.getElementById('edit_username').value = userData.username || '';
    document.getElementById('edit_email').value = userData.email || '';
    document.getElementById('edit_status').value = userData.status || '';
    

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