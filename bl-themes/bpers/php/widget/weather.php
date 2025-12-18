<!-- Weather Widget -->
<div class="weather-widget sidebar-widget mb-4">
	<div class="widget-header">
		<h5 class="widget-title">Weather</h5>
	</div>
	<div class="widget-content">
		<div class="weather-container">
			<div id="weather-data"></div>
			<script>
			(function() {
				const weatherContainer = document.getElementById('weather-data');
				if (!weatherContainer) return;
				
				// Configuration - Set your location here
				// Option 1: City name (will be geocoded automatically)
				const CITY_NAME = 'London'; // Change to your city name
				
				// Option 2: Coordinates directly (latitude, longitude) - Recommended!
				// If coordinates are provided, CITY_NAME will be ignored
				const COORDINATES = null; // Example: [51.5074, -0.1278] for London, [40.7128, -74.0060] for New York
				
				// Function to get weather description from weather code
				function getWeatherDescription(code) {
					const codes = {
						0: 'Clear sky',
						1: 'Mainly clear',
						2: 'Partly cloudy',
						3: 'Overcast',
						45: 'Foggy',
						48: 'Depositing rime fog',
						51: 'Light drizzle',
						53: 'Moderate drizzle',
						55: 'Dense drizzle',
						56: 'Light freezing drizzle',
						57: 'Dense freezing drizzle',
						61: 'Slight rain',
						63: 'Moderate rain',
						65: 'Heavy rain',
						66: 'Light freezing rain',
						67: 'Heavy freezing rain',
						71: 'Slight snow fall',
						73: 'Moderate snow fall',
						75: 'Heavy snow fall',
						77: 'Snow grains',
						80: 'Slight rain showers',
						81: 'Moderate rain showers',
						82: 'Violent rain showers',
						85: 'Slight snow showers',
						86: 'Heavy snow showers',
						95: 'Thunderstorm',
						96: 'Thunderstorm with slight hail',
						99: 'Thunderstorm with heavy hail'
					};
					return codes[code] || 'Unknown';
				}
				
				// Function to get weather icon (using simple emoji/unicode)
				function getWeatherIcon(code) {
					if (code === 0 || code === 1) return '☀️';
					if (code === 2 || code === 3) return '☁️';
					if (code >= 45 && code <= 48) return '🌫️';
					if (code >= 51 && code <= 67) return '🌧️';
					if (code >= 71 && code <= 77) return '🌨️';
					if (code >= 80 && code <= 86) return '🌦️';
					if (code >= 95 && code <= 99) return '⛈️';
					return '🌤️';
				}
				
				function loadWeather() {
					weatherContainer.innerHTML = '<div class="weather-loading">Loading weather...</div>';
					
					// Step 1: Get coordinates (either use provided or geocode city name)
					let latitude, longitude, cityName;
					
					if (COORDINATES && Array.isArray(COORDINATES) && COORDINATES.length === 2) {
						// Use provided coordinates
						latitude = COORDINATES[0];
						longitude = COORDINATES[1];
						cityName = CITY_NAME || 'Current Location';
						fetchWeatherData(latitude, longitude, cityName);
					} else {
						// Geocode city name to coordinates
						fetch(`https://geocoding-api.open-meteo.com/v1/search?name=${encodeURIComponent(CITY_NAME)}&count=1&language=en&format=json`)
							.then(response => response.json())
							.then(data => {
								if (data.results && data.results.length > 0) {
									latitude = data.results[0].latitude;
									longitude = data.results[0].longitude;
									cityName = data.results[0].name || CITY_NAME;
									fetchWeatherData(latitude, longitude, cityName);
								} else {
									weatherContainer.innerHTML = '<div class="weather-error">City not found. Please check the city name.</div>';
								}
							})
							.catch(error => {
								weatherContainer.innerHTML = '<div class="weather-error">Unable to find location</div>';
							});
					}
					
					// Step 2: Fetch weather data using coordinates
					function fetchWeatherData(lat, lon, name) {
						const url = `https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current=temperature_2m,relative_humidity_2m,weather_code,wind_speed_10m&timezone=auto`;
						
						fetch(url)
							.then(response => response.json())
							.then(data => {
								if (data.current) {
									const current = data.current;
									const weatherIcon = getWeatherIcon(current.weather_code);
									const weatherDesc = getWeatherDescription(current.weather_code);
									
									weatherContainer.innerHTML = `
										<div class="weather-info">
											<div class="weather-icon-emoji">${weatherIcon}</div>
											<div class="weather-temp">${Math.round(current.temperature_2m)}°C</div>
											<div class="weather-desc">${weatherDesc}</div>
											<div class="weather-city">${name}</div>
											<div class="weather-details">
												<span>Humidity: ${current.relative_humidity_2m}%</span>
												<span>Wind: ${Math.round(current.wind_speed_10m)} km/h</span>
											</div>
										</div>
									`;
								} else {
									weatherContainer.innerHTML = '<div class="weather-error">Unable to load weather data</div>';
								}
							})
							.catch(error => {
								weatherContainer.innerHTML = '<div class="weather-error">Weather service unavailable</div>';
							});
					}
				}
				
				loadWeather();
				setInterval(loadWeather, 1800000); // Refresh every 30 minutes
			})();
			</script>
			
		</div>
	</div>
</div>

