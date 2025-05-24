<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support Our Animal Rescue</title>
    <script src="https://www.paypalobjects.com/api/checkout.js"></script>
    <style>
        :root {
            --primary-color:rgb(32, 41, 32);
            --secondary-color:rgb(44, 24, 103);
            --background-color: #f5f5f5;
            --box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--background-color);
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            border-radius: 15px;
            background-color: white;
            box-shadow: var(--box-shadow);
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .header h2 {
            color: var(--primary-color);
            font-size: 32px;
            margin-bottom: 10px;
        }
        
        .header p {
            color: #666;
            font-size: 18px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #444;
        }
        
        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            outline: none;
        }
        
        .donation-options {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .donation-option {
            padding: 10px 20px;
            background-color: #e9e9e9;
            border: 2px solid transparent;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .donation-option:hover, .donation-option.active {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }
        
        .custom-amount {
            display: flex;
            align-items: center;
            margin-top: 15px;
        }
        
        .currency-symbol {
            position: relative;
            left: 25px;
            font-weight: bold;
            color: #555;
        }
        
        #custom-amount-input {
            padding-left: 30px;
            width: 150px;
        }
        
        #paypal-button-container {
            margin-top: 30px;
            text-align: center;
        }
        
        .donate-button {
            background-color: var(--primary-color);
            color: white;
            font-size: 18px;
            font-weight: 600;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: inline-block;
            margin-top: 20px;
        }
        
        .donate-button:hover {
            background-color:rgb(30, 40, 30);
        }
        
        .pet-images {
        display: flex;
        gap: 20px; /* Space between circles */
        justify-content: center; /* Center the circles */
    }
    
    .pet-image {
        width: 150px; /* Adjust size as needed */
        height: 150px; /* Must match width for perfect circle */
        border-radius: 50%; /* Makes it circular */
        overflow: hidden; /* Ensures image stays within circle */
        border: 3px solid #ffb6c1; /* Optional decorative border */
    }
    
    .pet-image img {
        width: 100%;
        height: 100%;
        object-fit: cover; /* Ensures image covers the circle without stretching */
    }
        
        .impact-info {
            background-color: #f9f9f9;
            border-left: 4px solid var(--secondary-color);
            padding: 15px;
            margin-top: 30px;
            border-radius: 5px;
        }
        
        .impact-info h3 {
            color: var(--secondary-color);
            margin-top: 0;
        }
        
        .text-center {
            text-align: center;
        }
        
        /* PayPal button container styles */
        #paypal-button {
            max-width: 350px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Make a Difference Today</h2>
            <p>Your donation helps us rescue, rehabilitate, and rehome animals in need</p>
        </div>
        
        <form id="donation_form">
            <div class="form-group">
                <label for="donation_amount">Choose Donation Amount</label>
                <div class="donation-options">
                    <div class="donation-option" data-amount="25">Rs.2500</div>
                    <div class="donation-option" data-amount="50">Rs.5000</div>
                    <div class="donation-option" data-amount="100">Rs.10000</div>
                    <div class="donation-option" data-amount="200">Rs.20000</div>
                </div>
                <div class="custom-amount">
                    <span class="currency-symbol">Rs.</span>
                    <input type="number" id="custom-amount-input" class="form-control" placeholder="Other amount" min="1" step="1">
                </div>
            </div>
            
            <input type="hidden" id="final_amount" value="50">
            
            <div class="form-group">
                <label for="donation_type">Donation Type</label>
                <select id="donation_type" class="form-control">
                    <option value="one-time">One-time Donation</option>
                    <option value="monthly">Monthly Donation</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="message">Personal Message (Optional)</label>
                <textarea id="message" name="message" class="form-control" rows="3" placeholder="Share why you're supporting our cause..."></textarea>
            </div>
            
            <div class="impact-info">
                <h3>Your Impact</h3>
                <p>Rs.2500 provides vaccines for 5 animals<br>
                Rs.5000 feeds 10 rescued pets for a week<br>
                Rs.10000 covers medical treatment for an injured animal<br>
                Rs.20000 helps fund a special rescue operation</p>
            </div>
            
            <!-- PayPal buttons will be rendered here -->
            <div class="text-center">
                <div id="paypal-button"></div>
            </div>
            
            <!-- Fallback button in case PayPal doesn't load -->
            <div class="text-center" id="fallback-button" style="display: none;">
                <button type="button" class="donate-button" onclick="submitDonation()">Donate Now</button>
            </div>
        </form>
        
        <div class="pet-images">
    <div class="pet-image">
        <img src="uploads\download (1).jpeg" alt="Cute dog">
    </div>
    
    <div class="pet-image">
        <img src="uploads\download.jpeg" alt="Playful cat">
    </div>
    
    <div class="pet-image">
        <img src="uploads\images.jpeg" alt="Fluffy rabbit">
    </div>
