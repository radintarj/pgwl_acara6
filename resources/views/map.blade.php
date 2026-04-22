@extends('layouts.template')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css">

<style>
#map{
    height:800px;
}
</style>
@endsection


@section('content')

<div id="map"></div>

{{-- Modal Input Point --}}
<div class="modal" tabindex="-1" id="modalInputPoint">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Input Point</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('points.store') }}" method="post">
            @csrf

            <div class="modal-body">
                <div class="mb-3">
                    <label>Name</label>
                    <input type="text" class="form-control" name="name">
                </div>

                <div class="mb-3">
                    <label>Description</label>
                    <textarea class="form-control" name="description"></textarea>
                </div>

                <div class="mb-3">
                    <label>Geometry</label>
                    <textarea class="form-control" id="geometry_point" name="geometry_point"></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>

            </form>

        </div>
    </div>
</div>

{{-- Modal Polyline --}}
<div class="modal" tabindex="-1" id="modalInputPolyline">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Input Polyline</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('polylines.store') }}" method="post">
            @csrf

            <div class="modal-body">
                <div class="mb-3">
                    <label>Name</label>
                    <input type="text" class="form-control" name="name">
                </div>

                <div class="mb-3">
                    <label>Description</label>
                    <textarea class="form-control" name="description"></textarea>
                </div>

                <div class="mb-3">
                    <label>Geometry</label>
                    <textarea class="form-control" id="geometry_polyline" name="geometry_polyline"></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>

            </form>

        </div>
    </div>
</div>

{{-- Modal Polygon --}}
<div class="modal" tabindex="-1" id="modalInputPolygon">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Input Polygon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('polygons.store') }}" method="post">
            @csrf

            <div class="modal-body">
                <div class="mb-3">
                    <label>Name</label>
                    <input type="text" class="form-control" name="name">
                </div>

                <div class="mb-3">
                    <label>Description</label>
                    <textarea class="form-control" name="description"></textarea>
                </div>

                <div class="mb-3">
                    <label>Geometry</label>
                    <textarea class="form-control" id="geometry_polygon" name="geometry_polygon"></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>

            </form>

        </div>
    </div>
</div>

@endsection


@section('scripts')

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
<script src="https://unpkg.com/@terraformer/wkt"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

{{-- Bootstrap JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>

// INIT MAP
var map = L.map('map').setView([-7.7956,110.3695],12);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{
    maxZoom:19
}).addTo(map);

// FEATURE GROUP
var drawnItems = new L.FeatureGroup();
map.addLayer(drawnItems);

// DRAW CONTROL
var drawControl = new L.Control.Draw({
    draw:{
        polyline:true,
        polygon:true,
        rectangle:true,
        circle:false,
        marker:true,
        circlemarker:false
    },
    edit:false
});

map.addControl(drawControl);

// EVENT DRAW
map.on('draw:created', function(e){

    var layer = e.layer;
    var type = e.layerType;

    var geojson = layer.toGeoJSON();
    var wkt = Terraformer.geojsonToWKT(geojson.geometry);

    console.log("Type:", type);
    console.log("WKT:", wkt);

    // POINT
    if(type === 'marker'){
        $('#geometry_point').val(wkt);
        new bootstrap.Modal(document.getElementById('modalInputPoint')).show();
    }

    // POLYLINE
    else if(type === 'polyline'){
        $('#geometry_polyline').val(wkt);
        new bootstrap.Modal(document.getElementById('modalInputPolyline')).show();
    }

    // POLYGON & RECTANGLE
    else if(type === 'polygon' || type === 'rectangle'){
        $('#geometry_polygon').val(wkt);
        new bootstrap.Modal(document.getElementById('modalInputPolygon')).show();
    }

    drawnItems.addLayer(layer);
});

// RELOAD setelah modal ditutup
$('#modalInputPoint, #modalInputPolyline, #modalInputPolygon').on('hidden.bs.modal', function(){
    location.reload();
});

// GeoJSON Point
var points = L.geoJSON(null, {
	// Style

	// onEachFeature
    onEachFeature: function (feature, layer) {
	// variable popup content
	var popup_content = "Nama: " + feature.properties.name + "<br>" +
		"Deskripsi: " + feature.properties.description + "<br>" +
		"Dibuat: " + feature.properties.created_at;
	layer.on({
		click: function (e) {
			points.bindPopup(popup_content);
		},
	});
},

});

$.getJSON("{{route('geojson.points')}}", function (data) {
	points.addData(data);
	map.addLayer(points);
});


// GeoJSON Polylines
var polylines = L.geoJSON(null, {
	// Style

	// onEachFeature
    onEachFeature: function (feature, layer) {
	// variable popup content
	var popup_content = "Nama: " + feature.properties.name + "<br>" +
		"Deskripsi: " + feature.properties.description + "<br>" +
		"Dibuat: " + feature.properties.created_at;
	layer.on({
		click: function (e) {
			polylines.bindPopup(popup_content);
		},
	});
},

});

$.getJSON("{{route('geojson.polylines')}}", function (data) {
	polylines.addData(data);
	map.addLayer(polylines);
});


// GeoJSON Polygons
var polygons = L.geoJSON(null, {
	// Style

	// onEachFeature
    onEachFeature: function (feature, layer) {
	// variable popup content
	var popup_content = "Nama: " + feature.properties.name + "<br>" +
		"Deskripsi: " + feature.properties.description + "<br>" +
		"Dibuat: " + feature.properties.created_at;
	layer.on({
		click: function (e) {
			polygons.bindPopup(popup_content);
		},
	});
},

});

$.getJSON("{{route('geojson.polygons')}}", function (data) {
	polygons.addData(data);
	map.addLayer(polygons);
});


// Control Layer
var baseMaps = {

};

var overlayMaps = {
	"Points": points,
	"Polylines": polylines,
	"Polygons": polygons,
};

var controllayer = L.control.layers(baseMaps, overlayMaps);
controllayer.addTo(map);

</script>

@endsection
