<div id="chatbot-widget">
    <button id="chatbot-toggle" aria-label="Chat tư vấn">
        <span class="icon-open">💬</span>
        <span class="icon-close" style="display: none;">×</span>
    </button>

    <div id="chatbot-panel" class="chatbot-hidden">
        <div class="chatbot-header">
            <div class="d-flex align-items-center">
                <span style="font-size: 20px; margin-right: 8px;">🤖</span>
                <div>
                    <strong style="display: block; line-height: 1.2;">KidolShop AI</strong>
                    <small style="font-weight: normal; opacity: 0.9;">Hỗ trợ 24/7</small>
                </div>
            </div>
            <button type="button" id="chatbot-close" title="Đóng chat">×</button>
        </div>

        <div id="chatbot-messages">
            <div class="chatbot-message chatbot-bot">
                Chào bạn! Mình là AI hỗ trợ của KidolShop. <br>
                Bạn muốn tìm sản phẩm nào (áo, quần, giày...) hoặc hỏi về giá cả, ship hàng cứ nhắn mình nhé!
            </div>
        </div>

        <div id="chatbot-loading" style="display: none;">
            <div class="typing-indicator">
                <span></span><span></span><span></span>
            </div>
        </div>

        <form id="chatbot-form">
            @csrf
            <input type="text" id="chatbot-input" placeholder="Hỏi về sản phẩm, giá..." autocomplete="off" required>
            <button type="submit">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="22" y1="2" x2="11" y2="13"></line>
                    <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                </svg>
            </button>
        </form>
    </div>
</div>

