/**
 * Main JavaScript File
 */

// Console log để kiểm tra
console.log('JavaScript loaded successfully!');

// Auto hide alerts sau 5 giây
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
});

/**
 * Format số tiền có dấu chấm ngăn cách nghìn (VD: 6.000, 300.000)
 * Dùng cho input type="text" kèm hidden field name="DonGia"
 */
function formatTienInput(el) {
    var cursorPos = el.selectionStart;
    var oldLen = el.value.length;
    var val = el.value.replace(/[^\d]/g, '');
    var num = parseInt(val) || 0;
    var formatted = num === 0 ? '0' : num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    el.value = formatted;
    // Cập nhật hidden field kế bên (DonGia hoặc bất kỳ hidden nào cùng parent/form)
    var hidden = el.nextElementSibling;
    if (!hidden || hidden.type !== 'hidden') {
        var container = el.closest('.form-group') || el.closest('td') || el.closest('div') || el.parentElement;
        hidden = container.querySelector('input[type="hidden"]');
    }
    if (hidden && hidden.type === 'hidden') hidden.value = num;
    // Giữ vị trí con trỏ hợp lý
    var newLen = formatted.length;
    var newPos = cursorPos + (newLen - oldLen);
    if (newPos < 0) newPos = 0;
    try { el.setSelectionRange(newPos, newPos); } catch(e) {}
}

// Auto format tất cả input có class 'money-input' khi trang load
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.money-input').forEach(function(el) {
        formatTienInput(el);
        el.addEventListener('input', function() { formatTienInput(this); });
        el.addEventListener('blur', function() { formatTienInput(this); });
        el.addEventListener('paste', function() {
            var self = this;
            setTimeout(function() { formatTienInput(self); }, 10);
        });
    });
});
