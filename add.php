<?php include 'includes/header.php'; ?>

<div class="container mt-4">
    <h2>Add New Transaction</h2>
    
    <form id="addForm" class="needs-validation" novalidate>
        <div class="mb-3">
            <label for="productID" class="form-label">Product ID</label>
            <input type="text" class="form-control" id="productID" name="productID" required>
        </div>
        
        <div class="mb-3">
            <label for="productName" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="productName" name="productName" required>
        </div>
        
        <div class="mb-3">
            <label for="amount" class="form-label">Amount</label>
            <input type="number" class="form-control" id="amount" name="amount" required>
        </div>
        
        <div class="mb-3">
            <label for="customerName" class="form-label">Customer Name</label>
            <input type="text" class="form-control" id="customerName" name="customerName" required>
        </div>
        
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-control" id="status" name="status" required>
                <option value="0">SUCCESS</option>
                <option value="1">FAILED</option>
            </select>
        </div>
        
        <div class="mb-3">
            <label for="transactionDate" class="form-label">Transaction Date</label>
            <input type="datetime-local" class="form-control" id="transactionDate" name="transactionDate" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="index.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<script>
document.getElementById('addForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    // Tambahkan logging untuk debug
    console.log('Form submitted');
    
    try {
        const formData = {
            productID: document.getElementById('productID').value,
            productName: document.getElementById('productName').value,
            amount: document.getElementById('amount').value,
            customerName: document.getElementById('customerName').value,
            status: document.getElementById('status').value,
            transactionDate: document.getElementById('transactionDate').value,
            createBy: 'system'
        };
        
        // Log data yang akan dikirim
        console.log('Sending data:', formData);
        
        const response = await fetch('api/transactions.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData)
        });
        
        const result = await response.json();
        console.log('Response:', result);
        
        if(result.status === 'success') {
            alert('Data berhasil disimpan!');
            window.location.href = 'index.php';
        } else {
            alert('Error: ' + (result.message || 'Gagal menyimpan data'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan: ' + error.message);
    }
});
</script>

<?php include 'includes/footer.php'; ?>