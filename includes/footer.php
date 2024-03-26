
				</div><!-- /.container-fluid -->
				<footer class="u-footer d-md-flex align-items-md-center text-center text-md-left text-muted text-muted">
					<p class="h5 mb-0 ml-auto">
						© <?php echo date("Y"); ?> <a class="link-muted" href="https://htmlstream.com/" target="_blank">TWE Technologies</a>. All Rights Reserved.
					</p>
				</footer>
			<!-- /.content-wrapper -->
			</div>
			<!-- ./wrapper -->

			<?php require_once "/var/www/develop.twe.tech/includes/inc_confirm_modal.php"; ?>

			<div class="modal fade" id="dynamicModal" tabindex="-1" aria-labelledby="dynamicModalLabel" aria-hidden="true" role="dialog">
				<div class="modal-dialog" role="document">
					<form action="/post.php" method="post" autocomplete="off">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="dynamicModalLabel">Error Loading Modal</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">

							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</main>
		<!-- REQUIRED SCRIPTS -->
		<script>
			$(function () {
				$('#responsive').DataTable({
					responsive: true
				});
			});
		</script>
		<!-- Custom js-->
		<script src="/includes/plugins/moment/moment.min.js"></script>
		<script src="/includes/plugins/chart.js/Chart.min.js"></script>
		<script src="/includes/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
		<script src='/includes/plugins/daterangepicker/daterangepicker.js'></script>
		<script src='/includes/plugins/select2/js/select2.min.js'></script>
		<script src='/includes/plugins/inputmask/jquery.inputmask.min.js'></script>
		<script src="/includes/plugins/tinymce/tinymce.min.js" referrerpolicy="origin"></script>
		<script src="/includes/plugins/Show-Hide-Passwords-Bootstrap-4/bootstrap-show-password.min.js"></script>
		<script src="/includes/plugins/clipboardjs/clipboard.min.js"></script>
		<script src="/includes/js/keepalive.js"></script>
		<script src="/includes/js/dynamic_modal_loading.js"></script>

		<!-- Global Vendor -->
		<script src="/includes/dist/vendor/jquery/dist/jquery.min.js"></script>
		<script src="/includes/dist/vendor/jquery-migrate/jquery-migrate.min.js"></script>
		<script src="/includes/dist/vendor/popper.js/dist/umd/popper.min.js"></script>
		<script src="/includes/dist/vendor/bootstrap/bootstrap.min.js"></script>

		<link href="//cdn.datatables.net/responsive/2.1.1/css/dataTables.responsive.css"/>
		<link data-require="datatables@*" data-semver="1.10.12" rel="stylesheet" href="//cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css" />
		<link data-require="bootstrap@*" data-semver="4.0.5" rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" />

		<!-- Plugins -->
		<script src="/includes/dist/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
		<script src="/includes/dist/vendor/chart.js/dist/Chart.min.js"></script>
		<script src="/includes/dist/vendor/datatables/datatables.min.js"></script>
		<!-- Initialization  -->
		<script src="/includes/dist/js/sidebar-nav.js"></script>
		<script src="/includes/dist/js/main.js"></script>
	</body>
</html>

<?php

// Calculate Execution time Uncomment for test

//$time_end = microtime(true);
//$execution_time = ($time_end - $time_start);
//echo '<h2>Total Execution Time: '.number_format((float) $execution_time, 10) .' seconds</h2>';
