<?php

/**
 * customer/order-new.php — New Fabrication Order (Customer)
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
requireCustomer();

$currentUser = currentUser();
$pageTitle   = 'New Order';
$breadcrumb  = [['label' => 'Orders', 'url' => 'orders.php'], ['label' => 'New Order']];

include __DIR__ . '/includes/head.php';
?>

<body>
    <?php include __DIR__ . '/includes/sidebar.php'; ?>
    <div class="main-wrapper">
        <?php include __DIR__ . '/includes/header.php'; ?>

        <main class="page-content fade-up">

            <!-- Page header -->
            <div class="page-header">
                <div class="page-header-left">
                    <h1 class="page-title">New Fabrication Order</h1>
                    <p class="page-subtitle">Fill out the form and we'll process your order right away</p>
                </div>
                <div class="page-actions">
                    <a href="orders.php" class="btn btn-default">
                        <i class='bx bx-arrow-back'></i> My Orders
                    </a>
                </div>
            </div>

            <form method="POST" id="orderForm" action="../auth/AddFabricationOrders.php"
                enctype="multipart/form-data" novalidate>

                <div class="row g-4">

                    <!-- ── LEFT COLUMN ───────────────────────────── -->
                    <div class="col-lg-8">

                        <!-- Customer Information -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="card-title">
                                    <i class='bx bx-user text-primary'></i> Customer Information
                                </h6>
                            </div>
                            <div class="card-section">
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                                        <input type="text" name="customer_name" class="form-control"
                                            placeholder="Full name" maxlength="100" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label">Customer Phone</label>
                                        <input type="tel" name="phone" class="form-control"
                                            placeholder="(123) 456-7890">
                                    </div>
                                    <div class="col-sm-8">
                                        <label class="form-label">Street Address <span class="text-danger">*</span></label>
                                        <input type="text" name="address" class="form-control"
                                            placeholder="Full street address" required>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="form-label">City <span class="text-danger">*</span></label>
                                        <input type="text" name="city" class="form-control" placeholder="City" required>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="form-label">ZIP Code <span class="text-danger">*</span></label>
                                        <input type="text" name="ZipCode" class="form-control" placeholder="e.g. 78201" required>
                                    </div>
                                    <div class="col-sm-4">
                                        <label class="form-label">PO Number</label>
                                        <input type="text" name="po_number" class="form-control" placeholder="Optional">
                                    </div>
                                    <div class="col-sm-4"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Sales Rep -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="card-title">
                                    <i class='bx bx-briefcase text-primary'></i> Sales Representative
                                </h6>
                            </div>
                            <div class="card-section">
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <label class="form-label">Sales Rep Name <span class="text-danger">*</span></label>
                                        <input type="text" name="sales_rep" class="form-control"
                                            placeholder="Representative name" required>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="form-label">Sales Rep Phone <span class="text-danger">*</span></label>
                                        <input type="text" name="sales_rep_phone" class="form-control"
                                            placeholder="Phone number" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Job Area Selection -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="card-title">
                                    <i class='bx bx-layer text-primary'></i> Select Job Areas
                                </h6>
                                <span style="font-size:12px;color:var(--color-text-sub);">Choose one or more</span>
                            </div>
                            <div class="card-section">
                                <div class="row g-3">
                                    <div class="col-6 col-md-3">
                                        <div class="job-type-card" id="card-kitchen" onclick="toggleJobCard('kitchen')">
                                            <input type="checkbox" id="jobKitchen" name="job_types[]"
                                                value="kitchen" class="visually-hidden" onchange="toggleJobSections()">
                                            <div class="job-card-icon"><i class='bx bx-restaurant'></i></div>
                                            <div class="job-card-label">Kitchen</div>
                                            <div class="job-card-desc">Countertops, islands</div>
                                            <div class="job-card-check"><i class='bx bx-check'></i></div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="job-type-card" id="card-bathroom" onclick="toggleJobCard('bathroom')">
                                            <input type="checkbox" id="jobBathroom" name="job_types[]"
                                                value="bathroom" class="visually-hidden" onchange="toggleJobSections()">
                                            <div class="job-card-icon"><i class='bx bx-bath'></i></div>
                                            <div class="job-card-label">Bathroom</div>
                                            <div class="job-card-desc">Vanities, shower walls</div>
                                            <div class="job-card-check"><i class='bx bx-check'></i></div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="job-type-card" id="card-MasterBathroom" onclick="toggleJobCard('MasterBathroom')">
                                            <input type="checkbox" id="jobMasterBathroom" name="job_types[]"
                                                value="MasterBathroom" class="visually-hidden" onchange="toggleJobSections()">
                                            <div class="job-card-icon"><i class='bx bxs-bath'></i></div>
                                            <div class="job-card-label">Master Bath</div>
                                            <div class="job-card-desc">Tub decks, large vanities</div>
                                            <div class="job-card-check"><i class='bx bx-check'></i></div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="job-type-card" id="card-other" onclick="toggleJobCard('other')">
                                            <input type="checkbox" id="jobOther" name="job_types[]"
                                                value="other" class="visually-hidden" onchange="toggleJobSections()">
                                            <div class="job-card-icon"><i class='bx bx-plus-circle'></i></div>
                                            <div class="job-card-label">Other</div>
                                            <div class="job-card-desc">Custom, commercial</div>
                                            <div class="job-card-check"><i class='bx bx-check'></i></div>
                                        </div>
                                    </div>
                                </div>
                                <div id="jobSelectionAlert" class="mt-3 d-none"
                                    style="padding:10px 14px;background:var(--color-primary-l,#e6f7f5);
                                    border-radius:var(--radius-sm);border-left:3px solid var(--color-primary);
                                    font-size:13px;color:var(--color-primary);">
                                    <i class='bx bx-info-circle me-1'></i>
                                    <span id="jobAlertText"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Dynamic sections injected here -->
                        <div id="jobSectionsContainer"></div>

                        <!-- Notes & Attachment -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="card-title">
                                    <i class='bx bx-note text-primary'></i> Notes &amp; Attachment
                                </h6>
                            </div>
                            <div class="card-section">
                                <div class="mb-3">
                                    <label class="form-label">Special Instructions</label>
                                    <textarea name="notes" rows="3" class="form-control"
                                        placeholder="Any special requirements or notes…"
                                        maxlength="500"></textarea>
                                    <div style="font-size:11px;color:var(--color-text-sub);text-align:right;margin-top:4px;">
                                        <span id="notesCounter">0</span>/500
                                    </div>
                                </div>
                                <label class="form-label">Attachment <span style="font-size:11px;color:var(--color-text-sub);">(optional)</span></label>
                                <div class="image-upload-zone" id="fileUploadZone" style="cursor:pointer;position:relative;">
                                    <div id="filePlaceholder">
                                        <i class='bx bx-cloud-upload' style="font-size:36px;color:var(--color-text-sub);"></i>
                                        <p style="margin:8px 0 4px;font-weight:500;">Click to upload image or document</p>
                                        <p style="font-size:12px;color:var(--color-text-sub);">
                                            JPG, PNG, WebP, PDF, Word, Excel — max 20 MB
                                        </p>
                                    </div>
                                    <div id="filePreview" style="display:none;"></div>
                                    <input type="file" name="image" id="fileInput"
                                        accept="image/*,.pdf,.doc,.docx,.xls,.xlsx"
                                        style="position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%;">
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- ── RIGHT COLUMN ──────────────────────────── -->
                    <div class="col-lg-4">
                        <div class="card" style="position:sticky;top:calc(var(--header-h) + 16px);">
                            <div class="card-header">
                                <h6 class="card-title"><i class='bx bx-check-shield text-primary'></i> Submit Order</h6>
                            </div>
                            <div class="card-section">
                                <p style="font-size:13px;color:var(--color-text-sub);margin-bottom:16px;">
                                    Review all sections before submitting. Required fields are marked
                                    <span class="text-danger">*</span>.
                                </p>
                                <div id="summaryChips" style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:16px;"></div>
                                <button type="submit" class="btn btn-primary w-100" id="submitBtn">
                                    <span id="submitSpinner" class="spinner-border spinner-border-sm me-1 d-none"></span>
                                    <i class='bx bx-send' id="submitIcon"></i>
                                    Submit Order
                                </button>
                                <a href="orders.php" class="btn btn-default w-100 mt-2">Cancel</a>
                            </div>
                        </div>
                    </div>

                </div>
            </form>

            <!-- JOB SECTION TEMPLATE -->
            <template id="jobSectionTemplate">
                <div class="card mb-3 job-section" data-job-type="">
                    <div class="card-header">
                        <h6 class="card-title">
                            <i class='bx bx-layer text-primary'></i>
                            <span class="job-type-label"></span> Details
                        </h6>
                    </div>
                    <div class="card-section">
                        <div class="job-type-other-field mb-4" style="display:none;">
                            <label class="form-label">Specify Job Type <span class="text-danger">*</span></label>
                            <input type="text" name="job_type_other[]" class="form-control job-type-other-input"
                                placeholder="e.g. Laundry Room, Bar, Fireplace">
                        </div>
                        <p class="form-section-label">Material Information</p>
                        <div class="row g-3 mb-4">
                            <div class="col-sm-4">
                                <label class="form-label">Material Type <span class="text-danger">*</span></label>
                                <select name="material_type[]" class="form-select material-select" required>
                                    <option value="">Select Material</option>
                                    <option value="granite">Granite</option>
                                    <option value="marble">Marble</option>
                                    <option value="quartz">Quartz</option>
                                    <option value="other">Other (specify)</option>
                                </select>
                                <div class="other-material-input mt-2" style="display:none;">
                                    <input type="text" name="material_other[]" class="form-control"
                                        placeholder="Custom material type">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label class="form-label">Thickness <span class="text-danger">*</span></label>
                                <select name="material_thickness[]" class="form-select thickness-select" required>
                                    <option value="">Select Thickness</option>
                                    <option value="20">2 Cm</option>
                                    <option value="30">3 Cm</option>
                                    <option value="custom">Custom (specify)</option>
                                </select>
                                <div class="custom-thickness-input mt-2" style="display:none;">
                                    <div class="input-group">
                                        <input type="text" name="material_thickness_custom[]" class="form-control"
                                            placeholder="Enter cm">
                                        <span class="input-group-text">Cm</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label class="form-label">Manufacture / Color <span class="text-danger">*</span></label>
                                <input type="text" name="material_color[]" class="form-control" required
                                    placeholder="e.g. Bianco Carrara">
                            </div>
                        </div>
                        <p class="form-section-label">Sink Information</p>
                        <div class="row g-3 mb-4">
                            <div class="col-sm-4">
                                <label class="form-label">Sink Provider <span class="text-danger">*</span></label>
                                <select name="sink_provider[]" class="form-select sink-provider-select" required>
                                    <option value="">Select Provider</option>
                                    <option value="customer">Customer Sink</option>
                                    <option value="aa_granite">AA Granite Sink</option>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label class="form-label">Sink Type <span class="text-danger">*</span></label>
                                <select name="sink_type[]" class="form-select sink-type-select" required>
                                    <option value="">Select Sink Type</option>
                                    <option value="undermount">Under Mount</option>
                                    <option value="dropin">Drop-in</option>
                                    <option value="farmhouse">Farmhouse / Apron</option>
                                    <option value="vessel">Vessel</option>
                                </select>
                            </div>
                            <div class="col-sm-4 sink-style-field" style="display:none;">
                                <label class="form-label">Sink Style <span class="text-danger">*</span></label>
                                <select name="sink_style[]" class="form-select sink-style-select">
                                    <option value="">Select Style</option>
                                    <option value="standard_single_bowl">Standard Single Bowl</option>
                                    <option value="standard_single_50_50">Standard Single 50/50</option>
                                    <option value="standard_single_60_40">Standard Single 60/40</option>
                                    <option value="zero_radius_single_bowl">Zero Radius Single Bowl</option>
                                    <option value="zero_radius_single_50_50">Zero Radius 50/50</option>
                                    <option value="zero_radius_single_60_40">Zero Radius 60/40</option>
                                    <option value="bathroom_rectangle_white">Bathroom Rectangle White</option>
                                    <option value="bathroom_rectangle_bisque">Bathroom Rectangle Bisque</option>
                                    <option value="bathroom_oval_white">Bathroom Oval White</option>
                                    <option value="bathroom_oval_bisque">Bathroom Oval Bisque</option>
                                    <option value="other">Other (specify)</option>
                                </select>
                                <div class="other-sink-style-input mt-2" style="display:none;">
                                    <input type="text" name="sink_style_other[]" class="form-control"
                                        placeholder="Custom sink style">
                                </div>
                            </div>
                        </div>
                        <p class="form-section-label">Additional Details</p>
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="form-label">Edge Profile</label>
                                <select name="edge_profile[]" class="form-select edge-select">
                                    <option value="">Select Edge</option>
                                    <option value="eased">Flat "Ease"</option>
                                    <option value="bullnose">Demi</option>
                                    <option value="bevel">3/8 Bevel</option>
                                    <option value="radius">3/8 Radius</option>
                                    <option value="custom">Custom (specify)</option>
                                </select>
                                <div class="custom-edge-input mt-2" style="display:none;">
                                    <input type="text" name="edge_profile_custom[]" class="form-control"
                                        placeholder="Describe edge profile">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label">Tear Out <span class="text-danger">*</span></label>
                                <select name="tear_out[]" class="form-select" required>
                                    <option value="">Select</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

        </main>

        <?php include __DIR__ . '/includes/footer.php'; ?>

        <style>
            .job-type-card {
                position: relative;
                border: 2px solid var(--color-border);
                border-radius: var(--radius-md);
                padding: 16px 12px;
                text-align: center;
                cursor: pointer;
                transition: border-color .18s, background .18s, box-shadow .18s;
                user-select: none;
                background: var(--color-surface);
            }

            .job-type-card:hover {
                border-color: var(--color-primary);
                background: #e6f7f5;
            }

            .job-type-card.active {
                border-color: var(--color-primary);
                background: #d1f0ec;
                box-shadow: 0 0 0 3px rgba(15, 118, 110, .12);
            }

            .job-card-icon {
                font-size: 28px;
                color: var(--color-primary);
                margin-bottom: 6px;
                line-height: 1;
            }

            .job-type-card:not(.active) .job-card-icon {
                color: var(--color-text-sub);
            }

            .job-card-label {
                font-weight: 600;
                font-size: 13px;
                color: var(--color-text);
            }

            .job-card-desc {
                font-size: 11px;
                color: var(--color-text-sub);
                margin-top: 2px;
            }

            .job-card-check {
                position: absolute;
                top: 8px;
                right: 8px;
                width: 18px;
                height: 18px;
                border-radius: 50%;
                background: var(--color-primary);
                color: #fff;
                font-size: 12px;
                display: none;
                align-items: center;
                justify-content: center;
            }

            .job-type-card.active .job-card-check {
                display: flex;
            }

            .form-section-label {
                font-size: 11px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: .06em;
                color: var(--color-text-sub);
                margin: 0 0 10px;
                padding-bottom: 6px;
                border-bottom: 1px solid var(--color-border);
            }

            .summary-chip {
                display: inline-flex;
                align-items: center;
                gap: 4px;
                padding: 4px 10px;
                border-radius: 20px;
                background: var(--color-primary-l, #d1f0ec);
                color: var(--color-primary);
                font-size: 12px;
                font-weight: 500;
            }
        </style>

        <script>
            let activeJobs = new Set();

            const JOB_META = [{
                    id: 'jobKitchen',
                    key: 'kitchen',
                    label: 'Kitchen'
                },
                {
                    id: 'jobBathroom',
                    key: 'bathroom',
                    label: 'Bathroom'
                },
                {
                    id: 'jobMasterBathroom',
                    key: 'MasterBathroom',
                    label: 'Master Bath'
                },
                {
                    id: 'jobOther',
                    key: 'other',
                    label: 'Other'
                },
            ];

            function toggleJobCard(jobKey) {
                const cbId = 'job' + jobKey.charAt(0).toUpperCase() + jobKey.slice(1);
                const cb = document.getElementById(cbId);
                const card = document.getElementById('card-' + jobKey);
                if (!cb) return;
                cb.checked = !cb.checked;
                card?.classList.toggle('active', cb.checked);
                toggleJobSections();
                updateSummaryChips();
            }

            function toggleJobSections() {
                const container = document.getElementById('jobSectionsContainer');
                const template = document.getElementById('jobSectionTemplate');
                const alertEl = document.getElementById('jobSelectionAlert');
                const alertText = document.getElementById('jobAlertText');

                const selected = JOB_META.filter(j => document.getElementById(j.id)?.checked);

                if (selected.length) {
                    alertEl.classList.remove('d-none');
                    alertText.textContent = 'Selected: ' + selected.map(j => j.label).join(', ');
                } else {
                    alertEl.classList.add('d-none');
                }

                container.querySelectorAll('.job-section').forEach(sec => {
                    const k = sec.getAttribute('data-job-type');
                    if (!selected.some(j => j.key === k)) {
                        sec.remove();
                        activeJobs.delete(k);
                    }
                });

                selected.forEach(j => {
                    if (!activeJobs.has(j.key)) {
                        const frag = template.content.cloneNode(true);
                        const div = frag.querySelector('.job-section');
                        div.setAttribute('data-job-type', j.key);
                        div.querySelector('.job-type-label').textContent = j.label;
                        if (j.key === 'other') {
                            const f = div.querySelector('.job-type-other-field');
                            const i = div.querySelector('.job-type-other-input');
                            if (f) f.style.display = 'block';
                            if (i) i.required = true;
                        }
                        container.appendChild(div);
                        activeJobs.add(j.key);
                        initSectionHandlers(div);
                    }
                });

                updateSummaryChips();
            }

            function initSectionHandlers(sec) {
                bindToggle(sec.querySelector('select[name="material_type[]"]'),
                    sec.querySelector('input[name="material_other[]"]'),
                    sec.querySelector('.other-material-input'));
                bindToggle(sec.querySelector('select[name="material_thickness[]"]'),
                    sec.querySelector('input[name="material_thickness_custom[]"]'),
                    sec.querySelector('.custom-thickness-input'));
                bindToggle(sec.querySelector('select[name="edge_profile[]"]'),
                    sec.querySelector('input[name="edge_profile_custom[]"]'),
                    sec.querySelector('.custom-edge-input'));
                bindToggle(sec.querySelector('select[name="sink_style[]"]'),
                    sec.querySelector('input[name="sink_style_other[]"]'),
                    sec.querySelector('.other-sink-style-input'));

                const provSel = sec.querySelector('select[name="sink_provider[]"]');
                if (provSel) {
                    provSel.addEventListener('change', () => {
                        const col = sec.querySelector('.sink-style-field');
                        const sel = sec.querySelector('select[name="sink_style[]"]');
                        const isAA = provSel.value === 'aa_granite';
                        if (col) col.style.display = isAA ? 'block' : 'none';
                        if (sel) {
                            sel.required = isAA;
                            if (!isAA) {
                                sel.value = '';
                                const oi = sec.querySelector('input[name="sink_style_other[]"]');
                                const ob = sec.querySelector('.other-sink-style-input');
                                if (oi) {
                                    oi.value = '';
                                    oi.required = false;
                                }
                                if (ob) ob.style.display = 'none';
                            }
                        }
                    });
                }
            }

            function bindToggle(sel, inp, box) {
                if (!sel || !inp || !box) return;
                sel.addEventListener('change', () => {
                    const isSpec = ['other', 'custom'].includes(sel.value);
                    box.style.display = isSpec ? 'block' : 'none';
                    inp.required = isSpec;
                    if (!isSpec) inp.value = '';
                    if (isSpec) inp.focus();
                });
            }

            function updateSummaryChips() {
                const chips = document.getElementById('summaryChips');
                if (!chips) return;
                const sel = JOB_META.filter(j => document.getElementById(j.id)?.checked);
                chips.innerHTML = sel.map(j =>
                    `<span class="summary-chip"><i class='bx bx-layer'></i>${j.label}</span>`
                ).join('');
            }

            document.getElementById('fileInput').addEventListener('change', function() {
                const file = this.files[0];
                if (!file) return;
                if (file.size > 20 * 1024 * 1024) {
                    showToast('File must be under 20 MB.', 'error');
                    this.value = '';
                    return;
                }
                const allowed = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp',
                    'application/pdf', 'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'application/vnd.ms-excel',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                ];
                if (!allowed.includes(file.type)) {
                    showToast('Invalid file type.', 'error');
                    this.value = '';
                    return;
                }

                document.getElementById('filePlaceholder').style.display = 'none';
                const preview = document.getElementById('filePreview');
                preview.style.display = 'block';

                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = e => {
                        preview.innerHTML = `<img src="${e.target.result}"
                style="max-height:200px;max-width:100%;border-radius:var(--radius-sm);object-fit:contain;">`;
                    };
                    reader.readAsDataURL(file);
                } else {
                    const ext = file.name.split('.').pop().toLowerCase();
                    const icons = {
                        pdf: 'bx-file-pdf',
                        doc: 'bx-file-doc',
                        docx: 'bx-file-doc',
                        xls: 'bxs-spreadsheet',
                        xlsx: 'bxs-spreadsheet'
                    };
                    preview.innerHTML = `
            <div style="display:flex;align-items:center;gap:12px;padding:12px;
                        background:var(--color-bg);border-radius:var(--radius-sm);">
                <i class='bx ${icons[ext]||"bx-file"}' style="font-size:32px;color:var(--color-primary);"></i>
                <div>
                    <div style="font-weight:600;font-size:13px;">${file.name}</div>
                    <div style="font-size:11px;color:var(--color-text-sub);">
                        ${ext.toUpperCase()} · ${(file.size/1024/1024).toFixed(2)} MB
                    </div>
                </div>
            </div>`;
                }
            });

            function validateForm() {
                const errors = [];
                [
                    ['input[name="customer_name"]', 'Customer name is required'],
                    ['input[name="address"]', 'Address is required'],
                    ['input[name="sales_rep"]', 'Sales rep is required'],
                    ['input[name="sales_rep_phone"]', 'Sales rep phone is required'],
                    ['input[name="city"]', 'City is required'],
                    ['input[name="ZipCode"]', 'ZIP code is required'],
                ].forEach(([s, m]) => {
                    const el = document.querySelector(s);
                    if (!el || !el.value.trim()) {
                        errors.push(m);
                        markInvalid(el);
                    }
                });
                /* File — optional, no validation needed */
                if (activeJobs.size === 0) {
                    errors.push('Select at least one job area.');
                } else {
                    document.querySelectorAll('.job-section').forEach(sec => {
                        const label = sec.querySelector('.job-type-label')?.textContent || 'Job';
                        if (sec.getAttribute('data-job-type') === 'other') {
                            const inp = sec.querySelector('input[name="job_type_other[]"]');
                            if (inp && !inp.value.trim()) {
                                errors.push(`Specify custom job type in "${label}" section`);
                                markInvalid(inp);
                            }
                        }
                        sec.querySelectorAll('[required]').forEach(inp => {
                            const hidden = inp.closest('[style*="display: none"]') || inp.closest('[style*="display:none"]');
                            if (!hidden && !inp.value.trim()) {
                                const lbl = inp.closest('.col-sm-4,.col-sm-6')?.querySelector('label')?.textContent?.trim() || 'Field';
                                errors.push(`"${lbl}" is required in ${label} section`);
                                markInvalid(inp);
                            }
                        });
                    });
                }
                return errors;
            }

            function markInvalid(el) {
                if (!el) return;
                el.classList.add('is-invalid');
                el.addEventListener('input', () => el.classList.remove('is-invalid'), {
                    once: true
                });
            }

            document.getElementById('orderForm').addEventListener('submit', async function(e) {
                e.preventDefault();
                const errors = validateForm();
                if (errors.length) {
                    errors.forEach(m => showToast(m, 'error'));
                    return;
                }

                const btn = document.getElementById('submitBtn');
                const spinner = document.getElementById('submitSpinner');
                const icon = document.getElementById('submitIcon');
                btn.disabled = true;
                spinner.classList.remove('d-none');
                icon.classList.add('d-none');

                const fd = new FormData(this);
                fd.delete('job_types[]');
                fd.delete('job_type_other[]');
                document.querySelectorAll('.job-section').forEach(sec => {
                    fd.append('job_types[]', sec.getAttribute('data-job-type'));
                    const oi = sec.querySelector('input[name="job_type_other[]"]');
                    fd.append('job_type_other[]', oi ? oi.value.trim() : '');
                });

                try {
                    const res = await fetch(this.action, {
                        method: 'POST',
                        body: fd
                    });
                    const data = await res.json();
                    if (data.success) {
                        showToast(data.message || 'Order submitted!', 'success');
                        setTimeout(() => window.location.href = 'orders.php', 1400);
                    } else {
                        showToast(data.message || 'Could not submit order.', 'error');
                        btn.disabled = false;
                        spinner.classList.add('d-none');
                        icon.classList.remove('d-none');
                    }
                } catch {
                    showToast('Network error. Please try again.', 'error');
                    btn.disabled = false;
                    spinner.classList.add('d-none');
                    icon.classList.remove('d-none');
                }
            });

            document.addEventListener('DOMContentLoaded', () => {
                const notes = document.querySelector('textarea[name="notes"]');
                const counter = document.getElementById('notesCounter');
                if (notes && counter) {
                    notes.addEventListener('input', () => {
                        if (notes.value.length > 500) notes.value = notes.value.slice(0, 500);
                        counter.textContent = notes.value.length;
                    });
                }
                document.querySelectorAll('#orderForm input:not([type="submit"])').forEach(inp => {
                    inp.addEventListener('keydown', e => {
                        if (e.key === 'Enter') e.preventDefault();
                    });
                });
            });
        </script>