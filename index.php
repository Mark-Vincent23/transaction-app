<?php include 'includes/header.php'; ?>

<div class="container mt-4">
    <h2>Transaction List</h2>
    <a href="add.php" class="btn btn-primary mb-3">Add New Transaction</a>
    
    <div id="errorMessage" class="alert alert-danger d-none"></div>
    
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Year</th>
                <th>Month</th>
                <th>Total Transactions</th>
                <th>Total Amount</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="transactionTable">
            <tr>
                <td colspan="5" class="text-center">Loading data...</td>
            </tr>
        </tbody>
    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadTransactions();
});

function loadTransactions() {
    const tbody = document.getElementById('transactionTable');
    const errorDiv = document.getElementById('errorMessage');
    
    fetch('api/transactions.php')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text(); // First get the raw text
        })
        .then(text => {
            if (!text) {
                throw new Error('Empty response received');
            }
            console.log('Raw response:', text); // Debug log
            return JSON.parse(text); // Then parse it as JSON
        })
        .then(data => {
            console.log('Parsed data:', data);
            tbody.innerHTML = ''; // Clear loading message

            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center">No data available</td></tr>';
                return;
            }

            data.forEach(row => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${row.year}</td>
                    <td>${getMonthName(row.month)}</td>
                    <td>${row.total_transactions}</td>
                    <td>$${parseFloat(row.total_amount).toFixed(2)}</td>
                    <td>
                        <button onclick="viewTransactions('${row.transaction_ids}')" class="btn btn-info btn-sm">View Details</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
            
            errorDiv.classList.add('d-none');
        })
        .catch(error => {
            console.error('Error:', error);
            errorDiv.textContent = `Error loading data: ${error.message}`;
            errorDiv.classList.remove('d-none');
            tbody.innerHTML = '<tr><td colspan="5" class="text-center">Error loading data</td></tr>';
        });
}

function getMonthName(month) {
    const months = ['January', 'February', 'March', 'April', 'May', 'June', 
                   'July', 'August', 'September', 'October', 'November', 'December'];
    return months[month - 1];
}

function viewTransactions(ids) {
    window.location.href = `view.php?ids=${ids}`;
}
</script>

<?php include 'includes/footer.php'; ?>