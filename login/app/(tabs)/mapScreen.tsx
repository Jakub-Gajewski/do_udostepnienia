import React, { useState } from 'react';
import { View, StyleSheet } from 'react-native';
import MapView, { Marker } from 'react-native-maps';

const MyMapScreen = () => {
  const [markers] = useState([
    { id: 1, lat: 52.2298, lon: 21.0122, title: "Warszawa" },
    { id: 2, lat: 50.0647, lon: 19.9450, title: "Kraków" }
  ]);

  return (
    <View style={styles.container}>
      <MapView
        style={styles.map}
        initialRegion={{
          latitude: 52.2298,
          longitude: 21.0122,
          latitudeDelta: 5,
          longitudeDelta: 5,
        }}
      >
        {markers.map(marker => (
          <Marker
            key={marker.id}
            coordinate={{ latitude: marker.lat, longitude: marker.lon }}
            title={marker.title}
          />
        ))}
      </MapView>
    </View>
  );
};

export default MyMapScreen;

const styles = StyleSheet.create({
  container: { flex: 1 },
  map: { flex: 1 }
});
