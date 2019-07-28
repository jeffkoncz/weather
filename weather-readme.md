# Challenge

Using the latest version of Laravel, create a simple app that displays the weather forecast for the current location collected from the
 [5 days weather forecast](https://openweathermap.org/forecast5) endpoint of the free openweathermap.org API 
 (see [How to start](https://openweathermap.org/appid)).
The UI should at least show the appropriate [weather icon](https://openweathermap.org/weather-conditions) and max/min
 temperatures for each day.
The app should not query the API at every page load, the weather data should be cached locally for the current day.
Finally, the app should save on a local (SQLite for simplicity) database the following information about every request
 received: IP, request method, user agent, time.

Make the final source code available (not necessarily publicly) on GitHub.

#### Extra:

- Implement the UI using Vue (preferred) or React
- Add interactivity to the page allowing the user to see the full forecast of a single day
- Add tests on either or both server and client
- Create a command to cache the weather data daily at 2am
- Make somehow your app available online (Heroku, AWS, Github Pages, etc., your choice)

### What we are looking for

- Clean and understandable code 
- Usage of best practices
- Knowledge of the language features available in the latest version of PHP and JS
