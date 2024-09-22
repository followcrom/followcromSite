const vinyl = document.getElementById('vinyl');

function togglePlay() {
  vinyl.classList.toggle('play');
}

vinyl.addEventListener('click', togglePlay);

document.addEventListener('keydown', function(event) {
  if (event.key === 'f' || event.key === 'c') {
    togglePlay();
  }
});
