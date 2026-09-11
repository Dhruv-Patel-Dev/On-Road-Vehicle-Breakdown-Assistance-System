<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'vehicle_owner') {
    header('Location: index.html');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - On-Road Assist</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-hover: #4338ca;
            --secondary-color: #f0f4f8;
            --text-dark: #1f2937;
            --text-light: #6b7280;
            --white: #ffffff;
            --border-color: #e5e7eb;
            --success-color: #16a34a;
            --danger-color: #dc2626;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Inter', sans-serif;
            background: var(--secondary-color);
            color: var(--text-dark);
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            background: var(--white);
            padding: 1.5rem 2rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: var(--primary-color);
            color: var(--white);
        }

        .profile-card {
            background: var(--white);
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .profile-card + .profile-card {
            margin-top: 2rem;
        }

        .section-title {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: var(--text-dark);
            border-bottom: 2px solid var(--border-color);
            padding-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .form-input {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid var(--border-color);
            border-radius: 6px;
            font-size: 1rem;
        }

        .form-input:focus {
            border-color: var(--primary-color);
            outline: none;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .btn-success {
            background: var(--success-color);
            color: var(--white);
        }

        .btn-secondary {
            background: var(--text-light);
            color: var(--white);
            margin-left: 1rem;
        }

        .btn-danger {
            background: var(--danger-color);
            color: var(--white);
            margin-top: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-user"></i> My Profile</h1>
            <a href="dashboard-vehicle-owner.html" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>

        <div class="profile-card">
            <div class="section-title">
                <i class="fas fa-edit"></i> Personal Information
            </div>
            <form id="profileForm">
                <div class="form-row">
                    <div class="form-group">
                        <label for="firstName">First Name</label>
                        <input type="text" id="firstName" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last Name</label>
                        <input type="text" id="lastName" class="form-input" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" class="form-input" required>
                </div>
                
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Update Profile
                </button>
                <button type="button" onclick="resetForm()" class="btn btn-secondary">
                    <i class="fas fa-undo"></i> Reset
                </button>
            </form>
        </div>

        <div class="profile-card">
            <div class="section-title">
                <i class="fas fa-shield-alt"></i> Account Security
            </div>
            <div class="form-group">
                <label>Change Password</label>
                <button onclick="changePassword()" class="btn btn-secondary" style="margin-left: 0;">
                    <i class="fas fa-key"></i> Change Password
                </button>
            </div>
            <div class="form-group">
                <label>Account Deletion</label>
                <button onclick="deleteAccount()" class="btn btn-danger" style="margin-left: 0; margin-top: 0.5rem;">
                    <i class="fas fa-trash"></i> Delete Account
                </button>
            </div>
        </div>
    </div>

    <script>
        async function resetForm() {
            if (confirm('Are you sure you want to reset all changes?')) {
                await fetchProfileData();
            }
        }

        async function changePassword() {
            const newPassword = prompt('Enter your new password:');
            if (!newPassword) return;
            if (newPassword.length < 6) {
                alert('Password must be at least 6 characters long!');
                return;
            }
            try {
                const res = await fetch('../backend/change_password.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ new_password: newPassword })
                });
                const data = await res.json();
                if (data.success) {
                    alert('Password changed successfully!');
                } else {
                    alert('Error: ' + data.error);
                }
            } catch (err) {
                alert('Failed to change password.');
            }
        }

        async function deleteAccount() {
            const confirmation = prompt('Type "DELETE" to confirm account deletion:');
            if (confirmation !== 'DELETE') return;
            if (!confirm('This action cannot be undone. Are you sure?')) return;
            try {
                const res = await fetch('../backend/delete_account.php', {
                    method: 'POST'
                });
                const data = await res.json();
                if (data.success) {
                    alert('Account deleted successfully.');
                    window.location.href = '../backend/logout.php';
                } else {
                    alert('Error: ' + data.error);
                }
            } catch (err) {
                alert('Failed to delete account.');
            }
        }

        document.getElementById('profileForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const data = {
                first_name: document.getElementById('firstName').value,
                last_name: document.getElementById('lastName').value,
                email: document.getElementById('email').value,
                phone: document.getElementById('phone').value
            };
            try {
                const res = await fetch('../backend/update_profile.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                const result = await res.json();
                if (result.success) {
                    alert('Profile updated successfully!');
                    await fetchProfileData(); // Refresh data
                } else {
                    alert('Error: ' + result.error);
                }
            } catch (err) {
                alert('Failed to update profile.');
            }
        });

        // Fetch profile data to populate the page
        async function fetchProfileData() {
            try {
                const profileRes = await fetch('../backend/get_user_profile.php');
                if (profileRes.ok) {
                    const data = await profileRes.json();
                    if (data.success && data.user) {
                        const user = data.user;
                        document.getElementById('firstName').value = user.first_name || '';
                        document.getElementById('lastName').value = user.last_name || '';
                        document.getElementById('email').value = user.email || '';
                        document.getElementById('phone').value = user.phone || '';
                    }
                }
            } catch (err) {
                console.error('Failed to load profile data', err);
                alert('Failed to load your profile data. Please try again.');
            }
        }

        // minimal HTML escape
        function escapeHtml(str) {
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '<')
                .replace(/>/g, '>')
                .replace(/"/g, '"')
                .replace(/'/g, '&#39;');
        }

        // run on page load
        fetchProfileData();
    </script>
</body>
</html>
