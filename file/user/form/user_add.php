<div class="modal-overlay" id="modalOverlay" onclick="closeOnOverlay(event)">
  <div class="modal-box">
      
      <div class="modal-head">
        <span class="modal-head-title">
          <i class="bi bi-person-plus-fill text-primary"></i> ເພີ່ມ User ໃໝ່
        </span>
        <button type="button" class="modal-btn-close" onclick="closeModal()">×</button>
      </div>

      <div class="modal-body">
        <form action="" id="save_user">
        <div class="form-group">
          <label class="form-label"><i class="bi bi-person me-1"></i>FirstName</label>
          <input type="text" name="fname" class="form-control-custom"  placeholder="ປ້ອນຊື່ແທ້...">
        </div>
        
        <div class="form-group">
          <label class="form-label"><i class="bi bi-person me-1"></i>LastName</label>
          <input type="text" name="lname" class="form-control-custom"  placeholder="ປ້ອນນາມສະກຸນ...">
        </div>
        
        <div class="form-group">
          <label class="form-label"><i class="bi bi-person me-1"></i>Username</label>
          <input type="text" name="username" class="form-control-custom"  placeholder="ປ້ອນຊື່ຜູ້ໃຊ້ງານ...">
        </div>

        <div class="form-group">
          <label class="form-label"><i class="bi bi-envelope me-1"></i>Email</label>
          <input type="email" name="email" class="form-control-custom" placeholder="example@job.la">
        </div>

        <div class="form-group">
          <label class="form-label"><i class="bi bi-lock me-1"></i>Password</label>
          <input type="password" name="password" class="form-control-custom"  placeholder="ຢ່າງໜ້ອຍ 6 ຕົວອັກສອນ">
        </div>
        
        <div class="form-group">
          <label class="form-label"><i class="bi bi-lock me-1"></i>Confirm Password</label>
          <input type="password" name="conpass" class="form-control-custom" placeholder="ປ້ອນລະຫັດຜ່ານຄືນອີກຄັ້ງ">
        </div>

        <div class="form-group">
          <label class="form-label"><i class="bi bi-shield me-1"></i>Role</label>
          <select name="status" class="form-select-custom">
              <option value="">-- ເລືອກສິດການໃຊ້ງານ --</option>
              <option value="Admin">Admin</option>
              <option value="User">User</option>
          </select>
        </div>

        <!-- <div class="form-group">
          <label class="form-label"><i class="bi bi-toggles me-1"></i>Status</label>
          <div class="toggle-wrap">
            <div class="toggle-switch on" id="statusToggle" onclick="toggleStatus()"></div>
            <span class="toggle-label" id="statusLabel" style="color:#22c55e;">ໃຊ້ງານຢູ່</span>
          </div>
        </div> -->
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

// ===== Toggle Status =====
function toggleStatus() {
  setStatus(currentStatus === 'active' ? 'inactive' : 'active');
}

function setStatus(s) {
  currentStatus = s;
  const t = document.getElementById('statusToggle');
  const l = document.getElementById('statusLabel');
  if (s === 'active') {
    t.classList.add('on');
    l.textContent = 'ໃຊ້ງານຢູ່';
    l.style.color = '#22c55e';
  } else {
    t.classList.remove('on');
    l.textContent = 'ປິດການໃຊ້ງານ';
    l.style.color = '#94a3b8';
  }
}
</script>