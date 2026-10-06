<!-- Start app main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Suivi des factures</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="edu?mis=on">Tableau de bord</a></div>
                <div class="breadcrumb-item">Facturation</div>
                <div class="breadcrumb-item">Suivi des factures</div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    <form id="tracking_form" class="row align-items-end" autocomplete="off">
                        <div class="form-group col-12 col-md-4">
                            <label>Matricule de l'étudiant</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="reg_no" list="student_list" placeholder="matricule ou nom" required>
                                <datalist id="student_list"></datalist>
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary" title="Rechercher"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-6 col-md-3">
                            <label>Niveau</label>
                            <select class="form-control" id="filter_level">
                                <option value="">Tous</option>
                            </select>
                        </div>
                        <div class="form-group col-6 col-md-3">
                            <label>Catégorie de frais</label>
                            <select class="form-control" id="filter_fee">
                                <option value="">Toutes</option>
                                <?php
                                    // Every active fee name the charges can carry: the current categories,
                                    // the older category tables still used by tbl_invoice, and special invoices.
                                    $fees = $conn->prepare("SELECT name FROM tbl_fee_categories WHERE status = 1
                                                            UNION SELECT name FROM tbl_fee_category WHERE status = 1
                                                            UNION SELECT name FROM fee_category WHERE status = 1
                                                            UNION SELECT name FROM tbl_special_invoice WHERE status = 1
                                                            ORDER BY name");
                                    $fees->execute();
                                    while($fee = $fees->fetch()):
                                        if(trim((string)$fee['name']) === '') continue;
                                ?>
                                <option value="<?php echo htmlspecialchars($fee['name']); ?>"><?php echo htmlspecialchars($fee['name']); ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group col-12 col-md-2">
                            <button type="submit" class="btn btn-primary btn-block" id="load_btn"><span id="load_spinner"></span> Charger</button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="tracking_result" hidden>
                <div class="card border border-success">
                    <div class="card-body text-center">
                        <h5 class="mb-0">FACTURES DE « <span id="student_title"></span> »</h5>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="alert alert-light text-center mb-3">
                            <i class="fas fa-info-circle"></i> À noter : l'icône <i class="fas fa-check-circle text-success"></i>
                            indique que le module figure sur la preuve d'inscription de l'étudiant.
                        </div>

                        <div class="d-flex flex-wrap align-items-center mb-2" style="gap:8px;">
                            <button type="button" class="btn btn-warning btn-sm" id="select_duplicates"><i class="fas fa-check-square"></i> Sélectionner les doublons</button>
                            <button type="button" class="btn btn-danger btn-sm" id="cancel_selected" disabled><i class="fas fa-trash"></i> Annuler la sélection (<span id="selected_count">0</span>)</button>
                        </div>
                        <h5 class="mb-3">Montant total : <span id="total_amount">0</span></h5>

                        <div class="table-responsive">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="check_all" title="Tout sélectionner"></th>
                                        <th>#</th>
                                        <th>Libellé</th>
                                        <th class="text-right">Montant</th>
                                        <th>Année académique</th>
                                        <th>Niveau</th>
                                        <th>Catégorie de frais</th>
                                        <th>Date de facture</th>
                                        <th>Enregistrée le</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="invoice_rows"></tbody>
                            </table>
                        </div>
                        <small class="text-muted">Le montant total ne compte pas les factures annulées. Une facture n° … (nouvelle facturation) s'annule en entier, avec toutes ses lignes.</small>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
// Runs after the dashboard footer has loaded jQuery, Bootstrap and the toast helpers.
window.addEventListener('load', function(){
    var CONTROLLER = "../new_files/Invoice_Tracking/controller.php";
    var STATUS = {
        unpaid:    ['Impayée', 'danger'],
        partial:   ['Partiellement payée', 'warning'],
        paid:      ['Payée', 'success'],
        cancelled: ['Annulée', 'secondary']
    };
    var rows = [];

    function money(value){
        return Number(value || 0).toLocaleString('fr-FR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
    }

    function date_fr(value){
        if(!value) return '';
        var p = value.substr(0, 10).split('-');
        return p.length == 3 ? p[2] + '/' + p[1] + '/' + p[0] : value;
    }

    function cancellable(row){
        return row.status == 'unpaid';
    }

    // What the charge is for: the module for older module invoices, otherwise the fee line.
    function label_cell(row){
        var $cell = $('<td>');
        if(row.source == 'old' && row.module_code){
            if(Number(row.on_proof)) $cell.append('<i class="fas fa-check-circle text-success"></i> ');
            $cell.append($('<strong>').text(row.module_code)).append(' ' + $('<span>').text(row.module_name || '').html());
        } else if(row.source == 'old'){
            $cell.text([row.comment, row.month].filter(Boolean).join(' | ') || row.fee_category || '');
        } else {
            $cell.text(row.description || row.fee_name || '');
            $cell.append($('<div class="text-muted small">').text('Facture n° ' + row.invoice_no));
        }
        return $cell;
    }

    function fill_levels(levels){
        var $select = $('#filter_level'), current = $select.val();
        $select.empty().append('<option value="">Tous</option>');
        $.each(levels || [], function(i, l){
            $select.append($('<option>').val(l.level_id).text(l.level_full_name));
        });
        $select.val($select.find('option[value="' + current + '"]').length ? current : '');
    }

    // Levels come from the student's registrations, so they are there before any invoice is loaded.
    var levels_for = null;
    function load_levels(){
        var reg_no = $.trim($('#reg_no').val());
        if(!reg_no || reg_no == levels_for) return;
        levels_for = reg_no;
        $.post(CONTROLLER, {action: 'levels', reg_no: reg_no}, fill_levels, 'JSON');
    }

    function visible_rows(){
        var level = $('#filter_level').val(), fee = $('#filter_fee').val();
        return rows.filter(function(r){
            return (!level || r.level_id == level) && (!fee || r.fee_category == fee);
        });
    }

    function render(){
        var list = visible_rows();
        var $body = $('#invoice_rows').empty();
        var total = 0;

        if(list.length == 0){
            $body.html('<tr><td colspan="11" class="text-center">Aucune facture trouvée</td></tr>');
        }
        list.forEach(function(row, index){
            if(row.status != 'cancelled') total += Number(row.amount || 0);
            var s = STATUS[row.status] || [row.status, 'light'];
            var $check = cancellable(row)
                ? $('<input type="checkbox" class="row-check">').val(row.row_key)
                : '';
            var $action = cancellable(row)
                ? $('<button type="button" class="btn btn-danger btn-sm cancel-one"><i class="fas fa-trash"></i> Annuler</button>').attr('data-key', row.row_key)
                : '';
            $('<tr>').toggleClass('text-muted', row.status == 'cancelled')
                .append($('<td>').append($check))
                .append($('<td>').text(index + 1))
                .append(label_cell(row))
                .append($('<td class="text-right">').text(money(row.amount)))
                .append($('<td>').text(row.acad_year || ''))
                .append($('<td>').text(row.level_full_name || ''))
                .append($('<td>').text(row.fee_category || ''))
                .append($('<td>').text(date_fr(row.invoice_date)))
                .append($('<td>').text(date_fr(row.recorded_on)))
                .append($('<td>').append($('<span class="badge">').addClass('badge-' + s[1]).text(s[0])))
                .append($('<td>').append($action))
                .appendTo($body);
        });
        $('#total_amount').text(money(total));
        $('#check_all').prop('checked', false);
        update_selection();
    }

    function update_selection(){
        var count = $('.row-check:checked').length;
        $('#selected_count').text(count);
        $('#cancel_selected').prop('disabled', count == 0);
    }

    function load_invoices(){
        var reg_no = $.trim($('#reg_no').val());
        if(!reg_no) return;
        $('#load_spinner').html("<i class='fas fa-spinner fa-spin'></i>");
        $.ajax({
            url: CONTROLLER,
            type: 'POST',
            data: {action: 'load', reg_no: reg_no},
            dataType: 'JSON',
            success: function(res){
                $('#load_spinner').empty();
                if(res.status != 200){
                    $('#tracking_result').attr('hidden', true);
                    pop_wrong(res.message);
                    return;
                }
                rows = res.rows;
                $('#student_title').text(res.student.name + ' | ' + res.student.reg_no);
                levels_for = res.student.reg_no;
                fill_levels(res.levels);
                // Keep the listed categories and add any older name found only on this student's charges.
                var $fee = $('#filter_fee');
                rows.forEach(function(r){
                    if(r.fee_category && $fee.find('option').filter(function(){ return this.value === r.fee_category; }).length == 0){
                        $fee.append($('<option>').val(r.fee_category).text(r.fee_category));
                    }
                });
                $('#tracking_result').attr('hidden', false);
                render();
            },
            error: function(){
                $('#load_spinner').empty();
                pop_wrong("Une erreur est survenue !");
            }
        });
    }

    function cancel(keys){
        if(keys.length == 0) return;
        var has_new = keys.some(function(k){ return k.indexOf('new-') == 0; });
        var question = 'Annuler ' + keys.length + ' facture(s) ?' +
            (has_new ? '\nUne facture n° … est annulée en entier, avec toutes ses lignes.' : '');
        if(!confirm(question)) return;
        $.ajax({
            url: CONTROLLER,
            type: 'POST',
            data: {action: 'cancel', keys: keys},
            dataType: 'JSON',
            success: function(res){
                if(res.status == 200) pop_up_success(res.message); else pop_wrong(res.message);
                load_invoices();
            },
            error: function(){ pop_wrong("Une erreur est survenue !"); }
        });
    }

    // Suggest students while typing in the matricule box.
    var typing;
    $('#reg_no').on('input', function(){
        clearTimeout(typing);
        var q = $.trim($(this).val());
        typing = setTimeout(function(){
            $.post(CONTROLLER, {action: 'search', q: q}, function(list){
                var $list = $('#student_list').empty();
                $.each(list || [], function(i, s){
                    $list.append($('<option>').val(s.reg_no).text(s.fname + ' ' + s.lname));
                });
            }, 'JSON');
        }, 300);
    });

    $('#reg_no').on('change', load_levels);

    $('#tracking_form').on('submit', function(e){
        e.preventDefault();
        load_invoices();
    });
    $('#filter_level, #filter_fee').on('change', render);

    $(document).on('change', '.row-check', update_selection);
    $('#check_all').on('change', function(){
        $('.row-check').prop('checked', this.checked);
        update_selection();
    });

    // A duplicate is a second active charge for the same thing (module or fee line), year and fee category.
    // The oldest one is kept; the later ones are ticked for cancelling.
    $('#select_duplicates').on('click', function(){
        var seen = {};
        $('.row-check').prop('checked', false);
        visible_rows().slice().reverse().forEach(function(row){
            if(row.status == 'cancelled') return;
            var what = row.source == 'old'
                ? (row.module_id ? 'module-' + row.module_id : 'fee-' + row.fee_category + '-' + (row.comment || '') + '-' + (row.month || ''))
                : 'fee-' + row.fee_category + '-' + (row.description || row.fee_name || '');
            var key = what + '|' + row.acad_cycle_id + '|' + row.fee_category;
            if(seen[key] && cancellable(row)){
                $('.row-check[value="' + row.row_key + '"]').prop('checked', true);
            }
            seen[key] = true;
        });
        update_selection();
        if($('.row-check:checked').length == 0) pop_info('Aucun doublon trouvé.');
    });

    $('#cancel_selected').on('click', function(){
        cancel($('.row-check:checked').map(function(){ return this.value; }).get());
    });
    $(document).on('click', '.cancel-one', function(){
        cancel([$(this).data('key')]);
    });
});
</script>
