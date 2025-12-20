<?php defined('BLUDIT') or die('Bludit CMS.'); ?>

<?php echo Bootstrap::formOpen(array('id' => 'jsform', 'class' => 'tab-content')); ?>

<div class="align-middle">
	<div class="float-right mt-1">
		<button type="submit" class="btn btn-primary btn-sm" name="save"><?php $L->p('Save') ?></button>
		<a class="btn btn-secondary btn-sm" href="<?php echo HTML_PATH_ADMIN_ROOT . 'dashboard' ?>" role="button"><?php $L->p('Cancel') ?></a>
	</div>
	<?php echo Bootstrap::pageTitle(array('title' => $L->g('Weather Management'), 'icon' => 'cloud')); ?>
</div>

<?php
// Token CSRF
echo Bootstrap::formInputHidden(array(
	'name' => 'tokenCSRF',
	'value' => $security->getTokenCSRF()
));
?>

<div class="row mt-4">
	<div class="col-md-8">
		<div class="card mb-4">
			<div class="card-header">
				<h5 class="mb-0"><?php $L->p('Location Settings') ?></h5>
			</div>
			<div class="card-body">
				<?php
				// List of popular cities
				$popularCities = array(
					'London' => 'London, UK',
					'New York' => 'New York, USA',
					'Tokyo' => 'Tokyo, Japan',
					'Paris' => 'Paris, France',
					'Sydney' => 'Sydney, Australia',
					'Berlin' => 'Berlin, Germany',
					'Rome' => 'Rome, Italy',
					'Madrid' => 'Madrid, Spain',
					'Moscow' => 'Moscow, Russia',
					'Toronto' => 'Toronto, Canada',
					'Mumbai' => 'Mumbai, India',
					'Beijing' => 'Beijing, China',
					'Dubai' => 'Dubai, UAE',
					'Singapore' => 'Singapore',
					'Bangkok' => 'Bangkok, Thailand',
					'Hong Kong' => 'Hong Kong',
					'Amsterdam' => 'Amsterdam, Netherlands',
					'Vienna' => 'Vienna, Austria',
					'Stockholm' => 'Stockholm, Sweden',
					'Copenhagen' => 'Copenhagen, Denmark',
					'Oslo' => 'Oslo, Norway',
					'Zurich' => 'Zurich, Switzerland',
					'Barcelona' => 'Barcelona, Spain',
					'Athens' => 'Athens, Greece',
					'Dublin' => 'Dublin, Ireland',
					'Edinburgh' => 'Edinburgh, UK',
					'Brussels' => 'Brussels, Belgium',
					'Prague' => 'Prague, Czech Republic',
					'Warsaw' => 'Warsaw, Poland',
					'Budapest' => 'Budapest, Hungary',
					'Lisbon' => 'Lisbon, Portugal',
					'Los Angeles' => 'Los Angeles, USA',
					'Chicago' => 'Chicago, USA',
					'Houston' => 'Houston, USA',
					'Phoenix' => 'Phoenix, USA',
					'Philadelphia' => 'Philadelphia, USA',
					'San Antonio' => 'San Antonio, USA',
					'San Diego' => 'San Diego, USA',
					'Dallas' => 'Dallas, USA',
					'San Jose' => 'San Jose, USA',
					'Austin' => 'Austin, USA',
					'Seattle' => 'Seattle, USA',
					'Boston' => 'Boston, USA',
					'Miami' => 'Miami, USA',
					'San Francisco' => 'San Francisco, USA',
					'Las Vegas' => 'Las Vegas, USA',
					'Denver' => 'Denver, USA',
					'Atlanta' => 'Atlanta, USA',
					'Portland' => 'Portland, USA',
					'Melbourne' => 'Melbourne, Australia',
					'Brisbane' => 'Brisbane, Australia',
					'Perth' => 'Perth, Australia',
					'Auckland' => 'Auckland, New Zealand',
					'Shanghai' => 'Shanghai, China',
					'Seoul' => 'Seoul, South Korea',
					'Jakarta' => 'Jakarta, Indonesia',
					'Manila' => 'Manila, Philippines',
					'Kuala Lumpur' => 'Kuala Lumpur, Malaysia',
					'Ho Chi Minh City' => 'Ho Chi Minh City, Vietnam',
					'Cairo' => 'Cairo, Egypt',
					'Johannesburg' => 'Johannesburg, South Africa',
					'Nairobi' => 'Nairobi, Kenya',
					'Lagos' => 'Lagos, Nigeria',
					'Buenos Aires' => 'Buenos Aires, Argentina',
					'Rio de Janeiro' => 'Rio de Janeiro, Brazil',
					'Sao Paulo' => 'Sao Paulo, Brazil',
					'Mexico City' => 'Mexico City, Mexico',
					'Bogota' => 'Bogota, Colombia',
					'Lima' => 'Lima, Peru',
					'Santiago' => 'Santiago, Chile',
					'Caracas' => 'Caracas, Venezuela',
					'Montreal' => 'Montreal, Canada',
					'Vancouver' => 'Vancouver, Canada',
					'Calgary' => 'Calgary, Canada',
					'Ottawa' => 'Ottawa, Canada',
					'Jerusalem' => 'Jerusalem, Israel',
					'Tel Aviv' => 'Tel Aviv, Israel',
					'Ankara' => 'Ankara, Turkey',
					'Istanbul' => 'Istanbul, Turkey'
				);
				
				// Add "Custom" option for manual entry
				$cityOptions = array_merge(array('' => $L->g('Select a city or enter custom')), $popularCities);
				$cityOptions['__custom__'] = $L->g('Custom (enter manually)');
				
				// Check if current city is in the list
				$selectedCity = '';
				if (isset($popularCities[$cityName])) {
					$selectedCity = $cityName;
				} elseif (!empty($cityName) && !in_array($cityName, $popularCities)) {
					$selectedCity = '__custom__';
				}
				
				echo Bootstrap::formSelect(array(
					'name' => 'citySelect',
					'label' => $L->g('Select City'),
					'options' => $cityOptions,
					'selected' => $selectedCity,
					'class' => 'js-city-select',
					'tip' => $L->g('Select a city from the list or choose "Custom" to enter a city name manually.')
				));
				
				echo Bootstrap::formInputText(array(
					'name' => 'cityName',
					'label' => $L->g('City Name'),
					'value' => $cityName,
					'class' => 'js-city-name-input',
					'placeholder' => 'London',
					'tip' => $L->g('Enter the city name for weather display. The city will be geocoded automatically to get coordinates.')
				));
				?>
				
				<hr class="my-4">
				
				<p class="text-muted"><?php $L->p('Optional: If you want to use specific coordinates instead of city name, fill in the latitude and longitude fields below. If coordinates are provided, the city name will be ignored.') ?></p>
				
				<?php
				$latitudeValue = $latitude !== null ? number_format($latitude, 6, '.', '') : '';
				echo Bootstrap::formInputText(array(
					'name' => 'latitude',
					'label' => $L->g('Latitude'),
					'value' => $latitudeValue,
					'class' => '',
					'placeholder' => '51.5074',
					'tip' => $L->g('Optional: Latitude coordinate (e.g., 51.5074 for London). Leave empty to use city name.')
				));
				
				$longitudeValue = $longitude !== null ? number_format($longitude, 6, '.', '') : '';
				echo Bootstrap::formInputText(array(
					'name' => 'longitude',
					'label' => $L->g('Longitude'),
					'value' => $longitudeValue,
					'class' => '',
					'placeholder' => '-0.1278',
					'tip' => $L->g('Optional: Longitude coordinate (e.g., -0.1278 for London). Leave empty to use city name.')
				));
				?>
			</div>
		</div>
		
		<div class="alert alert-info">
			<h6><?php $L->p('How it works:') ?></h6>
			<ul class="mb-0">
				<li><?php $L->p('If you provide coordinates (latitude and longitude), those will be used directly.') ?></li>
				<li><?php $L->p('If coordinates are empty, the city name will be geocoded automatically to find the coordinates.') ?></li>
				<li><?php $L->p('The weather widget will display current weather conditions for the selected location.') ?></li>
			</ul>
		</div>
	</div>
