<div class="section-body">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Search Student</h4>
                </div>
                <div class="card-body">
                    <div class="form-group col-12 col-md-6 m-auto">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search by reg. number or names"
                                id="mi_input">
                            <div class="input-group-append">
                                <div class="input-group-text" id="mi_spinner">
                                    <i class="fas fa-search"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-hover table-sm">
                        <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="mi_contents"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Academic Years Section -->
<div class="section-body" id="mi_info" hidden>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Student Academic History</h4>
                    <div class="card-header-action">
                        <a data-collapse="#mi_acad_collapse" class="btn btn-icon btn-info" href="#"><i
                                class="fas fa-minus"></i></a>
                    </div>
                </div>
                <div class="card-body collapse show" id="mi_acad_collapse">
                    <table class="table table-hover table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Specialization</th>
                                <th>Level</th>
                                <th>Academic Year</th>
                                <th>Invoice</th>
                                <th>Payment</th>
                                <th>Special</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="mi_academics"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Invoice Detail Section -->
<div class="section-body" id="mi_invoice_detail" hidden>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Invoice Records</h4>
                    <div class="card-header-action">
                        <button class="btn btn-danger" id="mi_btn_pdf"><i class="fas fa-file-pdf"></i> Print
                            PDF</button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="mi_student_label" class="mb-2 font-weight-bold"></div>
                    <table class="table table-hover table-sm">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Fee Name</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Month</th>
                                <th>Comment</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="mi_invoice_rows"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>