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

window.Echo.private(`user.messages.${currentUserId}`)
    .listen('MessageSent', (event) => {
        console.log('📩 New message received:', event);

        showMessageNotification(
            event.message.user.first_name,
            event.message.body,
            event.message.conversation_id
        );
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

function showMessageNotification(senderName, message, conversationId) {
    const notification = document.createElement('div');

    notification.className = `
        fixed bottom-4 right-4 
        bg-white text-gray-800 
        px-5 py-4 rounded-xl shadow-2xl 
        z-50 w-80 border border-gray-200
        animate-slide-in cursor-pointer
    `;

    notification.innerHTML = `
        <div class="flex items-start space-x-3">
            <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                <i class="fas fa-comment text-indigo-600"></i>
            </div>
            <div class="flex-1">
                <p class="font-semibold text-sm">${senderName}</p>
                <p class="text-sm text-gray-600 truncate">${message}</p>
            </div>
            <button class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;

    notification.addEventListener('click', (e) => {
        if (!e.target.closest('button')) {
            window.location.href = `/messages/${conversationId}`;
        } else {
            notification.remove();
        }
    });

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateY(20px)';
        setTimeout(() => notification.remove(), 300);
    }, 6000);
}
