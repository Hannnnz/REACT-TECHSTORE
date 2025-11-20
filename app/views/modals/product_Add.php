<div id="modal-add-product" class="modal-overlay hidden">
        <div class="modal-content">
            <div class="modal-header"><h2>Add New Product</h2><button class="modal-close-btn" onclick="closeModal('modal-add-product')">&times;</button></div>
            <div class="modal-body">
                <form id="form-add-product" method="post" action="<?=site_url('create'); ?>" autocomplete="off">
                    <div class="form-group">
                        <label for="product_name">Product Name</label>
                        <div class="input-group"><i class="fas fa-tag"></i><input type="text" id="product_name" name="product_name" placeholder="Enter product name" required></div>
                    </div>
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="category">Category</label>
                            <div class="input-group"><i class="fas fa-layer-group"></i>
                                <select id="category" name="category" class="form-select-field" required>
                                    <option value="" disabled selected>Select a product category</option>
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
                            <label for="unit_price">Unit Price (₱)</label>
                            <div class="input-group"><i class="fas fa-peso-sign"></i><input id="unit_price" name="unit_price" type="number" step="1" min="50" placeholder="₱0.00" required></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="product_id">Barcode</label>
                        <div class="input-group"><i class="fas fa-barcode"></i><input type="text" id="product_id" name="product_id" placeholder="Scan unique product code" required></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="action-btn modal-cancel-btn" onclick="closeModal('modal-add-product')">Cancel</button>
                <button class="action-btn primary-btn" onclick="handleFormSubmit('modal-add-product')">Save Product</button>
            </div>
        </div>
    </div>