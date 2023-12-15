$apiKey = 'ab0f6b84bbcbbd4066f4fee3eaba248c';


// This function takes one parmater $query
function searchMoviesAndTVShows($query) {
    // Key is used for connecting to the TMBD
    global $apiKey;
    $apiUrl = "https://api.themoviedb.org/3/search/multi?api_key={$apiKey}&query=" . urlencode($query);
    // Json response is put into the $result 
    $response = file_get_contents($apiUrl);
    $result = json_decode($response, true);
    // If the results were true it will will start extracting the relevant data 
    if ($result && isset($result['results'])) {
        $mediaList = array();
        // Will check the media type whether its a movie or tv show 
        foreach ($result['results'] as $media) {
            $mediaType = '';
            if (isset($media['media_type'])) {
                $mediaType = $media['media_type'];
            } elseif (isset($media['original_title'])) {
                $mediaType = 'movie';
            } elseif (isset($media['original_name'])) {
                $mediaType = 'tvshow';
            }
            // Gets the ID, Title, Type, and poster url 
            $mediaList[] = array(
                'ID' => $media['id'],
                'Title' => $media['original_title'] ?? $media['original_name'],
                'Type' => $mediaType,
                'PosterURL' => isset($media['poster_path']) ? "https://image.tmdb.org/t/p/w500{$media['poster_path']}" : 'Not available',
            );
        }
        // Returns the mediaList with relevant information 
        return $mediaList;
    } else {
        return array('error' => 'No results found.');
    }
}

$query = "Moana";

$result = searchMoviesAndTVShows($query);

print_r($result);