<style>
    /* --- CẤU TRÚC CHÍNH --- */
    #chatbot-widget {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 10000;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    }

    /* Nút Toggle Tròn */
    #chatbot-toggle {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        border: none;
        background: #ff6f61;
        color: #fff;
        font-size: 28px;
        box-shadow: 0 4px 15px rgba(255, 111, 97, 0.4);
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #chatbot-toggle:hover {
        transform: scale(1.1);
    }

    /* Panel Chat (Khung chính) */
    #chatbot-panel {
        width: 350px;
        height: 500px;
        max-height: 80vh;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 5px 40px rgba(0, 0, 0, 0.16);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        position: absolute;
        bottom: 80px;
        right: 0;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        opacity: 1;
        transform: translateY(0) scale(1);
        transform-origin: bottom right;
    }

    /* Trạng thái ẩn */
    #chatbot-panel.chatbot-hidden {
        opacity: 0;
        transform: translateY(20px) scale(0.9);
        pointer-events: none;
    }

    /* Header */
    .chatbot-header {
        background: linear-gradient(135deg, #ff6f61, #ff8a75);
        color: #fff;
        padding: 15px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    #chatbot-close {
        background: none;
        border: none;
        color: #fff;
        font-size: 24px;
        cursor: pointer;
        opacity: 0.8;
    }

    #chatbot-close:hover {
        opacity: 1;
    }

    /* Vùng tin nhắn */
    #chatbot-messages {
        padding: 15px;
        overflow-y: auto;
        flex: 1;
        background-color: #f9f9f9;
        scroll-behavior: smooth;
    }

    /* Bong bóng tin nhắn */
    .chatbot-message {
        padding: 10px 15px;
        border-radius: 18px;
        margin-bottom: 12px;
        line-height: 1.5;
        font-size: 14px;
        max-width: 85%;
        word-wrap: break-word;
        position: relative;
    }

    /* Bot Message */
    .chatbot-bot {
        background: #fff;
        color: #333;
        border-bottom-left-radius: 4px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        border: 1px solid #e0e0e0;
        margin-right: auto;
    }

    /* User Message */
    .chatbot-user {
        background: #ff6f61;
        color: #fff;
        border-bottom-right-radius: 4px;
        margin-left: auto;
        text-align: right;
        box-shadow: 0 2px 5px rgba(255, 111, 97, 0.3);
    }

    /* Form nhập liệu */
    #chatbot-form {
        display: flex;
        gap: 10px;
        padding: 15px;
        background: #fff;
        border-top: 1px solid #eee;
    }

    #chatbot-input {
        flex: 1;
        border: 1px solid #ddd;
        border-radius: 25px;
        padding: 10px 15px;
        font-size: 14px;
        outline: none;
        transition: border 0.3s;
    }

    #chatbot-input:focus {
        border-color: #ff6f61;
    }

    #chatbot-form button {
        border: none;
        background: #ff6f61;
        color: #fff;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.3s;
    }

    #chatbot-form button:hover {
        background: #e65b50;
    }

    /* --- PHẦN GỢI Ý SẢN PHẨM (PRODUCT CARD) --- */
    .suggestion-box {
        background: #fff;
        border: 1px solid #eee;
        border-radius: 12px;
        padding: 10px;
        margin-top: 8px;
        margin-bottom: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
    }

    .suggestion-header {
        font-size: 12px;
        color: #888;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 700;
    }

    .suggestion-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px dashed #eee;
    }

    .suggestion-item:last-child {
        border-bottom: none;
    }

    .suggestion-info {
        flex: 1;
        padding-right: 10px;
    }

    .s-title {
        display: block;
        font-weight: 600;
        color: #333;
        text-decoration: none;
        font-size: 13px;
        margin-bottom: 2px;
    }

    .s-title:hover {
        color: #ff6f61;
    }

    .s-price {
        font-size: 12px;
        color: #d32f2f;
        font-weight: bold;
    }

    .s-btn {
        font-size: 11px;
        background: #f0f0f0;
        color: #333;
        padding: 4px 10px;
        border-radius: 4px;
        text-decoration: none;
        white-space: nowrap;
        transition: all 0.2s;
    }

    .s-btn:hover {
        background: #ff6f61;
        color: #fff;
    }

    /* --- HIỆU ỨNG LOADING (3 CHẤM) --- */
    #chatbot-loading {
        padding: 10px 15px;
        background: #fff;
        border-radius: 18px;
        border-bottom-left-radius: 4px;
        width: fit-content;
        margin-bottom: 10px;
        border: 1px solid #e0e0e0;
    }

    .typing-indicator span {
        display: inline-block;
        width: 6px;
        height: 6px;
        background-color: #ccc;
        border-radius: 50%;
        animation: typing 1.4s infinite both;
        margin: 0 2px;
    }

    .typing-indicator span:nth-child(1) {
        animation-delay: 0s;
    }

    .typing-indicator span:nth-child(2) {
        animation-delay: 0.2s;
    }

    .typing-indicator span:nth-child(3) {
        animation-delay: 0.4s;
    }

    @keyframes typing {

        0%,
        80%,
        100% {
            transform: scale(0);
        }

        40% {
            transform: scale(1);
        }
    }

    /* Responsive Mobile */
    @media (max-width: 480px) {
        #chatbot-panel {
            width: 90%;
            right: 5%;
            bottom: 90px;
            height: 60vh;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const panel = document.getElementById('chatbot-panel');
        const toggle = document.getElementById('chatbot-toggle');
        const closeBtn = document.getElementById('chatbot-close');
        const form = document.getElementById('chatbot-form');
        const input = document.getElementById('chatbot-input');
        const messages = document.getElementById('chatbot-messages');
        const loading = document.getElementById('chatbot-loading');
        const token = form.querySelector('input[name="_token"]').value;

        // Hàm cuộn xuống cuối
        const scrollToBottom = () => {
            messages.scrollTop = messages.scrollHeight;
        };

        // Hàm xử lý bật/tắt Chat
        const toggleChat = () => {
            const isHidden = panel.classList.contains('chatbot-hidden');
            if (isHidden) {
                panel.classList.remove('chatbot-hidden');
                input.focus();
                // Đổi icon thành X
                toggle.querySelector('.icon-open').style.display = 'none';
                toggle.querySelector('.icon-close').style.display = 'inline';
            } else {
                panel.classList.add('chatbot-hidden');
                // Đổi icon thành Chat
                toggle.querySelector('.icon-open').style.display = 'inline';
                toggle.querySelector('.icon-close').style.display = 'none';
            }
        };

        toggle.addEventListener('click', toggleChat);
        closeBtn.addEventListener('click', () => {
            panel.classList.add('chatbot-hidden');
            toggle.querySelector('.icon-open').style.display = 'inline';
            toggle.querySelector('.icon-close').style.display = 'none';
        });

        // Hàm thêm tin nhắn vào giao diện
        const appendMessage = (text, sender, suggestions = []) => {
            const div = document.createElement('div');
            div.className = `chatbot-message chatbot-${sender}`;

            // Xử lý xuống dòng cho đẹp
            div.innerHTML = text.replace(/\n/g, '<br>');
            messages.appendChild(div);

            // Nếu có danh sách sản phẩm gợi ý
            if (suggestions && suggestions.length > 0) {
                const suggestionBox = document.createElement('div');
                suggestionBox.className = 'suggestion-box';

                let html = `<div class="suggestion-header">Sản phẩm liên quan:</div>`;

                suggestions.forEach(item => {
                    html += `
                <div class="suggestion-item">
                    <div class="suggestion-info">
                        <a href="${item.url}" target="_blank" class="s-title">${item.title}</a>
                        <span class="s-price">${item.price || 'Liên hệ'}</span>
                    </div>
                    <a href="${item.url}" target="_blank" class="s-btn">Xem</a>
                </div>`;
                });

                suggestionBox.innerHTML = html;
                messages.appendChild(suggestionBox);
            }

            scrollToBottom();
        };

        // Xử lý khi gửi form
        form.addEventListener('submit', async e => {
            e.preventDefault();
            const message = input.value.trim();
            if (!message) return;

            // 1. Hiện tin nhắn người dùng
            appendMessage(message, 'user');
            input.value = '';
            input.focus();

            // 2. Hiện hiệu ứng đang nhập
            loading.style.display = 'block';
            messages.appendChild(loading); // Đẩy loading xuống cuối
            scrollToBottom();

            try {
                const response = await fetch("{{ route('chatbot.suggest') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        message
                    })
                });

                // 3. Ẩn hiệu ứng loading
                loading.style.display = 'none';
                // Trả loading về vị trí cũ (để lần sau dùng tiếp) hoặc chỉ cần ẩn đi là được vì appendChild đã di chuyển nó

                if (!response.ok) throw new Error('Server Error');

                const data = await response.json();

                // 4. Hiện phản hồi từ AI
                appendMessage(data.reply, 'bot', data.suggestions);

            } catch (error) {
                loading.style.display = 'none';
                appendMessage('Hệ thống đang bận một chút, bạn thử lại sau giây lát nhé!', 'bot');
                console.error(error);
            }
        });
    });
</script>