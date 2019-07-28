<template>
  <v-container>
    <v-layout
      text-center
      wrap
      row
      align-center
    >
      <v-flex>
        <h1 class="display-1 font-weight-bold mb-3">
          5 Day Hourly Weather Forecast
        </h1>
        
        <v-progress-circular v-if="loading"
          :size="50"
          :width="5"
          color="blue"
          indeterminate
        ></v-progress-circular>

        <h3 v-if="weatherData" class="grey--text font-weight-bold mb-3">
          Forecast for {{weatherData.city.name}}, {{weatherData.city.country}}
        </h3>

        <v-card
          v-for="(day, key) in parsedWeather"
          class="mx-auto weather-card"
          max-width="400"
        >
          <v-list-item>
            <v-list-item-content>
              <v-list-item-title class="headline text-left">{{key | momentCalendarTime}}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list>
            <v-list-item
              v-for="hour in day"
            >
              <v-list-item-title>{{hour.dt | momentHour}}</v-list-item-title>
              <v-img
                :src="'https://openweathermap.org/img/wn/'+ hour.weather[0].icon +'@2x.png'"
                :alt="hour.weather[0].main"
                width="50"
              ></v-img>
              <v-list-item-subtitle class="text-right">
                <div>{{hour.main.temp | roundTemp}} °F</div>
                <div>{{hour.weather[0].description}}</div>
              </v-list-item-subtitle>
            </v-list-item>
          </v-list>
        </v-card>
        
        <v-btn @click="getFiveDayForecast" v-if="!loading" color="primary">{{weatherButtonText}}</v-btn>

      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
import moment from 'moment'
import _ from 'lodash'

export default {
  name: 'FiveDayForecast',
  
  props: {
    msg: String
  },

  data() {
    return {
      loading: false,
      latitude: null,
      longitude: null,
      weatherData: "",
      parsedWeather: ""
    }
  },

  computed: {
    weatherButtonText: function () {
      if (!this.parsedWeather) {
        return "Get Forecast"
      } else {
        return "Update Forecast"
      }
    }
  },
  
  filters: {
    momentCalendarTime (datestamp) {
      if (datestamp) {
        return moment(datestamp, 'YYYY-MM-DD').calendar(null, {
          sameDay: '[Today]',
          nextDay: '[Tomorrow]',
          nextWeek: 'dddd'
        })
      }
    },
    momentHour (timestamp) {
      if (timestamp) {
        return moment.unix(timestamp).format('h:mm A')
      }
    },
    roundTemp (temp) {
      if (temp) {
        return temp.toFixed()
      }
    },
  },

  mounted() {
  },

  watch: {
    weatherData: function (val) {
      // Create a new array with weather data grouped by day
      this.parsedWeather = _.groupBy(val.list, function (item) {
        return moment.unix(item.dt).format('YYYY-MM-DD')
      })
    }
  },

  methods: {
    getFiveDayForecast(latitude, longitude) {
      this.loading = true
      this.weatherData = ""
      this.parsedWeather = ""
      const vm = this
      // Get current location of client if they have browser support
      // for geolocation
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          function (position) {
            // we request a forecast from our API using the current position of the client  
            vm.longitude = position.coords.longitude
            vm.latitude = position.coords.latitude
            vm.$http.get('/forecast5day', {
              params: {
                lat: vm.latitude,
                lon: vm.longitude
              }
            })
            .then(function (response) {
              // Handle successful response
              vm.weatherData = response.data.response
            })
            .catch(function (error) {
              console.log(error);
            })
            .finally(function () {
              vm.loading = false
            })
          },
          function (error) {
              vm.loading = false
              alert(error.message);
          }, {
              enableHighAccuracy: false
              , timeout: 5000
          }
        )
      } else {
        vm.loading = false
        alert("Geolocation is not supported by this browser.");
      }
    },
  }
}
</script>
<style scoped>
.weather-card {
  margin: 10px;
}
</style>
