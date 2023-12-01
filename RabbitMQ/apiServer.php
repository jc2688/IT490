#!/usr/bin/php
<?php
// Include required PHP files for RabbitMQ functionality and API-related functions
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');
require_once('../Backend/apiFunctions.inc');

// Function to process incoming requests
function requestProcessor($request){
  // Print a message indicating the request received and show request details
  echo "received request" . PHP_EOL;
  var_dump($request);

  // Switch case to handle different types of requests based on the 'type' field
  switch ($request['type']) {
    // Handle search movies request
    case "searchMovies":
      echo "Searching for movies\n";
      return searchMoviesAndTVShows($request['query']);

    // Handle search movies and TV shows request
    case "searchMoviesAndTVShows\n":
      echo "Searching for tv shows and movies";
      return searchMoviesandTVShows($request['query']);

    // Handle search person request
    case "searchPerson":
      echo "Searching for a person\n";
      return searchPerson($request['personName']);

    // Handle recommendation for actor or director request
    case "recommendationActorDirector":
      echo "Getting recommendations based on actor and director\n";
      return recommendationActorDirector($request['username']);

    // Handle get movies by actor request
    case "getMoviesByActor":
      echo "Getting movies by actor\n";
      return getMoviesByActor($request['actorName']);

    // Handle get movies by director request
    case "getMoviesByDirector":
      echo "Getting movies by director\n";
      return getMoviesByDirector($request['directorName']);

    // Handle get movies by movie and genre request
    case "getMoviesByMovieAndGenre":
      echo "Getting movies by movie and genre\n";
      return getMoviesByMovieAndGenre($request['username']);

    // Handle get movie by details request
    case "getMoviesByDetails":
      echo "Getting details for movie\n";
      return getMoviesByDetails($request['movieID']);

    // Default case for unhandled request types
    default:
      echo "Request type not handled\n";
      return ["error" => "Request type not supported"];
  }
}

// Create a new RabbitMQServer class instance with specified configuration for api access
$server = new rabbitMQServer("testRabbitMQ.ini","api");

echo "api server started up" . PHP_EOL; // Message indicating the server has started
$server -> process_requests('requestProcessor'); // Process incoming requests using the defined request processor function
echo "api server shut down" . PHP_EOL; // Message indicating the server is shutting down
exit();
?>
