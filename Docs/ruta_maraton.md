# Ruta Maratón Olimpiada 33 – Urb. Celina Muyurina (Montero)

Ruta oficial definida por esquinas (puntos de giro del recorrido). Coordenadas oficiales en formato WGS84 (latitud, longitud).

## 1. Resumen

| Dato | Valor |
|---|---|
| Evento | Maratón Olimpiada 33 |
| Zona | Muyurina, Montero (Santa Cruz, Bolivia) |
| Inicio (Punto 1) | -17.359770, -63.258670 |
| Meta (Punto 7) | -17.361681, -63.244658 |
| Puntos de control | 7 (incluye inicio y meta) |
| Distancia aproximada | ~4.7 km (~5.0 km perimetral oficial) |

## 2. Puntos Oficiales de la Ruta

| # | Descripción | Latitud | Longitud | Tramo siguiente |
|---|---|---|---|---|
| 1 | **Inicio** (partida) | -17.359770 | -63.258670 | 1328 m (recta hacia el sureste) |
| 2 | Giro recta inicial | -17.369881 | -63.252018 | 1072 m (hacia el noreste) |
| 3 | Esquina noreste | -17.364783 | -63.243443 | 56 m |
| 4 | Giro retorno | -17.364355 | -63.243718 | 996 m (retorno largo suroeste) |
| 5 | Fin retorno suroeste | -17.369076 | -63.251698 | 135 m |
| 6 | Giro recta final | -17.368046 | -63.252373 | 1082 m (enfilada a la meta) |
| 7 | **Meta** (llegada oficial) | -17.361681 | -63.244658 | — |

## 3. GeoJSON Oficial

Nota: GeoJSON usa el estándar `[longitud, latitud]`.

```json
{
  "type": "FeatureCollection",
  "features": [
    {
      "type": "Feature",
      "properties": { "name": "Maratón Olimpiada 33", "distancia_km_aprox": 4.7 },
      "geometry": {
        "type": "LineString",
        "coordinates": [
          [-63.258670, -17.359770],
          [-63.252018, -17.369881],
          [-63.243443, -17.364783],
          [-63.243718, -17.364355],
          [-63.251698, -17.369076],
          [-63.252373, -17.368046],
          [-63.244658, -17.361681]
        ]
      }
    },
    { "type": "Feature", "properties": { "name": "Inicio (Punto 1)" }, "geometry": { "type": "Point", "coordinates": [-63.258670, -17.359770] } },
    { "type": "Feature", "properties": { "name": "Meta (Punto 7)" },   "geometry": { "type": "Point", "coordinates": [-63.244658, -17.361681] } }
  ]
}
```

## 4. Visualización en Mapa Interactivo (Leaflet + OpenStreetMap)

```html
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<div id="map" style="height:500px"></div>
<script>
  // Leaflet usa [lat, lng]
  const ruta = [
    [-17.359770, -63.258670],
    [-17.369881, -63.252018],
    [-17.364783, -63.243443],
    [-17.364355, -63.243718],
    [-17.369076, -63.251698],
    [-17.368046, -63.252373],
    [-17.361681, -63.244658]
  ];

  const map = L.map('map');
  L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap'
  }).addTo(map);

  const linea = L.polyline(ruta, { color: '#0066B2', weight: 5 }).addTo(map);
  L.marker(ruta[0]).addTo(map).bindPopup('Punto 1 (Inicio)');
  L.marker(ruta[ruta.length - 1]).addTo(map).bindPopup('Punto 7 (Meta)');
  map.fitBounds(linea.getBounds(), { padding: [20, 20] });
</script>
```