function showPopup(productID) {
    const popup = document.getElementById('popup');
    popup.style.display = 'block';

    fetch(`/productListing.php?productID=${productID}`)
        .then(response => response.json())
        .then(data => {
            const detailsContainer = document.getElementById('product-details');
            const priceDisplay = document.getElementById('product-price');
            const quantityInput = document.getElementById('quantity-input');
            
            detailsContainer.innerHTML = ''; // Clear existing options

            let minPrice = Infinity;
            let maxPrice = -Infinity;

            // Store unique values for each key
            const uniqueDetails = {};

            // Populate variations in the dropdown
            data.forEach(variation => {
                const details = JSON.parse(variation.details);
                // Dynamically build the details string
                for (const [key, value] of Object.entries(details)) {
                    if (!uniqueDetails[key]) {
                        uniqueDetails[key] = new Set();
                    }
                    uniqueDetails[key].add(value);
                }

                // Track the minimum and maximum prices for range display
                minPrice = Math.min(minPrice, variation.price);
                maxPrice = Math.max(maxPrice, variation.price);
            });

            // Dynamically create dropdowns for each key in the details JSON
            for (const [key, values] of Object.entries(uniqueDetails)) {
                const label = document.createElement('label');
                label.htmlFor = `${key}-select`;
                label.textContent = `${key}:`;

                const select = document.createElement('select');
                select.id = `${key}-select`;
                select.dataset.key = key; // Store the key for later reference

                // Populate the dropdown with unique values
                values.forEach(value => {
                    const option = document.createElement('option');
                    option.value = value;
                    option.textContent = value;
                    select.appendChild(option);
                });

                // Append the label and dropdown to the details container
                detailsContainer.appendChild(label);
                detailsContainer.appendChild(select);
            }

            // Set the product name, description, and default price range
            document.getElementById('product-name').innerText = data[0].productName;
            document.getElementById('product-description').innerText = data[0].productDesc;
            priceDisplay.innerText = `Price: RM ${minPrice.toFixed(2)} - RM ${maxPrice.toFixed(2)}`;

            function updatePrice() {
                const selectedOptions = {};
                const selects = detailsContainer.querySelectorAll('select');

                // Collect selected values for all dropdowns
                selects.forEach(select => {
                    selectedOptions[select.dataset.key] = select.value;
                });

                // Find the matching variation
                const selectedVariation = data.find(variation => {
                    const details = JSON.parse(variation.details);
                    return Object.entries(selectedOptions).every(([key, value]) => details[key] === value);
                });

                if (selectedVariation) {
                    const selectedPrice = selectedVariation.price;
                    const totalPrice = selectedPrice * quantityInput.value;

                    priceDisplay.innerText = `Price: RM ${selectedPrice.toFixed(2)} x ${quantityInput.value} = RM ${totalPrice.toFixed(2)}`;
                }
            }

            // Attach event listeners to dropdowns and quantity input
            detailsContainer.querySelectorAll('select').forEach(select => {
                select.addEventListener('change', updatePrice);
            });
            quantityInput.addEventListener('input', updatePrice);

            // Initialize the price on page load (in case the first variation is selected)
            updatePrice();
        })
        .catch(error => {
            console.error('Error fetching product variations:', error);
        });
}



// closing pop up
document.getElementById('close-popup').addEventListener('click', function () {
    document.getElementById('popup').style.display = 'none';
});

// Add product to cart
function submitCart() {
    const detailsContainer = document.getElementById('product-details');
    const quantity = document.getElementById('quantity-input').value;
    const selectedOptions = {};

    detailsContainer.querySelectorAll('select').forEach(select => {
        selectedOptions[select.dataset.key] = select.value;
    });

    alert(`Product added to cart!\nDetails: ${JSON.stringify(selectedOptions)}\nQuantity: ${quantity}`);

    // Close the popup after adding
    document.getElementById('popup').style.display = 'none';
}