
            </div>
        </div>
    </div>
</div>

<!-- Overlay -->
<div class="layout-overlay layout-menu-toggle"></div>

<!-- Drag Target Area To SlideIn Menu On Small Screens -->
<div class="drag-target"></div>
</div>
<!-- / Layout wrapper -->


<!-- Core JS -->
<!-- build:js assets/vendor/js/core.js -->

<script src="/includes/assets/vendor/libs/jquery/jquery.js"></script>
<script src="/includes/assets/vendor/libs/popper/popper.js"></script>
<script src="/includes/assets/vendor/js/bootstrap.js"></script>
<script src="/includes/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
<script src="/includes/assets/vendor/libs/hammer/hammer.js"></script>

<script src="/includes/assets/vendor/js/menu.js"></script>

<!-- endbuild -->

<!-- Vendors JS -->

<!-- Main JS -->
<script src="/includes/assets/js/main.js"></script>

<script>
    $(function () {
        $('#responsive').DataTable({
            responsive: true,
            order: <?= $datatable_order ?>,
            buttons: ['copy', 'excel', 'pdf']
        });
    });
</script>

<!-- Page JS -->
</body>
</html>