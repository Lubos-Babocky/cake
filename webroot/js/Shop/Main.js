function renderCartData(data) {
	document.getElementById('cart-subtotal').textContent = data.subtotal.toFixed(2);
	document.getElementById('cart-taxes').textContent = data.taxes.toFixed(2);
	document.getElementById('cart-total').textContent = data.total.toFixed(2);
	renderCartItemsTable(data.items);
}

function sendRequest(event) {
	fetch(event.target.dataset.url, {
		method: 'GET',
		headers: {
			'Content-Type': 'application/json'
		}
	})
			.then(response => response.json())
			.then(renderCartData)
			.catch(error => console.error('Error loading cart data:', error));
}

function renderCartItemsTable(data) {
	const createCell = (type, content) => {
		const cell = document.createElement(type);
		cell.textContent = content;
		return cell;
	};
	
	const createCellWithButtons = (productId) => {
		const cell = document.createElement('td');
		const decreaseButton = document.createElement('span');
		decreaseButton.textContent = '↓';
		decreaseButton.dataset.url = '/cart/decrease/' + productId;
		decreaseButton.classList.add('cart-button-decrease');
		decreaseButton.addEventListener('click', (event) => sendRequest(event));

		const removeButton = document.createElement('span');
		removeButton.textContent = 'X';
		removeButton.dataset.url = '/cart/remove/' + productId;
		removeButton.classList.add('cart-button-remove');
		removeButton.addEventListener('click', (event) => sendRequest(event));

		cell.appendChild(decreaseButton);
		cell.appendChild(removeButton);
		return cell;
	};
	
	const table = document.createElement('table');
	const thead = document.createElement('thead');
	const headerRow = document.createElement('tr');
	['Title', 'Quantity', 'TAX', 'Price', 'Akcie'].forEach(text => {
		headerRow.appendChild(createCell('th', text));
	});
	thead.appendChild(headerRow);
	table.appendChild(thead);
	const tbody = document.createElement('tbody');
	Object.values(data).forEach(item => {
		const row = document.createElement('tr');
		row.appendChild(createCell('td', item.name));
		row.appendChild(createCell('td', item.cart_quantity));
		row.appendChild(createCell('td', item.vat_rate));
		row.appendChild(createCell('td', parseFloat(item.price).toFixed(2) + '€'));
		if(typeof item.id === 'number') {
			row.appendChild(createCellWithButtons(item.id));
		} else {
			row.appendChild(createCell('td', ''));
		}
		tbody.appendChild(row);
	});
	table.appendChild(tbody);
	const cartItemsTableContainer = document.getElementById('cart-items');
	cartItemsTableContainer.innerHTML = '';
	cartItemsTableContainer.appendChild(table);
}


window.onload = function () {
	fetch('/cart/info', {
		method: 'GET',
		headers: {
			'Content-Type': 'application/json'
		}
	})
			.then(response => response.json())
			.then(renderCartData)
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
				.then(renderCartData)
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
			.then(renderCartData)
			.catch(error => console.error('Error:', error));
});
