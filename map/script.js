var map = L.map('map').setView([27.699929, 85.319628], 12);

L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

var markers = [];
var control = null;
var currentMarker = null; // This will hold the current marker being added
var isDestinationSelected = false; // To track if the destination has been set
var currentLocation = null; // Store location coordinates for rescue form

// Search for a location (address or street name)
function searchLocation() {
    var location = document.getElementById('searchBox').value;
    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${location}`)
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                var lat = data[0].lat;
                var lon = data[0].lon;
                map.setView([lat, lon], 15);
                currentMarker = L.marker([lat, lon]).addTo(map);
                currentMarker.bindPopup(`You can add a name for this marker.`).openPopup();
                
                // Store current location
                currentLocation = {
                    lat: lat,
                    lon: lon,
                    display_name: data[0].display_name
                };

                // Enable the "Save Marker" button after search result
                document.getElementById('saveMarker').disabled = false;
                document.getElementById('markerName').value = ""; // Clear previous input
            } else {
                alert('Location not found');
            }
        });
}

// Calculate route between two locations
function calculateRoute() {
    var start = document.getElementById('startLocation').value;
    var end = document.getElementById('destinationLocation').value;

    if (!start || !end) {
        alert('Please enter both start and destination locations');
        return;
    }

    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${start}`)
        .then(response => response.json())
        .then(startData => {
            if (startData.length === 0) {
                alert('Start location not found');
                return;
            }
            var startCoords = [startData[0].lat, startData[0].lon];

            return fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${end}`)
                .then(response => response.json())
                .then(endData => {
                    if (endData.length === 0) {
                        alert('Destination location not found');
                        return;
                    }
                    var endCoords = [endData[0].lat, endData[0].lon];

                    if (control) {
                        map.removeControl(control);
                    }
                    control = L.Routing.control({
                        waypoints: [
                            L.latLng(startCoords[0], startCoords[1]),
                            L.latLng(endCoords[0], endCoords[1])
                        ],
                        routeWhileDragging: true,
                        createMarker: function() { return null; } // Hide markers
                    }).addTo(map);

                    // Add markers for start and end locations
                    L.marker(startCoords).addTo(map).bindPopup("Start Location").openPopup();
                    L.marker(endCoords).addTo(map).bindPopup("Destination Location").openPopup();

                    // Store destination location
                    currentLocation = {
                        lat: endCoords[0],
                        lon: endCoords[1],
                        display_name: end
                    };

                    // Enable saving of the destination marker
                    isDestinationSelected = true;
                    document.getElementById('saveMarker').disabled = false;
                    document.getElementById('markerName').value = ""; // Clear previous input
                    currentMarker = L.marker(endCoords).addTo(map);
                    currentMarker.bindPopup("You can add a name for this destination marker.").openPopup();
                });
        });
}

// Handle map click to add a marker after adding name
map.on('click', function(e) {
    // Place a marker on the clicked location
    currentMarker = L.marker(e.latlng).addTo(map);
    currentMarker.bindPopup(`You can add a name for this marker.`).openPopup();
    
    // Store current location
    currentLocation = {
        lat: e.latlng.lat,
        lon: e.latlng.lng,
        display_name: "Selected point"
    };

    // Enable the "Save Marker" button
    document.getElementById('saveMarker').disabled = false;
    document.getElementById('markerName').value = ""; // Clear previous input
});

// Save the marker when the "Save Marker" button is clicked
function saveMarker() {
    var markerName = document.getElementById('markerName').value.trim();
    if (!markerName) {
        alert('Please enter a marker name');
        return;
    }

    if (currentMarker) {
        currentMarker.bindPopup(markerName).openPopup();
        markers.push({ 
            name: markerName, 
            coords: [currentMarker.getLatLng().lat, currentMarker.getLatLng().lng], 
            marker: currentMarker 
        });

        // Store the location name
        if (currentLocation) {
            currentLocation.name = markerName;
        }

        // Add to the marked locations list
        var listItem = document.createElement('li');
        listItem.innerHTML = `
            <div>
                <span>${markerName}</span>
                <button onclick="removeMarker(${markers.length - 1})">Remove</button>
                <button onclick="seeMarker(${markers.length - 1})">See</button>
                <button onclick="initiateRescue(${markers.length - 1})">Rescue</button>
            </div>
        `;
        document.getElementById('markedLocations').appendChild(listItem);

        // Disable the "Save Marker" button and reset marker input field
        document.getElementById('saveMarker').disabled = true;
        document.getElementById('markerName').value = "";
        
        // Show rescue option
        showRescueOption(markerName, currentMarker.getLatLng().lat, currentMarker.getLatLng().lng);
    }
}

// Show rescue option modal
function showRescueOption(locationName, lat, lng) {
    // Create modal for rescue option
    const modal = document.createElement('div');
    modal.classList.add('rescue-modal');
    modal.innerHTML = `
        <div class="rescue-modal-content">
            <h3>Apply for a Rescue Operation?</h3>
            <p>Would you like to request a rescue operation at ${locationName}?</p>
            <div class="rescue-buttons">
                <button onclick="redirectToRescueForm('${locationName}', ${lat}, ${lng})">Yes</button>
                <button onclick="closeRescueModal()">No</button>
            </div>
        </div>
    `;
    document.body.appendChild(modal);
}

// Close the rescue modal
function closeRescueModal() {
    const modal = document.querySelector('.rescue-modal');
    if (modal) {
        document.body.removeChild(modal);
    }
}

// Redirect to the rescue form with location information
function redirectToRescueForm(locationName, lat, lng) {
    // Redirect to rescue form with location parameters
    window.location.href = `rescue-form.php?location=${encodeURIComponent(locationName)}&lat=${lat}&lng=${lng}`;
}

// Initiate rescue from the marked locations list
function initiateRescue(index) {
    var marker = markers[index];
    redirectToRescueForm(marker.name, marker.coords[0], marker.coords[1]);
}

// Remove a marker
function removeMarker(index) {
    var markerToRemove = markers[index].marker;
    map.removeLayer(markerToRemove);
    markers.splice(index, 1); // Remove from array

    // Remove from the UI
    document.getElementById('markedLocations').removeChild(document.getElementById('markedLocations').children[index]);
}

// "See" the marker's location on the map
function seeMarker(index) {
    var marker = markers[index];
    map.setView(marker.coords, 15); // Center the map to this marker's location
    marker.marker.openPopup(); // Open the marker's popup
}

// Close the marker container and redirect
function closeMarkerContainer() {
    document.querySelector('.marked-container').style.display = 'none';
    // Redirect to localhost/pawparivaar
    window.location.href = 'http://localhost/pawparivaar';
}