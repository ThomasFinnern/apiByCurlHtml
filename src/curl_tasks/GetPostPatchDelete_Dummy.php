<?php
//==============================================================================================================
// Examples 

// https://manual.joomla.org/docs/general-concepts/webservices/#using-the-php-curl-functions
// it is below -> Using the PHP cURL Functions

class GetPostPatchDelete
{


// --- GET REQUEST -----------------------------------

    // Retrieve all articles from the "Uncategorized" Category
    public function get_j_articles()
    {
        // Before passing the HTTP METHOD to CURL
        $curl = curl_init();

        $url = 'https://example.org/api/index.php/v1';

        // Put your Joomla! Api token in a safe place, for example a password manager or a vault storing secrets
        // We should not use environment variables to store secrets.
        // Here is why: https://www.trendmicro.com/en_us/research/22/h/analyzing-hidden-danger-of-environment-variables-for-keeping-secrets.html
        $token = '';

        $categoryId = 2; // Joomla's default "Uncategorized" Category

        // HTTP request headers
        $headers = [
            'Accept: application/vnd.api+json',
            'Content-Type: application/json',
            sprintf('X-Joomla-Token: %s', trim($token)),
        ];

        curl_setopt_array($curl, [
                CURLOPT_URL => sprintf('%s/content/articles?filter[category]=%d', $url, $categoryId),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => 'utf-8',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2TLS,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => $headers,
            ]
        );

        $response = curl_exec($curl);
        curl_close($curl);
        echo $response;
    }

// --- GET - Retrieve one specific Article -----------------------------------

    public function get_j_article()
    {
        // Before passing the HTTP METHOD to CURL
        $curl = curl_init();

        $url = 'https://example.org/api/index.php/v1';

        // Put your Joomla! Api token in a safe place, for example a password manager or a vault storing secrets
        // We should not use environment variables to store secrets.
        // Here is why: https://www.trendmicro.com/en_us/research/22/h/analyzing-hidden-danger-of-environment-variables-for-keeping-secrets.html
        $token = '';

        $articleId = 1; // The Article ID of a specific Article

        // HTTP request headers
        $headers = [
            'Accept: application/vnd.api+json',
            'Content-Type: application/json',
            sprintf('X-Joomla-Token: %s', trim($token)),
        ];
        curl_setopt_array($curl, [
                CURLOPT_URL => sprintf('%s/content/articles/%d', $url, $articleId),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => 'utf-8',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2TLS,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => $headers,
            ]
        );

        $response = curl_exec($curl);
        curl_close($curl);
        echo $response;
    }

//--- POST REQUEST ---------------------------------------

    // POST - Create an Article in the Category "Uncategorized" (Category ID = 2)
    public function post_j_article()
    {
        // Before passing the HTTP METHOD to CURL
        $curl = curl_init();

        $url = 'https://example.org/api/index.php/v1';

        // Put your Joomla! Api token in a safe place, for example a password manager or a vault storing secrets
        // We should not use environment variables to store secrets.
        // Here is why: https://www.trendmicro.com/en_us/research/22/h/analyzing-hidden-danger-of-environment-variables-for-keeping-secrets.html
        $token = '';

        $categoryId = 2; // Joomla's default "Uncategorized" Category

        $data = [
            'title' => 'How to add an article to Joomla via the API?',
            'alias' => 'how-to-add-article-via-joomla-api',
            'articletext' => 'I have no idea...',
            'catid' => $categoryId,
            'language' => '*',
            'metadesc' => '',
            'metakey' => '',
        ];

        $dataString = json_encode($data);

        // HTTP request headers
        $headers = [
            'Accept: application/vnd.api+json',
            'Content-Type: application/json',
            'Content-Length: ' . mb_strlen($dataString),
            sprintf('X-Joomla-Token: %s', trim($token)),
        ];

        curl_setopt_array($curl, [
                CURLOPT_URL => sprintf('%s/%s', $url, 'content/articles'),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => 'utf-8',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2TLS,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $dataString,
                CURLOPT_HTTPHEADER => $headers,
            ]
        );

        $response = curl_exec($curl);
        curl_close($curl);
        echo $response;
    }

//--- PATCH REQUEST ---------------------------------------

