var Bars = {
  map: undefined,
  markers: [],
  neptunFountainLatitude: 54.34853,
  neptunFountainLongitude: 18.65324,
  bounds: new google.maps.LatLngBounds(),
  infoWindow: new google.maps.InfoWindow(),
  btnMyPositionSelector: '#btn-my-position',
  btnNeptunFountainSelector: '#btn-neptun-fountain',
  defaultIcon: 'http://maps.google.com/mapfiles/ms/icons/green-dot.png',
  apiUrl: '/api/v1/bars',

  init: function() {
    this.setMap();
    this.bindButtons();
  },
  setMap: function() {
    var $this = this;
    this.map = new google.maps.Map(document.getElementById('map'), {
      center: this.createLocation(this.neptunFountainLatitude, this.neptunFountainLongitude),
      zoom: 15
    });
  },
  bindButtons: function() {
    this.bindNearByNeptunButton();
    this.bindNearMeButton();
  },
  bindNearByNeptunButton: function() {
    $(this.btnNeptunFountainSelector).click($.proxy(this.findBarsNearbyNeptun, this));
  },
  bindNearMeButton: function() {
    $(this.btnMyPositionSelector).click($.proxy(this.findBarsNearMe, this));
  },
  findBarsNearbyNeptun: function() {
    this.removeMarkers();
    this.createMarker(this.neptunFountainLatitude, this.neptunFountainLongitude);
    this.search(this.apiUrl);
  },
  findBarsNearMe: function() {
    this.removeMarkers();
    this.searchByGeoLocation();
  },
  removeMarkers: function() {
    for (i = 0; i < this.markers.length; i++) {
      this.markers[i].setMap(null);
    }
  },
  createLocation: function(lat, lng) {
    return new google.maps.LatLng(lat, lng);
  },
  createMarker: function(lat, lng, icon) {
    var marker = new google.maps.Marker({
      position: this.createLocation(lat, lng),
      map: this.map,
      icon: icon ? icon : this.defaultIcon
    });
    this.markers.push(marker);
    this.bounds.extend(marker.position);
    return marker;
  },
  googleIcon: function(url) {
    return {
      url: url,
      scaledSize: new google.maps.Size(40, 40),
      origin: new google.maps.Point(0,0),
      anchor: new google.maps.Point(0, 0)
    };
  },
  googleListener: function(item, marker, i) {
    var $this = this;
    google.maps.event.addListener(marker, 'click', (function(marker, i) {
      return function() {
        $this.infoWindow.setContent('<div><strong>' + item['name'] + '</strong><br/>' + item['vicinity'] + '</div>');
        $this.infoWindow.open($this.map, marker);
      }
    })(marker, i));
  },
  search: function(url) {
    var $this = this;
    $.get(url, function(response) {
      if (response.code !== 200) {
        $this.showApiError();
        return false;
      }
      $this.processDataResults(response.results);
      $this.map.fitBounds($this.bounds);
    });
  },
  processDataResults: function(data) {
    for (var i = 0; i < data.length; i++) {
      var item = data[i];
      var marker = this.createMarker(item['geometry']['location']['lat'], item['geometry']['location']['lng'], this.googleIcon(item['icon']));
      this.googleListener(item, marker, i);
    }
  },
  searchByGeoLocation: function() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition($.proxy(this.searchByLocation, this), $.proxy(this.locationError, this), {timeout:60000});
    } else {
      this.showError('Your browser doesn\'t support Geolocation.');
      this.disableButton(this.btnMyPositionSelector);
    }
  },
  searchByLocation: function(position) {
    var lat = position.coords.latitude;
    var lng = position.coords.longitude;
    this.createMarker(lat, lng);
    this.search(this.apiUrl + '/' + lat + '/' + lng);
  },
  locationError: function() {
    this.showError('The Geolocation service failed.');
    this.disableButton(this.btnMyPositionSelector);
  },
  showApiError: function() {
    this.showError('Application failed. Internal server error.');
    this.disableButton(this.btnMyPositionSelector);
    this.disableButton(this.btnNeptunFountainSelector);
  },
  showError: function(message) {
    $('#error').html(message).show();
  },
  disableButton: function(selector) {
    $(selector).attr('disabled', true);
  }
};

$(document).ready(function() {
  Bars.init();
});
