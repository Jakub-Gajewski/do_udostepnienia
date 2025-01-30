import { DarkTheme, DefaultTheme, ThemeProvider } from '@react-navigation/native';
import { createDrawerNavigator } from '@react-navigation/drawer';
import { Stack } from 'expo-router';
import * as SplashScreen from 'expo-splash-screen';
import { StatusBar } from 'expo-status-bar';
import { useEffect } from 'react';
import 'react-native-reanimated';

import { useColorScheme } from '@/hooks/useColorScheme';

import HomeScreen from './(tabs)/index';
import TabTwoScreen from './(tabs)/explore';
import LoginScreen from './(tabs)/login';
import MyMapScreen from './(tabs)/mapScreen';

// Prevent the splash screen from auto-hiding before asset loading is complete.
SplashScreen.preventAutoHideAsync();

const Drawer = createDrawerNavigator();

export default function RootLayout() {
  const colorScheme = useColorScheme();
  const [loaded] = useFonts({
    SpaceMono: require('../assets/fonts/SpaceMono-Regular.ttf'),
  });

  useEffect(() => {
    if (loaded) {
      SplashScreen.hideAsync();
    }
  }, [loaded]);

  if (!loaded) {
    return null;
  }

   return (
      <ThemeProvider value={colorScheme === 'dark' ? DarkTheme : DefaultTheme}>
        <Drawer.Navigator initialRouteName="Home">
          <Drawer.Screen name="Home" component={HomeScreen} />
          <Drawer.Screen name="Explore" component={TabTwoScreen} />
          <Drawer.Screen name="Login" component={LoginScreen} />
          <Drawer.Screen name="Map" component={MyMapScreen} />
        </Drawer.Navigator>
        <StatusBar style="auto" />
      </ThemeProvider>
    );
}
