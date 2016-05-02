<?php

require_once("config.php"); // includes database connection

// TODO: in postman altijd in de header accept application/json mee sturen.

$method = $_SERVER["REQUEST_METHOD"];

switch ($method) {

    case "GET":

        // accept
        $accept = $_SERVER['HTTP_ACCEPT'];

        // db query
        $sql_select = "SELECT Id, Title, Artist, ReleaseDate FROM albums";
        $results = mysqli_query($dbLink, $sql_select);

        // pagination??
        $resultArray = [];

        // get start
        if (isset($_GET['start'])) {
            if ($_GET['start'] > 1) {
                $start = (int)$_GET['start'] - 1;
            } else {
                $start = 0;
            }
        } else {
            $start = 0;
        }

        // total entries
        $sql_count = "SELECT * FROM albums";
        $totalAlbums = mysqli_query($dbLink, $sql_count);
        $total = (int)mysqli_num_rows($totalAlbums);

        // get limit
        if (isset($_GET['limit'])) {
            $limit = (int)$_GET['limit'];
        } else {
            $limit = $total;
        }

        // get albums with start & limit
        $sql_limit = "SELECT Id, Title, Artist, ReleaseDate FROM albums LIMIT " . $start . ", " . $limit ." ";
        $limitResult = mysqli_query($dbLink, $sql_limit);

        // amount of pages & current page
        $pages = ceil($total / $limit);
        $currentPage = ceil($start / $limit) + 1;

        // last page & previous page & next page
        $lastPage = floor($total - $limit) + 1;

        $previousPage = ($start - $limit) + 1;
        if ($previousPage < $limit) {
            $previousStart = 1;
        } else {
            $previousStart = $previousPage;
        }

        $nextPage = ($start + $limit) + 1;
        if ($nextPage > $total){
            $nextStart = $total;
        } else {
            $nextStart = $nextPage;
        }

        // GET DETAIL PAGE IN JSON
        if (isset($_GET['Id']) && $accept == "application/json") {
            $id = $_GET['Id'];

            header("Content-Type: application/json");

        // db query
        $sql_select = "SELECT Id, Title, Artist, ReleaseDate FROM albums WHERE Id = $id";
        $results = mysqli_query($dbLink, $sql_select);

        if(mysqli_num_rows($results) >0){
            //found

            while ($row = mysqli_fetch_assoc($results)) {

                $links = array();

                $link = array();
                $link["rel"] = "self";
                $link["href"] = "https://stud.hosted.hr.nl/0892322/webservice/albums/" . $row["Id"];
                $linkCollection = array();
                $linkCollection["rel"] = "collection";
                $linkCollection["href"] = "https://stud.hosted.hr.nl/0892322/webservice/albums/";

                array_push($links, $link);
                array_push($links, $linkCollection);

                $row["links"] = $links;

                $resultArray[] = $row;

                echo json_encode($row);
            }
        } else {
            //not found
            http_response_code(404);
        }

}
        // GET DETAIL PAGE IN XML
        else if (isset($_GET['Id']) && $accept == "application/xml"){
            $id = $_GET['Id'];

            header("Content-Type: application/xml");

            // db query
            $sql_select = "SELECT Id, Title, Artist, ReleaseDate FROM albums WHERE Id = $id";
            $results = mysqli_query($dbLink, $sql_select);

            $xml = "<?xml version='1.0' encoding='UTF-8'?> <albums>";
            foreach ($results as $album){
                $xml .= "<item>";
                    $xml .= "<Id>".$album["Id"]."</Id>";
                    $xml .= "<Title>".$album["Title"]."</Title>";
                    $xml .= "<Artist>". $album["Artist"]."</Artist>";
                    $xml .= "<ReleaseDate>".$album["ReleaseDate"]."</ReleaseDate>";
                    $xml .= "<links>";
                    $xml .= "<link>";
                        $xml .= "<rel>self</rel>";
                        $xml .= "<href>https://stud.hosted.hr.nl/0892322/webservice/albums/" . $album["Id"]." </href>";
                    $xml .= "</link>";
                    $xml .= "<link>";
                        $xml .= "<rel>collection</rel>";
                        $xml .= "<href>https://stud.hosted.hr.nl/0892322/webservice/albums/ </href>";
                    $xml .= "</link>";
                    $xml .= "</links>";
                $xml.="</item>";
            }
            $xml .= "</albums>";

            echo $xml;
            http_response_code(200);
        }

        // GET JSON
        else if ($accept == "application/json") {

            header("Content-Type: application/json");

            $items = array();

            while ($row = mysqli_fetch_assoc($limitResult)) {

            $links = array();

            $link = array();
            $link["rel"] = "self";
            $link["href"] = "https://stud.hosted.hr.nl/0892322/webservice/albums/" . $row["Id"];
            $linkCollection = array();
            $linkCollection["rel"] = "collection";
            $linkCollection["href"] = "https://stud.hosted.hr.nl/0892322/webservice/albums/";

            array_push($links, $link);
            array_push($links, $linkCollection);

            $row["links"] = $links;

            $items[] = $row;
        }

            // PAGINATION
            $pagination = array(
                "currentPage" => $currentPage,
                "currentItems" => $limit,
                "totalPages" => $pages,
                "totalItems" => $total,
                "links" => array(
                    array("rel" => "first",
                        "page" => 1,
                        "href" => "https://stud.hosted.hr.nl/0892322/webservice/albums?start=1&limit=".$limit),
                    array("rel" => "last",
                        "page" => $lastPage,
                        "href" => "https://stud.hosted.hr.nl/0892322/webservice/albums?start=$lastPage&limit=".$limit),
                    array("rel" => "previous",
                        "page" => $currentPage -1,
                        "href" => "https://stud.hosted.hr.nl/0892322/webservice/albums?start=$previousPage&limit=".$limit),
                    array("rel" => "next",
                        "page" => $currentPage +1,
                        "href" => "https://stud.hosted.hr.nl/0892322/webservice/albums?start=$nextStart&limit=".$limit)
                )
            );

            $links = array();

            $linkcollection = array();
            $linkcollection["rel"] = "self";
            $linkcollection["href"] = "https://stud.hosted.hr.nl/0892322/webservice/albums/";

            array_push($links, $linkcollection);

            $resultArray ['items'] = $items;
            $resultArray ['links'] = $links;
            $resultArray ['pagination'] = $pagination;

        echo json_encode($resultArray);

        http_response_code(200);

        }

        // GET XML
        else if ($accept == "application/xml" ) {

            header("Content-Type: application/xml");

            $xml = "<?xml version='1.0' encoding='UTF-8'?> <albums>";
            foreach ($results as $album){
                $xml .= "<item>";
                    $xml .= "<Id>".$album["Id"]."</Id>";
                    $xml .= "<Title>".$album["Title"]."</Title>";
                    $xml .= "<Artist>". $album["Artist"]."</Artist>";
                    $xml .= "<ReleaseDate>".$album["ReleaseDate"]."</ReleaseDate>";
                    $xml .= "<links>";
                    $xml .= "<link>";
                        $xml .= "<rel>self</rel>";
                        $xml .= "<href>https://stud.hosted.hr.nl/0892322/webservice/albums/" . $album["Id"]. " </href>";
                    $xml .= "</link>";
                    $xml .= "<link>";
                        $xml .= "<rel>collection</rel>";
                        $xml .= "<href>https://stud.hosted.hr.nl/0892322/webservice/albums/ </href>";
                    $xml .= "</link>";
                $xml .= "</links>";
                $xml.="</item>";
            }
            $xml .= "</albums>";

            echo $xml;
            http_response_code(200);

        } else {
            http_response_code(403);
        }

        break;

    case "OPTIONS":

        if (isset($_GET['Id']) == null) {
            header("Allow: GET, POST, OPTIONS");
            exit;
        } else {
            header("Allow: GET, PUT, DELETE, OPTIONS");
            exit;
        }

        break;

    case "POST":
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $content = $_SERVER["CONTENT_TYPE"];

            // JSON
            if ($content == "application/json") {
                http_response_code(201);

                $body = file_get_contents("php://input");
                $json = json_decode($body);

                if ($json->Title == null || $json->Artist == null || $json->ReleaseDate == null) {
                    http_response_code(403);

                } else {
                    $sql = "INSERT INTO albums (Title, Artist, ReleaseDate)
                VALUES ('$json->Title', '$json->Artist', '$json->ReleaseDate')";

                    if ($result = mysqli_query($dbLink, $sql)) {
                        echo "Toegevoegd: ", $json->Title, " van ", $json->Artist, " van het jaar ", $json->ReleaseDate;
                    } else {
                        echo "er ging iets mis!";
                    }
                }

                // X-WWW-FORM-URLENCODED
            }   else if ($content == "application/x-www-form-urlencoded") {
                http_response_code(201);

                if ($_POST["Title"] == null || $_POST["Artist"] == null || $_POST["ReleaseDate"] == null) {
                    http_response_code(403);

                } else {

                    $sql = "INSERT INTO albums (Title, Artist, ReleaseDate)
                VALUES ('" . $_POST["Title"] . "','" . $_POST["Artist"] . "','" . $_POST["ReleaseDate"] . "')";

                    if ($result = mysqli_query($dbLink, $sql)) {
                        echo "Toegevoegd (POST): ", $_POST["Title"], " van ", $_POST["Artist"], " van het jaar ", $_POST["ReleaseDate"];
                    } else {
                        echo "er ging iets mis!";
                    }
                }

            }
            else {
                // content-type not allowed
                http_response_code(405);
            }

        } else {

            // method not allowed
            http_response_code(405);
        }

        break;

    case "PUT": // TODO: Content-type = application/json

        if ($_SERVER["REQUEST_METHOD"] == "PUT") {

            $content = $_SERVER["CONTENT_TYPE"];

            // JSON
            if (isset($_GET['Id']) && $content == "application/json") {
                $id = $_GET['Id'];

                http_response_code(200);

                $body = file_get_contents("php://input");
                $json = json_decode($body);

                if ($json->Title == null || $json->Artist == null || $json->ReleaseDate == null) {
                    http_response_code(403);

                } else {

                    // check if album with given id exists in db
                    $sql = "SELECT Id FROM albums WHERE Id='$id'";
                    $results = mysqli_query($dbLink, $sql);

                    if(mysqli_num_rows($results) >0){
                        //found

                        $sql_update = "UPDATE albums
                    SET Title ='$json->Title', Artist ='$json->Artist', ReleaseDate ='$json->ReleaseDate'
                    WHERE Id ='$id' ";

                        if ($result = mysqli_query($dbLink, $sql_update)) {
                            echo "geupdatet: ", $json->Title, " van ", $json->Artist, " van het jaar ", $json->ReleaseDate;
                        } else {
                            echo "er ging iets mis!";
                        }
                    } else{
                        //not found
                        http_response_code(404);
                    }
                }
            }
            else {
                // content-type not allowed
                http_response_code(405);
            }

        } else {

            // method not allowed
            http_response_code(405);
        }

        break;

    case "DELETE":
        if ($_SERVER["REQUEST_METHOD"] == "DELETE") {

            $content = $_SERVER["CONTENT_TYPE"];

            // JSON
            if (isset($_GET['Id']) || $content == "application/json") {
                $id = $_GET['Id'];

                $body = file_get_contents("php://input");
                $json = json_decode($body);

                $sql_delete = "DELETE FROM albums WHERE Id='$id'";

                if ($result = mysqli_query($dbLink, $sql_delete)) {

                    echo "Album is verwijderd";
                    http_response_code(204);

                } else {
                    echo "er ging iets mis!";
                }
            } else {
                http_response_code(403);
            }

        } else {
            // content-type not allowed
            http_response_code(404);
        }

        break;

        // if REQUEST_METHOD is not POST, GET, PUT, DELETE or OPTIONS
        default: http_response_code(403);
        break;
}










