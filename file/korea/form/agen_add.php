<div class="modal-overlay" id="modalOverlay" onclick="closeOnOverlay(event)">
  <div class="modal-box">
      
      <div class="modal-head">
        <span class="modal-head-title">
          <i class="bi bi-person-plus-fill text-primary"></i> ເພີ່ມ Agency ໃໝ່
        </span>
        <button type="button" class="modal-btn-close" onclick="closeModal()">×</button>
      </div>

      <div class="modal-body">
        <form action="" id="save_agen">
        <div class="form-group">
          <label class="form-label">Agency ID</label>
          <input type="text" name="agen_id" class="form-control-custom" value="<?= $agen_id ?>" readonly>
        </div>
        
        <div class="form-group">
          <label class="form-label">Agency Name</label>
          <input type="text" name="agen_name" class="form-control-custom">
        </div>
        <div class="form-group">
          <label class="form-label">Agency Name (Eng)</label>
          <input type="text" name="agen_name_eng" class="form-control-custom">
        </div>

        <div class="form-group">
          <label class="form-label">Address</label>
          <input type="text" name="agen_address" class="form-control-custom">
        </div>

        <div class="form-group">
          <label class="form-label">Phone</label>
          <input type="text" name="agen_tel" class="form-control-custom" >
        </div>
        
        <div class="form-group">
          <label class="form-label">Email</label>
          <input type="email" name="agen_email" class="form-control-custom">
        </div>
        </form>
      </div>

      <div class="modal-foot">
        <button type="button" class="btn-cancel" onclick="closeModal()">
          <i class="bi bi-x-lg me-1"></i>ຍົກເລີກ
        </button>
        <button type="button" class="btn-save" id="btn_agen">
          <i class="bi bi-check-lg"></i> ບັນທຶກ
        </button>
      </div>
      
  </div>
</div>
<script>
// ===== ຟັງຊັນ ເປີດ Modal (Clear ຄ່າເກົ່າອອກໃຫ້ເປັນຫວ່າງປ່າວສະເໝີ) =====
function openModal() {

  document.getElementById('modalOverlay').classList.add('show');
}

// ===== ປິດ Modal =====
function closeModal() {
  document.getElementById('modalOverlay').classList.remove('show');
}

// ===== ປິດ Modal ຖ້າຄລິກ Overlay =====
function closeOnOverlay(e) {
  if (e.target === document.getElementById('modalOverlay')) closeModal();
}


</script>