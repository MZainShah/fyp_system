@include('includes.header')
@include('includes.sidebar')

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4 py-4">
            <div class="mb-4">
                <h2 class="fw-bold text-dark">Edit Notice</h2>
                <p class="text-muted">Sir, aap yahan se Google Sheet (Row #{{ $row }}) ka data update kar sakte hain.</p>
            </div>

            <div class="card border-0 shadow-sm" style="border-radius: 20px; overflow: hidden;">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('admin.notices.update', $row) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-4">
                            <!-- Date & Sender -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Date</label>
                                <input type="date" name="date" class="form-control form-control-lg bg-light border-0" value="{{ $notice[0] }}" required style="border-radius: 12px;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Sender Name</label>
                                <input type="text" name="sender" class="form-control form-control-lg bg-light border-0" value="{{ $notice[1] }}" required style="border-radius: 12px;">
                            </div>

                            <!-- Title -->
                            <div class="col-12">
                                <label class="form-label fw-bold">Notice Title</label>
                                <input type="text" name="title" class="form-control form-control-lg bg-light border-0" value="{{ $notice[2] }}" required style="border-radius: 12px;">
                            </div>

                            <!-- Message -->
                            <div class="col-12">
                                <label class="form-label fw-bold">Notice Message</label>
                                <textarea name="message" class="form-control bg-light border-0" rows="6" required style="border-radius: 12px;">{{ $notice[3] }}</textarea>
                            </div>

                            <!-- Document Link -->
                            <div class="col-12">
                                <label class="form-label fw-bold">PDF / Document Link (Optional)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-0"><i class="fas fa-link text-primary"></i></span>
                                    <input type="url" name="doc_link" class="form-control form-control-lg bg-light border-0" value="{{ $notice[4] ?? '' }}" placeholder="https://..." style="border-top-right-radius: 12px; border-bottom-right-radius: 12px;">
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="col-12 mt-5">
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow">
                                    <i class="fas fa-save me-2"></i> Update Notice
                                </button>
                                <a href="{{ route('admin.notices.index') }}" class="btn btn-light btn-lg rounded-pill px-4 ms-2">
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
    @include('includes.footer')
</div>