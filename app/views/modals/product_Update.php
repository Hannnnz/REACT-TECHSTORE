<div id="modal-edit-product" class="modal-overlay hidden">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Edit Product</h2>
            <button type="button" class="modal-close-btn" onclick="closeModal('modal-edit-product')">&times;</button>
        </div>

        <div class="modal-body">
            <form id="form-edit-product" method="post" action="<?=site_url('product/update'); ?>" autocomplete="off">
                <input type="hidden" id="edit_id" name="id" required>
                <div class="form-group">
                    <label for="edit_product_name">Product Name</label>
                    <div class="input-group">
                        <i class="fas fa-tag"></i>
                        <input type="text" id="edit_product_name" name="product_name" required>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="edit_category">Category</label>
                        <div class="input-group">
                            <i class="fas fa-layer-group"></i>
                            <select id="edit_category" name="category" class="form-select-field" required>
                                <option value="" disabled>Select a product category</option>
                                <option value="Electronics">Electronics</option>
                                <option value="Keyboard">Keyboard</option>
                                <option value="Mouse">Mouse</option>
                                <option value="Controller">Controller</option>
                                <option value="Speaker">Speaker</option>
                                <option value="Headset">Headset</option>
                                <option value="Microphone">Microphone</option>
                                <option value="Webcam">Webcam</option>
                                <option value="Accessories">Accessories</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="edit_unit_price">Unit Price (₱)</label>
                        <div class="input-group">
                            <i class="fas fa-peso-sign"></i>
                            <input id="edit_unit_price" name="unit_price" type="number" step="1" min="50" required>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="edit_product_id">Barcode</label>
                    <div class="input-group">
                        <i class="fas fa-barcode"></i>
                        <input type="text" id="edit_product_id" name="product_id" required>
                    </div>
                </div>

        </div>

        <div class="modal-footer">
            <button type="button" class="action-btn modal-cancel-btn" onclick="closeModal('modal-edit-product')">Cancel</button>
            <button type="submit" class="action-btn primary-btn">Update</button>
        </div>
        </form>
    </div>
</div>
