@extends('admin.layouts.app')
@section('title', 'Website Cache Management')

@push('styles')
@endpush
@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box d-md-flex justify-content-md-between align-items-center">
                    <div>
                        <h4 class="mb-1 fw-semibold">Cache Management</h4>
                        <p class="text-muted small mb-0">Laravel application cache control panel</p>
                    </div>
                    <div class="">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
                            </li><!--end nav-item-->

                            </li><!--end nav-item-->
                            <li class="breadcrumb-item active">Cache Management</li>
                        </ol>
                    </div>
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->
        {{-- Header --}}


        {{-- Alert area --}}
        <div id="alert-area"></div>

        {{-- Status Cards --}}
        <div class="row g-3 mb-4">
            @php
                $statusCards = [
                    ['key' => 'route', 'label' => 'Route Cache', 'icon' => 'bi-signpost-2'],
                    ['key' => 'config', 'label' => 'Config Cache', 'icon' => 'bi-sliders'],
                    ['key' => 'view', 'label' => 'View Cache', 'icon' => 'bi-layout-text-window'],
                    ['key' => 'compiled', 'label' => 'Compiled Files', 'icon' => 'bi-file-earmark-code'],
                ];
            @endphp

            @foreach ($statusCards as $card)
                <div class="col-6 col-md-3">
                    <div class="card border-0 bg-light h-100">
                        <div class="card-body text-center py-3">
                            <i class="{{ $card['icon'] }} fs-4 mb-2 text-secondary d-block"></i>
                            <div class="small text-muted mb-1">{{ $card['label'] }}</div>
                            <span class="badge {{ $cacheStatus[$card['key']] ? 'bg-success' : 'bg-secondary' }}"
                                id="status-{{ $card['key'] }}">
                                {{ $cacheStatus[$card['key']] ? 'Cached' : 'No Cache' }}
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row g-4">

            {{-- Left: Individual Clear --}}
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 fw-semibold">Individual Cache Clear</h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-flex flex-column gap-2">

                            @php
                                $items = [
                                    [
                                        'type' => 'app',
                                        'label' => 'Application Cache',
                                        'cmd' => 'cache:clear',
                                        'color' => 'primary',
                                    ],
                                    [
                                        'type' => 'route',
                                        'label' => 'Route Cache',
                                        'cmd' => 'route:clear',
                                        'color' => 'warning',
                                    ],
                                    [
                                        'type' => 'config',
                                        'label' => 'Config Cache',
                                        'cmd' => 'config:clear',
                                        'color' => 'purple',
                                    ],
                                    [
                                        'type' => 'view',
                                        'label' => 'View Cache',
                                        'cmd' => 'view:clear',
                                        'color' => 'info',
                                    ],
                                    [
                                        'type' => 'event',
                                        'label' => 'Event Cache',
                                        'cmd' => 'event:clear',
                                        'color' => 'danger',
                                    ], // ← add
                                    [
                                        'type' => 'compiled',
                                        'label' => 'Compiled Files',
                                        'cmd' => 'clear-compiled',
                                        'color' => 'secondary',
                                    ], // ← add
                                ];
                            @endphp

                            @foreach ($items as $item)
                                <div class="d-flex align-items-center justify-content-between p-3 rounded border bg-white"
                                    id="row-{{ $item['type'] }}">
                                    <div>
                                        <div class="fw-medium small">{{ $item['label'] }}</div>
                                        <code class="text-muted" style="font-size:11px">php artisan
                                            {{ $item['cmd'] }}</code>
                                    </div>
                                    <button class="btn btn-sm btn-outline-{{ $item['color'] }} clear-btn"
                                        data-type="{{ $item['type'] }}" data-url="{{ route('admin.cache.clear') }}">
                                        <span class="btn-text">Clear</span>
                                        <span class="btn-spinner spinner-border spinner-border-sm d-none"
                                            role="status"></span>
                                    </button>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>

            {{-- Right: Clear All + Optimize + Log --}}
            <div class="col-lg-7">
                <div class="d-flex flex-column gap-4">

                    {{-- Clear All --}}
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="mb-0 fw-semibold">Clear All Cache</h6>
                        </div>
                        <div class="card-body p-3">
                            <p class="text-muted small mb-3">সব ধরনের cache একসাথে clear করুন।</p>

                            <div id="clear-all-progress" class="d-none mb-3">
                                <div class="d-flex justify-content-between small text-muted mb-1">
                                    <span id="progress-label">Clearing...</span>
                                    <span id="progress-pct">0%</span>
                                </div>
                                <div class="progress" style="height:6px">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                                        id="progress-bar" style="width:0%"></div>
                                </div>
                            </div>

                            <button class="btn btn-danger w-100" id="clear-all-btn"
                                data-url="{{ route('admin.cache.clearAll') }}">
                                <i class="bi bi-trash me-2"></i>
                                <span id="clear-all-text">Clear All Cache</span>
                            </button>
                        </div>
                    </div>

                    {{-- Optimize --}}
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="mb-0 fw-semibold">Optimize — Cache Rebuild</h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="d-flex flex-column gap-2">
                                @php
                                    $optimizes = [
                                        [
                                            'type' => 'route_cache',
                                            'label' => 'Route Cache Rebuild',
                                            'cmd' => 'route:cache',
                                        ],
                                        [
                                            'type' => 'config_cache',
                                            'label' => 'Config Cache Rebuild',
                                            'cmd' => 'config:cache',
                                        ],
                                        ['type' => 'optimize', 'label' => 'Full Optimize', 'cmd' => 'optimize'],
                                    ];
                                @endphp
                                @foreach ($optimizes as $opt)
                                    <div
                                        class="d-flex align-items-center justify-content-between p-3 rounded border bg-white">
                                        <div>
                                            <div class="fw-medium small">{{ $opt['label'] }}</div>
                                            <code class="text-muted" style="font-size:11px">php artisan
                                                {{ $opt['cmd'] }}</code>
                                        </div>
                                        <button class="btn btn-sm btn-outline-success optimize-btn"
                                            data-type="{{ $opt['type'] }}"
                                            data-url="{{ route('admin.cache.optimize') }}">
                                            <span class="btn-text">Run</span>
                                            <span class="btn-spinner spinner-border spinner-border-sm d-none"
                                                role="status"></span>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Activity Log --}}
                    <div class="card border-0 shadow-sm">
                        <div
                            class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-semibold">Activity Log</h6>
                            <button class="btn btn-sm btn-link text-muted p-0" id="clear-log-btn">Clear</button>
                        </div>
                        <div class="card-body p-0">
                            <div id="log-box"
                                style="font-family:monospace;font-size:12px;max-height:180px;overflow-y:auto;padding:12px;background:#f8f9fa">
                                <div class="text-primary">→ Dashboard loaded. Ready.</div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection
