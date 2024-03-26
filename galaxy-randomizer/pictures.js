document.addEventListener('DOMContentLoaded', function() {
    const images = [
        'galaxy_cluster.jpg', 
        'm_104.jpg',
        'ngc_1300.jpg',
        'interacting_galaxies.jpg',
        'm51.jpg',
        'ngc_6217.jpg'
    ];
    const captions = [
        'Galaxy Cluster',
        'M 104',
        'NGC 1300',
        'Interacting Galaxies',
        'M51',
        'NGC 6217'
    ];

    const index = Math.floor(Math.random() * images.length);

    const imgElement = document.getElementById('randomImage');
    const captionElement = document.getElementById('caption');

    imgElement.src = images[index];
    imgElement.alt = captions[index]; 
    captionElement.textContent = captions[index];
});