</div>

<?php echo Bootstrap::formClose(); ?>

<script>
	(function() {
		const citySelect = document.querySelector('.js-city-select');
		const cityNameInput = document.querySelector('.js-city-name-input');
		
		if (!citySelect || !cityNameInput) return;
		
		// When city is selected from dropdown
		citySelect.addEventListener('change', function() {
			const selectedValue = this.value;
			
			if (selectedValue === '__custom__') {
				// Show and focus on the input field for custom entry
				cityNameInput.value = '';
				cityNameInput.focus();
			} else if (selectedValue !== '') {
				// Set the selected city name in the input
				cityNameInput.value = selectedValue;
			} else {
				// Clear the input if "Select a city" is chosen
				cityNameInput.value = '';
			}
		});
		
		// When user types in the custom input, update the select to "Custom"
		cityNameInput.addEventListener('input', function() {
			const currentValue = this.value.trim();
			const selectedOption = citySelect.options[citySelect.selectedIndex];
			
			// Check if the typed value matches any option in the select
			let found = false;
			for (let i = 0; i < citySelect.options.length; i++) {
				if (citySelect.options[i].value === currentValue && citySelect.options[i].value !== '' && citySelect.options[i].value !== '__custom__') {
					citySelect.value = currentValue;
					found = true;
					break;
				}
			}
			
			// If not found and not empty, set to custom
			if (!found && currentValue !== '') {
				citySelect.value = '__custom__';
			}
		});
	})();
</script>
