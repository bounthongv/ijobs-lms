<div class="modal-overlay" id="modalOverlayEdit" onclick="closeOnOverlayEdit(event)">
  <div class="modal-box">
      
      <div class="modal-head">
        <span class="modal-head-title">
          <i class="bi bi-person-plus-fill text-primary"></i> ແກ້ໄຂ Province 
        </span>
        <button type="button" class="modal-btn-close" onclick="closeEditModal()">×</button>
      </div>

      <div class="modal-body">
        <form action="" id="edit_prok"> 
          <div class="form-group">
          <label class="form-label">Province ID</label>
          <input type="text" name="prok_id" id="edit_prok_id" class="form-control-custom" readonly>
        </div>
        
        <div class="form-group">
          <label class="form-label">Province Name</label>
          <input type="text" name="prok_name" id="edit_prok_name" class="form-control-custom">
        </div>

        <div class="form-group">
          <label class="form-label">Province Name (Lao)</label>
          <input type="text" name="prok_name_lao" id="edit_prok_name_lao" class="form-control-custom">
        </div>

        </form>
      </div>

      <div class="modal-foot">
        <button type="button" class="btn-cancel" onclick="closeEditModal()">
          <i class="bi bi-x-lg me-1"></i>ຍົກເລີກ
        </button>
        <button type="button" class="btn-save" id="e_btn_prok">
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
    document.getElementById('edit_prok_id').value = empData.prok_id || '';
    document.getElementById('edit_prok_name').value = empData.prok_name || '';
    document.getElementById('edit_prok_name_lao').value = empData.prok_name_lao || '';
    

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