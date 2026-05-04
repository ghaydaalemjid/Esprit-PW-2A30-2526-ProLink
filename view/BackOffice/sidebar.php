<?php /* Sidebar */ ?>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<link rel="stylesheet" href="../assets/style.css">
<link rel="stylesheet" href="sidebar.css">

<style>
.notif-container {
    position: relative;
    display: block;
    cursor: pointer;
}

.notif-link {
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    color: white;
    padding: 10px;
}

.notif-badge {
    position: absolute;
    top: -5px;
    right: 15px;
    background: #ff4444;
    color: white;
    border-radius: 50%;
    padding: 2px 6px;
    font-size: 11px;
    font-weight: bold;
    min-width: 18px;
    text-align: center;
}

.notif-dropdown {
    position: fixed;
    top: 50px;
    left: 260px;
    width: 350px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.2);
    z-index: 1000;
    display: none;
    max-height: 450px;
    overflow-y: auto;
}

.notif-dropdown.show {
    display: block;
}

.notif-header {
    padding: 12px 15px;
    border-bottom: 1px solid #eee;
    font-weight: bold;
    display: flex;
    justify-content: space-between;
    background: #f8f9fa;
    border-radius: 10px 10px 0 0;
}

.notif-item {
    padding: 12px 15px;
    border-bottom: 1px solid #f0f0f0;
    cursor: pointer;
    transition: background 0.2s;
}

.notif-item:hover {
    background: #f5f5f5;
}

.notif-item.unread {
    background: #e3f2fd;
    border-left: 3px solid #2196F3;
}

.notif-message {
    font-size: 13px;
    color: #333;
}

.notif-date {
    font-size: 11px;
    color: #999;
    margin-top: 5px;
}

.notif-empty {
    padding: 30px;
    text-align: center;
    color: #999;
}

.btn-mark-all {
    background: none;
    border: none;
    color: #2196F3;
    cursor: pointer;
    font-size: 12px;
}

.btn-mark-all:hover {
    text-decoration: underline;
}
</style>

<div class="sidebar">
    <div class="brand">ProLink</div>

    <nav>
        <ul>
            <li><a href="dashboard.php">🏠 Dashboard</a></li>
            <li><a href="listUsers.php">👤 Gestion Users</a></li>
            <li><a href="listProjects.php">📁 Gestion Projets</a></li>
            <li><a href="listCandidatures.php">📄 Gestion Candidatures</a></li>

            <li class="notif-container">
                <div class="notif-link" onclick="toggleNotifications()">
                    🔔 Notifications
                    <span class="notif-badge" id="notifCount">0</span>
                </div>
                
                <div class="notif-dropdown" id="notifDropdown">
                    <div class="notif-header">
                        <span>🔔 Notifications</span>
                        <button class="btn-mark-all" onclick="markAllAsRead()">Tout marquer comme lu</button>
                    </div>
                    <div id="notifList">
                        <div class="notif-empty">📭 Chargement...</div>
                    </div>
                </div>
            </li>

            <li><a href="profile_admin.php">👤 Mon profil</a></li>
            <li><a href="../logout.php">🔓 Se déconnecter</a></li>
        </ul>
    </nav>
</div>

<script>
let notifInterval = null;

function startNotificationChecker() {
    if(notifInterval) clearInterval(notifInterval);
    fetchNotifications();
    notifInterval = setInterval(function() {
        fetchNotifications();
    }, 5000);
}

function fetchNotifications() {
    fetch('get_notifications.php?action=get')
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                updateNotifBadge(data.count);
                updateNotifList(data.notifications);
            }
        })
        .catch(error => console.error('Erreur:', error));
}

function updateNotifBadge(count) {
    let badge = document.getElementById('notifCount');
    if(badge) {
        if(count > 0) {
            badge.textContent = count > 99 ? '99+' : count;
            badge.style.display = 'inline-block';
        } else {
            badge.style.display = 'none';
        }
    }
}

function updateNotifList(notifications) {
    let container = document.getElementById('notifList');
    if(!container) return;
    
    if(!notifications || notifications.length === 0) {
        container.innerHTML = '<div class="notif-empty">📭 Aucune notification non lue</div>';
        return;
    }
    
    let html = '';
    notifications.forEach(notif => {
        let message = notif.message.replace(/[�]/g, '');
        html += `
            <div class="notif-item unread" onclick="markAsRead(${notif.id})">
                <div class="notif-message">${message}</div>
                <div class="notif-date">${formatDate(notif.created_at)}</div>
            </div>
        `;
    });
    container.innerHTML = html;
}

function markAsRead(id) {
    fetch(`get_notifications.php?action=mark_read&id=${id}`)
        .then(() => fetchNotifications());
}

function markAllAsRead() {
    fetch('get_notifications.php?action=mark_all_read')
        .then(() => fetchNotifications());
}

function formatDate(dateStr) {
    let date = new Date(dateStr);
    let now = new Date();
    let diff = Math.floor((now - date) / 1000);
    
    if(diff < 60) return 'à l\'instant';
    if(diff < 3600) return `il y a ${Math.floor(diff / 60)} min`;
    if(diff < 86400) return `il y a ${Math.floor(diff / 3600)} h`;
    return `le ${date.toLocaleDateString()}`;
}

function toggleNotifications() {
    let dropdown = document.getElementById('notifDropdown');
    if(dropdown) {
        dropdown.classList.toggle('show');
    }
}

document.addEventListener('click', function(event) {
    let container = document.querySelector('.notif-container');
    let dropdown = document.getElementById('notifDropdown');
    
    if(container && dropdown) {
        if(!container.contains(event.target)) {
            dropdown.classList.remove('show');
        }
    }
});

document.addEventListener('DOMContentLoaded', function() {
    startNotificationChecker();
});
</script>

<script src="backoffice.js"></script>