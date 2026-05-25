@include('includes.header')
@include('includes.sidebar')

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4 py-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold">Notice Management</h2>
                <a href="{{ url('/admin/notices') }}" class="btn btn-primary rounded-pill px-4 shadow-sm">
    <i class="fas fa-plus me-2"></i> Create New Notice
</a>
            </div>

            <div class="row">
                <div class="col-xl-10">
                    @include('includes.notice_list_design')
                </div>
            </div>
        </div>
    </main>
</div>

<!-- EDIT NOTICE MODAL -->
<div class="modal fade" id="editNoticeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; border: none;">
            <div class="modal-header border-0 pt-4 px-4">
                <h5 class="modal-title fw-bold">Edit Notice Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editNoticeForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="small fw-bold">Date</label>
                            <input type="date" name="date" id="edit_date" class="form-control bg-light" required>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold">Sender Name</label>
                            <input type="text" name="sender" id="edit_sender" class="form-control bg-light" required>
                        </div>
                        <div class="col-12">
                            <label class="small fw-bold">Notice Title</label>
                            <input type="text" name="title" id="edit_title" class="form-control bg-light" required>
                        </div>
                        <div class="col-12">
                            <label class="small fw-bold">Notice Message</label>
                            <textarea name="message" id="edit_message" class="form-control bg-light" rows="4" required></textarea>
                        </div>
                        <div class="col-12">
                            <label class="small fw-bold">Document Link</label>
                            <input type="url" name="doc_link" id="edit_link" class="form-control bg-light">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pb-4 px-4">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openEditModal(rowId, date, sender, title, message, link) {
    // Form action URL update karein
    document.getElementById('editNoticeForm').action = "/admin/update-notice/" + rowId;
    
    // Form fields mein data bharein
    document.getElementById('edit_date').value = date;
    document.getElementById('edit_sender').value = sender;
    document.getElementById('edit_title').value = title;
    document.getElementById('edit_message').value = message;
    document.getElementById('edit_link').value = link;
    
    // Modal show karein
    var myModal = new bootstrap.Modal(document.getElementById('editNoticeModal'));
    myModal.show();
}
</script>