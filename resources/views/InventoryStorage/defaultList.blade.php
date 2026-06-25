<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>School Safety & Emergency Inventory</title>

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<style>
:root{
    --drrm-teal:#0D7377;
    --drrm-teal-hover:#0a5a5d;
    --drrm-teal-light:#e6f1f1;
}

body{
    background:#f4f7f6;
    font-family:'Segoe UI',Tahoma,Geneva,Verdana,sans-serif;
}

/* Sidebar */
.sidebar{
    min-height:100vh;
    background:var(--drrm-teal);
    color:white;
    box-shadow:2px 0 5px rgba(0,0,0,.1);
}

.sidebar-heading{
    font-size:1.1rem;
    font-weight:bold;
    text-transform:uppercase;
    padding:1.5rem;
    border-bottom:1px solid rgba(255,255,255,.1);
}

.sidebar .nav-link{
    color:rgba(255,255,255,.75);
    padding:.9rem 1.5rem;
    transition:.3s;
}

.sidebar .nav-link:hover,
.sidebar .nav-link.active{
    color:white;
    background:var(--drrm-teal-hover);
    border-left:4px solid white;
}

/* Cards */
.card{
    border:none;
    border-radius:12px;
    box-shadow:0 .125rem .25rem rgba(0,0,0,.075);
}

.stat-card{
    border-left:4px solid var(--drrm-teal);
}

.text-teal{
    color:var(--drrm-teal);
}

.btn-teal{
    background:var(--drrm-teal);
    color:white;
}

.btn-teal:hover{
    background:var(--drrm-teal-hover);
    color:white;
}

.table thead{
    background:var(--drrm-teal-light);
}

.form-control:focus{
    border-color:var(--drrm-teal);
    box-shadow:0 0 0 .2rem rgba(13,115,119,.15);
}

.section-badge{
    background:var(--drrm-teal);
    color:white;
    padding:.4rem .75rem;
    border-radius:6px;
    font-weight:600;
}

.inventory-icon{
    color:var(--drrm-teal);
}
</style>
</head>

<body>

<div class="container-fluid">
<div class="row">
<!-- Sidebar -->
<nav class="col-md-3 col-lg-2 d-md-block sidebar collapse px-0">

    <div class="sidebar-heading text-center">
        <i class="fa-solid fa-boxes-stacked me-2"></i>
        Inventory Manager
    </div>

    <div class="position-sticky pt-3">

        <ul class="nav flex-column">

            <li class="nav-item">
                <a class="nav-link" href="{{ route('inventory-storage.dashboard') }}">
                    <i class="fa-solid fa-gauge-high me-2"></i>
                    Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fa-solid fa-truck-ramp-box me-2"></i>
                    Distributions
                </a>
            </li>

            <!-- ACTIVE PAGE -->
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('inventory-storage.default-list') }}">
                    <i class="fa-solid fa-list me-2"></i>
                    Default List
                </a>
            </li>

        </ul>

        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-white-50"
            style="font-size:0.75rem; border:none;">
            <span>Saved Reports</span>
        </h6>

        <ul class="nav flex-column mb-2">

            <li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="fa-solid fa-file-lines me-2"></i>
                    Monthly Summary
                </a>
            </li>

        </ul>

    </div>

        </div>

    </nav>

    <!-- Main Content -->
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center pt-4 pb-3 mb-4 border-bottom">

            <div>
                <h2 class="fw-bold text-teal">
                    Safety, Emergency, Response & Rescue Inventory
                </h2>
                <p class="text-muted mb-0">
                    Supplies and equipment provided by DepEd and Partners
                </p>
            </div>

            <button class="btn btn-teal">
                <i class="fa-solid fa-plus me-2"></i>
                Add Item
            </button>

        </div>

        <!-- Statistics -->
        <div class="row g-3 mb-4">

            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <small class="text-muted">Total Equipment</small>
                        <h3 class="fw-bold mb-0">26</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <small class="text-muted">DepEd Quantity</small>
                        <h3 class="fw-bold mb-0">150</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <small class="text-muted">Partner Quantity</small>
                        <h3 class="fw-bold mb-0">85</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <small class="text-muted">Total Inventory</small>
                        <h3 class="fw-bold mb-0">235</h3>
                    </div>
                </div>
            </div>

        </div>

        <!-- Inventory Table -->
        <div class="card">

            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">

                <div>
                    <span class="section-badge me-2">A</span>
                    <span class="fw-semibold text-teal">
                        Emergency Supplies and Equipment
                    </span>
                </div>

                <input
                    type="text"
                    class="form-control w-auto"
                    placeholder="Search Item..."
                >

            </div>

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead>
                    <tr>
                        <th>Item Description</th>
                        <th class="text-center">DepEd Qty</th>
                        <th class="text-center">Partners Qty</th>
                        <th class="text-center">Total</th>
                    </tr>
                    </thead>

                    <tbody>

                    <tr>
                        <td>
                            <i class="fa-solid fa-bed-pulse inventory-icon me-2"></i>
                            2-Fold Aluminum Stretcher
                        </td>
                        <td>
                            <input type="number" class="form-control text-center deped" value="0">
                        </td>
                        <td>
                            <input type="number" class="form-control text-center partner" value="0">
                        </td>
                        <td class="text-center fw-bold total">0</td>
                    </tr>

                    <tr>
                        <td>
                            <i class="fa-solid fa-bag-shopping inventory-icon me-2"></i>
                            Cadaver Bag
                        </td>
                        <td><input type="number" class="form-control text-center deped" value="0"></td>
                        <td><input type="number" class="form-control text-center partner" value="0"></td>
                        <td class="text-center fw-bold total">0</td>
                    </tr>

                    <tr>
                        <td>
                            <i class="fa-solid fa-user-doctor inventory-icon me-2"></i>
                            C-Collar
                        </td>
                        <td><input type="number" class="form-control text-center deped" value="0"></td>
                        <td><input type="number" class="form-control text-center partner" value="0"></td>
                        <td class="text-center fw-bold total">0</td>
                    </tr>

                    <tr>
                        <td>
                            <i class="fa-solid fa-bed inventory-icon me-2"></i>
                            Cot (Battlefield Bed)
                        </td>
                        <td><input type="number" class="form-control text-center deped" value="0"></td>
                        <td><input type="number" class="form-control text-center partner" value="0"></td>
                        <td class="text-center fw-bold total">0</td>
                    </tr>

                    <tr>
                        <td>
                            <i class="fa-solid fa-kit-medical inventory-icon me-2"></i>
                            CPR Board
                        </td>
                        <td><input type="number" class="form-control text-center deped" value="0"></td>
                        <td><input type="number" class="form-control text-center partner" value="0"></td>
                        <td class="text-center fw-bold total">0</td>
                    </tr>

                    <tr>
                        <td>
                            <i class="fa-solid fa-lightbulb inventory-icon me-2"></i>
                            Emergency Head Lamp
                        </td>
                        <td><input type="number" class="form-control text-center deped" value="0"></td>
                        <td><input type="number" class="form-control text-center partner" value="0"></td>
                        <td class="text-center fw-bold total">0</td>
                    </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </main>

</div>
</div>

<script>
document.querySelectorAll('tr').forEach(row => {

    const deped = row.querySelector('.deped');
    const partner = row.querySelector('.partner');
    const total = row.querySelector('.total');

    if(deped && partner && total){

        function updateTotal(){
            total.textContent =
                (parseInt(deped.value) || 0) +
                (parseInt(partner.value) || 0);
        }

        deped.addEventListener('input', updateTotal);
        partner.addEventListener('input', updateTotal);
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
