<div class="modal-overlay" id="modalOverlay" onclick="closeOnOverlay(event)">
  <div class="modal-box">

    <div class="modal-head">
      <span class="modal-head-title">
        <i class="bi bi-hash text-primary"></i> ເພີ່ມ Province ໃໝ່
      </span>
      <button type="button" class="modal-btn-close" onclick="closeModal()">×</button>
    </div>

    <div class="modal-body">
      <form action="" id="save_pro">

        <div class="form-group">
          <label class="form-label">
             Province ID
          </label>
          <input type="text" name="pro_id" class="form-control-custom">
        </div>

        <div class="form-group">
          <label class="form-label">
            Province Name (Lao)
          </label>
          <input type="text" name="pro_name_lao" class="form-control-custom">
        </div>

        <div class="form-group">
          <label class="form-label">
             Province Name (Eng)
          </label>
          <input type="text" name="pro_name" class="form-control-custom">
        </div>

      </form>
    </div>

    <div class="modal-foot">
      <button type="button" class="btn-cancel" onclick="closeModal()">
        <i class="bi bi-x-lg me-1"></i>ຍົກເລີກ
      </button>
      <button type="button" class="btn-save" id="btn_pro">
        <i class="bi bi-check-lg"></i> ບັນທຶກ
      </button>
    </div>

  </div>
</div>
<script>
  // ===== ຕົວແປ Global =====
  let currentStatus = 'active';

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