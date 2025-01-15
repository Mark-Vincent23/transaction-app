<?php include 'includes/header.php'; ?>

<div class="container mt-4">
    <h2>Transaction Details</h2>
    <div id="errorMessage" class="alert alert-danger d-none"></div>

    <div class="mb-3">
        <a href="index.php" class="btn btn-secondary">Back to List</a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Product ID</th>
                <th>Product Name</th>
                <th>Amount</th>
                <th>Customer Name</th>
                <th>Status</th>
                <th>Transaction Date</th>
                <th>Created By</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="detailTable">
            <tr>
                <td colspan="9" class="text-center">Loading data...</td>
            </tr>
        </tbody>
    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const ids = urlParams.get('ids');
    
    if (!ids) {
        showError('No transaction IDs provided');
        return;
    }
    
    loadTransactionDetails(ids);
});

function loadTransactionDetails(ids) {
    const tbody = document.getElementById('detailTable');
    
    fetch(`api/transactions.php?ids=${ids}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            tbody.innerHTML = '';

            if (data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="9" class="text-center">No data available</td></tr>';
                return;
            }

            data.forEach(row => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${row.id}</td>
                    <td>${row.productID}</td>
                    <td>${row.productName}</td>
                    <td>$${parseFloat(row.amount).toFixed(2)}</td>
                    <td>${row.customerName}</td>
                    <td>
                        <span class="badge ${row.status === 0 ? 'bg-success' : 'bg-danger'}">
                            ${row.status === 0 ? 'SUCCESS' : 'FAILED'}
                        </span>
                    </td>
                    <td>${formatDateTime(row.transactionDate)}</td>
                    <td>${row.createBy}</td>
                    <td>
                        <button onclick="editTransaction(${row.id})" class="btn btn-warning btn-sm">Edit</button>
                        <button onclick="deleteTransaction(${row.id})" class="btn btn-danger btn-sm">Delete</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        })
        .catch(error => {
            showError(error.message);
        });
}

function formatDateTime(dateString) {
    const date = new Date(dateString);
    return date.toLocaleString();
}

function showError(message) {
    const errorDiv = document.getElementById('errorMessage');
    errorDiv.textContent = `Error: ${message}`;
    errorDiv.classList.remove('d-none');
    
    const tbody = document.getElementById('detailTable');
    tbody.innerHTML = '<tr><td colspan="9" class="text-center">Error loading data</td></tr>';
}

function editTransaction(id) {
    window.location.href = `edit.php?id=${id}`;
}

function deleteTransaction(id) {
    if (confirm('Are you sure you want to delete this transaction?')) {
        fetch(`api/transactions.php?id=${id}`, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(result => {
            if (result.status === 'success') {
                alert('Transaction deleted successfully');
                loadTransactionDetails(ids); // Reload the table
            } else {
                alert('Error: ' + result.message);
            }
        })
        .catch(error => {
            alert('Error: ' + error.message);
        });
    }
}
</script>

<?php include 'includes/footer.php'; ?>