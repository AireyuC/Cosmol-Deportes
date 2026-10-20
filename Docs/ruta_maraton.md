# Ruta Maratón Olimpiada 33 – Urb. Celina Muyurina (Montero)

Ruta definida por esquinas (puntos donde el recorrido gira). Las coordenadas son las que entregó el equipo del proyecto, en WGS84 (lat, lng).

## 1. Resumen

| Dato | Valor |
|---|---|
| Evento | Maratón Olimpiada 33 |
| Zona | Muyurina, Montero (Santa Cruz, Bolivia) |
| Inicio | -17.360332, -63.258289 |
| Meta | -17.361665, -63.244581 (aproximada) |
| Puntos de giro | 11 (incluye inicio y meta) |
| Distancia aproximada | ~5.0 km (suma de tramos rectos entre esquinas) |

> La distancia es una estimación en línea recta entre esquinas. La real puede variar un poco por curvas y por el lado de la calle por donde se corra.

## 2. Puntos de la ruta

La numeración respeta la que se indicó: **no se proporcionó el punto 3**, así que la lista salta del 2 al 4.

| # | Descripción | Latitud | Longitud | Tramo siguiente |
|---|---|---|---|---|
| 1 | **Inicio** (partida) | -17.360332 | -63.258289 | 1254 m (recta larga hacia el sureste) |
| 2 | Fin de la recta inicial | -17.369889 | -63.252024 | 117 m |
| 4 | Esquina | -17.369353 | -63.251073 | 218 m |
| 5 | Esquina | -17.371009 | -63.249979 | 509 m |
| 6 | Esquina | -17.368591 | -63.245912 | 218 m |
| 7 | Esquina | -17.366925 | -63.246990 | 445 m |
| 8 | Esquina | -17.364782 | -63.243447 | 56 m |
| 9 | Esquina | -17.364355 | -63.243720 | 997 m (retorno largo hacia el suroeste) |
| 10 | Esquina | -17.369076 | -63.251708 | 136 m |
| 11 | Esquina | -17.368040 | -63.252384 | 1090 m (salida hacia el noreste) |
| 12 | **Meta** (aproximada) | -17.361665 | -63.244581 | — |

## 3. GeoJSON listo para usar

Nota: GeoJSON usa el orden `[lng, lat]`.

```json
{
  "type": "FeatureCollection",
  "features": [
    {
      "type": "Feature",
      "properties": { "name": "Maratón Olimpiada 33", "distancia_km_aprox": 5.0 },
      "geometry": {
        "type": "LineString",
        "coordinates": [
          [-63.258289, -17.360332],
          [-63.252024, -17.369889],
          [-63.251073, -17.369353],
          [-63.249979, -17.371009],
          [-63.245912, -17.368591],
          [-63.246990, -17.366925],
          [-63.243447, -17.364782],
          [-63.243720, -17.364355],
          [-63.251708, -17.369076],
          [-63.252384, -17.368040],
          [-63.244581, -17.361665]
        ]
      }
    },
    { "type": "Feature", "properties": { "name": "Inicio" }, "geometry": { "type": "Point", "coordinates": [-63.258289, -17.360332] } },
    { "type": "Feature", "properties": { "name": "Meta" },   "geometry": { "type": "Point", "coordinates": [-63.244581, -17.361665] } }
  ]
}
```

## 4. Qué se necesita para mostrar el recorrido en un mapa

### Opción A – Leaflet + OpenStreetMap (gratis, sin API key) ✅ recomendada

Solo necesitas la librería Leaflet (CDN) y las coordenadas.

```html
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<div id="map" style="height:500px"></div>
<script>
  // Leaflet usa [lat, lng]
  const ruta = [
    [-17.360332, -63.258289], [-17.369889, -63.252024], [-17.369353, -63.251073],
    [-17.371009, -63.249979], [-17.368591, -63.245912], [-17.366925, -63.246990],
    [-17.364782, -63.243447], [-17.364355, -63.243720], [-17.369076, -63.251708],
    [-17.368040, -63.252384], [-17.361665, -63.244581]
  ];

  const map = L.map('map');
  L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap'
  }).addTo(map);

  const linea = L.polyline(ruta, { color: '#d81b9c', weight: 5 }).addTo(map);
  L.marker(ruta[0]).addTo(map).bindPopup('Inicio');
  L.marker(ruta[ruta.length - 1]).addTo(map).bindPopup('Meta');
  map.fitBounds(linea.getBounds(), { padding: [20, 20] });
</script>
```

### Opción B – Google Maps JavaScript API

Necesitas:

- Un proyecto en **Google Cloud Console**.
- Activar **Maps JavaScript API**.
- Una **API key**, restringida por dominio (HTTP referrer).
- **Facturación activada** (hay crédito mensual gratuito, pero pide tarjeta).

```html
<div id="map" style="height:500px"></div>
<script>
  function initMap() {
    const ruta = [
      { lat: -17.360332, lng: -63.258289 }, { lat: -17.369889, lng: -63.252024 },
      { lat: -17.369353, lng: -63.251073 }, { lat: -17.371009, lng: -63.249979 },
      { lat: -17.368591, lng: -63.245912 }, { lat: -17.366925, lng: -63.246990 },
      { lat: -17.364782, lng: -63.243447 }, { lat: -17.364355, lng: -63.243720 },
      { lat: -17.369076, lng: -63.251708 }, { lat: -17.368040, lng: -63.252384 },
      { lat: -17.361665, lng: -63.244581 }
    ];
    const map = new google.maps.Map(document.getElementById('map'), {
      center: ruta[0], zoom: 15, mapTypeId: 'hybrid'
    });
    new google.maps.Polyline({ path: ruta, strokeColor: '#d81b9c', strokeWeight: 5, map });
    new google.maps.Marker({ position: ruta[0], map, title: 'Inicio' });
    new google.maps.Marker({ position: ruta[ruta.length - 1], map, title: 'Meta' });

    const bounds = new google.maps.LatLngBounds();
    ruta.forEach(p => bounds.extend(p));
    map.fitBounds(bounds);
  }
</script>
<script async src="https://maps.googleapis.com/maps/api/js?key=TU_API_KEY&callback=initMap"></script>
```

### Opción C – Google My Maps (sin código)

Importa el KML en https://www.google.com/mymaps, luego **Compartir → Insertar en mi sitio** y pega el `<iframe>` en la vista. Es la más rápida, pero con menos control visual.

### ¿Hace falta Directions API?

No. Directions calcula rutas por calles, y aquí el trazado es personalizado (con calles de tierra). Una **Polyline** con estas coordenadas es más fiel. Si se usara Directions, la ruta podría "ajustarse" a otras calles y no coincidir con la oficial.

## 5. Detalles que conviene saber

- Con solo las esquinas, la línea une los puntos en recta. Si alguna calle tiene curvas, agrega puntos intermedios o exporta el trazado completo desde Google Earth (clic derecho en "MARATON OLIMPIADA 33" → **Guardar lugar como…** → `.kml`) y conviértelo a GeoJSON en https://mapshaper.org.
- Falta el punto 3 de la numeración original. Si existe una esquina entre el 2 y el 4, agrégala en ese orden dentro del arreglo.
- La meta está marcada como "más o menos"; conviene confirmar el punto exacto de llegada en campo.
- Guarda la ruta como `ruta-maraton.geojson` y cárgala con `fetch()` en vez de pegar coordenadas en el código.
- Para el proyecto, agrega marcadores de Inicio, Meta, hidratación y puestos de salud.
- Si la vista usa React/Next.js: `react-leaflet` o `@vis.gl/react-google-maps`.