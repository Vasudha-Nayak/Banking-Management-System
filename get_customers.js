<script>
fetch('get_customers.php')
    .then(response => response.json())
    .then(data => {
        const container = document.querySelector('.balance-container'); // Make sure you have this in your HTML

        data.forEach(customer => {
            const customerRow = document.createElement('div');
            customerRow.classList.add('customer-row');

            customerRow.innerHTML = `
                <div>${customer.customer_id}</div>
                <div>${customer.customer_name}</div>
                <div>${customer.customer_email}</div>
                <div>${customer.account_id}</div>
                <div>${customer.account_type}</div>
                <div>${customer.balance}</div>
            `;

            container.appendChild(customerRow);
        });
    })
    .catch(error => console.error('Error fetching customer data:', error));
</script>
