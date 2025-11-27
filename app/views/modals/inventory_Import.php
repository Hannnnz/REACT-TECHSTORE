<div id="modal-import-csv" class="modal-overlay hidden">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Import Inventory CSV</h2>
            <button class="modal-close-btn" onclick="closeModal('modal-import-csv')">&times;</button>
        </div>
        <form id="import-csv-form" action="<?= site_url('inventory/update-csv'); ?>" method="POST" enctype="multipart/form-data">
            <div class="modal-body">
                <div id="drop-zone" class="drop-zone" style="padding:40px; border:2px dashed var(--clr-border); text-align:center; cursor:pointer; transition:all 0.2s ease;">
                    <i class="fas fa-file-csv" style="font-size:3rem; margin-bottom:10px; color:var(--clr-primary)"></i>
                    <p>Drag & Drop CSV file here or Click to Upload</p>
                    <p id="file-name-display" style="margin-top:10px; color:var(--clr-primary); font-weight:600;"></p>
                    <input type="file" id="csv-file-input" name="csv_file" accept=".csv" hidden>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="action-btn modal-cancel-btn" onclick="closeModal('modal-import-csv')">Cancel</button>
                <button type="submit" class="action-btn primary-btn" id="btn-upload-csv">Upload</button>
            </div>
        </form>
    </div>
</div>
