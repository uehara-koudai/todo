/*global fetch*/


document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.task-checkbox').forEach(function(checkbox) {
        checkbox.addEventListener('change', function(e) {
            let taskId = e.target.getAttribute('data-task-id');
            let isChecked = e.target.checked;

            // ここでAjaxリクエストを使ってサーバーにデータを送信
            fetch(`/update-task-state/${taskId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // この行を追加
                },
                body: JSON.stringify({
                    state: isChecked ? 'Done' : 'ToDo'
                })
            });
        });
    });
});