    // Modify a specific Article
    public function patch_j_article()
    {
        // Before passing the HTTP METHOD to CURL
        $curl = curl_init();

        $url = 'https://example.org/api/index.php/v1';

        // Put your Joomla! Api token in a safe place, for example a password manager or a vault storing secrets
        // We should not use environment variables to store secrets.
        // Here is why: https://www.trendmicro.com/en_us/research/22/h/analyzing-hidden-danger-of-environment-variables-for-keeping-secrets.html
        $token = '';

        $articleId = 1; // The Article ID of a specific Article


        $data = [
            'id' => $articleId,
            'title' => 'How to add an article via the Joomla 4 API?',
            'introtext' => 'When using PATCH, articletext MUST be split into two parts or use at least just introtext in order to work properly',
            'fulltext' => 'MORE CONTENT if you wish',
        ];

        $dataString = json_encode($data);

        // HTTP request headers
        $headers = [
            'Accept: application/vnd.api+json',
            'Content-Type: application/json',
            'Content-Length: ' . mb_strlen($dataString),
            sprintf('X-Joomla-Token: %s', trim($token)),
        ];

        curl_setopt_array($curl, [
                CURLOPT_URL => sprintf('%s/content/articles/%d', $url, $articleId),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => 'utf-8',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2TLS,
                CURLOPT_CUSTOMREQUEST => 'PATCH',
                CURLOPT_POSTFIELDS => $dataString,
                CURLOPT_HTTPHEADER => $headers,
            ]
        );

        $response = curl_exec($curl);
        curl_close($curl);
        echo $response;
    }

//--- DELETE REQUEST ---------------------------------------

    // Remove a specific Article
    public function delete_j_article()
    {
        // Before passing the HTTP METHOD to CURL
        $curl = curl_init();

        $url = 'https://example.org/api/index.php/v1';

        // Put your Joomla! Api token in a safe place, for example a password manager or a vault storing secrets
        // We should not use environment variables to store secrets.
        // Here is why: https://www.trendmicro.com/en_us/research/22/h/analyzing-hidden-danger-of-environment-variables-for-keeping-secrets.html
        $token = '';


        $articleId = 1; // The Article ID of a specific Article


        // HTTP request headers
        $headers = [
            'Accept: application/vnd.api+json',
            'Content-Type: application/json',
            sprintf('X-Joomla-Token: %s', trim($token)),
        ];

        curl_setopt_array($curl, [
                CURLOPT_URL => sprintf('%s/content/articles/%d', $url, $articleId),
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => 'utf-8',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2TLS,
               CURLOPT_CUSTOMREQUEST => 'DELETE',
               CURLOPT_HTTPHEADER => $headers,
            ]
        );

        $response = curl_exec($curl);
        curl_close($curl);
        echo $response;
    }
}

//=======================================================================================
    // https://stackoverflow.com/questions/46578460/how-to-call-an-api-via-php-and-get-a-json-file-from-it


//// --- GET REQUEST -----------------------------------
//
//    $url = 'http://example.com/api/products';
//    $ch = curl_init($url);
//    curl_setopt($ch, CURLOPT_HTTPGET, true);
//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//    $response_json = curl_exec($ch);
//    curl_close($ch);
//    $response=json_decode($response_json, true);



//--- POST REQUEST ---------------------------------------

//    $postdata = array(
//        'name' => 'Arfan'
//    );
//
//    // $url = "https://example.com/api/user/create";
//    // http://127.0.0.1/joomgallery5x_dev/api/index.php/v1/joomgallery
//    $url = '(<span lang="en" dir="ltr" class="mw-content-ltr">http://127.0.0.1/joomla5</span>)/api/index.php/v1/joomgallery';
//    $url = 'http://127.0.0.1/joomgallery5x_dev/api/index.php/v1/joomgallery';
//
//    $curl = curl_init($url);
//    curl_setopt($curl, CURLOPT_HEADER, false);
//    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//    curl_setopt($curl, CURLOPT_POST, true);
//    curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
//
//    $json_response = curl_exec($curl);
//    $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
//    curl_close($curl);
//
//// You can also use file_get_content to get API data.
//
//	$json = file_get_contents("$url")
//

//$ch = curl_init();
//curl_setopt($ch, CURLOPT_POST, false);
//curl_setopt($ch, CURLOPT_URL, $url);
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//$api_response_json = curl_exec($ch);
//curl_close($ch);
////convert json to PHP array for further process
//$api_response_arr = json_decode($api_response_json);
//
//if($api_response_arr['respond'] == true ){
//    //code for success here
//}else{
//    // code for false here
//}
//


















