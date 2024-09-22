function showHiddenText(icon, text) {
    icon.addEventListener('mouseover', () => {
      document.getElementById('nav_hover').innerHTML = text;
    });
  }
  
  
  function hideHiddenText(icon) {
    icon.addEventListener('mouseout', () => {
      document.getElementById('nav_hover').innerHTML = 'online';
    });
  }