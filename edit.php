<?php include 'includes/header.php'; ?>

<div class="container mt-4">
    <h2>Edit Transaction</h2>
    <div id="errorMessage" class="alert alert-danger d-none"></div>
    
    <form id="editForm" class="needs-validation" novalidate>
        <input type="hidden" id="transactionId">
        
        <div class="mb-3">
            <label for="productID" class="form-label">Product ID</label>
            <input type="text" class="form-control" id="productID" name="productID" required>
            <div class="invalid-feedback">Please provide a product ID.</div>
        </div>
        
        <div class="mb-3">
            <label for="productName" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="productName" name="productName" required>
            <div class="invalid-feedback">Please provide a product name.</div>
        </div>
        
        <div class="mb-3">
            <label for="amount" class="form-label">Amount</label>
            <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
            <div class="invalid-feedback">Please provide a valid amount.</div>
        </div>
        
        <div class="mb-3">
            <label for="customerName" class="form-label">Customer Name</label>
            <input type="text" class="form-control" id="customerName" name="customerName" required>
            <div class="invalid-feedback">Please provide a customer name.</div>
        </div>
        
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-control" id="status" name="status" required>
                <option value="0">SUCCESS</option>
                <option value="1">FAILED</option>
            </select>
            <div class="invalid-feedback">Please select a status.</div>
        </div>
        
        <div class="mb-3">
            <label for="transactionDate" class="form-label">Transaction Date</label>
            <input type="datetime-local" class="form-control" id="transactionDate" name="transactionDate" required>
            <div class="invalid-feedback">Please provide a transaction date.</div>
        </div>
        
        <button type="submit" class="btn btn-primary">Update Transaction</button>
        <a href="view.php" class="btn btn-secondary" onclick="goBack(event)">Cancel</a>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get transaction ID from URL
    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');
    
    if (!id) {
        showError('No transaction ID provided');
        return;
    }
    
    document.getElementById('transactionId').value = id;
    loadTransactionData(id);
});

async function loadTransactionData(id) {
    try {
        const response = await fetch(`api/transactions.php?id=${id}`);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        if (data.length === 0) {
            throw new Error('Transaction not found');
        }
        
        const transaction = data[0]; // Get first record
        
        // Populate form fields
        document.getElementById('productID').value = transaction.productID;
        document.getElementById('productName').value = transaction.productName;
        document.getElementById('amount').value = transaction.amount;
        document.getElementById('customerName').value = transaction.customerName;
        document.getElementById('status').value = transaction.status;
        
        // Format date for datetime-local input
        const date = new Date(transaction.transactionDate);
        const formattedDate = date.toISOString().slice(0, 16);
        document.getElementById('transactionDate').value = formattedDate;
        
    } catch (error) {
        showError(error.message);
    }
}

document.getElementById('editForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    try {
        const id = document.getElementById('transactionId').value;
        const formData = {
            id: id,
            productID: document.getElementById('productID').value,
            productName: document.getElementById('productName').value,
            amount: document.getElementById('amount').value,
            customerName: document.getElementById('customerName').value,
            status: document.getElementById('status').value,
            transactionDate: document.getElementById('transactionDate').value,
            modifiedBy: 'system'
        };
        
        const response = await fetch('api/transactions.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData)
        });
        
        const result = await response.json();
        
        if (result.status === 'success') {
            alert('Transaction updated successfully!');
            goBack();
        } else {
            showError(result.message || 'Failed to update transaction');
        }
    } catch (error) {
        showError(error.message);
    }
});

function showError(message) {
    const errorDiv = document.getElementById('errorMessage');
    errorDiv.textContent = `Error: ${message}`;
    errorDiv.classList.remove('d-none');
}

function goBack(event) {
    if (event) {
        event.preventDefault();
    }
    // Check if we have a previous page
    if (document.referrer) {
        window.location.href = document.referrer;
    } else {
        window.location.href = 'index.php';
    }
}
</script>

<?php include 'includes/footer.php'; ?>