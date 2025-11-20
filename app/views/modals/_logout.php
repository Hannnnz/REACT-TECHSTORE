<div id="logout-modal" class="modal-overlay hidden">
        <div class="modal-content">
            <div class="modal-header"><h2>Confirm Logout</h2><button class="modal-close-btn" onclick="closeModal('logout-modal')">&times;</button></div>
            <div class="modal-body"><p>Are you sure you want to log out?</p></div>
            <div class="modal-footer">
                <button class="action-btn modal-cancel-btn" onclick="closeModal('logout-modal')">Cancel</button>
                <button class="action-btn delete-btn" onclick="window.location.href='<?= site_url('auth/logout'); ?>'">Logout</button>
            </div>
        </div>
    </div>  