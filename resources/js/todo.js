/*global fetch*/
/*global openTaskEditModal*/ 
/*global closeTaskEditModal*/ 
/*global closeCategoryEditModal*/ 


document.addEventListener('DOMContentLoaded', function() {
    
    
    // チェックボックスの変更を監視
    document.querySelectorAll('.task-checkbox').forEach(function(checkbox) {
        checkbox.addEventListener('change', function(e) {
            let taskId = e.target.getAttribute('data-task-id');
            let isChecked = e.target.checked;

            // Ajaxリクエストを使ってサーバーにデータを送信
            fetch(`/update-task-state/${taskId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    state: isChecked ? 'Done' : 'ToDo'
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log(data);
            })
            .catch(error => {
                console.log('There was a problem with the fetch operation:', error.message);
            });
        });
    });

    // チェックボックスの利用可否を設定する関数
    function checkCheckboxAvailability() {
        let now = new Date();
        let hour = now.getHours();

        let checkboxes = document.querySelectorAll(".task-checkbox");

        if (hour < 6 || hour >= 12) {
            checkboxes.forEach(checkbox => {
                checkbox.disabled = true;
            });
        } else {
            checkboxes.forEach(checkbox => {
                checkbox.disabled = false;
            });
        }
    }

    // 1分ごとにcheckCheckboxAvailability関数を呼び出す
    setInterval(checkCheckboxAvailability, 60000);

    // 初回読み込み時にも実行
    checkCheckboxAvailability();
    
        // ボタンと達成リストのコンテナを取得
    const toggleButton = document.getElementById('toggleCompletedTasks');
    const completedTasksContainer = document.getElementById('completedTasksContainer');

    // ボタンがクリックされたときのイベントリスナーを追加
    toggleButton.addEventListener('click', function() {
        // 達成リストの表示・非表示を切り替え
        if (completedTasksContainer.style.display === 'none') {
            completedTasksContainer.style.display = 'block';
        } else {
            completedTasksContainer.style.display = 'none';
        }
    });
    
    
    
    //カテゴリー畳むトグルボタン
    const toggleCategoryButtons = document.querySelectorAll('.toggleCategory');

    toggleCategoryButtons.forEach(button => {
        button.addEventListener('click', function() {
            const categoryId = button.getAttribute('data-category-id');
            const categoryContent = document.getElementById(`categoryContent-${categoryId}`);

            if (categoryContent.style.display === 'none') {
                categoryContent.style.display = 'block';
                button.textContent = '▼';
            } else {
                categoryContent.style.display = 'none';
                button.textContent = '▶';
            }
        });
    });
    
    
    
    // タスク編集ボタンを押して出てくるモーダルウィンドウの開く処理
    openTaskEditModal = function(taskId, taskTitle) {
        //モーダルを表示
        document.getElementById('taskEditModal').classList.remove('hidden');
        //元のタスク名を表示
        document.getElementById('taskEditTitle').value = taskTitle;
        // フォームのaction属性を設定
        const form = document.getElementById('taskEditForm');
        form.action = `/tasks/${taskId}/edit`;  // このURLは、実際のルート定義に合わせて変更してください        
    }
    
    
    // タスク編集モーダルを閉じる処理
    closeTaskEditModal = function() {
        document.getElementById('taskEditModal').classList.add('hidden');
    }
    
    // タスク編集ボタンのイベントリスナーを追加
    document.querySelectorAll('.edit-button').forEach(button => {
        button.addEventListener('click', function() {
            const taskId = button.getAttribute('data-task-id');
            const taskTitle = button.previousElementSibling.textContent.trim();
            openTaskEditModal(taskId, taskTitle);
        });
    });
    
    //カテゴリー編集モーダル
    function openCategoryEditModal(categoryId, categoryName) {
        //モーダルを表示
        document.getElementById('categoryEditModal').classList.remove('hidden');
        //元のカテゴリー名を表示
        document.getElementById('categoryEditName').value = categoryName;
        //フォームの action属性を設定
        const form = document.getElementById('categoryEditForm');
        form.action = `/categories/${categoryId}/edit`; // ここを修正        
    }
    
    // カテゴリー編集モーダルを閉じる処理
    closeCategoryEditModal = function() {
        document.getElementById('categoryEditModal').classList.add('hidden');
    }


    // カテゴリー編集ボタンのイベントリスナーを追加
    document.querySelectorAll('.edit-category-button').forEach(button => {
        button.addEventListener('click', function() {
            const categoryId = button.getAttribute('data-category-id');
            const categoryName = button.parentElement.querySelector('.category-name').textContent.trim();
            openCategoryEditModal(categoryId, categoryName);
        });
    });
    
    //タスクの編集モーダルのキャンセルボタンのイベントリスナー
    document.querySelector('#taskEditModal button[type="button"]').addEventListener('click', function(event) {
        closeTaskEditModal();
        event.stopPropagation(); // これにより、イベントの伝播が停止します
    });
    
    //カテゴリーの編集モーダルのキャンセルボタンのイベントリスナー
    document.querySelector('#categoryEditModal button[type="button"]').addEventListener('click', function(event) {
        closeCategoryEditModal();
        event.stopPropagation(); // これにより、イベントの伝播が停止します
    });



    // document.getElementById('categoryEditModal').addEventListener('click', function(event) {
    //     event.stopPropagation();
    // });
    
    // document.getElementById('taskEditModal').addEventListener('click', function(event) {
    //     event.stopPropagation();
    // });




    
});


