<template>
  <v-app>
    <v-content>
      <FiveDayForecast/>
    </v-content>
  </v-app>
</template>
<script>
import FiveDayForecast from './components/FiveDayForecast';

export default {
  name: 'App',
  components: {
    FiveDayForecast,
  },
  data: () => ({
    //
  }),
};
</script>
