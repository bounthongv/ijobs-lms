<div class="modal-overlay" id="modalOverlayEdit" onclick="closeOnOverlayEdit(event)">
  <div class="modal-box">
      
      <div class="modal-head">
        <span class="modal-head-title">
          <i class="bi bi-person-plus-fill text-primary"></i> ແກ້ໄຂ District 
        </span>
        <button type="button" class="modal-btn-close" onclick="closeEditModal()">×</button>
      </div>

      <div class="modal-body">
        <form action="" id="edit_disk"> 
          <div class="form-group">
          <label class="form-label">District ID</label>
          <input type="text" name="disk_id" id="edit_disk_id" class="form-control-custom" readonly>
        </div>
        
        <div class="form-group">
          <label class="form-label">Province</label>
          <select name="prok_id" id="edit_prok_id" class="form-select-custom">
            <?php foreach($s_prok as $prok): ?>
              <option value="<?= $prok['prok_id'] ?>"><?= $prok['prok_name'] ?></option>
            <?php endforeach ?>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Agency</label>
          <select name="agen_name" id="edit_agen_name" class="form-select-custom">
            <?php foreach($agency as $agen): ?>
              <option value="<?= $agen['agen_name'] ?>"><?= $agen['agen_name'] ?></option>
            <?php endforeach ?>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">District Name</label>
          <input type="text" name="disk_name" id="edit_disk_name" class="form-control-custom">
        </div>

        <div class="form-group">
          <label class="form-label">District Name (Lao)</label>
          <input type="text" name="disk_name_lao" id="edit_disk_name_lao" class="form-control-custom">
        </div>
        <div class="form-group">
          <label class="form-label">ຕົວອັກສອນຫຍໍ້</label>
          <input type="text" name="disk_code" id="edit_disk_code" class="form-control-custom">
        </div>
        <div class="form-group">
          <label class="form-label">ທີ່ຢູ່</label>
          <input type="text" name="disk_location" id="edit_disk_location" class="form-control-custom">
        </div>
        <div class="form-group">
          <label class="form-label">ເບີໂທ</label>
          <input type="text" name="disk_phone" id="edit_disk_phone" class="form-control-custom">
        </div>

        </form>
      </div>

      <div class="modal-foot">
        <button type="button" class="btn-cancel" onclick="closeEditModal()">
          <i class="bi bi-x-lg me-1"></i>ຍົກເລີກ
        </button>
        <button type="button" class="btn-save" id="e_btn_disk">
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
    document.getElementById('edit_disk_id').value = empData.disk_id || '';
    document.getElementById('edit_prok_id').value = empData.prok_id || '';
    document.getElementById('edit_agen_name').value = empData.agen_name || '';
    document.getElementById('edit_disk_name').value = empData.disk_name || '';
    document.getElementById('edit_disk_name_lao').value = empData.disk_name_lao || '';
    document.getElementById('edit_disk_code').value = empData.disk_code || '';
    document.getElementById('edit_disk_location').value = empData.disk_location || '';
    document.getElementById('edit_disk_phone').value = empData.disk_phone || '';
    

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