#GlassKit#
##Gradr##

This project consists of 3 different components:

+ A web application built using the Laravel Framework 5.0
+ An Android application for Google Glass (tested on XE22)
+ A small webserver running Apache 2, PHP 5.5, MySQL and Composer 1.0

The Google Glass application is the main user input platform. On it, users can load locations, fill checklists, take pictures and send them to the backend where they can be processed in order to display a list of past results to the user through the webplatform. The Glass application also gets data from the backend when it needs to check which grades are accepted.

###Installation###

**Google Glass' Gradr application:** To install the application on Google Glass you will need Android Studio with ADB enable. Your Google Glass will need to have developer settings enabled and no previous version of Gradr installed. To uninstall any version of Gradr from the glass, please use the following command on your console.
<pre><code>adb uninstall com.medialabamsterdam.checklistprototype</code></pre>

And then just build the application normally.

**Gradr Web application:** For the Laravel documentation please visit http://laravel.com/docs/5.0

To install the Laravel application, all the included folders need to be uploaded to the webserver. After uploading the folder use ssh to log in to the webserver and navigate to the folder containing the Laravel folders. All dependencies can be installed using the <pre><code>composer update</code></pre> command.

The application can be configured using the .env file in the root folder of the application. After configuring the application, create the database tables. This can be done either manually, or automatically using the <pre><code>php artisan migrate</code></pre> command in ssh.

After this setup the application is ready to use. In order to log in it is necessary to register an account.

###Application for Google Glass###

The application is only set to work with the Areas and Locations that exist inside the Port of Amsterdam. If modifying it to work with areas other than those you will have to modify the database available with this project and export it in SQL format and format it so every statement is in a single line (No \r or \n in the middle of an `INSERT` or `CREATE` lines).
Ideally you will need to change how the database work, for more information please see the "Known Issues" topic.

The Glass application is available both in English and Dutch, but to change the language you need to change the code in `Utilities > Constants` and change `LOAD_ALTERNATE_LANGUAGE` to `TRUE`.

If you wish to implement authentication or user IDs, as well as changing using your own server URL, you will find the variable at `Utilities > Constants`, you can change `USER_ID` to match a specific user in each Glass and `WEB_SERVICE_URL` to match your URL.

####Usage####
When the app is started it will try to find if the user's actual location is inside one of the locations registered in the database. Alternatively you can long press with three fingers to load a demo location.

When the location is acquired, the user can tap to go to the Categories screen. Here the user can swipe either left or right to change the categories, tap to go into the SubCategories screen or long press with two fingers in order to skip a category. The user will also be able to send the results from this screen when he has finished all the grading.

On the SubCategories screen, the user will see the SubCategories he has to rate. He can change the rating by swiping with two fingers instead of one. If more details are needed the user can tap to go into the Details screen.

The Details screen show the details for a specific subcategory. The user may swipe up for more details about one rating or swipe left/right with two fingers in order to change the rate and, subsequently, the details.

When the user is finished with all the categories and subcategories, he can submit the checklist through the last card on the Categories screen.  

####*Libraries*####

This project uses the following libraries:

#####**Polygon contains Point**#####
<pre><code>Creator: Roman Kushnarenko
Repository: https://github.com/sromku/polygon-contains-point
</code></pre>

#####**Ion**#####
<pre><code>Creator: Koushik Dutta
Repository: https://github.com/koush/ion
</code></pre>

#####**Gson**#####
<pre><code>Creator: Google
Repository: https://github.com/google/gson
</code></pre>

###Web Platform###
The map uses the javascript based Google Maps API v3. A map can be initialized using the Map object, defined in public/js/map.js. To create an instance, use new Map(options). Options can be defined as a json object, that should at least include the “elementId” key and value to defined the html element for the map. In the “mapOptions” key, options for the Maps API can be defined in a json object.

new Map(
{
elementId: "map-panel",
    options: 
{
            mapTypeId: google.maps.MapTypeId.ROADMAP
}
}
);


The Map object has a couple of public methods.

drawAll()
    draw all locations.
drawLocations(locations, callback)
    draw a list of given locations by name.
drawLocation(name, callback)
    draw a single location by name.
drawRoute(from, to)
    draw a route from LatLng to LatLng position.
removeLocations(locations, callback)
    remove a list of given locations by name.
removeLocation(location)
    remove a single location by name.
routeToNextLocations(callback)
    draw a route to the closest location.
getNextLocation(callback)
    get the closest location information.
showCurrentPosition(callback)
    draw the current position of the user.
hideRoute()
    hide the route.

To use the locations, please import locations.sql into the locations table in the database. Also import locations_subcategory.sql in the location_subcategory table and subcategories in de the subcategories table. This last import stores location specific grading data for 34 locations in area 112A and is needed to submit checklists from google glass and store the submitted data.

####Usage####

The web platform displays the logged in user's personal todo list on a map view. This todo list is generated automaticly when visiting the '/randomlist' url. When the list is generated it selects 10% of the the locations in the database randomly. These locations are then grouped and devided over all the users with the 'field' role. In the map view, the locations can be shown and hidden by clicking on the checkboxes in the top toolbar. Hidden locations won't be included in the workflow. The top bar also has a 'my location' button on the far right. By clicking this button, the user will see where he is on the map. The icon that show the user's position updates every 5 seconds.

Using the bottom right toolbox on the map view the user can search for the nearest location on his todo list. A click on the button will trigger the search and show the nearest location. Using the bottom left toolbox the user can show or hide the route to the nearest location. For these functions, only visible locations will be included.

Google Glass syncronizes with the web platform. When the web platform receives a new assessment it updates the corresponding todo list, which is then automatically pushed to the interface. The web platform again calculates the nearest location and shows it to the user. Also, the location that was assessed is removed from the map view todo list.

####API####

The API is used to create, delete and update data in the database.

POST    - /checklist/           - create a new checklist
GET - /location/name/{id}       - get location data by id
GET - /location/subcategories/{id}  - get subcategorylocations for a location by id
GET - /locations/all/           - get all locations with their position data
GET - /location/categories/{id} - get all categories for a location by id
GET - /randomlist/          - generate a new list of randomly selected locations.

###Known Issues###

The database is too complex and not centralized at the moment since we decided to focus on having a working application. It would be wise to simplify the database by removing unnecessary relationships and fields and to unify the databases on a web server in order to be able to update it without having to reinstall the Glass App. The ideal solution would have the application query for the version of the database when the user starts the application and update the local Glass database if needed.

It is also known that if the user leaves the application entirely (`TWO_SWIPE_DOWN`), progress will not be saved. A better approach would be to save every step the user takes on the Glass's own memory. If the user happens to start Gradr and he is in the same location as the last session, then the application could retrieve that session's checklist for the user.

The checklists are automatically linked to user ID 1, this can be changed for alternate pairs of glasses or you can implement using the OAuth2 token instead.