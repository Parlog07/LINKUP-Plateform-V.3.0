import './bootstrap';



document.addEventListener('DOMContentLoaded', function() {
    const currentUserId = window.currentUserId;
    
    if (currentUserId && window.Echo) {
        console.log('🔔 Setting up notification listener for user:', currentUserId);
        
        window.Echo.private(`user.notifications.${currentUserId}`)
            .listen('FriendRequestSent', (event) => {
                console.log('✅ Notification received:', event);
                
                // Show notification
                showNotification(event.message);
            })
            .error((error) => {
                console.error('❌ Echo error:', error);
            });
    } else {
        console.warn('⚠️ Echo not initialized or user not logged in');
    }
});

function showNotification(message) {
    const notification = document.createElement('div');
    notification.className = 'fixed top-4 right-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-4 rounded-lg shadow-xl z-50 animate-slide-in flex items-center space-x-3';
    notification.innerHTML = `
        <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
            <i class="fas fa-user-plus"></i>
        </div>
        <div class="flex-1">
            <p class="font-semibold text-sm">${message}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200 transition-colors">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

const style = document.createElement('style');
style.textContent = `
    @keyframes slide-in {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    .animate-slide-in {
        animation: slide-in 0.3s ease-out;
    }
`;
document.head.appendChild(style);