<footer class="footer">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-6">
				<script>document.write(new Date().getFullYear())</script> © Picastro.
			</div>
			
		</div>
	</div>
</footer>


<div class="modal fade flip" id="deleteCamp" tabindex="-1" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-body p-5 text-center">
				<lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#405189,secondary:#f06548" style="width:90px;height:90px"></lord-icon>
				<div class="mt-4 text-center">
					<h4>You are about to delete ?</h4>
					<p class="text-muted fs-14 mb-4">Deleting your task will remove all of
						your information from our database.</p>
					<div class="hstack gap-2 justify-content-center remove">
						<button class="btn btn-link btn-ghost-success fw-medium text-decoration-none" id="deleteRecord-close" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Close</button>
						<button class="btn btn-danger" id="delete-record">Yes, Delete It</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--end delete modal -->