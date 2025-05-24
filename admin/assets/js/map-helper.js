/**
 * Helper functions for rendering maps in modals
 */

// Function to properly render Leaflet maps in Bootstrap modals
function initMapInModal(mapId, latitude, longitude, popupText, zoom = 15) {
    // Create map instance but don't immediately render it
    let map = null;
    
    // Function to initialize the map after the modal is fully visible
    function setupMap() {
        // If map already initialized, just resize it
        if (map) {
            map.invalidateSize();
            return;
        }
        
        // Create the map
        map = L.map(mapId, {
            center: [latitude, longitude],
            zoom: zoom,
            scrollWheelZoom: false
        });
        
        // Add the OpenStreetMap tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            maxZoom: 19
        }).addTo(map);
        
        // Add a marker at the specified location
        var marker = L.marker([latitude, longitude]).addTo(map);
        
        // Add popup if text is provided
        if (popupText) {
            marker.bindPopup(popupText).openPopup();
        }
        
        // Handle window resize
        window.addEventListener('resize', function() {
            if (map) map.invalidateSize();
        });
    }
    
    // Setup listeners for modal events
    $('.modal').on('shown.bs.modal', function() {
        setTimeout(setupMap, 300);
    });
    
    // Return control functions
    return {
        resize: function() {
            if (map) map.invalidateSize();
        },
        center: function() {
            if (map) map.setView([latitude, longitude], zoom);
        }
    };
}
