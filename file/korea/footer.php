</div> </div> 
<div class="apims-toasts" id="toastContainer"></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  $(document).ready(function () {
    let link = "<?= BASE_URLS ?>logout.php"
    $("#logout").click(function (e) { 
        Swal.fire({
          title: "ທ່ານຕ້ອງການອອກຈາກລະບົບແທ້ ຫຼື ບໍ່?",
          // text: "You won't be able to revert this!",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "ອອກຈາກລະບົບ",
          cancelButtonText: "ຍົກເລີກ"
        }).then((result) => {
          if (result.isConfirmed) {
            // Redirect to logout script
            window.location.href = link;
          }
        });
      });
  });
  function toggleSidebar() {
    const width = window.innerWidth;
    const body = document.body;
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');

    if (width > 768) {
      body.classList.toggle('sidebar-collapsed');
    } else {
      sidebar.classList.toggle('open');
      overlay.classList.toggle('show');
    }
  }
  function showToast(message, type = 'success') {
      const container = document.getElementById('toastContainer');
      const icons = { success: 'fa-check-circle', error: 'fa-times-circle' };
      const toast = document.createElement('div');
      toast.className = `apims-toast ${type}`;
      toast.innerHTML = `
        <i class="fas ${icons[type] || 'fa-info-circle'} toast-icon"></i>
        <span class="toast-msg">${message}</span>
        <button class="toast-close" onclick="this.parentElement.remove()">
          <i class="fas fa-times"></i>
        </button>`;
      container.appendChild(toast);
      setTimeout(() => toast.remove(), 2000);
    }
</script>

</body>
</html>