@push('scripts')
    <script>
        const csrfToken = '{{ csrf_token() }}';

        function addLog(msg, type = 'primary') {
            const box = document.getElementById('log-box');
            const time = new Date().toTimeString().slice(0, 8);
            const el = document.createElement('div');
            el.className = 'text-' + type;
            el.textContent = '[' + time + '] ' + msg;
            box.appendChild(el);
            box.scrollTop = box.scrollHeight;
        }

        function showAlert(msg, type = 'success') {
            const area = document.getElementById('alert-area');
            area.innerHTML = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
        ${msg}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>`;
            setTimeout(() => {
                area.innerHTML = '';
            }, 4000);
        }

        // Individual clear buttons
        document.querySelectorAll('.clear-btn').forEach(btn => {
            btn.addEventListener('click', async function() {
                const type = this.dataset.type;
                const url = this.dataset.url;
                const spinner = this.querySelector('.btn-spinner');
                const text = this.querySelector('.btn-text');

                this.disabled = true;
                spinner.classList.remove('d-none');
                text.textContent = '...';
                addLog('Running: php artisan cache clear [' + type + ']', 'primary');

                try {
                    const res = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            type
                        })
                    });
                    const data = await res.json();

                    if (data.success) {
                        addLog(data.output, 'success');
                        toastr.success('Cache Clear Successfully!');
                        showAlert('<i class="bi bi-check-circle me-1"></i>' + data.output, 'success');
                        const statusBadge = document.getElementById('status-' + type);
                        if (statusBadge) {
                            statusBadge.textContent = 'Cleared';
                            statusBadge.className = 'badge bg-secondary';
                        }
                    } else {
                        toastr.error('Something went wrong!');
                        addLog('Error: ' + data.output, 'danger');
                        showAlert(data.output, 'danger');
                    }
                } catch (e) {
                    addLog('Request failed: ' + e.message, 'danger');
                    showAlert('Server error. Please try again.', 'danger');
                    toastr.error('Something went wrong!');
                } finally {
                    this.disabled = false;
                    spinner.classList.add('d-none');
                    text.textContent = 'Clear';
                }
            });
        });

        // Optimize buttons
        document.querySelectorAll('.optimize-btn').forEach(btn => {
            btn.addEventListener('click', async function() {
                const type = this.dataset.type;
                const url = this.dataset.url;
                const spinner = this.querySelector('.btn-spinner');
                const text = this.querySelector('.btn-text');

                this.disabled = true;
                spinner.classList.remove('d-none');
                text.textContent = '...';
                addLog('Running optimize: ' + type, 'primary');

                try {
                    const res = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            type
                        })
                    });
                    const data = await res.json();

                    if (data.success) {
                        addLog(data.output, 'success');
                        showAlert('<i class="bi bi-check-circle me-1"></i>' + data.output, 'success');
                    } else {
                        addLog('Error: ' + data.output, 'danger');
                        showAlert(data.output, 'danger');
                    }
                } catch (e) {
                    addLog('Request failed: ' + e.message, 'danger');
                } finally {
                    this.disabled = false;
                    spinner.classList.add('d-none');
                    text.textContent = 'Run';
                }
            });
        });

        // Clear All button
        document.getElementById('clear-all-btn').addEventListener('click', async function() {
            if (!confirm('সব cache clear করবেন?')) return;

            const progressWrap = document.getElementById('clear-all-progress');
            const progressBar = document.getElementById('progress-bar');
            const progressPct = document.getElementById('progress-pct');
            const progressLbl = document.getElementById('progress-label');
            const btnText = document.getElementById('clear-all-text');

            this.disabled = true;
            progressWrap.classList.remove('d-none');
            btnText.textContent = 'Clearing...';
            progressBar.style.width = '10%';
            progressPct.textContent = '10%';
            addLog('Starting full cache clear...', 'warning');

            try {
                const res = await fetch(this.dataset.url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });
                const data = await res.json();

                const total = data.results.length;
                data.results.forEach((r, i) => {
                    const pct = Math.round((i + 1) / total * 100);
                    progressBar.style.width = pct + '%';
                    progressPct.textContent = pct + '%';
                    progressLbl.textContent = r.label + '...';
                    addLog(r.output, r.success ? 'success' : 'danger');
                });

                if (data.success) {
                    showAlert('<i class="bi bi-check-circle me-1"></i>All caches cleared successfully!',
                        'success');
                    addLog('All done!', 'success');
                } else {
                    showAlert('Some caches could not be cleared. Check the log.', 'warning');
                }
            } catch (e) {
                addLog('Request failed: ' + e.message, 'danger');
                showAlert('Server error. Please try again.', 'danger');
            } finally {
                this.disabled = false;
                btnText.textContent = 'Clear All Cache';
                setTimeout(() => {
                    progressWrap.classList.add('d-none');
                    progressBar.style.width = '0%';
                }, 2000);
            }
        });

        // Clear log
        document.getElementById('clear-log-btn').addEventListener('click', () => {
            document.getElementById('log-box').innerHTML = '<div class="text-muted">→ Log cleared.</div>';
        });
    </script>
@endpush
