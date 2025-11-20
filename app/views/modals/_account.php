<div id="settings-modal" class="modal-overlay hidden">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Account Settings</h2>
                <button class="modal-close-btn" onclick="closeModal('settings-modal')">&times;</button>
            </div>
            <div class="modal-body">
                <form id="account-settings-form">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <div class="input-group">
                            <i class="fas fa-user"></i>
                            <input type="text" id="username" value="Admin" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <div class="input-group">
                            <i class="fas fa-envelope"></i>
                            <input type="email" id="email" value="admin@techstore.com">
                        </div>
                    </div>
                    
                    <h3 class="form-divider">Change Password</h3>
                    
                    <div class="form-group">
                        <label for="current-password">Current Password</label>
                        <div class="input-group">
                            <i class="fas fa-shield-alt"></i>
                            <input type="password" id="current-password" placeholder="Enter current password">
                        </div>
                    </div>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="new-password">New Password</label>
                            <div class="input-group">
                                <i class="fas fa-lock"></i>
                                <input type="password" id="new-password" placeholder="Enter new password">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="confirm-password">Confirm New Password</label>
                            <div class="input-group">
                                <i class="fas fa-lock"></i>
                                <input type="password" id="confirm-password" placeholder="Confirm new password">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="action-btn modal-cancel-btn" onclick="closeModal('settings-modal')">Cancel</button>
                <button class="action-btn primary-btn" id="save-settings-btn" onclick="handleFormSubmit('settings-modal', 'Account settings saved!')">Save Changes</button>
            </div>
        </div>
    </div>