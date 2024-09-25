window.onload = function() {
    fetch('/cart/info', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('cart-subtotal').textContent = data.subtotal.toFixed(2);
        document.getElementById('cart-taxes').textContent = data.taxes.toFixed(2);
        document.getElementById('cart-total').textContent = data.total.toFixed(2);
    })
    .catch(error => console.error('Error loading cart data:', error));
};

document.querySelectorAll('.add-to-cart').forEach(button => {
	button.addEventListener('click', function () {
		const productId = this.getAttribute('data-id');
		fetch(`/cart/add-to-cart/${productId}`, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/json',
				'X-CSRF-Token': document.querySelector('#csrfToken').getAttribute('data-token')
			}
		})
				.then(response => response.json())
				.then(data => {
					document.getElementById('cart-subtotal').innerText = data.subtotal.toFixed(2);
					document.getElementById('cart-taxes').innerText = data.taxes.toFixed(2);
					document.getElementById('cart-total').innerText = data.total.toFixed(2);
				})
				.catch(error => console.error('Error:', error));
	});
});

document.getElementById('clear-cart').addEventListener('click', function () {
	fetch('/cart/clear-basket', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
			'X-CSRF-Token': document.querySelector('#csrfToken').getAttribute('data-token')
		}
	})
			.then(response => response.json())
			.then(data => {
				document.getElementById('cart-subtotal').innerText = data.subtotal.toFixed(2);
				document.getElementById('cart-taxes').innerText = data.taxes.toFixed(2);
				document.getElementById('cart-total').innerText = data.total.toFixed(2);
			})
			.catch(error => console.error('Error:', error));
});
