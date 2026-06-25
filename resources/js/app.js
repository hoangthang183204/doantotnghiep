// // resources/js/app.js

// // ============================================
// // ALPINE COMPONENTS
// // ============================================
// document.addEventListener('alpine:init', () => {
//     // Notification Component
//     Alpine.data('notificationComponent', () => ({
//         isOpen: false,
//         notifications: [],
//         unreadCount: 0,
//         loading: false,
//         timer: null,

//         init() {
//             console.log('Notification component initialized');
//             this.fetchNotifications();
//             this.startPolling();
//         },

//         toggleDropdown() {
//             this.isOpen = !this.isOpen;
//             if (this.isOpen) {
//                 this.fetchNotifications();
//             }
//         },

//         async fetchNotifications() {
//             this.loading = true;
//             try {
//                 const response = await fetch('/api/notifications', {
//                     headers: {
//                         'X-Requested-With': 'XMLHttpRequest',
//                         'Accept': 'application/json',
//                     },
//                     credentials: 'same-origin'
//                 });
                
//                 if (!response.ok) throw new Error('Network response was not ok');
//                 const data = await response.json();
                
//                 this.notifications = data.data || [];
//                 this.unreadCount = data.unread_count || 0;
//                 console.log('Notifications fetched:', this.notifications.length);
//             } catch (error) {
//                 console.error('Error fetching notifications:', error);
//             } finally {
//                 this.loading = false;
//             }
//         },

//         async markAsRead(notification) {
//             if (notification.read_at) return;

//             try {
//                 const response = await fetch(`/api/notifications/${notification.id}/mark-as-read`, {
//                     method: 'POST',
//                     headers: {
//                         'X-Requested-With': 'XMLHttpRequest',
//                         'Accept': 'application/json',
//                         'Content-Type': 'application/json',
//                     },
//                     credentials: 'same-origin',
//                     body: JSON.stringify({})
//                 });

//                 if (!response.ok) throw new Error('Network response was not ok');
                
//                 notification.read_at = new Date().toISOString();
//                 this.unreadCount = Math.max(0, this.unreadCount - 1);
//                 this.updateBadge();
//             } catch (error) {
//                 console.error('Error marking notification as read:', error);
//             }
//         },

//         async markAllAsRead() {
//             try {
//                 const response = await fetch('/api/notifications/mark-all-as-read', {
//                     method: 'POST',
//                     headers: {
//                         'X-Requested-With': 'XMLHttpRequest',
//                         'Accept': 'application/json',
//                         'Content-Type': 'application/json',
//                     },
//                     credentials: 'same-origin',
//                     body: JSON.stringify({})
//                 });

//                 if (!response.ok) throw new Error('Network response was not ok');
                
//                 this.notifications.forEach(n => n.read_at = new Date().toISOString());
//                 this.unreadCount = 0;
//                 this.updateBadge();
//             } catch (error) {
//                 console.error('Error marking all as read:', error);
//             }
//         },

//         startPolling() {
//             this.timer = setInterval(() => {
//                 this.fetchUnreadCount();
//             }, 30000);
//         },

//         async fetchUnreadCount() {
//             try {
//                 const response = await fetch('/api/notifications/unread-count', {
//                     headers: {
//                         'X-Requested-With': 'XMLHttpRequest',
//                         'Accept': 'application/json',
//                     },
//                     credentials: 'same-origin'
//                 });
                
//                 if (!response.ok) throw new Error('Network response was not ok');
//                 const data = await response.json();
                
//                 this.unreadCount = data.count || 0;
//                 this.updateBadge();
//             } catch (error) {
//                 console.error('Error fetching unread count:', error);
//             }
//         },

//         timeAgo(time) {
//             if (!time) return '';
//             const date = new Date(time);
//             const now = new Date();
//             const diff = Math.floor((now - date) / 1000);
            
//             if (diff < 60) return 'Vừa xong';
//             if (diff < 3600) return Math.floor(diff / 60) + ' phút trước';
//             if (diff < 86400) return Math.floor(diff / 3600) + ' giờ trước';
//             if (diff < 2592000) return Math.floor(diff / 86400) + ' ngày trước';
//             if (diff < 31536000) return Math.floor(diff / 2592000) + ' tháng trước';
//             return Math.floor(diff / 31536000) + ' năm trước';
//         },

//         updateBadge() {
//             const title = document.querySelector('title');
//             if (title) {
//                 title.textContent = this.unreadCount > 0 
//                     ? `(${this.unreadCount}) HRFlow` 
//                     : 'HRFlow';
//             }
//         },

//         destroy() {
//             if (this.timer) {
//                 clearInterval(this.timer);
//             }
//         }
//     }));

//     // User Dropdown Component
//     Alpine.data('userDropdown', () => ({
//         isOpen: false,
//         toggle() {
//             this.isOpen = !this.isOpen;
//             console.log('User dropdown toggled:', this.isOpen);
//         }
//     }));

//     console.log('✅ Alpine components registered successfully');
// });