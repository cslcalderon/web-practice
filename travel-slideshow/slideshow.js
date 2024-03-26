let images = [
    "agra_fort.jpg",
    "ajanta_ellora.jpg",
    "akshardham_temple.jpg",
    "gateway_of_india.jpg",
    "hawa_mahal.jpg",
    "mehrangarh_fort.jpg",
    "mysore_palace.jpg",
    "qutub_minar.jpg",
    "sun_temple.jpg",
    "taj_mahal.jpg",
    "victoria_memorial.jpg"
];

let currentIndex = 0;
let interval;

function startSlideshow() {
    interval = setInterval(() => {
        document.getElementById("slide").src = images[currentIndex];
        currentIndex = (currentIndex + 1) % images.length;
    }, 3000); 
}

function stopSlideshow() {
    clearInterval(interval);
}

document.getElementById("startBtn").onclick = startSlideshow;
document.getElementById("endBtn").onclick = stopSlideshow;

window.onload = startSlideshow; 
