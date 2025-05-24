/**
 * Helper functions for rendering maps in the PawParivaar application
 */

// Function to initialize a map with a marker at specific coordinates
function initMapWithMarker(elementId, lat, lng, popupText) {
    // Create map instance
    var map = L.map(elementId).setView([lat, lng], 15);
    
    // Add OpenStreetMap tile layer
    L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
    
    // Add marker at specified location
    var marker = L.marker([lat, lng]).addTo(map);
    
    // Add popup if text is provided
    if (popupText) {
        marker.bindPopup(popupText).openPopup();
    }
    
    // Return map instance for further manipulation if needed
    return {
        map: map,
        marker: marker
    };
}

// Function to resize map when inside a modal or tab
function resizeMapInContainer(map) {
    setTimeout(function() {
        map.invalidateSize();
    }, 100);
}

// Function to convert address to coordinates using Nominatim
function geocodeAddress(address) {
    return new Promise((resolve, reject) => {
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    resolve({
                        lat: parseFloat(data[0].lat),
                        lng: parseFloat(data[0].lon),
                        display_name: data[0].display_name
                    });
                } else {
                    reject(new Error('Location not found'));
                }
            })
            .catch(error => reject(error));
    });
}
