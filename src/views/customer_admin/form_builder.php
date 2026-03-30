<?php $layout = 'admin'; $title = ($form ? 'Edit' : 'Create') . ' Form'; $pageTitle = ($form ? 'Edit' : 'Create') . ' Form'; ?>
<?php $sidebarMenu = [
    ['url' => '/customer', 'icon' => 'speedometer2', 'label' => 'Dashboard', 'active' => false],
    ['url' => '/customer/tables', 'icon' => 'grid-3x3', 'label' => 'Tables', 'active' => false],
    ['url' => '/customer/waiters', 'icon' => 'people', 'label' => 'Waiters', 'active' => false],
    ['url' => '/customer/assignments', 'icon' => 'diagram-3', 'label' => 'Assignments', 'active' => false],
    ['url' => '/customer/products', 'icon' => 'box-seam', 'label' => 'Products', 'active' => false],
    ['url' => '/customer/qr', 'icon' => 'qr-code', 'label' => 'QR Codes', 'active' => false],
    ['url' => '/customer/forms', 'icon' => 'ui-checks-grid', 'label' => 'Form Builder', 'active' => true],
    ['url' => '/customer/form-assignments', 'icon' => 'link-45deg', 'label' => 'Form Assignments', 'active' => false],
    ['url' => '/customer/location', 'icon' => 'geo-alt', 'label' => 'Location', 'active' => false],
    ['url' => '/customer/orders', 'icon' => 'receipt', 'label' => 'Orders', 'active' => false],
    ['url' => '/customer/subscription', 'icon' => 'credit-card', 'label' => 'Subscription', 'active' => false],
    ['url' => '/customer/activity-logs', 'icon' => 'clock-history', 'label' => 'Activity Logs', 'active' => false],
]; ?>

<?php
$isEdit   = !empty($form);
$formAction = $isEdit ? url('/customer/forms/update/' . (int) $form['id']) : url('/customer/forms/store');
$formName   = $isEdit ? $form['name'] : '';
$existingFields = ($isEdit && $formData && isset($formData['fields'])) ? $formData['fields'] : [];
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jquery-ui@1.13.2/dist/themes/base/jquery-ui.min.css">

