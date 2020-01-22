var marker;

function initMap() {
  var map = new google.maps.Map(document.getElementById('map'), {
    zoom: 18,
    center: {lat: 51.618254, lng: -3.937277}
  });

  marker = new google.maps.Marker({
    map: map,
    draggable: true,
    animation: google.maps.Animation.DROP,
    position: {lat: 51.618254, lng: -3.937277}
  });
  marker.addListener('click', toggleBounce);
}

function toggleBounce() {
  if (marker.getAnimation() !== null) {
    marker.setAnimation(null);
  } else {
    marker.setAnimation(google.maps.Animation.BOUNCE);
  }
}
