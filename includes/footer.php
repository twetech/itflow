
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
    require_once "/var/www/nestogy.io/includes/inc_confirm_modal.php";
    require_once "/var/www/nestogy.io/includes/inc_dynamic_modal.php";
    function renderMenuItems($items, $level = 0, $isLast = true) {
        $itemCount = count($items);
        $firstItem = true; // Track the first item at this level
    
        foreach ($items as $index => $item) {
            if (!$firstItem) {
                echo '<span class="px-2">|</span>'; // Add separator before the item if it's not the first
            }
    
            echo '<div class="d-inline">'; // Inline display using Bootstrap
    
            // Check if a link is provided and display accordingly
            if (!empty($item['link'])) {
                echo '<a href="' . htmlspecialchars($item['link']) . '" class="footer-link">' . htmlspecialchars($item['title']) . '</a>';
            } else {
                echo '<span>' . htmlspecialchars($item['title']) . '</span>'; // Non-link text
            }
    
            // If there are children, show them with a hierarchy visual cue
            if (!empty($item['children'])) {
                echo ' <span class="text-muted">></span> '; // Visual cue for hierarchy
                renderMenuItems($item['children'], $level + 1, $index == $itemCount - 1);
            }
    
            echo '</div>';
    
            $firstItem = false; // After the first item has been rendered, set this to false
        }
    }

?>

<footer class="content-footer footer bg-footer-theme">
    <div class="container-fluid pt-5 pb-4">
        <div class="row">
            <div class="row">
                <div class="col-12 col-sm-3 col-md-2 mb-4 mb-sm-4">
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
            <?php // renderMenuItems($menuItems); ?>
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

<script src="/includes/assets/vendor/libs/popper/popper.js"></script>
<script src="/includes/assets/vendor/js/bootstrap.js"></script>
<script src="/includes/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
<script src="/includes/assets/vendor/libs/hammer/hammer.js"></script>
<script src="/includes/assets/vendor/libs/i18n/i18n.js"></script>
<script src="/includes/assets/vendor/libs/typeahead-js/typeahead.js"></script>
<script src="/includes/assets/vendor/js/menu.js"></script>

<script src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.3/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.1/js/dataTables.responsive.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.1/js/responsive.bootstrap5.js"></script>


<script src="/includes/assets/vendor/js/menu.js"></script>

<!-- endbuild -->

<!-- Vendors JS -->


<script src="/includes/assets/vendor/libs/block-ui/block-ui.js"></script>
<script src="/includes/assets/vendor/libs/sortablejs/sortable.js"></script>
<script src="/includes/assets/vendor/libs/toastr/toastr.js"></script>
<script src="/includes/plugins/moment/moment.min.js"></script>
<script src="/includes/assets/vendor/libs/apex-charts/apexcharts.js"></script>
<script src="/includes/assets/vendor/libs/flatpickr/flatpickr.js"></script>
<script src="/includes/assets/vendor/libs/cleavejs/cleave.js"></script>
<script src="/includes/assets/vendor/libs/cleavejs/cleave-phone.js"></script>
<script src="/includes/assets/vendor/libs/jquery-repeater/jquery-repeater.js"></script>
<script src="/includes/js/header_timers.js"></script>

<script src="/includes/js/reformat_datetime.js"></script>
<script src="/includes/plugins/select2/js/select2.min.js"></script>



<!-- Main JS -->
<script src="/includes/assets/js/main.js"></script>

<script src="/includes/js/dynamic_modal_loading.js"></script>

<!-- Page JS -->

<script>
document.querySelectorAll('textarea').forEach(function(textarea) {
    textarea.addEventListener('click', function initTinyMCE() {
        // This check ensures that TinyMCE is initialized only once for each textarea
        if (!tinymce.get(this.id)) {
            tinymce.init({
                selector: '#' + this.id,
                plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage advtemplate mentions tableofcontents footnotes mergetags autocorrect typography inlinecss markdown',
                toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
            });
        }
    }, { once: true });
});
</script>

<script>


$(function () {
    var datatable = $('.datatables-basic').DataTable({
        responsive: true,
        order: <?= $datatable_order ?>
        <?= $datatable_settings ?? '' ?>
    });

    datatable.on('init.dt', function () {
        $(document).trigger('updateDateTime');
        // start any select2
    });

    $(".select2").select2();
});

</script>

<script src="/includes/assets/js/cards-actions.js"></script>
</body>
</html>