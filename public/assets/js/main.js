document.getElementById('userSearch').addEventListener('input', function(e) {
    let query = e.target.value;
    let resultsDiv = document.getElementById('searchResults');

    if (query.length < 2) {
        resultsDiv.innerHTML = '';
        return;
    }

    fetch(`index.php?action=search_users&term=${query}`)
        .then(response => response.json())
        .then(data => {
            resultsDiv.innerHTML = '';
            data.forEach(user => {
                resultsDiv.innerHTML += `
                    <div class="user-result" style="display: flex; align-items: center; gap: 10px; padding: 8px; border-bottom: 1px solid #334155;">
                        <div class="user-badge" style="width: 30px; height: 30px; font-size: 0.8rem;">${user.full_name[0]}</div>
                        <div>
                            <div style="font-size: 0.9rem; color: white;">${user.full_name}</div>
                            <div style="font-size: 0.7rem; color: #94a3b8;">@${user.username}</div>
                        </div>
                    </div>
                `;
            });
        });
});