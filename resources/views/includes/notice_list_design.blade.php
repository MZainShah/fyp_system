<style>
    .notice-card {
        background: #ffffff;
        border-radius: 20px;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        border: 1px solid #edf2f7;
        position: relative;
        overflow: hidden;
    }

    .notice-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .date-badge {
        background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        color: white;
        padding: 15px;
        border-radius: 15px;
        min-width: 80px;
        text-align: center;
        box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.3);
    }

    .admin-tag {
        background: #f3f4f6;
        color: #374151;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
    }

    .doc-btn {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #475569;
        font-weight: 500;
        transition: all 0.2s;
        border-radius: 12px;
        padding: 8px 16px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }

    .doc-btn:hover {
        background: #6366f1;
        color: white;
        border-color: #6366f1;
    }

    .edit-btn {
        background: #fff9db;
        border: 1px solid #fab005;
        color: #856404;
        font-weight: 600;
        border-radius: 12px;
        padding: 8px 16px;
        transition: all 0.2s;
    }

    .edit-btn:hover {
        background: #fab005;
        color: white;
    }

    .notice-title {
        color: #1e293b;
        font-weight: 700;
        letter-spacing: -0.025em;
    }

    .fade-in-up {
        animation: fadeInUp 0.5s ease-out forwards;
        opacity: 0;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="row g-4">
    @forelse($notices as $index => $notice)
    <div class="col-12 fade-in-up" style="animation-delay: {{ $index * 0.1 }}s">
        <div class="notice-card p-4">
            <div class="d-flex align-items-start flex-wrap flex-md-nowrap gap-4">
                
                <!-- Date Badge -->
                <div class="date-badge flex-shrink-0">
                    <div class="fw-bold fs-4">{{ date('d', strtotime($notice[0])) }}</div>
                    <div class="small text-uppercase opacity-80" style="font-size: 0.7rem; font-weight: 800;">
                        {{ date('M Y', strtotime($notice[0])) }}
                    </div>
                </div>

                <!-- Content Area -->
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="admin-tag">
                            <i class="fas fa-shield-alt me-2 text-primary"></i> Published by {{ $notice[1] }}
                        </span>
                    </div>

                    <h4 class="notice-title mb-2">{{ $notice[2] }}</h4>
                    
                    <p class="text-muted mb-4" style="line-height: 1.6; font-size: 0.95rem;">
                        {{ $notice[3] }}
                    </p>

                    <div class="pt-3 border-top border-light d-flex gap-2">
                        <!-- View Document Link -->
                        @if(!empty($notice[4]))
                        <a href="{{ $notice[4] }}" target="_blank" class="doc-btn shadow-sm">
                            <i class="fas fa-file-invoice me-2"></i> View Document
                        </a>
                        @endif

                        <!-- Edit Button (Only for Admin) -->
                        @if(request()->is('admin/*'))
<div class="mt-3">
    <a href="{{ url('admin/edit-notice/' . ($notice['row_id'] ?? '')) }}" class="btn btn-sm btn-warning rounded-pill px-3 shadow-sm">
        <i class="fas fa-edit me-1"></i> Edit Notice
    </a>
</div>
@endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5">
        <div class="text-muted">
            <i class="fas fa-folder-open fa-3x mb-3 opacity-20"></i>
            <p>Sir, filhal koi notices share nahi kiye gaye.</p>
        </div>
    </div>
    @endforelse
</div>