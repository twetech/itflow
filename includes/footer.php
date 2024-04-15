
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    require_once "/var/www/develop.twe.tech/includes/inc_confirm_modal.php";
    require_once "/var/www/develop.twe.tech/includes/inc_dynamic_modal.php";
?>

<footer class="content-footer footer bg-footer-theme">
    <div class="container-fluid pt-5 pb-4">
        <div class="row">
            <div class="row">
                <div class="col-12 col-sm-6 col-md-4 mb-4 mb-sm-4">
                    <h4 class="fw-bold mb-3"><a href="https://twe.tech" target="_blank" class="footer-text">ITFlow-NG </a></h4>        <span>Get ready for a better ERP.</span>
                    <div class="social-icon my-3">
                    <a href="javascript:void(0)" class="btn btn-icon btn-sm btn-facebook"><i class='bx bxl-facebook'></i></a>
                    <a href="javascript:void(0)" class="ms-2 btn btn-icon btn-sm btn-twitter"><i class='bx bxl-twitter'></i></a>
                    <a href="javascript:void(0)" class="ms-2 btn btn-icon btn-sm btn-linkedin"><i class='bx bxl-linkedin'></i></a>
                    </div>
                    <p class="pt-4">
                    <script>
                    document.write(new Date().getFullYear())
                    </script> © TWE Technologies
                    </p>
                </div>
            </div>
            <div class="row">
            <?php
                foreach ($menuItems as $item): ?>
                    <div class="col-12 col-sm-4 col-md-2 mb-4 mb-md-0">
                    <h5><?= htmlspecialchars($item['title']) ?></h5>
                    <ul class="list-unstyled">
                        <?php
                        if (isset($item['children'])):
                        foreach ($item['children'] as $child): ?>
                            <li>
                                <a href="<?= htmlspecialchars($child['link']) ?>" class="footer-link d-block pb-2">
                                    <?= htmlspecialchars($child['title']) ?>
                                </a>
                            </li>
                        <?php endforeach;
                        endif; ?>
                    </ul>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</footer>

<!-- Overlay -->
<div class="layout-overlay layout-menu-toggle"></div>

<!-- Drag Target Area To SlideIn Menu On Small Screens -->
<div class="drag-target"></div>
</div>
<!-- / Layout wrapper -->




<!-- Core JS -->
<!-- build:js assets/vendor/js/core.js -->

<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="/includes/assets/vendor/libs/popper/popper.js"></script>
<script src="/includes/assets/vendor/js/bootstrap.js"></script>
<script src="/includes/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
<script src="/includes/assets/vendor/libs/hammer/hammer.js"></script>

<script src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.3/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.1/js/dataTables.responsive.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.1/js/responsive.bootstrap5.js"></script>


<script src="/includes/assets/vendor/js/menu.js"></script>

<!-- endbuild -->

<!-- Vendors JS -->


<!-- Main JS -->
<script src="/includes/assets/js/main.js"></script>

<script src="/includes/js/dynamic_modal_loading.js"></script>



<script>
			$(function () {
				$('.datatables-basic').DataTable({
					responsive: true,
					order: <?= $datatable_order ?>,
				});
			});

			$('.trumbowyg').trumbowyg();
</script>

<!-- Page JS -->
</body>
</html>