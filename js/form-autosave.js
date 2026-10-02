/**
 * Keeps a form's field values (and, optionally, which step it's on) in
 * localStorage so a reload doesn't wipe out what the visitor already typed.
 * Used by the multi-step application forms (Create Account, Postgraduate,
 * Masters) which don't have a backend draft-save yet.
 *
 * Usage:
 *   var autosave = enableFormAutosave('#my_form', 'my_form_draft_v1', {
 *       rowAdders: { etude: function(){ pgAddEtudeRow(); } }, // name="etude_x[]" fields
 *       currentStepGetter: function(){ return pgCurrentSection; },
 *       onRestoreStep: function(step){ ...show that step without validation... }
 *   });
 *   // call autosave.clear() once the form is actually, successfully submitted.
 */
function enableFormAutosave(formSelector, storageKey, options) {
    options = options || {};
    var $form = $(formSelector);
    if (!$form.length) { return null; }

    function fieldsForKey(name) {
        return $form.find('[name="' + name + '"]');
    }

    function saveState() {
        var data = {};
        $form.find('input, select, textarea').each(function () {
            var $el = $(this);
            var name = $el.attr('name');
            if (!name || $el.attr('type') === 'file') { return; }

            if ($el.attr('type') === 'checkbox' || $el.attr('type') === 'radio') {
                data[name] = $el.is(':checked');
            } else if (name.slice(-2) === '[]') {
                data[name] = data[name] || [];
                data[name].push($el.val());
            } else {
                data[name] = $el.val();
            }
        });

        if (options.currentStepGetter) {
            data.__step = options.currentStepGetter();
        }

        try {
            localStorage.setItem(storageKey, JSON.stringify(data));
        } catch (e) { /* storage full or unavailable - nothing we can do */ }
    }

    function restoreState() {
        var raw;
        try {
            raw = localStorage.getItem(storageKey);
        } catch (e) { return; }
        if (!raw) { return; }

        var data;
        try {
            data = JSON.parse(raw);
        } catch (e) { return; }

        // Make sure there are enough dynamically-added rows before filling them in.
        if (options.rowAdders) {
            Object.keys(options.rowAdders).forEach(function (prefix) {
                var arrName = prefix + '[]';
                var wanted = (data[arrName] || []).length;
                var existing = fieldsForKey(arrName).length;
                for (var i = existing; i < wanted; i++) {
                    options.rowAdders[prefix]();
                }
            });
        }

        var arrayCursor = {};
        $form.find('input, select, textarea').each(function () {
            var $el = $(this);
            var name = $el.attr('name');
            if (!name || !(name in data)) { return; }

            if ($el.attr('type') === 'checkbox' || $el.attr('type') === 'radio') {
                $el.prop('checked', !!data[name]);
            } else if (name.slice(-2) === '[]') {
                var idx = arrayCursor[name] || 0;
                arrayCursor[name] = idx + 1;
                if (Array.isArray(data[name]) && data[name][idx] !== undefined) {
                    $el.val(data[name][idx]);
                }
            } else {
                $el.val(data[name]);
            }
        });

        if (options.onRestoreStep && data.__step) {
            options.onRestoreStep(data.__step);
        }
    }

    var saveTimer = null;
    $form.on('input change', 'input, select, textarea', function () {
        clearTimeout(saveTimer);
        saveTimer = setTimeout(saveState, 250);
    });

    function clearState() {
        try { localStorage.removeItem(storageKey); } catch (e) { /* ignore */ }
    }

    // Restore after the page's own ready handlers (e.g. the first dynamic
    // table row) have already run.
    setTimeout(restoreState, 50);

    return { save: saveState, restore: restoreState, clear: clearState };
}
