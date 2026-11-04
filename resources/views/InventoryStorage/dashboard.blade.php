<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management Dashboard | DRRM Compliance</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --drrm-teal: #0D7377;
            --drrm-teal-hover: #0a5a5d;
            --drrm-teal-light: #e6f1f1;
            --drrm-teal-border: #20a39e;
        }

        body {
            background-color: #f4f7f6;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Sidebar Styling */
        .sidebar {
            min-height: 100vh;
            background-color: var(--drrm-teal);
            color: white;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.75);
            padding: 0.8rem 1.5rem;
            transition: all 0.3s;
            border-radius: 0;
        }

        .sidebar .nav-link:hover, 
        .sidebar .nav-link.active {
            color: white;
            background-color: var(--drrm-teal-hover);
            border-left: 4px solid #fff;
        }

        .sidebar-heading {
            font-size: 1.1rem;
            font-weight: bold;
            text-transform: uppercase;
            padding: 1.5rem;
            letter-spacing: 1px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        /* Card Styling */
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .card-header {
            background-color: transparent;
            border-bottom: 1px solid #edf2f2;
            font-weight: 600;
            color: var(--drrm-teal);
        }

        .stat-card {
            border-left: 4px solid var(--drrm-teal);
        }

        .btn-teal {
            background-color: var(--drrm-teal);
            color: white;
        }

        .btn-teal:hover {
            background-color: var(--drrm-teal-hover);
            color: white;
        }

        .map-container {
            height: 450px;
            background-color: #e9ecef;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px dashed var(--drrm-teal);
            color: var(--drrm-teal);
        }

        .text-teal {
            color: var(--drrm-teal) !important;
        }

        .badge-hazard {
            padding: 0.5em 0.8em;
            border-radius: 4px;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse px-0">
            <div class="sidebar-heading text-center">
                <i class="fa-solid fa-boxes-stacked me-2"></i> Inventory Manager
            </div>
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">
                            <i class="fa-solid fa-gauge-high me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('inventory-storage.default-list') }}">
                            <i class="fa-solid fa-list me-2"></i> Default List
                        </a>
                    </li>
                </ul>

                <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-white-50" style="font-size: 0.75rem; border: none;">
                    <span>Saved Reports</span>
                </h6>
                <ul class="nav flex-column mb-2">
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fa-solid fa-file-lines me-2"></i> Monthly Summary
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <!-- Header -->
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Inventory & Resource Management</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
                    </div>
                    <a href="{{ url('/dashboard') }}" class="btn btn-sm btn-outline-secondary me-2">
                        <i class="fa-solid fa-arrow-left me-1"></i> Back to Dashboard
</a>
                    <button type="button" class="btn btn-sm btn-teal" data-bs-toggle="modal" data-bs-target="#addItemModal">
                        <i class="fa-solid fa-plus me-1"></i> Add New Item
                    </button>
                </div>
            </div>

            <!-- Recent Logs Table -->
            <div class="card shadow-sm mb-4">
                <div class="card-header py-3">
                    <i class="fa-solid fa-list-check me-2"></i> Inventory View
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>Item Name</th>
                                    <th>Unit</th>
                                    <th>Quantity</th>
                                    <th>Status</th>
                                    <th>Location</th>
                                    <th>Fund Source</th>
                                    <th>Date Received</th>
                                    <th>Date Checked</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($items as $item)
                                    <tr>
                                        <td>{{ $item->item_name }}</td>
                                        <td>{{ $item->unit }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td><span class="badge bg-info text-dark">{{ str_replace('_', ' ', $item->status) }}</span></td>
                                        <td>{{ $item->location }}</td>
                                        <td>{{ $item->fund_source }}</td>
                                        <td>{{ $item->date_received ? \Carbon\Carbon::parse($item->date_received)->format('M d, Y') : 'N/A' }}</td>
                                        <td>{{ $item->date_checked ? \Carbon\Carbon::parse($item->date_checked)->format('M d, Y') : 'N/A' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">No inventory items found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

                </div><!-- /panel-monthly-summary -->

            </div><!-- /page-body -->
        </div><!-- /main-content -->
    </div><!-- /layout -->


    <div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addItemModalLabel"><i class="fa-solid fa-plus"></i> Add New Inventory
                        Item</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('inventory-storage.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-section-label"><i class="fa-solid fa-cube"></i> Item Details</div>
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label" for="add-item-name">Item Name</label>
                                <input type="text" class="form-control" id="add-item-name" name="item_name"
                                    placeholder="Enter item name..." required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="add-unit">Unit</label>
                                <select class="form-select" name="unit" id="add-unit">
                                    <option value="" disabled selected hidden>Select unit type</option>
                                    <option value="Boxes">Boxes</option>
                                    <option value="Sets">Sets</option>
                                    <option value="Pieces">Pieces</option>
                                    <option value="Others">Others (Specify below)</option>
                                </select>
                                <div class="mt-2 d-none" id="addUnitOtherWrapper">
                                    <input type="text" class="form-control" name="unit_other" id="addUnitOtherInput"
                                        placeholder="crates, sets, pallets…">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="add-quantity">Quantity</label>
                                <input type="number" class="form-control" id="add-quantity" name="quantity" min="0"
                                    value="0" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="add-status">Status</label>
                                <select class="form-select" id="add-status" name="status" required>
                                    <option value="working">Working</option>
                                    <option value="needs_attention">Needs Attention</option>
                                    <option value="for_repair">For Repair</option>
                                    <option value="defective">Defective</option>
                                    <option value="missing">Missing</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" for="add-location">Location</label>
                                <input type="text" class="form-control" id="add-location" name="location"
                                    placeholder="Storage area">
                            </div>
                        </div>
                        <div class="form-section-label" style="margin-top:24px;"><i
                                class="fa-solid fa-money-bill-wave"></i> Funding & Dates</div>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label" for="add-fund-source">Fund Source</label>
                                <select class="form-select" id="add-fund-source" name="fund_source">
                                    <option value="" disabled selected hidden>Select funding source</option>
                                    <option value="School MOOE">School MOOE</option>
                                    <option value="SEF">SEF</option>
                                    <option value="Division Office">Division Office</option>
                                    <option value="Regional Office">Regional Office</option>
                                    <option value="Central Office">Central Office</option>
                                    <option value="FTPA/PTA/GPTA">FTPA / PTA / GPTA</option>
                                    <option value="Others">Others (Specify below)</option>
                                </select>
                                <div class="mt-2 d-none" id="addFundOtherWrapper">
                                    <input type="text" class="form-control" name="fund_source_other"
                                        id="addFundOtherInput" placeholder="e.g., donation, specific project">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="add-date-received">Date Received <small>(per custodian
                                        slip)</small></label>
                                <input type="date" class="form-control" id="add-date-received" name="date_received"
                                    value="{{ date('Y-m-d') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label" for="add-date-checked">Date Checked</label>
                                <input type="date" class="form-control" id="add-date-checked" name="date_checked"
                                    value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-primary-teal"><i class="fa-solid fa-floppy-disk"></i> Save
                            Item</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ── EDIT ITEM MODAL ── -->
    <div class="modal fade" id="editItemModal" tabindex="-1" aria-labelledby="editItemModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editItemModalLabel"><i class="fa-solid fa-pen"></i> Edit Inventory Item
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-section-label"><i class="fa-solid fa-cube"></i> Item Details</div>
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label" for="edit-item-name">Item Name</label>
                            <input type="text" class="form-control" id="edit-item-name" placeholder="Enter item name..."
                                required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="edit-unit">Unit</label>
                            <select class="form-select" id="edit-unit">
                                <option value="">— none —</option>
                                <option value="Boxes">Boxes</option>
                                <option value="Sets">Sets</option>
                                <option value="Pieces">Pieces</option>
                                <option value="Others">Others</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="edit-quantity">Quantity</label>
                            <input type="number" class="form-control" id="edit-quantity" min="0" value="0">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="edit-status">Status</label>
                            <select class="form-select" id="edit-status">
                                <option value="working">Working</option>
                                <option value="needs_attention">Needs Attention</option>
                                <option value="for_repair">For Repair</option>
                                <option value="defective">Defective</option>
                                <option value="missing">Missing</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="edit-location">Location</label>
                            <input type="text" class="form-control" id="edit-location" placeholder="Storage area">
                        </div>
                    </div>
                    <div class="form-section-label" style="margin-top:24px;"><i class="fa-solid fa-money-bill-wave"></i>
                        Funding & Dates</div>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label" for="edit-fund-source">Fund Source</label>
                            <select class="form-select" id="edit-fund-source">
                                <option value="">— none —</option>
                                <option value="School MOOE">School MOOE</option>
                                <option value="SEF">SEF</option>
                                <option value="Division Office">Division Office</option>
                                <option value="Regional Office">Regional Office</option>
                                <option value="Central Office">Central Office</option>
                                <option value="FTPA/PTA/GPTA">FTPA / PTA / GPTA</option>
                                <option value="Others">Others</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="edit-date-received">Date Received</label>
                            <input type="date" class="form-control" id="edit-date-received">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="edit-date-checked">Date Checked</label>
                            <input type="date" class="form-control" id="edit-date-checked">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn-primary-teal" id="btn-save-edit"><i
                            class="fa-solid fa-floppy-disk"></i> Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ── DELETE CONFIRMATION MODAL ── -->
    <div class="modal fade" id="deleteItemModal" tabindex="-1" aria-labelledby="deleteItemModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteItemModalLabel"><i class="fa-solid fa-triangle-exclamation"
                            style="color: var(--missing);"></i> Delete Item</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Are you sure you want to delete "<strong id="deleteItemName"></strong>"?<br><br>This
                        action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn-danger-sm py-2 px-3" id="btn-confirm-delete"><i
                            class="fa-solid fa-trash"></i> Delete Item</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ── SCHOOL SWITCHER MODAL (collapsed sidebar) ── -->
    @if(auth()->user()->role === 'admin' && $schools->count() > 1)
        <div class="modal fade" id="schoolSwitcherModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fa-solid fa-school"></i> Select Active School</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="padding:16px;">
                        <div style="position:relative;margin-bottom:10px;">
                            <i class="fa-solid fa-magnifying-glass"
                                style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#8aa0a0;font-size:.8rem;"></i>
                            <input type="text" id="modalSchoolSearch" placeholder="Search schools..."
                                style="width:100%;border:1.5px solid #dde8e8;border-radius:10px;padding:8px 12px 8px 34px;font-size:.83rem;font-family:inherit;outline:none;color:#1a2e2e;transition:border-color .2s;"
                                autocomplete="off">
                        </div>
                        <div id="modalSchoolList"
                            style="max-height:280px;overflow-y:auto;display:flex;flex-direction:column;gap:4px;">
                            @foreach($schools as $s)
                                <div class="modal-school-item {{ $school && $school->id == $s->id ? 'modal-school-active' : '' }}"
                                    data-id="{{ $s->id }}" data-name="{{ $s->school_name }}"
                                    style="display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:10px;cursor:pointer;transition:background .15s;border:1.5px solid transparent;">
                                    <div
                                        style="width:32px;height:32px;border-radius:8px;background:rgba(21,143,143,.1);color:var(--teal);display:flex;align-items:center;justify-content:center;font-size:.8rem;flex-shrink:0;">
                                        <i class="fa-solid fa-school"></i>
                                    </div>
                                    <span
                                        style="font-size:.85rem;font-weight:500;color:#2a4040;flex:1;">{{ $s->school_name }}</span>
                                    <i class="fa-solid fa-check"
                                        style="color:var(--teal);font-size:.75rem;{{ $school && $school->id == $s->id ? '' : 'display:none;' }}"></i>
                                </div>
                            @endforeach
                            <div id="modalSchoolEmpty"
                                style="display:none;padding:24px;text-align:center;color:#8aa0a0;font-size:.83rem;">No
                                schools found</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- ── TOAST CONTAINER ── -->
    <div class="toast-wrap" id="toastWrap"></div>

    <!-- ── IMAGE HOVER PREVIEW ── -->
    <div id="img-preview-overlay">
        <img id="img-preview-img" src="" alt="Preview">
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const CSRF = document.querySelector('meta[name="csrf-token"]').content;

        // ── helpers ──────────────────────────────────────────────────
        function toast(msg, type = 'success') {
            var wrap = document.getElementById('toastWrap');
            var el = document.createElement('div');
            el.className = 'toast-msg ' + type;
            el.innerHTML = '<i class="fa-solid ' + (type === 'success' ? 'fa-circle-check' : 'fa-circle-xmark') + '"></i> ' + msg;
            wrap.appendChild(el);
            setTimeout(function () { el.remove(); }, 3500);
        }

        function apiFetch(url, method, body) {
            return fetch(url, {
                method: method,
                headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: body ? JSON.stringify(body) : undefined,
            }).then(function (r) { return r.json(); });
        }

        // ── Panel switcher ────────────────────────────────────────────
        var panelTitles = { 'panel-dashboard': 'Inventory & Resource Management', 'panel-default-list': 'Default Inventory List', 'panel-monthly-summary': 'Monthly Summary' };
        var panelSubs = { 'panel-dashboard': '{{ $school ? $school->school_name : "No school selected" }} · Storage overview', 'panel-default-list': '{{ $school ? $school->school_name : "No school selected" }} · Compliance checklist', 'panel-monthly-summary': '{{ $school ? $school->school_name : "No school selected" }} · {{ now()->format("F Y") }}' };

        function switchPanel(panelId) {
            document.querySelectorAll('.panel').forEach(function (p) { p.classList.remove('active'); });
            document.getElementById(panelId).classList.add('active');
            document.querySelectorAll('.sidebar-nav .nav-link[data-panel]').forEach(function (b) {
                b.classList.toggle('active', b.dataset.panel === panelId);
            });
            document.getElementById('topbar-title').textContent = panelTitles[panelId] || '';

            // Toggle topbar action buttons
            var btnExport = document.getElementById('btn-export');
            var btnAddItem = document.getElementById('btn-add-item');
            var isMonthly = panelId === 'panel-monthly-summary';
            if (btnExport) btnExport.style.display = isMonthly ? '' : 'none';
            if (btnAddItem) btnAddItem.style.display = isMonthly ? 'none' : '';
        }

        document.querySelectorAll('[data-panel]').forEach(function (btn) {
            btn.addEventListener('click', function () { switchPanel(this.dataset.panel); });
        });

        // ── Default List filter ───────────────────────────────────────
        function applyFilter(filter) {
            document.querySelectorAll('.section-tab').forEach(function (t) { t.classList.toggle('active', t.dataset.filter === filter); });
            document.querySelectorAll('.section-card[data-section]').forEach(function (card) {
                card.style.display = (filter === 'all' || card.dataset.section.toLowerCase() === filter) ? '' : 'none';
            });
        }
        document.querySelectorAll('.section-tab').forEach(function (tab) {
            tab.addEventListener('click', function () { applyFilter(this.dataset.filter); });
        });
        applyFilter('all');

        // ── Per-section search ────────────────────────────────────────
        ['a', 'b'].forEach(function (s) {
            var input = document.getElementById('search-' + s);
            if (!input) return;
            input.addEventListener('input', function () {
                var q = this.value.toLowerCase();
                var visibleCount = 0;
                document.querySelectorAll('#table-' + s + ' tbody tr.compliance-row').forEach(function (row) {
                    var match = row.textContent.toLowerCase().includes(q);
                    row.style.display = match ? '' : 'none';
                    if (match) visibleCount++;
                });
                var noDataRow = document.getElementById('no-search-' + s);
                if (noDataRow) noDataRow.style.display = (visibleCount === 0) ? 'table-row' : 'none';
            });
        });

        // ── Dashboard search ──────────────────────────────────────────
        document.getElementById('inventorySearch').addEventListener('input', function () {
            var q = this.value.toLowerCase();
            document.querySelectorAll('#inventoryTable tbody tr').forEach(function (row) {
                row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
        });

        // ── Source cascade ────────────────────────────────────────────
        document.querySelectorAll('.source-select-wrap').forEach(function (wrap) {
            var srcSel = wrap.querySelector('.source-select');
            var deped = wrap.querySelector('.deped-options');
            var partner = wrap.querySelector('.partner-options');
            var otherInput = wrap.querySelector('.other-source-input');

            function toggleOtherInput() {
                if (srcSel.value === 'deped' && deped.value === 'Others (Please Specify)') {
                    otherInput.classList.remove('d-none');
                } else if (srcSel.value === 'partner' && partner.value === 'Others (Please Specify)') {
                    otherInput.classList.remove('d-none');
                } else {
                    otherInput.classList.add('d-none');
                }
            }

            srcSel.addEventListener('change', function () {
                deped.classList.add('d-none'); 
                partner.classList.add('d-none');
                if (this.value === 'deped') deped.classList.remove('d-none');
                if (this.value === 'partner') partner.classList.remove('d-none');
                toggleOtherInput();
            });

            if (deped) deped.addEventListener('change', toggleOtherInput);
            if (partner) partner.addEventListener('change', toggleOtherInput);
        });

        // ── Compliance score updater ──────────────────────────────────
        function updateScore(section) {
            var s = section.toLowerCase();
            var rows = document.querySelectorAll('#table-' + s + ' tbody tr.compliance-row');
            var total = rows.length;
            var have = 0;
            rows.forEach(function (r) { if (r.querySelector('.have-checkbox').checked) have++; });
            var pct = total > 0 ? Math.round((have / total) * 100) : 0;
            var el = document.getElementById('score-val-' + s);
            var sub = document.getElementById('score-sub-' + s);
            var bar = document.getElementById('score-bar-' + s);
            if (el) { el.textContent = pct + '%'; el.className = 'cs-value ' + (pct >= 80 ? 'cs-good' : pct >= 50 ? 'cs-fair' : 'cs-poor'); }
            if (sub) sub.textContent = have + ' / ' + total + ' sourced';
            if (bar) { bar.style.width = pct + '%'; bar.className = 'progress-thin-bar ' + (pct >= 80 ? 'bar-good' : pct >= 50 ? 'bar-fair' : 'bar-poor'); }

            // Update overall score
            var allRows = document.querySelectorAll('.compliance-row');
            var allTotal = allRows.length;
            var allHave = 0;
            allRows.forEach(function (r) { if (r.querySelector('.have-checkbox').checked) allHave++; });
            var allPct = allTotal > 0 ? Math.round((allHave / allTotal) * 100) : 0;

            var allEl = document.getElementById('score-val-all');
            var allSub = document.getElementById('score-sub-all');
            var allBar = document.getElementById('score-bar-all');
            if (allEl) { allEl.textContent = allPct + '%'; allEl.className = 'cs-value ' + (allPct >= 80 ? 'cs-good' : allPct >= 50 ? 'cs-fair' : 'cs-poor'); }
            if (allSub) allSub.textContent = allHave + ' / ' + allTotal + ' items sourced';
            if (allBar) { allBar.style.width = allPct + '%'; allBar.className = 'progress-thin-bar ' + (allPct >= 80 ? 'bar-good' : allPct >= 50 ? 'bar-fair' : 'bar-poor'); }
        }

        document.querySelectorAll('.have-checkbox').forEach(function (cb) {
            cb.addEventListener('change', function () {
                var section = this.closest('tr').dataset.section;
                updateScore(section);
            });
        });

        // ── Save section ──────────────────────────────────────────────
        document.querySelectorAll('.btn-save-section').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var section = this.dataset.section;
                var s = section.toLowerCase();
                var rows = document.querySelectorAll('#table-' + s + ' tbody tr.compliance-row');
                var items = [];
                rows.forEach(function (row) {
                    var wrap = row.querySelector('.source-select-wrap');
                    var srcSel = wrap.querySelector('.source-select');
                    var src = srcSel.value;
                    var detail = '';
                    if (src === 'deped') detail = wrap.querySelector('.deped-options').value;
                    if (src === 'partner') detail = wrap.querySelector('.partner-options').value;
                    
                    if (detail === 'Others (Please Specify)') {
                        var otherInput = wrap.querySelector('.other-source-input');
                        if (otherInput && otherInput.value.trim() !== '') {
                            detail = otherInput.value.trim();
                        }
                    }
                    items.push({
                        item_key: row.dataset.key,
                        has_item: row.querySelector('.have-checkbox').checked,
                        quantity_owned: parseInt(row.querySelector('.qty-input').value) || 0,
                        source: src || null,
                        source_detail: detail || null,
                        date_checked: row.querySelector('.form-control-date').value || null,
                        remarks: row.querySelector('.remarks-input').value || null,
                    });
                });

                var statusEl = document.getElementById('save-status-' + s);
                if (statusEl) statusEl.textContent = 'Saving…';

                apiFetch('{{ route("inventory-storage.default-list.save") }}', 'POST', { section: section, items: items })
                    .then(function (res) {
                        if (res.success) {
                            toast(res.message || 'Saved!', 'success');
                            if (statusEl) statusEl.textContent = 'Saved · ' + new Date().toLocaleTimeString();
                        } else {
                            toast(res.message || 'Error saving.', 'error');
                            if (statusEl) statusEl.textContent = '';
                        }
                    }).catch(function () {
                        toast('Network error. Please try again.', 'error');
                        if (statusEl) statusEl.textContent = '';
                    });
            });
        });

        // ── Add modal: "Others" toggles ───────────────────────────────
        document.getElementById('add-fund-source').addEventListener('change', function () {
            var w = document.getElementById('addFundOtherWrapper'), i = document.getElementById('addFundOtherInput');
            if (this.value === 'Others') { w.classList.remove('d-none'); i.setAttribute('required', 'required'); }
            else { w.classList.add('d-none'); i.removeAttribute('required'); i.value = ''; }
        });
        document.getElementById('add-unit').addEventListener('change', function () {
            var w = document.getElementById('addUnitOtherWrapper'), i = document.getElementById('addUnitOtherInput');
            if (this.value === 'Others') { w.classList.remove('d-none'); i.setAttribute('required', 'required'); }
            else { w.classList.add('d-none'); i.removeAttribute('required'); i.value = ''; }
        });

        // ── Edit modal ────────────────────────────────────────────────
        var currentEditId = null;
        var editModal = new bootstrap.Modal(document.getElementById('editItemModal'));

        document.querySelectorAll('.btn-edit-item').forEach(function (btn) {
            btn.addEventListener('click', function () {
                currentEditId = this.dataset.id;
                apiFetch('{{ url("inventory-storage/item") }}/' + currentEditId, 'GET')
                    .then(function (item) {
                        document.getElementById('edit-item-name').value = item.item_name || '';
                        document.getElementById('edit-unit').value = item.unit || '';
                        document.getElementById('edit-quantity').value = item.quantity || 0;
                        document.getElementById('edit-status').value = item.status || 'working';
                        document.getElementById('edit-location').value = item.location || '';
                        document.getElementById('edit-fund-source').value = item.fund_source || '';
                        document.getElementById('edit-date-received').value = item.date_received || '';
                        document.getElementById('edit-date-checked').value = item.date_checked || '';
                        editModal.show();
                    }).catch(function () { toast('Could not load item data.', 'error'); });
            });
        });

        document.getElementById('btn-save-edit').addEventListener('click', function () {
            if (!currentEditId) return;
            var body = {
                item_name: document.getElementById('edit-item-name').value,
                unit: document.getElementById('edit-unit').value,
                quantity: parseInt(document.getElementById('edit-quantity').value) || 0,
                status: document.getElementById('edit-status').value,
                location: document.getElementById('edit-location').value,
                fund_source: document.getElementById('edit-fund-source').value,
                date_received: document.getElementById('edit-date-received').value,
                date_checked: document.getElementById('edit-date-checked').value,
                _method: 'PUT',
            };
            fetch('{{ url("inventory-storage/update") }}/' + currentEditId, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(body),
            }).then(function (r) { return r.json(); })
                .then(function (res) {
                    if (res.success) {
                        toast('Item updated successfully.', 'success');
                        editModal.hide();
                        setTimeout(function () { location.reload(); }, 800);
                    } else { toast(res.message || 'Update failed.', 'error'); }
                }).catch(function () { toast('Network error.', 'error'); });
        });

        // ── Delete ────────────────────────────────────────────────────
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteItemModal'));
        var currentDeleteId = null;

        document.querySelectorAll('.btn-delete-item').forEach(function (btn) {
            btn.addEventListener('click', function () {
                currentDeleteId = this.dataset.id;
                document.getElementById('deleteItemName').textContent = this.dataset.name;
                deleteModal.show();
            });
        });

        document.getElementById('btn-confirm-delete').addEventListener('click', function () {
            if (!currentDeleteId) return;
            apiFetch('{{ url("inventory-storage/destroy") }}/' + currentDeleteId, 'DELETE')
                .then(function (res) {
                    if (res.success) {
                        toast('Item deleted.', 'success');
                        var row = document.querySelector('#inventoryTable tr[data-id="' + currentDeleteId + '"]');
                        if (row) {
                            var status = row.dataset.status;
                            row.remove();

                            // Update stats
                            var totalEl = document.getElementById('stat-total');
                            if (totalEl) totalEl.textContent = Math.max(0, parseInt(totalEl.textContent) - 1);
                            var statEl = document.getElementById('stat-' + status);
                            if (statEl) statEl.textContent = Math.max(0, parseInt(statEl.textContent) - 1);

                            // Check if empty
                            var remaining = document.querySelectorAll('#inventoryTable tbody tr.item-row');
                            if (remaining.length === 0) {
                                var emptyRow = document.getElementById('emptyInventoryRow');
                                if (emptyRow) emptyRow.style.display = 'flex';
                            }
                        }
                        deleteModal.hide();
                    } else { toast(res.message || 'Delete failed.', 'error'); }
                }).catch(function () { toast('Network error.', 'error'); });
        });

        // ── School switcher (admin) ───────────────────────────────────
        function switchSchool(schoolId) {
            apiFetch('{{ route("inventory-storage.set-school") }}', 'POST', { id: schoolId })
                .then(function (res) { if (res.success) location.reload(); })
                .catch(function () { toast('Could not switch school.', 'error'); });
        }

        // Sidebar custom picker
        var pickerBtn = document.getElementById('schoolPickerBtn');
        var pickerDropdown = document.getElementById('schoolPickerDropdown');
        var pickerSearch = document.getElementById('schoolPickerSearch');
        var pickerList = document.getElementById('schoolPickerList');
        var pickerEmpty = document.getElementById('schoolPickerEmpty');

        if (pickerBtn) {
            // Toggle dropdown
            pickerBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                var isOpen = pickerDropdown.classList.toggle('open');
                pickerBtn.classList.toggle('open', isOpen);
                pickerBtn.setAttribute('aria-expanded', isOpen);
                if (isOpen) {
                    pickerSearch.value = '';
                    filterPicker('');
                    setTimeout(function () { pickerSearch.focus(); }, 50);
                }
            });

            // Search
            pickerSearch.addEventListener('input', function () {
                filterPicker(this.value.toLowerCase());
            });

            function filterPicker(q) {
                var items = pickerList.querySelectorAll('.school-picker-item');
                var visible = 0;
                items.forEach(function (item) {
                    var match = item.dataset.name.toLowerCase().includes(q);
                    item.style.display = match ? '' : 'none';
                    if (match) visible++;
                });
                pickerEmpty.style.display = visible === 0 ? '' : 'none';
            }

            // Select school
            pickerList.addEventListener('click', function (e) {
                var item = e.target.closest('.school-picker-item');
                if (!item) return;
                if (item.classList.contains('active')) {
                    pickerDropdown.classList.remove('open');
                    pickerBtn.classList.remove('open');
                    return;
                }
                switchSchool(item.dataset.id);
            });

            // Close on outside click
            document.addEventListener('click', function (e) {
                if (!document.getElementById('schoolSwitcherWrap').contains(e.target)) {
                    pickerDropdown.classList.remove('open');
                    pickerBtn.classList.remove('open');
                }
            });
        }

        // Modal picker (collapsed sidebar)
        var modalSchoolSearch = document.getElementById('modalSchoolSearch');
        var modalSchoolList = document.getElementById('modalSchoolList');
        var modalSchoolEmpty = document.getElementById('modalSchoolEmpty');

        if (modalSchoolSearch) {
            modalSchoolSearch.addEventListener('input', function () {
                var q = this.value.toLowerCase();
                var items = modalSchoolList.querySelectorAll('.modal-school-item');
                var visible = 0;
                items.forEach(function (item) {
                    var match = item.dataset.name.toLowerCase().includes(q);
                    item.style.display = match ? '' : 'none';
                    if (match) visible++;
                });
                modalSchoolEmpty.style.display = visible === 0 ? '' : 'none';
            });

            // Hover styles
            modalSchoolList.addEventListener('mouseover', function (e) {
                var item = e.target.closest('.modal-school-item');
                if (item && !item.classList.contains('modal-school-active')) item.style.background = '#f0f7f7';
            });
            modalSchoolList.addEventListener('mouseout', function (e) {
                var item = e.target.closest('.modal-school-item');
                if (item && !item.classList.contains('modal-school-active')) item.style.background = '';
            });

            // Click to switch
            modalSchoolList.addEventListener('click', function (e) {
                var item = e.target.closest('.modal-school-item');
                if (!item) return;
                switchSchool(item.dataset.id);
            });

            // Style active items
            document.querySelectorAll('.modal-school-active').forEach(function (item) {
                item.style.background = 'rgba(21,143,143,.08)';
                item.style.borderColor = 'rgba(21,143,143,.2)';
            });

            // Clear search when modal opens
            var schoolModal = document.getElementById('schoolSwitcherModal');
            if (schoolModal) {
                schoolModal.addEventListener('show.bs.modal', function () {
                    modalSchoolSearch.value = '';
                    modalSchoolList.querySelectorAll('.modal-school-item').forEach(function (i) { i.style.display = ''; });
                    modalSchoolEmpty.style.display = 'none';
                });
            }
        }

        // ── Session-based panel open ──────────────────────────────────
        @if(session('open_panel') === 'default-list')
            switchPanel('panel-default-list');
        @endif
        if (window.location.hash === '#default-list') switchPanel('panel-default-list');

        // ── Image Hover Preview ───────────────────────────────────────
        var previewOverlay = document.getElementById('img-preview-overlay');
        var previewImg = document.getElementById('img-preview-img');

        document.querySelectorAll('.item-img').forEach(function (img) {
            img.addEventListener('mouseenter', function (e) {
                previewImg.src = this.src;
                previewOverlay.style.display = 'block';
            });
            img.addEventListener('mousemove', function (e) {
                var offsetX = 15;
                var offsetY = 15;
                var x = e.clientX + offsetX;
                var y = e.clientY + offsetY;

                // Prevent going off screen (assuming max width/height ~320px)
                if (x + 320 > window.innerWidth) x = e.clientX - 320 - offsetX;
                if (y + 320 > window.innerHeight) y = e.clientY - 320 - offsetY;

                previewOverlay.style.left = x + 'px';
                previewOverlay.style.top = y + 'px';
            });
            img.addEventListener('mouseleave', function () {
                previewOverlay.style.display = 'none';
            });
        });
    </script>
</body>
</html>