<style>
.field-type-btn{cursor:grab;padding:12px;margin-bottom:8px;background:#f8f9fa;border:2px dashed #dee2e6;border-radius:8px;text-align:center;transition:all .2s}
.field-type-btn:hover{border-color:#0d6efd;background:#e7f1ff}
.field-type-btn.disabled-field{opacity:.5;cursor:not-allowed;pointer-events:none}
#formCanvas{min-height:200px;border:2px dashed #dee2e6;border-radius:8px;padding:16px;background:#fff}
#formCanvas.ui-sortable-helper{box-shadow:0 4px 12px rgba(0,0,0,.15)}
.canvas-field{background:#f8f9fa;border:1px solid #dee2e6;border-radius:8px;padding:16px;margin-bottom:12px;position:relative}
.canvas-field .drag-handle{cursor:grab;color:#adb5bd;margin-right:8px}
.canvas-field .remove-field{position:absolute;top:8px;right:8px}
#formCanvas .ui-sortable-placeholder{height:60px;border:2px dashed #0d6efd;border-radius:8px;background:#e7f1ff;margin-bottom:12px}
</style>

<form method="POST" action="<?= h($formAction) ?>" id="formBuilderForm">
    <?= CSRF::field() ?>
    <input type="hidden" name="form_json" id="formJsonInput">

    <div class="row mb-3">
        <div class="col-md-8">
            <label class="form-label fw-bold">Form Name</label>
            <input type="text" name="name" id="formName" class="form-control form-control-lg" value="<?= h($formName) ?>" required placeholder="Enter form name...">
        </div>
        <div class="col-md-4 d-flex align-items-end gap-2">
            <span class="badge bg-primary fs-6" id="fieldCounter">0/7 fields</span>
            <button type="button" class="btn btn-outline-info" id="previewBtn"><i class="bi bi-eye"></i> Preview</button>
            <button type="submit" class="btn btn-success"><i class="bi bi-check-lg"></i> Save</button>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left: Field Types -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white"><h6 class="mb-0">Field Types</h6></div>
                <div class="card-body">
                    <div class="field-type-btn" data-type="text" id="addText">
                        <i class="bi bi-input-cursor-text fs-4 d-block mb-1"></i>Text Field
                    </div>
                    <div class="field-type-btn" data-type="select" id="addSelect">
                        <i class="bi bi-menu-button-wide fs-4 d-block mb-1"></i>Select
                    </div>
                    <div class="field-type-btn" data-type="textarea" id="addTextarea">
                        <i class="bi bi-textarea-resize fs-4 d-block mb-1"></i>Textarea
                    </div>
                    <div class="field-type-btn" data-type="button" id="addButton">
                        <i class="bi bi-stop-btn fs-4 d-block mb-1"></i>Button
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Canvas -->
        <div class="col-md-9">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white"><h6 class="mb-0">Form Canvas</h6></div>
                <div class="card-body">
                    <div id="formCanvas">
                        <p class="text-muted text-center py-4" id="canvasPlaceholder">Click a field type to add it here. Drag to reorder.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title">Form Preview</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body" id="previewBody"></div>
</div></div></div>

<script src="https://cdn.jsdelivr.net/npm/jquery-ui@1.13.2/dist/jquery-ui.min.js"></script>
<script>
(function(){
    const MAX_FIELDS = 7;
    let fieldCount = 0;

    function updateCounter(){
        fieldCount = $('#formCanvas .canvas-field').length;
        $('#fieldCounter').text(fieldCount + '/' + MAX_FIELDS + ' fields');
        if(fieldCount >= MAX_FIELDS){
            $('.field-type-btn').addClass('disabled-field');
        } else {
            $('.field-type-btn').removeClass('disabled-field');
        }
        $('#canvasPlaceholder').toggle(fieldCount === 0);
    }

    function createFieldHtml(type, label, placeholder, required, options){
        label = label || (type.charAt(0).toUpperCase() + type.slice(1) + ' Field');
        placeholder = placeholder || '';
        required = required || false;
        options = options || [];

        let optionsHtml = '';
        if(type === 'select'){
            let optsList = options.length ? options.map(function(o,i){
                return '<div class="input-group input-group-sm mb-1 opt-row"><input type="text" class="form-control opt-input" value="'+$('<div>').text(o).html()+'"><button type="button" class="btn btn-outline-danger btn-sm remove-opt"><i class="bi bi-x"></i></button></div>';
            }).join('') : '';
            optionsHtml = '<div class="mt-2"><label class="form-label small text-muted">Options</label><div class="options-container">'+optsList+'</div><button type="button" class="btn btn-outline-secondary btn-sm mt-1 add-opt-btn"><i class="bi bi-plus"></i> Add Option</button></div>';
        }

        return '<div class="canvas-field" data-type="'+type+'">' +
            '<div class="d-flex align-items-center mb-2">' +
                '<i class="bi bi-grip-vertical drag-handle"></i>' +
                '<span class="badge bg-secondary me-2">'+type+'</span>' +
                '<button type="button" class="btn btn-sm btn-outline-danger remove-field"><i class="bi bi-x-lg"></i></button>' +
            '</div>' +
            '<div class="row g-2">' +
                '<div class="col-md-6"><label class="form-label small">Label</label><input type="text" class="form-control form-control-sm field-label" value="'+$('<div>').text(label).html()+'"></div>' +
                (type !== 'button' ? '<div class="col-md-4"><label class="form-label small">Placeholder</label><input type="text" class="form-control form-control-sm field-placeholder" value="'+$('<div>').text(placeholder).html()+'"></div>' : '') +
                (type !== 'button' ? '<div class="col-md-2 d-flex align-items-end"><div class="form-check"><input type="checkbox" class="form-check-input field-required" '+(required?'checked':'')+'><label class="form-check-label small">Required</label></div></div>' : '') +
            '</div>' +
            optionsHtml +
        '</div>';
    }

    function addField(type){
        if(fieldCount >= MAX_FIELDS) return;
        var html = createFieldHtml(type);
        $('#canvasPlaceholder').before(html);
        updateCounter();
    }

    // Click to add fields
    $('.field-type-btn').on('click', function(){
        if($(this).hasClass('disabled-field')) return;
        addField($(this).data('type'));
    });

    // Remove field
    $(document).on('click', '.remove-field', function(){
        $(this).closest('.canvas-field').remove();
        updateCounter();
    });

    // Add option for select
    $(document).on('click', '.add-opt-btn', function(){
        $(this).siblings('.options-container').append(
            '<div class="input-group input-group-sm mb-1 opt-row"><input type="text" class="form-control opt-input" placeholder="Option text"><button type="button" class="btn btn-outline-danger btn-sm remove-opt"><i class="bi bi-x"></i></button></div>'
        );
    });

    $(document).on('click', '.remove-opt', function(){
        $(this).closest('.opt-row').remove();
    });

    // Sortable
    $('#formCanvas').sortable({
        handle: '.drag-handle',
        placeholder: 'ui-sortable-placeholder',
        items: '.canvas-field',
        tolerance: 'pointer'
    });

    // Build JSON on submit
    $('#formBuilderForm').on('submit', function(e){
        var fields = [];
        $('#formCanvas .canvas-field').each(function(){
            var $f = $(this);
            var field = {
                type: $f.data('type'),
                label: $f.find('.field-label').val() || 'Untitled',
                placeholder: $f.find('.field-placeholder').val() || '',
                required: $f.find('.field-required').is(':checked')
            };
            if(field.type === 'select'){
                field.options = [];
                $f.find('.opt-input').each(function(){
                    var v = $(this).val().trim();
                    if(v) field.options.push(v);
                });
            }
            fields.push(field);
        });
        if(fields.length === 0){
            e.preventDefault();
            alert('Please add at least one field.');
            return false;
        }
        $('#formJsonInput').val(JSON.stringify({fields: fields}));
    });

    // Preview
    $('#previewBtn').on('click', function(){
        var html = '<h5>' + ($('#formName').val() || 'Untitled Form') + '</h5><hr>';
        $('#formCanvas .canvas-field').each(function(){
            var $f = $(this);
            var type = $f.data('type');
            var label = $f.find('.field-label').val() || 'Untitled';
            var ph = $f.find('.field-placeholder').val() || '';
            var req = $f.find('.field-required').is(':checked') ? ' <span class="text-danger">*</span>' : '';

            if(type === 'text'){
                html += '<div class="mb-3"><label class="form-label">'+$('<span>').text(label).html()+req+'</label><input type="text" class="form-control" placeholder="'+$('<span>').text(ph).html()+'" disabled></div>';
            } else if(type === 'textarea'){
                html += '<div class="mb-3"><label class="form-label">'+$('<span>').text(label).html()+req+'</label><textarea class="form-control" placeholder="'+$('<span>').text(ph).html()+'" disabled rows="3"></textarea></div>';
            } else if(type === 'select'){
                var opts = '<option value="">'+$('<span>').text(ph || 'Select...').html()+'</option>';
                $f.find('.opt-input').each(function(){
                    var v = $(this).val().trim();
                    if(v) opts += '<option>'+$('<span>').text(v).html()+'</option>';
                });
                html += '<div class="mb-3"><label class="form-label">'+$('<span>').text(label).html()+req+'</label><select class="form-select" disabled>'+opts+'</select></div>';
            } else if(type === 'button'){
                html += '<div class="mb-3"><button type="button" class="btn btn-primary" disabled>'+$('<span>').text(label).html()+'</button></div>';
            }
        });
        if($('#formCanvas .canvas-field').length === 0) html += '<p class="text-muted">No fields added yet.</p>';
        $('#previewBody').html(html);
        new bootstrap.Modal(document.getElementById('previewModal')).show();
    });

    // Load existing fields (edit mode)
    <?php if (!empty($existingFields)): ?>
    var existing = <?= json_encode($existingFields, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
    existing.forEach(function(f){
        var html = createFieldHtml(f.type, f.label, f.placeholder || '', f.required || false, f.options || []);
        $('#canvasPlaceholder').before(html);
    });
    updateCounter();
    <?php endif; ?>

    updateCounter();
})();
</script>
