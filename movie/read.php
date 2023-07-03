<?php
header('Content-Type: application/json');
require_once "../conn.php";

$response = new stdClass();

$sql = "SELECT
            m.*,
            l.id language_id,
            l.name language_name,
            l.code language_code,
            g.id genre_id,
            g.name genre_name,
            s.id subtitle_id,
            s.name subtitle_name,
            s.code subtitle_code,
            f.id format_id,
            f.name format_name
        FROM movies m

        JOIN movie_language ml ON ml.movie_id = m.id
        JOIN languages l ON ml.language_id = l.id

        JOIN movie_genre mg ON mg.movie_id = m.id
        JOIN genres g ON mg.genre_id = g.id
        
        JOIN movie_format mf ON mf.movie_id = m.id
        JOIN formats f ON mf.format_id = f.id
        
        JOIN movie_subtitle ms ON ms.movie_id = m.id
        JOIN subtitles s ON ms.subtitle_id = s.id
";
$movies = $conn->query($sql)->fetch_all(MYSQLI_ASSOC);

$result = [];
foreach ($movies as $movie) {
    $id = $movie['id'];
    $result[$id]['name'] = $movie['name'];
    $result[$id]['description'] = $movie['description'];
    $result[$id]['poster'] = $movie['poster'];
    $result[$id]['trailer'] = $movie['trailer'];
    $result[$id]['age_limit'] = $movie['age_limit'];
    $result[$id]['country'] = $movie['country'];
    $result[$id]['director'] = $movie['director'];
    $result[$id]['duration'] = $movie['duration'];
    $result[$id]['languages'] [$movie['language_id']]= (object)array(
        "id" => $movie['language_id'],
        "name" => $movie['language_name'],
        "code" => $movie['language_code']
    );
    $result[$id]['genres'] [$movie['genre_id']]= (object)array(
        "id" => $movie['genre_id'],
        "name" => $movie['genre_name'],
    );
    $result[$id]['formats'] [$movie['format_id']]= (object)array(
        "id" => $movie['format_id'],
        "name" => $movie['format_name'],
    );
    $result[$id]['subtitles'] [$movie['subtitle_id']]= (object)array(
        "id" => $movie['subtitle_id'],
        "name" => $movie['subtitle_name'],
        "code" => $movie['subtitle_code']
    );
}

$response->code = 0;
$response->message = "Success";
$response->data = $result;

echo(json_encode($response));