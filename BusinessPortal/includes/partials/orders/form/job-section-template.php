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
                    <select name="thickness[]" class="form-select thickness-select" required>
                        <option value="">Select Thickness</option>
                        <option value="2cm">2 Cm</option>
                        <option value="3cm">3 Cm</option>
                        <option value="custom">Custom (specify)</option>
                    </select>
                    <div class="custom-thickness-input mt-2" style="display:none;">
                        <div class="input-group">
                            <input type="text" name="thickness_custom[]" class="form-control"
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
                        <option value="ts_granite">Ts Granite Sinke</option>
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
