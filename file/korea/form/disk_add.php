<div class="modal-overlay" id="modalOverlay" onclick="closeOnOverlay(event)">
  <div class="modal-box">
      
      <div class="modal-head">
        <span class="modal-head-title">
          <i class="bi bi-person-plus-fill text-primary"></i> ເພີ່ມ Province ໃໝ່
        </span>
        <button type="button" class="modal-btn-close" onclick="closeModal()">×</button>
      </div>

      <div class="modal-body">
        <form action="" id="save_disk">
        <div class="form-group">
          <label class="form-label">District ID</label>
          <input type="text" name="disk_id" class="form-control-custom" value="<?= $disk_id ?>" readonly>
        </div>
        
        <div class="form-group">
          <label class="form-label">Province</label>
          <select name="prok_id" id="" class="form-select-custom">
            <?php foreach($s_prok as $prok): ?>
              <option value="<?= $prok['prok_id'] ?>"><?= $prok['prok_name'] ?></option>
            <?php endforeach ?>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Agency</label>
          <select name="agen_name" id="" class="form-select-custom">
            <?php foreach($agency as $agen): ?>
              <option value="<?= $agen['agen_name'] ?>"><?= $agen['agen_name'] ?></option>
            <?php endforeach ?>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">District Name</label>
          <input type="text" name="disk_name" class="form-control-custom">
        </div>

        <div class="form-group">
          <label class="form-label">District Name (Lao)</label>
          <input type="text" name="disk_name_lao" class="form-control-custom">
        </div>
        <div class="form-group">
          <label class="form-label">ຕົວອັກສອນຫຍໍ້</label>
          <input type="text" name="disk_code" class="form-control-custom">
        </div>
        <div class="form-group">
          <label class="form-label">ທີ່ຢູ່</label>
          <input type="text" name="disk_location" class="form-control-custom">
        </div>
        <div class="form-group">
          <label class="form-label">ເບີໂທ</label>
          <input type="text" name="disk_phone" class="form-control-custom">
        </div>

        </form>
      </div>

      <div class="modal-foot">
        <button type="button" class="btn-cancel" onclick="closeModal()">
          <i class="bi bi-x-lg me-1"></i>ຍົກເລີກ
        </button>
        <button type="button" class="btn-save" id="btn_disk">
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