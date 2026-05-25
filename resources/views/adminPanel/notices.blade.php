<!-- include header here -->
@include('includes.header')
<!-- include sidebar here -->
@include('includes.sidebar')

<div id="layoutSidenav_content">
    <main>
        <div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Breadcrumb for Navigation -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item active">Create Notice</li>
                </ol>
            </nav>

            <div class="card border-0 shadow-lg" style="border-radius: 20px;">
                <!-- Header with Icon -->
                <div class="card-header bg-primary text-white p-4" style="border-radius: 20px 20px 0 0;">
                    <div class="d-flex align-items-center">
                        <div class="bg-white rounded-circle p-2 me-3">
                            <i class="fas fa-bullhorn text-primary fs-4"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-bold">Create New Notice</h4>
                            <small class="opacity-75">Sir, yahan se aap important updates publish kar sakte hain.</small>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4 p-md-5">
                    <!-- Success/Error Alerts -->
                    @if(session('success'))
                        <div class="alert alert-success border-0 shadow-sm mb-4">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.notices.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Notice Title -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Notice Title</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-heading text-muted"></i></span>
                                <input type="text" name="title" class="form-control bg-light border-start-0" 
                                       placeholder="e.g. Project Submission Deadline Extended" required>
                            </div>
                            @error('title') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <!-- Notice Message -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Notice Message</label>
                            <textarea name="message" class="form-control bg-light" rows="6" 
                                      placeholder="Sir, apna detail message yahan likhein..." required></textarea>
                            @error('message') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <!-- PDF Attachment (Optional) -->
                        <div class="mb-4">
                            <label class="form-label fw-bold text-dark">Google Doc / Attachment Link (Optional)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-link text-muted"></i></span>
                                <input type="url" name="doc_link" class="form-control bg-light border-start-0" 
                                    placeholder="https://docs.google.com/document/d/..." >
                            </div>
                            <small class="text-muted">Sir, file upload kar ke uska 'Anyone with the link' wala link yahan paste kar dein.</small>
                            @error('doc_link') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2 mt-5">
                            <button type="submit" class="btn btn-primary btn-lg shadow-sm" style="border-radius: 12px;">
                                <i class="fas fa-paper-plane me-2"></i> Publish Notice
                            </button>
                            <a href="#" class="btn btn-link text-muted btn-sm">Cancel and Return</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Simple Script to show selected File Name -->
<script>
    document.getElementById('pdfUpload').onchange = function () {
        var name = this.files[0] ? this.files[0].name : "Click to upload PDF or drag and drop";
        document.getElementById('fileNameDisplay').innerHTML = '<span class="text-primary fw-bold">' + name + '</span>';
    };
</script>

<style>
    .border-dashed {
        border-style: dashed !important;
        border-color: #dee2e6 !important;
        transition: all 0.3s ease;
    }
    .border-dashed:hover {
        border-color: #0d6efd !important;
        background-color: #f1f8ff !important;
    }
    .form-control:focus {
        box-shadow: none;
        border-color: #0d6efd;
    }
</style>
            </main>
            @include('includes.footer')