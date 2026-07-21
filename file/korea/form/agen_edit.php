<div class="modal-overlay" id="modalOverlayEdit" onclick="closeOnOverlayEdit(event)">
  <div class="modal-box">
      
      <div class="modal-head">
        <span class="modal-head-title">
          <i class="bi bi-person-plus-fill text-primary"></i> ແກ້ໄຂ Agency 
        </span>
        <button type="button" class="modal-btn-close" onclick="closeEditModal()">×</button>
      </div>

      <div class="modal-body">
        <form action="" id="edit_agen"> 
          <div class="form-group">
          <label class="form-label">Agency ID</label>
          <input type="text" name="agen_id" id="edit_agen_id" class="form-control-custom"  readonly>
        </div>
        
        <div class="form-group">
          <label class="form-label">Agency Name</label>
          <input type="text" name="agen_name" id="edit_agen_name" class="form-control-custom">
        </div>
        <div class="form-group">
          <label class="form-label">Agency Name (Eng)</label>
          <input type="text" name="agen_name_eng" id="edit_agen_name_eng" class="form-control-custom">
        </div>

        <div class="form-group">
          <label class="form-label">Address</label>
          <input type="text" name="agen_address" id="edit_agen_address" class="form-control-custom">
        </div>

        <div class="form-group">
          <label class="form-label">Phone</label>
          <input type="text" name="agen_tel" id="edit_agen_tel" class="form-control-custom" >
        </div>
        
        <div class="form-group">
          <label class="form-label">Email</label>
          <input type="email" name="agen_email" id="edit_agen_email" class="form-control-custom">
        </div>

        </form>
      </div>

      <div class="modal-foot">
        <button type="button" class="btn-cancel" onclick="closeEditModal()">
          <i class="bi bi-x-lg me-1"></i>ຍົກເລີກ
        </button>
        <button type="button" class="btn-save" id="e_btn_agen">
          <i class="bi bi-check-lg"></i> ແກ້ໄຂ
        </button>
      </div>
      
  </div>
</div>

<script>

// ===== ຕົວແປ Global =====
let currentEditStatus = 'active';

// ===== ຟັງຊັນ ເປີດ Modal Edit ແລະ Load ຂໍ້ມູນເກົ່າມາໃສ່ຟອມ =====
function handleEditButtonClick(button) {
    // ດຶງຄ່າ JSON string ອອກມາຈາກ data-emp ແລ້ວແປງກັບເປັນ Object
    const empData = JSON.parse(button.getAttribute('data-emp'));
    
    // ເຣຍກຟັງຊັນເປີດ Modal
    openModalEdit(empData);
}
function openModalEdit(empData) {
    // 1. ນຳຂໍ້ມູນເກົ່າມາຫຍອດໃສ່ input ແຕ່ລະຕົວ
    document.getElementById('edit_agen_id').value = empData.agen_id || '';
    document.getElementById('edit_agen_name').value = empData.agen_name || '';
    document.getElementById('edit_agen_name_eng').value = empData.agen_name_eng || '';
    document.getElementById('edit_agen_address').value = empData.agen_address || '';
    document.getElementById('edit_agen_tel').value = empData.agen_tel || '';
    document.getElementById('edit_agen_email').value = empData.agen_email || '';
    

    // 2. ສະແດງ Modal
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