</div>
    </div>

    <script>
    // Handle donation amount selection
    const donationOptions = document.querySelectorAll('.donation-option');
    const customAmountInput = document.getElementById('custom-amount-input');
    const finalAmountInput = document.getElementById('final_amount');
    const fallbackButton = document.getElementById('fallback-button');
    
    // Set default amount
    donationOptions[1].classList.add('active'); // $50 default
    
    donationOptions.forEach(option => {
        option.addEventListener('click', function() {
            // Remove active class from all options
            donationOptions.forEach(opt => opt.classList.remove('active'));
            
            // Add active class to clicked option
            this.classList.add('active');
            
            // Set the amount
            finalAmountInput.value = this.dataset.amount;
            
            // Clear custom amount input
            customAmountInput.value = '';
        });
    });
    
    customAmountInput.addEventListener('input', function() {
        if (this.value) {
            // Remove active class from all options
            donationOptions.forEach(opt => opt.classList.remove('active'));
            
            // Set the custom amount
            finalAmountInput.value = this.value;
        } else {
            // If custom amount is cleared, default to $50
            donationOptions[1].classList.add('active');
            finalAmountInput.value = '50';
        }
    });
    
    // Function to handle donation submission with fallback button
    function submitDonation() {
        const amount = document.getElementById('final_amount').value;
        const message = document.getElementById('message').value;
        const donationType = document.getElementById('donation_type').value;
        
        // Redirect to a page that will handle PayPal checkout
        window.location.href = `paypal_checkout.php?amount=${amount}&message=${encodeURIComponent(message)}&type=${donationType}`;
    }
    
    // PayPal integration with error handling
    let paypalButtonRendered = false;
    
    try {
        paypal.Button.render({
            env: 'sandbox', // Change to 'production' for live environment
            client: {
                sandbox: 'AdDNu0ZwC3bqzdjiiQlmQ4BRJsOarwyMVD_L4YQPrQm4ASuBg4bV5ZoH-uveg8K_l9JLCmipuiKt4fxn', // Your sandbox client ID
            },
            commit: true,
            style: {
                color: 'gold',
                size: 'responsive',
                shape: 'rect',
                label: 'paypal'
            },
            payment: function(data, actions) {
                const donationAmount = document.getElementById('final_amount').value;
                const donationType = document.getElementById('donation_type').value;
                
                return actions.payment.create({
                    payment: {
                        transactions: [
                            {
                                amount: {
                                    total: donationAmount,
                                    currency: 'USD' // Changed from PHP to USD, adjust as needed
                                },
                                description: donationType === 'monthly' ? 'Monthly donation to animal rescue' : 'One-time donation to animal rescue'
                            }
                        ]
                    }
                });
            },
            onAuthorize: function(data, actions) {
                return actions.payment.execute().then(function(payment) {
                    // Get the donation details
                    const amount = payment.transactions[0].amount.total;
                    const message = document.getElementById('message').value;
                    const donationType = document.getElementById('donation_type').value;

                    // Redirect to the processing page with payment details
                    window.location.href = `process_donation.php?paymentId=${payment.id}&PayerID=${payment.payer.payer_info.payer_id}&amount=${amount}&message=${encodeURIComponent(message)}&type=${donationType}`;
                });
            },
            onError: function(err) {
                console.error('PayPal Error:', err);
                // Show fallback button if PayPal has an error
                fallbackButton.style.display = 'block';
            }
        }, '#paypal-button');
        
        paypalButtonRendered = true;
    } catch (err) {
        console.error('Error rendering PayPal button:', err);
        // Show fallback button if PayPal script fails to load or render
        fallbackButton.style.display = 'block';
    }
    
    // Show fallback button after a timeout if PayPal doesn't load
    setTimeout(function() {
        if (!paypalButtonRendered) {
            fallbackButton.style.display = 'block';
        }
    }, 3000);
    </script>
</body>
</html>