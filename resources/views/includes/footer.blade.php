<footer class="py-4 bg-light mt-auto">
    <div class="container-fluid px-4">
        <div class="d-flex align-items-center justify-content-between small">
            <div class="text-muted">Copyright &copy; Final Year Project Management System 2026</div>
            <div>
                <a href="#">Privacy Policy</a>
                &middot;
                <a href="#">Terms &amp; Conditions</a>
            </div>
        </div>
    </div>
</footer>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="{{ asset('js/scripts.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="{{ asset('assets/demo/chart-area-demo.js') }}"></script>
<script src="{{ asset('assets/demo/chart-bar-demo.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
<script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Saving Blade session variables into JS constants
        const successMsg = "{{ session('success') }}";
        const errorMsg = "{{ session('error') }}";
        const warningMsg = "{{ session('warning') }}";

        // Success Popup
        if (successMsg) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: successMsg,
                showConfirmButton: true, // Remains visible until clicked
                confirmButtonText: 'OK',
                confirmButtonColor: '#28a745'
            });
        }

        // Error Popup
        if (errorMsg) {
            Swal.fire({
                icon: 'error',
                title: 'Error Occurred!',
                text: errorMsg,
                showConfirmButton: true,
                confirmButtonText: 'Close',
                confirmButtonColor: '#d33'
            });
        }

        // Warning Popup
        if (warningMsg) {
            Swal.fire({
                icon: 'warning',
                title: 'Attention Required!',
                text: warningMsg,
                showConfirmButton: true,
                confirmButtonText: 'Got it',
                confirmButtonColor: '#f6ad55'
            });
        }
    });

    function confirmDelete(formId) {
    Swal.fire({
        title: 'Are you sure, Sir?',
        text: "This student record will be permanently deleted!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33', // Red for delete
        cancelButtonColor: '#3085d6', // Blue for cancel
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // This line finds the form by its ID and submits it
            document.getElementById(formId).submit();
        }
    });
}
</script>
</body>

</html>