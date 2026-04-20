@extends('comprehensive-school-safety.layouts.app')
@section('activeMenu', 'storage')
@section('headerLabel', $school->name ?? 'Storage')

@push('styles')
<style>
    .btn-print-storage {
        background: #A8191F;
        color: #fff;
        border: none;
    }

    .storage-print-layout {
        display: none;
    }

    .storage-print-layout .header-container {
        position: relative;
        height: 80px;
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

    .storage-print-layout .info-grid {
        border: 1px solid #000;
        padding: 10px;
        margin-bottom: 14px;
        font-size: 12px;
    }

    .storage-print-layout .info-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 5px;
    }

    .storage-print-layout table {
        width: 100%;
        border-collapse: collapse;
    }

    .storage-print-layout th,
    .storage-print-layout td {
        border: 1px solid #000;
        padding: 8px;
        text-align: left;
        font-size: 11px;
    }

    .storage-print-layout th {
        background: #f2f2f2;
        text-transform: uppercase;
        font-size: 10px;
    }

    @media print {
        @page {
            size: portrait;
            margin: 0;
        }

        body {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            margin: 0;
            padding: 0;
            background: #fff;
        }

        .csss-sidebar,
        .csss-topbar,
        .csss-content > * {
            display: none !important;
        }

        .csss-main,
        .csss-content {
            margin: 0 !important;
            padding: 0 !important;
            background: #fff !important;
        }

        .csss-content .storage-print-layout {
            display: block !important;
            margin: 1cm;
        }

        .storage-print-layout thead {
            display: table-header-group;
        }

        .storage-print-layout tr {
            page-break-inside: avoid;
            break-inside: avoid;
        }
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="csss-section-title mb-1">Storage Inventory</h2>
        <p class="csss-muted mb-0">List items, equipment, tools, and other resources for this school.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <button type="button" class="btn btn-print-storage" onclick="window.print()">
            <i class="fas fa-print me-1"></i> Print Report
        </button>
        @if(auth()->user()->role !== 'viewer')
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStorageItemModal">
                <i class="fas fa-plus-circle me-1"></i> Add Item
            </button>
        @endif
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-triangle-exclamation me-1"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="csss-card p-4">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width: 20%;">Item / Equipment / Tool</th>
                    <th style="width: 15%;">Type</th>
                    <th style="width: 16%;">From</th>
                    <th style="width: 10%;" class="text-center">Available</th>
                    <th style="width: 10%;" class="text-center">Functional</th>
                    <th>Remarks</th>
                    @if(auth()->user()->role !== 'viewer')
                        <th style="width: 14%;" class="text-end">Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($storageItems as $item)
                    <tr>
                        <form method="POST" action="{{ route('comprehensive-school-safety.school.storage.update', [$school->id, $item->id]) }}">
                            @csrf
                            @method('PUT')
                            <td>
                                <input type="text" class="form-control form-control-sm" name="item_name" value="{{ $item->item_name }}" {{ auth()->user()->role === 'viewer' ? 'readonly' : '' }} required>
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-sm" name="item_type" value="{{ $item->item_type }}" placeholder="e.g. Item, Equipment, Tool" {{ auth()->user()->role === 'viewer' ? 'readonly' : '' }}>
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-sm" name="source_from" value="{{ $item->source_from }}" placeholder="e.g. LGU / Donation / School MOOE" {{ auth()->user()->role === 'viewer' ? 'readonly' : '' }}>
                            </td>
                            <td class="text-center">
                                <input type="hidden" name="is_available" value="0">
                                <input class="form-check-input" type="checkbox" name="is_available" value="1" {{ $item->is_available ? 'checked' : '' }} {{ auth()->user()->role === 'viewer' ? 'disabled' : '' }}>
                            </td>
                            <td class="text-center">
                                <input type="hidden" name="is_functional" value="0">
                                <input class="form-check-input" type="checkbox" name="is_functional" value="1" {{ $item->is_functional ? 'checked' : '' }} {{ auth()->user()->role === 'viewer' ? 'disabled' : '' }}>
                            </td>
                            <td>
                                <input type="text" class="form-control form-control-sm" name="remarks" value="{{ $item->remarks }}" {{ auth()->user()->role === 'viewer' ? 'readonly' : '' }}>
                            </td>
                            @if(auth()->user()->role !== 'viewer')
                                <td class="text-end">
                                    <button type="submit" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-save"></i>
                                    </button>
                        </form>
                                    <form method="POST" action="{{ route('comprehensive-school-safety.school.storage.destroy', [$school->id, $item->id]) }}" class="d-inline" onsubmit="return confirm('Delete this storage item?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ auth()->user()->role !== 'viewer' ? '7' : '6' }}" class="text-center py-5 text-muted">
                            <i class="fas fa-box-open" style="font-size: 2rem;"></i>
                            <div class="mt-2">No storage items listed yet.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

<div class="storage-print-layout">
    <div class="header-container">
        <div style="position: absolute; left: 0; top: 0; display: flex; align-items: center;">
            <img src="{{ asset('images/Layer-0-1.png') }}" alt="Logo 1" style="height: 60px; margin-right: 10px;">
            <img src="{{ asset('images/What-Is-the-Difference-Between-DepEd-Seal-and-DepEd-Logo.png') }}" alt="Logo 2" style="height: 60px; margin-right: 10px;">
            <img src="{{ asset('images/drrmis-logo-2.png') }}" alt="Logo 3" style="height: 60px; margin-right: 15px;">
            <div style="text-align: left;">
                <h2 style="margin: 0; font-size: 16px; font-weight: bold; text-transform: uppercase;">DepEd DRRM</h2>
            </div>
        </div>

        <div style="width: 100%; text-align: center; padding-left: 430px; padding-right: 30px; box-sizing: border-box;">
            <h1 style="margin: 0; font-size: 14px; font-weight: normal; text-transform: uppercase;">Storage Inventory Report</h1>
        </div>
    </div>

    <div class="info-grid">
        <div class="info-row">
            <div><strong>Name of School:</strong> {{ $school->name }}</div>
            <div><strong>Name of School Head:</strong> {{ $school->school_head ?: 'N/A' }}</div>
        </div>
        <div class="info-row">
            <div><strong>School ID:</strong> {{ $school->school_id ?: ($school->school_id_number ?: 'N/A') }}</div>
            <div><strong>DRRM Coordinator:</strong> {{ $school->school_drrm_coordinator ?: 'N/A' }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 20%;">Item / Equipment / Tool</th>
                <th style="width: 16%;">Type</th>
                <th style="width: 18%;">From</th>
                <th style="width: 10%; text-align: center;">Available</th>
                <th style="width: 10%; text-align: center;">Functional</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @forelse($storageItems as $item)
                <tr>
                    <td>{{ $item->item_name }}</td>
                    <td>{{ $item->item_type ?: 'N/A' }}</td>
                    <td>{{ $item->source_from ?: 'N/A' }}</td>
                    <td style="text-align: center;">{{ $item->is_available ? 'Yes' : 'No' }}</td>
                    <td style="text-align: center;">{{ $item->is_functional ? 'Yes' : 'No' }}</td>
                    <td>{{ $item->remarks ?: '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 20px;">No storage items listed yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if(auth()->user()->role !== 'viewer')
<div class="modal fade" id="addStorageItemModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Add Storage Item</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('comprehensive-school-safety.school.storage.store', $school->id) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Item Name *</label>
                        <input type="text" class="form-control" name="item_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Type</label>
                        <input type="text" class="form-control" name="item_type" placeholder="Item, Equipment, Tool, etc.">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">From</label>
                        <input type="text" class="form-control" name="source_from" placeholder="e.g. LGU, Donation, School MOOE">
                    </div>
                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_available" name="is_available" value="1" checked>
                                <label class="form-check-label" for="is_available">Available</label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_functional" name="is_functional" value="1" checked>
                                <label class="form-check-label" for="is_functional">Functional</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold">Remarks</label>
                        <textarea class="form-control" name="remarks" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Item</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
